class CarritoComprasNK {
    constructor(UrlAction, KeyJX, dirRaiz, ObjOptionsIn) {
        var _this=this;
        ObjOptionsIn=ObjOptionsIn||{};
        this.KeyJX=KeyJX || globalThis.localStorage.getItem("UsuarioKeyJX") || "";
        this.urlAction=UrlAction||"No Definido";
        this.dirRaiz=dirRaiz||"";

        this.objOptions={
            valuesSend: ObjOptionsIn.valuesSend || [],
            loadingImg: ObjOptionsIn.loadingImg || "img/loading.gif"
        };
        // ----------------------
        this.valueCarrito=[];

        this.ClienteTipo="Persona";
        this.ClienteTelefono="";
        this.ClienteCorreo="";

        this.PersonaNombres="";
        this.PersonaIdentificacion="";
        
        this.EmpresaNombre="";
        this.EmpresaNit="";
        this.EmpresaNitDV="";
        
        this.DireccionPais="";
        this.DireccionDepartamento="";
        this.DireccionMunicipio="";
        this.DireccionEnvio="";

        this.PagoTipo="";//ContactoDirecto - AppsMoviles  - RetiroSede - OnlineWompi
        this.PagoBanco="";//Bancolombia
        this.PagoBancoPersonaTipo="";//Juridica - Natural
        // ----------------------
        this.PantallaActual="Lista";
        // ----------------------
        this.nodeCarrito=document.createElement("div");
        this.nodeCarrito.className="CarritoComprasNK";
        
        this.nodeHeader=document.createElement("div");
        this.nodeCarrito.appendChild(this.nodeHeader);
        
        this.nodeStatus=document.createElement("div");
        this.nodeStatus.className="StatusBox";
        this.nodeCarrito.appendChild(this.nodeStatus);
        
        this.nodeContainer=document.createElement("div");
        this.nodeContainer.className="ContainerBox";
        this.nodeCarrito.appendChild(this.nodeContainer);

        this.nodeMenu=document.createElement("div");
        this.nodeMenu.className="MenuBox";
        this.nodeContainer.appendChild(this.nodeMenu);

        this.nodeContenido=document.createElement("div");
        this.nodeContenido.className="ContenidoBox";
        this.nodeContainer.appendChild(this.nodeContenido);
        
        this.nodeLista=document.createElement("ul");
        this.nodeLista.className="CarritoLista";
        // this.nodeContainer.appendChild(this.nodeLista);
        
        this.nodeCliente=document.createElement("div");
        this.nodeCliente.className="ClienteBox";
        // this.nodeContainer.appendChild(this.nodeCliente);
        
        this.nodeBanco=document.createElement("div");
        this.nodeBanco.className="BancoBox";
        // this.nodeContainer.appendChild(this.nodeBanco);
        
        this.nodeResumen=document.createElement("div");
        this.nodeResumen.className="ResumenBox";
        // this.nodeContainer.appendChild(this.nodeResumen);
        
        this.nodeFooter=document.createElement("div");
        this.nodeFooter.className="CarritoFooter";
        this.nodeCarrito.appendChild(this.nodeFooter);

        this.nodeHide=document.createElement("div");
        // ----------------------
        this.HeaderConfig();
        this.jxCarritoGet();
        globalThis.CarritoComprasNK_Reload=function() {
            _this.jxCarritoGet();
        }
    }

    HeaderConfig() {
        var _this=this;
        this.nodeHeader.innerHTML="";
        var nnTitulo=document.createElement("h1");
        this.nodeHeader.appendChild(nnTitulo);
        nnTitulo.innerHTML="Carrito de Compras";
        // ---------------
        nnTitulo.onclick=function() {
            console.clear();
            console.log(_this);
        }
    }

