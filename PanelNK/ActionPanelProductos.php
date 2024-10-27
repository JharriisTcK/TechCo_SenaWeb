<?php

require("../Privado/DB_DATA.php");
require("../PHP/neoKiriPHP_class.php");
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


if (isset($_POST["ProductosNK_Admin"])) {
    switch ($_POST["ProductosNK_Admin"]) {
        case 'Info_Get':
            $ProductosNKAdmin_InfoGet_Categorias=ProductosCategorias_Getters::GetAll();
            if(!$ProductosNKAdmin_InfoGet_Categorias[0]) {
				$ResponseObj->RespuestaError=$ProductosNKAdmin_InfoGet_Categorias[1];
				exit();
            }
			// $ResponseObj->Categorias=$ProductosNKAdmin_InfoGet_Categorias[1];

			// {
			// 	Nombre: "";
			// 	Valor: "";
			// 	Seleccionado: "";
			// }
			$CategoriasProductos=[];
			foreach ($ProductosNKAdmin_InfoGet_Categorias[1] as $Categoria) {
				$buffCategoria=new stdClass();
				$buffCategoria->Nombre=$Categoria->Nombre;
				$buffCategoria->Valor=$Categoria->ProductoCategoriaID;
				$buffCategoria->Seleccionado=false;
				array_push($CategoriasProductos, $buffCategoria);
			}

			$ProductosNKAdmin_InfoGet_Marcas=ProductosMarcas_Getters::GetAll();
			if(!$ProductosNKAdmin_InfoGet_Marcas[0]) {
				echo json_encode($ResponseObj);
				exit();
				$ResponseObj->RespuestaError=$ProductosNKAdmin_InfoGet_Marcas[1];
			}
			$MarcasProductos=[];
			foreach ($ProductosNKAdmin_InfoGet_Marcas[1] as $Marca) {
				$buffMarca=new stdClass();
				$buffMarca->Nombre=$Marca->Nombre;
				$buffMarca->Valor=$Marca->ProductoMarcaID;
				$buffMarca->Seleccionado=false;
				array_push($MarcasProductos, $buffMarca);
			}

            $ProductosNKAdmin_InfoGet_Productos=ProductosNK_Getters::GetAll(false);
            if($ProductosNKAdmin_InfoGet_Productos[0]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Productos=$ProductosNKAdmin_InfoGet_Productos[1];
                $ResponseObj->Categorias=$CategoriasProductos;
                $ResponseObj->Marcas=$MarcasProductos;
            }
        break;


        case 'Producto_Add':
            $ProductosNKAdmin_ProductoAdd_Nombre=$_POST["ProductoNombre"];
            $ProductosNKAdmin_ProductoAdd_CodeID=$_POST["ProductoCodigo"];
            $ProductosNKAdmin_ProductoAdd_Categoria=$_POST["ProductoCategoria"];
            $ProductosNKAdmin_ProductoAdd_Marca=$_POST["ProductoMarca"];
            $ProductosNKAdmin_ProductoAdd_Stat=ProductoNK::Producto_New($ProductosNKAdmin_ProductoAdd_Nombre, $ProductosNKAdmin_ProductoAdd_CodeID, $ProductosNKAdmin_ProductoAdd_Categoria, $ProductosNKAdmin_ProductoAdd_Marca, $dirRaiz);
            if(!$ProductosNKAdmin_ProductoAdd_Stat[0]) {
                $ResponseObj->RespuestaError=$ProductosNKAdmin_ProductoAdd_Stat[1];
				echo json_encode($ResponseObj);
				exit();
            }
			$ProductosNKAdmin_ProductoAdd_Obj=new ProductoNK($ProductosNKAdmin_ProductoAdd_Stat[1], $dirRaiz);
			$ProductosNKAdmin_ProductoAdd_LiteStat=$ProductosNKAdmin_ProductoAdd_Obj->DBLite_Crear();
			if($ProductosNKAdmin_ProductoAdd_LiteStat[0]) {
				$ResponseObj->RespuestaBool=true;
			}
        break;

        case 'Producto_Del':
            $ProductosNKAdmin_ProductoDel_ProductoID=$_POST["ProductoID"];
            $ProductosNKAdmin_ProductoDel_Obj=new ProductoNK($ProductosNKAdmin_ProductoDel_ProductoID, $dirRaiz);
            $ProductosNKAdmin_ProductoDel_Stat=$ProductosNKAdmin_ProductoDel_Obj->Producto_Del();
            if($ProductosNKAdmin_ProductoDel_Stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$ProductosNKAdmin_ProductoDel_Stat[1];
            }
        break;

        case 'Producto_Habilitar':
            $ProductosNKAdmin_ProdHabilitar_ID=$_POST["ProductoID"];
            $ProductosNKAdmin_ProdHabilitar_Obj=new ProductoNK($ProductosNKAdmin_ProdHabilitar_ID, $dirRaiz);
            $stat=$ProductosNKAdmin_ProdHabilitar_Obj->HabilitarStat();
            if($stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$stat[1];
            }
        break;
    }
}

