<?php
session_start();

$CompraID=$_GET["CompraID"];
?>
<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Compra</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/TopBar_NK.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../css/CompraRevisionNK.css" />
    <script type="text/javascript" src="../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/NeoKiri_Functions_js.js"></script>
    <script type="text/javascript" src="../js/CompraRevisionNK.js"></script>
    <script type="text/javascript" src="../js/LoginBoxNK_js.js"></script>
    <script type="text/javascript" src="../js/CarritoComprasNK.js"></script>
    <script type="text/javascript" src="../js/SliderBoxNK_JS.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
    </header>    
    <main>
        <div id="UsuarioNK_Header"></div>
        <div class="mainContainer">
            <div id="CompraInfoBox"></div>
            <aside id="AsideMain"></aside>
        </div>
    </main>

    <div id="HeaderSlider">s</div>
    <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
    <script type="text/javascript">
        var HeaderSlider=document.querySelector("#HeaderSlider");
        new SliderBoxNK(HeaderSlider, "../API.php", "../", {
            logoSrc: "../logo.png",
            valuesSend: [["Servicios","ServiciosGet"]]
        })

        var CompraInfoBox=document.querySelector("#CompraInfoBox");
        new CompraRevisionNK(CompraInfoBox, "../ActionUsuario.php", "<?php echo $CompraID ?>", "../", {});
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