<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politica de Cookies | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="css/TopBar_NK.css" />
    <script type="text/javascript" src="js/InitWeb_js.js"></script>
    <script type="text/javascript" src="js/LoginBoxNK_js.js"></script>
    <script type="text/javascript" src="js/CarritoComprasNK.js"></script>
    <script type="text/javascript" src="js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="js/SliderBoxNK_JS.js"></script>
    <style type="text/css">
        .InfoEmpresaHeader  {
            position: relative;
            height: 15em;
        }
        .InfoEmpresaHeader > div {
            position: absolute;
            bottom: 0px;
            width: 100%;
        }

        .InfoEmpresaBox h1 {
            text-align: center;
            color: var(--blanco);
            background-color: var(--color-principal);
            margin: 0;
            padding: 0.5em;
            border-radius: 0.5em;
            position: sticky;
            top: 3.4em;
            font-size: 1em;
        }
        
        .InfoEmpresaLogo {
            display: block;
            width: 35em;
            margin: 0 auto;
        }
        .InfoEmpresaBox {
            background-color: var(--gris-bg);
            padding: 0.5em;
            width: 80%;
            margin: 1em auto;
            border-radius: 1em;
        }
        .InfoEmpresaBox h2 {
            text-align: center;
            text-decoration: underline;
        }
    </style>
</head>
<body class="cssnk-gradientanimation">
    <nav id="HeaderNav"></nav>
    <header class="InfoEmpresaHeader">
        <div>
            <img class="InfoEmpresaLogo" src="logo.png" alt="TechCo - Tecnología e Innovación" title="TechCo - Tecnología e Innovación"/>
        </div>
    </header>
    
    <main>
        <div class="InfoEmpresaBox">
            <h1>Política de cookies</h1>
            <h2>¿Qué son las cookies?</h2>
            <p>Las cookies son pequeños archivos de texto que se almacenan en tu ordenador o dispositivo móvil cuando visitas un sitio web. Las cookies permiten que el sitio web recuerde tus acciones y preferencias (como tu idioma, tamaño de letra y otras opciones de visualización) durante un período de tiempo, lo que te permite navegar por el sitio web de forma más eficiente y que sea más personal.</p>
            <h2>¿Qué tipos de cookies utilizamos?</h2>
            <p>Utilizamos los siguientes tipos de cookies en nuestro sitio web:</p>
            <ul>
                <li>Cookies necesarias: Estas cookies son esenciales para que nuestro sitio web funcione correctamente. Permiten que los usuarios naveguen por el sitio web y utilicen sus funciones básicas.</li>
                <ul>
                    <li>Ejemplo: Las cookies necesarias permiten que los usuarios se registren en nuestro sitio web y accedan a su cuenta.</li>
                </ul>
                <li>Cookies de rendimiento: Estas cookies recopilan información sobre cómo los usuarios utilizan nuestro sitio web, como las páginas que visitan más a menudo y los errores que encuentran. Esta información se utiliza para mejorar el rendimiento y la funcionalidad de nuestro sitio web.</li>
                <ul>
                    <li>Ejemplo: Las cookies de rendimiento nos permiten saber qué páginas son más populares y cómo podemos mejorar nuestro sitio web.</li>
                </ul>
                <li>Cookies de funcionalidad: Estas cookies recuerdan las elecciones que haces, como tu idioma o tu ubicación. Esto nos permite proporcionarte una experiencia más personalizada.</li>
                <ul>
                    <li>Ejemplo: Las cookies de funcionalidad nos permiten recordar tu idioma preferido para que no tengas que seleccionarlo cada vez que visites nuestro sitio web.</li>
                </ul>
                <li>Cookies de publicidad: Estas cookies se utilizan para mostrarte anuncios más relevantes y atractivos.</li>
                <ul>
                    <li>Ejemplo: Las cookies de publicidad nos permiten mostrarte anuncios que están relacionados con tus intereses.</li>
                </ul>
            </ul>
            <h2>Cookies de terceros</h2>
            <p>También utilizamos cookies de terceros en nuestro sitio web. Estas cookies se colocan por empresas externas que nos prestan servicios, como el análisis de tráfico web o la publicidad.</p>
            <h2>Cómo controlar las cookies</h2>
            <p>Puedes controlar y administrar las cookies a través de la configuración de tu navegador web. La mayoría de los navegadores te permiten rechazar las cookies, borrar las cookies existentes y recibir una notificación cuando se coloca una nueva cookie.</p>
            <h2>Consentimiento</h2>
            <p>Al utilizar nuestro sitio web, aceptas el uso de cookies de acuerdo con esta política.</p>
            <h2>Autoridades de protección de datos personales</h2>
            <p>La Ley 1581 de 2012, que regula el tratamiento de datos personales en Colombia, establece que el consentimiento debe ser expreso, libre e informado. En el caso de las cookies, el consentimiento puede ser dado a través de una casilla de verificación o un botón de aceptación.</p>
            <p>La Agencia Española de Protección de Datos es la autoridad competente para vigilar el cumplimiento de la Ley Orgánica 3/2018, de 5 de diciembre, de Protección de Datos Personales y garantía de los derechos digitales. El sitio web de esta entidad contiene información sobre los derechos de los titulares de datos personales, así como sobre las obligaciones de los responsables del tratamiento de datos personales.</p>
            <p>Para obtener más información sobre cómo administrar las cookies, visita los siguientes sitios web:</p>
            <ul>
                <li>Colombia: Superintendencia de Industria y Comercio de Colombia</li>
                <li>España: Agencia Española de Protección de Datos</li>
            </ul>

        </div>

        <div id="HeaderSlider"></div>
        <script type="text/javascript"">
            var HeaderSlider=document.querySelector("#HeaderSlider")
            new SliderBoxNK(HeaderSlider, "API.php", "", {
                logoSrc: "logo.png",
                valuesSend: [["Servicios","ServiciosGet"]]
            })
        </script>
    </main>

    <footer>
        <div class="mainContainer">
            <div id="InfoFooter"></div>
        </div>
    </footer>
    <script type="text/javascript">
        new MenuTop_Footer("API.php", "", {});
        new NeoKiriWeb_Footer("API.php", "", {});
    </script>
</body>
</html>