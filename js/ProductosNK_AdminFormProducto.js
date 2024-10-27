class ProductosNK_AdminFormProducto {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosNK_AdminForm";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Texto Lista NeoKiri",
            valuesSend: objOptionsIn.valuesSend || [],
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
        };
        //----------------
        this.valueProducto={};
        this.valueCategorias=[];
        this.valueMarcas=[];
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
        var form=new FormNK(this.nodeFormProductosBox, this.urlAction, this.valueProducto.ProductoID, this.dirRaiz, {
            valuesSend: [
                ["ProductosNK_AdminFormProducto", "Producto_Edit"],
                ["ProductoID", this.KeyJX]
            ]
        });
        form.setTexto("Nombre: ", "ProductoNombre", this.valueProducto.Nombre, true, {
            descripcion: "Nombre del nuevo producto"
        })

        form.setTexto("Codigo: ", "ProductoCodigo", this.valueProducto.ProductoCodeID, true, {})
        form.setSelect("Categoria: ", "ProductoCategoria", this.valueCategorias, {})
        form.setSelect("Marca: ", "ProductoMarca", this.valueMarcas, {})
        form.setTexto("Nick-Dir: ", "NickDir", this.valueProducto.ProductoNickDir, true, {})
        form.setTextarea("Descripcion: ", "Descripcion", this.valueProducto.Descripcion, false, {});
        form.setNumero("PrecioDistribuidor: ", "PrecioDistribuidor", this.valueProducto.PrecioDistribuidor, false, {})
        form.setNumero("Precio Final: ", "PrecioFinal", this.valueProducto.PrecioFinal, false, {})
        form.setNumero("Precio Oferta: ", "PrecioFinalOferta", this.valueProducto.PrecioFinalOferta, false, {})
        form.setTexto("Disponibles: ", "Disponibles", this.valueProducto.Disponibles, false, {})
        form.finalizar("Editar Producto");
        form.setCallbackSubmit(function(){
            // _this.jxInfo_Get();
        });
    }

    ConfigPortada() {
        this.nodePortada.innerHTML="";
        new FileImageNK(this.nodePortada, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_AdminFormProducto", "PortadaIMG"]], titulo: "Portada del Producto"});

    }
    ConfigPortadaFB() {
        this.nodePortadaFB.innerHTML="";
        new FileImageNK(this.nodePortadaFB, this.KeyJX, this.urlAction, this.dirRaiz, {valuesSend:[["ProductosNK_AdminFormProducto", "PortadaFB"]], titulo: "Portada Facebook del Producto", w: 1200, h: 630});
    }
    ConfigContenido() {
        new TextareaRichNK(this.nodeContenido, this.dirRaiz+"FrameFormNK.php", this.urlAction, this.valueProducto.ProductoID, this.dirRaiz, {
			Titulo: "Contenido del Producto",
            valuesSend: [["ProductosNK_AdminFormProducto", "ProductoContenido"]]
        });
    }

    jxInfo_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_AdminFormProducto", "Info_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_AdminFormProducto::jxInfo_Get()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                if(!data.Categorias) {
                    console.warn("Sin Categorias no se puede editar producto");
                    return false;
                }
                if(!data.Marcas) {
                    console.warn("Sin Marcas no se puede editar producto");
                    return false;
                }
                this.valueProducto = data.Producto;
                this.valueCategorias = data.Categorias;
                this.valueMarcas = data.Marcas;
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