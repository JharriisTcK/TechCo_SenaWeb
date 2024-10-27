<?php

require("Privado/DB_DATA.php");
require("PHP/neoKiriPHP_class.php");

// DBS
// NeoTechSena_MainDB
require("PHP/TechCoWeb_class.php");
require("PHP/TechCoAdmin_Login.php");
require("PHP/ProductosNK_class.php");
require("PHP/UsuariosNK.php");
require("PHP/CajaNK_class.php");


$dirRaiz="";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError="";

if(isset($_POST["UsuarioNK"])) {
    switch ($_POST["UsuarioNK"]) {
        case 'UsuarioRegistrar':
            $UsuarioNKAdmin_Nombres=$_POST['Nombres'];
            $UsuarioNKAdmin_Apellidos=$_POST['Apellidos'];
            $UsuarioNKAdmin_Correo=$_POST['Correo'];
            $UsuarioNKAdmin_Stat_UsuarioID=UsuarioNK::Usuario_New($UsuarioNKAdmin_Nombres, $UsuarioNKAdmin_Apellidos, $UsuarioNKAdmin_Correo, $dirRaiz);
            if(!$UsuarioNKAdmin_Stat_UsuarioID[0]) {
                $ResponseObj->RespuestaError=$UsuarioNKAdmin_Stat_UsuarioID[1];
                echo json_encode($ResponseObj);
                exit();
            }
            // -----------
            $UsuarioNKAdmin_Obj=new UsuarioNK($UsuarioNKAdmin_Stat_UsuarioID[1], $dirRaiz);
            $UsuarioNKAdmin_Obj->EsAdministrador_Change();
            // -----------
            $Stat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioNew", $UsuarioNKAdmin_Correo, $dirRaiz);
            if($Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $UsuarioAdmin_Dir=$dirRaiz."UsuarioNuevo/".$Stat[1]."/";
                $ResponseObj->GoTo=$UsuarioAdmin_Dir;
                header("location: $UsuarioAdmin_Dir");
            } else {
                $ResponseObj->RespuestaError=$Stat[1];
            }
            echo json_encode($ResponseObj);
            exit();
            // print_r($_POST);
            break;
        
        default:
            echo "SIN COMANDO NK";
            break;
    }
    exit();
}

echo "<pre>";
$stat=NeoKiriWeb_Fix::FoldersMediaCrear($dirRaiz);
print_r($stat);
// ------------
$stat=ProductosNK_Fix::TablasAll_Crear();
print_r($stat);
// ------------
$stat=UsuariosNK_Fix::TablasAll_Crear();
print_r($stat);
// $stat=NeoKiriWeb_Fix::TablaAllCrear($dirRaiz);
// print_r($stat);
$stat=neoKiriAdmin_Login_Fix::TablaAllCrear($dirRaiz);
print_r($stat);
// ------------
// $stat=ColaboradoresNK_Fix::TablasAll_Crear();
// print_r($stat);
$stat=CajaNK_Fix::TablasAll_Crear($dirRaiz);
print_r($stat);

$Usuarios=UsuariosNK_Getters::Get_All($dirRaiz, false);
if(!$Usuarios[0]) {
    echo "Error als solicitar usuarios";
    print_r($Usuarios);
    exit();
}
$CrearFormularioRegistro=false;
if(count($Usuarios[1])<1) {
    echo "Sin usuarios Crear Formulario de Registro";
    $CrearFormularioRegistro=true;
}
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Web</title>
</head>
<body>
    <?php 
    if($CrearFormularioRegistro) {
    ?>
    <div>
        <form method="POST" action="__initweb__.php">
        <div>
            <label><b>Nombres: </b><input type="text" name="Nombres" required></label>
        </div>
        <div>
            <label><b>Apellidos: </b><input type="text" name="Apellidos" required></label>
        </div>
        <div>
            <label><b>Correo: </b><input type="email" name="Correo" required></label>
        </div>
        <div>
            <input type="hidden" name="UsuarioNK" value="UsuarioRegistrar">
            <input type="submit" value="Registrar">
        </div>
        </form>
    </div>
    <?php } ?>
</body>
</html>