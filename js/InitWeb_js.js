globalThis.addEventListener('load', function() {
    // console.log('La página ha terminado de cargar');
});

globalThis.addEventListener('DOMContentLoaded', function() {
    // console.log('La página ha terminado de cargar el DOM');
});

class MenuTop_Footer {    
    constructor(UrlAction, dirRaiz, ObjOptionsIn) {
        // console.log("new MenuTop_Footer() - js")
        var _this=this;
        this.nodeMenu = document.querySelector("#HeaderNav") || document.createElement("div");
        this.nodeMenu.innerHTML="";
        this.nodeMenu.className="HeaderNav";
        // ------ Values
        this.urlAction=UrlAction||"No Definido";
        this.dirRaiz=dirRaiz||"";
        
        ObjOptionsIn=ObjOptionsIn||{};
        this.objOptions={
            valuesSend: ObjOptionsIn.valuesSend || [],
            loadingImg: ObjOptionsIn.loadingImg || "img/loading.gif"
        };
        this.UsuarioNombre="";
        this.UsuarioFoto="";
        this.CarritoProductos=[];
        // ------ NODOS
        this.TopBar=document.createElement("div");
        this.TopBar.className="TopBar";
        this.nodeMenu.appendChild(this.TopBar);

        this.TopBar_Contenido=document.createElement("div");
        this.TopBar_Contenido.className="ContenidoBar";
        
        this.nodeUsuarioBox=document.createElement("div");
        this.nodeUsuarioBox.className="UsuarioNK";
        this.nodeMenu.appendChild(this.TopBar_Contenido);
        
        this.TopBar_NodeHide=document.createElement("div");
        // ------ Variables Configuracion
        this.IniciandoSesion=false;
        this.SesionIniciada=false;
        this.TopBarContenido_Activo=false;
        this.UsuarioBox_Activo=false;
        this.Carrito_Activo=false;
        this.NeoKiriLoginBox_Activo=false;
        // ------ ObjetosContenidoBar
        this.NeoKiriLoginObj=new LoginBoxNK(this.urlAction, this.dirRaiz, this.objOptions);
        this.CarritoObj=new CarritoComprasNK(this.urlAction, this.KeyJX, this.dirRaiz, this.objOptions);;
        
        // ------ Configurar
        this.TopBarConfig();
        this.jxUsuarioLoginCheck();
        globalThis.Usuario_Logout=function() {
            _this.jxLogout();
        }
    }
    
    TopBarConfig() {
        var _this=this;
        this.TopBar.innerHTML="";
        var nnProfileMenu=document.createElement("ul");
        nnProfileMenu.className="ProfileMenu";

        var nnLiNeoKiriButton=document.createElement("li");
        nnLiNeoKiriButton.className="NeoKiriButton";
        nnProfileMenu.appendChild(nnLiNeoKiriButton)
        var nnLiNeoKiriButtonA=document.createElement("a");
        nnLiNeoKiriButtonA.href="javascript:void(0)"
        nnLiNeoKiriButton.appendChild(nnLiNeoKiriButtonA)
        var nnLiNeoKiriButtonImg=document.createElement("img");
        nnLiNeoKiriButtonImg.encoding="async";
        nnLiNeoKiriButtonImg.loading="lazy";
        nnLiNeoKiriButtonImg.src=this.dirRaiz+"logo_min.png";
        nnLiNeoKiriButtonA.appendChild(nnLiNeoKiriButtonImg)
        this.neoKiriButton=nnLiNeoKiriButton;

        var nnLiHome=document.createElement("li");
        nnProfileMenu.appendChild(nnLiHome)
        var nnLiHomeA=document.createElement("a");
        nnLiHomeA.innerHTML="🏠";
        nnLiHomeA.href=this.dirRaiz+"img/../"
        nnLiHome.appendChild(nnLiHomeA)
        this.TopBar.appendChild(nnProfileMenu);

        // ------
        
        var nnSearchMenu=document.createElement("div");
        nnSearchMenu.className="SearchMenu";
        this.TopBar.appendChild(nnSearchMenu);
        var nnSearchForm=document.createElement("form");
        nnSearchMenu.appendChild(nnSearchForm);
        nnSearchForm.action=this.dirRaiz+"Buscar";
        
        var nnSearchForm_inputtext=document.createElement("input");
        nnSearchForm_inputtext.type="text";
        nnSearchForm_inputtext.name="s";
        nnSearchForm.appendChild(nnSearchForm_inputtext);
        
        var nnSearchForm_inputsubmit=document.createElement("input");
        nnSearchForm_inputsubmit.type="submit";
        nnSearchForm_inputsubmit.value="🔎";
        nnSearchForm.appendChild(nnSearchForm_inputsubmit);
        
        nnSearchForm.onsubmit=function(e) {
            if (nnSearchForm_inputtext.value.length<3) {
                e.preventDefault(nnSearchForm_inputtext.value);
            }
            
            // _this.jxBuscar();
        }
        // ------
        var nnAcercaMenu=document.createElement("ul");
        nnAcercaMenu.className="AcercaMenu";
        this.TopBar.appendChild(nnAcercaMenu);
        
        var nnLiCarrito=document.createElement("li");
        nnLiCarrito.className="Carrito";
        nnAcercaMenu.appendChild(nnLiCarrito)
        var nnLiCarritoA=document.createElement("a");
        nnLiCarritoA.innerHTML="Carrito";
        nnLiCarritoA.href="javascript:void(0)";
        nnLiCarrito.appendChild(nnLiCarritoA);
        
        this.neoKiriButton.addEventListener("click", function() {
            _this.NeoKiriButtonClick();
        }, false);

        nnLiCarrito.addEventListener("click", function() {
            _this.CarritoClick();
        }, false);

        globalThis.addEventListener("resize", function() {
            if(!_this.TopBarContenido_Activo) {
                return false;
            }
            _this.TopBarContenido_Activar();
            console.log("ResizeTopbar");
        }, false);
    }