    MostrarVentana() {
        this.OcultarVentanas();
        switch (this.PantallaActual) {
            case "Lista":
                this.nodeContenido.appendChild(this.nodeLista);
            break;
            
            case "Cliente":
                this.nodeContenido.appendChild(this.nodeCliente);
            break;
            
            case "Pago":
                this.nodeContenido.appendChild(this.nodeBanco);
            break;
            
            case "Resumen":
                this.nodeContenido.appendChild(this.nodeResumen);
            break;
        }
        
    }
    OcultarVentanas() {
        this.nodeHide.appendChild(this.nodeLista);
        this.nodeHide.appendChild(this.nodeCliente);
        this.nodeHide.appendChild(this.nodeBanco);
        this.nodeHide.appendChild(this.nodeResumen);
    }
    MenuConfig() {
        var _this=this;
        this.nodeMenu.innerHTML="";
        // --------
        var nnLista=document.createElement("button");
        nnLista.innerHTML="Carrito";
        this.nodeMenu.appendChild(nnLista);
        nnLista.onclick=function() {
            _this.ProductosConfig();
            _this.PantallaActual="Lista";
            _this.MostrarVentana();
            _this.BotonActivoRemove();
            _this.BotonLista.className="Activo";
        }
        this.BotonLista=nnLista;
        // -------
        var nnCliente=document.createElement("button");
        nnCliente.innerHTML="Informacion de Cliente";
        this.nodeMenu.appendChild(nnCliente);
        nnCliente.onclick=function() {
            _this.ClienteConfig();
            _this.PantallaActual="Cliente";
            _this.MostrarVentana();
            _this.BotonActivoRemove();
            _this.BotonCliente.className="Activo";
        }
        this.BotonCliente=nnCliente;

        var nnPagoB=document.createElement("button");
        nnPagoB.innerHTML="Forma de Pago";
        this.nodeMenu.appendChild(nnPagoB);
        nnPagoB.onclick=function() {
            _this.PagoConfig()
            _this.PantallaActual="Pago";
            _this.MostrarVentana();
            _this.BotonActivoRemove();
            _this.BotonPago.className="Activo";
        }
        this.BotonPago=nnPagoB;

        var nnResumen=document.createElement("button");
        nnResumen.innerHTML="Resumen";
        this.nodeMenu.appendChild(nnResumen);
        nnResumen.onclick=function() {
            _this.ResumenConfig()
            _this.PantallaActual="Resumen";
            _this.MostrarVentana();
            _this.BotonActivoRemove();
            _this.BotonResumen.className="Activo";
        }
        this.BotonResumen=nnResumen;
    }

    BotonActivoRemove() {
        this.BotonLista.removeAttribute("class");
        this.BotonCliente.removeAttribute("class");
        this.BotonPago.removeAttribute("class");
        this.BotonResumen.removeAttribute("class");
    }

    CheckDatos() {
        // this.BotonLista.disabled="disabled";
        this.BotonCliente.disabled=true;
        this.BotonPago.disabled=true;
        this.BotonResumen.disabled=true;
        // Verificar Productos para habilitar cliente
        if(this.valueCarrito.length>0) {
            this.BotonCliente.disabled=false;
        }
        // Verificar Cliente para habilitar tipo de pago
        let ClienteCompleto=true;
        switch (this.ClienteTipo) {
            case "Persona":
                if(!this.PersonaNombres) {
                    ClienteCompleto=false
                }
                if(!this.PersonaIdentificacion) {
                    ClienteCompleto=false
                }
                break;

            case "Empresa":
                if(!this.EmpresaNombre) {
                    ClienteCompleto=false
                }
                if(!this.EmpresaNit) {
                    ClienteCompleto=false
                }
                break;
        
            default:
                ClienteCompleto=false;
                break;
        }
        if(!this.ClienteTelefono || !this.ClienteCorreo || !this.DireccionEnvio) {
            ClienteCompleto=false
        }
        if(!ClienteCompleto) {
            return false;
        }
        this.BotonPago.disabled=false;
        
        // ---Comprobar Modo de Pago
        if(!this.PagoTipo) {
            return false;
        }
        this.BotonResumen.disabled=false;
    }

    ProductosConfig() {
        this.nodeLista.innerHTML="";
        if(this.valueCarrito.length<1) {
            var nnP=document.createElement("img");
            nnP.src=this.dirRaiz+"img/encontstruccion.png";
            nnP.className="imgzero";
            this.nodeLista.appendChild(nnP);
            var nnP=document.createElement("p");
            nnP.innerHTML="No hay elementos en el carrito de compras";
            this.nodeLista.appendChild(nnP);
        } else {
            for(var i=0;i<this.valueCarrito.length;i++) {
                this.ProductoConfig(i);
            }
        }
        this.BotonLista.className="Activo";
        this.FooterConfig();
    }
    
