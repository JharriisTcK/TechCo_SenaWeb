class ProductosNK_Admin {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosNK_Admin";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Productos NK - Administrador",
            valuesSend: objOptionsIn.valuesSend || [],
            loadingImg: objOptionsIn.loadingImg || "img/loading.gif"
        };
        //----------------
        this.valueProductos=[];
        this.valueCategorias=[];
        this.valueMarcas=[];
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeHeaderBox.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeaderBox);
        
        this.nodeFormCategoriasBox=document.createElement("div");
        this.nodeFormCategoriasBox.className="FormCategoriasBox";
        this.nodeObj.appendChild(this.nodeFormCategoriasBox);
        
        this.nodeCategoriasBox=document.createElement("div");
        this.nodeCategoriasBox.className="CategoriasBox";
        this.nodeObj.appendChild(this.nodeCategoriasBox);
        
        this.nodeFormProductosBox=document.createElement("div");
        this.nodeFormProductosBox.className="FormProductosBox";
        this.nodeObj.appendChild(this.nodeFormProductosBox);
        
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        
        this.nodeListaDeshabilitadoBox=document.createElement("div");
        this.nodeListaDeshabilitadoBox.className="DeshabilitadosBox";
        this.nodeObj.appendChild(this.nodeListaDeshabilitadoBox);
        
        this.nodeListaBox=document.createElement("div");
        this.nodeListaBox.className="ListaBox";
        this.nodeObj.appendChild(this.nodeListaBox);
        //----------------
        // this.ConfigProductosForm();
        this.ConfigHeader();
        this.jxInfo_Get();
    }

    ConfigHeader() {
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Productos | Administrador";
        this.nodeHeaderBox.appendChild(nnTitulo);
    }

    ConfigProductosForm() {
        this.nodeFormProductosBox.innerHTML="";
        // ------------------------
        if(this.valueCategorias.length<1) {
            this.nodeFormProductosBox.innerHTML="Sin categorias definidas no se permiten crear nuevos productos";
            return false;
        }
        if(this.valueMarcas.length<1) {
            this.nodeFormProductosBox.innerHTML="Sin Marcas definidas no se permiten crear nuevos productos";
            return false;
        }
        // ------------------------
        var _this=this;
        var form=new FormNK(this.nodeFormProductosBox, this.urlAction, "", this.dirRaiz, {
            valuesSend: [["ProductosNK_Admin", "Producto_Add"]]
        });
        form.setTexto("Nombre: ", "ProductoNombre", "", true, {
            descripcion: "Nombre del nuevo producto"
        })

        form.setTexto("Codigo: ", "ProductoCodigo", "", true, {})
        form.setSelect("Categoria: ", "ProductoCategoria", this.valueCategorias, {})
        form.setSelect("Marca: ", "ProductoMarca", this.valueMarcas, {})
        form.finalizar("Registrar Producto");
        form.setCallbackSubmit(function(){
            _this.jxInfo_Get();
        });
    }

    ConfigProductos() {
        this.nodeListaBox.innerHTML="";
        this.nodeListaDeshabilitadoBox.innerHTML="";
        var nnLista=document.createElement("ul");
        this.nodeListaBox.appendChild(nnLista);
        var nnListaDeshabilitados=document.createElement("ul");
        this.nodeListaDeshabilitadoBox.appendChild(nnListaDeshabilitados);
        for (var i=0; i<this.valueProductos.length;i++) {
            this.ConfigProductos_Item(i, nnLista);
        }
    }

    ConfigProductos_Item(i, nodeLista) {
        var _this=this;
        var ProductoItem=this.valueProductos[i];
        var nnLi=document.createElement("li");
        nnLi.className="ProductoItem";
        nodeLista.appendChild(nnLi);
        var nnHeaderItem=document.createElement("a");
        nnHeaderItem.href=this.dirRaiz+ProductoItem.link;
        nnHeaderItem.target="_BLANK";
        nnLi.appendChild(nnHeaderItem);
        if(ProductoItem.PortadaT) {
            var nnPortadaItem=document.createElement("img");
            nnPortadaItem.src=this.dirRaiz + ProductoItem.PortadaT;
            nnHeaderItem.appendChild(nnPortadaItem);
        }
        var nnNombre=document.createElement("b");
        nnNombre.innerHTML=ProductoItem.Nombre;
        nnHeaderItem.appendChild(nnNombre);
        var nnControles=document.createElement("div");
        nnLi.appendChild(nnControles);
        // ---------
        var nnControl_Habilitar=document.createElement("button");
        nnLi.appendChild(nnControl_Habilitar);
        if (ProductoItem.Habilitado) {
            nnControl_Habilitar.innerHTML="Deshablitar";
        } else {
            nnControl_Habilitar.innerHTML="Hablitar";
        }       

        nnControl_Habilitar.addEventListener("click", function(e) {
            _this.jxProducto_Habilitar(i);
        }, false);
        // ---------
        var nnControl_Editar=document.createElement("button");
        nnControl_Editar.innerHTML="Editar";
        nnLi.appendChild(nnControl_Editar);
        nnControl_Editar.addEventListener("click", function(e) {
            _this.jxProducto_Edit(i);
        }, false);
        // ---------
        var nnControl_Eliminar=document.createElement("button");
        nnControl_Eliminar.innerHTML="Eliminar";
        nnLi.appendChild(nnControl_Eliminar);
        nnControl_Eliminar.addEventListener("click", function(e) {
            _this.jxProducto_Del(i);
        }, false);
    }


    jxInfo_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_Admin", "Info_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_Admin::jxInfo_Get()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.valueProductos = data.Productos;
                this.valueCategorias = data.Categorias;
                this.valueMarcas = data.Marcas;
				this.ConfigProductosForm();
                this.ConfigProductos();
			} else {
				this.ConfigProductosForm();
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxProducto_Habilitar(i) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("ProductosNK_Admin", "Producto_Habilitar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoID", this.valueProductos[i].ProductoID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_Admin::jxProducto_Habilitar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.jxInfo_Get();
			} else {
            }
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxProducto_Edit(i) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // -------
        var ProductoID=this.valueProductos[i].ProductoID;
        // -------
		globalThis.location="ProductoEdit.php?ID="+ProductoID;
	}

    jxProducto_Del(i) {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("ProductosNK_Admin", "Producto_Del");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoID", this.valueProductos[i].ProductoID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_Admin::jxProducto_Del()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.jxInfo_Get();
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

class ProductosCategoriasNK_Admin {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosCategoriasNK_Admin";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Productos NK - Administrador",
            valuesSend: objOptionsIn.valuesSend || [],
            LoadingImg: objOptionsIn.LoadingImg || "img/loading.gif"
        };
        //----------------
        this.valueCategorias=[];
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeHeaderBox.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeaderBox);
        this.nodeFormBox=document.createElement("div");
        this.nodeFormBox.className="FormBox";
        this.nodeObj.appendChild(this.nodeFormBox);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodePublicosBox=document.createElement("div");
        this.nodePublicosBox.className="PublicosBox";
        this.nodeObj.appendChild(this.nodePublicosBox);
        this.nodeNoPublicosBox=document.createElement("div");
        this.nodeNoPublicosBox.className="NoPublicosBox";
        this.nodeObj.appendChild(this.nodeNoPublicosBox);
        //----------------
        this.ConfigHeader();
        this.ConfigForm();
        this.jxCategorias_Get();
    }

    ConfigHeader() {
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Categorias | Productos Administrador";
        this.nodeHeaderBox.appendChild(nnTitulo);
    }

    ConfigForm() {
        var _this=this;
        this.nodeFormBox.innerHTML="";
        var nnForm=document.createElement("form");
        this.nodeFormBox.appendChild(nnForm);
        var nnTextoLabel=document.createElement("label");
        nnForm.appendChild(nnTextoLabel);
        var nnTextoB=document.createElement("b");
        nnTextoB.innerHTML="Nombre Categoria: "
        nnTextoLabel.appendChild(nnTextoB);
        var nnTextoInput=document.createElement("Input");
        nnTextoInput.type="text";
        nnTextoInput.required="required";
        nnTextoLabel.appendChild(nnTextoInput);
        var nnSubmit=document.createElement("Input");
        nnSubmit.type="submit";
        nnSubmit.value="Añadir";
        nnTextoLabel.appendChild(nnSubmit);
        nnForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (!nnTextoInput.value.length) {
                return false;
            }
            _this.jxCategoria_Add(nnTextoInput.value);
        }, false);

    }

    ConfigCategorias() {
        this.nodePublicosBox.innerHTML="";
        var nnLista=document.createElement("ul");
        this.nodePublicosBox.appendChild(nnLista);
        for(var i=0; i<this.valueCategorias.length;i++) {
            this.ConfigCategorias_Item(nnLista, i);
        }
    }


    ConfigCategorias_Item(nnLista, i) {
        var _this=this;
        var valueItem=this.valueCategorias[i];
        var nnLi=document.createElement("li");
        nnLista.appendChild(nnLi);
        if(valueItem.PortadaS) {
            var nnPortada=document.createElement("img");
            nnPortada.src=this.dirRaiz+valueItem.PortadaS;
            nnLi.appendChild(nnPortada);
        }
        var nnNombre=document.createElement("div");
        nnNombre.innerHTML=valueItem.Nombre;
        nnLi.appendChild(nnNombre);
        var nnControles=document.createElement("div");
        nnControles.className="ControlesBox";
        nnLi.appendChild(nnControles);
        var nnControl_Editar=document.createElement("button");
        nnControl_Editar.innerHTML="Editar";
        nnControles.appendChild(nnControl_Editar);
        var nnControl_Habilitar=document.createElement("button");
        nnControl_Habilitar.innerHTML="Habilitar";
        nnControles.appendChild(nnControl_Habilitar);
        var nnControl_Eliminar=document.createElement("button");
        nnControl_Eliminar.innerHTML="Eliminar";
        nnControles.appendChild(nnControl_Eliminar);
        // -------------------
        if(valueItem.Habilitado) {
            nnControl_Habilitar.innerHTML="Deshabilitar";
        }
        // -------------------
        nnControl_Editar.addEventListener("click", function(e) {
            _this.ConfigCategorias_Item_EditarConfig(i);
        }, true)
        nnControl_Habilitar.addEventListener("click", function(e) {
            _this.jxCategoria_Habilitar(i);
        }, true)
        nnControl_Eliminar.addEventListener("click", function(e) {
            _this.jxCategoria_Del(i);
        }, true)
    }


    ConfigCategorias_Item_EditarConfig(i) {
        var valueItem=this.valueCategorias[i];
        // globalThis.open("ProductoCategoriaEdit.php?ID="+valueItem.ProductoCategoriaID);
        globalThis.location="ProductoCategoriaEdit.php?ID="+valueItem.ProductoCategoriaID;
    }

    ConfigCategoriasForm() {
        this.nodeFormCategoriasBox.innerHTML="";
        new TextoListaNK(this.nodeFormCategoriasBox, this.urlAction, this.KeyJX, this.dirRaiz, {
            valuesSend: [["ProductosNK_Admin", "CategoriaAdd"]]
        });
    }

    jxCategorias_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_CategoriasAdmin", "Categorias_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_CategoriasAdmin::jxCategorias_Get()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.valueCategorias = data.ProductosCategorias;
                this.ConfigCategorias();
			} else {
                
            }
            this.nodeStatusBox.innerHTML="";
            this.ConfigForm();
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxCategoria_Add(NombreCategoria) {
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_CategoriasAdmin", "Categoria_Add");
		fd.append("KeyJX", this.KeyJX);
		fd.append("NombreCategoria", NombreCategoria);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_CategoriasAdmin::jxTexto_Add()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(data);
                return false;
            }
            console.log(data);
			if(data.RespuestaBool) {
				this.jxCategorias_Get();
			} else {
				
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxCategoria_Habilitar(i) {
		"use strict";
        var ProductoCategoriaID=this.valueCategorias[i].ProductoCategoriaID;
        this.nodeStatusBox.innerHTML="";
        // ------------------------------
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // ------------------------------
		var fd=new FormData();
		fd.append("ProductosNK_CategoriasAdmin", "Categoria_Habilitar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoCategoriaID", ProductoCategoriaID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_CategoriasAdmin::jxCategoria_Habilitar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.nodeStatusBox.innerHTML="";
                this.jxCategorias_Get();
			} else {
				
			}
            this.nodeStatusBox.innerHTML="";
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxCategoria_Del(i) {
		"use strict";
        var CategoriaID=this.valueCategorias[i].ProductoCategoriaID;
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_CategoriasAdmin", "Categoria_Del");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoCategoriaID", CategoriaID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("ProductosNK_CategoriasAdmin::jxCategoria_Del()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
				this.jxCategorias_Get();
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

class ProductosMarcasNK_Admin {
    constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        "use strict";
        var _this = this;
        this.nodeObj=nodeIn||document.createElement("div");
        this.nodeObj.innerHTML="";
        this.nodeObj.className = "ProductosMarcasNK_Admin";

        this.dirRaiz=dirRaiz;       
        this.urlAction=urlAction;
        this.KeyJX=KeyJX; 
        
        //----------------
        objOptionsIn = objOptionsIn || {};
        this.objOptions = {
            Titulo: objOptionsIn.Titulo||"Productos Marcas NK - Administrador",
            valuesSend: objOptionsIn.valuesSend || [],
            LoadingImg: objOptionsIn.LoadingImg || "img/loading.gif"
        };
        //----------------
        this.valueMarcas=[];
        //----------------
        this.nodeHeaderBox=document.createElement("div");
        this.nodeHeaderBox.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeaderBox);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeFormBox=document.createElement("div");
        this.nodeFormBox.className="FormBox";
        this.nodeObj.appendChild(this.nodeFormBox);
        this.nodePublicosBox=document.createElement("div");
        this.nodePublicosBox.className="PublicosBox";
        this.nodeObj.appendChild(this.nodePublicosBox);
        this.nodePrivadosBox=document.createElement("div");
        this.nodePrivadosBox.className="PrivadosBox";
        this.nodeObj.appendChild(this.nodePrivadosBox);
        //----------------
        this.ConfigHeader();
        this.ConfigForm();
        this.jxMarcas_Get();
    }

    ConfigHeader() {
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Marcas | Productos Administrador";
        this.nodeHeaderBox.appendChild(nnTitulo);
    }

    ConfigForm() {
        var _this=this;
        this.nodeFormBox.innerHTML="";
        var nnForm=document.createElement("form");
        this.nodeFormBox.appendChild(nnForm);
        var nnTextoLabel=document.createElement("label");
        nnForm.appendChild(nnTextoLabel);
        var nnTextoB=document.createElement("b");
        nnTextoB.innerHTML="Nombre Marca: "
        nnTextoLabel.appendChild(nnTextoB);
        var nnTextoInput=document.createElement("Input");
        nnTextoInput.type="text";
        nnTextoInput.required="required";
        nnTextoLabel.appendChild(nnTextoInput);
        var nnSubmit=document.createElement("Input");
        nnSubmit.type="submit";
        nnSubmit.value="Añadir";
        nnTextoLabel.appendChild(nnSubmit);
        nnForm.addEventListener("submit", function(e) {
            e.preventDefault();
            if (!nnTextoInput.value.length) {
                return false;
            }
            _this.jxMarca_Add(nnTextoInput.value);
        }, false);

    }

    ConfigMarcas() {
        this.nodePublicosBox.innerHTML="";
        var nnLista=document.createElement("ul");
        this.nodePublicosBox.appendChild(nnLista);
        for(var i=0; i<this.valueMarcas.length;i++) {
            this.ConfigMarca_Item(nnLista, i);
        }
    }


    ConfigMarca_Item(nnLista, i) {
        var _this=this;
        var valueItem=this.valueMarcas[i];
        var nnLi=document.createElement("li");
        nnLista.appendChild(nnLi);
        var nnNombreA=document.createElement("a");
        nnNombreA.href=this.dirRaiz+"Marcas/"+valueItem.NickDir+"/";
        nnNombreA.target="_BLANK";
        nnLi.appendChild(nnNombreA);
        var nnMarcaImg=document.createElement("img");
        nnMarcaImg.src=this.dirRaiz+valueItem.LogoS;
        nnNombreA.appendChild(nnMarcaImg);
        var nnNombre=document.createElement("b");
        nnNombre.innerHTML=valueItem.Nombre;
        nnNombreA.appendChild(nnNombre);
        // -------------------
        var nnControles=document.createElement("div");
        nnControles.className="ControlesBox";
        nnLi.appendChild(nnControles);
        var nnControl_Editar=document.createElement("button");
        nnControl_Editar.innerHTML="Editar";
        nnControles.appendChild(nnControl_Editar);
        var nnControl_Habilitar=document.createElement("button");
        nnControl_Habilitar.innerHTML="Habilitar";
        nnControles.appendChild(nnControl_Habilitar);
        var nnControl_Eliminar=document.createElement("button");
        nnControl_Eliminar.innerHTML="Eliminar";
        nnControles.appendChild(nnControl_Eliminar);
        // -------------------
        if(valueItem.Habilitado) {
            nnControl_Habilitar.innerHTML="Deshabilitar";
        }
        // -------------------
        nnControl_Editar.addEventListener("click", function(e) {
            _this.ConfigCategorias_Item_EditarConfig(i);
        }, true)
        nnControl_Habilitar.addEventListener("click", function(e) {
            _this.jxMarca_Habilitar(i);
        }, true)
        nnControl_Eliminar.addEventListener("click", function(e) {
            _this.jxMarca_Del(i);
        }, true)
    }


    ConfigCategorias_Item_EditarConfig(i) {
        var valueItem=this.valueMarcas[i];
        // this.nodeEditFormBox.innerHTML="";
        // this.nodeEditFormBox.innerHTML=valueItem.Nombre;
        globalThis.location="ProductoMarcaEdit.php?ID="+valueItem.ProductoMarcaID;
    }

    ConfigCategoriasForm() {
        this.nodeFormCategoriasBox.innerHTML="";
        new TextoListaNK(this.nodeFormCategoriasBox, this.urlAction, this.KeyJX, this.dirRaiz, {
            valuesSend: [["ProductosNK_Admin", "CategoriaAdd"]]
        });
    }

    jxMarcas_Get() {
		"use strict";
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_MarcasAdmin", "Marcas_Get");
		fd.append("KeyJX", this.KeyJX);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("ProductosNK_MarcasAdmin::jxMarcas_Get()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            console.log(data);
			if(data.RespuestaBool) {
                this.valueMarcas = data.Marcas;
                this.ConfigMarcas();
			} else {
                
            }
            this.ConfigForm();
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxMarca_Add(NombreMarca) {
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_MarcasAdmin", "Marca_Add");
		fd.append("KeyJX", this.KeyJX);
		fd.append("MarcaNombre", NombreMarca);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("ProductosNK_MarcasAdmin::jxMarca_Add()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(data);
                return false;
            }
            console.log(data);
			if(data.RespuestaBool) {
				this.jxMarcas_Get();
			} else {
				
			}
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxMarca_Habilitar(i) {
		"use strict";
        var ProductoMarcaID=this.valueMarcas[i].ProductoMarcaID;
        this.nodeStatusBox.innerHTML="";
        // ------------------------------
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
        // ------------------------------
		var fd=new FormData();
		fd.append("ProductosNK_MarcasAdmin", "Marca_Habilitar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoMarcaID", ProductoMarcaID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // ------------------------------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("ProductosNK_MarcasAdmin::jxMarca_Habilitar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.nodeStatusBox.innerHTML="";
                this.jxMarcas_Get();
			} else {
				
			}
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}

    jxMarca_Del(i) {
		"use strict";
        var MarcaID=this.valueMarcas[i].ProductoMarcaID;
        this.nodeStatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.LoadingImg;
        this.nodeStatusBox.appendChild(imgLoading);
		var fd=new FormData();
		fd.append("ProductosNK_MarcasAdmin", "Marca_Eliminar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ProductoMarcaID", MarcaID);
        for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("ProductosNK_MarcasAdmin::jxMarca_Del()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.jxMarcas_Get();
			} else {
                this.nodeStatusBox.innerHTML=data.RespuestaError;
			}
		})
		.catch(err=>{
			this.nodeStatusBox.innerHTML="Creo que hay un error en el sistema, por favor informanos:<br/>Error: "+err;
			console.error(err);
		});
	}
}