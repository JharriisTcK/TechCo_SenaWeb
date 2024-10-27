<?php

class neoKiri {

    public static $MesesNombre=["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    public static $DiasNombre=["Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado", "Domingo"];

    public static $tablaLite_Imagenes="CREATE TABLE IF NOT EXISTS Imagenes (
        ImagenID integer not null primary key autoincrement unique,
        Categoria text not null,
        ImagenH text not null,
        ImagenM text,
        ImagenS text,
        ImagenT text,
        ImagenC text,
        ImagenO text,
        Titulo text,
        Descripcion text,
        Formato text,
        Tamanio integer,
        Reaccion_megusta integer default 0,
        Reaccion_nomegusta integer default 0,
        Denuncias integer default 0,
        Habilitado integer default 1,
        Fecha_Registro datetime not null
    )";

public static $tablaLite_Archivos="CREATE TABLE IF NOT EXISTS Archivos (
        ArchivoID integer not null primary key autoincrement unique,
        Tipo text,
        Titulo text,
        Descripcion text,
        Categoria text,
        Tamanio integer,
        Visitas default 0,
        Descargas integer default 0,
        Reaccion_MeGusta integer default 0,
        Reaccion_NoMeGusta integer default 0,
        Habilitado integer default 1,
        Fecha_Registro datetime not null
    )";

    public static $tablaLite_Videos="CREATE TABLE IF NOT EXISTS Videos (
        VideoID integer not null primary key autoincrement unique,
        VideoTipo text not null default 'Youtube',
        Src_ID text not null unique,
        Src_Thumb text,
        Titulo text not null,
        Descripcion text,
        Categoria text,
        Duracion text,
        Reaccion_MeGusta integer default 0,
        Reaccion_NoMeGusta integer default 0,
        Habilitado integer default 1,
        Fecha_Registro datetime not null
    )";