if (isset($_POST["ProductosNK_AdminFormProducto"])) {
    switch ($_POST["ProductosNK_AdminFormProducto"]) {
        case 'Info_Get':
            $ProductosNK_AdminFormProducto_KeyJX=$_POST["KeyJX"];;
			// ----------------------------------------------
			// Obtener categorias
			$ProductosNK_AdminFormProducto_CategoriasStat=ProductosCategorias_Getters::GetAll();
			if(!$ProductosNK_AdminFormProducto_CategoriasStat[0]) {
				$ResponseObj->RespuestaError=$ProductosNK_AdminFormProducto_CategoriasStat[1];
				echo json_encode($ResponseObj);
				exit();
			}
			$CategoriasResultado=$ProductosNK_AdminFormProducto_CategoriasStat[1];
			// ----------------------------------------------
			// Obtener marcas
			$ProductosNK_AdminFormProducto_MarcasStat=ProductosMarcas_Getters::GetAll();
			if(!$ProductosNK_AdminFormProducto_MarcasStat[0]) {
				$ResponseObj->RespuestaError=$ProductosNK_AdminFormProducto_MarcasStat[1];
				echo json_encode($ResponseObj);
				exit();
			}
			$MarcasResultado=$ProductosNK_AdminFormProducto_MarcasStat[1];
			// ----------------------------------------------
			// Obtener Producto
			$ProductosNK_AdminFormProducto_ProductoObj=new ProductoNK($ProductosNK_AdminFormProducto_KeyJX, $dirRaiz);
            if (empty($ProductosNK_AdminFormProducto_ProductoObj->ProductoID)) {
				$ResponseObj->RespuestaError="No se encontro informacion del producto.";
				echo json_encode($ResponseObj);
				exit();
			}
			// ----------------------------------------------
			// Configurar Categorias Obtenidas en formato FOrmNK::Select
			$buffCategorias=[];
			foreach ($CategoriasResultado as $Categoria) {
				$buffCategoria=new stdClass();
				$buffCategoria->Nombre=$Categoria->Nombre;
				$buffCategoria->Valor=$Categoria->ProductoCategoriaID;
				$buffCategoria->Seleccionado=false;
				if($ProductosNK_AdminFormProducto_ProductoObj->CategoriaID==$Categoria->ProductoCategoriaID) {
					$buffCategoria->Seleccionado=true;
				}
				array_push($buffCategorias, $buffCategoria);
			}
			// ----------------------------------------------
			// Configurar Categorias Obtenidas en formato FOrmNK::Select
			$buffMarcas=[];
			foreach ($MarcasResultado as $Marca) {
				$buffMarca=new stdClass();
				$buffMarca->Nombre=$Marca->Nombre;
				$buffMarca->Valor=$Marca->ProductoMarcaID;
				$buffMarca->Seleccionado=false;
				if($ProductosNK_AdminFormProducto_ProductoObj->MarcaID==$Marca->ProductoMarcaID) {
					$buffMarca->Seleccionado=true;
				}
				array_push($buffMarcas, $buffMarca);
			}
			// ----------------------------------------------
			$ResponseObj->RespuestaBool=true;
			$ResponseObj->Producto=$ProductosNK_AdminFormProducto_ProductoObj;
			$ResponseObj->Categorias=$buffCategorias;
			$ResponseObj->Marcas=$buffMarcas;

        break;

        case 'Producto_Edit':
            $AdminFormProducto_Edit_KeyJX=$_POST["ProductoID"];
            $AdminFormProducto_Edit_ProductoObj=new ProductoNK($AdminFormProducto_Edit_KeyJX, $dirRaiz);
            $stat=$AdminFormProducto_Edit_ProductoObj->Info_Set(
                $_POST["ProductoCategoria"],
                $_POST["ProductoCodigo"],
                $_POST["NickDir"],
                $_POST["ProductoNombre"],
                $_POST["ProductoMarca"],
                $_POST["Descripcion"],
                $_POST["PrecioDistribuidor"],
                $_POST["PrecioFinal"],
                $_POST["PrecioFinalOferta"],
                $_POST["Disponibles"]
            );
            if($stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$stat[1];
            }
        break;

        case "PortadaIMG":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$ProductoPortada_Get_KeyJX=$_POST["KeyJX"];
					$ProductoPortada_Get_Obj=new ProductoNK($ProductoPortada_Get_KeyJX, $dirRaiz);
					if(!empty($ProductoPortada_Get_Obj->PortadaS)) {
						$ResponseObj->RespuestaBool=true;
						$ProductoPortada_Get_Std=new stdClass();
						$ProductoPortada_Get_Std->id_image="portadaImg";
						$ProductoPortada_Get_Std->SrcS=$ProductoPortada_Get_Obj->PortadaS;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$ProductoPortada_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$ProductoPortada_Up_KeyJX=$_POST["KeyJX"];
					$ProductoPortada_Up_Imagen=$_FILES["Imagen"];
					$ProductoPortada_Up_Caption=$_POST["Caption"];
					$ProductoPortada_Up_Obj=new ProductoNK($ProductoPortada_Up_KeyJX, $dirRaiz);
					$ProductoPortada_Up_Stat=$ProductoPortada_Up_Obj->PortadaImg_Upload($ProductoPortada_Up_Imagen, $ProductoPortada_Up_Caption);
					if($ProductoPortada_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$ProductoPortada_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoPortada_Up_Stat[1];
					}
				break;

				case "ImagenEliminar":
					$ProductoPortada_Del_ID=$_POST["KeyJX"];
					$ProductoPortada_Del_Obj=new ProductoNK($ProductoPortada_Del_ID, $dirRaiz);
					$ProductoPortada_Del_Stat=$ProductoPortada_Del_Obj->PortadaImg_Del();
					if($ProductoPortada_Del_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoPortada_Del_Stat[1];
					}
				break;
			}
		break;

        case "PortadaFB":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$ProductoPortadaFB_Get_KeyJX=$_POST["KeyJX"];
					$ProductoPortadaFB_Get_Obj=new ProductoNK($ProductoPortadaFB_Get_KeyJX, $dirRaiz);
					if(!empty($ProductoPortadaFB_Get_Obj->PortadaFB)) {
						$ResponseObj->RespuestaBool=true;
						$ProductoPortadaFB_Get_Std=new stdClass();
						$ProductoPortadaFB_Get_Std->id_image="portadaImg";
						$ProductoPortadaFB_Get_Std->SrcS=$ProductoPortadaFB_Get_Obj->PortadaFB;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$ProductoPortadaFB_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$ProductoPortadaFB_Up_ID=$_POST["KeyJX"];
					$ProductoPortadaFB_Up_Imagen=$_FILES["Imagen"];
					$ProductoPortadaFB_Up_Caption=$_POST["Caption"];
					$ProductoPortadaFB_Up_Obj=new ProductoNK($ProductoPortadaFB_Up_ID, $dirRaiz);
					$ProductoPortadaFB_Up_Stat=$ProductoPortadaFB_Up_Obj->PortadaFB_Upload($ProductoPortadaFB_Up_Imagen, $ProductoPortadaFB_Up_Caption);
					if($ProductoPortadaFB_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$ProductoPortadaFB_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoPortadaFB_Up_Stat[1];

					}
				break;

				case "ImagenEliminar":
					$ArticuloPortadaEliminar_ID=$_POST["KeyJX"];
					$ArticuloPortadaEliminar_Obj=new ProductoNK($ArticuloPortadaEliminar_ID, $dirRaiz);
					$ArticuloPortadaEliminar_Stat=$ArticuloPortadaEliminar_Obj->PortadaFB_Del();
					if($ArticuloPortadaEliminar_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloPortadaEliminar_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;
			}
		break;

        case "ProductoContenido":
			switch ($_POST['TextareaRichNK']) {

                case "ContenidoGet":
					$ProductoContenidoGet_ID=$_POST["KeyJX"];
					$ProductoContenidoGet_Obj=new ProductoNK($ProductoContenidoGet_ID, $dirRaiz);
					$ProductoContenidoGet_Obj->ImagenesGet();
					$ProductoContenidoGet_Obj->VideosGet();
					if(!empty($ProductoContenidoGet_Obj->ProductoID)) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Contenido=$ProductoContenidoGet_Obj->Contenido;
						$ResponseObj->Imagenes=$ProductoContenidoGet_Obj->Imagenes;
						$ResponseObj->Videos=$ProductoContenidoGet_Obj->Videos;
					} else {
						$ResponseObj->RespuestaError="no se pudo obtener el contenido del producto";
					}
				break;

                case "ContenidoSet":
					$ProductoContenidoSet_ID=$_POST["KeyJX"];
					$ProductoContenidoSet_Contenido=$_POST["Contenido"];
					$ProductoContenidoSet_Obj=new ProductoNK($ProductoContenidoSet_ID, $dirRaiz);
					$ProductoContenidoSet_Stat=$ProductoContenidoSet_Obj->Contenido_Set($ProductoContenidoSet_Contenido);
					if($ProductoContenidoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenidoSet_Stat[1];
					}
				break;

				case 'ImagenesGet':
					$ProductoContenido_ImagenesGet_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_ImagenesGet_Obj=new ProductoNK($ProductoContenido_ImagenesGet_KeyJX, $dirRaiz);
					$ProductoContenido_ImagenesGet_Stat=$ProductoContenido_ImagenesGet_Obj->ImagenesGet();
					if($ProductoContenido_ImagenesGet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Imagenes=$ProductoContenido_ImagenesGet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_ImagenesGet_Stat[1];
					}
				break;

				case "ImagenSubir":
					$ProductoContenido_ImagenesUp_ID=$_POST["KeyJX"];
					$ProductoContenido_ImagenesUp_Img=$_FILES["Imagen"];
					$ProductoContenido_ImagenesUp_Caption=$_POST["Caption"];
					$ProductoContenido_ImagenesUp_Obj=new ProductoNK($ProductoContenido_ImagenesUp_ID, $dirRaiz);
					$ProductoContenido_ImagenesUp_Stat=$ProductoContenido_ImagenesUp_Obj->ImagenSubir($ProductoContenido_ImagenesUp_Img, $ProductoContenido_ImagenesUp_Caption);
					if($ProductoContenido_ImagenesUp_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->ArticuloImagenUp_LastID=$ProductoContenido_ImagenesUp_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_ImagenesUp_Stat[1];
					}
				break;
				
				case "ImagenEliminar":
					$ProductoContenido_ImagenDel_ID=$_POST["KeyJX"];
					$ProductoContenido_ImagenDel_ImgID=$_POST["ImagenID"];
					$ProductoContenido_ImagenDel_Obj=new ProductoNK($ProductoContenido_ImagenDel_ID, $dirRaiz);
					$ProductoContenido_ImagenDel_Stat=$ProductoContenido_ImagenDel_Obj->ImagenDel($ProductoContenido_ImagenDel_ImgID);
					if($ProductoContenido_ImagenDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						// $ResponseObj->Imagen=$ProductoContenido_ImagenDel_Stat;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_ImagenDel_Stat[1];
					}
				break;

				case "VideoYoutubeSubir":
					$ProductoContenido_VideoSet_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoSet_Titulo=$_POST["Titulo"];
					$ProductoContenido_VideoSet_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoSet_Obj=new ProductoNK($ProductoContenido_VideoSet_KeyJX, $dirRaiz);
					$ProductoContenido_VideoSet_Stat=$ProductoContenido_VideoSet_Obj->VideoYoutubeSet($ProductoContenido_VideoSet_Titulo, $ProductoContenido_VideoSet_VideoID);
					if($ProductoContenido_VideoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->LastID=$ProductoContenido_VideoSet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoSet_Stat[1];
					}
				break;
				
				case "VideoEliminar":
					$ProductoContenido_VideoDel_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoDel_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoDel_Obj=new ProductoNK($ProductoContenido_VideoDel_KeyJX, $dirRaiz);
					$ProductoContenido_VideoDel_Stat=$ProductoContenido_VideoDel_Obj->VideoDel($ProductoContenido_VideoDel_VideoID);
					if($ProductoContenido_VideoDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoDel_Stat[1];
					}
				break;
			}
		break;
    }
}

