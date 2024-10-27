<?php 

session_start();
if(!isset($_SESSION["AdminKeyJX"])) {
    header("location: Login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ES-CO">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PanelNK | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/StylePanel.css" />
    <link rel="stylesheet" type="text/css" href="PanelNK_Style.css" />
    <link rel="stylesheet" type="text/css" href="../css/SliderBoxNK_JS.css" />
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/SliderBoxNK_JS.js"></script>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
        <div id="HeaderSlider"></div>
        <!-- <img src="logo.png" alt="NeoKiri Logo" title="TechCo - Tecnología e Innovación" class="LogoMain" /> -->
        <script>
            var HeaderSlider=document.querySelector("#HeaderSlider")
            new SliderBoxNK(HeaderSlider, "../API.php", "../", {
                logoSrc: "../logo.png",
                valuesSend: [["Servicios","ServiciosGet"]]
            })
        </script>
    </header>

    <main>
        <div class="mainContainer">
            <ul id="MenuPanelNK">
                <li><a href="Productos/">Productos</a></li>
                <li><a href="Usuarios/">Usuarios</a></li>
                <li><a href="Caja.php">Caja</a></li>
                <li><a href="Tokens.php">Tokens</a></li>
            </ul>
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
    </script>
</body>
</html>