<?php

require("../Privado/DB_DATA.php");
require("../PHP/neoKiriPHP_class.php");
require("../PHP/TechCoWeb_class.php");
require("../PHP/UsuariosNK.php");

$dirRaiz="../";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError=false;

session_start();
if(!isset($_SESSION["AdminKeyJX"])) {
	$ResponseObj->RespuestaError="Sesion no iniciada";
    echo json_encode($ResponseObj);
	exit();
}

$S_AdminKeyJX=$_SESSION["AdminKeyJX"];


if (isset($_POST["UsuariosNK_Admin"])) {
    switch ($_POST["UsuariosNK_Admin"]) {
        case 'UsuarioNew':
            $UsuarioAdminBoard_New_Nombres=$_POST["UsuarioNombres"];
            $UsuarioAdminBoard_New_Apellidos=$_POST["UsuarioApellidos"];
            $UsuarioAdminBoard_New_Correo=$_POST["UsuarioCorreo"];
            $stat=UsuarioNK::Usuario_New($UsuarioAdminBoard_New_Nombres, $UsuarioAdminBoard_New_Apellidos, $UsuarioAdminBoard_New_Correo, $dirRaiz);
            if(!$stat[0]) {
                $ResponseObj->RespuestaError=$stat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $stat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioNew", $UsuarioAdminBoard_New_Correo);
            if($stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Token=$stat[1];
            }
            echo json_encode($ResponseObj);
            exit();
		break;

        case 'UsuariosGet':
            $UsuariosNK_Get=UsuariosNK_Getters::Get_All($dirRaiz);
            if($UsuariosNK_Get[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Usuarios=$UsuariosNK_Get[1];
            } else {
                $ResponseObj->RespuestaError=$stat[1];
            }
		break;

        case 'UsuarioDelete':
            $UsuarioNKADmin_UsuarioDel_UsuarioID=$_POST["UsuarioID"];
            $UsuarioNKADmin_UsuarioDel_UsuarioObj=new UsuarioNK($UsuarioNKADmin_UsuarioDel_UsuarioID, $dirRaiz);
            $ResponseObj->UsuarioObj=$UsuarioNKADmin_UsuarioDel_UsuarioObj;
            $UsuarioNKADmin_UsuarioDel_Stat=$UsuarioNKADmin_UsuarioDel_UsuarioObj->Usuario_Del();
            if($UsuarioNKADmin_UsuarioDel_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UsuarioNKADmin_UsuarioDel_Stat[1];
            }
        break;

        case 'TokenPassGet':
            $UsuarioNKAdmin_TokenPassGet_KeyJX=$_POST["KeyJX"];
            $UsuarioNKAdmin_TokenPassGet_UsuarioCorreo=$_POST["UsuarioCorreo"];
            $UsuarioNKAdmin_TokenPassGet_TokenStat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioPassRecover", $UsuarioNKAdmin_TokenPassGet_UsuarioCorreo);
            if($UsuarioNKAdmin_TokenPassGet_TokenStat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Token=$UsuarioNKAdmin_TokenPassGet_TokenStat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNKAdmin_TokenPassGet_TokenStat[1];
            }
            echo json_encode($ResponseObj);
            exit();
        break;

        case 'LoginAsAdmin':
            $UsuarioNK_LoginAs_UsuarioID=$_POST['UsuarioID'];
            $UsuarioNK_LoginAs_KeyJX=$_POST['KeyJX'];
            UsuarioNK_Login::Logout();
            $UsuarioNK_LoginAs_Stat=UsuarioNK_Login::CrearHash($UsuarioNK_LoginAs_UsuarioID);
            if($UsuarioNK_LoginAs_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Token=$UsuarioNK_LoginAs_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_LoginAs_Stat[1];
            }
            echo json_encode($ResponseObj);
            exit();
        break;

    }
}

if (isset($_POST["UsuariosNK_Admin"])) {
    switch ($_POST["UsuariosNK_Admin"]) {
        case 'TokensAdminGet':
            $UsuarioTokenAdmin_Get_Stat=UsuarioNK_TokenAdmin::Get_All($dirRaiz);
            if($UsuarioTokenAdmin_Get_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Tokens=$UsuarioTokenAdmin_Get_Stat[1];
            }
        break;
    }
}



$ResponseObj->DIR="Action Usuarios Admin";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;

header('Content-Type: application/json; charset=utf-8');

echo json_encode($ResponseObj);
exit();



?>