if (isset($_POST["ProductosNK_CategoriasAdmin"])) {
    switch ($_POST["ProductosNK_CategoriasAdmin"]) {
        case 'Categoria_Add':
			$ProductosNKAdmin_CategoriaAdd_Nombre=$_POST["NombreCategoria"];
			$stat=ProductoCategoriaNK::Categoria_New($ProductosNKAdmin_CategoriaAdd_Nombre, $dirRaiz);
			if(!$stat[0]) {
				$ResponseObj->RespuestaError=$stat[1];
				echo json_encode($ResponseObj);
				exit();
			}
			$ProductosNKAdmin_CategoriaAdd_Obj=new ProductoCategoriaNK($stat[1], $dirRaiz);
			$ProductosNKAdmin_CategoriaAdd_LiteStat=$ProductosNKAdmin_CategoriaAdd_Obj->DBLite_Crear();
			if($ProductosNKAdmin_CategoriaAdd_LiteStat[0]) {
				$ResponseObj->RespuestaBool=true;
			}
		break;

		case "Categorias_Get":
			$ProductosCategoriasNKAdmin_CategoriaGet_KeyJX=$_POST["KeyJX"];
			$ProductosCategoriasNKAdmin_CategoriaGet_Stat=ProductosCategorias_Getters::GetAll(false);
			if($ProductosCategoriasNKAdmin_CategoriaGet_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
				$ResponseObj->ProductosCategorias=$ProductosCategoriasNKAdmin_CategoriaGet_Stat[1];
			} else {
				$ResponseObj->RespuestaError=$ProductosCategoriasNKAdmin_CategoriaGet_Stat[1];
			}
		break;

		case 'Categoria_Habilitar':
			$ProductosCategoriasNKAdmin_CategoriaHabilitar_KeyJX=$_POST["KeyJX"];
			$ProductosCategoriasNKAdmin_CategoriaHabilitar_ID=$_POST["ProductoCategoriaID"];
			$ProductosCategoriasNKAdmin_CategoriaHabilitar_Obj=new ProductoCategoriaNK($ProductosCategoriasNKAdmin_CategoriaHabilitar_ID, $dirRaiz);
			$ProductosCategoriasNKAdmin_CategoriaHabilitar_Stat=$ProductosCategoriasNKAdmin_CategoriaHabilitar_Obj->Habilitar();
			if($ProductosCategoriasNKAdmin_CategoriaHabilitar_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->RespuestaError=$ProductosCategoriasNKAdmin_CategoriaHabilitar_Stat[1];
			}
		break;
		
		case 'Categoria_Del':
			$ProductosCategoriasNKAdmin_CategoriaEliminar_KeyJX=$_POST["KeyJX"];
			$ProductosCategoriasNKAdmin_CategoriaEliminar_ID=$_POST["ProductoCategoriaID"];
			$ProductosCategoriasNKAdmin_CategoriaEliminar_Obj=new ProductoCategoriaNK($ProductosCategoriasNKAdmin_CategoriaEliminar_ID, $dirRaiz);
			$ProductosCategoriasNKAdmin_CategoriaEliminar_Stat=$ProductosCategoriasNKAdmin_CategoriaEliminar_Obj->ProductoCategoria_Delete();
			if($ProductosCategoriasNKAdmin_CategoriaEliminar_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->RespuestaError=$ProductosCategoriasNKAdmin_CategoriaEliminar_Stat[1];
			}
		break;
	}
}