    ProductoConfig(i) {
        var _this=this;
        var ProductoItem=this.valueCarrito[i];
        var nnLi=document.createElement("li");
        this.nodeLista.appendChild(nnLi);
        
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
        nnContenido.className="ContenidoItem";
        nnLi.appendChild(nnContenido);

        var nnNombre=document.createElement("div");
        nnContenido.appendChild(nnNombre);
        var nnNombreA=document.createElement("a");
        nnNombreA.href=this.dirRaiz+ProductoItem.link;
        nnNombreA.innerHTML=ProductoItem.Nombre;
        nnNombre.appendChild(nnNombreA);
        var nnCantidad=document.createElement("div");
        nnCantidad.innerHTML="<b>Precio/UND: </b>"+CarritoComprasNK.NumeroAMoneda(ProductoItem.PrecioFinal)+", <b>Cantidad: </b>"+ProductoItem.Cantidad;
        nnContenido.appendChild(nnCantidad);
        var nnPrecio=document.createElement("div");
        nnPrecio.innerHTML="Total: "+CarritoComprasNK.NumeroAMoneda(ProductoItem.Precio);
        nnContenido.appendChild(nnPrecio);

        var nnControlesIitem=document.createElement("div");
        nnContenido.appendChild(nnControlesIitem);
        var nnButtonEliminar=document.createElement("button");
        nnButtonEliminar.innerHTML="Eliminar del Carrito";
        nnControlesIitem.appendChild(nnButtonEliminar);

        nnButtonEliminar.onclick=function() {
            _this.jxCarritoDel(i);
        }
    }

