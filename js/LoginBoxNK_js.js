class LoginBoxNK {
    constructor(UrlAction, dirRaiz, ObjOptionsIn) {
        // console.info("NeoKiriLogin_JS::New()");
        // ----------------------
        ObjOptionsIn=ObjOptionsIn||{};
        this.urlAction=UrlAction||"No Definido";
        this.dirRaiz=dirRaiz||"";
        
        this.objOptions={
            valuesSend: ObjOptionsIn.valuesSend || [],
            loadingImg: ObjOptionsIn.loadingImg || "img/loading.gif",
            logoH: ObjOptionsIn.logoH || "logo.png"
        };
        // ----------------------
        this.FormIniciar_Construido=false;
        this.FormRegistrar_Construido=false;
        this.FormRecuperar_Construido=false;
        // ----------------------
        this.nodeObj=document.createElement("div");
        this.nodeObj.className="NeoKiriLogin_JS";
        
        this.nodeHeader=document.createElement("div");
        this.nodeHeader.className="HeaderBox";
        this.nodeObj.appendChild(this.nodeHeader);
        
        this.nodeContain=document.createElement("div");
        this.nodeContain.className="ContainerBox";
        this.nodeObj.appendChild(this.nodeContain);

        this.nodeMenu=document.createElement("div");
        this.nodeMenu.className="MenuBox";
        this.nodeContain.appendChild(this.nodeMenu);

        this.nodeContenido=document.createElement("div");
        this.nodeContenido.className="ContenidoBox";
        this.nodeContain.appendChild(this.nodeContenido);
        
        this.nodeStatus=document.createElement("div");
        this.nodeStatus.className="StatusBox";
        this.nodeObj.appendChild(this.nodeStatus);
        
        this.nodeFooter=document.createElement("div");
        this.nodeFooter.className="FooterBox";
        this.nodeObj.appendChild(this.nodeFooter);
        // -------------------------------------
        this.nodeHideBox=document.createElement("div");
        
        this.nodeLoginBox=document.createElement("div");
        this.nodeLoginBox.className="LoginBox";
        
        this.nodeRegistrarBox=document.createElement("div");
        this.nodeRegistrarBox.className="RegistrarBox";
        
        this.nodeRecuperarBox=document.createElement("div");
        this.nodeRecuperarBox.className="RecuperarBox";
        // -------------------------------------
        this.MenuLogin_Config();
        this.HeaderLogin_Config();
    }
    
    FormIniciar_Config() {
        // console.log("NeoKiriLogin_JS.FormIniciar_Config()");
        var _this=this;
        this.nodeLoginBox.innerHTML="";

        var nnForm=document.createElement("form");
        nnForm.method="POST";
        // nnForm.action=this.urlAction;
        this.nodeLoginBox.appendChild(nnForm)

        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Iniciar Sesion";
        nnForm.appendChild(nnTitulo);
        
        var nnCorreoLabel=document.createElement("label");
        nnForm.appendChild(nnCorreoLabel);
        var nnCorreoB=document.createElement("b");
        nnCorreoB.innerHTML="Correo Electronico: ";
        nnCorreoLabel.appendChild(nnCorreoB);
        var nnCorreo=document.createElement("input");
        nnCorreo.type="text";
        nnCorreo.required="required";
        nnCorreo.name="Login_UsuarioNick";
        nnCorreoLabel.appendChild(nnCorreo);
        
        var nnPassLabel=document.createElement("label");
        nnForm.appendChild(nnPassLabel);
        var nnPassB=document.createElement("b");
        nnPassB.innerHTML="Contraseña: ";
        nnPassLabel.appendChild(nnPassB);
        var nnPass=document.createElement("input");
        nnPass.type="password";
        nnPass.required="required";
        nnPass.name="Login_UsuarioPass";
        nnPassLabel.appendChild(nnPass);
        
        var nnStatusBox=document.createElement("div");
        nnForm.appendChild(nnStatusBox);
        
        var nnSubmit=document.createElement("input");
        nnSubmit.type="submit";
        nnSubmit.value="Iniciar Sesion";
        nnForm.appendChild(nnSubmit);
        
        var nnClear=document.createElement("input");
        nnClear.type="reset";
        nnClear.value="❌";
        nnForm.appendChild(nnClear);
        
        nnForm.onsubmit=function(e) {
            e.preventDefault();
            console.log("Registrar.....");
            _this.jxUsuarioIniciar(
                nnCorreo.value,
                nnPass.value, 
                nnStatusBox
                );
        }

        this.FormIniciar_Construido=true;
    }

    FormRegistrar_Config() {
        console.log("NeoKiriLogin_JS.FormRegistrar_Config()");
        var _this=this;
        this.nodeRegistrarBox.innerHTML="";
        
        var nnForm=document.createElement("form");
        this.nodeRegistrarBox.appendChild(nnForm)

        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Registrar Usuario";
        nnForm.appendChild(nnTitulo);
        
        var nnNombreLabel=document.createElement("label");
        nnForm.appendChild(nnNombreLabel);
        var nnNombreB=document.createElement("b");
        nnNombreB.innerHTML="Nombres: ";
        nnNombreLabel.appendChild(nnNombreB);
        var nnNombre=document.createElement("input");
        nnNombre.type="text";
        nnNombre.required="required";
        nnNombre.name="Reg_UsuarioNombres";
        nnNombreLabel.appendChild(nnNombre);
        
        var nnApellidoLabel=document.createElement("label");
        nnForm.appendChild(nnApellidoLabel);
        var nnApellidoB=document.createElement("b");
        nnApellidoB.innerHTML="Apellidos: ";
        nnApellidoLabel.appendChild(nnApellidoB);
        var nnApellido=document.createElement("input");
        nnApellido.type="text";
        nnApellido.required="required";
        nnApellido.name="Reg_UsuarioApellidos";
        nnApellidoLabel.appendChild(nnApellido);
        
        var nnCorreoLabel=document.createElement("label");
        nnForm.appendChild(nnCorreoLabel);
        var nnCorreoB=document.createElement("b");
        nnCorreoB.innerHTML="Correo: ";
        nnCorreoLabel.appendChild(nnCorreoB);
        var nnCorreo=document.createElement("input");
        nnCorreo.type="text";
        nnCorreo.required="required";
        nnCorreo.name="Reg_UsuarioCorreo";
        nnCorreoLabel.appendChild(nnCorreo);
        
        var nnStatusBox=document.createElement("div");
        nnForm.appendChild(nnStatusBox);

        var nnSubmit=document.createElement("input");
        nnSubmit.type="submit";
        nnSubmit.value="Registrar";
        nnForm.appendChild(nnSubmit);
        
        var nnClear=document.createElement("input");
        nnClear.type="reset";
        nnClear.value="❌";
        nnForm.appendChild(nnClear);
        
        nnForm.onsubmit=function(e) {
            e.preventDefault();
            console.log("Registrar.....");
            _this.jxUsuarioRegistrar(
                nnNombre.value,
                nnApellido.value,
                nnCorreo.value,
                nnStatusBox
            );
        }
        this.FormRegistrar_Construido=true;
    }
    
    FormRecuperar_Config() {
        console.log("NeoKiriLogin_JS.FormRecuperar_Config()");
        var _this=this;
        this.nodeRecuperarBox.innerHTML="";
        
        var nnForm=document.createElement("form");
        this.nodeRecuperarBox.appendChild(nnForm)
        
        var nnTitulo=document.createElement("h2");
        nnTitulo.innerHTML="Recuperar Clave de Usuario";
        nnForm.appendChild(nnTitulo);

        var nnCorreoLabel=document.createElement("label");
        nnForm.appendChild(nnCorreoLabel);
        var nnCorreoB=document.createElement("b");
        nnCorreoB.innerHTML="Correo: ";
        nnCorreoLabel.appendChild(nnCorreoB);
        var nnCorreo=document.createElement("input");
        nnCorreo.type="text";
        nnCorreo.required="required";
        nnCorreo.name="Rec_UsuarioCorreo";
        nnCorreoLabel.appendChild(nnCorreo);
        
        var nnStatusBox=document.createElement("div");
        nnForm.appendChild(nnStatusBox);

        var nnSubmit=document.createElement("input");
        nnSubmit.type="submit";
        nnSubmit.value="Recuperar";
        nnForm.appendChild(nnSubmit);
        
        var nnClear=document.createElement("input");
        nnClear.type="reset";
        nnClear.value="❌";
        nnForm.appendChild(nnClear);
        
        nnForm.onsubmit=function(e) {
            e.preventDefault();
            console.log("Registrar.....");
            _this.jxUsuarioRecuperar(
                nnCorreo.value,
                nnStatusBox
            );
        }
        this.FormRecuperar_Construido=true;
    }

    MostrarLogin() {
        // console.info("NeoKiriLogin_JS.MostrarLogin()")
        if(!this.FormIniciar_Construido) {
            this.FormIniciar_Config();
        }
        this.nodeContenido.appendChild(this.nodeLoginBox);
        this.nodeHideBox.appendChild(this.nodeRegistrarBox);
        this.nodeHideBox.appendChild(this.nodeRecuperarBox);
    }

    MostrarRegistrar() {
        // console.info("NeoKiriLogin_JS.MostrarRegistrar()")
        if(!this.FormRegistrar_Construido) {
            this.FormRegistrar_Config();
        }
        this.nodeHideBox.appendChild(this.nodeLoginBox);
        this.nodeContenido.appendChild(this.nodeRegistrarBox);
        this.nodeHideBox.appendChild(this.nodeRecuperarBox);
    }
    MostrarRecuperar() {
        if(!this.FormRecuperar_Construido) {
            this.FormRecuperar_Config();
        }
        this.nodeHideBox.appendChild(this.nodeLoginBox);
        this.nodeHideBox.appendChild(this.nodeRegistrarBox);
        this.nodeContenido.appendChild(this.nodeRecuperarBox);
    }

    HeaderLogin_Config() {
        this.nodeHeader.innerHTML="";
        var nnLogo=document.createElement("img");
        nnLogo.encoding="async";
        nnLogo.loading="lazy";
        nnLogo.src=this.dirRaiz+this.objOptions.logoH;
        this.nodeHeader.appendChild(nnLogo);
    }

    MenuLogin_Config() {
        this.nodeFooter.innerHTML="";
        var _this=this;        
        
        var nnIniciarButton=document.createElement("button");
        nnIniciarButton.innerHTML="Iniciar Sesion";
        nnIniciarButton.classList.add("Activo");
        this.nodeMenu.appendChild(nnIniciarButton);
        
        var nnRegistrarButton=document.createElement("button");
        nnRegistrarButton.innerHTML="Registrar";
        this.nodeMenu.appendChild(nnRegistrarButton);

        var nnRecuperarButton=document.createElement("button");
        nnRecuperarButton.innerHTML="Recuperar Contraseña";
        this.nodeMenu.appendChild(nnRecuperarButton);

        let QuitarActivo=function() {
            nnIniciarButton.classList.remove("Activo");
            nnRegistrarButton.classList.remove("Activo");
            nnRecuperarButton.classList.remove("Activo");
        }

        nnIniciarButton.onclick=function() {
            QuitarActivo();
            _this.MostrarLogin();
            nnIniciarButton.classList.add("Activo");
        }

        nnRegistrarButton.onclick=function() {
            QuitarActivo();
            _this.MostrarRegistrar();
            nnRegistrarButton.classList.add("Activo");
        }

        nnRecuperarButton.onclick=function() {
            QuitarActivo();
            _this.MostrarRecuperar();
            nnRecuperarButton.classList.add("Activo");
        }
        this.MostrarLogin();
    }
    
    jxUsuarioRegistrar(Nombres, Apellidos, Correo, StatusBox) {
		"use strict";
        StatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        StatusBox.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "UsuarioRegistrar");
		fd.append("Nombres", Nombres);
		fd.append("Apellidos", Apellidos);
		fd.append("Correo", Correo);
		// fd.append("Pass", Pass);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
            fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("NeoKiriWeb::jxUsuarioRegistrar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                StatusBox.innerHTML="";
                this.MostrarLogin();
                // this.jxInfo_Get();
			} else {
                StatusBox.innerHTML=data.RespuestaError;
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}

    jxUsuarioIniciar(Correo, Pass, StatusBox) {
		"use strict";
        StatusBox.innerHTML="";
        var _this=this;
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        StatusBox.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "UsuarioIniciar");
		fd.append("Correo", Correo);
		fd.append("Pass", Pass);
		// for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
        //     fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		// }
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("NeoKiriWeb::jxUsuarioIniciar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                StatusBox.innerHTML="";
                globalThis.localStorage.setItem("UsuarioKeyJX", data.KeyJX);
                StatusBox.innerHTML="Sesion iniciada correctamente... recargando pagina";
                setTimeout(function() {
                    globalThis.location=_this.dirRaiz;
                }, 3000);
			} else {
                StatusBox.innerHTML=data.RespuestaError;
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}

    jxUsuarioRecuperar(Correo, StatusBox) {
		"use strict";
        StatusBox.innerHTML="";
        var imgLoading=document.createElement("img");
        imgLoading.src=this.dirRaiz+this.objOptions.loadingImg;
        imgLoading.loading="lazy";
        imgLoading.encoding="async";
        StatusBox.appendChild(imgLoading);
        // -------
		var fd=new FormData();
		fd.append("NeoKiri_Web", "UsuarioRecuperar");
		fd.append("Correo", Correo);
		// for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
        //     fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		// }
        // -------
		fetch(this.urlAction, {method: "POST", body: fd})
		.then(resp=>resp.text())
		.then(data=>{
            console.info("NeoKiriWeb::jxUsuarioRecuperar()");
            try {
                data=JSON.parse(data);
                console.log(data);
            } catch (error) {
                console.log(data);
                return false;
            }
			if(data.RespuestaBool) {
                StatusBox.innerHTML="";
                globalThis.localStorage.removeItem("UsuarioKeyJX");
                globalThis.localStorage.removeItem("UsuarioKeyJX_Expira");
                StatusBox.innerHTML="Recuperado usuario";
			} else {
                StatusBox.innerHTML=data.RespuestaError;
            }
		})
		.catch(err=>{
			console.error(err);
		});
	}
}