<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="icon" type="image/x-icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="../css/TopBar_NK.css" />
    <script type="text/javascript" src="../js/InitWeb_js.js"></script>
    <script type="text/javascript" src="../js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="../js/CarritoComprasNK.js"></script>
    <script type="text/javascript" src="../js/LoginBoxNK_js.js"></script>
    <style type="text/css">
        main {
            width: 70%;
            max-width: 800px;
            background-color: #fff6;
            margin: 1em auto;
            margin-top: 4.5em;
            padding: 1em;
            text-align: center;
            border-radius: 1em;
        }
        .PefrilFoto {
            height: 10em;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <nav id="HeaderNav"></nav>
    <header>
    </header>

    <main>
        <div id="AcercadeUsuario">
            <img src="../img/perfilnofoto.jpg" class="PefrilFoto">
            <h1>Bienvenido</h1>
            <p>Estamos encantados que te intereses por hacer parte de nuestro equipo</p>
            <p>Desde aqui podemos ofrecerte una mejor experiencia en nuestro sitio, Puedes crear un carrito de compras, listar tus favoritos, agregar direcciones para tus envios ...</p>
            <p>Si lo deseas puedes calificar nuestros productos, colaboradores y trabajos realizados. Tus calificaciones nos ayudan a mejorar nuestra oferta y a ofrecerte una mejor experiencia.</p>
            <p>Es importante tener en cuenta que los colaboradores que aparecen en nuestra página web no son empleados de NeoKiri. Somos una plataforma que brinda a los colaboradores el espacio para mostrar sus habilidades y encontrar trabajo.</p>
            <p>Esto ayudará a que nuestros colaboradores crezcan y mejoren sus habilidades.</p>
            <p>Esperamos que disfrutes de tu experiencia y compra en nuestro sitio, estamos siempre dispuestos a colaborarte. ¡Saludos!</p>
        </div>

        <div id="UsuarioLogin"></div>
    </main>

    <footer>
        <div class="mainContainer">
            <div id="InfoFooter" class="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new MenuTop_Footer("../API.php", "../", {});
        new NeoKiriWeb_Footer("../API.php", "../", {});
        
        let UsuarioLoginBox=document.querySelector("#UsuarioLogin");
        let nkLogin_Acerca=new LoginBoxNK("../ActionUsuario.php", "../", {});
        UsuarioLoginBox.appendChild(nkLogin_Acerca.nodeObj);
    </script>
</body>
</html>