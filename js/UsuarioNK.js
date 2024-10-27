/*jshint esversion: 6 */
class UsuarioNK  {
	constructor(nodeObj, urlAction, dirRaiz, objOptionsIn) {
        this.urlAction=urlAction;
        this.KeyJX="";
        this.dirRaiz=dirRaiz||"";
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend: objOptionsIn.valuesSend||[],
            mostrarPublicados: objOptionsIn.mostrarPublicados || true,
            mostrarFormulario: objOptionsIn.mostrarFormulario || true,
            loadingImg: objOptionsIn.loadingImg || dirRaiz+"img/loading.gif",
            urlActionPublicaciones: objOptionsIn.urlActionPublicaciones || this.urlAction
        };
        //--------------------------
        this.nodeObj=nodeObj || document.createElement("div");
        this.nodeObj.className="UsuarioNK";
        this.nodeObj.innerHTML="";
        this.nodeHeader=document.createElement("div");
        this.nodeHeader.className="UsuarioNK_HeaderBox cssnk-gradientanimation";
        this.nodeObj.appendChild(this.nodeHeader);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeContainerBox=document.createElement("div");
        this.nodeContainerBox.className="ContainerBox";
        this.nodeObj.appendChild(this.nodeContainerBox);
        this.nodeMenu=document.createElement("nav");
        this.nodeMenu.className="UsuarioMenuBox";
        this.nodeContainerBox.appendChild(this.nodeMenu);
        this.nodeContenidoBox=document.createElement("div");
        this.nodeContenidoBox.className="ContenidoUsuarioBox";
        this.nodeContainerBox.appendChild(this.nodeContenidoBox);
        this.nodeHide=document.createElement("div");
        this.nodeHideNews=document.createElement("div");
        //--------------------------
        this.nodeInfoFormBox=document.createElement("div");
        this.nodeInfoFormBox.className="InfoFormBox";
        this.nodeFavoritosBox=document.createElement("div");
        this.nodeFavoritosBox.className="ProductosFavoritos";
        this.nodeMisCompras=document.createElement("div");
        this.nodeMisCompras.className="MisCompras";
        this.nodeSedesFormBox=document.createElement("div");
        this.nodeSedesFormBox.className="SedesFormBox";
        this.nodeSeguridad=document.createElement("div");
        this.nodeSeguridad.className="Seguridad";
        //--------------------------
        this.VentanaActiva="InfoPersonal";
        this.valueUsuario={};
        this.valueProductosFavoritos=[];
        this.valueMisCompras=[];
        this.Favoritos_OK=false;
        this.MisCompras_OK=false;
        this.Sedes_OK=false;
        this.Publicaciones_Obteniendo=false;
        this.PublicacionesObj=null;
        //--------------------------
        this.jxUsuarioGet();
        this.MenuConfig();
    }

    HeaderConfig() {
        var _this=this;
        this.nodeHeader.innerHTML="";
        
        let nodeHeaderImageBG=document.createElement("img");
        nodeHeaderImageBG.src=this.dirRaiz+"img/headerbg.jpg";
        nodeHeaderImageBG.className="HeaderBG";
        this.nodeHeader.appendChild(nodeHeaderImageBG);
        var nodeConteinerHeader=document.createElement("div");
        nodeConteinerHeader.className="InfoBox";
        this.nodeHeader.appendChild(nodeConteinerHeader);
        var nnPerfilFoto=document.createElement("img");
        nodeConteinerHeader.appendChild(nnPerfilFoto);
        if(this.valueUsuario.PerfilH) {
            nnPerfilFoto.src=this.dirRaiz+this.valueUsuario.PerfilT;
            nnPerfilFoto.onclick=function() {
                neoKiri.ImgMaximize(_this.dirRaiz+_this.valueUsuario.PerfilH, _this.valueUsuario.Nombres+" "+_this.valueUsuario.Apellidos);
            }
        } else {
            nnPerfilFoto.src=this.dirRaiz+"img/perfilnofoto.jpg";
        }

        var nnInfoBox=document.createElement("div");
        nodeConteinerHeader.appendChild(nnInfoBox);
        
        var nnTitulo=document.createElement("h1");
        nnInfoBox.appendChild(nnTitulo);
        var nnTituloA=document.createElement("a");
        nnTitulo.appendChild(nnTituloA);
        if(this.valueUsuario.Nombres) {
            nnTituloA.innerHTML=this.valueUsuario.Nombres+" "+this.valueUsuario.Apellidos;
            nnTituloA.href=this.dirRaiz+"Usuario/";
            document.title=this.valueUsuario.Nombres+" "+this.valueUsuario.Apellidos+" | Panel Usuario";
        } else {
            nnTituloA.innerHTML="UsuarioNK";
        }
        let nnCerrarSesionBT=document.createElement("button");
        nnInfoBox.appendChild(nnCerrarSesionBT);
        nnCerrarSesionBT.innerHTML="Cerrar Sesion";
        nnCerrarSesionBT.onclick=function() {
            globalThis.Usuario_Logout();
        }
    }

    MenuConfig() {
        var _this=this;
        this.nodeMenu.innerHTML="";
        var nnUl=document.createElement("ul");
        this.nodeMenu.appendChild(nnUl);
        // -----------
        var nnEditPersona=document.createElement("li");
        nnUl.appendChild(nnEditPersona);
        var nnEditPersonaA=document.createElement("a");
        nnEditPersonaA.href="javascript:void(0)";
        nnEditPersonaA.innerHTML="Informacion Personal";
        nnEditPersonaA.classList.add("Activo");
        nnEditPersona.appendChild(nnEditPersonaA);
        nnEditPersona.onclick=function() {
            _this.OcultarTodo();
            _this.VentanaActiva="InfoPersonal";
            nnEditPersonaA.classList.add("Activo");
            _this.MostrarVentana();
        }
        // -----------
        var nnMisCompras=document.createElement("li");
        nnUl.appendChild(nnMisCompras);
        var nnMisComprasA=document.createElement("a");
        nnMisComprasA.href="javascript:void(0)";
        nnMisComprasA.innerHTML="Compras";
        nnMisCompras.appendChild(nnMisComprasA);
        nnMisCompras.onclick=function() {
            _this.OcultarTodo();
            _this.VentanaActiva="Compras";
            nnMisComprasA.classList.add("Activo");
            _this.MostrarVentana();
        }
        // -----------
        var nnMisFavoritos=document.createElement("li");
        nnUl.appendChild(nnMisFavoritos);
        var nnMisFavoritosA=document.createElement("a");
        nnMisFavoritosA.href="javascript:void(0)";
        nnMisFavoritosA.innerHTML="Favoritos";
        nnMisFavoritos.appendChild(nnMisFavoritosA);
        nnMisFavoritos.onclick=function() {
            _this.OcultarTodo();
            _this.VentanaActiva="Favoritos";
            nnMisFavoritosA.classList.add("Activo");
            _this.MostrarVentana();
        }
        // -----------
        var nnSeguridad=document.createElement("li");
        nnUl.appendChild(nnSeguridad);
        var nnSeguridaA=document.createElement("a");
        nnSeguridaA.href="javascript:void(0)";
        nnSeguridaA.innerHTML="Seguridad";
        nnSeguridad.appendChild(nnSeguridaA);
        nnSeguridad.onclick=function() {
            _this.OcultarTodo();
            nnSeguridaA.classList.add("Activo");
            _this.VentanaActiva="Seguridad";
            _this.MostrarVentana();
        }
    }

    jxUsuarioGet() {
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if(!KeyJX) {
            console.warn("Sin llave Usuario JX: "+KeyJX);
            globalThis.location=this.dirRaiz+"UsuarioLogin/";
            return false;
        }
        //-------------------------
        this.OcultarTodo();
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.loading="lazy";
        loadingImg.encoding="async";
        loadingImg.src=this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("UsuarioNK", "UsuarioGet");
        fd.append("KeyJX", KeyJX);
        for(var i=0; i<this.objOptions.valuesSend.length; i++) {
            fd.append(
                this.objOptions.valuesSend[i][0],
                this.objOptions.valuesSend[i][1]
            );
        }
        fetch(this.urlAction, {method:"POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("UsuarioNK::jxUsuarioGet");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            console.log(data);
            if(data.RespuestaBool) {
                this.valueUsuario=data.Usuario;
                globalThis.localStorage.setItem("UsuarioKeyJX", data.KeyJX);
                this.KeyJX=data.KeyJX;
                this.HeaderConfig();
                this.MostrarVentana();
            } else {
                // globalThis.localStorage.removeItem("UsuarioKeyJX");
            }
        })
        .catch(error=>{
            // globalThis.localStorage.removeItem("UsuarioKeyJX");
            console.warn(error);
        });        
    }

    jxProductosFavoritosGet() {
        "use strict";
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
        if(this.Favoritos_OK) {
            console.log("Ya se estan obteniendo los productos favoritos, por favor espere");
            return false;
        }
        // -------
        this.nodeFavoritosBox.innerHTML="";
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeFavoritosBox.appendChild(nnLoadingImg);
        // -------
		var fd=new FormData();
		fd.append("UsuarioNK", "ProductosFavoritosGet");
		fd.append("KeyJX", KeyJX);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.json())
		.then(data=>{
            console.info("UsuarioNK::jxProductosFavoritosGet()");
			if(data.RespuestaBool) {
                this.nodeFavoritosBox.innerHTML="";
                this.valueProductosFavoritos=data.ProductosFavoritos;
                this.Favoritos_OK=true;
                this.ProductosFavoritosConfig();
            } else {
                this.Favoritos_OK=false;
            }
            this.ProductosFavoritos_Obteniendo=false;
		})
		.catch(err=>{
			this.Favoritos_OK=false;
            console.error(err);
		});
	}
    

    jxProductoFavoritoEliminar(i, Boton) {
        "use strict";
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
        Boton.disabled=true;
        var texto=Boton.innerHTML;
        Boton.innerHTML="Quitando producto de favoritos ...";
        // -------
		var fd=new FormData();
		fd.append("UsuarioNK", "ProductoFavoritoEliminar");
		fd.append("KeyJX", KeyJX);
		fd.append("Producto", this.valueProductosFavoritos[i].ProductoFavoritoID);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("UsuarioNK::jxProductoFavoritoEliminar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                Boton.innerHTML="Se ha quitado de favoritos.";
                // this.jxProductosFavoritosGet();
            } else {
                Boton.disabled=false;
                Boton.innerHTML=data.RespuestaError+" :(";
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}

    jxMisComprasGet() {
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
        if(this.MisCompras_OK) {
            console.log("Ya se creo el nodo");
            return false;
        }
        // -------
        this.nodeMisCompras.innerHTML="";
        this.nodeContenidoBox.appendChild(this.nodeMisCompras);
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeMisCompras.appendChild(nnLoadingImg);
        // -------
        var url = this.urlAction+'?NeoKiri_Web=Usuario_MisComprasGet&KeyJX='+KeyJX;
        fetch(url)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if(data.RespuestaBool) {
                this.nodeMisCompras.innerHTML="";
                this.valueMisCompras=data.MisCompras;
                this.MisCompras_OK=true;
                this.MisCompras_Config();
            } else {
                this.MisCompras_OK=true;
            }
        })
        .catch((error) => {
            console.error(error);
            this.MisCompras_OK=true;
        });
	}

    jxPublicacionesGet() {
        "use strict";
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
        if(this.Publicaciones_Obteniendo) {
            console.log("Ya se estan obteniendo los productos favoritos, por favor espere");
            return false;
        }
        this.Publicaciones_Obteniendo=true;
        // -------
        this.OcultarTodo();
        this.nodeFavoritosBox.innerHTML="";
        this.nodeContenidoBox.appendChild(this.nodeFavoritosBox);
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeFavoritosBox.appendChild(nnLoadingImg);
        // -------
		var fd=new FormData();
		fd.append("UsuarioNK", "ProductosFavoritosGet");
		fd.append("KeyJX", KeyJX);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("UsuarioNK::jxProductosFavoritosGet()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.nodeFavoritosBox.innerHTML="";
                this.valueProductosFavoritos=data.ProductosFavoritos;
                this.ProductosFavoritosConfig();
            } else {
                
            }
            this.Publicaciones_Obteniendo=false;
		})
		.catch(err=>{
			console.error(err);
		});
	}

    jxReestablecerContrasenia(Button) {
        Button.disabled=true;
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
		var fd=new FormData();
		fd.append("UsuarioNK", "ReestablecerContrasenia");
		fd.append("KeyJX", KeyJX);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("UsuarioNK::jxReestablecerContrasenia()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                Button.innerHTML="Se ha enviado la solicitur a tu correo electronico";
            } else {
                Button.innerHTML=data.RespuestaError;
            }
		})
		.catch(err=>{
            Button.innerHTML=err;
			console.error(err);
		});
	}

    InfoUsuario_FormConfig() {
        this.nodeInfoFormBox.innerHTML="";

        let FotoBox=document.createElement("div");
        this.nodeInfoFormBox.appendChild(FotoBox);
        let FileImageObj=new FileImageNK(FotoBox, this.KeyJX, this.urlAction, this.dirRaiz, {titulo: "Foto de Perfil", valuesSend: [["UsuarioNK", "FotoPerfil"]]});

        let InfoBox=document.createElement("div");
        this.nodeInfoFormBox.appendChild(InfoBox);

        var FormInfoEdit=new FormNK(InfoBox, this.urlAction, this.KeyJX, this.dirRaiz);
        FormInfoEdit.setTitulo("Informacion Personal");
        FormInfoEdit.setTexto("Nombres : ", "UsuarioNombre",this.valueUsuario.Nombres, true);
        FormInfoEdit.setTexto("Apellidos : ", "UsuarioApellido",this.valueUsuario.Apellidos, true);
        FormInfoEdit.setTexto("Alias : ", "UsuarioAlias",this.valueUsuario.Alias, false);
        FormInfoEdit.setTexto("Cargo : ", "UsuarioCargo",this.valueUsuario.Cargo, false);
        FormInfoEdit.setFecha("Fecha de Nacimiento", "UsuarioFechaNacimiento", this.valueUsuario.Fecha_Nacimiento, false, false, {});
        FormInfoEdit.setTextarea("Descripcion: ","UsuarioDescripcion",this.valueUsuario.Descripcion, false, {
            descripcion: "Esta es una descripcion corta",
            limite: 160
        });
        FormInfoEdit.setHidden("UsuarioNK", "UsuarioInfoSet");
        FormInfoEdit.setHidden("UsuarioKeyJX", this.KeyJX);
        FormInfoEdit.finalizar("Editar Informacion de Usuario");
    }

    ProductosFavoritosConfig() {
        this.nodeFavoritosBox.innerHTML="";
        this.nodeContenidoBox.appendChild(this.nodeFavoritosBox);
        var nnTituloFavoritos=document.createElement("h2");
        nnTituloFavoritos.innerHTML="Productos Favoritos: "+this.valueProductosFavoritos.length;
        this.nodeFavoritosBox.appendChild(nnTituloFavoritos);
        var nnFavoritosLista=document.createElement("ul");
        this.nodeFavoritosBox.appendChild(nnFavoritosLista);
        for (let i = 0; i < this.valueProductosFavoritos.length; i++) {
            this.ProductoFavorito_ItemConfig(i, nnFavoritosLista);
        }
        this.ProductosFavoritosCreado=true;
    }
    
    ProductoFavorito_ItemConfig(i, nnFavoritosLista) {
        var _this=this;
        var nnLi=document.createElement("li");
        nnFavoritosLista.appendChild(nnLi);

        let ProductoItem=this.valueProductosFavoritos[i];
        
        var nnFotoBox=document.createElement("div");
        nnFotoBox.className="FotoBox";
        nnLi.appendChild(nnFotoBox);
        
        if(ProductoItem.PortadaS) {
            var nnFoto=document.createElement("img");
            nnFoto.src=this.dirRaiz+ProductoItem.PortadaS;
            nnFoto.encoding="async";
            nnFoto.loading="lazy";
            nnFotoBox.appendChild(nnFoto);
        }
        
        var nnContenido=document.createElement("div");
        nnContenido.className="ContenidoBox";
        nnLi.appendChild(nnContenido);
    
        var nnNombre=document.createElement("div");
        nnContenido.appendChild(nnNombre);
        var nnNombreA=document.createElement("a");
        nnNombreA.href=this.dirRaiz+ProductoItem.link;
        nnNombreA.innerHTML=ProductoItem.Nombre;
        nnNombre.appendChild(nnNombreA);
        var nnPrecio=document.createElement("div");
        nnPrecio.innerHTML="Precio: "+CarritoComprasNK.NumeroAMoneda(ProductoItem.Precio)+" COP";
        nnContenido.appendChild(nnPrecio);
    
        var nnControlesIitem=document.createElement("div");
        nnContenido.appendChild(nnControlesIitem);
        // var nnButtonEliminar=document.createElement("button");
        // nnButtonEliminar.innerHTML="Eliminar de favoritos";
        // nnControlesIitem.appendChild(nnButtonEliminar);
        var nnButtonEliminarContain=document.createElement("button");
        nnButtonEliminarContain.className="corazoncontain";
        nnControlesIitem.appendChild(nnButtonEliminarContain);
        var nnButtonEliminar=document.createElement("div");
        nnButtonEliminar.className="corazon_rojo";
        nnButtonEliminarContain.appendChild(nnButtonEliminar);
        nnButtonEliminarContain.onclick=function() {
            _this.jxProductoFavoritoEliminar(i, nnButtonEliminarContain);
        }
    }

    MisCompras_Config() {
        this.nodeMisCompras.innerHTML="";
        this.nodeContenidoBox.appendChild(this.nodeMisCompras);
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Mis Compras: "+this.valueMisCompras.length;
        this.nodeMisCompras.appendChild(nnTitulo);
        var nnComprasLista=document.createElement("ul");
        this.nodeMisCompras.appendChild(nnComprasLista);
        for (let i = 0; i < this.valueMisCompras.length; i++) {
            this.MisCompras_ItemConfig(i, nnComprasLista);
        }
        this.MisComprasCreado=true;
    }
    
    MisCompras_ItemConfig(i, nnMisComprasLista) {
        var _this=this;
        var nnLi=document.createElement("li");
        nnMisComprasLista.appendChild(nnLi);

        let CompraItem=this.valueMisCompras[i];

        var nnTitulo=this.nnNode("div", nnLi);
        var nnTituloB=this.nnNode("b", nnTitulo);
        nnTituloB.innerHTML=CompraItem.Titulo;
        var nnPrecio=this.nnNode("div", nnLi);
        nnPrecio.innerHTML=CarritoComprasNK.NumeroAMoneda(CompraItem.PrecioTotalBase)+" COP";
        var nnControlesItem=this.nnNode("div", nnLi);
        var nnVerCompra=this.nnNode("button", nnControlesItem);
        nnVerCompra.innerHTML="Ver Compra";
        nnVerCompra.onclick=function() {
            // alert(CompraItem.CotizacionVentaID);
            globalThis.location=_this.dirRaiz+"Usuario/Compra="+CompraItem.CotizacionVentaID;
        }        
    }

    SeguridadForm_Config() {
        var _this=this;
        this.nodeSeguridad.innerHTML="";
        let ContraseniaBox=document.createElement("div");
        this.nodeSeguridad.appendChild(ContraseniaBox);
        this.nnNode("h2", ContraseniaBox, "Reestablecer Contraseña")
        let ContraseniaSolicitarButton=this.nnNode("button", ContraseniaBox, "Solicitar Reestablecimiento");
        ContraseniaSolicitarButton.onclick=function() {
            _this.jxReestablecerContrasenia(ContraseniaSolicitarButton);
        }
        return true;
    }

    nnNode(nodeType, nodeAppend, innertext) {
        var nnNode=document.createElement(nodeType);
        if(innertext) {
            nnNode.innerHTML=innertext;
        }
        nodeAppend.appendChild(nnNode);
        return nnNode;
    }

    OcultarTodo() {
        let nodesMenu=this.nodeMenu.querySelectorAll("a");
        nodesMenu.forEach(elem=>{
            elem.classList.remove("Activo");
        });
        this.nodeHide.appendChild(this.nodeInfoFormBox);
        this.nodeHide.appendChild(this.nodeFavoritosBox);
        this.nodeHide.appendChild(this.nodeMisCompras);
        this.nodeHide.appendChild(this.nodeSedesFormBox)
        if(this.PublicacionesObj) {
            this.nodeHide.appendChild(this.PublicacionesObj.nodeObj);
        }
        this.nodeHide.appendChild(this.nodeSeguridad);
    }

    MostrarVentana() {
        switch (this.VentanaActiva) {
            case "InfoPersonal":
                this.InfoUsuario_FormConfig();
                this.nodeContenidoBox.appendChild(this.nodeInfoFormBox);
                break;

            case "Compras":
                this.nodeContenidoBox.appendChild(this.nodeMisCompras);
                if(!this.MisCompras_OK) {
                    this.jxMisComprasGet();
                }
                break;

            case "Favoritos":
                this.nodeContenidoBox.appendChild(this.nodeFavoritosBox);
                if(!this.Favoritos_OK) {
                    this.jxProductosFavoritosGet();
                }
                break;
            
            case "Seguridad":
                this.SeguridadForm_Config();
                this.nodeContenidoBox.appendChild(this.nodeSeguridad);
                break;
        }
    }
}