if (isset($_POST["ProductosNK_CategoriaAdminForm"])) {
    switch ($_POST["ProductosNK_CategoriaAdminForm"]) {
        case 'Info_Get':
            $ProductosNK_CategoriaAdminForm_KeyJX=$_POST["KeyJX"];;
			// ----------------------------------------------
			// Obtener Producto
			$ProductosNK_CategoriaAdminForm_CategoriaObj=new ProductoCategoriaNK($ProductosNK_CategoriaAdminForm_KeyJX, $dirRaiz);
            if (empty($ProductosNK_CategoriaAdminForm_CategoriaObj->ProductoCategoriaID)) {
				$ResponseObj->RespuestaError="No se encontro informacion de la categoria.";
				echo json_encode($ResponseObj);
				exit();
			}
			// ----------------------------------------------
			$ResponseObj->RespuestaBool=true;
			$ResponseObj->Categoria=$ProductosNK_CategoriaAdminForm_CategoriaObj;

        break;

        case 'Categoria_Edit':
            $AdminFormCategoria_Edit_KeyJX=$_POST["KeyJX"];
            $AdminFormCategoria_Edit_Nombre=$_POST["CategoriaNombre"];
            $AdminFormCategoria_Edit_Descripcion=$_POST["CategoriaDescripcion"];
            $AdminFormCategoria_Edit_TablaAsignada=$_POST["CategoriaTabla"];
            $AdminFormCategoria_Edit_CategoriaObj=new ProductoCategoriaNK($AdminFormCategoria_Edit_KeyJX, $dirRaiz);
            $stat=$AdminFormCategoria_Edit_CategoriaObj->Info_Set(
                $AdminFormCategoria_Edit_Nombre,
                $AdminFormCategoria_Edit_Descripcion,
                $AdminFormCategoria_Edit_TablaAsignada
            );
            if($stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$stat[1];
            }
        break;

        case "PortadaIMG":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$CategoriaPortada_Get_KeyJX=$_POST["KeyJX"];
					$CategoriaPortada_Get_Obj=new ProductoCategoriaNK($CategoriaPortada_Get_KeyJX, $dirRaiz);
					if(empty($CategoriaPortada_Get_Obj->ProductoCategoriaID)) {
						$ResponseObj->RespuestaError="Sin ID Categoria";
						echo json_encode($ResponseObj);
						exit();
					}
					if(!empty($CategoriaPortada_Get_Obj->PortadaS)) {
						$ResponseObj->RespuestaBool=true;
						$CategoriaPortada_Get_Std=new stdClass();
						$CategoriaPortada_Get_Std->id_image="portadaImg";
						$CategoriaPortada_Get_Std->SrcS=$CategoriaPortada_Get_Obj->PortadaS;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$CategoriaPortada_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$CategoriaPortada_Up_KeyJX=$_POST["KeyJX"];
					$CategoriaPortada_Up_Imagen=$_FILES["Imagen"];
					$CategoriaPortada_Up_Caption=$_POST["Caption"];
					$CategoriaPortada_Up_Obj=new ProductoCategoriaNK($CategoriaPortada_Up_KeyJX, $dirRaiz);
					$CategoriaPortada_Up_Stat=$CategoriaPortada_Up_Obj->PortadaImg_Upload($CategoriaPortada_Up_Imagen, $CategoriaPortada_Up_Caption);
					if($CategoriaPortada_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$CategoriaPortada_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$CategoriaPortada_Up_Stat[1];
					}
				break;

				case "ImagenEliminar":
					$CategoriaPortada_Del_ID=$_POST["KeyJX"];
					$CategoriaPortada_Del_Obj=new ProductoCategoriaNK($CategoriaPortada_Del_ID, $dirRaiz);
					$CategoriaPortada_Del_Stat=$CategoriaPortada_Del_Obj->PortadaImg_Del();
					if($CategoriaPortada_Del_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$CategoriaPortada_Del_Stat[1];
					}
				break;
			}
		break;

		// TODO:
        case "PortadaFB":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$ProductoPortadaFB_Get_KeyJX=$_POST["KeyJX"];
					$ProductoPortadaFB_Get_Obj=new ProductoNK($ProductoPortadaFB_Get_KeyJX, $dirRaiz);
					if(!empty($ProductoPortadaFB_Get_Obj->PortadaFB)) {
						$ResponseObj->RespuestaBool=true;
						$ProductoPortadaFB_Get_Std=new stdClass();
						$ProductoPortadaFB_Get_Std->id_image="portadaImg";
						$ProductoPortadaFB_Get_Std->SrcS=$ProductoPortadaFB_Get_Obj->PortadaFB;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$ProductoPortadaFB_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$ProductoPortadaFB_Up_ID=$_POST["KeyJX"];
					$ProductoPortadaFB_Up_Imagen=$_FILES["Imagen"];
					$ProductoPortadaFB_Up_Caption=$_POST["Caption"];
					$ProductoPortadaFB_Up_Obj=new ProductoNK($ProductoPortadaFB_Up_ID, $dirRaiz);
					$ProductoPortadaFB_Up_Stat=$ProductoPortadaFB_Up_Obj->PortadaFB_Upload($ProductoPortadaFB_Up_Imagen, $ProductoPortadaFB_Up_Caption);
					if($ProductoPortadaFB_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$ProductoPortadaFB_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoPortadaFB_Up_Stat[1];

					}
				break;

				case "ImagenEliminar":
					$ArticuloPortadaEliminar_ID=$_POST["KeyJX"];
					$ArticuloPortadaEliminar_Obj=new ProductoNK($ArticuloPortadaEliminar_ID, $dirRaiz);
					$ArticuloPortadaEliminar_Stat=$ArticuloPortadaEliminar_Obj->PortadaFB_Del();
					if($ArticuloPortadaEliminar_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloPortadaEliminar_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;
			}
		break;

        case "CategoriaContenido":
			switch ($_POST['TextareaRichNK']) {

                case "ContenidoGet":
					$CategoriaContenidoGet_ID=$_POST["KeyJX"];
					$CategoriaContenidoGet_Obj=new ProductoCategoriaNK($CategoriaContenidoGet_ID, $dirRaiz);
					if(!empty($CategoriaContenidoGet_Obj->ProductoCategoriaID)) {
						$CategoriaContenidoGet_Obj->ImagenesGet();
						// $CategoriaContenidoGet_Obj->VideosGet();
						$ResponseObj->Contenido=$CategoriaContenidoGet_Obj->Contenido;
						$ResponseObj->Imagenes=$CategoriaContenidoGet_Obj->Imagenes;
						$ResponseObj->Videos=[];//$CategoriaContenidoGet_Obj->Videos;
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError="no se pudo obtener el contenido de la categoria del producto";
					}
				break;

                case "ContenidoSet":
					$CategoriaContenidoSet_ID=$_POST["KeyJX"];
					$CategoriaContenidoSet_Contenido=$_POST["Contenido"];
					$CategoriContenidoSet_Obj=new ProductoCategoriaNK($CategoriaContenidoSet_ID, $dirRaiz);
					$ProductoContenidoSet_Stat=$CategoriContenidoSet_Obj->Contenido_Set($CategoriaContenidoSet_Contenido);
					if($ProductoContenidoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenidoSet_Stat[1];
					}
				break;

				case 'ImagenesGet':
					$CategoriaContenido_ImagenesGet_KeyJX=$_POST["KeyJX"];
					$CategoriaContenido_ImagenesGet_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenesGet_KeyJX, $dirRaiz);
					$CategoriaContenido_ImagenesGet_Stat=$CategoriaContenido_ImagenesGet_Obj->ImagenesGet();
					if($CategoriaContenido_ImagenesGet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Imagenes=$CategoriaContenido_ImagenesGet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenesGet_Stat[1];
					}
				break;

				case "ImagenSubir":
					$CategoriaContenido_ImagenesUp_ID=$_POST["KeyJX"];
					$CategoriaContenido_ImagenesUp_Img=$_FILES["Imagen"];
					$CategoriaContenido_ImagenesUp_Caption=$_POST["Caption"];
					$CategoriaContenido_ImagenesUp_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenesUp_ID, $dirRaiz);
					$CategoriaContenido_ImagenesUp_Stat=$CategoriaContenido_ImagenesUp_Obj->ImagenSubir($CategoriaContenido_ImagenesUp_Img, $CategoriaContenido_ImagenesUp_Caption);
					if($CategoriaContenido_ImagenesUp_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Nuevo_ID=$CategoriaContenido_ImagenesUp_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenesUp_Stat[1];
					}
				break;
				
				case "ImagenEliminar":
					$CategoriaContenido_ImagenDel_ID=$_POST["KeyJX"];
					$CategoriaContenido_ImagenDel_ImgID=$_POST["ImagenID"];
					$CategoriaContenido_ImagenDel_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenDel_ID, $dirRaiz);
					$CategoriaContenido_ImagenDel_Stat=$CategoriaContenido_ImagenDel_Obj->ImagenDel($CategoriaContenido_ImagenDel_ImgID);
					if($CategoriaContenido_ImagenDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						// $ResponseObj->Imagen=$ProductoContenido_ImagenDel_Stat;
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenDel_Stat[1];
					}
				break;

				// TODO:
				case "VideoYoutubeSubir":
					$ProductoContenido_VideoSet_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoSet_Titulo=$_POST["Titulo"];
					$ProductoContenido_VideoSet_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoSet_Obj=new ProductoNK($ProductoContenido_VideoSet_KeyJX, $dirRaiz);
					$ProductoContenido_VideoSet_Stat=$ProductoContenido_VideoSet_Obj->VideoYoutubeSet($ProductoContenido_VideoSet_Titulo, $ProductoContenido_VideoSet_VideoID);
					if($ProductoContenido_VideoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->LastID=$ProductoContenido_VideoSet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoSet_Stat[1];
					}
				break;
				
				case "VideoEliminar":
					$ProductoContenido_VideoDel_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoDel_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoDel_Obj=new ProductoNK($ProductoContenido_VideoDel_KeyJX, $dirRaiz);
					$ProductoContenido_VideoDel_Stat=$ProductoContenido_VideoDel_Obj->VideoDel($ProductoContenido_VideoDel_VideoID);
					if($ProductoContenido_VideoDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoDel_Stat[1];
					}
				break;
			}
		break;
    }
}

