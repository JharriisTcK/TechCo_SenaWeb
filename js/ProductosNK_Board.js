/*jshint esversion: 6 */
class ProductosNK_Board {
	constructor(nodeObj, dirRaiz, actionUrl, objOptionsIn) {
		"use strict";
		var _this = this;
        this.nodeObj=nodeObj||document.createElement("div");
        this.nodeObj.className="ProductosNK_Board";
		this.dirRaiz = dirRaiz || "";
		this.actionUrl=actionUrl||"NO CONFIGURADO";
		//-----------------------
		objOptionsIn=objOptionsIn||{};
		this.objOptions= {
            valuesSend: objOptionsIn.valuesSend || [],
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif",
            msgGetArea: objOptionsIn.msgGetArea || "Productos",
            msgGetID: objOptionsIn.msgGetID || "All"
		};
        //[Productos, All||Ofertas]
        //[Categorias, All||NickDir]
        //[Marcas, All||NickDir]
        //-----------------------
        this.valueProductos=[];
        this.valueCategorias=[];
        this.valueMarcas=[];
        //-----------------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeHeaderBox.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeaderBox);
        
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        
        this.nodeProductosContainerBox=document.createElement("div");
        this.nodeProductosContainerBox.className="ContainerBox";
        this.nodeObj.appendChild(this.nodeProductosContainerBox);
        
        this.nodeSideControlesBox=document.createElement("div");
        this.nodeSideControlesBox.className="SideControlesBox";
        this.nodeProductosContainerBox.appendChild(this.nodeSideControlesBox);
        
        this.nodeCategoriasBox=document.createElement("div");
        this.nodeCategoriasBox.className="CategoriasBox";
        this.nodeCategoriasLista=document.createElement("ul");
        this.nodeSideControlesBox.appendChild(this.nodeCategoriasBox);
        
        this.nodeMarcasBox=document.createElement("div");
        this.nodeMarcasBox.className="MarcasBox";
        this.nodeMarcasLista=document.createElement("ul");
        this.nodeSideControlesBox.appendChild(this.nodeMarcasBox);
        
        this.nodeOtrosBox=document.createElement("div");
        this.nodeOtrosBox.className="OtrosBox";
        this.nodeSideControlesBox.appendChild(this.nodeOtrosBox);
        
        this.nodeListaBox=document.createElement("div");
        this.nodeListaBox.className="ListaBox";
        // this.nodeListaHeader=document.createElement("div");
        this.nodeLista=document.createElement("ul");
        this.nodeProductosContainerBox.appendChild(this.nodeListaBox);
        //----------------
        this.jxInfo_Get();
    }

    ConfigCategoriasSide() {
        this.nodeCategoriasBox.innerHTML="";
        var nodeMenuTitulo=document.createElement("h2");
        this.nodeCategoriasBox.appendChild(nodeMenuTitulo);
        var nodeMenuTituloA=document.createElement("a");
        nodeMenuTituloA.href=this.dirRaiz+"Categorias/";
        nodeMenuTituloA.innerHTML="Categorias";
        nodeMenuTitulo.appendChild(nodeMenuTituloA);
        var nodeMenu=document.createElement("ul");
        this.nodeCategoriasBox.appendChild(nodeMenu);
        this.nodeCategoriasLista=nodeMenu;
        for (let i = 0; i < this.valueCategorias.length; i++) {
            this.ConfigCategoriaSide(i);
        }
    }

    ConfigCategoriaSide(i) {
        let valueCategoria=this.valueCategorias[i];
        var nnLi=document.createElement("li");
        this.nodeCategoriasLista.appendChild(nnLi);
        var nnA=document.createElement("a");
        nnA.href=this.dirRaiz+valueCategoria.link;
        nnA.innerHTML=valueCategoria.Nombre;
        nnLi.appendChild(nnA);
    }

    ConfigMarcasSide() {
        this.nodeMarcasBox.innerHTML="";
        var nodeMenuTitulo=document.createElement("h2");
        this.nodeMarcasBox.appendChild(nodeMenuTitulo);
        var nodeMenuTituloA=document.createElement("a");
        nodeMenuTituloA.innerHTML="Marcas";
        nodeMenuTituloA.href=this.dirRaiz+"Marcas/";
        nodeMenuTitulo.appendChild(nodeMenuTituloA);
        var nodeMenu=document.createElement("ul");
        this.nodeMarcasBox.appendChild(nodeMenu);
        this.nodeMarcasLista=nodeMenu;
        for (let i = 0; i < this.valueMarcas.length; i++) {
            this.ConfigMarcaSide(i);
        }
    }

    ConfigMarcaSide(i) {
        let valueMarca=this.valueMarcas[i];
        var nnLi=document.createElement("li");
        this.nodeMarcasLista.appendChild(nnLi);
        var nnA=document.createElement("a");
        nnA.href=this.dirRaiz+valueMarca.link;
        nnA.innerHTML=valueMarca.Nombre;
        nnLi.appendChild(nnA);
    }

