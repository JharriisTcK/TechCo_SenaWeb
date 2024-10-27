<?php
require("Privado/DB_DATA.php");
require("PHP/TechCoWeb_class.php");
require("PHP/neoKiriPHP_class.php");
require("PHP/UsuariosNK.php");
require("PHP/ProductosNK_class.php");
require("PHP/CajaNK_class.php");

session_start();
$UsuarioNK_ID="";
$UsuarioNK_KeyJX="";
if(isset($_SESSION["UsuarioNK"])) {
    $UsuarioNK_ID=$_SESSION["UsuarioNK"];
    $UsuarioNK_KeyJX=$_SESSION["UsuarioKeyJX"];
}

$dirRaiz="";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError=false;

if (isset($_POST["TokenAdmin"])) {
    switch ($_POST["TokenAdmin"]) {
        case 'PassSet':
            $TokenAdmin_PassSet_Pass1=$_POST["Pass1"];
            $TokenAdmin_PassSet_Pass2=$_POST["Pass2"];
            $TokenAdmin_PassSet_TokenID=$_POST["TokenID"];
            $TokenAdmin_PassSet_TokenObj=UsuarioNK_TokenAdmin::Get_ID($TokenAdmin_PassSet_TokenID, $dirRaiz);
            if(!$TokenAdmin_PassSet_TokenObj[0]) {
                $ResponseObj->RespuestaError=$TokenAdmin_PassSet_TokenObj[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $TokenAdmin_PassSet_TokenObj=$TokenAdmin_PassSet_TokenObj[1];
            switch ($TokenAdmin_PassSet_TokenObj->TokenTipo) {
                case 'ColaboradorPassRecover':
                break;
            }
            if($TokenAdmin_PassSet_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$TokenAdmin_PassSet_Stat[1];
            }
        break;
    }
}

if (isset($_GET["NeoKiri_Web"])) {
    switch ($_GET["NeoKiri_Web"]) {
        case 'Usuario_MisComprasGet':
            $UsuarioNK_MisComprasGet_KeyJX=$_GET["KeyJX"];
            // Verificar keyjx
            if($UsuarioNK_KeyJX!=$UsuarioNK_MisComprasGet_KeyJX) {
                $ResponseObj->RespuestaError="Las llaves no coinciden";
                echo json_encode($ResponseObj);
                exit();
            }
            // Obtener compras
            $UsuarioNK_MisComprasGet_Cotizaciones=CajaNK_Getters::CotizacionesGet(true);
            if($UsuarioNK_MisComprasGet_Cotizaciones[0]) {
                $ResponseObj->MisCompras=$UsuarioNK_MisComprasGet_Cotizaciones[1];
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_MisComprasGet_Cotizaciones[1];
            }
        break;
    }
}


if (isset($_GET["UsuarioNK"])) {
    switch ($_GET["UsuarioNK"]) {
        case 'UsuarioGet':
        break;
    }
}

if (isset($_POST["UsuarioNK"])) {
    switch ($_POST["UsuarioNK"]) {
        case 'UsuarioGet':
            $UsuarioNK_Get_KeyJX=$_POST["KeyJX"];
            $UsuarioNK_Get_Stat=UsuarioNK_Login::LoginCheck($UsuarioNK_Get_KeyJX);
            if(!$UsuarioNK_Get_Stat[0]) {
                $ResponseObj->RespuestaError=$UsuarioNK_Get_Stat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->RespuestaToken=true;
            $UsuarioNK_Get_Obj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            if(empty($UsuarioNK_Get_Obj->UsuarioID)) {
                $ResponseObj->RespuestaError="ID usuario no encontrado";
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->RespuestaBool=true;
            $ResponseObj->KeyJX=$UsuarioNK_Get_KeyJX;
            $ResponseObj->Usuario=$UsuarioNK_Get_Obj;
        break;

        case 'UsuarioInfoSet':
            $UsuarioNK_Set_Nombres=$_POST["UsuarioNombre"];
            $UsuarioNK_Set_Apellidos=$_POST["UsuarioApellido"];
            $UsuarioNK_Set_Alias=$_POST["UsuarioAlias"];
            $UsuarioNK_Set_Cargo=$_POST["UsuarioCargo"];
            $UsuarioNK_Set_Nacimiento=$_POST["UsuarioFechaNacimiento"];
            $UsuarioNK_Set_Descripcion=$_POST["UsuarioDescripcion"];
            $UsuarioNK_Set_Obj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $UsuarioNK_Set_Stat=$UsuarioNK_Set_Obj->Info_Set($UsuarioNK_Set_Nombres, $UsuarioNK_Set_Apellidos, $UsuarioNK_Set_Alias, $UsuarioNK_Set_Descripcion, $UsuarioNK_Set_Cargo, "?", $UsuarioNK_Set_Nacimiento);
            if($UsuarioNK_Set_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_Set_Stat[1];
            }
        break;

        case "FotoPerfil":
            switch ($_POST["FileImageNK"]) {
                case 'ImagenesGet':
                    $UsuarioFotoPerfil_Get_KeyJX=$_POST["KeyJX"];
                    if($UsuarioFotoPerfil_Get_KeyJX!=$UsuarioNK_KeyJX) {
                        $ResponseObj->RespuestaError="Las llaves no coinciden";
                        echo json_encode($ResponseObj);
                        exit();
                    }
                    $UsuarioFotoPerfil_Get_ObjUsuario=new UsuarioNK($UsuarioNK_ID, $dirRaiz);
                    $Imagenes=[];
                    if(!empty($UsuarioFotoPerfil_Get_ObjUsuario->PerfilH)) {
                        $buffImagen=new stdClass();
                        $buffImagen->id_image="FotoPerfil";
                        $buffImagen->SrcH=$UsuarioFotoPerfil_Get_ObjUsuario->PerfilH;
                        $buffImagen->SrcS=$UsuarioFotoPerfil_Get_ObjUsuario->PerfilS;
                        $buffImagen->Caption="FotoPerfil";
                        array_push($Imagenes, $buffImagen);
                    }
                    $ResponseObj->RespuestaBool=true;
                    $ResponseObj->Imagenes=$Imagenes;
                    echo json_encode($ResponseObj);
                    exit();
                break;

                case 'ImagenSubir':
                    $UsuarioFotoPerfil_Up_KeyJX=$_POST["KeyJX"];
                    $UsuarioFotoPerfil_Up_Imagen=$_FILES["Imagen"];
                    if($UsuarioFotoPerfil_Up_KeyJX!=$UsuarioNK_KeyJX) {
                        $ResponseObj->RespuestaError="Las llaves no coinciden";
                        echo json_encode($ResponseObj);
                        exit();
                    }
                    $UsuarioFotoPerfil_Up_ObjUsuario=new UsuarioNK($UsuarioNK_ID, $dirRaiz);
                    $UsuarioFotoPerfil_Up_Stat=$UsuarioFotoPerfil_Up_ObjUsuario->FotoPerfil_Up($UsuarioFotoPerfil_Up_Imagen, "Foto de Perfil");
                    if($UsuarioFotoPerfil_Up_Stat[0]) {
                        $ResponseObj->RespuestaBool=true;
                    }
                break;
                
                case 'ImagenEliminar':
                    $UsuarioFotoPerfil_Del_KeyJX=$_POST["KeyJX"];
                    if($UsuarioFotoPerfil_Del_KeyJX!=$UsuarioNK_KeyJX) {
                        $ResponseObj->RespuestaError="Las llaves no coinciden";
                        echo json_encode($ResponseObj);
                        exit();
                    }
                    $UsuarioFotoPerfil_Del_ObjUsuario=new UsuarioNK($UsuarioNK_ID, $dirRaiz);
                    $UsuarioFotoPerfil_Del_Stat=$UsuarioFotoPerfil_Del_ObjUsuario->FotoPerfil_Del();
                    if($UsuarioFotoPerfil_Del_Stat[0]) {
                        $ResponseObj->RespuestaBool=true;
                    }
                break;
            }

        break;

        case 'ProductosFavoritosGet':
            $UsuarioNK_FavoritosGet_KeyJX=$_POST["KeyJX"];
            if($UsuarioNK_FavoritosGet_KeyJX!=$_SESSION["UsuarioKeyJX"]) {
                echo json_encode($ResponseObj);
                exit();
            }
            $UsuarioNK_FavoritosGet_Obj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $UsuarioNK_FavoritosGet_Stat=$UsuarioNK_FavoritosGet_Obj->Favoritos_GetAll();
            // $ResponseObj->UsuarioNK=$UsuarioNK_FavoritosGet_Obj;
            if($UsuarioNK_FavoritosGet_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->ProductosFavoritos=$UsuarioNK_FavoritosGet_Obj->Favoritos;
            }
        break;

        case 'ProductoFavoritoEliminar':
            $UsuarioNK_ProductoFavoritoEliminar_KeyJX=$_POST["KeyJX"];
            $UsuarioNK_ProductoFavoritoEliminar_ProductoFavoritoID=$_POST["Producto"];
            if($UsuarioNK_ProductoFavoritoEliminar_KeyJX!=$UsuarioNK_KeyJX) {
                echo json_encode($ResponseObj);
                exit();
            }
            $UsuarioNK_ProductoFavoritoEliminar_UsuarioObj=new UsuarioNK($UsuarioNK_ID, $dirRaiz);
            $UsuarioNK_ProductoFavoritoEliminar_Stat=$UsuarioNK_ProductoFavoritoEliminar_UsuarioObj->Favorito_DelID($UsuarioNK_ProductoFavoritoEliminar_ProductoFavoritoID);
            if($UsuarioNK_ProductoFavoritoEliminar_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $UsuarioNK_ProductoFavoritoEliminar_UsuarioObj->Favoritos_GetAll();
            }
        break;

        case 'ReestablecerContrasenia':
            $UsuarioNK_ReestablecerCont_KeyJX=$_POST["KeyJX"];
            if($UsuarioNK_ReestablecerCont_KeyJX!==$UsuarioNK_KeyJX) {
                $ResponseObj->RespuestaBool="Las llaves no coinciden";
                echo json_encode($ResponseObj);
                exit();
            }
            $UsuarioNK_ReestablecerCont_Correo=UsuariosNK_Getters::Correo_from_Id($UsuarioNK_ID);
            if(!$UsuarioNK_ReestablecerCont_Correo[0]) {
                $ResponseObj->RespuestaBool=$UsuarioNK_ReestablecerCont_Correo[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $UsuarioNK_ReestablecerCont_Stat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioPassRecover", $UsuarioNK_ReestablecerCont_Correo[1]);
            if($UsuarioNK_ReestablecerCont_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_ReestablecerCont_Stat[1];
            }
            echo json_encode($ResponseObj);
            exit();
        break;
    }
}

if (isset($_POST["CompraRevisionNK"])) {
    switch ($_POST["CompraRevisionNK"]) {
        case 'CompraGet':
            $CompraRevision_CompraGet_CotizacionID=$_POST["KeyJX"];
            $CompraRevision_CompraGet_CotizacionObj=new CajaNK_CotizacionVenta($CompraRevision_CompraGet_CotizacionID, $dirRaiz);
            if(!empty($CompraRevision_CompraGet_CotizacionObj->CotizacionVentaID)) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Cotizacion=$CompraRevision_CompraGet_CotizacionObj;
            } else {
                $ResponseObj->RespuestaError="No se encuenta esta cotizacion";
            }
            


        break;
    }
}


// if($ResponseObj->RespuestaBool==true) {
//     echo json_encode($ResponseObj);
//     exit();
// }


$ResponseObj->DIR="Action Usuario";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;
$ResponseObj->COOKIES=$_COOKIE;

if(isset($_SESSION)) {
    $ResponseObj->SESSION=$_SESSION;
}

echo json_encode($ResponseObj);
exit();



?>