if (isset($_POST["ProductosNK_MarcasAdmin"])) {
    switch ($_POST["ProductosNK_MarcasAdmin"]) {
        case 'Marca_Add':
			$ProductosNKAdmin_MarcaAdd_Nombre=$_POST["MarcaNombre"];
			$stat=ProductoMarcaNK::Marca_New($ProductosNKAdmin_MarcaAdd_Nombre, $dirRaiz);
			if(!$stat[0]) {
				$ResponseObj->RespuestaError=$stat[1];
				echo json_encode($ResponseObj);
				exit();
			}
			$ProductosNKAdmin_MarcaAdd_Obj=new ProductoMarcaNK($stat[1], $dirRaiz);
			$ProductosNKAdmin_MarcaAdd_LiteStat=$ProductosNKAdmin_MarcaAdd_Obj->DBLite_Crear();
			if($ProductosNKAdmin_MarcaAdd_LiteStat[0]) {
				$ResponseObj->RespuestaBool=true;
			}
		break;

		case "Marcas_Get":
			$ProductosCategoriasNKAdmin_MarcasGet_KeyJX=$_POST["KeyJX"];
			$ProductosCategoriasNKAdmin_MarcaGet_Stat=ProductosMarcas_Getters::GetAll(false);
			if($ProductosCategoriasNKAdmin_MarcaGet_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
				$ResponseObj->Marcas=$ProductosCategoriasNKAdmin_MarcaGet_Stat[1];
			} else {
				$ResponseObj->RespuestaError=$ProductosCategoriasNKAdmin_MarcaGet_Stat[1];
			}
		break;

		case 'Marca_Habilitar':
			$ProductosMarcasNKAdmin_MarcaHabilitar_KeyJX=$_POST["KeyJX"];
			$ProductosMarcasNKAdmin_MarcaHabilitar_ID=$_POST["ProductoMarcaID"];
			$ProductosMarcasNKAdmin_MarcaHabilitar_Obj=new ProductoMarcaNK($ProductosMarcasNKAdmin_MarcaHabilitar_ID, $dirRaiz);
			$ProductosMarcasNKAdmin_MarcaHabilitar_Stat=$ProductosMarcasNKAdmin_MarcaHabilitar_Obj->Habilitar();
			if($ProductosMarcasNKAdmin_MarcaHabilitar_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->RespuestaError=$ProductosMarcasNKAdmin_MarcaHabilitar_Stat[1];
			}
		break;
		
		case 'Marca_Eliminar':
			$ProductosMarcasNKAdmin_MarcaEliminar_KeyJX=$_POST["KeyJX"];
			$ProductosMarcasNKAdmin_MarcaEliminar_ID=$_POST["ProductoMarcaID"];
			$ProductosMarcasNKAdmin_MarcaEliminar_Obj=new ProductoMarcaNK($ProductosMarcasNKAdmin_MarcaEliminar_ID, $dirRaiz);
			$ProductosMarcasNKAdmin_MarcaEliminar_Stat=$ProductosMarcasNKAdmin_MarcaEliminar_Obj->ProductoMarca_Delete();
			if($ProductosMarcasNKAdmin_MarcaEliminar_Stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->RespuestaError=$ProductosMarcasNKAdmin_MarcaEliminar_Stat[1];
			}
		break;
	}
}