    TopBarContenido_Accion() {
        // Comprobar si hay algun objeto activo
        var ObjetosActivos=false;
        if(this.NeoKiriLoginBox_Activo || this.Carrito_Activo || this.UsuarioBox_Activo) {
            ObjetosActivos=true;
        }
        // si no hay objeto activo y esta activo el topbar desactivar
        if (ObjetosActivos) {
            this.TopBarContenido_Limpiar();
        }
        if(this.NeoKiriLoginBox_Activo) {
            this.TopBar_Contenido.appendChild(this.NeoKiriLoginObj.nodeObj);
        }
        if(this.UsuarioBox_Activo) {
            this.TopBar_Contenido.appendChild(this.nodeUsuarioBox);
        }
        if(this.Carrito_Activo) {
            if(this.CarritoObj.nodeCarrito) {
                this.TopBar_Contenido.appendChild(this.CarritoObj.nodeCarrito);
            } else {
                console.log("No se ha iniciado sesion para usar el carrito");
                this.TopBar_Contenido.innerHTML="";
            }
        }
        if (!ObjetosActivos && this.TopBarContenido_Activo) {
            this.TopBarContenido_Desactivar();
        }  else {
            this.TopBarContenido_Activar();
        }
    }

    TopBarContenido_Activar() {

        document.body.style.overflow="hidden";
        
        var TopMenuHeight=this.TopBar.offsetHeight;
        var WinW=globalThis.innerWidth;
        var WinH=globalThis.innerHeight;

        document.body.style.width=WinW+"px";
        document.body.style.height=WinH+"px";
        
        this.TopBar_Contenido.style.overflow="auto";
        this.TopBar_Contenido.style.top=TopMenuHeight+"px";
        this.TopBar_Contenido.style.height=(WinH-TopMenuHeight)+"px";
        this.TopBar_Contenido.style.width=(WinW)+"px";
        
        this.TopBarContenido_Activo=true;
    }

    TopBarContenido_Desactivar() {
        document.body.removeAttribute("style");
        this.TopBar_Contenido.style.height="0px";
        this.TopBar_Contenido.style.overflow="hidden";
        this.TopBarContenido_Activo=false;
    }

    TopBarContenido_Limpiar() {
        // this.TopBar_Contenido.appendChild(this.CarritoObj.nodeCarrito);
        this.TopBar_NodeHide.appendChild(this.NeoKiriLoginObj.nodeObj);
        this.TopBar_NodeHide.appendChild(this.nodeUsuarioBox);
        if(this.CarritoObj.nodeCarrito) {
            this.TopBar_NodeHide.appendChild(this.CarritoObj.nodeCarrito);
        }
    }

