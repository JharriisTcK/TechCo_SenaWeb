<?php

require("../Privado/DB_DATA.php");
require("../PHP/neoKiriPHP_class.php");
require("../PHP/CajaNK_class.php");
require("../PHP/ProductosNK_class.php");

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

if (isset($_POST["Caja_AdminNK"])) {
    switch ($_POST["Caja_AdminNK"]) {
        case 'CotizacionesGet':
            $CajaAdminNK_CotizacionesGet_Stat=CajaNK_Getters::CotizacionesGet();
            if($CajaAdminNK_CotizacionesGet_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Cotizaciones=$CajaAdminNK_CotizacionesGet_Stat[1];

            }
        break;
    }
}

if (isset($_POST["CajaRevision_AdminNK"])) {
    switch ($_POST["CajaRevision_AdminNK"]) {
        case 'CotizacionGet':
            $CajaRevisionAdmin_KeyJX=$_POST["KeyJX"];
            $CajaRevisionAdmin_Obj=new CajaNK_CotizacionVenta($CajaRevisionAdmin_KeyJX, $dirRaiz);
            if(!empty($CajaRevisionAdmin_Obj->CotizacionVentaID)) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Cotizacion=$CajaRevisionAdmin_Obj;
                // $ResponseObj->VerificarPago=$CajaRevisionAdmin_Obj->VerificarPago();
            } else {
                $ResponseObj->RespuestaError="No se encuentra la cotizacion";
            }
        break;

        case "ConfirmarCompra":
            $CajaRevisionAdmin_Confirmar_KeyJX=$_POST["KeyJX"];
            $CajaRevisionAdmin_Confirmar_Obj=new CajaNK_CotizacionVenta($CajaRevisionAdmin_Confirmar_KeyJX, $dirRaiz);
            $CajaRevisionAdmin_Confirmar_Stat=$CajaRevisionAdmin_Confirmar_Obj->VentaEstadoSet(1, "");
            $CajaRevisionAdmin_Confirmar_Obj->VerificarPago();
            if($CajaRevisionAdmin_Confirmar_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$CajaRevisionAdmin_Confirmar_Stat[1];
            }

        break;
    }
}


$ResponseObj->DIR="Action Panel Caja";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;

echo json_encode($ResponseObj);
exit();


