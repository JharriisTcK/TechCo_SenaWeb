<?php

require("../PHP/neoKiriPHP_class.php");
require("../PHP/PublicacionNK_class.php");
require("../PHP/UsuariosNK.php");
require("../PHP/ServiciosNK_class.php");

session_start();

if(isset($_SESSION["UsuarioNK"])) {
    $UsuarioNK_ID=$_SESSION["UsuarioNK"];
} else {
    $UsuarioNK_ID="";
	echo "COMO QUE NO SE HA INICIADO SESION CORRIGE ESTA MONDA";
	exit();
}

$UsuarioNK_KeyJX=$_SESSION["UsuarioKeyJX"];
$UsuarioNK_Nombres=$_SESSION["UsuarioNombres"];
$UsuarioNK_Expiracion=$_SESSION["UsuarioFechaExpiracion"];


$dirRaiz="../";

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError=false;


if (isset($_POST['PublicacionNK_EditorBoard'])) {
    switch ($_POST['PublicacionNK_EditorBoard']) {
        case 'PublicacionNew':
            $PublicacionNK_EditorBoard_New_Titulo=$_POST["PublicacionTitulo"];
            $PublicacionNK_EditorBoard_New_Fecha="now";
            $PublicacionNK_EditorBoard_New_FechaObj=new DateTime($PublicacionNK_EditorBoard_New_Fecha, new DateTimeZone("America/Bogota"));
            $stat=PublicacionNK::Publicacion_New($PublicacionNK_EditorBoard_New_Titulo, $PublicacionNK_EditorBoard_New_FechaObj, $UsuarioNK_ID, $dirRaiz);
            if($stat[0]) {
				$ResponseObj->RespuestaBool=true;
				$ResponseObj->ID=$stat[1];
			} else {
				$ResponseObj->RespuestaError=$stat[1];
            }
			echo json_encode($ResponseObj);
			exit();
		break;
            
        case 'PublicacionesGet':
            $PublicacionNK_EditorBoard_GetAll_Stat=PublicacionNK_Getters::FromColaborador($UsuarioNK_ID, false);
            if(!$PublicacionNK_EditorBoard_GetAll_Stat[0]) {
				$ResponseObj->RespuestaError=$PublicacionNK_EditorBoard_GetAll_Stat[1];
				echo json_encode($ResponseObj);
				exit();
			}
			$ResponseObj->ResultadosIDS=$PublicacionNK_EditorBoard_GetAll_Stat[2];
			$stat=PublicacionNK_Getters::GetAll_FromIDList($ResponseObj->ResultadosIDS);
            if($stat[0]) {
				$ResponseObj->RespuestaBool=true;
				$ResponseObj->Resultados=$stat[1];
			} else {
				$ResponseObj->RespuestaError=$PublicacionNK_EditorBoard_GetAll_Stat[1];
			}
			
		break;

        case "PublicacionPublicar":
            $PublicacionNK_Form_ID=$_POST["PublicacionID"];
            $PublicacionObj=new PublicacionNK($PublicacionNK_Form_ID, $dirRaiz);
            if (empty($PublicacionObj->PublicacionID)) {
				echo json_encode($ResponseObj);
				exit();
			}
			if ($PublicacionObj->Publicado) {
				$ResponseObj->RespuestaError="Ya se encuentra publicado";
				echo json_encode($ResponseObj);
				exit();
			}
			$buffPermitir=false;
			$buffPublicar=$PublicacionObj->Publicado;
			if($PublicacionObj->Permitido) {
				$buffPermitir=false;
			} else {
				$buffPermitir=true;
			}
			$stat=$PublicacionObj->PublicarSet($buffPermitir, $buffPublicar);
			if($stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->RespuestaError=$stat[1];
			}
		break;

        case "PublicacionDel":
            $PublicacionNK_EditorBoard_Del_ID=$_POST["PublicacionID"];
            $PublicacionObj=new PublicacionNK($PublicacionNK_EditorBoard_Del_ID, $dirRaiz);
			$stat=$PublicacionObj->Publicacion_Delete();
            if($stat[0]) {
				$ResponseObj->RespuestaBool=true;
			} else {
				$ResponseObj->folder=$PublicacionObj->folder_publicacion;
				$ResponseObj->RespuestaBool=false;
				$ResponseObj->RespuestaError=$stat[1];
			}
		break;
    }

}


