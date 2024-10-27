<?php

require("../Privado/DB_DATA.php");
require("../PHP/neoKiriPHP_class.php");
require("../PHP/TechCoWeb_class.php");
require("../PHP/TechCoAdmin_Login.php");
require("../PHP/UsuariosNK.php");

$dirRaiz="../";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError=false;

session_start();

if (isset($_POST["AdminToken"])) {
    switch ($_POST["AdminToken"]) {
        case 'Solicitar':
            if(!isset($_SESSION["UsuarioNK"])){
                $ResponseObj->RespuestaError="";
                echo json_encode($ResponseObj);
                exit();
            }
            $AdminLogin_UsuarioID=$_SESSION["UsuarioNK"];
            $AdminLogin_Stat=NeoKiriAdmin_Login::Login($AdminLogin_UsuarioID, $dirRaiz);
            if($AdminLogin_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Token=$AdminLogin_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$AdminLogin_Stat;
            }
            echo json_encode($ResponseObj);
            exit();
        break;
    }
}

// ----------------------------

if(!isset($_SESSION["AdminKeyJX"])) {
	$ResponseObj->RespuestaError="Sesion no iniciada";
    echo json_encode($ResponseObj);
	exit();
}

if (isset($_POST["TokensBoard_AdminNK"])) {
    switch ($_POST["TokensBoard_AdminNK"]) {
        case 'TokensAdminGet':
            $TokenAdmin_Get_Stat=UsuarioNK_TokenAdmin::Get_All($dirRaiz);
            if($TokenAdmin_Get_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Tokens=$TokenAdmin_Get_Stat[1];
            }
        break;

        case 'TokenAdminEliminar':
            $TokenAdmin_Del_TokeinID=$_POST["TokenID"];
            $TokenAdmin_Del_Stat=UsuarioNK_TokenAdmin::TokenAdmin_Del($TokenAdmin_Del_TokeinID);
            if($TokenAdmin_Del_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$TokenAdmin_Del_Stat[0];
            }


            break;
    }
}




$ResponseObj->DIR="Action Admin";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;
$ResponseObj->SESSION=$_SESSION;

echo json_encode($ResponseObj);
exit();



?>