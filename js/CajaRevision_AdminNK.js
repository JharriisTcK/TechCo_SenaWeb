/*jshint esversion: 6 */
class CajaRevision_AdminNK  {
	constructor(nodeIn, urlAction, KeyJX, dirRaiz, objOptionsIn) {
        this.urlAction=urlAction;
        this.dirRaiz=dirRaiz;
        this.KeyJX=KeyJX;
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend: objOptionsIn.valuesSend||[],
            loadingImg: objOptionsIn.loadingImg || dirRaiz+"img/loading.gif"
        };
        //--------------------------
        this.nodeObj=nodeIn || document.createElement("div");
        this.nodeObj.className="CajaRevision_AdminNK";
        this.nodeHeader=document.createElement("h1");
        this.nodeObj.appendChild(this.nodeHeader);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        
        this.nodeCliente=document.createElement("div");
        this.nodeCliente.className="ClienteBox";
        this.nodeObj.appendChild(this.nodeCliente);
        this.nodeItems=document.createElement("div");
        this.nodeItems.className="ItemsLista";
        this.nodeObj.appendChild(this.nodeItems);
        this.nodeValores=document.createElement("div");
        this.nodeValores.className="ValoresBox";
        this.nodeObj.appendChild(this.nodeValores);
        this.nodeControles=document.createElement("div");
        this.nodeControles.className="ControlesBox";
        this.nodeObj.appendChild(this.nodeControles);
        //--------------------------
        this.valueCotizacion={};
        //--------------------------
        this.HeaderConfig();
        if(this.objOptions.mostrarFormulario) {
            // this.FormConfig();
        }
        this.jxCotizacionGet();
    }

    HeaderConfig() {
        this.nodeHeader.innerHTML="Revisar Cotización | Administrador";
    }

    ClienteConfig() {
        this.nodeCliente.innerHTML="";
        if(this.valueCotizacion.ClienteTipo=="Persona Natural") {
            var nnPersonaNombre=document.createElement("div");
            nnPersonaNombre.innerHTML="<b>Nombre: </b><span>"+this.valueCotizacion.PersonaNombres+"</span>";
            this.nodeCliente.appendChild(nnPersonaNombre);
            var nnPersonaIdentificacion=document.createElement("div");
            nnPersonaIdentificacion.innerHTML="<b>C.C. / NIUP: </b><span>"+this.valueCotizacion.PersonaIdentificacionTipo+" "+this.valueCotizacion.PersonaIdentificacion+"</span>";
            this.nodeCliente.appendChild(nnPersonaIdentificacion);
        }

        var nnClienteDireccion=document.createElement("div");
        nnClienteDireccion.innerHTML="<b>Dirección: </b><span>"+this.valueCotizacion.ClienteDireccion+"</span>";
        this.nodeCliente.appendChild(nnClienteDireccion);
        var nnClienteTelefono=document.createElement("div");
        nnClienteTelefono.innerHTML="<b>Telefono: </b><span>"+this.valueCotizacion.ClienteTelefono+"</span>";
        this.nodeCliente.appendChild(nnClienteTelefono);
        var nnClienteCorreo=document.createElement("div");
        nnClienteCorreo.innerHTML="<b>Correo: </b><span>"+this.valueCotizacion.ClienteCorreo+"</span>";
        this.nodeCliente.appendChild(nnClienteCorreo);
    }

    ItemsConfig() {
        this.nodeItems.innerHTML="";
        let ItemsCarrito=JSON.parse(this.valueCotizacion.ItemsJSON);
        console.log(ItemsCarrito);

        var ListaNode=document.createElement("ul");
        this.nodeItems.appendChild(ListaNode);
        ItemsCarrito.forEach(Item => {
            var ListaItem=document.createElement("li");
            ListaNode.appendChild(ListaItem);
            var nnItemNombre=document.createElement("div");
            nnItemNombre.innerHTML="<b>Nombre Producto: </b><span> "+Item.Nombre+"</span>";
            ListaItem.appendChild(nnItemNombre);
            var nnItemCantidad=document.createElement("div");
            nnItemCantidad.innerHTML="<b>Cantidad: </b><span> "+Item.Cantidad+"</span>";
            ListaItem.appendChild(nnItemCantidad);
            var nnPrecioUnd=document.createElement("div");
            nnPrecioUnd.innerHTML="<b>Precio/UND: </b><span>$ "+Item.PrecioUND+"</span>";
            ListaItem.appendChild(nnPrecioUnd);
            var nnItemsPrecio=document.createElement("div");
            nnItemsPrecio.innerHTML="<b>Precio: </b><span>$ "+Item.PrecioItems+"</span>"+"<b> +IVA(19%): </b><span>$ "+Item.PrecioIVA+"</span>";
            ListaItem.appendChild(nnItemsPrecio);
            var nnItemsPrecioTotal=document.createElement("div");
            nnItemsPrecioTotal.innerHTML="<b>Precio Total: </b><span>$ "+Item.Precio+"</span>";
            ListaItem.appendChild(nnItemsPrecioTotal);
        });
    }

    ValoresConfig() {
        this.nodeValores.innerHTML="";

        var nnPrecioBase=document.createElement("div");
        nnPrecioBase.innerHTML="<b>Precio Base: </b><span>$ "+this.valueCotizacion.PrecioTotalBase+"</span>";
        this.nodeValores.appendChild(nnPrecioBase);
        var nnPrecioIVA=document.createElement("div");
        nnPrecioIVA.innerHTML="<b>Precio IVA: </b><span>$ "+this.valueCotizacion.PrecioTotalIva+"</span>";
        this.nodeValores.appendChild(nnPrecioIVA);
        var nnPrecioTotal=document.createElement("div");
        nnPrecioTotal.innerHTML="<b>Precio Total: </b><span>$ "+this.valueCotizacion.PrecioTotal+"</span>";
        this.nodeValores.appendChild(nnPrecioTotal);
        var nnPagoTipo=document.createElement("div");
        nnPagoTipo.innerHTML="<b>Tipo de Pago: </b><span>"+this.valueCotizacion.PagoTipo+"</span>";
        this.nodeValores.appendChild(nnPagoTipo);
    }

    ControlesConfig() {
        this.nodeControles.innerHTML="";
        let _this=this;
        if(this.valueCotizacion.PagoConfirmado) {
            var nnNoCofirmadoP=document.createElement("p");
            nnNoCofirmadoP.innerHTML="El pago de esta venta se ha confirmado";
            this.nodeControles.appendChild(nnNoCofirmadoP);
            this.nodeControles.classList.add("Confirmado");
        } else {
            var nnNoCofirmadoP=document.createElement("p");
            nnNoCofirmadoP.innerHTML="Aun no se ha confirmado el pago, importante revisar la informacion de pago antes de proceder";
            this.nodeControles.appendChild(nnNoCofirmadoP);
            var nnNoCofirmadoButton=document.createElement("button");
            nnNoCofirmadoButton.innerHTML="Confirmar Pago";
            this.nodeControles.appendChild(nnNoCofirmadoButton);
            nnNoCofirmadoButton.onclick=function() {
                _this.jxVerificarCompra();
            }
            this.nodeControles.classList.add("Pendiente");
        }
    }
    
    jxCotizacionGet() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("CajaRevision_AdminNK", "CotizacionGet");
        fd.append("KeyJX", this.KeyJX);
        for(var i=0; i<this.objOptions.valuesSend.length; i++) {
            fd.append(
                this.objOptions.valuesSend[i][0],
                this.objOptions.valuesSend[i][1]
            );
        }
        fetch(this.urlAction, {method:"POST", body: fd})
        .then(resp=>resp.json())
        .then(data=>{
            this.nodeStatusBox.innerHTML="";
            console.info("CajaRevision_AdminNK::jxCotizacionGet");
            console.log(data);
            if(data.RespuestaBool) {
                this.valueCotizacion=data.Cotizacion;
                this.ClienteConfig();
                this.ItemsConfig();
                this.ValoresConfig();
                this.ControlesConfig();
            }
        })
        .catch(error=>{
            console.warn("jxCotizacionGet Error: "+error);
        });        
    }

    jxVerificarCompra() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("CajaRevision_AdminNK", "ConfirmarCompra");
        fd.append("KeyJX", this.KeyJX);
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
            console.info("CajaRevision_AdminNK::jxVerificarCompra");
            console.log(data);
            if(data.RespuestaBool) {
                this.jxCotizacionGet();
            } else {
                this.nodeStatusBox.innerHTML=data.RespuestaError;
            }
        })
        .catch(error=>{
            console.warn("jxCotizacionGet Error: "+error);
        });        
    }
}