    ConfigMenuSide() {
        this.ConfigCategoriasSide();
        this.ConfigMarcasSide();
    }

    ConfigProductos() {
        this.nodeListaBox.innerHTML="";
        this.nodeListaHeader=document.createElement("div");
        this.nodeListaHeader.className="ListaHeader";
        this.nodeListaBox.appendChild(this.nodeListaHeader);
        this.nodeLista=document.createElement("ul");
        this.nodeListaBox.appendChild(this.nodeLista);
        if(!this.valueProductos.length) {
            this.ConfigZeroProductos(this.nodeLista);
            return true;
        }
        for(var i=0; i<this.valueProductos.length; i++) {
            this.ConfigProducto(i);
        }
    }
    
    ConfigZeroProductos(nodeAppend) {
        var nnZero=document.createElement("div");
        nnZero.className="imgzero";
        nodeAppend.appendChild(nnZero);
        var nnTextoZero=document.createElement("p");
        nnTextoZero.innerHTML="!Lo sentimos¡<br>No hay productos en este momento";
        nnZero.appendChild(nnTextoZero);
        var nnImg=document.createElement("img");
        nnImg.loading="lazy";
        nnImg.encoding="async";
        nnImg.src=this.dirRaiz + "img/encontstruccion.png";
        nnZero.appendChild(nnImg);
        return true;
    }


    ConfigListaHeader(ListaTipo, ListaTipoID) {
        this.nodeListaHeader.innerHTML="";
        var Titulo="";
        var Descripcion="";
        switch (ListaTipo) {
            case "Categorias":
                Titulo="Categorias";
                Descripcion="Categorias que NeoKiri trabaja actualmente";
            break;

            case "Categoria":
                var CategoriaEncontrado=false;
                var CategoriaItem=null;
                this.valueCategorias.forEach(element => {
                    if(element.NickDir==ListaTipoID) {
                        CategoriaEncontrado=true;
                        CategoriaItem=element;
                        return true;
                    }
                });
                if(!CategoriaEncontrado) {
                    return false;
                }
                Titulo=CategoriaItem.Nombre + " | Categorias";
                Descripcion=CategoriaItem.Descripcion;
            break;

            case "Marcas":
                Titulo="Marcas";
            break;

            case "Marca":
                var MarcaEncontrado=false;
                var MarcaItem=null;
                this.valueMarcas.forEach(element => {
                    if(element.NickDir==ListaTipoID) {
                        MarcaEncontrado=true;
                        MarcaItem=element;
                        return true;
                    }
                });
                if(!MarcaEncontrado) {
                    return false;
                }
                Titulo=MarcaItem.Nombre + " | Marcas";
                Descripcion=MarcaItem.Descripcion;
            break;
        }
        var nnTitulo=document.createElement("h3");
        nnTitulo.innerHTML=Titulo;
        this.nodeListaHeader.appendChild(nnTitulo);
        if(Descripcion) {
            var nnDescripcion=document.createElement("p");
            nnDescripcion.innerHTML=Descripcion;
            this.nodeListaHeader.appendChild(nnDescripcion);
        }
    }

    ConfigProducto(i) {
        var ProductoItem=this.valueProductos[i];
        var nnLi=document.createElement("li");
        nnLi.className="ProductoItem";
        this.nodeLista.appendChild(nnLi);
        var nnLink=document.createElement("a");
        nnLink.href=this.dirRaiz+ProductoItem.link;
        nnLi.appendChild(nnLink);
        if (ProductoItem.PortadaT) {
            var nnPortada=document.createElement("img");
            nnPortada.title=ProductoItem.Nombre;
            nnPortada.loading="lazy";
            nnPortada.encoding="async";
            nnPortada.alt=ProductoItem.Nombre;
            nnPortada.src=this.dirRaiz+ProductoItem.PortadaT;
            nnPortada.classList.add("cssnk-gradientanimation");
            nnLink.appendChild(nnPortada);
        }
        var nnNombre=document.createElement("b");
        nnNombre.className="ProductoNombre";
        nnNombre.innerHTML=ProductoItem.Nombre;
        nnLink.appendChild(nnNombre);
        // ---------
        var nnInfoBox=document.createElement("div");
        nnLi.appendChild(nnInfoBox);
        // ---------
        let precio = ProductoItem.PrecioFinal;
        let options = { style: 'currency', currency: 'COP', minimumFractionDigits: 0 };
        let precioformateado = precio.toLocaleString('es-CO', options);
        var nnPrecio=document.createElement("div");
        nnPrecio.className="ProductoPrecio";
        nnPrecio.innerHTML=precioformateado+" COP";
        nnInfoBox.appendChild(nnPrecio);
        // ---------
        var nnCategoria=document.createElement("div");
        nnCategoria.innerHTML=ProductoItem.CategoriaNombre;
        nnInfoBox.appendChild(nnCategoria);
        // ---------
        var nnControles=document.createElement("div");
        nnInfoBox.appendChild(nnControles);
        // var nnControl_Carrito=document.createElement("button");
        // nnControl_Carrito.innerHTML="Carrito";
        // nnControles.appendChild(nnControl_Carrito)
    }