if (isset($_POST["ProductosNK_MarcaAdminForm"])) {
    switch ($_POST["ProductosNK_MarcaAdminForm"]) {
        case 'Info_Get':
            $ProductosNK_MarcaAdminForm_KeyJX=$_POST["KeyJX"];;
			// ----------------------------------------------
			// Obtener Marca
			$ProductosNK_MarcaAdminForm_MarcaObj=new ProductoMarcaNK($ProductosNK_MarcaAdminForm_KeyJX, $dirRaiz);
            if (empty($ProductosNK_MarcaAdminForm_MarcaObj->ProductoMarcaID)) {
				$ResponseObj->RespuestaError="No se encontro informacion de la marca producto.";
				echo json_encode($ResponseObj);
				exit();
			}
			// ----------------------------------------------
			$ResponseObj->RespuestaBool=true;
			$ResponseObj->Marca=$ProductosNK_MarcaAdminForm_MarcaObj;
        break;

        case 'Marca_Edit':
            $AdminFormMarca_Edit_KeyJX=$_POST["KeyJX"];
            $AdminFormMarca_Edit_Nombre=$_POST["MarcaNombre"];
            $AdminFormMarca_Edit_Descripcion=$_POST["MarcaDescripcion"];
            $AdminFormMarca_Edit_NickDir=$_POST["MarcaNickDir"];
            $AdminFormMarca_Edit_MarcaObj=new ProductoMarcaNK($AdminFormMarca_Edit_KeyJX, $dirRaiz);
            $stat=$AdminFormMarca_Edit_MarcaObj->Info_Set(
                $AdminFormMarca_Edit_Nombre,
                $AdminFormMarca_Edit_Descripcion,
                $AdminFormMarca_Edit_NickDir
            );
            if($stat[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$stat[1];
            }
        break;

        case "LogoIMG":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$MarcaLogo_Get_KeyJX=$_POST["KeyJX"];
					$MarcaLogo_Get_Obj=new ProductoMarcaNK($MarcaLogo_Get_KeyJX, $dirRaiz);
					if(empty($MarcaLogo_Get_Obj->ProductoMarcaID)) {
						$ResponseObj->RespuestaError="Sin ID Marca";
						echo json_encode($ResponseObj);
						exit();
					}
					if(!empty($MarcaLogo_Get_Obj->LogoS)) {
						$ResponseObj->RespuestaBool=true;
						$MarcaLogo_Get_Std=new stdClass();
						$MarcaLogo_Get_Std->id_image="LogoImg";
						$MarcaLogo_Get_Std->SrcS=$MarcaLogo_Get_Obj->LogoS;
						$MarcaLogo_Get_Std->Caption=$MarcaLogo_Get_Obj->Nombre;
						$ResponseObj->Imagenes=[$MarcaLogo_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$MarcaLogo_Up_KeyJX=$_POST["KeyJX"];
					$MarcaLogo_Up_Imagen=$_FILES["Imagen"];
					$MarcaLogo_Up_Caption=$_POST["Caption"];
					$MarcaLogo_Up_Obj=new ProductoMarcaNK($MarcaLogo_Up_KeyJX, $dirRaiz);
					$MarcaLogo_Up_Stat=$MarcaLogo_Up_Obj->LogoImg_Upload($MarcaLogo_Up_Imagen, $MarcaLogo_Up_Caption);
					if($MarcaLogo_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$MarcaLogo_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$MarcaLogo_Up_Stat[1];
					}
				break;

				case "ImagenEliminar":
					$MarcaLogo_Del_ID=$_POST["KeyJX"];
					$MarcaLogo_Del_Obj=new ProductoMarcaNK($MarcaLogo_Del_ID, $dirRaiz);
					$MarcaLogo_Del_Stat=$MarcaLogo_Del_Obj->LogoImg_Del();
					if($MarcaLogo_Del_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$MarcaLogo_Del_Stat[1];
					}
				break;
			}
		break;

		// TODO:
        case "PortadaFB":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$ProductoPortadaFB_Get_KeyJX=$_POST["KeyJX"];
					$ProductoPortadaFB_Get_Obj=new ProductoNK($ProductoPortadaFB_Get_KeyJX, $dirRaiz);
					if(!empty($ProductoPortadaFB_Get_Obj->PortadaFB)) {
						$ResponseObj->RespuestaBool=true;
						$ProductoPortadaFB_Get_Std=new stdClass();
						$ProductoPortadaFB_Get_Std->id_image="portadaImg";
						$ProductoPortadaFB_Get_Std->SrcS=$ProductoPortadaFB_Get_Obj->PortadaFB;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$ProductoPortadaFB_Get_Std];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
				break;

				case "ImagenSubir":
					$ProductoPortadaFB_Up_ID=$_POST["KeyJX"];
					$ProductoPortadaFB_Up_Imagen=$_FILES["Imagen"];
					$ProductoPortadaFB_Up_Caption=$_POST["Caption"];
					$ProductoPortadaFB_Up_Obj=new ProductoNK($ProductoPortadaFB_Up_ID, $dirRaiz);
					$ProductoPortadaFB_Up_Stat=$ProductoPortadaFB_Up_Obj->PortadaFB_Upload($ProductoPortadaFB_Up_Imagen, $ProductoPortadaFB_Up_Caption);
					if($ProductoPortadaFB_Up_Stat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$ProductoPortadaFB_Up_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoPortadaFB_Up_Stat[1];

					}
				break;

				case "ImagenEliminar":
					$ArticuloPortadaEliminar_ID=$_POST["KeyJX"];
					$ArticuloPortadaEliminar_Obj=new ProductoNK($ArticuloPortadaEliminar_ID, $dirRaiz);
					$ArticuloPortadaEliminar_Stat=$ArticuloPortadaEliminar_Obj->PortadaFB_Del();
					if($ArticuloPortadaEliminar_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloPortadaEliminar_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;
			}
		break;

		// TODO:
        case "CategoriaContenido":
			switch ($_POST['TextareaRichNK']) {

                case "ContenidoGet":
					$CategoriaContenidoGet_ID=$_POST["KeyJX"];
					$CategoriaContenidoGet_Obj=new ProductoCategoriaNK($CategoriaContenidoGet_ID, $dirRaiz);
					if(!empty($CategoriaContenidoGet_Obj->ProductoCategoriaID)) {
						$CategoriaContenidoGet_Obj->ImagenesGet();
						// $CategoriaContenidoGet_Obj->VideosGet();
						$ResponseObj->Contenido=$CategoriaContenidoGet_Obj->Contenido;
						$ResponseObj->Imagenes=$CategoriaContenidoGet_Obj->Imagenes;
						$ResponseObj->Videos=[];//$CategoriaContenidoGet_Obj->Videos;
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError="no se pudo obtener el contenido de la categoria del producto";
					}
				break;

                case "ContenidoSet":
					$CategoriaContenidoSet_ID=$_POST["KeyJX"];
					$CategoriaContenidoSet_Contenido=$_POST["Contenido"];
					$CategoriContenidoSet_Obj=new ProductoCategoriaNK($CategoriaContenidoSet_ID, $dirRaiz);
					$ProductoContenidoSet_Stat=$CategoriContenidoSet_Obj->Contenido_Set($CategoriaContenidoSet_Contenido);
					if($ProductoContenidoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenidoSet_Stat[1];
					}
				break;

				case 'ImagenesGet':
					$CategoriaContenido_ImagenesGet_KeyJX=$_POST["KeyJX"];
					$CategoriaContenido_ImagenesGet_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenesGet_KeyJX, $dirRaiz);
					$CategoriaContenido_ImagenesGet_Stat=$CategoriaContenido_ImagenesGet_Obj->ImagenesGet();
					if($CategoriaContenido_ImagenesGet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Imagenes=$CategoriaContenido_ImagenesGet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenesGet_Stat[1];
					}
				break;

				case "ImagenSubir":
					$CategoriaContenido_ImagenesUp_ID=$_POST["KeyJX"];
					$CategoriaContenido_ImagenesUp_Img=$_FILES["Imagen"];
					$CategoriaContenido_ImagenesUp_Caption=$_POST["Caption"];
					$CategoriaContenido_ImagenesUp_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenesUp_ID, $dirRaiz);
					$CategoriaContenido_ImagenesUp_Stat=$CategoriaContenido_ImagenesUp_Obj->ImagenSubir($CategoriaContenido_ImagenesUp_Img, $CategoriaContenido_ImagenesUp_Caption);
					if($CategoriaContenido_ImagenesUp_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Nuevo_ID=$CategoriaContenido_ImagenesUp_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenesUp_Stat[1];
					}
				break;
				
				case "ImagenEliminar":
					$CategoriaContenido_ImagenDel_ID=$_POST["KeyJX"];
					$CategoriaContenido_ImagenDel_ImgID=$_POST["ImagenID"];
					$CategoriaContenido_ImagenDel_Obj=new ProductoCategoriaNK($CategoriaContenido_ImagenDel_ID, $dirRaiz);
					$CategoriaContenido_ImagenDel_Stat=$CategoriaContenido_ImagenDel_Obj->ImagenDel($CategoriaContenido_ImagenDel_ImgID);
					if($CategoriaContenido_ImagenDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						// $ResponseObj->Imagen=$ProductoContenido_ImagenDel_Stat;
					} else {
						$ResponseObj->RespuestaError=$CategoriaContenido_ImagenDel_Stat[1];
					}
				break;

				case "VideoYoutubeSubir":
					$ProductoContenido_VideoSet_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoSet_Titulo=$_POST["Titulo"];
					$ProductoContenido_VideoSet_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoSet_Obj=new ProductoNK($ProductoContenido_VideoSet_KeyJX, $dirRaiz);
					$ProductoContenido_VideoSet_Stat=$ProductoContenido_VideoSet_Obj->VideoYoutubeSet($ProductoContenido_VideoSet_Titulo, $ProductoContenido_VideoSet_VideoID);
					if($ProductoContenido_VideoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->LastID=$ProductoContenido_VideoSet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoSet_Stat[1];
					}
				break;
				
				case "VideoEliminar":
					$ProductoContenido_VideoDel_KeyJX=$_POST["KeyJX"];
					$ProductoContenido_VideoDel_VideoID=$_POST["VideoID"];
					$ProductoContenido_VideoDel_Obj=new ProductoNK($ProductoContenido_VideoDel_KeyJX, $dirRaiz);
					$ProductoContenido_VideoDel_Stat=$ProductoContenido_VideoDel_Obj->VideoDel($ProductoContenido_VideoDel_VideoID);
					if($ProductoContenido_VideoDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ProductoContenido_VideoDel_Stat[1];
					}
				break;
			}
		break;
    }
}

$ResponseObj->DIR="Action Panel Productos";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;

echo json_encode($ResponseObj);
exit();


