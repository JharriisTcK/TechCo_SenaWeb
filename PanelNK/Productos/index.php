<?php 

session_start();
if(!isset($_SESSION["AdminKeyJX"])) {
    header("location: ../Login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos | PanelNK | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../../css/FormNK.css" />
    <link rel="stylesheet" type="text/css" href="../../css/ProductosNK.css" />
    <script type="text/javascript" src="../../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../../js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="../../js/TextoListaNK.js"></script>
    <script type="text/javascript" src="../../js/ProductosNK_Admin.js"></script>
    <script type="text/javascript" src="../../js/FormNK.js"></script>
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
                <div>
                    <div id="ProductosAdminBox"></div>
                    <script type="text/javascript">
                        var nodeProductosBox=document.querySelector("#ProductosAdminBox");
                        new ProductosNK_Admin(nodeProductosBox, "../ActionPanelProductos.php", "", "../../", {});
                    </script>
                </div>

                <div>
                    <div id="ProductosCategoriasAdminBox"></div>
                    <script type="text/javascript">
                        var nodeProductosCategoriasBox=document.querySelector("#ProductosCategoriasAdminBox");
                        new ProductosCategoriasNK_Admin(nodeProductosCategoriasBox, "../ActionPanelProductos.php", "", "../../", {});
                    </script>
                </div>
                
                <div>
                    <div id="ProductosMarcasAdminBox"></div>
                    <script type="text/javascript">
                        var nodeProductosCargasBox=document.querySelector("#ProductosMarcasAdminBox");
                        new ProductosMarcasNK_Admin(nodeProductosCargasBox, "../ActionPanelProductos.php", "", "../../", {});
                    </script>
                </div>
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
        new NeoKiriWeb_Footer("../../API.php", "../../", {});
    </script>
</body>
</html>