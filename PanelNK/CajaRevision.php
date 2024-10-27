<?php

$CotizacionID_In=$_GET["CotizacionID"];

?>
<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revizar Cotizacion | Caja | PanelNK</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/StylePanel.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../css/CajaAdminNK.css" />
    <script type="text/javascript" src="../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="../js/CajaRevision_AdminNK.js"></script>
    <script type="text/javascript" src="../js/FormNK.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
        <div id="HeaderSlider"></div>
        <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
        
    </header>

    <main>
        <div class="mainContainer">
            <div id="CajaAdminBox"></div>
            <script>
                
            </script>
            <div>
                <aside id="AsideMain"></aside>
            </div>
        </div>
    </main>

    <footer>
        <div class="mainContainer">
            <div id="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new NeoKiriWeb_Footer("../API.php", "../", {});

        var HeaderSlider=document.querySelector("#HeaderSlider")
        new SliderBoxNK(HeaderSlider, "../API.php", "../", {
            logoSrc: "../logo.png",
            valuesSend: [["Servicios","ServiciosGet"]]
        })

        let nnCajaBox=document.querySelector("#CajaAdminBox");
        new CajaRevision_AdminNK(nnCajaBox, "ActionPanelCaja.php", "<?php echo $CotizacionID_In ?>", "../", {});
    </script>
</body>
</html>