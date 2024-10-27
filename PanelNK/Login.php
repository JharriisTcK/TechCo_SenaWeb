<!DOCTYPE html>
<html lang="es-CO">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>PanelNK | TechCo - Tecnología e Innovación</title>
	<meta name="robots" content="noindex">
	<meta name="robots" content="nofollow">

	<link rel="icon" type="image/ico" href="../favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../css/StyleMain.css" />
	<link rel="stylesheet" type="text/css" href="../css/LoginBoxNK.css" />
	<script type="text/javascript" src="../js/LoginBoxNK_js.js"></script>

	<script type="text/javascript">
		globalThis.addEventListener("DOMContentLoaded",onLoadDOM,false);
	</script>
	</head>
	
	<body>
		<div id="login-box"></div>
		<script type="text/javascript">
			var nodeLogin=document.querySelector("#login-box");
			var objOptionsIn={
				Logo: "../logo.png",
				VarNomLocalKey: "PanelNKAdmin_KeyJX"
			}
			new LoginBoxNK(nodeLogin, "actionAdmin.php", "../", objOptionsIn);
		</script>
</body>
</html>