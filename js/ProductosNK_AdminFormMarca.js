class ProductosNK_AdminFormMarca {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosNK_AdminFormMarca";

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
        this.valueMarca={};
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeHeaderBox);
        this.nodeStatusBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeFormMarcaBox=document.createElement("div");
        this.nodeObj.appendChild(this.nodeFormMarcaBox);
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
        this.nodeFormMarcaBox.innerHTML="";
        var _this=this;
        var form=new FormNK(this.nodeFormMarcaBox, this.urlAction, this.KeyJX, this.dirRaiz, {
            valuesSend: [
                ["ProductosNK_MarcaAdminForm", "Marca_Edit"],
                ["KeyJX", this.KeyJX]
            ]
        });
        form.setTexto("Nombre: ", "MarcaNombre", this.valueMarca.Nombre, true, {
            descripcion: "Nombre de la nueva marca"
        })
        form.setTexto("NickDir: ", "MarcaNickDir", this.valueMarca.NickDir, true, {
            descripcion: "Direccion unica de enlace"
        })
        form.setTextarea("Descripcion: ", "MarcaDescripcion", this.valueMarca.Descripcion, false, {});
        form.finalizar("Editar Info Basica de Marca");
        form.setCallbackSubmit(function(){
            // _this.jxInfo_Get();
        });
    }

    ConfigPortada() {
        this.nodePortada.innerHTML="";
        new FileImageNK(this.nodePortada, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_MarcaAdminForm", "LogoIMG"]], titulo: "Logo de la Marca"});
    }
    ConfigPortadaFB() {
        this.nodePortadaFB.innerHTML="";
        new FileImageNK(this.nodePortadaFB, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_MarcaAdminForm", "PortadaFB"]], titulo: "Portada Facebook de la Marca", w: 1200, h: 630});
    }
    ConfigContenido() {
        new TextareaRichNK(this.nodeContenido, this.dirRaiz+"FrameFormNK.php", this.urlAction, this.KeyJX, this.dirRaiz, {
			Titulo: "Contenido de la Marca",
            valuesSend: [["ProductosNK_MarcaAdminForm", "MarcaContenido"]]
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
		fd.append("ProductosNK_MarcaAdminForm", "Info_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_AdminFormMarca::jxInfo_Get()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.valueMarca = data.Marca;
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