public static $tablaLite_Comentarios="CREATE TABLE IF NOT EXISTS Comentarios (
        ComentarioID integer not null primary key autoincrement unique,
        Comentario text not null,
        Titulo text,
        Categoria text,
        UsuarioID text,
        Email text,
        Reaccion_MeGusta integer default 0,
        Reaccion_NoMeGusta integer default 0,
        Denuncias integer default 0,
        Habilitado integer default 1,
        Fecha_Registro datetime not null
    )";

    public static function encodeTitulo2Link($strIn) {
        $texto=trim($strIn);
        if(empty($texto)) {
            return "";
        }
        
        $charChangea=["â","ä","à","å","á","ã"];
        $charChangeA=["Ä","Å","Á","Â","À","Ã"];
        $charChangee=["é","ê","ë","è"];
        $charChangeE=["É","Ê","Ë","È"];
        $charChangei=["ï","î","ì","í"];
        $charChangeI=["Í","Î","Ï","Ì"];
        $charChangeo=["ô","ö","ò","ó","õ"];
        $charChangeO=["Ö","Ó","Ô","Ò","Õ"];
        $charChangeu=["ü","û","ù","ú","µ"];
        $charChangeU=["Ü","Ú","Û","Ù"];
        $charChangen=["ñ"];
        $charChangeN=["Ñ"];
    
        $charToChange=array("a","A","e","E","i","I","o","O","u","U","n","N");
        
        foreach($charToChange as $letra) {
            $letraChange=null;
            $letraChangeVariations=null;
            switch($letra) {
                case "a":
                    $letraChange="a";
                    $letraChangeVariations=$charChangea;
                    break;
                    case "A":
                        $a=0;
                        $letraChange="A";
                        $letraChangeVariations=$charChangeA;
                        break;
                    case "e":
                        $letraChange="e";
                        $letraChangeVariations=$charChangee;
                        break;
                    case "E":
                        $letraChange="E";
                        $letraChangeVariations=$charChangeE;
                        break;
                    case "i":
                        $letraChange="i";
                        $letraChangeVariations=$charChangei;
                        break;
                    case "I":
                        $letraChange="I";
                        $letraChangeVariations=$charChangeI;
                        break;
                    case "o":
                        $letraChange="o";
                        $letraChangeVariations=$charChangeo;
                        break;
                    case "O":
                        $letraChange="O";
                        $letraChangeVariations=$charChangeO;
                        break;
                    case "u":
                        $letraChange="u";
                        $letraChangeVariations=$charChangeu;
                        break;
                    case "U":
                        $letraChange="U";
                        $letraChangeVariations=$charChangeU;
                        break;
                    case "n":
                        $letraChange="n";
                        $letraChangeVariations=$charChangen;
                        break;
                    case "N":
                        $letraChange="N";
                        $letraChangeVariations=$charChangeN;
                        break;
            }
            foreach($letraChangeVariations as $charVariation)
            {
                $texto=str_replace($charVariation, $letraChange, $texto);
            }
            
        }
        $texto=preg_replace("/[\W]+/", "-", $texto);
		$texto=preg_replace("/\-+/", "-", $texto);
        return $texto;
    }

    public static function Imagen_Convert($dirFilename, $dirFilenameOutput, $mxWidth, $mxHeight, $type="webp") {
		if(!is_file($dirFilename)) {
			return [false, "no existe el archivo: ".$dirFilename];
		}
		$fileInfo=getimagesize($dirFilename);
		$w=(integer)$fileInfo[0];
		$h=(integer)$fileInfo[1];
		$wN=$w;
		$hN=$h;
		$orientation=null;
		if($w>=$h) {
			$orientation="horizontal";
		} else if($h>$w) {
			$orientation="vertical";
		} else {
            return [false, "Error al detectar orientacion"];
        }

		switch($orientation) {
			case "horizontal":
				if($w>=$mxWidth){
					$wN=$mxWidth;
					$hN*=$mxWidth/$w;
				}
				break;

			case "vertical":
				if($h>$mxHeight){
					$wN*=$mxHeight/$h;
					$hN=$mxHeight;
				}
				break;

			default:
				return [false, "problema con la deteccion orientacion de imagen"];
				break;
		}

        $imgExt=neoKiri::mime2ext(mime_content_type($dirFilename));

        $imgOrig=null;
        switch($imgExt) {
            case "png":
                $imgOrig=imagecreatefrompng($dirFilename);
            break;
            
            case "jpg":
            case "jpeg":
                $imgOrig=imagecreatefromjpeg($dirFilename);
            break;

            case "gif":
                $imgOrig=imagecreatefromgif($dirFilename);
            break;

        }

		$img=imagecreatetruecolor($wN, $hN);
		$colorBlack=imagecolorallocate($img,0,0,0);
		imagecolortransparent($img,$colorBlack);
		imagealphablending($img, false);
		imagesavealpha($img, true);

		if(!imagecopyresampled($img, $imgOrig,0,0,0,0, $wN, $hN, $w, $h)) {
			return [false, "no hubo copia"];
		}

		switch($type) {
            default:
			case "webp":
				imagewebp($img, $dirFilenameOutput);
				break;

			case "jpeg":
			case "jpg":
				imagejpeg($img, $dirFilenameOutput);
				break;

			case "png":
				imagepng($img, $dirFilenameOutput);
				break;
		}
		imagedestroy($img);
		imagedestroy($imgOrig);
		return [true, $dirFilenameOutput, $type];
	}

    public static function Ocultar_Palabras_Ofensivas($textoIn){
        $palabrasroseras=[
        "/malparid[oa]s?/i",
        "/(?:gran\s*)?puta\s+madre/i",
        "/put[ao]/i",
        "/chimb(?:ad)?[ao]/i"
        ];
        $textoIn=preg_replace($palabrasroseras, "******", $textoIn);
        return $textoIn;
    }

    public static function FechasDiferencia($FechaInicioIn, $FechaFinIn) {
        $now=new DateTime("now", new DateTimeZone("America/Bogota"));
        $inicio=new DateTime($FechaInicioIn, new DateTimeZone("America/Bogota"));
        $fin=new DateTime($FechaFinIn, new DateTimeZone("America/Bogota"));
        //---------Estado desde ahora
        $StatFromNow="";
        if($now<$inicio) {
            $StatFromNow="Pendiente";
        } else {
            if($now>=$inicio && $now<$fin) {
                $StatFromNow="Proceso";
            } else {
                $StatFromNow="Finalizado";
            }
        }
        //---------Tiempo Restante
        $TiempoEspera="";
        $TiempoTranscurrido="Han transcurrido ";
        $TiempoRestante="Faltan ";
        $TiempoFinalizado="";
        $diffDate="";
        $diffDateTranscurrido="";
        $diffDateTranscurrido=$now->diff($fin);
        switch ($StatFromNow) {
            case 'Pendiente':
                $diffDate=$now->diff($inicio);
                $TiempoEspera="Inicia en ";
            break;
                
            case 'Proceso':
                $diffDate=$inicio->diff($now);
            break;
            
            case 'Finalizado':
                $diffDate=$fin->diff($now);
                $TiempoFinalizado="Finalizo hace ";
            break;
        }
        if($diffDate->y) {
            $TiempoEspera.=$diffDate->y." años ";
            $TiempoTranscurrido.=$diffDateTranscurrido->y." años ";
            $TiempoRestante.=$diffDate->y." años ";
            $TiempoFinalizado.=$diffDate->y." años ";
        }
        if($diffDate->m) {
            $TiempoEspera.=$diffDate->m." meses ";
            $TiempoTranscurrido.=$diffDateTranscurrido->m." meses ";
            $TiempoRestante.=$diffDate->m." meses ";
            $TiempoFinalizado.=$diffDate->m." meses ";
        }
        if($diffDate->d) {
            $TiempoEspera.=$diffDate->d." dias ";
            $TiempoTranscurrido.=$diffDateTranscurrido->d." dias ";
            $TiempoRestante.=$diffDate->d." dias ";
            $TiempoFinalizado.=$diffDate->d." dias ";
        }
        if($diffDate->h) {
            $TiempoEspera.=$diffDate->h." horas ";
            $TiempoTranscurrido.=$diffDateTranscurrido->h." horas ";
            $TiempoRestante.=$diffDate->h." horas ";
            $TiempoFinalizado.=$diffDate->h." horas ";
        }
        if($diffDate->i) {
            $TiempoEspera.=$diffDate->i." minutos ";
            $TiempoTranscurrido.=$diffDateTranscurrido->i." minutos ";
            $TiempoRestante.=$diffDate->i." minutos ";
            $TiempoFinalizado.=$diffDate->i." minutos ";
        }

        $FechaInicioText=neoKiri::$DiasNombre[$inicio->format("N")].", ".$inicio->format("d")." de ".neoKiri::$MesesNombre[$inicio->format("n")]." del ".$inicio->format("Y");
        $FechaFinText=neoKiri::$DiasNombre[$fin->format("N")].", ".$fin->format("d")." de ".neoKiri::$MesesNombre[$fin->format("n")]." del ".$fin->format("Y");

        $FechaReturnObjStd=new stdClass();
        $FechaReturnObjStd->StatFromNow=$StatFromNow;
        $FechaReturnObjStd->FechaInicioText=$FechaInicioText;
        $FechaReturnObjStd->FechaFinalText=$FechaFinText;
        $FechaReturnObjStd->TiempoEspera=$TiempoEspera;
        $FechaReturnObjStd->TiempoTranscurrido=$TiempoTranscurrido;
        $FechaReturnObjStd->TiempoRestante=$TiempoRestante;
        $FechaReturnObjStd->TiempoFinalizado=$TiempoFinalizado;
        $FechaReturnObjStd->TiempoRestanteObj=$diffDate;

        switch ($StatFromNow) {
            case 'Pendiente':
                unset($FechaReturnObjStd->TiempoTranscurrido);
                unset($FechaReturnObjStd->TiempoRestante);
                unset($FechaReturnObjStd->TiempoFinalizado);
                break;

            case 'Proceso':
                unset($FechaReturnObjStd->TiempoEspera);
                unset($FechaReturnObjStd->TiempoFinalizado);
                break;

            case 'Finalizado':
                unset($FechaReturnObjStd->TiempoEspera);
                unset($FechaReturnObjStd->TiempoTranscurrido);
                unset($FechaReturnObjStd->TiempoRestante);
                break;
        }

        return $FechaReturnObjStd;
    }

    public static function FechaToText($FechaIn) {
        $now=new DateTime("now", new DateTimeZone("America/Bogota"));
        $inicio=new DateTime($FechaIn, new DateTimeZone("America/Bogota"));
        //---------Estado desde ahora
        $StatFromNow="";
        if($now<$inicio) {
            $StatFromNow="Pendiente";
        } else {
            $StatFromNow="Finalizado";
        }
        //---------Tiempo Restante
        $TiempoEspera="";
        $TiempoFinalizado="";
        $diffDate="";
        switch ($StatFromNow) {
            case 'Pendiente':
                $diffDate=$now->diff($inicio);
                $TiempoEspera="Inicia en ";
            break;
            
            case 'Finalizado':
                $diffDate=$inicio->diff($now);
                $TiempoFinalizado="Finalizo hace ";
            break;
        }
        //Configurar el Texto de diferencia en tiempo
        if($diffDate->y) {
            $TiempoEspera.=$diffDate->y." años ";
            $TiempoFinalizado.=$diffDate->y." años ";
        }
        if($diffDate->m) {
            $TiempoEspera.=$diffDate->m." meses ";
            $TiempoFinalizado.=$diffDate->m." meses ";
        }
        if($diffDate->d) {
            $TiempoEspera.=$diffDate->d." dias ";
            $TiempoFinalizado.=$diffDate->d." dias ";
        }
        if($diffDate->h) {
            $TiempoEspera.=$diffDate->h." horas ";
            $TiempoFinalizado.=$diffDate->h." horas ";
        }
        if($diffDate->i) {
            $TiempoEspera.=$diffDate->i." minutos ";
            $TiempoFinalizado.=$diffDate->i." minutos ";
        }

        //Fecha de Entrada en Texto español, -- Miercoles, 20 de Junio de 2022 --  
        $FechaTextoText=neoKiri::$DiasNombre[$inicio->format("N")].", ".$inicio->format("d")." de ".neoKiri::$MesesNombre[$inicio->format("n")]." del ".$inicio->format("Y");

        // Objeto para Retornar
        $FechaReturnObjStd=new stdClass();
        $FechaReturnObjStd->StatFromNow=$StatFromNow;//Pendiente o Finalizado
        $FechaReturnObjStd->FechaTexto=$FechaTextoText;//Miercoles, 7 de Junio de 2022
        $FechaReturnObjStd->TiempoEspera=$TiempoEspera;//Inicia en 1 año 2 dias 23 minutos
        $FechaReturnObjStd->TiempoFinalizado=$TiempoFinalizado;//Finalizo hace 3 dias 4 minutos
        $FechaReturnObjStd->TiempoRestanteObj=$diffDate;//Objeto Diff

        switch ($StatFromNow) {
            case 'Pendiente':
                unset($FechaReturnObjStd->TiempoFinalizado);
            break;

            case 'Finalizado':
                unset($FechaReturnObjStd->TiempoEspera);
            break;
        }

        return $FechaReturnObjStd;
    }

    public static function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'font/otf'                                                                  => 'otf',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'font/ttf'                                                                  => 'ttf',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'image/webp'                                                                => 'webp',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'font/woff'                                                                 => 'woff',
            'font/woff2'                                                                => 'woff2',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];
    
        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }

    public static function delDirectorio($dirFolder) {
		$stat=array();
		// array_push($stat,"Eliminar: ".$dirFolder);
		if(!is_dir($dirFolder)) {
			return [true, "La carpeta no existe"];
		}
		$dirList=scandir($dirFolder);
		array_push($stat, $dirList);
		if(count($dirList)>2) {
			for($i=2;$i<count($dirList);$i++) {
				if(is_dir($dirFolder.$dirList[$i])) {
					$statBucle=self::delDirectorio($dirFolder.$dirList[$i]."/");
					if($statBucle[0]) {
						array_push($stat, $statBucle[1]);
					}
				} else if(is_readable($dirFolder.$dirList[$i])) {
					if(unlink($dirFolder.$dirList[$i])) {
					} else {
						echo "No se pudo eliminar el archivo";
					};
				}
			}
		}


		rmdir($dirFolder);

		//rmdir(); eliminar directorio
		//unlink(); eliminar archivo
		//is_readable(); es archivo
		//is_dir(); es directorio
		//filesize(); tamaño imagen
		return [true, $stat];
	} //end - delDirectorio();

    public static function GenerarRandomID(int $length, string $prefix, string $characters="") {
        $characters=trim($characters);
        if(empty($characters)) {
            $characters = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz0123456789';
        }
        if( strlen($prefix) > $length ) {
            $prefix="";
        }
        $id = $prefix;
        for ($i = 0; $i < $length - strlen($prefix); $i++) {
            $id .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $id;
    }


}

?>