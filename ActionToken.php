<?php
require("Privado/DB_DATA.php");
require("PHP/neoKiriPHP_class.php");
require("PHP/TechCoWeb_class.php");
require("PHP/UsuariosNK.php");

$dirRaiz="";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError=false;

if (isset($_POST["TokenAdmin"])) {
    switch ($_POST["TokenAdmin"]) {
        case 'PassSet':
            UsuarioNK_Login::Logout();
            $TokenAdmin_PassSet_Pass1=trim($_POST["Pass1"]);
            $TokenAdmin_PassSet_Pass2=trim($_POST["Pass2"]);
            $TokenAdmin_PassSet_ID=trim($_POST["TokenID"]);
            $TokenAdmin_PassSet_TokenObj=UsuarioNK_TokenAdmin::Get_ID($TokenAdmin_PassSet_ID, $dirRaiz);
            if(!$TokenAdmin_PassSet_TokenObj[0]) {
                $ResponseObj->StatTokenAdmin=$TokenAdmin_PassSet_TokenObj[1];
                echo json_encode($ResponseObj);
                exit();
            }
            // --------------
            $TokenAdmin_PassSet_TokenObj=$TokenAdmin_PassSet_TokenObj[1];
            switch ($TokenAdmin_PassSet_TokenObj->TokenTipo) {
                case 'UsuarioNew':
                    $TokenAdmin_PassSet_TokenObj_UsuarioID=$TokenAdmin_PassSet_TokenObj->UsuarioID;
                    $TokenAdmin_PassSet_Obj=new UsuarioNK($TokenAdmin_PassSet_TokenObj_UsuarioID, $dirRaiz);
                    $TokenAdmin_PassSet_Stat=$TokenAdmin_PassSet_Obj->Password_Set($TokenAdmin_PassSet_Pass1, $TokenAdmin_PassSet_Pass2);
                break;

                case 'UsuarioPassRecover':
                    $TokenAdmin_PassRecoverSet_TokenObj_UsuarioID=$TokenAdmin_PassSet_TokenObj->UsuarioID;
                    $TokenAdmin_PassRecoverSet_Obj=new UsuarioNK($TokenAdmin_PassRecoverSet_TokenObj_UsuarioID, $dirRaiz);
                    $TokenAdmin_PassSet_Stat=$TokenAdmin_PassRecoverSet_Obj->Password_Set($TokenAdmin_PassSet_Pass1, $TokenAdmin_PassSet_Pass2);
                break;
                
                default:
                    $TokenAdmin_PassSet_Stat=[false, "No se conoce el tipo de token"];
                break;
            }

            if($TokenAdmin_PassSet_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                UsuarioNK_TokenAdmin::TokenAdmin_Del($TokenAdmin_PassSet_ID);
            } else {
                $ResponseObj->RespuestaError=$TokenAdmin_PassSet_Stat[1];
            }
            echo json_encode($ResponseObj);
            exit();
        break;
    }
}

// if($ResponseObj->RespuestaBool==true) {
//     echo json_encode($ResponseObj);
//     exit();
// }


$ResponseObj->DIR="Action Token";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;

echo json_encode($ResponseObj);
exit();



?>