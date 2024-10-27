<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechCo - Tecnología e Innovación</title>
    <link type="text/plain" rel="author" href="humans.txt" />
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="css/TopBar_NK.css" />
    <link rel="stylesheet" type="text/css" href="css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="css/ProductosNK.css" />
    <script type="text/javascript" src="js/CarritoComprasNK.js"></script>
    <script type="text/javascript" src="js/InitWeb_js.js"></script>
    <script type="text/javascript" src="js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="js/ProductosNK_Board.js"></script>
    <script type="text/javascript" src="js/LoginBoxNK_js.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
        <div id="HeaderSlider"></div>
        <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
         
    </header>

    <main>
        <div class="mainContainer">
            <div id="ProductosBox"></div>
            
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
        new MenuTop_Footer("API.php", "", {});
        new NeoKiriWeb_Footer("API.php", "", {});

        var nodeProductos=document.querySelector("#ProductosBox");
        new ProductosNK_Board(nodeProductos, "", "API.php", {});

        var HeaderSlider=document.querySelector("#HeaderSlider");
        new SliderBoxNK(HeaderSlider, "API.php", "", {
            logoSrc: "logo.png",
            valuesSend: [["Servicios","ServiciosGet"]]
        });
    </script>
</body>
</html>