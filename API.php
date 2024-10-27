<?php
require("Privado/DB_DATA.php");
require("PHP/neoKiriPHP_class.php");
require("PHP/TechCoWeb_class.php");
require("PHP/UsuariosNK.php");
require("PHP/ProductosNK_class.php");
require("PHP/CajaNK_class.php");

$dirRaiz="";

session_start();

if(isset($_SESSION["UsuarioNK"])) {
    $UsuarioNK_ID=$_SESSION["UsuarioNK"];
    $UsuarioNK_KeyJX=$_SESSION["UsuarioKeyJX"];
} 

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError="";



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

if (isset($_POST["NeoKiri_Web"])) {
    switch ($_POST["NeoKiri_Web"]) {
        case 'UsuarioRegistrar':
            $UsuarioNK_Registrar_Nombres=$_POST["Nombres"];
            $UsuarioNK_Registrar_Apellidos=$_POST["Apellidos"];
            $UsuarioNK_Registrar_Correo=$_POST["Correo"];
            // $UsuarioNK_Registrar_Pass=$_POST["Pass"];
            $UsuarioNK_Registrar_Stat=UsuarioNK::Usuario_New($UsuarioNK_Registrar_Nombres, $UsuarioNK_Registrar_Apellidos, $UsuarioNK_Registrar_Correo, $dirRaiz);
            if (!$UsuarioNK_Registrar_Stat[0]) {
                $ResponseObj->RespuestaError=$UsuarioNK_Registrar_Stat[1];
                $ResponseObj->Stat=$UsuarioNK_Registrar_Stat;
                echo json_encode($ResponseObj);
                exit();
            }
            $Stat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioNew", $UsuarioNK_Registrar_Correo, $dirRaiz);
            // $ResponseObj->Stat2=$Stat;
            if($Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Token=$Stat[1];
            }
        break;

        case 'UsuarioIniciar':
            $UsuarioNK_Iniciar_Correo=$_POST["Correo"];
            $UsuarioNK_Iniciar_Pass=$_POST["Pass"];
            $UsuarioNK_Iniciar_Stat=UsuarioNK_Login::Login($UsuarioNK_Iniciar_Correo, $UsuarioNK_Iniciar_Pass, $dirRaiz);
            if($UsuarioNK_Iniciar_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->KeyJX=$UsuarioNK_Iniciar_Stat[1];
                $ResponseObj->Expira=$UsuarioNK_Iniciar_Stat[2];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_Iniciar_Stat[1];
            }
            $ResponseObj->UsuarioNK_Iniciar_Stat=$UsuarioNK_Iniciar_Stat;
        break;

        case 'UsuarioLoginCheck':
            $UsuarioNK_LoginCheck_KeyJX=$_POST["KeyJX"];
            $UsuarioNK_LoginCheck_Stat=UsuarioNK_Login::LoginCheck($UsuarioNK_LoginCheck_KeyJX);
            if($UsuarioNK_LoginCheck_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->TokenObj=$UsuarioNK_LoginCheck_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_LoginCheck_Stat[1];
                UsuarioNK_Login::Logout();
            }
        break;

        case 'UsuarioLogout':
            $UsuarioNK_Logout_KeyJX=$_POST["KeyJX"];
            // $UsuarioNK_Logout_Stat=UsuarioNK_Login::LoginCheck($UsuarioNK_Logout_KeyJX);
            $UsuarioNK_Logout_Stat=UsuarioNK_Login::Logout();
            if($UsuarioNK_Logout_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                // $ResponseObj->TokenObj=$UsuarioNK_Logout_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_Logout_Stat[1];
            }
        break;

        case 'UsuarioRecuperar':
            $UsuarioNK_Recuperar_KeyJX=$_POST["Correo"];
            $UsuarioNK_Recuperar_Stat=UsuarioNK_TokenAdmin::TokenAdmin_New("UsuarioPassRecover", $UsuarioNK_Recuperar_KeyJX, $dirRaiz);
            if($UsuarioNK_Recuperar_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->TokenObj=$UsuarioNK_Recuperar_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioNK_Recuperar_Stat[1];
            }
        break;

        case 'Usuario_ProductosFavoritosGet':
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

        case 'Usuario_ProductoFavoritoEliminar':
            $UsuarioNK_ProductoFavoritoEliminar_KeyJX=$_POST["KeyJX"];
            $UsuarioNK_ProductoFavoritoEliminar_ProductoFavoritoID=$_POST["Producto"];
            if($UsuarioNK_ProductoFavoritoEliminar_KeyJX!=$_SESSION["UsuarioKeyJX"]) {
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
    }
}

if (isset($_GET["ProductosNK_Board"])) {
    switch ($_GET["ProductosNK_Board"]) {
        case 'ProductosGet':
            $ProductosNKBoard_CategoriasGet_Stat=ProductosCategorias_Getters::GetAll(true);
            if(!$ProductosNKBoard_CategoriasGet_Stat[0]) {
                $ResponseObj->RespuestaError=$ProductosNKBoard_CategoriasGet_Stat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->Categorias=$ProductosNKBoard_CategoriasGet_Stat[1];
            
            $ProductosNKBoard_MarcasGet_Stat=ProductosMarcas_Getters::GetAll(true);
            if(!$ProductosNKBoard_MarcasGet_Stat[0]) {
                $ResponseObj->RespuestaError=$ProductosNKBoard_MarcasGet_Stat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->Marcas=$ProductosNKBoard_MarcasGet_Stat[1];

            switch ($_GET["ObjArea"]) {
                case 'Productos':
                    if ($_GET["ObjID"]=="All") {
                        $ProductosNKBoard_ProductosGet_Stat=ProductosNK_Getters::GetAll(true);
                        if ($ProductosNKBoard_ProductosGet_Stat[0]) {
                            $ResponseObj->RespuestaBool=true;
                            $ResponseObj->Productos=$ProductosNKBoard_ProductosGet_Stat[1];
                        }
                    }
                break;

                case 'Categorias':
                    if ($_GET["ObjID"]=="All") {
                        $ResponseObj->RespuestaBool=true;
                        $ResponseObj->Productos=[];
                    } else {
                        $ProductosNKBoard_ProductosCategoriaIDGet_Stat=ProductosCategorias_Getters::GetIDFromNickDir($_GET["ObjID"]);
                        if(!$ProductosNKBoard_ProductosCategoriaIDGet_Stat[0]) {
                            $ResponseObj->RespuestaError=$ProductosNKBoard_ProductosCategoriaIDGet_Stat[1];
                            echo json_encode($ResponseObj);
                            exit();
                        }
                        $ProductosNKBoard_ProductosCategoriaID=$ProductosNKBoard_ProductosCategoriaIDGet_Stat[1]->ProductoCategoriaID;
                        $ProductosNKBoard_ProductosCategoriaGet_Stat=ProductosNK_Getters::GetAll_FromCategoriaID($ProductosNKBoard_ProductosCategoriaID);
                        if($ProductosNKBoard_ProductosCategoriaGet_Stat[0]) {
                            $ResponseObj->RespuestaBool=true;
                            $ResponseObj->Productos=$ProductosNKBoard_ProductosCategoriaGet_Stat[1];
                        } else {
                            $ResponseObj->RespuestaError=$ProductosNKBoard_ProductosCategoriaGet_Stat[1];
                        }
                    }

                break;

                case 'Marcas':
                    if ($_GET["ObjID"]=="All") {
                        $ResponseObj->RespuestaBool=true;
                        $ResponseObj->Productos=[];
                    } else {
                        $ProductosNKBoard_ProductosMarcaIDGet_Stat=ProductosMarcas_Getters::GetIDFromNickDir($_GET["ObjID"]);
                        if(!$ProductosNKBoard_ProductosMarcaIDGet_Stat[0]) {
                            $ResponseObj->RespuestaError=$ProductosNKBoard_ProductosMarcaIDGet_Stat[1];
                            echo json_encode($ResponseObj);
                            exit();
                        }
                        $ProductosNKBoard_ProductosMarcaID=$ProductosNKBoard_ProductosMarcaIDGet_Stat[1]->ProductoMarcaID;
                        $ProductosNKBoard_ProductosMarcaGet_Stat=ProductosNK_Getters::GetAll_FromMarcaID($ProductosNKBoard_ProductosMarcaID);
                        if($ProductosNKBoard_ProductosMarcaGet_Stat[0]) {
                            $ResponseObj->RespuestaBool=true;
                            $ResponseObj->Productos=$ProductosNKBoard_ProductosMarcaGet_Stat[1];
                        } else {
                            $ResponseObj->RespuestaError=$ProductosNKBoard_ProductosMarcaGet_Stat[1];
                        }
                    }

                break;
            }

        break;
    }
}

if (isset($_GET["ProductoNK"])) {
    switch ($_GET["ProductoNK"]) {
        case 'ProductoGet':
            $ProductoNK_ProductoGet_NickDir=$_GET["KeyJX"];
            $ProductoNK_ProductoGet_StatID=ProductosNK_Getters::GetIDFromNickDir($ProductoNK_ProductoGet_NickDir);
            if(!$ProductoNK_ProductoGet_StatID[0]) {
                $ResponseObj->RespuestaError=$ProductoNK_ProductoGet_StatID[1];
                echo json_encode($ResponseObj);
                exit();
            };
            $ProductoNK_ProductoGet_Obj=new ProductoNK($ProductoNK_ProductoGet_StatID[1]->ProductoID, $dirRaiz);
            if(empty($ProductoNK_ProductoGet_Obj->ProductoID)) {
                $ResponseObj->RespuestaError="Producto no encontrado";
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->RespuestaBool=true;
            $ProductoNK_ProductoGet_Obj->ImagenesGet();
            $ProductoNK_ProductoGet_Obj->VideosGet();
            $ResponseObj->Producto=$ProductoNK_ProductoGet_Obj;
            // ------------------------------------
            if(!isset($_SESSION["UsuarioNK"])) {
                UsuarioNK_Login::Logout();
            }
            $ResponseObj->CarritoExisteIn=false;
            $ResponseObj->CarritoCantidad=1;
            if(isset($_SESSION["CarritoSessionIDS"])) {
                $CarritoObjs=json_decode($_SESSION['CarritoSessionIDS']);
                foreach ($CarritoObjs as $CarritoItem) {
                    if($ProductoNK_ProductoGet_Obj->ProductoID==$CarritoItem->ProductoID) {
                        $ResponseObj->CarritoExisteIn=true;
                        $ResponseObj->CarritoCantidad=$CarritoItem->Cantidad;
                    }
                }
            }
            // ------------------------------------
            $ResponseObj->EsFavorito=false;;
            if(isset($_SESSION["FavoritosSessionIDS"])) {
                $FavoritosSesion_json=json_decode($_SESSION['FavoritosSessionIDS']);
                foreach ($FavoritosSesion_json as $FavoritoSesion_i) {
                    if($ProductoNK_ProductoGet_Obj->ProductoID==$FavoritoSesion_i->ProductoID) {
                        $ResponseObj->EsFavorito=true;
                    }
                }
            }
            // ------------------------------------
        break;
    }
}

if (isset($_POST["ProductoNK"])) {
    switch ($_POST["ProductoNK"]) {
        case 'CarritoAdd':
            if(!isset($_SESSION["UsuarioNK"])){
                $ResponseObj->RespuestaError="No se ha iniciado sesion.";
                echo json_encode($ResponseObj);
                exit();
            }
            // -------
            $ProductoNK_CarritoAdd_ProductoKey=$_POST["KeyJX"];
            $ProductoNK_CarritoAdd_Cantidad=(int)$_POST["Cantidad"];
            // -------Obtener id del producto desde el nick
            $ProductoNK_CarritoAdd_IDFromNickStat=ProductosNK_Getters::GetIDFromNickDir($ProductoNK_CarritoAdd_ProductoKey);
            if(!$ProductoNK_CarritoAdd_IDFromNickStat[0]) {
                echo json_encode($ResponseObj);
                $ResponseObj->RespuestaError=$ProductoNK_CarritoAdd_IDFromNickStat[1];
                exit();
            }
            $ProductoNK_CarritoAdd_ProductoID=$ProductoNK_CarritoAdd_IDFromNickStat[1]->ProductoID;
            //-----Verificar producto y cantidad
            $ProductoNK_CarritoAdd_ProductoObj=new ProductoNK($ProductoNK_CarritoAdd_ProductoID, $dirRaiz);
            if(empty($ProductoNK_CarritoAdd_ProductoObj->ProductoID)) {
                echo json_encode($ResponseObj);
                $ResponseObj->RespuestaError="No se encontro el producto";
                exit();
            }
            if($ProductoNK_CarritoAdd_ProductoObj->Disponibles>$ProductoNK_CarritoAdd_Cantidad) {
                $$ProductoNK_CarritoAdd_Cantidad=$ProductoNK_CarritoAdd_ProductoObj->Disponibles;
            }
            if($ProductoNK_CarritoAdd_Cantidad<1) {
                $$ProductoNK_CarritoAdd_Cantidad=1;
            }
            //-----
            $ProductoNK_CarritoAdd_UsuarioObj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $ProductoNK_CarritoAdd_UsuarioObj->Carrito_DelID($ProductoNK_CarritoAdd_ProductoObj->ProductoID);
            $ProductoNK_CarritoAdd_Stat=$ProductoNK_CarritoAdd_UsuarioObj->CarritoAdd($ProductoNK_CarritoAdd_ProductoObj->ProductoID, $ProductoNK_CarritoAdd_Cantidad);
            if($ProductoNK_CarritoAdd_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            }
            $ProductoNK_CarritoAdd_UsuarioObj->Carrito_GetAll();

        break;
            
        case "CarritoGet":
            if(!isset($_SESSION["UsuarioNK"])){
                $ResponseObj->RespuestaError="No hay SessionID";
                echo json_encode($ResponseObj);
                exit();
            }
            $ProductoNK_CarritoGet_UsuarioObj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $ProductoNK_CarritoGet_Stat=$ProductoNK_CarritoGet_UsuarioObj->Carrito_GetAll();
            if($ProductoNK_CarritoGet_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Carrito=$ProductoNK_CarritoGet_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$ProductoNK_CarritoGet_Stat[1];
            }
        break;

        case "CarritoDel":
            if(!isset($_SESSION["UsuarioNK"])){
                $ResponseObj->RespuestaError="No hay SessionID";
                echo json_encode($ResponseObj);
                exit();
            }
            $ProductoNK_CarritoDel_CarritoID=$_POST["CarritoID"];
            $ProductoNK_CarritoDel_UsuarioObj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $ProductoNK_CarritoDel_Stat=$ProductoNK_CarritoDel_UsuarioObj->Carrito_DelID($ProductoNK_CarritoDel_CarritoID);
            if($ProductoNK_CarritoDel_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Carrito=$ProductoNK_CarritoDel_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$ProductoNK_CarritoDel_Stat[1];
            }
        break;

        case "FavoritoClic":
            if(!isset($_SESSION["UsuarioNK"])) {
                $ResponseObj->RespuestaError="Sesion no iniciada";
                echo json_encode($ResponseObj);
                exit();
            }
            $ProductoNK_FavoritoClick_KeyJX=$_POST["KeyJX"];
            // Obtener id de producto del nick
            $ProductoNK_FavoritoClick_FromKey=ProductosNK_Getters::GetIDFromNickDir($ProductoNK_FavoritoClick_KeyJX);
            if(!$ProductoNK_FavoritoClick_FromKey[0]) {
                echo json_encode($ResponseObj);
                exit();
            }
            $ProductoNK_FavoritoClick_ProductoIDFromKey=$ProductoNK_FavoritoClick_FromKey[1]->ProductoID;
            // Obtener informacion de usuario y productos favoritos, /Para crear session
            $ProductoNK_FavoritoClick_UsuarioObj=new UsuarioNK($_SESSION["UsuarioNK"], $dirRaiz);
            $ProductoNK_FavoritoClick_UsuarioObj->Favoritos_GetAll();
            $ResponseObj->UsuarioObj=$ProductoNK_FavoritoClick_UsuarioObj;
            // Buscar producto en favoritos
            $ProductoNK_FavoritoClick_EsFavorito=false;
            foreach ($ProductoNK_FavoritoClick_UsuarioObj->Favoritos as $UsuarioProductoFavorito) {
                if($UsuarioProductoFavorito->ProductoID==$ProductoNK_FavoritoClick_ProductoIDFromKey) {
                    $ProductoNK_FavoritoClick_EsFavorito=true;
                }
            }
            // Si esta en favorito eliminar, de lo contrario agregar
            $ProductoNK_FavoritoClick_RespuestaOK="";
            $ResponseObj->EsFavorito=$ProductoNK_FavoritoClick_EsFavorito;
            if($ProductoNK_FavoritoClick_EsFavorito) {
                $ProductoNK_FavoritoClick_Stat=$ProductoNK_FavoritoClick_UsuarioObj->Favorito_DelID($ProductoNK_FavoritoClick_ProductoIDFromKey);
                $ProductoNK_FavoritoClick_RespuestaOK="Producto Eliminado de Favoritos";
                $ResponseObj->EsFavorito=false;
            } else {
                $ProductoNK_FavoritoClick_Stat=$ProductoNK_FavoritoClick_UsuarioObj->Favorito_Add($ProductoNK_FavoritoClick_ProductoIDFromKey);
                $ProductoNK_FavoritoClick_RespuestaOK="Añadido a Favoritos";
                $ResponseObj->EsFavorito=true;
            }
            // -----------
            if(!$ProductoNK_FavoritoClick_Stat[0]) {
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->RespuestaBool=true;
            $ResponseObj->RespuestaOK=$ProductoNK_FavoritoClick_RespuestaOK;
            $ProductoNK_FavoritoClick_UsuarioObj->Favoritos_GetAll();
        break;

    }
}

if (isset($_POST["CarritoComprasNK"])) {
    switch ($_POST["CarritoComprasNK"]) {
        case 'ConfirmarCompra':
            // Comprobar KeyJX
            $CarritoCompras_Confirmar_KeyJX=$_POST["KeyJX"];
            if($CarritoCompras_Confirmar_KeyJX!=$UsuarioNK_KeyJX) {
                $ResponseObj->RespuestaError="Las llaves no coinciden";
                echo json_encode($ResponseObj);
                exit();
            }
            // Datos del Contacto
            $CarritoCompras_Confirmar_ClienteTipo=$_POST["ClienteTipo"];
            $CarritoCompras_Confirmar_ClienteTelefono=$_POST["ClienteTelefono"];
            $CarritoCompras_Confirmar_ClienteCorreo=$_POST["ClienteCorreo"];
            // Datos del cliente persona
            $CarritoCompras_Confirmar_PersonaNombres=$_POST["PersonaNombre"];
            $CarritoCompras_Confirmar_PersonaID=$_POST["PersonaID"];
            // Datos del cliente empresa
            $CarritoCompras_Confirmar_EmpresaNombre=$_POST["EmpresaNombre"];
            $CarritoCompras_Confirmar_EmpresaNIT=$_POST["EmpresaNIT"];
            $CarritoCompras_Confirmar_EmpresaNITDV=$_POST["EmpresaNITDV"];
            // Datos del Direccion
            $CarritoCompras_Confirmar_DireccionDepartamento=$_POST["DireccionDepartamento"];
            $CarritoCompras_Confirmar_DireccionMunicipio=$_POST["DireccionMunicipio"];
            $CarritoCompras_Confirmar_DireccionEnvio=$_POST["DireccionEnvio"];
            // Datos de pago
            $CarritoCompras_Confirmar_PagoTipo=$_POST["PagoTipo"];
            
            // Obtener carrito de compras de usuario
            $CarritoCompras_Confirmar_UsuarioObj=new UsuarioNK($UsuarioNK_ID, $dirRaiz);
            // Obtener ids de carrito
            $CarritoCompras_Confirmar_UsuarioCarrito=$CarritoCompras_Confirmar_UsuarioObj->Carrito_GetAll_Lite();
            $ResponseObj->Carrito=$CarritoCompras_Confirmar_UsuarioCarrito;
            if(!$CarritoCompras_Confirmar_UsuarioCarrito[0]) {
                $ResponseObj->RespuestaError="Carrito de compras vacio";
                echo json_encode($ResponseObj);
                exit();
            }
            // Verificar ids obtenidas del carrito
            $CarritoCompras_Confirmar_ProductosdeCarrito=ProductosNK_Getters::FromIDS($CarritoCompras_Confirmar_UsuarioCarrito[2], true);
            if(!$CarritoCompras_Confirmar_ProductosdeCarrito[0]) {
                $ResponseObj->RespuestaError="Productos del Carrito no obtenido";
                echo json_encode($ResponseObj);
                exit();
            }
            $ResponseObj->Productosdecarrito=$CarritoCompras_Confirmar_ProductosdeCarrito;

            // Crer nuevos datos de cotizacion (valor)
            $CarritoCompras_Confirmar_Items=[];
            $CarritoCompras_Confirmar_ItemsRestarCantidad=[];
            $CarritoCompras_Confirmar_PrecioTotalBase=0;
            $CarritoCompras_Confirmar_PrecioTotalIVA=0;
            $CarritoCompras_Confirmar_PrecioTotal=0;
            foreach ($CarritoCompras_Confirmar_ProductosdeCarrito[1] as $ProductodeCarrito) {
                $ProductodeCarrito_buff=new stdClass();
                $ProductodeCarrito_buff->ProductoID=$ProductodeCarrito->ProductoID;
                $ProductodeCarrito_buff->Nombre=$ProductodeCarrito->Nombre;
                $ProductodeCarrito_buff->PrecioUND=$ProductodeCarrito->PrecioFinal;
                $ProductodeCarrito_buff->Cantidad=1;
                foreach ($CarritoCompras_Confirmar_UsuarioCarrito[1] as $Productoencarrito) {
                    if($Productoencarrito->ProductoID==$ProductodeCarrito->ProductoID) {
                        $ProductodeCarrito_buff->Cantidad=$Productoencarrito->Cantidad;
                    }
                }
                $ProductodeCarrito_buff->PrecioItems=$ProductodeCarrito->PrecioFinal*$ProductodeCarrito_buff->Cantidad;
                $ProductodeCarrito_buff->PrecioIVA=($ProductodeCarrito_buff->PrecioItems*19)/100;
                $ProductodeCarrito_buff->Precio=$ProductodeCarrito_buff->PrecioItems+$ProductodeCarrito_buff->PrecioIVA;
                array_push($CarritoCompras_Confirmar_Items, $ProductodeCarrito_buff);
                $CarritoCompras_Confirmar_PrecioTotalBase+=$ProductodeCarrito_buff->PrecioItems;
                $CarritoCompras_Confirmar_PrecioTotalIVA+=$ProductodeCarrito_buff->PrecioIVA;
                // ----------------------------------
                $ProductoCarrito_ItemsRestar_buffStd=new stdClass();
                $ProductoCarrito_ItemsRestar_buffStd->ProductoID=$ProductodeCarrito->ProductoID;
                $ProductoCarrito_ItemsRestar_buffStd->CantidadNueva=$ProductodeCarrito_buff->Cantidad-$Productoencarrito->Cantidad;
                array_push($CarritoCompras_Confirmar_ItemsRestarCantidad, $ProductoCarrito_ItemsRestar_buffStd);
            }
            $CarritoCompras_Confirmar_PrecioTotal=$CarritoCompras_Confirmar_PrecioTotalBase+$CarritoCompras_Confirmar_PrecioTotalIVA;
            $ResponseObj->Items=$CarritoCompras_Confirmar_Items;
            // --------------------
            // Crear nueva Cotizacion y confirmar creacion
            $CarritoCompras_Confirmar_CotizacionNewStat=CajaNK_CotizacionVenta::CotizacionVenta_New($CarritoCompras_Confirmar_ClienteTelefono, $CarritoCompras_Confirmar_ClienteCorreo, $UsuarioNK_ID, $dirRaiz);
            if(!$CarritoCompras_Confirmar_CotizacionNewStat[0]) {
                $ResponseObj->RespuestaError=$CarritoCompras_Confirmar_CotizacionNewStat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $CarritoCompras_Confirmar_CotizacionNewObj=new CajaNK_CotizacionVenta($CarritoCompras_Confirmar_CotizacionNewStat[1], $dirRaiz);
            // Ingresar datos de persona o empresa
            if($CarritoCompras_Confirmar_ClienteTipo=="Persona") {
                $CarritoCompras_Confirmar_CotizacionNewObj->PersonaInfo_Set($CarritoCompras_Confirmar_PersonaNombres, "CC", $CarritoCompras_Confirmar_PersonaID);
                
            } else if ($CarritoCompras_Confirmar_ClienteTipo=="Empresa") {
                $CarritoCompras_Confirmar_CotizacionNewObj->EmpresaInfo_Set($CarritoCompras_Confirmar_EmpresaNombre, $CarritoCompras_Confirmar_EmpresaNIT, $CarritoCompras_Confirmar_EmpresaNITDV);
            } else {
                $ResponseObj->RespuestaError="Tipo de cliente no identificado, whats up??";
                echo json_encode($ResponseObj);
                exit();
            }
            // Ingresar datos de direccion y pago
            $CarritoCompras_Confirmar_CotizacionNewObj->ContactoInfo_Set($CarritoCompras_Confirmar_ClienteTelefono, $CarritoCompras_Confirmar_ClienteCorreo, $CarritoCompras_Confirmar_DireccionEnvio, 000, $CarritoCompras_Confirmar_PagoTipo);
            // Ingresar datos de precios e items
            $CarritoCompras_Confirmar_CotizacionNewObj->Precios_Set($CarritoCompras_Confirmar_Items, $CarritoCompras_Confirmar_PrecioTotalBase, $CarritoCompras_Confirmar_PrecioTotalIVA, $CarritoCompras_Confirmar_PrecioTotal);
            
            // Limpiar Carrito de Compras
            $CarritoCompras_Confirmar_CarritoLimpiarStat=$CarritoCompras_Confirmar_UsuarioObj->Carrito_DelAll();
            if(!$CarritoCompras_Confirmar_CarritoLimpiarStat[0]) {
                $ResponseObj->RespuestaError="No se limpio el carrito de compras: ".$CarritoCompras_Confirmar_CarritoLimpiarStat[1];
                echo json_encode($ResponseObj);
                exit();
            }
            $CarritoCompras_Confirmar_CotizacionNewObj->VentaEstadoSet(1, "Sin observaciones");
            $ResponseObj->RespuestaBool=true;
            $ResponseObj->CotizacionID=$CarritoCompras_Confirmar_CotizacionNewObj->CotizacionVentaID;
            $ResponseObj->NuevaCantidadRestar=$CarritoCompras_Confirmar_ItemsRestarCantidad;
        break;
    }
}

// if($ResponseObj->RespuestaBool==true) {
//     echo json_encode($ResponseObj);
//     exit();
// }

header('Content-Type: application/json; charset=utf-8');

$ResponseObj->DIR="API";
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