    ClienteConfig() {
        var _this=this;
        this.nodeCliente.innerHTML="";
        
        var nnTituloDiv=document.createElement("h2");
        nnTituloDiv.innerHTML="Facturar a nombre de: ";
        this.nodeCliente.appendChild(nnTituloDiv);
        // --------------
        var nnClienteTipoDiv=document.createElement("div");
        this.nodeCliente.appendChild(nnClienteTipoDiv);
        nnClienteTipoDiv.className="ClienteTipo_Controles";


        var nnClienteTipoPersonaLabel=document.createElement("label");
        nnClienteTipoDiv.appendChild(nnClienteTipoPersonaLabel);
        var nnClienteTipoPersonaInput=document.createElement("input");
        nnClienteTipoPersonaLabel.appendChild(nnClienteTipoPersonaInput);
        nnClienteTipoPersonaInput.type="radio";
        nnClienteTipoPersonaInput.setAttribute("value", "Persona");
        nnClienteTipoPersonaInput.name="ClienteTipo";
        var nnClienteTipoPersonaB=document.createElement("b");
        nnClienteTipoPersonaLabel.appendChild(nnClienteTipoPersonaB);
        nnClienteTipoPersonaB.innerHTML="Persona ";
        
        var nnClienteTipoEmpresaLabel=document.createElement("label");
        nnClienteTipoDiv.appendChild(nnClienteTipoEmpresaLabel);
        var nnClienteTipoEmpresaInput=document.createElement("input");
        nnClienteTipoEmpresaLabel.appendChild(nnClienteTipoEmpresaInput);
        nnClienteTipoEmpresaInput.type="radio";
        nnClienteTipoEmpresaInput.name="ClienteTipo";
        nnClienteTipoEmpresaInput.setAttribute("value", "Empresa");
        var nnClienteTipoEmpresaB=document.createElement("b");
        nnClienteTipoEmpresaLabel.appendChild(nnClienteTipoEmpresaB);
        nnClienteTipoEmpresaB.innerHTML="Empresa";

        nnClienteTipoPersonaInput.onchange=function() {
            if(this.checked) {
                _this.ClienteTipo="Persona";
                _this.ClienteConfig();
                _this.CheckDatos();
            }
        };
        
        nnClienteTipoEmpresaInput.onchange=function() {
            if(this.checked) {
                _this.ClienteTipo="Empresa";
                _this.ClienteConfig();
                _this.CheckDatos();
            }
        };
        // --------------
        
        switch (this.ClienteTipo) {
            case "Persona":
                nnClienteTipoPersonaInput.checked=true;
                var nnPersonaBox=document.createElement("div");
                this.nodeCliente.appendChild(nnPersonaBox);

                var nnPerNombresDiv=document.createElement("label");
                nnPersonaBox.appendChild(nnPerNombresDiv);
                var nnPerNombresB=document.createElement("b");
                nnPerNombresDiv.appendChild(nnPerNombresB);
                nnPerNombresB.innerHTML="Nombres: ";
                var nnPerNombresInput=document.createElement("input");
                nnPerNombresDiv.appendChild(nnPerNombresInput);
                nnPerNombresInput.type="text";
                
                var nnIdentificacionDiv=document.createElement("label");
                nnPersonaBox.appendChild(nnIdentificacionDiv);
                var nnIdentificacionB=document.createElement("b");
                nnIdentificacionDiv.appendChild(nnIdentificacionB);
                nnIdentificacionB.innerHTML="Identificacion/NIUP: ";
                var nnIdentificacionInput=document.createElement("input");
                nnIdentificacionDiv.appendChild(nnIdentificacionInput);
                nnIdentificacionInput.type="number";

                // --------------------------
                nnPerNombresInput.setAttribute("value", this.PersonaNombres);
                nnIdentificacionInput.setAttribute("value", this.PersonaIdentificacion);
                // --------------------------
                nnPerNombresInput.onkeyup=function() {
                    _this.PersonaNombres=this.value;
                    _this.CheckDatos();
                }
                nnIdentificacionInput.onkeyup=function() {
                    _this.PersonaIdentificacion=this.value;
                    _this.CheckDatos();
                }
            break;

            case "Empresa":
                nnClienteTipoEmpresaInput.checked=true;
                var nnEmpresaBox=document.createElement("div");
                this.nodeCliente.appendChild(nnEmpresaBox);

                var nnNombresDiv=document.createElement("label");
                nnEmpresaBox.appendChild(nnNombresDiv);
                var nnNombresB=document.createElement("b");
                nnNombresDiv.appendChild(nnNombresB);
                nnNombresB.innerHTML="Razon Social: ";
                var nnNombresInput=document.createElement("input");
                nnNombresDiv.appendChild(nnNombresInput);
                nnNombresInput.type="text";
                
                var nnNitDiv=document.createElement("label");
                nnEmpresaBox.appendChild(nnNitDiv);
                var nnNitB=document.createElement("b");
                nnNitDiv.appendChild(nnNitB);
                nnNitB.innerHTML="NIT: ";
                var nnNitInput=document.createElement("input");
                nnNitDiv.appendChild(nnNitInput);
                nnNitInput.type="number";

                // --------------------------
                nnNombresInput.setAttribute("value", this.EmpresaNombre);
                nnNitInput.setAttribute("value", this.EmpresaNit);
                // --------------------------
                nnNombresInput.onkeyup=function() {
                    _this.EmpresaNombre=this.value;
                    _this.CheckDatos();
                }
                nnNitInput.onkeyup=function() {
                    _this.EmpresaNit=this.value;
                    _this.CheckDatos();
                }
                
            break;
        }

        var nnContactoDiv=document.createElement("h2");
        nnContactoDiv.innerHTML="Datos de contacto: ";
        this.nodeCliente.appendChild(nnContactoDiv);

        var nnTelefonoDiv=document.createElement("label");
        this.nodeCliente.appendChild(nnTelefonoDiv);
        var nnTelefonoB=document.createElement("b");
        nnTelefonoDiv.appendChild(nnTelefonoB);
        nnTelefonoB.innerHTML="Telefono: ";
        var nnTelefonoInput=document.createElement("input");
        nnTelefonoDiv.appendChild(nnTelefonoInput);
        nnTelefonoInput.type="number";
        
        var nnCorreoDiv=document.createElement("label");
        this.nodeCliente.appendChild(nnCorreoDiv);
        var nnCorreoB=document.createElement("b");
        nnCorreoDiv.appendChild(nnCorreoB);
        nnCorreoB.innerHTML="Correo: ";
        var nnCorreoInput=document.createElement("input");
        nnCorreoDiv.appendChild(nnCorreoInput);
        nnCorreoInput.type="text";
        // -----------------------
        nnTelefonoInput.setAttribute("value", this.ClienteTelefono);
        nnCorreoInput.setAttribute("value", this.ClienteCorreo);
        // -----------------------
        nnCorreoInput.onkeyup=function() {
            _this.ClienteCorreo=this.value;
            _this.CheckDatos();
        }
        nnTelefonoInput.onkeyup=function() {
            _this.ClienteTelefono=this.value;
            _this.CheckDatos();
        }
        // -----------------------
        var nnTituloDieccionDiv=document.createElement("h2");
        nnTituloDieccionDiv.innerHTML="Dirección de envio: ";
        this.nodeCliente.appendChild(nnTituloDieccionDiv);
        
        var nnDireccionDiv=document.createElement("label");
        this.nodeCliente.appendChild(nnDireccionDiv);
        var nnDireccionB=document.createElement("b");
        nnDireccionDiv.appendChild(nnDireccionB);
        nnDireccionB.innerHTML="Dirección: ";
        var nnDireccionInput=document.createElement("input");
        nnDireccionDiv.appendChild(nnDireccionInput);
        nnDireccionInput.type="text";
        nnDireccionInput.setAttribute("value", this.DireccionEnvio);
        nnDireccionInput.onkeyup=function() {
            _this.DireccionEnvio=this.value;
            _this.CheckDatos();
        }
    }
    
