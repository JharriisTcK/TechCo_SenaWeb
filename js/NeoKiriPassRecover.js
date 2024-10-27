
class NeoKiriPassRecover  {
	constructor(nodeObj, urlAction, dirRaiz, KeyJX, objOptionsIn) {
        this.urlAction=urlAction||"NO Definido";
        this.KeyJX=KeyJX||"";
        this.dirRaiz=dirRaiz||"";
        objOptionsIn=objOptionsIn||{};
        this.objOptions={
            valuesSend: objOptionsIn.valuesSend||[],
            loadingImg: objOptionsIn.loadingImg || dirRaiz+"img/loading.gif"
        };
        //--------------------------
        this.nodeObj=nodeObj || document.createElement("div");
        this.nodeObj.className="NeoKiri_PassRecover";
        this.nodeObj.innerHTML="";
        this.nodeHeader=document.createElement("div");
        this.nodeHeader.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeader);
        this.nodeStatusBox=document.createElement("div");
        this.nodeStatusBox.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatusBox);
        this.nodeContenidoBox=document.createElement("div");
        this.nodeContenidoBox.className="ContenidoBox";
        this.nodeObj.appendChild(this.nodeContenidoBox);
        this.nodeHide=document.createElement("div");
        //--------------------------
        this.valueToken={};
        //--------------------------
        this.HeaderConfig();
        this.jxInfoGet();
    }

    jxInfoGet() {
        this.nodeStatusBox.innerHTML="";
        var loadingImg=document.createElement("img");
        loadingImg.loading="lazy";
        loadingImg.encoding="async";
        loadingImg.src=this.objOptions.loadingImg;
        this.nodeStatusBox.appendChild(loadingImg);
        //-------------------------
        if(!this.KeyJX) {
            console.warn("Sin llave JX: ");
            return false;
        }
        //-------------------------
        var fd=new FormData();
        fd.append("NeoKiriPassRecover", "InfoGet");
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
            console.info("NeoKiriPassRecover::jxInfoGet()");
            try {
                data=JSON.parse(data);
            } catch (error) {
                console.error(data);
                return false;
            }
            console.log(data);
            if(data.RespuestaBool) {
                this.valueToken=data.Token;
                this.HeaderConfig();
                this.ContenidoConfig();
            } else {
                
            }
        })
        .catch(error=>{
            console.warn(error);
        });
    }

    jxPassSet(e) {
        e.preventDefault();
        console.log("Form Detenido");
        nnSubmit.disabled=true;
        
        var val1=nnPass1.value;
        var val2=nnPass2.value;
        
        if(val1!=val2) {
            this.nodeStatusBox.innerHTML="Las contraseñas no coinciden";
            return false;
        }
        var fd=new FormData();
        fd.append("TokenAdmin", "PassSet");
        fd.append("Pass1", val1);
        fd.append("Pass2", val2);
        fd.append("TokenID", this.KeyJX);
        
        fetch("../../ActionToken.php", {method: "POST", body: fd})
        .then(resp=>resp.text())
        .then(data=>{
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
            if(data.RespuestaBool) {
                this.nodeStatusBox.innerHTML="La contraseña se ha guardado correctamente, ya puedes iniciar sesion en nuestros servicios. :D";
                setTimeout(function() {
                    globalThis.location="../../ColaboradorNK/";
                }, 2000);
            } else {
            }
        })
        .catch(err=>{
            console.error(err);
        });
    }


    HeaderConfig() {
        var nnPerfilImagen=document.createElement("img");
        nnPerfilImagen.src=this.dirRaiz+"img/UsuarioPerfilNone.png";
        this.nodeHeader.appendChild(nnPerfilImagen);
    
        var nnTitulo=document.createElement("h1");
        nnTitulo.innerHTML="Recuperacion de Contraseña";
        this.nodeHeader.appendChild(nnTitulo);
        
        var nnSubtitulo=document.createElement("h2");
        nnSubtitulo.innerHTML="Colaborador NeoKiri";
        this.nodeHeader.appendChild(nnSubtitulo);
    
        var nnDescripcion=document.createElement("p");
        nnDescripcion.innerHTML="Bienvenido, gracias por utilizar nuestros servicios y validar tu cuenta, por favor ingresa la nueva contraseña que vas a utilizar en este proceso.";
        this.nodeHeader.appendChild(nnDescripcion);
    }

    ContenidoConfig() {
        var nnForm=document.createElement("form");
        this.nodeContenidoBox.appendChild(nnForm);

        var nnPass1Label=document.createElement("label");
        nnForm.appendChild(nnPass1Label);
        var nnPass1B=document.createElement("label");
        nnPass1B.innerHTML="Ingresa nueva contraseña: ";
        nnPass1Label.appendChild(nnPass1B);
        var nnPass1=document.createElement("input");
        nnPass1.type="text";
        nnPass1.required="required";
        nnPass1Label.appendChild(nnPass1);
        
        var nnPass2Label=document.createElement("label");
        nnForm.appendChild(nnPass2Label);
        var nnPass2B=document.createElement("label");
        nnPass2B.innerHTML="Repite la nueva contraseña: ";
        nnPass2Label.appendChild(nnPass2B);
        var nnPass2=document.createElement("input");
        nnPass2.type="text";
        nnPass2.required="required";
        nnPass2Label.appendChild(nnPass2);

        var nnSubmit=document.createElement("input");
        nnSubmit.type="submit";
        nnSubmit.disabled=true;
        nnSubmit.value="Cambiar Contraseña";
        nnForm.appendChild(nnSubmit);
        
        nnPass2.addEventListener("keyup", checkpass, false);
        
        
        nnForm.onsubmit=function(e) {
            
            
        }
    }

    checkpass() {
        this.nodeStatusBox.innerHTML="";
        this.nodeStatusBox.removeAttribute("class");
        
        nnSubmit.disabled=true;
        
        var val1=nnPass1.value;
        var val2=nnPass2.value;
        
        console.log(val1);
        console.log(val2);
        
        if(val1!=val2) {
            this.nodeStatusBox.innerHTML="Las contraseñas no coinciden";
            return false;
        }
        
        nnSubmit.disabled=false;
    }
}