    jxUsuarioLoginCheck() {
        "use strict";
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        let AsideUsuarioObj={
            Nombre: "",
            Foto:"",
            Iniciado: false
        }
        if (!KeyJX) {
            // console.info("No hay llave KeyJX Definida");
            // this.NeoKiriLoginObj.MostrarLogin();
            MenuTop_Footer.Aside_UsuarioBox(AsideUsuarioObj, this.dirRaiz);
            return false;
        }
        this.IniciandoSesion=true;
        this.TopBarContenido_Limpiar();
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "UsuarioLoginCheck");
		fd.append("KeyJX", KeyJX);
		// for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
        //     fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		// }
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.json())
		.then(data=>{
            console.info("NeoKiriWeb::jxUsuarioLoginCheck()");
            console.log(data);
            this.IniciandoSesion=false;
			if(data.RespuestaBool) {
                this.UsuarioNombre=data.TokenObj.Usuario_Nombre;
                this.UsuarioFoto=data.TokenObj.Usuario_Foto||"";
                this.SesionIniciada=true;
                this.KeyJX=KeyJX;
                this.UsuarioNodesConfig();
                // ---- Nodo Aside usuario
                AsideUsuarioObj.Nombre=this.UsuarioNombre;
                AsideUsuarioObj.Foto=this.UsuarioFoto;
                AsideUsuarioObj.Iniciado=true;
            } else {
                globalThis.localStorage.removeItem("UsuarioKeyJX");
                globalThis.localStorage.removeItem("UsuarioKeyJX_Expira");
                this.KeyJX="";
            }
            MenuTop_Footer.Aside_UsuarioBox(AsideUsuarioObj, this.dirRaiz);
		})
		.catch(err=>{
			console.error(err);
		});
	}

    

    jxLogout() {
        "use strict";
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            // console.info("No hay llave KeyJX Definida");
            // this.NeoKiriLoginObj.MostrarLogin();
            return false;
        }
        this.IniciandoSesion=true;
        this.TopBarContenido_Limpiar();
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "UsuarioLogout");
		fd.append("KeyJX", KeyJX);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.json())
		.then(data=>{
			if(data.RespuestaBool) {
                globalThis.localStorage.removeItem("UsuarioKeyJX");
                globalThis.localStorage.removeItem("UsuarioKeyJX_Expira");
                globalThis.location=this.dirRaiz;
            } else {
                console.error("NeoKiriWeb::jxLogout()"+ data.RespuestaError);
            }
		})
		.catch(err=>{
            console.error("NeoKiriWeb::jxLogout()");
			console.log(err);
		});
	}

    UsuarioNodesConfig() {
        this.nodeUsuarioBox.innerHTML="";
        
        this.UsuarioHeader=document.createElement("div");
        this.UsuarioHeader.className="UsuarioHeader";
        this.nodeUsuarioBox.appendChild(this.UsuarioHeader);
        
        this.UsuarioContenedor=document.createElement("div");
        this.UsuarioContenedor.className="UsuarioContenedor";
        this.nodeUsuarioBox.appendChild(this.UsuarioContenedor);
        
        this.UsuarioMenu=document.createElement("div");
        this.UsuarioMenu.className="UsuarioMenu";
        this.UsuarioContenedor.appendChild(this.UsuarioMenu);
        
        this.UsuarioContenido=document.createElement("div");
        this.UsuarioContenedor.appendChild(this.UsuarioContenido);
        
        this.UsuarioFooter=document.createElement("div");
        this.UsuarioFooter.className="UsuarioFooter";
        this.nodeUsuarioBox.appendChild(this.UsuarioFooter);
        
        this.UsuarioHide=document.createElement("div");
        // --------------------
        this.UsuarioMenu_InicioCreado=false;
        this.UsuarioMenu_InicioObj=document.createElement("div");
        this.UsuarioMenu_ProductosFavoritosCreado=false;
        this.UsuarioMenu_ProductosFavoritosNode=document.createElement("div");
        this.UsuarioMenu_ProductosFavoritosNode.className="ProductosFavoritos";;
        this.UsuarioMenu_MisComprasNode=document.createElement("div");
        this.UsuarioMenu_MisComprasNode.className="Usuario_MisCompras";;
        // ------ Arrays
        this.ProductosFavoritos = [];
        this.ProductosFavoritos_Obteniendo=false;
        this.MisCompras = [];
        this.MisCompras_Obteniendo=false;
        // --------------------
        this.UsuarioHeaderConfig();
        this.UsuarioFooterConfig();
        this.UsuarioMenuConfig();
        // --------------------
        this.UsuarioMenu_InicioClic();
    }
    
    UsuarioHeaderConfig() {
        var _this=this;
        this.UsuarioHeader.innerHTML="";
    }
    UsuarioFooterConfig() {
        this.UsuarioFooter.innerHTML="";
        var nnStatDiv=document.createElement("div");
        this.UsuarioFooter.appendChild(nnStatDiv);
        var nnLogo=document.createElement("img");
        nnLogo.loading="lazy";
        nnLogo.encoding="async";
        nnLogo.className="LogoFooter";
        nnLogo.src=this.dirRaiz+"img/loading.gif";
        this.UsuarioFooter.appendChild(nnLogo);
    }

    UsuarioMenuConfig() {
        this.UsuarioMenu.innerHTML="";
        var _this=this;

        var Usuario=document.createElement("div");
        Usuario.className="UsuarioBox";
        this.UsuarioMenu.appendChild(Usuario);
        var ImagenUsuario=document.createElement("img");
        if(this.UsuarioFoto) {
            ImagenUsuario.src=this.dirRaiz+this.UsuarioFoto;
        } else {
            ImagenUsuario.src=this.dirRaiz+"img/perfilnofoto.jpg";
        }
        Usuario.appendChild(ImagenUsuario);
        var Titulo=document.createElement("h1");
        Usuario.appendChild(Titulo);
        var TituloA=document.createElement("a");
        TituloA.innerHTML=this.UsuarioNombre;
        TituloA.href=this.dirRaiz+"Usuario/";
        Titulo.appendChild(TituloA);
        var Controles=document.createElement("div");
        Usuario.appendChild(Controles);
        var nnCerrarSesion=document.createElement("button");
        nnCerrarSesion.innerHTML="Cerrar Sesion";
        Controles.appendChild(nnCerrarSesion);
        nnCerrarSesion.onclick=function() {
            _this.jxLogout();
        }

        var nnLista=document.createElement("ul");
        this.UsuarioMenu.appendChild(nnLista);
        
        var nnMenuInicio=document.createElement("li");
        nnMenuInicio.innerHTML="Bienvenido";
        nnLista.appendChild(nnMenuInicio);
        var nnMenuProductosFavoritos=document.createElement("li");
        nnMenuProductosFavoritos.innerHTML="Productos Favoritos";
        nnLista.appendChild(nnMenuProductosFavoritos);
        var nnMenuMisCompras=document.createElement("li");
        nnMenuMisCompras.innerHTML="Mis Compras";
        nnLista.appendChild(nnMenuMisCompras);
        // -------------------
        nnMenuInicio.addEventListener("click", function() {
            _this.UsuarioMenu_InicioClic();
        }, true);
        nnMenuProductosFavoritos.addEventListener("click", function() {
            _this.UsuarioMenu_ProductosFavoritosClic();
        }, true);
        nnMenuMisCompras.addEventListener("click", function() {
            _this.UsuarioMenu_MisComprasClic();
        }, true);
        // -------------------
    }

    UsuarioMenu_InicioClic() {
        this.UsuarioMenuContenido_OcultarTodo();
        if(!this.UsuarioMenu_InicioCreado) {
            this.UsuarioMenu_InicioCrear();
        }
        this.UsuarioContenido.appendChild(this.UsuarioMenu_InicioObj);

    }
    UsuarioMenu_InicioCrear() {
        this.UsuarioMenu_InicioObj.innerHTML="";
        var Parrafo1=document.createElement("p");
        Parrafo1.innerHTML="<p>Bienvenido "+this.UsuarioNombre+", Estamos encantados de que nos estes visitando</p>";
        Parrafo1.innerHTML+="<p>Desde ahora podemos ofrecerte una mejor experiencia en nuestro sitio, Ahora puedes crear un carrito de compras, listar tus favoritos, agregar direcciones ...</p>";
        Parrafo1.innerHTML+="<p>Este es un proyecto de software para el programa Analisis y Desarrollo de Software del SENA en el año 2024.</p>";
        Parrafo1.innerHTML+="<img src='"+this.dirRaiz+"img/SenaLogo.png'>";
        this.UsuarioMenu_InicioObj.appendChild(Parrafo1);
        this.UsuarioMenu_InicioCreado=true;
    }

    UsuarioMenu_ProductosFavoritosClic() {
        this.jxProductosFavoritosGet();
    }
    
    UsuarioMenu_MisComprasClic() {
        this.jxMisComprasGet();
    }

    UsuarioMenu_ProductosFavoritosCrear() {
        this.UsuarioContenido.appendChild(this.UsuarioMenu_ProductosFavoritosNode);
        this.UsuarioMenu_ProductosFavoritosNode.innerHTML="";
        var nnTituloFavoritos=document.createElement("h2");
        nnTituloFavoritos.innerHTML="Productos Favoritos: "+this.ProductosFavoritos.length;
        this.UsuarioMenu_ProductosFavoritosNode.appendChild(nnTituloFavoritos);
        var nnFavoritosLista=document.createElement("ul");
        this.UsuarioMenu_ProductosFavoritosNode.appendChild(nnFavoritosLista);
        for (let i = 0; i < this.ProductosFavoritos.length; i++) {
            this.UsuarioMenu_ProductoFavorito_ItemCrear(i, nnFavoritosLista);
        }
        this.UsuarioMenu_ProductosFavoritosCreado=true;
    }
    
    UsuarioMenu_ProductoFavorito_ItemCrear(i, nnFavoritosLista) {
        var _this=this;
        var nnLi=document.createElement("li");
        nnFavoritosLista.appendChild(nnLi);

        let ProductoItem=this.ProductosFavoritos[i];
        
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

    UsuarioMenu_MisComprasCrear() {
        this.UsuarioContenido.appendChild(this.UsuarioMenu_MisComprasNode);
        this.UsuarioMenu_MisComprasNode.innerHTML="";
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Mis compras: "+this.MisCompras.length;
        this.UsuarioMenu_MisComprasNode.appendChild(nnTitulo);
        var nnComprasLista=document.createElement("ul");
        this.UsuarioMenu_MisComprasNode.appendChild(nnComprasLista);
        for (let i = 0; i < this.MisCompras.length; i++) {
            this.UsuarioMenuMisCompras_ItemCrear(i, nnComprasLista);
        }
        this.UsuarioMenu_ProductosFavoritosCreado=true;
    }
    
    UsuarioMenuMisCompras_ItemCrear(i, nnComprasLista) {
        var _this=this;
        var nnLi=document.createElement("li");
        nnComprasLista.appendChild(nnLi);

        let CompraItem=this.MisCompras[i];

        var nnTitulo=this.nnNode("div", nnLi);
        var nnTituloB=this.nnNode("b", nnTitulo);
        nnTituloB.innerHTML=CompraItem.Titulo;
        var nnPrecio=this.nnNode("div", nnLi);
        nnPrecio.innerHTML=CarritoComprasNK.NumeroAMoneda(CompraItem.PrecioTotalBase)+" COP";
        let nnLink=this.nnNode("a", nnLi);
        nnLink.innerHTML="Ver compra";
        nnLink.href=this.dirRaiz+"Usuario/Compra="+CompraItem.CotizacionVentaID;
    }

    UsuarioMenuContenido_OcultarTodo() {
        this.UsuarioHide.appendChild(this.UsuarioMenu_InicioObj);
        this.UsuarioHide.appendChild(this.UsuarioMenu_ProductosFavoritosNode);
        this.UsuarioHide.appendChild(this.UsuarioMenu_MisComprasNode);
    }

    jxProductosFavoritosGet() {
        var KeyJX=globalThis.localStorage.getItem("UsuarioKeyJX");
        if (!KeyJX) {
            return false;
        }
        // -------
        if(this.ProductosFavoritos_Obteniendo) {
            console.log("Ya se estan obteniendo los productos favoritos, por favor espere");
            return false;
        }
        this.ProductosFavoritos_Obteniendo=true;
        // -------
        this.UsuarioMenuContenido_OcultarTodo();
        this.UsuarioContenido.appendChild(this.UsuarioMenu_ProductosFavoritosNode);
        this.UsuarioMenu_ProductosFavoritosNode.innerHTML="";
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.UsuarioMenu_ProductosFavoritosNode.appendChild(nnLoadingImg);
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "Usuario_ProductosFavoritosGet");
		fd.append("KeyJX", KeyJX);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("NeoKiriWeb::jxProductosFavoritosGet()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                this.UsuarioMenu_ProductosFavoritosNode.innerHTML="";
                this.ProductosFavoritos=data.ProductosFavoritos;
                this.ProductosFavoritos_Obteniendo=false;
                this.UsuarioMenu_ProductosFavoritosCrear();
            } else {
                
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
        if(this.MisCompras_Obteniendo) {
            console.log("Ya se estan obteniendo las compras, por favor espere");
            return false;
        }
        this.MisCompras_Obteniendo=true;
        // -------
        this.UsuarioMenuContenido_OcultarTodo();
        this.UsuarioContenido.appendChild(this.UsuarioMenu_MisComprasNode);
        this.UsuarioMenu_MisComprasNode.innerHTML="";
        var nnLoadingImg=document.createElement("img");
        nnLoadingImg.loading="lazy";
        nnLoadingImg.encoding="async";
        nnLoadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.UsuarioMenu_MisComprasNode.appendChild(nnLoadingImg);
        // -------
        var url = this.urlAction+'?NeoKiri_Web=Usuario_MisComprasGet&KeyJX='+KeyJX;
        fetch(url)
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            if(data.RespuestaBool) {
                this.UsuarioMenu_MisComprasNode.innerHTML="";
                this.MisCompras=data.MisCompras;
                this.MisCompras_Obteniendo=false;
                this.UsuarioMenu_MisComprasCrear();
            } else {
                
            }
        })
        .catch((error) => {
            console.error(error);
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
		fd.append("NeoKiri_Web", "Usuario_ProductoFavoritoEliminar");
		fd.append("KeyJX", KeyJX);
		fd.append("Producto", this.ProductosFavoritos[i].ProductoFavoritoID);
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("NeoKiriWeb::jxProductoFavoritoEliminar()");
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

    NeoKiriButtonClick() {
        if(this.IniciandoSesion){
            return false;
        }

        this.Carrito_Activo=false;
        if(this.SesionIniciada) {
            this.NeoKiriLoginBox_Activo=false;
            if(this.UsuarioBox_Activo) {
                this.UsuarioBox_Activo=false;
            } else {
                this.UsuarioBox_Activo=true;
            }

        } else {
            this.UsuarioBox_Activo=false;
            if(this.NeoKiriLoginBox_Activo) {
                this.NeoKiriLoginBox_Activo=false;
            } else {
                this.NeoKiriLoginBox_Activo=true;
            }
        }
        this.TopBarContenido_Accion();
    }

    CarritoClick() {
        if(this.IniciandoSesion){
            return false;
        }
        this.UsuarioBox_Activo=false;
        this.NeoKiriLoginBox_Activo=false;
        if(!this.Carrito_Activo) {
            // this.CarritoMostrar();
            this.Carrito_Activo=true;
        } else {
            // this.CarritoOcultar();
            this.Carrito_Activo=false;
        }
        this.TopBarContenido_Accion();
    }

    nnNode(nodeType, nodeAppend) {
        var nnNode=document.createElement(nodeType);
        nodeAppend.appendChild(nnNode);
        return nnNode;
    }

    static Aside_UsuarioBox(AsideUsuarioObj, dirRaiz) {
        let Usuario=document.querySelector("#UsuarioBox_MinBox");
        if(!Usuario) {
            return false;
        }
        Usuario.innerHTML="";
        var EnlaceA=document.createElement("a");
        EnlaceA.href=dirRaiz+"Usuario/";
        Usuario.appendChild(EnlaceA);
        var ImagenUsuario=document.createElement("img");
        if(AsideUsuarioObj.Foto) {
            ImagenUsuario.src=dirRaiz+AsideUsuarioObj.Foto;
        } else {
            ImagenUsuario.src=dirRaiz+"img/perfilnofoto.jpg";
        }
        EnlaceA.appendChild(ImagenUsuario);
        var Titulo=document.createElement("div");
        Titulo.innerHTML=AsideUsuarioObj.Nombre||"Iniciar Sesion";
        EnlaceA.appendChild(Titulo);
    }
}