    ConfigCategorias() {
        this.nodeListaBox.innerHTML="";
        this.nodeListaHeader=document.createElement("div");
        this.nodeListaHeader.className="ListaHeader";
        this.nodeListaBox.appendChild(this.nodeListaHeader);
        this.nodeLista=document.createElement("ul");
        this.nodeListaBox.appendChild(this.nodeLista);
        if(this.valueCategorias.length<1) {
            this.ConfigZeroProductos(this.nodeLista);
            return true;
        }
        for(var i=0; i<this.valueCategorias.length; i++) {
            this.ConfigCategoria(i);
        }
    }
    ConfigCategoria(i) {
        var valueCategoria=this.valueCategorias[i];
        var nnLi=document.createElement("li");
        nnLi.className="CategoriaItem";
        this.nodeLista.appendChild(nnLi);
        var nnLink=document.createElement("a");
        nnLink.href=valueCategoria.NickDir+"/";
        nnLi.appendChild(nnLink);
        if(valueCategoria.PortadaS) {
            var nnPortada=document.createElement("img");
            nnPortada.src=this.dirRaiz + valueCategoria.PortadaS;
            nnLink.appendChild(nnPortada);
        }
        var nnNombre=document.createElement("b");
        nnNombre.innerHTML=valueCategoria.Nombre;
        nnLink.appendChild(nnNombre);
    }

    ConfigMarcas() {
        this.nodeListaBox.innerHTML="";
        this.nodeListaHeader=document.createElement("div");
        this.nodeListaHeader.className="ListaHeader";
        this.nodeListaBox.appendChild(this.nodeListaHeader);
        this.nodeLista=document.createElement("ul");
        this.nodeListaBox.appendChild(this.nodeLista);
        if(this.valueMarcas.length<1) {
            this.ConfigZeroProductos(this.nodeLista);
            return true;
        }
        for(var i=0; i<this.valueMarcas.length; i++) {
            this.ConfigMarca(i);
        }
    }

    ConfigMarca(i) {
        var valueMarca=this.valueMarcas[i];
        var nnLi=document.createElement("li");
        nnLi.className="MarcaItem";
        this.nodeLista.appendChild(nnLi);
        var nnLink=document.createElement("a");
        nnLink.href=valueMarca.NickDir+"/";
        nnLi.appendChild(nnLink);
        if(valueMarca.LogoS) {
            var nnLogo=document.createElement("img");
            nnLogo.src=this.dirRaiz + valueMarca.LogoS;
            nnLink.appendChild(nnLogo);
        }
        var nnNombre=document.createElement("b");
        nnNombre.innerHTML=valueMarca.Nombre;
        nnLink.appendChild(nnNombre);
    }


    jxInfo_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.loading="lazy";
        imgLoading.decoding="async";
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // ------------------------
		var fd="?ProductosNK_Board=ProductosGet";
		fd+="&KeyJX="+this.KeyJX;
		fd+="&ObjArea="+this.objOptions.msgGetArea;
		fd+="&ObjID="+this.objOptions.msgGetID;
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd+="&"+this.objOptions.valuesSend[i][0]+"="+this.objOptions.valuesSend[i][1];
		}
		fetch(this.actionUrl+fd)
		.then(resp=>resp.json())
		.then(data=>{
            console.info("ProductosNK_Board::jxInfo_Get()");
            console.log(data);
			if(data.RespuestaBool) {
                this.valueProductos = data.Productos;
                this.valueCategorias = data.Categorias;
                this.valueMarcas = data.Marcas;
                this.ConfigMenuSide();
                switch (this.objOptions.msgGetArea) {
                    case "Productos":
                        if (this.objOptions.msgGetID=="Ofertas") {
                        } else {
                            this.ConfigProductos();
                        }
                    break;
                    
                    case "Categorias":
                        if (this.objOptions.msgGetID=="All") {
                            this.ConfigCategorias();
                            this.ConfigListaHeader("Categorias", "All");
                        } else {
                            this.ConfigProductos();
                            this.ConfigListaHeader("Categoria", this.objOptions.msgGetID);
                        }
                    break;
                    
                    case "Marcas":
                        if (this.objOptions.msgGetID=="All") {
                            this.ConfigMarcas();
                            this.ConfigListaHeader("Marcas", "All");
                        } else {
                            this.ConfigProductos();
                            this.ConfigListaHeader("Marca", this.objOptions.msgGetID);
                        }
                    break;
                }
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