if (isset($_POST["PublicacionNK_Form"])) {
    switch ($_POST["PublicacionNK_Form"]) {
        case "PublicacionNK_Get":
            $PublicacionNK_Form_ID=$_POST["KeyJX"];
            $PublicacionObj=new PublicacionNK($PublicacionNK_Form_ID, $dirRaiz);
            if ($PublicacionObj->PublicacionID) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Publicacion=$PublicacionObj;
                $ResponseObj->Secciones=PublicacionNK_Conexion::$PublicacionSecciones;
            }
			$ResponseObj->Servicios=[];
			$PublicacionNKForm_Get_ServiciosStat=ServiciosNK_Getters::GetAllPublicos("SelectCheckNK");
			if($PublicacionNKForm_Get_ServiciosStat[0]) {
				$PublicacionObj->ServiciosID_Get();
				$ResponseObj->Servicios=$PublicacionNKForm_Get_ServiciosStat[1];
			}
		break;
            
		case "PublicacionNK_Edit":
			$PublicacionNK_FormEdit_ID=$_POST["KeyJX"];
			$PublicacionNK_FormEdit_Descripcion=$_POST["PublicacionDescripcion"];
			$PublicacionNK_FormEdit_Tags=$_POST["PublicacionTags"];
			$PublicacionObj=new PublicacionNK($PublicacionNK_FormEdit_ID, $dirRaiz);
			$Stat=$PublicacionObj->Info_Set($PublicacionNK_FormEdit_Descripcion, "", $PublicacionNK_FormEdit_Tags);
			if ($Stat[0]) {
				$ResponseObj->RespuestaBool=true;
			}
        break;

		case 'Servicios':
			switch ($_POST['SelectCheckNK']) {
				case 'InfoGuardar':
					$PublicacionNK_SelectCheckNK_Guardar_PublicacionID=$_POST["KeyJX"];
					$PublicacionNK_SelectCheckNK_Guardar_Seleccionados=$_POST["Seleccionados"];
					$PublicacionNK_SelectCheckNK_Guardar_SeleccionadosArr=explode("," , $PublicacionNK_SelectCheckNK_Guardar_Seleccionados);
					$PublicacionNK_SelectCheckNK_Guardar_PublicacionObj=new PublicacionNK($PublicacionNK_SelectCheckNK_Guardar_PublicacionID, $dirRaiz);
					$PublicacionNK_SelectCheckNK_Guardar_PublicacionObj->Servicios_Del();
					$PublicacionNK_SelectCheckNK_Guardar_PublicacionStat=$PublicacionNK_SelectCheckNK_Guardar_PublicacionObj->Servicios_Set($PublicacionNK_SelectCheckNK_Guardar_SeleccionadosArr);
					if($PublicacionNK_SelectCheckNK_Guardar_PublicacionStat[0]) {
						// $PublicacionNK_SelectCheckNK_Guardar_PublicacionObj->Servicios_Get();
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$PublicacionNK_SelectCheckNK_Guardar_PublicacionStat[1];
						$ResponseObj->PublicacionObj=$PublicacionNK_SelectCheckNK_Guardar_PublicacionObj;
					}
				break;
			}
		break;

        case "PortadaIMG":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$PortadaArticuloGetFormID=$_POST["KeyJX"];
					$PortadaArticuloGetFormObj=new PublicacionNK($PortadaArticuloGetFormID, $dirRaiz);
					if(!empty($PortadaArticuloGetFormObj->PortadaS)) {
						$ResponseObj->RespuestaBool=true;
						$PortadaArticuloGetObj=new stdClass();
						$PortadaArticuloGetObj->id_image="portadaImg";
						$PortadaArticuloGetObj->SrcS=$PortadaArticuloGetFormObj->PortadaS;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$PortadaArticuloGetObj];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
					echo json_encode($ResponseObj);
					exit();
				break;

				case "ImagenSubir":
					$editPortadaArticuloID=$_POST["KeyJX"];
					$editPortadaArticuloImage=$_FILES["Imagen"];
					$editPortadaArticuloImageCaption=$_POST["Caption"];
					$editPortadaArticuloObj=new PublicacionNK($editPortadaArticuloID, $dirRaiz);
					$editPortadaArticuloStat=$editPortadaArticuloObj->PortadaImg_Upload($editPortadaArticuloImage, $editPortadaArticuloImageCaption);
					if($editPortadaArticuloStat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$editPortadaArticuloStat[1];
					} else {
						$ResponseObj->RespuestaError=$editPortadaArticuloStat[1];

					}
					echo json_encode($ResponseObj);
					exit();
				break;

				case "ImagenEliminar":
					$ArticuloPortadaEliminar_ID=$_POST["KeyJX"];
					$ArticuloPortadaEliminar_Obj=new PublicacionNK($ArticuloPortadaEliminar_ID, $dirRaiz);
					$ArticuloPortadaEliminar_Stat=$ArticuloPortadaEliminar_Obj->PortadaImg_Del();
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

        case "PortadaFB":
			switch ($_POST['FileImageNK']) {
				case 'ImagenesGet':
					$PortadaArticuloGetFormID=$_POST["KeyJX"];
					$PortadaArticuloGetFormObj=new PublicacionNK($PortadaArticuloGetFormID, $dirRaiz);
					if(!empty($PortadaArticuloGetFormObj->PortadaFB)) {
						$ResponseObj->RespuestaBool=true;
						$PortadaArticuloGetObj=new stdClass();
						$PortadaArticuloGetObj->id_image="portadaImg";
						$PortadaArticuloGetObj->SrcS=$PortadaArticuloGetFormObj->PortadaFB;
						// $PortadaArticuloGetObj->Caption=$PortadaArticuloGetFormObj->PortadaC;
						$ResponseObj->Imagenes=[$PortadaArticuloGetObj];
					} else {
                        $ResponseObj->RespuestaError="Sin portada";
                    }
					echo json_encode($ResponseObj);
					exit();
				break;

				case "ImagenSubir":
					$editPortadaArticuloID=$_POST["KeyJX"];
					$editPortadaArticuloImage=$_FILES["Imagen"];
					$editPortadaArticuloImageCaption=$_POST["Caption"];
					$editPortadaArticuloObj=new PublicacionNK($editPortadaArticuloID, $dirRaiz);
					$editPortadaArticuloStat=$editPortadaArticuloObj->PortadaFB_Upload($editPortadaArticuloImage, $editPortadaArticuloImageCaption);
					if($editPortadaArticuloStat[0]){
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->id_imagen=$editPortadaArticuloStat[1];
					} else {
						$ResponseObj->RespuestaError=$editPortadaArticuloStat[1];

					}
					echo json_encode($ResponseObj);
					exit();
				break;

				case "ImagenEliminar":
					$ArticuloPortadaEliminar_ID=$_POST["KeyJX"];
					$ArticuloPortadaEliminar_Obj=new PublicacionNK($ArticuloPortadaEliminar_ID, $dirRaiz);
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

        case "PublicacionContenido":
			switch ($_POST['TextareaRichNK']) {
				case 'ImagenesGet':
					$ArticuloContenido_ImagenesGet_KeyJX=$_POST["KeyJX"];
					$ArticuloContenido_ImagenesGet_Obj=new PublicacionNK($ArticuloContenido_ImagenesGet_KeyJX, $dirRaiz);
					$ArticuloContenido_ImagenesGet_Stat=$ArticuloContenido_ImagenesGet_Obj->ImagenesGet();
					if($ArticuloContenido_ImagenesGet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Imagenes=$ArticuloContenido_ImagenesGet_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenido_ImagenesGet_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;

				case "ImagenSubir":
					$ArticuloContenido_ImagenesUp_ID=$_POST["KeyJX"];
					$ArticuloContenido_ImagenesUp_Img=$_FILES["Imagen"];
					$ArticuloContenido_ImagenesUp_Caption=$_POST["Caption"];
					$ArticuloContenido_ImagenesUp_Obj=new PublicacionNK($ArticuloContenido_ImagenesUp_ID, $dirRaiz);
					$ArticuloContenido_ImagenesUp_Stat=$ArticuloContenido_ImagenesUp_Obj->ImagenUpload($ArticuloContenido_ImagenesUp_Img, $ArticuloContenido_ImagenesUp_Caption);
					if($ArticuloContenido_ImagenesUp_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->ArticuloImagenUp_LastID=$ArticuloContenido_ImagenesUp_Stat[1];
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenido_ImagenesUp_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();

				break;
				
				case "ImagenEliminar":
					$ArticuloContenido_ImagenDel_ID=$_POST["KeyJX"];
					$ArticuloContenido_ImagenDel_ImgID=$_POST["ImagenID"];
					$ArticuloContenido_ImagenDel_Obj=new PublicacionNK($ArticuloContenido_ImagenDel_ID, $dirRaiz);
					$ArticuloContenido_ImagenDel_Stat=$ArticuloContenido_ImagenDel_Obj->ImagenDel($ArticuloContenido_ImagenDel_ImgID);
					if($ArticuloContenido_ImagenDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenido_ImagenDel_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();

				break;

				case "ContenidoGet":
					$ArticuloContenidoSet_ID=$_POST["KeyJX"];
					$ArticuloContenidoSet_Obj=new PublicacionNK($ArticuloContenidoSet_ID, $dirRaiz);
					// $ArticuloContenidoSet_Stat=$ArticuloContenidoSet_Obj->ContenidoSet($ArticuloContenidoSet_Contenido);
					if(empty(!$ArticuloContenidoSet_Obj->PublicacionID)) {
						$ArticuloContenidoSet_Obj->ImagenesGet();
						$ArticuloContenidoSet_Obj->videosGet();
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->Contenido=$ArticuloContenidoSet_Obj->Contenido;
						$ResponseObj->Imagenes=$ArticuloContenidoSet_Obj->Imagenes;
						$ResponseObj->Videos=$ArticuloContenidoSet_Obj->Videos;
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenidoSet_Stat[1];
					}
					// echo json_encode($ResponseObj);
					// exit();
				break;

				case "ContenidoSet":
					$ArticuloContenidoSet_ID=$_POST["KeyJX"];
					$ArticuloContenidoSet_Contenido=$_POST["Contenido"];
					$ArticuloContenidoSet_Obj=new PublicacionNK($ArticuloContenidoSet_ID, $dirRaiz);
					$ArticuloContenidoSet_Stat=$ArticuloContenidoSet_Obj->ContenidoSet($ArticuloContenidoSet_Contenido);
					if($ArticuloContenidoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenidoSet_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;

				case "VideoYoutubeSubir":
					$ArticuloContenido_VideoSet_KeyJX=$_POST["KeyJX"];
					$ArticuloContenido_VideoSet_Titulo=$_POST["Titulo"];
					$ArticuloContenido_VideoSet_VideoID=$_POST["VideoID"];
					$ArticuloContenido_VideoSet_Obj=new PublicacionNK($ArticuloContenido_VideoSet_KeyJX, $dirRaiz);
					$ArticuloContenido_VideoSet_Stat=$ArticuloContenido_VideoSet_Obj->VideoYoutubeSet($ArticuloContenido_VideoSet_Titulo, $ArticuloContenido_VideoSet_VideoID);
					if($ArticuloContenido_VideoSet_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
						$ResponseObj->LastID=$ArticuloContenido_VideoSet_Stat[1];
						
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenido_VideoSet_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();
				break;
				
				case "VideoEliminar":
					$ArticuloContenido_VideoDel_KeyJX=$_POST["KeyJX"];
					$ArticuloContenido_VideoDel_VideoID=$_POST["VideoID"];
					$ArticuloContenido_VideoDel_Obj=new PublicacionNK($ArticuloContenido_VideoDel_KeyJX, $dirRaiz);
					$ArticuloContenido_VideoDel_Stat=$ArticuloContenido_VideoDel_Obj->VideoDel($ArticuloContenido_VideoDel_VideoID);
					if($ArticuloContenido_VideoDel_Stat[0]) {
						$ResponseObj->RespuestaBool=true;
					} else {
						$ResponseObj->RespuestaError=$ArticuloContenido_VideoDel_Stat[1];
					}
					echo json_encode($ResponseObj);
					exit();

				break;
			}
			
		break;
    }
}



$ResponseObj->DIR="Action Publicaciones - ColaboradorNK";
$ResponseObj->GET=$_GET;
$ResponseObj->POST=$_POST;
$ResponseObj->FILES=$_FILES;
$ResponseObj->SESSION=$_SESSION;

echo json_encode($ResponseObj);
exit();



?>