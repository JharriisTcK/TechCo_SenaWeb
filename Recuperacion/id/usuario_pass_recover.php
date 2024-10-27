<?php 

require("../../Privado/DB_DATA.php");
require("../../PHP/TechCoWeb_class.php");
require("../../PHP/UsuariosNK.php");
$dirRaiz="../../";
$TokenAdmin=$_GET["Token"];
$stat=UsuarioNK_TokenAdmin::Get_ID($TokenAdmin, $dirRaiz);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperación Clave de Usuario | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../../css/neoKiri_recover.css" />
    <script type="text/javascript" src="../../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../../js/NeoKiriPassRecover.js"></script>
</head>
<body>
    <header>
    </header>

    <main>
        <div id="UsuarioRecover" class="neoKiri_Recover"></div>
        <script type="text/javascript">
            var node=document.querySelector("#UsuarioRecover");
            
            var nnPerfilImagen=document.createElement("img");
            nnPerfilImagen.src="../../img/UsuarioPerfilNone.png";
            node.appendChild(nnPerfilImagen);

            var nnTitulo=document.createElement("h1");
            nnTitulo.innerHTML="Recuperacion de Contraseña";
            node.appendChild(nnTitulo);
            
            var nnSubtitulo=document.createElement("h2");
            nnSubtitulo.innerHTML="Usuario NeoKiri";
            node.appendChild(nnSubtitulo);

            var nnDescripcion=document.createElement("p");
            nnDescripcion.innerHTML="Bienvenido <b><?php echo $stat[1]->Nombres; ?></b>, gracias por utilizar nuestros servicios y validar tu cuenta, por favor ingresa la nueva contraseña que vas a utilizar en este proceso.";
            node.appendChild(nnDescripcion);
            
            var nnForm=document.createElement("form");
            node.appendChild(nnForm);

            var nnPass1Label=document.createElement("label");
            nnForm.appendChild(nnPass1Label);
            var nnPass1B=document.createElement("b");
            nnPass1B.innerHTML="Ingresa nueva contraseña: ";
            nnPass1Label.appendChild(nnPass1B);
            var nnPass1=document.createElement("input");
            nnPass1.type="password";
            nnPass1.required="required";
            nnPass1Label.appendChild(nnPass1);
            
            var nnPass2Label=document.createElement("label");
            nnForm.appendChild(nnPass2Label);
            var nnPass2B=document.createElement("b");
            nnPass2B.innerHTML="Repite la nueva contraseña: ";
            nnPass2Label.appendChild(nnPass2B);
            var nnPass2=document.createElement("input");
            nnPass2.type="password";
            nnPass2.required="required";
            nnPass2Label.appendChild(nnPass2);

            var nnStatus=document.createElement("div");
            nnForm.appendChild(nnStatus);

            var nnSubmit=document.createElement("input");
            nnSubmit.type="submit";
            nnSubmit.disabled=true;
            nnSubmit.value="Cambiar Contraseña";
            nnForm.appendChild(nnSubmit);
            
            nnPass2.addEventListener("keyup", checkpass, false);
            
            function checkpass() {
                nnStatus.innerHTML="";
                nnStatus.removeAttribute("class");
                
                nnSubmit.disabled=true;
                
                var val1=nnPass1.value;
                var val2=nnPass2.value;
                
                // console.log(val1);
                // console.log(val2);
                
                if(val1!=val2) {
                    nnStatus.innerHTML="Las contraseñas no coinciden";
                    return false;
                }
                
                nnSubmit.disabled=false;
            }
            
            nnForm.onsubmit=function(e) {
                e.preventDefault();
                console.log("Form Detenido");
                nnSubmit.disabled=true;
                
                var val1=nnPass1.value;
                var val2=nnPass2.value;
                
                if(val1!=val2) {
                    nnStatus.innerHTML="Las contraseñas no coinciden";
                    return false;
                }
                var fd=new FormData();
                fd.append("TokenAdmin", "PassSet");
                fd.append("Pass1", val1);
                fd.append("Pass2", val2);
                fd.append("TokenID", "<?php echo $TokenAdmin ?>");

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
                        nnStatus.innerHTML="La contraseña se ha guardado correctamente, ya puedes iniciar sesion en nuestros servicios. :D";
                        setTimeout(function() {
                            globalThis.location="../../";
                        }, 2000);
                    } else {
                    }
                })
                .catch(err=>{
                    console.error(err);
                });

            }

        </script>
    </main>

    <footer>
        <div class="mainContainer">
            <div id="InfoFooter" class="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new NeoKiriWeb_Footer("../../API.php", "../../", {});
        
    </script>
</body>
</html>