/*jshint esversion: 6 */
class UsuariosNK_Admin  {
	constructor(nodeID, urlAction, dirRaiz, KeyJX, objOptionsIn) {
        this.urlAction=urlAction;
        this.KeyJX=KeyJX;
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
        this.nodeObj.className="UsuariosNK_Admin";
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
        this.nodeTokensAdminBox=document.createElement("div");
        this.nodeTokensAdminBox.className="TokenAdminBox";
        this.nodeTokensAdminBox_Lista=null;
        this.nodeObj.appendChild(this.nodeTokensAdminBox);
        //--------------------------
        this.valueUsuarios=[];
        this.valueTokensAdmin=[];
        //--------------------------
        this.HeaderConfig();
        if(this.objOptions.mostrarFormulario) {
            this.FormConfig();
        }
        this.jxUsuariosGet();
    }

    HeaderConfig() {
        var _this=this;
        this.nodeHeader.innerHTML="";
        var nnTitulo=document.createElement("h1");
        this.nodeHeader.appendChild(nnTitulo);
        nnTitulo.innerHTML="Usuarios | Administrador";
    }

    FormConfig() {
        this.nodeFormularioNew.innerHTML="";
        var _this=this;
        /* -----------------------
        var PeriodistasSelect=[];
        for(var i=0; i<this.valuePeriodistas.length; i++) {
            var PeriodistaSelectBuff=[
                this.valuePeriodistas[i].Nombre+" "+this.valuePeriodistas[i].Apellido,
                this.valuePeriodistas[i].id_periodista,
                false
            ];
            PeriodistasSelect.push(PeriodistaSelectBuff);
        }
        // */
        var FormArticuloNew=new FormNK(this.nodeFormularioNew,this.urlAction,"Usuarios_Admin",this.dirRaiz);
        FormArticuloNew.setTitulo("Registrar Nuevo Usuario");
		FormArticuloNew.setTexto("Nombres","UsuarioNombres","",true);
		FormArticuloNew.setTexto("Apellidos: ","UsuarioApellidos","",true);
		FormArticuloNew.setTexto("Correo: ","UsuarioCorreo","",true);
		FormArticuloNew.setHidden("UsuariosNK_Admin","UsuarioNew");
		FormArticuloNew.finalizar("Registrar Nuevo Usuario");
		FormArticuloNew.setCallbackSubmit(function() {
            _this.jxUsuariosGet();
        });
    }


