/*jshint esversion: 6 */
class TokensBoard_AdminNK  {
	constructor(nodeID, urlAction, dirRaiz, objOptionsIn) {
        this.urlAction=urlAction;
        this.KeyJX="";
        this.dirRaiz=dirRaiz;
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend: objOptionsIn.valuesSend||[],
            mostrarPublicados: objOptionsIn.mostrarPublicados || true,
            mostrarFormulario: objOptionsIn.mostrarFormulario || true,
            loadingImg: objOptionsIn.loadingImg || dirRaiz+"img/loading.gif"
        };
        //--------------------------
        this.nodeObj=document.querySelector(nodeID) || document.createElement("div");
        this.nodeObj.className="TokensBoard_AdminNK";
        this.nodeHeader=document.createElement("div");
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
        this.valueTokensAdmin=[];
        //--------------------------
        this.HeaderConfig();
        if(this.objOptions.mostrarFormulario) {
            // this.FormConfig();
        }
        this.jxTokensAdminGet();
    }

    HeaderConfig() {
        var _this=this;
        this.nodeHeader.innerHTML="";
        var nnTitulo=document.createElement("h1");
        this.nodeHeader.appendChild(nnTitulo);
        nnTitulo.innerHTML="Tokens | Administrador";
    }

    TokensConfig() {
        this.nodeListaBox.innerHTML="";
        // var nnTituloTokens=document.createElement("h2");
        // nnTituloTokens.innerHTML="Tokens - Administrador";
        // this.nodeListaBox.appendChild(nnTituloTokens);
        var nnListaTokens=document.createElement("ul");
        this.nodeListaBox.appendChild(nnListaTokens);
        this.nodeLista=nnListaTokens;
        

        if(this.valueTokensAdmin.length) {
            for(var i=0; i<this.valueTokensAdmin.length;i++) {
                this.TokenConfig(i);
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
        if(!this.objOptions.mostrarPublicados) {
            this.nodeActivosBox.style.display="none";
        }
    }

    TokenConfig(Itera) {
        var _this=this;
        var TokenItem=this.valueTokensAdmin[Itera];
        var nnLi=document.createElement("li");
        this.nodeLista.appendChild(nnLi);
        var nnPerfilFoto=document.createElement("img");
        nnPerfilFoto.src=this.dirRaiz+"img/UsuarioPerfilNone.png";
        nnLi.appendChild(nnPerfilFoto);
        var nnNombres=document.createElement("div");
        nnNombres.innerHTML=TokenItem.Nombres;
        nnLi.appendChild(nnNombres);
        var nnTokenCorreo=document.createElement("div");
        nnLi.appendChild(nnTokenCorreo);
        nnTokenCorreo.innerHTML=TokenItem.Correo;
        var nnTokenTipo=document.createElement("div");
        nnLi.appendChild(nnTokenTipo);
        nnTokenTipo.innerHTML=TokenItem.TokenTipo;

        //-----------------
        var nnControles=document.createElement("div");
        nnLi.appendChild(nnControles);
        //-----------------
        var nnBRecuperar=document.createElement("button");
        nnBRecuperar.innerHTML="Recuperar";
        nnBRecuperar.className="ButtonEditar";
        nnControles.appendChild(nnBRecuperar);
        nnBRecuperar.onclick=function() {
            _this.RecuperarTokenButton(Itera, nnBRecuperar);
        };
        //-----------------
        var nnBEliminar=document.createElement("button");
        nnControles.appendChild(nnBEliminar);
        nnBEliminar.innerHTML="Eliminar";
        nnBEliminar.className="ButtonEliminar";

        nnBEliminar.onclick=function() {
            if(!confirm("Estas seguro que deseas eliminar token de: \n"+TokenItem.Correo)) {
                return false;
            }
            nnBEliminar.disabled=true;
            _this.jxEliminar(TokenItem.TokenAdminID, nnBEliminar);
        };
    }

    RecuperarTokenButton(Itera, nnButton) {
        var TokenTipo=this.valueTokensAdmin[Itera].TokenTipo;
        var TokenID=this.valueTokensAdmin[Itera].TokenAdminID;
        switch (TokenTipo) {
            case "ColaboradorPassRecover":
                globalThis.open(this.dirRaiz+"RecuperacionColaborador/"+TokenID+"/");
            break;
            
            case "UsuarioNew":
                globalThis.open(this.dirRaiz+"UsuarioNuevo/"+TokenID+"/");
            break;

            case 'UsuarioPassRecover':
                globalThis.open(this.dirRaiz+"RecuperacionUsuario/"+TokenID+"/");
            break;
        }
        
    }
    
    jxTokensAdminGet() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("TokensBoard_AdminNK", "TokensAdminGet");
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
            console.info("TokensBoard_AdminNK::jxTokensAdminGet");
            console.log(data);
            if(data.RespuestaBool) {
                this.valueTokensAdmin=data.Tokens;
                this.TokensConfig();
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
                this.jxTokensAdminGet();
            }
        })
        .catch(error=>{
            Button.innerHTML="ServiciosNK Publicar Error: "+error;
        });        
    }
}
