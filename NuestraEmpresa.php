<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuestra Empresa | TechCo - Tecnología e Innovación</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/StyleMain.css" />
    <link rel="stylesheet" type="text/css" href="css/SliderBoxNK_JS.css" />
    <link rel="stylesheet" type="text/css" href="css/TopBar_NK.css" />
    <script type="text/javascript" src="js/InitWeb_js.js"></script>
    <script type="text/javascript" src="js/neoKiriFooterWeb.js"></script>
    <script type="text/javascript" src="js/LoginBoxNK_js.js"></script>
    <script type="text/javascript" src="js/SliderBoxNK_JS.js"></script>
    <script type="text/javascript" src="js/CarritoComprasNK.js"></script>
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
            border-radius: 0.5em;
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
            <h1>TechCo - Tecnología e Innovación</h1>
            <h2>¿Quiénes somos?</h2>
            <p>Somos TechCo, una empresa que nace de la pasión por la tecnología.</p>
            
            <h2>¿Qué hacemos?</h2>
            <ul>
                <li>Ofrecemos productos de hardware y software para mejorar, actualizar o reparar tu equipo de cómputo, ya sea de escritorio o portátil, con accesorios de calidad para que le des una nueva vida a tu equipo informatico.</li>
                <li>Analisamos y Desarrollamos aplicaciones, tanto para web, móviles y de escritorio, utilizando lenguajes de programación y/o frameworks versátiles, que nos permitan crear productos funcionales y atractivos.</li>
            </ul>

            <h2>¿Por qué elegirnos?</h2>
            <ul>
                <li>Porque ofrecemos calidad y eficiencia en todos nuestros servicios, garantizando la satisfacción de nuestros clientes.</li>
                <li>Porque nos adaptamos a las exigencias y preferencias de cada cliente, brindando asesoría personalizada y acompañamiento durante todo el proceso.</li>
                
                <li>Porque estamos siempre al día con las últimas tendencias y novedades en tecnología y diseño, buscando innovar y mejorar constantemente.</li>
                
            </ul>

            <h2>Nuestros valores</h2>
            <ul>
                <li><b>Compromiso:</b> Nos comprometemos con cada proyecto que realizamos, poniendo todo nuestro esfuerzo y dedicación para lograr los mejores resultados posibles.</li>
                <li><b>Creatividad:</b> Buscamos soluciones originales y novedosas para cada desafío que se nos presenta, aplicando nuestra imaginación y talento al servicio de nuestros clientes.</li>
                <li><b>Calidad:</b> Nos esforzamos por ofrecer servicios de alta calidad, cuidando cada detalle y cumpliendo con los estándares más exigentes del mercado.</li>
                <li><b>Confianza:</b> Generamos confianza en nuestros clientes, brindando transparencia, honestidad y profesionalismo en cada etapa del proceso.</li>
                <li><b>Colaboración:</b> Trabajamos en equipo con nuestros clientes, escuchando sus necesidades, opiniones y sugerencias, y estableciendo una comunicación fluida y constante.</li>
            </ul>

            <h2>Contacto</h2>
            <p>Si quieres saber más sobre nosotros o solicitar un presupuesto, puedes visitar nuestra página web o nuestras redes sociales, donde encontrarás más información sobre nuestros servicios y trabajos realizados.
            También puedes llamarnos o enviarnos un mensaje por WhatsApp o correo electrónico, donde te atenderemos con gusto y resolveremos tus dudas.</p>
            <p>Estaremos encantados de trabajar contigo y ayudarte a cumplir tus objetivos.</p>
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