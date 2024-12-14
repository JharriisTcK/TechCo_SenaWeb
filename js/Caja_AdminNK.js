/*jshint esversion: 6 */
class Caja_AdminNK  {
	constructor(nodeIn, urlAction, dirRaiz, objOptionsIn) {
        this.urlAction=urlAction;
        this.dirRaiz=dirRaiz;
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend: objOptionsIn.valuesSend||[],
            loadingImg: objOptionsIn.loadingImg || dirRaiz+"img/loading.gif"
        };
        //--------------------------
        this.nodeObj=nodeIn || document.createElement("div");
        this.nodeObj.className="Caja_AdminNK";
        this.nodeHeader=document.createElement("h1");
        this.nodeObj.appendChild(this.nodeHeader);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeFormularioNew=document.createElement("div");
        this.nodeFormularioNew.className="FormBox";
        this.nodeObj.appendChild(this.nodeFormularioNew);
        this.nodeListaBox=document.createElement("div");
        this.nodeListaBox.className="ListaBox";
        this.nodeObj.appendChild(this.nodeListaBox);
        this.nodeLista=document.createElement("ul");
        this.nodeObj.appendChild(this.nodeLista);
        //--------------------------
        this.valueCotizaciones=[];
        //--------------------------
        this.HeaderConfig();
        if(this.objOptions.mostrarFormulario) {
            // this.FormConfig();
        }
        this.jxCajaCotizacionesGet();
    }

    HeaderConfig() {
        var _this=this;
        this.nodeHeader.innerHTML="Caja | Administrador";
    }

    CotizacionesConfig() {
        this.nodeListaBox.innerHTML="";
        // var nnTituloTokens=document.createElement("h2");
        // nnTituloTokens.innerHTML="Tokens - Administrador";
        // this.nodeListaBox.appendChild(nnTituloTokens);
        var nnListaCotizaciones=document.createElement("ul");
        this.nodeListaBox.appendChild(nnListaCotizaciones);
        this.nodeLista=nnListaCotizaciones;
        

        if(this.valueCotizaciones.length) {
            for(var i=0; i<this.valueCotizaciones.length;i++) {
                this.CotizacionConfig(i);
            }
        } else {
            var nnLiZero=document.createElement("div");
            nnLiZero.className="zeroItem";
            this.nodeListaBox.appendChild(nnLiZero);
            var nnImgZero=document.createElement("img");
            nnImgZero.className="zeroItemImg";
            nnImgZero.src=this.dirRaiz+"img/encontstruccion.png";
            nnLiZero.appendChild(nnImgZero);
        }
    }

    CotizacionConfig(Itera) {
        var _this=this;
        var CotizacionItem=this.valueCotizaciones[Itera];
        var nnLi=document.createElement("li");
        nnLi.className=(CotizacionItem.PagoConfirmado)?"Confirmado":"Pendiente";
        this.nodeLista.appendChild(nnLi);
        
        var nnTitulo=document.createElement("div");
        nnLi.appendChild(nnTitulo);
        nnTitulo.innerHTML=CotizacionItem.Titulo;

        var nnPrecioTotal=document.createElement("div");
        nnLi.appendChild(nnPrecioTotal);
        nnPrecioTotal.innerHTML="$ "+CotizacionItem.PrecioTotal;

        //-----------------
        var nnControles=document.createElement("div");
        nnLi.appendChild(nnControles);
        //-----------------
        var nnBRevisar=document.createElement("a");
        nnBRevisar.innerHTML="Revisar Cotizacion";
        nnBRevisar.href="CajaRevision.php?CotizacionID="+CotizacionItem.CotizacionVentaID;
        nnBRevisar.className="Button";
        nnControles.appendChild(nnBRevisar);
        nnBRevisar.onclick=function() {
            
        };
        //-----------------
        var nnBEliminar=document.createElement("button");
        nnControles.appendChild(nnBEliminar);
        nnBEliminar.innerHTML="Eliminar";
        nnBEliminar.className="ButtonEliminar";

        nnBEliminar.onclick=function() {
            if(!confirm("Estas seguro que deseas eliminar token de: \n"+CotizacionItem.Correo)) {
                return false;
            }
            nnBEliminar.disabled=true;
            _this.jxEliminar(CotizacionItem.TokenAdminID, nnBEliminar);
        };
    }
    
    jxCajaCotizacionesGet() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.dirRaiz+this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("Caja_AdminNK", "CotizacionesGet");
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
            console.info("Caja_AdminNK::jxCajaCotizacionesGet");
            console.log(data);
            if(data.RespuestaBool) {
                this.valueCotizaciones=data.Cotizaciones;
                this.CotizacionesConfig();
            }
        })
        .catch(error=>{
            console.warn("jxTokensGet Error: "+error);
        });        
    }

    jxPublicar(ServicioID, Button) {
        var fd=new FormData();
        fd.append("Servicios_AdminBoard", "Publicar");
        fd.append("ServicioID", ServicioID);
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
            Button.disabled=false;
            console.group("ServiciosNKAdmin::jxPublicar");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(error);
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.jxUsuariosGet();
            }
        })
        .catch(error=>{
            this.nodeStatusBox.innerHTML="ServiciosNK Publicar Error: "+error;
        });        
    }

    jxEliminar(TokenAdminID, Button) {
        Button.disabled=true;
        var fd=new FormData();
        fd.append("TokensBoard_AdminNK", "TokenAdminEliminar");
        fd.append("TokenID", TokenAdminID);
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
            Button.disabled=false;
            console.group("ServiciosNKAdmin::jxPublicar");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(error);
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.jxCajaCotizacionesGet();
            }
        })
        .catch(error=>{
            Button.innerHTML="ServiciosNK Publicar Error: "+error;
        });        
    }
}
