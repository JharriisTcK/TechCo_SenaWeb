<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politica de Devolución</title>
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
            <h1>Política de Devolución</h1>
            <h2>Derecho al retracto</h2>
            <p>Los consumidores tienen derecho al retracto, que es la facultad de devolver un producto comprado sin dar explicaciones y recibir el reembolso completo del dinero. Este derecho se aplica a las compras realizadas a distancia, como en línea o por teléfono, y tiene un plazo de 10 días hábiles desde la recepción del producto.</p>
            <h2>Condiciones para la devolución</h2>
            <p>Para que la devolución sea aceptada, el producto debe cumplir con los siguientes requisitos:</p>
            <ul>
                <li>No debe haber sido usado.</li>
                <li>Debe estar en su empaque original.</li>
                <li>Debe contar con todas sus piezas y accesorios.</li>
            </ul>
            <h2>Proceso de devolución</h2>
            <p>Para devolver un producto, el consumidor debe ponerse en contacto con el servicio de atención al cliente a través de nuestros canales de comunicación. Una vez que hayamos recibido la solicitud de devolución, procederemos con la verificación de la solicitud y te indicaremos el proceso para que pueda devolver el producto.</p>
            <h2>Reembolso</h2>
            <p>Una vez que recibamos el producto devuelto, le reembolsaremos el monto total de la compra en un plazo de 30 días hábiles.</p>
            <h2>Excepciones</h2>
            <p>La política de devoluciones no aplica a los siguientes productos:</p>
            <ul>
                <li>Productos personalizados o hechos a medida.</li>
                <li>Productos que hayan sido usados o deteriorados.</li>
                <li>Productos que no se encuentren en su empaque original.</li>
            </ul>
            <h2>Garantía legal</h2>
            <p>Todos los productos vendidos por TechCo cuentan con garantía legal establecida por la ley, según el producto que corresponda. Durante este tiempo, los consumidores están protegidos contra defectos de fabricación o materiales.</p>
            <h2>Autorización de apertura</h2>
            <p>Los consumidores pueden autorizan a TechCo a abrir  y revisar los productos los productos antes de proceder con el envió, porque contamos con las herramientas especializadas necesaria para comprobar el estado de los productos y comprobar defectos de fabricación. Esto nos permite garantizar que los productos no son defectuosos antes de proceder al envío, reembolso o cambio.</p>
            <h2>Instrucción de apertura de productos</h2>
            <p>Para evitar daños en el producto, siga las instrucciones de apertura del producto. Si el producto se abre incorrectamente, la garantía legal puede quedar anulada.</p>

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