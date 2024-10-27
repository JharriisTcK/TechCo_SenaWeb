<?php

$NickDirIn=$_GET["nickdir"];

?>
<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/TopBar_NK.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderImagenesNK1.css" />
    <link rel="stylesheet" type="text/css" href="../css/ProductosNK.css" />
    <script type="text/javascript" src="../js/NeoKiri_Functions_js.js"></script>
    <script type="text/javascript" src="../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../js/LoginBoxNK_js.js"></script>
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/SliderImagenesNK1.js"></script>
    <script type="text/javascript" src="../js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="../js/ProductoNK.js"></script>
    <script type="text/javascript" src="../js/CarritoComprasNK.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>

    <header></header>

    <main>
        <div id="ProductoHeaderBox"></div>
        <div class="mainContainer">
            <div id="ProductoBox"></div>
            <script>
                var ProductoBox=document.querySelector("#ProductoBox");
                var ProductoHeaderBox=document.querySelector("#ProductoHeaderBox");
                let ProductoObj=new ProductoNK(ProductoBox, "../API.php", "<?php echo $NickDirIn ?>", "../", {})
                ProductoHeaderBox.appendChild(ProductoObj.nodeHeader);

            </script>
            <aside id="AsideMain">
                <div id="UsuarioBox_MinBox"></div>
            </aside>
        </div>
    </main>

    <footer>
        <div class="mainContainer">
            <div id="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new MenuTop_Footer("../API.php", "../", {});
        new NeoKiriWeb_Footer("API.php", "../", {});
    </script>
</body>
</html>