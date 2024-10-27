<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuario Panel | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/TopBar_NK.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../css/FormNK.css" />
    <link rel="stylesheet" type="text/css" href="../css/UsuariosNK.css" />
    <link rel="stylesheet" type="text/css" href="../css/FileImageNK.css" />
    <script type="text/javascript" src="../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/NeoKiri_Functions_js.js"></script>
    <script type="text/javascript" src="../js/LoginBoxNK_js.js"></script>
    <script type="text/javascript" src="../js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="../js/UsuarioNK.js"></script>
    <script type="text/javascript" src="../js/FormNK.js"></script>
    <script type="text/javascript" src="../js/CarritoComprasNK.js"></script>
    <script type="text/javascript" src="../js/FileImageNK.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
    </header>    
    <main>
        <div id="UsuarioNK_Header"></div>
        <div class="mainContainer">
            <div id="UsuarioNK"></div>
            <script type="text/javascript">
                var nodeUsuario=document.querySelector("#UsuarioNK");
                var nodeUsuarioHeader=document.querySelector("#UsuarioNK_Header");
                let UsuarioObj=new UsuarioNK(nodeUsuario, "../ActionUsuario.php", "../", {
                    urlActionPublicaciones: "ActionPublicaciones.php"
                });
                nodeUsuarioHeader.appendChild(UsuarioObj.nodeHeader);
            </script>
            <aside id="AsideMain">
            <?php
            if(isset($_SESSION["AdminKeyJX"])) {
            ?>
            <div id="AdminBox">
                <button id="AdminBoxButton">Ir a Panel Administrador</button>
            </div>
            <script type="text/javascript">
            let AdminButton=document.querySelector("#AdminBoxButton");
            AdminButton.onclick=function() {
                globalThis.location="../PanelNK/";
            }
            </script>

            <?php
            } else if(isset($_SESSION["UsuarioAdministrador"]) && !empty($_SESSION["UsuarioAdministrador"])) {
            ?>
            <div id="AdminBox">
            <button id="AdminBoxButton">Solicitar Token Administrador</button>
            </div>
            <script type="text/javascript">
            let AdminButton=document.querySelector("#AdminBoxButton");
            AdminButton.onclick=function() {
                globalThis.localStorage.removeItem("AdminToken");
                let fd=new FormData();
                fd.append("AdminToken", "Solicitar");
                fetch("../PanelNK/ActionAdmin.php", {body: fd, method:"post"})
                .then(resp=>resp.json())
                .then(data=>{
                    console.log(data)
                    if(data.RespuestaBool) {
                        globalThis.localStorage.setItem("AdminToken", data.Token);
                        globalThis.location="../PanelNK/";
                    } else {
                        console.error(data.RespuestaError);
                    }
                })
                .catch(error=>{
                    console.error(error);
                });
            }
            </script>
            <?php
            }
            ?>
            </aside>
        </div>
    </main>

    <div id="HeaderSlider">s</div>
    <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
    <script type="text/javascript">
        var HeaderSlider=document.querySelector("#HeaderSlider")
        new SliderBoxNK(HeaderSlider, "../API.php", "../", {
            logoSrc: "../logo.png",
            valuesSend: [["Servicios","ServiciosGet"]]
        })
    </script>

    <footer>
        <div class="mainContainer">
            <div class="InfoFooter" id="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new MenuTop_Footer("../API.php", "../", {});
        new NeoKiriWeb_Footer("../API.php", "../", {});
    </script>
</body>
</html>