    UsuariosConfig() {
        this.nodeListaBox.innerHTML="";
        this.nodeLista.innerHTML="";
        
        var nnTituloPendientes=document.createElement("h2");
        nnTituloPendientes.innerHTML="Usuarios";
        this.nodeListaBox.appendChild(nnTituloPendientes);
        
        this.nodeListaBox.appendChild(this.nodeLista);

        if(this.valueUsuarios.length) {
            for(var i=0; i<this.valueUsuarios.length;i++) {
                this.UsuarioConfig(i);
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
    
    
    UsuarioConfig(Itera) {
        var _this=this;
        let UsuarioItem=this.valueUsuarios[Itera];
        var nnLi=document.createElement("li");
        nnLi.className="UsuarioItem";
        this.nodeLista.appendChild(nnLi);
        var nnLink=document.createElement("a");
        nnLink.href="javascript:void(0)";
        // nnLink.target="_BLANK";
        nnLi.appendChild(nnLink);
        var nnImgPortada=document.createElement("img");
        nnLink.appendChild(nnImgPortada);
        if(UsuarioItem.PerfilT) {
            nnImgPortada.src=this.dirRaiz+UsuarioItem.PerfilS;
        } else {
            nnImgPortada.src=this.dirRaiz+"img/perfilnofoto.jpg";
        }
        var nnNombre=document.createElement("div");
        nnNombre.innerHTML=UsuarioItem.Nombres+" "+UsuarioItem.Apellidos;
        nnLink.appendChild(nnNombre);
        var nnUsuarioStat=document.createElement("div");
        nnLi.appendChild(nnUsuarioStat);
        //-----------------
        nnLink.onclick=function() {
            _this.jxUsuarioLoginAdmin(UsuarioItem.UsuarioID);
        }
        var nnControles=document.createElement("div");
        nnLi.appendChild(nnControles);
        //-----------------
        var nnBHabilitar=document.createElement("button");
        nnControles.appendChild(nnBHabilitar);
        if(UsuarioItem.EsColaborador) {
            nnBHabilitar.innerHTML="Colaborador: OFF";
        } else {
            nnBHabilitar.innerHTML="Colaborador: ON";
        }
        nnBHabilitar.onclick=function() {
            nnBHabilitar.disabled=true;
            _this.jxColaboradorStat_Cambiar(UsuarioItem.UsuarioID, nnBHabilitar);
        };
        //-----------------
        var nnBSolToken=document.createElement("button");
        nnControles.appendChild(nnBSolToken);
        nnBSolToken.innerHTML="Solicitar Token Contraseña";
        nnBSolToken.onclick=function() {
            nnBSolToken.disabled=true;
            _this.jxColaborador_TokenPassGet(UsuarioItem.Correo, nnBSolToken);
        };

        //-----------------
        var nnBEliminar=document.createElement("button");
        nnControles.appendChild(nnBEliminar);
        nnBEliminar.innerHTML="Eliminar";
        nnBEliminar.className="ButtonEliminar";

        nnBEliminar.onclick=function() {
            var UsuarioNombre=UsuarioItem.Nombres;
            if(!confirm("Estas seguro que deseas eliminar item: \n"+UsuarioNombre)) {
                return false;
            }
            nnBEliminar.disabled=true;
            _this.jxUsuarioEliminar(UsuarioItem.UsuarioID, nnBEliminar);
        };
    }

    UsuarioEditarButton(Itera, Button) {
        Button.disabled=true;
        var ServicioID=this.valueServicios[Itera].ServicioID;
        globalThis.open(this.dirRaiz+"PanelNK/Servicios/ServicioEditar.php?ID="+ServicioID);
        // globalThis.open(this.dirRaiz+"PanelNK/Servicios/ServicioEditar.php?ID="+ServicioID, "_BLANK");
    }


    jxUsuariosGet() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.src=this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        var fd=new FormData();
        fd.append("UsuariosNK_Admin", "UsuariosGet");
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
            console.group("UsuariosNK_Admin::jxUsuariosGet");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.valueUsuarios=data.Usuarios;
                this.UsuariosConfig();
            }
        })
        .catch(error=>{
            console.warn("Servicio New Error: "+error);
        });        
    }

    jxColaboradorStat_Cambiar(UsuarioID, Button) {
        Button.disabled=false;
        var fd=new FormData();
        fd.append("UsuariosNK_Admin", "ColaboradorStat");
        fd.append("UsuarioID", UsuarioID);
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
            console.info("UsuariosNKAdmin::jxColaboradorStat");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(error);
            }
            console.log(data);
            if(data.RespuestaBool) {
                this.jxUsuariosGet();
            }
        })
        .catch(error=>{
            this.nodeStatusBox.innerHTML="UsuariosNK Status Colaborador Error: "+error;
        });        
    }

    jxColaborador_TokenPassGet(Correo, Button) {
        Button.disabled=false;
        var fd=new FormData();
        fd.append("UsuariosNK_Admin", "TokenPassGet");
        fd.append("UsuarioCorreo", Correo);
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
            console.info("UsuariosNKAdmin::jxColaborador_TokenPassGet");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(error);
            }
            console.log(data);
            if(data.RespuestaBool) {
                Button.innerHTML="Token Creado Correctamente";
            } else {
                Button.innerHTML=data.RespuestaError;
            }
        })
        .catch(error=>{
            this.nodeStatusBox.innerHTML="UsuariosNK Token Pass Error: "+error;
        });        
    }

    jxUsuarioLoginAdmin(UsuarioID) {
        globalThis.localStorage.removeItem("UsuarioKeyJX");
        let fd=new FormData();
        fd.append("UsuariosNK_Admin", "LoginAsAdmin");
        fd.append("UsuarioID", UsuarioID);
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
            console.info("UsuariosNKAdmin::jxUsuarioLoginAdmin");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.warn(error);
            }
            console.log(data);
            if(data.RespuestaBool) {
                globalThis.localStorage.setItem("UsuarioKeyJX", data.Token);
                globalThis.location=this.dirRaiz+"Usuario/";                
            }
        })
        .catch(error=>{
            this.nodeStatusBox.innerHTML="UsuariosNK Status Colaborador Error: "+error;
        });        
    }

    jxUsuarioEliminar(UsuarioID, Button) {
        Button.disabled=true;
        var fd=new FormData();
        fd.append("UsuariosNK_Admin", "UsuarioDelete");
        fd.append("UsuarioID", UsuarioID);
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
            console.group("ServiciosNKAdmins::jxEliminar");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            console.log(data);
            console.groupEnd();
            if(data.RespuestaBool) {
                this.jxUsuariosGet();
            }
        })
        .catch(error=>{
            this.nodeStatusBox.innerHTML="Eliminar Error: "+error;
        });        
        Button.disabled=false;
    }
}