    PagoConfig() {
        var _this=this;
        this.nodeBanco.innerHTML="";
        
        var nnTituloDiv=document.createElement("h2");
        nnTituloDiv.innerHTML="Forma de Pago";
        this.nodeBanco.appendChild(nnTituloDiv);

        var nnDirectoDiv=document.createElement("label");
        this.nodeBanco.appendChild(nnDirectoDiv);
        var nnDirectoInput=document.createElement("input");
        nnDirectoDiv.appendChild(nnDirectoInput);
        nnDirectoInput.type="radio";
        nnDirectoInput.name="pagotipo";
        nnDirectoInput.setAttribute("value", "ContactoDirecto");
        var nnDirectoB=document.createElement("b");
        nnDirectoDiv.appendChild(nnDirectoB);
        nnDirectoB.innerHTML="Contacto Directo: Whatsapp | Telegram | Llamada";
        
        var nnOnlineWompiDiv=document.createElement("label");
        this.nodeBanco.appendChild(nnOnlineWompiDiv);
        var nnOnlineWompiInput=document.createElement("input");
        nnOnlineWompiDiv.appendChild(nnOnlineWompiInput);
        nnOnlineWompiInput.type="radio";
        nnOnlineWompiInput.name="pagotipo";
        nnOnlineWompiInput.setAttribute("value", "OnlineWompi");
        var nnOnlineWompiB=document.createElement("b");
        nnOnlineWompiDiv.appendChild(nnOnlineWompiB);
        nnOnlineWompiB.innerHTML="Pago Online: WOMPI";

        var nnRetiroSedeDiv=document.createElement("label");
        this.nodeBanco.appendChild(nnRetiroSedeDiv);
        var nnRetiroSedeInput=document.createElement("input");
        nnRetiroSedeDiv.appendChild(nnRetiroSedeInput);
        nnRetiroSedeInput.type="radio";
        nnRetiroSedeInput.name="pagotipo";
        nnRetiroSedeInput.setAttribute("value", "RetiroSede");
        var nnRetiroSedeB=document.createElement("b");
        nnRetiroSedeDiv.appendChild(nnRetiroSedeB);
        nnRetiroSedeB.innerHTML="Retirar en nuestra sede";
        // -----------------------
        if(this.PagoTipo=="ContactoDirecto") {
            nnDirectoInput.checked=true;
        } else if (this.PagoTipo=="OnlineWompi") {
            nnOnlineWompiInput.checked=true;
        } else if (this.PagoTipo=="RetiroSede") {
            nnRetiroSedeInput.checked=true;
        } else {
            // this.PagoTipo="ContactoDirecto"
            // this.PagoConfig();
        }
        // -----------------------
        nnDirectoInput.onchange=function() {
            if(this.checked) {
                _this.PagoTipo=this.value;
                _this.CheckDatos();
            }
        }
        nnOnlineWompiInput.onchange=function() {
            if(this.checked) {
                _this.PagoTipo=this.value;
                _this.CheckDatos();
            }
        }
        nnRetiroSedeInput.onchange=function() {
            if(this.checked) {
                _this.PagoTipo=this.value;
                _this.CheckDatos();
            }
        }
        // -----------------------
        return true;
    }

