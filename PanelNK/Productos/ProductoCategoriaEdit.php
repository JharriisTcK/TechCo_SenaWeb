<?php

$ID_in=$_GET["ID"];

?>
<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria | PanelNK | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../../css/FormNK.css" />
    <link rel="stylesheet" type="text/css" href="../../css/FileImageNK.css" />
    <link rel="stylesheet" type="text/css" href="../../css/TextareaRichNK.css" />
    <link rel="stylesheet" type="text/css" href="../../css/ProductosNK.css" />
    <link rel="stylesheet" type="text/css" href="../../css/ProductosNK_Admin.css" />
    <script type="text/javascript" src="../../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../../js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="../../js/FormNK.js"></script>
    <script type="text/javascript" src="../../js/ProductosNK_AdminFormCategoria.js"></script>
    <script type="text/javascript" src="../../js/FileImageNK.js"></script>
    <script type="text/javascript" src="../../js/TextareaRichNK.js"></script>
    <script type="text/javascript" src="../../js/NeoKiri_Functions_js.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
        <div id="HeaderSlider"></div>
        <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
        <script>
            var HeaderSlider=document.querySelector("#HeaderSlider")
            new SliderBoxNK(HeaderSlider, "../../API.php", "../../", {
                logoSrc: "../../logo.png",
                valuesSend: [["Servicios", "ServiciosGet"]]
            })
        </script>
    </header>

    <main>
        <div class="mainContainer">
            <div>
            <div id="ProductosAdminBox"></div>
                <script type="text/javascript">
                    var nodeProductosCategoriasBox=document.querySelector("#ProductosAdminBox");
                    new ProductosNK_AdminFormCategoria(nodeProductosCategoriasBox, "../ActionPanelProductos.php", "<?php echo $ID_in; ?>", "../../", {});
                </script>
            </div>
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
        new MenuTop_Footer("../../API.php", "../../", {});
        new NeoKiriWeb_Footer("../../API.php", "../../", {});
    </script>
</body>
</html>