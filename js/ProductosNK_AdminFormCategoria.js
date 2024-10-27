class ProductosNK_AdminFormCategoria {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosNK_AdminCategoriaForm";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Admin Form Producto Categoria - NeoKiri",
            valuesSend: objOptionsIn.valuesSend || [],
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
        };
        //----------------
        this.valueCategoria={};
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeHeaderBox);
        this.nodeStatusBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeFormProductosBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeFormProductosBox);
        this.nodePortada=document.createElement("div");
        this.nodeObj.appendChild(this.nodePortada);
        this.nodePortadaFB=document.createElement("div");
        this.nodeObj.appendChild(this.nodePortadaFB);
        this.nodeContenido=document.createElement("div");
        this.nodeObj.appendChild(this.nodeContenido);
        //----------------
        this.jxInfo_Get();
    }

    ConfigInfo() {
        this.nodeFormProductosBox.innerHTML="";
        var _this=this;
        var form=new FormNK(this.nodeFormProductosBox, this.urlAction, this.KeyJX, this.dirRaiz, {
            valuesSend: [
                ["ProductosNK_CategoriaAdminForm", "Categoria_Edit"],
                ["KeyJX", this.KeyJX]
            ]
        });
        form.setTexto("Nombre: ", "CategoriaNombre", this.valueCategoria.Nombre, true, {
            descripcion: "Nombre del nuevo producto"
        })
        form.setTextarea("Descripcion: ", "CategoriaDescripcion", this.valueCategoria.Descripcion, false, {});
        form.setSelect("Asignar Tabla SQL: ", "CategoriaTabla", [], {})
        form.finalizar("Editar Categoria");
        form.setCallbackSubmit(function(){
            // _this.jxInfo_Get();
        });
    }

    ConfigPortada() {
        this.nodePortada.innerHTML="";
        new FileImageNK(this.nodePortada, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_CategoriaAdminForm", "PortadaIMG"]], titulo: "Portada de la Categoria"});

    }
    ConfigPortadaFB() {
        this.nodePortadaFB.innerHTML="";
        new FileImageNK(this.nodePortadaFB, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_CategoriaAdminForm", "PortadaFB"]], titulo: "Portada Facebook de la Categoria", w: 1200, h: 630});
    }
    ConfigContenido() {
        new TextareaRichNK(this.nodeContenido, this.dirRaiz+"FrameFormNK.php", this.urlAction, this.KeyJX, this.dirRaiz, {
			Titulo: "Contenido de la Categoria",
            valuesSend: [["ProductosNK_CategoriaAdminForm", "CategoriaContenido"]]
        });
    }

    jxInfo_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        imgLoading.loading="lazy";
        imgLoading.encoding="async";
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_CategoriaAdminForm", "Info_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_AdminFormCategoria::jxInfo_Get()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.valueCategoria = data.Categoria;
                this.ConfigInfo();
                this.ConfigPortada();
                this.ConfigPortadaFB();
                this.ConfigContenido();
			} else {
                
            }
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
            this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}
}