    ResumenConfig() {
        var _this=this;
        this.nodeResumen.innerHTML="";
        
        var nnTituloDiv=document.createElement("h2");
        nnTituloDiv.innerHTML="Resumen";
        this.nodeResumen.appendChild(nnTituloDiv);

        var nnTitulo2Div=document.createElement("h3");
        nnTitulo2Div.innerHTML="Productos: "+this.valueCarrito.length;
        this.nodeResumen.appendChild(nnTitulo2Div);
        var nnResumenProductos_Lista=document.createElement("ul");
        nnResumenProductos_Lista.className="ResumenProductos";
        this.nodeResumen.appendChild(nnResumenProductos_Lista);
        this.valueCarrito.forEach(producto => {
            var nnResumenProductoItem=document.createElement("li");
            nnResumenProductos_Lista.appendChild(nnResumenProductoItem);
            // -----
            var nnResumenProductoItem_nombre=document.createElement("div");
            nnResumenProductoItem.appendChild(nnResumenProductoItem_nombre);
            nnResumenProductoItem_nombre.innerHTML="<b>["+producto.Cantidad+"]</b> " +producto.Nombre;
            var nnResumenProductoItem_precio=document.createElement("div");
            nnResumenProductoItem.appendChild(nnResumenProductoItem_precio);
            nnResumenProductoItem_precio.innerHTML="$ "+producto.Precio+" COP";
        });
        
        var nnTituloCliente=document.createElement("h3");
        nnTituloCliente.innerHTML="Informacion de cliente: ";
        this.nodeResumen.appendChild(nnTituloCliente);
        var nnResumenClienteBox=document.createElement("ul");
        this.nodeResumen.appendChild(nnResumenClienteBox);
        switch (this.ClienteTipo) {
            case "Persona":
                var nnResumenCliente_ClienteTipo=document.createElement("li");
                nnResumenClienteBox.appendChild(nnResumenCliente_ClienteTipo);
                nnResumenCliente_ClienteTipo.innerHTML="Persona Natural";
                var nnResumenCliente_Nombre=document.createElement("li");
                nnResumenClienteBox.appendChild(nnResumenCliente_Nombre);
                nnResumenCliente_Nombre.innerHTML="<b>Nombre: </b>"+this.PersonaNombres+" | <b>CC/NUIP:</b> "+this.PersonaIdentificacion;
                break;

            case "Empresa":
                var nnResumenCliente_ClienteTipo=document.createElement("li");
                nnResumenClienteBox.appendChild(nnResumenCliente_ClienteTipo);
                nnResumenCliente_ClienteTipo.innerHTML="Persona Juridica";
                var nnResumenCliente_Nombre=document.createElement("li");
                nnResumenClienteBox.appendChild(nnResumenCliente_Nombre);
                nnResumenCliente_Nombre.innerHTML="<b>Razon Social:</b> "+this.EmpresaNombre+" | <b>NIT:</b> "+this.EmpresaNit+"-"+this.EmpresaNitDV;
                break;
        
            default:
                break;
        }
        
        var nnResumenCliente_Telefono=document.createElement("li");
        nnResumenClienteBox.appendChild(nnResumenCliente_Telefono);
        nnResumenCliente_Telefono.innerHTML="<b>Tel:</b> "+this.ClienteTelefono+" | <b>Email:</b> "+this.ClienteCorreo;
        
        var nnTituloPago=document.createElement("h3");
        nnTituloPago.innerHTML="Informacion de pago: ";
        this.nodeResumen.appendChild(nnTituloPago);
        var nnResumenPagoBox=document.createElement("ul");
        this.nodeResumen.appendChild(nnResumenPagoBox);
        var nnResumenCliente_PagoTipo=document.createElement("li");
        nnResumenCliente_PagoTipo.className="ResumenPago";
        nnResumenPagoBox.appendChild(nnResumenCliente_PagoTipo);
        nnResumenCliente_PagoTipo.innerHTML=this.PagoTipo;

        var nnConfirmarCompra=document.createElement("button");
        nnConfirmarCompra.innerHTML="Confirmar Compra";
        nnConfirmarCompra.className="BT_ConfirmarCompra";
        this.nodeResumen.appendChild(nnConfirmarCompra);
        nnConfirmarCompra.onclick=function() {
            _this.jxConfirmarCompra();
        }
    }



    FooterConfig() {
        var _this=this;
        this.nodeFooter.innerHTML="";

        var PrecioTotal=0;
        var ProductosTotal=0;
        var ItemsTotal=0;
        this.valueCarrito.forEach(element => {
            PrecioTotal+=element.Precio;
            ProductosTotal+=1;
            ItemsTotal+=element.Cantidad;
        });

        var nnItemsDescripcion=document.createElement("div");
        nnItemsDescripcion.innerHTML="Cantidad de productos: "+ProductosTotal+", Total Items: "+ItemsTotal;
        this.nodeFooter.appendChild(nnItemsDescripcion);
        var nnTituloPrecio=document.createElement("h3");
        nnTituloPrecio.innerHTML="Total a pagar: "+CarritoComprasNK.NumeroAMoneda(PrecioTotal);
        this.nodeFooter.appendChild(nnTituloPrecio);
    }

    jxCarritoGet() {
        "use strict";
        // -------
        this.nodeStatus.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.loading="lazy";
        imgLoading.encoding="async";
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        
        this.nodeStatus.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("ProductoNK", "CarritoGet");
		// fd.append("KeyJX", this.KeyJX);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("CarritoCompras::jxCarritoGet()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            this.nodeStatus.innerHTML="";
			if(data.RespuestaBool) {
                this.valueCarrito=data.Carrito;
                this.HeaderConfig();
                this.MenuConfig()
                this.ProductosConfig();
                this.PantallaActual="Lista";
                this.MostrarVentana();
                this.CheckDatos();
            } else {
                var errorImg=document.createElement("img");
                errorImg.encoding="async";
                errorImg.loading="lazy";
                errorImg.src=this.dirRaiz+"img/encontstruccion.png";
                this.nodeStatus.appendChild(errorImg);
                var errorP=document.createElement("p");
                errorP.innerHTML="Lo sentimos, esta caracteristica esta disponible solo para usuarios registrados.";
                this.nodeFooter.appendChild(errorP);
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}

    jxCarritoDel(i) {
        "use strict";
        // -------
        this.nodeStatus.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.loading="lazy";
        imgLoading.encoding="async";
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        
        this.nodeStatus.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("ProductoNK", "CarritoDel");
		fd.append("CarritoID", this.valueCarrito[i].CarritoID);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatus.removeChild(imgLoading);
            console.info("CarritoCompras::jxCarritoDel()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            this.nodeStatus.innerHTML="";
			if(data.RespuestaBool) {
                this.jxCarritoGet();
            } else {
                
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}
    
    jxConfirmarCompra() {
        "use strict";
        // -------
        this.nodeStatus.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.loading="lazy";
        imgLoading.encoding="async";
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        
        this.nodeStatus.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("CarritoComprasNK", "ConfirmarCompra");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ClienteTipo", this.ClienteTipo);
		fd.append("PersonaNombre", this.PersonaNombres);
		fd.append("PersonaID", this.PersonaIdentificacion);
		fd.append("EmpresaNombre", this.EmpresaNombre);
		fd.append("EmpresaNIT", this.EmpresaNit);
		fd.append("EmpresaNITDV", this.EmpresaNitDV);
		fd.append("ClienteTelefono", this.ClienteTelefono);
		fd.append("ClienteCorreo", this.ClienteCorreo);
		fd.append("DireccionDepartamento", this.DireccionDepartamento);
		fd.append("DireccionMunicipio", this.DireccionMunicipio);
		fd.append("DireccionEnvio", this.DireccionEnvio);
		fd.append("PagoTipo", this.PagoTipo);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            this.nodeStatus.removeChild(imgLoading);
            console.info("CarritoCompras::jxConfirmarCompra()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            this.nodeStatus.innerHTML="";
			if(data.RespuestaBool) {
                console.info("Compra Confirmada");
                globalThis.alert("Compra Confirmada");
                this.jxCarritoGet();
            } else {
                this.nodeStatus.innerHTML=data.RespuestaError;
                this.nodeStatus.focus();
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}

    static NumeroAMoneda(NumeroIn) {
		let precio = NumeroIn;
        let options = { style: 'currency', currency: 'COP', minimumFractionDigits: 0 };
        let precioformateado = precio.toLocaleString('es-CO', options);
		return precioformateado
	}
}