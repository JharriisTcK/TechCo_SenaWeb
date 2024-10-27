<?php

class UsuarioNK_Conexion {
    public static $tabla_usuarios="UsuariosNK";
	public static $tabla_usuarioslogin="UsuariosNK_Login";
	public static $tabla_usuariosredes="UsuariosNK_Redes";
    public static $tabla_usuariossedes="UsuariosNK_Sede";
    public static $tabla_usuariossedesalbum="UsuariosNK_SedeAlbum";
	public static $tabla_usuariostoken="UsuariosNK_Token";
	public static $tabla_usuariostokenadmin="UsuariosNK_TokenAdmin";

	public static $folder_usuarios="Media/Usuarios/";
	public static $sqlite_usuarios="Media/Usuarios/UsuariosNK.sqlite3";

	public static function getConexion() {
		$mysqli=new MySQLi("localhost", NK_USER, NK_PASS);

		if($mysqli->connect_errno)		{
			echo "Error Conexion: ".$mysqli->connect_error;
			exit();
		}

		if(!$mysqli->select_db(DBNAME_MAIN))
		{
			echo "no se encuentra base de datos: ".$mysqli->error ;
			exit();
		};

		$mysqli->set_charset("utf8");
		return $mysqli;
	}
}



class UsuarioNK {
    private $UsuarioID_In="";

    public $UsuarioID="";
    public $Nombres="";
    public $Apellidos="";
    public $Alias="";
    public $Descripcion="";
    public $Cargo="";
    public $Sexo="";
    public $PerfilH="";
    public $PerfilM="";
    public $PerfilS="";
    public $PerfilT="";
    public $Fecha_Nacimiento="";
    public $Fecha_Registro="";

    public $CarritoLite=[];
    public $CarritoLite_IDS=[];
    public $Carrito=[];

    public $FavoritosLite=[];
    public $FavoritosLite_IDS=[];
    public $Favoritos=[];


    public $Correo="";
    public $Nick=""; 
    public $Verificado="";
    public $Habilitado="";
    public bool $EsAdministrador=false;

    // -----------

    public $SedeDepartamento="";
    public $SedeMunicipio="";
    public $SedeDireccion="";
    public $SedeCoordenadaLat="";
    public $SedeCoordenadaLong="";
    public $LocalH="";
    public $LocalM="";
    public $LocalS="";
    public $LocalT="";
    public $LocalFB="";

    // -----------
    
    public $dirRaiz="";
    public $folder_usuario="";
    public $sqlite_usuario="";

    public function __construct($UsuarioID, $dirRaiz) {
		$this->UsuarioID_In=$UsuarioID;
		$this->dirRaiz=$dirRaiz;
		$this->Info_Get();
	}

	public function Info_Get()	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $tabla2=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT u.UsuarioID, u.Nombres, u.Apellidos, u.Alias, u.Descripcion, u.Cargo, u.Sexo, u.PerfilFotoH, u.PerfilFotoM, u.PerfilFotoS, u.PerfilFotoT, u.Fecha_Nacimiento, u.Fecha_Registro, ul.Correo, ul.Nick, ul.Verificado, ul.Habilitado, ul.EsAdministrador FROM $tabla u LEFT JOIN $tabla2 ul ON u.UsuarioID=ul.UsuarioID WHERE u.UsuarioID=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$this->UsuarioID_In);
			$stmt->execute();
			// -----------------
            $buffPerfilH="";
            $buffPerfilM="";
            $buffPerfilS="";
            $buffPerfilT="";
			$stmt->bind_result(
				$this->UsuarioID,
                $this->Nombres,
                $this->Apellidos,
                $this->Alias,
                $this->Descripcion,
                $this->Cargo,
                $this->Sexo,
                $buffPerfilH,
                $buffPerfilM,
                $buffPerfilS,
                $buffPerfilT,
                $this->Fecha_Nacimiento,
                $this->Fecha_Registro,
                $this->Correo,
                $this->Nick,
                $this->Verificado,
                $this->Habilitado,
                $this->EsAdministrador
			);
			$stmt->fetch();
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
        if(empty($this->UsuarioID)) {
            return [false, "SIn UsuarioID"];
        }
		//-------------------
        $this->EsAdministrador=boolval($this->EsAdministrador);
        //-------------------
		$Fecha_Obj=new DateTime($this->Fecha_Registro, new DateTimeZone("America/Bogota"));
		$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
		$this->folder_usuario=UsuarioNK_Conexion::$folder_usuarios.$this->UsuarioID."/";
		$buffTituloLink=neoKiri::encodeTitulo2Link($this->Nombres);
		$this->sqlite_usuario=$this->dirRaiz.$this->folder_usuario.$this->UsuarioID.".sqlite3";
		//-----------------------------
		if(!empty($buffPerfilH)) {
			$this->PerfilH=$this->folder_usuario.$buffPerfilH;
			$this->PerfilM=$this->folder_usuario.$buffPerfilM;
			$this->PerfilS=$this->folder_usuario.$buffPerfilS;
			$this->PerfilT=$this->folder_usuario.$buffPerfilT;
		}
		//------Portada Facebook
		// if(!empty($buffPortadaFB)) {
		// 	$this->PortadaFB=$this->folder_producto.$buffPortadaFB;
		// }
		//-------------------
		return [true];
	}

    public function Info_Set($Nombres, $Apellidos, $Alias, $Descripcion, $Cargo, $Sexo, $Fecha_Nacimiento) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin id usuario a editar"];
		}
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
		$query="UPDATE $tabla SET Nombres=?, Apellidos=?, Alias=?, Descripcion=?, Cargo=?, Sexo=?, Fecha_Nacimiento=? WHERE UsuarioID=?";
		$sql=UsuarioNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("ssssssss", $Nombres, $Apellidos, $Alias, $Descripcion, $Cargo, $Sexo, $Fecha_Nacimiento,  $this->UsuarioID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
            $sql->close();
			return [false, $sql->error];
		}
		$sql->close();
		return [true];
	}

    /* ********************************************* */
	public function FotoPerfil_Up($fileimg, $caption) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		//--imagen Portada
		if(!is_uploaded_file($fileimg["tmp_name"])) {
			return [false, "Error FotoPerfil_Up: NO se encuentra el archivo subido:".$fileimg];
		}
		$filenamePortadaO=$this->UsuarioID."FotoPerfil__.png";
		$filenamePortadaH=$this->UsuarioID."FotoPerfil_H.webp";
		$filenamePortadaM=$this->UsuarioID."FotoPerfil_M.webp";
		$filenamePortadaS=$this->UsuarioID."FotoPerfil_S.webp";
		$filenamePortadaT=$this->UsuarioID."FotoPerfil_T.webp";
		//----------------------------------
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
		$sql="UPDATE $tabla SET PerfilFotoH=?, PerfilFotoM=?, PerfilFotoS=?, PerfilFotoT=? WHERE UsuarioID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("sssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		$dirFileO=$this->dirRaiz.$this->folder_usuario.$filenamePortadaO;
		if(move_uploaded_file($fileimg["tmp_name"], $dirFileO)) {
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_usuario.$filenamePortadaH, 1024, 1024, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_usuario.$filenamePortadaM, 800, 600, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_usuario.$filenamePortadaS, 640, 480, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_usuario.$filenamePortadaT, 200, 200, "webp");
			// neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_publicacion.$filenamePortadaT, 320, 240, "webp");
			unlink($dirFileO);
		} else {
			return [false, "Error setPortadaImg: Error al copiar imagen de portada"];
		}
		return [true, $filenamePortadaH];		
	}
	
	public function FotoPerfil_Del() {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID PublicacionNK"];
		}
		//--imagen Portada
		if(is_file($this->dirRaiz.$this->PerfilH)) {
			unlink($this->dirRaiz.$this->PerfilH);
			unlink($this->dirRaiz.$this->PerfilM);
			unlink($this->dirRaiz.$this->PerfilS);
			unlink($this->dirRaiz.$this->PerfilT);
		} else {
			return [false, "No se encontro archivo a eliminar"];
		}
		$filenamePortadaH="";
		$filenamePortadaM="";
		$filenamePortadaS="";
		$filenamePortadaT="";
		$caption="";
		//----------------------------------
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
		$sql="UPDATE $tabla SET PerfilFotoH=?, PerfilFotoM=?, PerfilFotoS=?, PerfilFotoT=?  WHERE UsuarioID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("sssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		
		return [true, $filenamePortadaH];		
	}

    public function Password_Set($Pass1, $Pass2) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin id usuario a editar"];
		}
        $Pass1=trim($Pass1);
        $Pass2=trim($Pass2);
        if(empty($Pass1) || empty($Pass2)) {
            return [false, "Datos Vacios"];
        }

        if($Pass1 !== $Pass2) {
            return [false, "Las Contraseñas no coinciden"];
        }
        // ---------------------
        $Pass=password_hash($Pass1, PASSWORD_DEFAULT);
        // ---------------------
		$tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
		$query="UPDATE $tabla SET Contrasenia=?, Verificado=1 WHERE UsuarioID=?";
		$sql=UsuarioNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
            $stmt->bind_param("ss", $Pass,  $this->UsuarioID);
			if(!$stmt->execute()) {
                return [false, $sql->error];
			};
			$stmt->close();
		} else {
            $sql->close();
			return [false, $sql->error];
		}
        $sql->close();
        // ---------------------
		return [true, "Contraseña Usuario Cambiada Correctamente"];
	}

    public function Password_Verificar($Pass) {
        $Pass=trim($Pass);
        if(empty($this->UsuarioID) || empty($Pass)) {
            return [false, "Error: Se necesitan datos de inicio de sesion LOGIN"];
        }
        // ------------
        $buffUsuarioID="";
        $buffContrasenia="";
        $buffVerificado="";
        $buffHabilitado="";
        // ------------
        $mysqli=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT UsuarioID, Contrasenia, Verificado, Habilitado FROM $tabla WHERE UsuarioID=? LIMIT 1";
        $Resultados=[];
        if($stmt=$mysqli->prepare($sql)) {
            // -----------------
            $stmt->bind_param("s",$this->UsuarioID);
            $stmt->execute();
            // -----------------
            $stmt->bind_result(
                $buffUsuarioID,
                $buffContrasenia,
                $buffVerificado,
                $buffHabilitado
            );
            $stmt->fetch();
            $stmt->close();
        } else {
            $mysqli->close();
            return [false,"UsuarioNK::Password_Verificar() error"];
        }
        $mysqli->close();
        // ------------
        if(empty($buffUsuarioID)) {
            return [false, "Error: Este usuario no existe"];
        }
        
        if(!boolval($buffVerificado) || !boolval($buffVerificado)) {
            return [false, "Lo siento, esta cuenta no se ha verificado o esta deshabilitada. Comunicate con nosotros."];
        }

        if(password_verify($Pass, $buffContrasenia)) {
            return [true, true, "Si coinciden"];
        } else {
            return [true, false, "No coinciden"];
        }
    }

    public function CarritoAdd(string $ProductoID, int $Cantidad) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		if(empty($ProductoID)) {
			return [false, "Sin Key Producto"];
		}
        if(is_nan($Cantidad)) {
            return [false, "Necesitamos un numero"];
        }
		if($Cantidad<1) {
			return [false, "Necesitamos al una cantidad"];
		}
		//-----
        $CarritoID=hash("crc32",$this->UsuarioID.$ProductoID);
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
        $fechaUpload=$FechaNow->format("Y-m-d H:i:s");
		//-----
		$sqlite=new SQLite3($this->sqlite_usuario, SQLITE3_OPEN_READWRITE,"neoKiri");
		$sql_setImageRich="INSERT INTO CarritoCompras (CarritoID, UsuarioID, ProductoID, Cantidad, Fecha_Registro) VALUES (:caid, :usid, :prid, :cant, :fch)";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
			$stmtlit->bindParam(":caid", $CarritoID,SQLITE3_TEXT);
			$stmtlit->bindParam(":usid", $this->UsuarioID,SQLITE3_TEXT);
			$stmtlit->bindParam(":prid", $ProductoID,SQLITE3_TEXT);
			$stmtlit->bindParam(":cant", $Cantidad,SQLITE3_INTEGER);
			$stmtlit->bindParam(":fch", $fechaUpload,SQLITE3_TEXT);
            $stmtlit->execute();
			$stmtlit->close();
		} else {
            return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
        }		
		$sqlite->close();
		//------
		return [true, $CarritoID];
	}
    
    public function Carrito_DelID(string $CarritoID) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
        $CarritoID=trim($CarritoID);
		if(empty($CarritoID)) {
			return [false, "Sin Key Producto"];
		}
		//-----
		$sqlite=new SQLite3($this->sqlite_usuario, SQLITE3_OPEN_READWRITE,"neoKiri");
		$sql_setImageRich="DELETE FROM CarritoCompras WHERE CarritoID=:caid OR ProductoID=:proid";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
			$stmtlit->bindParam(":caid", $CarritoID, SQLITE3_TEXT);
			$stmtlit->bindParam(":proid", $CarritoID, SQLITE3_TEXT);
            $stmtlit->execute();
			$stmtlit->close();
		} else {
            return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
        }		
		$sqlite->close();
		//------
		return [true, $CarritoID];
	}
    
    public function Carrito_DelAll() {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		//-----
		$sqlite=new SQLite3($this->sqlite_usuario, SQLITE3_OPEN_READWRITE,"neoKiri");
		$sql_setImageRich="DELETE FROM CarritoCompras";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
            $stmtlit->execute();
			$stmtlit->close();
		} else {
            return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
        }		
		$sqlite->close();
		//------
		return [true, "Carrito de compras limpio"];
	}

    public function Carrito_GetAll_Lite() {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		$resultados=[];
		$resultados_ids=[];
		$sqlite=new SQLite3($this->sqlite_usuario,SQLITE3_OPEN_READONLY,"neoKiri");
		$querylite="SELECT CarritoID, UsuarioID, ProductoID, Cantidad, Fecha_Registro FROM CarritoCompras";
		$resultados_lite=$sqlite->query($querylite);
		while($image=$resultados_lite->fetchArray(SQLITE3_ASSOC)) {
			$image=(object)$image;
            array_push($resultados, $image);
            array_push($resultados_ids, $image->ProductoID);
            unset($image);
		};
		$sqlite->close();
		$this->CarritoLite=$resultados;
		$this->CarritoLite_IDS=$resultados_ids;
		return [true, $resultados, $resultados_ids];
	}
    
    public function Carrito_GetID($CarritoID) {
        if(empty($CarritoID)) {
            return [false, "Sin ID Carrito"];
        }
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		$resultados=[];
		$sqlite=new SQLite3($this->sqlite_usuario,SQLITE3_OPEN_READONLY,"neoKiri");
		$querylite="SELECT CarritoID, UsuarioID, ProductoID, Fecha_Registro FROM CarritoCompras WHERE CarritoID=:carid LIMIT 1";
        if($stmtlit=$sqlite->prepare($querylite)) {
            $stmtlit->bindParam(":carid", $CarritoID, SQLITE3_TEXT);
            $result=$stmtlit->execute();
            while($row=$result->fetchArray(SQLITE3_ASSOC)) {
                array_push($resultados, (object)$row);
            }
            $stmtlit->close();
        }
		$sqlite->close();
        if(count($resultados)<1) {
            return [false, "Sin Resultados"];
        }
		return [true, $resultados];
	}

    public function Carrito_GetAll() {
        $this->Carrito_GetAll_Lite();
        $CarritoStat=ProductosNK_Getters::FromIDS($this->CarritoLite_IDS);
        if($CarritoStat[0]) {
            $this->Carrito=$CarritoStat[1];
        }
        $buffCarritoSession=[];
        foreach ($this->Carrito as $CarritoItem) {
            foreach ($this->CarritoLite as $CarritoLiteItem) {
                if($CarritoItem->ProductoID==$CarritoLiteItem->ProductoID) {
                    $CarritoItem->CarritoID=$CarritoLiteItem->CarritoID;
                    $CarritoItem->Cantidad=(int) $CarritoLiteItem->Cantidad;
                    $CarritoItem->Precio=( (int) $CarritoItem->PrecioFinal) * ( (int)$CarritoLiteItem->Cantidad);
                    $buffCarritoSessionStd=new stdClass();
                    $buffCarritoSessionStd->ProductoID=$CarritoItem->ProductoID;
                    $buffCarritoSessionStd->Cantidad=$CarritoItem->Cantidad;
                    array_push($buffCarritoSession, $buffCarritoSessionStd);
                }
            }
        }
        $_SESSION["CarritoSessionIDS"]=json_encode($buffCarritoSession);
        return $CarritoStat;
    }

    public function Favorito_Add(string $ProductoID) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		if(empty($ProductoID)) {
			return [false, "Sin Key Producto"];
		}
		//-----
        $FavoritoID=hash("crc32",$this->UsuarioID.$ProductoID);
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
        $fechaUpload=$FechaNow->format("Y-m-d H:i:s");
		//-----
        $sqlite=new SQLite3($this->sqlite_usuario, SQLITE3_OPEN_READWRITE,"neoKiri");
        $sql_setImageRich="INSERT INTO ProductosFavoritos (ProductoFavoritoID, UsuarioID, ProductoID, Fecha_Registro) VALUES (:caid, :usid, :prid, :fch)";
        if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
            $stmtlit->bindParam(":caid", $FavoritoID,SQLITE3_TEXT);
            $stmtlit->bindParam(":usid", $this->UsuarioID,SQLITE3_TEXT);
            $stmtlit->bindParam(":prid", $ProductoID,SQLITE3_TEXT);
            $stmtlit->bindParam(":fch", $fechaUpload,SQLITE3_TEXT);
            if(!$stmtlit->execute()) {
                return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
            };
            $stmtlit->close();
        } else {
            return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
        }		
        $sqlite->close();
		//------
		return [true, $FavoritoID];
	}
    
    public function Favorito_DelID(string $FavoritoID) {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
        $FavoritoID=trim($FavoritoID);
		if(empty($FavoritoID)) {
			return [false, "Sin Key Producto"];
		}
		//-----
		$sqlite=new SQLite3($this->sqlite_usuario, SQLITE3_OPEN_READWRITE,"neoKiri");
		$sql_setImageRich="DELETE FROM ProductosFavoritos WHERE ProductoFavoritoID=:caid OR ProductoID=:proid";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
			$stmtlit->bindParam(":caid", $FavoritoID, SQLITE3_TEXT);
			$stmtlit->bindParam(":proid", $FavoritoID, SQLITE3_TEXT);
            $stmtlit->execute();
			$stmtlit->close();
		} else {
            return [false, $sqlite->lastErrorCode()." :: ".$sqlite->lastErrorMsg()];
        }		
		$sqlite->close();
		//------
		return [true, $FavoritoID];
	}

    public function Favorito_GetAll_Lite() {
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		$resultados=[];
		$resultados_ids=[];
		$sqlite=new SQLite3($this->sqlite_usuario,SQLITE3_OPEN_READONLY,"neoKiri");
		$querylite="SELECT ProductoFavoritoID, UsuarioID, ProductoID, Fecha_Registro FROM ProductosFavoritos";
		$resultados_lite=$sqlite->query($querylite);
		while($image=$resultados_lite->fetchArray(SQLITE3_ASSOC)) {
			$image=(object)$image;
            array_push($resultados, $image);
            array_push($resultados_ids, $image->ProductoID);
            unset($image);
		};
		$sqlite->close();
		$this->FavoritosLite=$resultados;
		$this->FavoritosLite_IDS=$resultados_ids;
		return [true, $resultados];
	}
    
    public function Favorito_GetID($FavoritoID) {
        if(empty($FavoritoID)) {
            return [false, "Sin ID Favorito"];
        }
		if(empty($this->UsuarioID)) {
			return [false, "Sin ID Usuario"];
		}
		$resultados=[];
		$sqlite=new SQLite3($this->sqlite_usuario,SQLITE3_OPEN_READONLY,"neoKiri");
		$querylite="SELECT ProductoFavoritoID, UsuarioID, ProductoID, Fecha_Registro FROM ProductosFavoritos WHERE ProductoFavoritoID=:favid LIMIT 1";
        if($stmtlit=$sqlite->prepare($querylite)) {
            $stmtlit->bindParam(":favid", $FavoritoID, SQLITE3_TEXT);
            $result=$stmtlit->execute();
            while($row=$result->fetchArray(SQLITE3_ASSOC)) {
                array_push($resultados, (object)$row);
            }
            $stmtlit->close();
        }
		$sqlite->close();
        if(count($resultados)<1) {
            return [false, "Sin Resultados"];
        }
		return [true, $resultados];
	}

    public function Favoritos_GetAll() {
        $this->Favorito_GetAll_Lite();
        $FavoritosStat=ProductosNK_Getters::FromIDS($this->FavoritosLite_IDS);
        if(!$FavoritosStat[0]) {
            return $FavoritosStat;
        }
        $this->Favoritos=$FavoritosStat[1];
        // -------------------------------
        $buffFavoritosSession=[];
        foreach ($this->Favoritos as $FavoritoItem) {
            foreach ($this->FavoritosLite as $FavoritoLiteItem) {
                if($FavoritoItem->ProductoID==$FavoritoLiteItem->ProductoID) {
                    $FavoritoItem->ProductoFavoritoID=$FavoritoLiteItem->ProductoFavoritoID;
                    $FavoritoItem->Precio=(int) $FavoritoItem->PrecioFinal;
                    $buffFavoritoSessionStd=new stdClass();
                    $buffFavoritoSessionStd->ProductoID=$FavoritoItem->ProductoID;
                    array_push($buffFavoritosSession, $buffFavoritoSessionStd);
                }
            }
        }
        $_SESSION["FavoritosSessionIDS"]=json_encode($buffFavoritosSession);
        // -------------------------------
        return $FavoritosStat;
    }

    public function EsAdministrador_Change() {
		if(empty($this->UsuarioID)) {
			return [false, "Sin id usuario a editar"];
		}
        $buffEsAdministrador=!boolval($this->EsAdministrador);
		$tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
		$query="UPDATE $tabla SET EsAdministrador=? WHERE UsuarioID=?";
		$sql=UsuarioNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("is", $buffEsAdministrador,  $this->UsuarioID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
            $sql->close();
			return [false, $sql->error];
		}
		$sql->close();
		return [true, $buffEsAdministrador];
	}

    public static function Usuario_New($Nombres, $Apellidos, $Correo, $dirRaiz) {
		//--------------------------
		//Comprobar datos vacios
		if(empty($Nombres) || empty($Apellidos)) {
            return [false, "Faltan datos necesarios para registro de usuario"];
		}
        $Correo=strtolower( trim($Correo) );
        if(empty($Correo)) {
            return [false, "Faltan datos necesarios para registro de inicio de sesion"];
        }
        //--------------------------
        // TODO: Verificar que correo no existe
        //--------------------------
		//Preparar datos
		//Año/Mes/Dia/Hora Militar/Minuto/Segundo/
        $FechaRegistro=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fecha_id=$FechaRegistro->format("YmdHis");
		$fecha_registro=$FechaRegistro->format("Y-m-d H:i:s");
		$fecha_ano=$FechaRegistro->format("Y");
		$fecha_mes=$FechaRegistro->format("m");
		//--------------------------
		//Generar Un Identificador de PublicacionNK
        $str="";
		$separar=explode(" ",stripslashes($Nombres));
		foreach ($separar as $line) {
			$lineAct=preg_replace("/\W/","",$line);
			if(empty($lineAct)) {
				continue;
			} else {
				$str.=substr($lineAct,0,1);
			}
		}
		$UsuarioID="UsuarioNK".hash("sha256", $Nombres.$Apellidos.$Correo.$fecha_id);
		$newID=neoKiri::GenerarRandomID(4, "");
		$UsuarioIDAle=$newID;
		//--------------------------
        $Pass=password_hash($Correo.$UsuarioID, PASSWORD_DEFAULT);
		//--------------------------
		//Generar Consulta SQL para agregar a la DB 
		$mysqli=UsuarioNK_Conexion::getConexion();
		$stat="";
        $tabla=UsuarioNK_Conexion::$tabla_usuarios;
		$nickDir=neoKiri::encodeTitulo2Link($Nombres." ".$Apellidos)."-".$UsuarioIDAle;
		$sql="INSERT INTO $tabla (UsuarioID, Nombres, Apellidos, Fecha_Registro) VALUES (?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
            $stmt->bind_param("ssss", $UsuarioID, $Nombres, $Apellidos, $fecha_registro);
			if(!$stmt->execute()) {
                return [false, "Usuario New Error: ".$mysqli->error];
			};
			$stmt->close();
		} else {
            $stat=$mysqli->error;
			return [false, "Usuario New Error ".$stat];
		}
        //----------------
        $tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
		$sql="INSERT INTO $tabla (UsuarioID, Correo, Contrasenia, Nick) VALUES (?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
            $stmt->bind_param("ssss", $UsuarioID, $Correo, $Pass, $nickDir);
			if(!$stmt->execute()) {
                return [false, "Usuario New Error: ".$mysqli->error];
			};
			$stmt->close();
		} else {
            $stat=$mysqli->error;
			return [false, "Usuario New Error ".$stat];
		}
		$mysqli->close();

		//--Crear carpetas
		$folderUsuario=$dirRaiz.UsuarioNK_Conexion::$folder_usuarios.$UsuarioID."/";
		if(!is_dir($folderUsuario)) {
			mkdir($folderUsuario,0755,true);
		}

		$UsuarioLiteStat=UsuarioNK_Lite::DB_Crear($dirRaiz, $UsuarioID, $folderUsuario);
		if(!$UsuarioLiteStat[0]) {
			return $UsuarioLiteStat;
		}
		return [true, $UsuarioID];
		//--------------------------------------------------------
	}

    public function Usuario_Del()	{
		if(empty($this->UsuarioID)) {
			return [false,"sin id usuario a eliminar"];
		}
		// return [false, "Eliminando: ".$this->dirRaiz.$this->folder_usuario];
		//-------------------
		$mysqli=UsuarioNK_Conexion::getConexion();
		//----------------------------
		$tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
		$sql="DELETE FROM $tabla WHERE UsuarioID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Usuario_Del :: ".$mysqli->error];
		}
		//----------------------------
		//----------------------------
		$tabla=UsuarioNK_Conexion::$tabla_usuariostoken;
		$sql="DELETE FROM $tabla WHERE UsuarioID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Usuario_Del :: ".$mysqli->error];
		}
		
		//----------------------------
		//----------------------------
		$tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
		$sql="DELETE FROM $tabla WHERE UsuarioID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Usuario_Del :: ".$mysqli->error];
		}
		//----------------------------
		//----------------------------
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
		$sql="DELETE FROM $tabla WHERE UsuarioID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->UsuarioID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Usuario_Del :: ".$mysqli->error];
		}
		//----------------------------
		$mysqli->close();
		//--------------------------------
        if($this->dirRaiz.$this->folder_usuario==$this->dirRaiz) {
            return [false, "Se quiere eliminar el proyecto, (︶︹︶)"];
        }
		$statDelDirectory=neoKiri::delDirectorio($this->dirRaiz.$this->folder_usuario,true);
		if(!$statDelDirectory[0])	{
			return [false, $statDelDirectory];
		}
		return [true, $this->UsuarioID, $statDelDirectory];
	}
}

class UsuarioNK_Lite {
	public static function DB_Crear($dirRaiz, $UsuarioID, $folder_usuario) {
		$sqliteDir=$folder_usuario.$UsuarioID.".sqlite3";
		$sqlite=new SQLite3($sqliteDir, SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sqlite->exec(UsuariosNK_Fix::$queryTablaUsuariosNK);
		$sqlite->exec(UsuariosNK_Fix::$queryTablaUsuariosTokenNK);
		$sqlite->exec(UsuariosNK_Fix::$queryTablaUsuariosTokenAdminNK);
		//---------------------------
		$sqlite->exec(neoKiri::$tablaLite_Imagenes);
		$sqlite->exec(neoKiri::$tablaLite_Videos);
		$sqlite->exec(neoKiri::$tablaLite_Archivos);
		$sqlite->exec(neoKiri::$tablaLite_Comentarios);
		//---------------------------
		$Query_carrito="CREATE TABLE IF NOT EXISTS CarritoCompras (
			CarritoID varchar(255) not null primary key,
			UsuarioID varchar(255) not null,
			ProductoID varchar(255) not null,
			Cantidad int(11) not null default 1,
			Fecha_Registro datetime
			)";
		$sqlite->exec($Query_carrito);
		//---------------------------
		$Query_productosfavoritos="CREATE TABLE IF NOT EXISTS ProductosFavoritos (
			ProductoFavoritoID varchar(255) not null primary key,
			UsuarioID varchar(255) not null,
			ProductoID varchar(255) not null,
			Fecha_Registro datetime
			)";
		$sqlite->exec($Query_productosfavoritos);
		//---------------------------
		$Query_direcciones="CREATE TABLE IF NOT EXISTS Direcciones (
			DireccionID varchar(255) not null primary key,
			Pais varchar(255) not null,
			Departamento varchar(255) not null,
			Municipio varchar(255) not null,
			DireccionEnvio varchar(255) not null,
			Fecha_Registro datetime
			)";
		$sqlite->exec($Query_direcciones);
        //---------------------------
		$sqlite->close();
		return [true];
	}
}


class UsuariosNK_Getters {

    public static function Get_All($dirRaiz)	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $tabla2=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT u.UsuarioID, u.Nombres, u.Apellidos, u.Descripcion, u.Cargo, u.Sexo, u.PerfilFotoH, u.PerfilFotoM, u.PerfilFotoS, u.PerfilFotoT, ul.Correo, ul.Nick, ul.Verificado, ul.Habilitado, ul.EsAdministrador, u.Fecha_Nacimiento, u.Fecha_Registro FROM $tabla u LEFT JOIN $tabla2 ul ON u.UsuarioID=ul.UsuarioID ORDER BY u.Nombres ASC";
		$Resultados=[];
        $ResultadosIds=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$this->UsuarioID_In);
			$stmt->execute();
			// -----------------
            $buffUsuarioID="";
            $buffNombres="";
            $buffApellidos="";
            $buffDescripcion="";
            $buffCargo="";
            $buffSexo="";
            $buffPerfilH="";
            $buffPerfilM="";
            $buffPerfilS="";
            $buffPerfilT="";
            $buffCorreo="";
            $buffNick="";
            $buffVerificado="";
            $buffHabilitado="";
            $buffEsAdministrador=false;
            $buffFechaNacimiento="";
            $buffFechaRegistro="";
			$stmt->bind_result(
				$buffUsuarioID,
                $buffNombres,
                $buffApellidos,
                $buffDescripcion,
                $buffCargo,
                $buffSexo,
                $buffPerfilH,
                $buffPerfilM,
                $buffPerfilS,
                $buffPerfilT,
                $buffCorreo,
                $buffNick,
                $buffVerificado,
                $buffHabilitado,
                $buffEsAdministrador,
                $buffFechaNacimiento,
                $buffFechaRegistro
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Apellidos=$buffApellidos;
                $buffStd->Descripcion=$buffDescripcion;
                $buffStd->Cargo=$buffCargo;
                $buffStd->Sexo=$buffSexo;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Nick=$buffNick;
                $buffStd->Verificado=$buffVerificado;
                $buffStd->Habilitado=$buffHabilitado;
                $buffStd->EsAdministrador=boolval($buffEsAdministrador);
                $buffStd->Fecha_Nacimiento=$buffFechaNacimiento;
                $buffStd->Fecha_Registro=$buffFechaRegistro;

                $Fecha_Obj=new DateTime($buffFechaRegistro, new DateTimeZone("America/Bogota"));
                $Fecha_Texto=$Fecha_Obj->format("Y-m-d");
                $bufffolderusuario=UsuarioNK_Conexion::$folder_usuarios.$buffUsuarioID."/";
                $buffTituloLink=neoKiri::encodeTitulo2Link($buffNombres." ".$buffApellidos);
                $buffSqliteUsuario=$dirRaiz.$bufffolderusuario.$buffUsuarioID.".sqlite3";
                //-----------------------------
                if(!empty($buffPerfilH)) {
                    $buffStd->PerfilH=$bufffolderusuario.$buffPerfilH;
                    $buffStd->PerfilM=$bufffolderusuario.$buffPerfilM;
                    $buffStd->PerfilS=$bufffolderusuario.$buffPerfilS;
                    $buffStd->PerfilT=$bufffolderusuario.$buffPerfilT;
                }
                array_push($Resultados, $buffStd);
                array_push($ResultadosIds, $buffUsuarioID);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		return [true, $Resultados];
	}
    public static function Get_All_FromList(array $UsuariosIds, $dirRaiz)	{
        if(count($UsuariosIds)<1) {
            return [false, "Necesitamos una lista de ids usuario"];
        }
        $UsuariosIdsTexto=implode(",", $UsuariosIds);
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $tabla2=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT u.UsuarioID, u.Nombres, u.Apellidos, u.Descripcion, u.Cargo, u.Sexo, u.PerfilFotoH, u.PerfilFotoM, u.PerfilFotoS, u.PerfilFotoT, ul.Correo, ul.Nick, ul.Verificado, ul.Habilitado ul.EsAdministrador, u.Fecha_Nacimiento, u.Fecha_Registro FROM $tabla u LEFT JOIN $tabla2 ul ON u.UsuarioID=ul.UsuarioID WHERE u.UsuarioID IN (?) ORDER BY u.Nombres ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$this->UsuarioID_In);
			$stmt->execute();
			// -----------------
            $buffUsuarioID="";
            $buffNombres="";
            $buffApellidos="";
            $buffDescripcion="";
            $buffCargo="";
            $buffSexo="";
            $buffPerfilH="";
            $buffPerfilM="";
            $buffPerfilS="";
            $buffPerfilT="";
            $buffCorreo="";
            $buffNick="";
            $buffVerificado="";
            $buffHabilitado="";
            $buffEsAdministrador=false;
            $buffFechaNacimiento="";
            $buffFechaRegistro="";
			$stmt->bind_result(
				$buffUsuarioID,
                $buffNombres,
                $buffApellidos,
                $buffDescripcion,
                $buffCargo,
                $buffSexo,
                $buffPerfilH,
                $buffPerfilM,
                $buffPerfilS,
                $buffPerfilT,
                $buffCorreo,
                $buffNick,
                $buffVerificado,
                $buffHabilitado,
                $buffEsAdministrador,
                $buffFechaNacimiento,
                $buffFechaRegistro
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Apellidos=$buffApellidos;
                $buffStd->Descripcion=$buffDescripcion;
                $buffStd->Cargo=$buffCargo;
                $buffStd->Sexo=$buffSexo;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Nick=$buffNick;
                $buffStd->Verificado=$buffVerificado;
                $buffStd->Habilitado=$buffHabilitado;
                $buffStd->EsAdministrador=boolval($buffEsAdministrador);
                $buffStd->Fecha_Nacimiento=$buffFechaNacimiento;
                $buffStd->Fecha_Registro=$buffFechaRegistro;

                $Fecha_Obj=new DateTime($buffFechaRegistro, new DateTimeZone("America/Bogota"));
                $Fecha_Texto=$Fecha_Obj->format("Y-m-d");
                $bufffolderusuario=UsuarioNK_Conexion::$folder_usuarios.$buffUsuarioID."/";
                $buffTituloLink=neoKiri::encodeTitulo2Link($buffNombres." ".$buffApellidos);
                $buffSqliteUsuario=$dirRaiz.$bufffolderusuario.$buffUsuarioID.".sqlite3";
                //-----------------------------
                if(!empty($buffPerfilH)) {
                    $buffStd->PerfilH=$bufffolderusuario.$buffPerfilH;
                    $buffStd->PerfilM=$bufffolderusuario.$buffPerfilM;
                    $buffStd->PerfilS=$bufffolderusuario.$buffPerfilS;
                    $buffStd->PerfilT=$bufffolderusuario.$buffPerfilT;
                }
                array_push($Resultados, $buffStd);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		return [true, $Resultados];
	}

    public static function Correo_from_Id($UsuarioID)	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT Correo FROM $tabla WHERE UsuarioID=? LIMIT 1";
        $buffCorreo="";
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$UsuarioID);
			$stmt->execute();
			// -----------------
			$stmt->bind_result(
				$buffCorreo
			);
            $stmt->fetch();
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"Correo_from_Id"];
		}
		$mysqli->close();
		//-------------------
        if (empty($buffCorreo)) {
            return [false, "No hubo resultados"];
        }
		//-------------------
		return [true, $buffCorreo];
	}
    
    public static function GetFromCorreo($UsuarioCorreo)	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $tablalogin=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT u.UsuarioID, ul.Correo, u.Nombres, u.Apellidos, ul.Verificado FROM $tabla u LEFT JOIN $tablalogin ul ON u.UsuarioID=ul.UsuarioID WHERE ul.Correo=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$UsuarioCorreo);
			$stmt->execute();
			// -----------------
            $buffUsuarioID="";
            $buffCorreo="";
            $buffNombres="";
            $buffApellidos="";
            $buffVerificado="";
            $stmt->bind_result(
				$buffUsuarioID,
                $buffCorreo,
                $buffNombres,
                $buffApellidos,
                $buffVerificado
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Apellidos=$buffApellidos;
                $buffStd->Verificado=$buffVerificado;
                array_push($Resultados, $buffStd);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
        if (count($Resultados)<1) {
            return [false, "Sin Resultados"];
        }
		//-------------------
		return [true, $Resultados[0]];
	}

    public static function IDFromNick($UsuarioNick)	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $tablalogin=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT u.UsuarioID, ul.Correo, u.Nombres, u.Apellidos, ul.Verificado, ul.Nick FROM $tabla u LEFT JOIN $tablalogin ul ON u.UsuarioID=ul.UsuarioID WHERE ul.Nick=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$UsuarioNick);
			$stmt->execute();
			// -----------------
            $buffUsuarioID="";
            $buffCorreo="";
            $buffNombres="";
            $buffApellidos="";
            $buffVerificado="";
            $buffNick="";
            $stmt->bind_result(
				$buffUsuarioID,
                $buffCorreo,
                $buffNombres,
                $buffApellidos,
                $buffVerificado,
                $buffNick
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Apellidos=$buffApellidos;
                $buffStd->Verificado=$buffVerificado;
                $buffStd->Nick=$buffNick;
                array_push($Resultados, $buffStd);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
        if (count($Resultados)<1) {
            return [false, "Sin Resultados"];
        }
		//-------------------
		return [true, $Resultados[0], $buffUsuarioID];
	}
}

class UsuarioNK_Login {

    public static function Login($Correo, $Pass, $dirRaiz) {
        $Correo=strtolower(trim($Correo));
        // ------------
        if(empty($Correo) || empty($Pass)) {
            UsuarioNK_Login::Logout();
            return [false, "Error: Se necesitan datos de inicio de sesion LOGIN"];
        }
        // ------------
        $buffUsuarioID="";
        $buffCorreo="";
        $buffContrasenia="";
        $buffVerificado="";
        $buffHabilitado="";
        // ------------
        $mysqli=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $sql="SELECT UsuarioID, Correo, Contrasenia, Verificado, Habilitado FROM $tabla WHERE Correo=? LIMIT 1";
        $Resultados=[];
        if($stmt=$mysqli->prepare($sql)) {
            // -----------------
            $stmt->bind_param("s",$Correo);
            $stmt->execute();
            // -----------------
            $stmt->bind_result(
                $buffUsuarioID,
                $buffCorreo,
                $buffContrasenia,
                $buffVerificado,
                $buffHabilitado
            );
            $stmt->fetch();
            $stmt->close();
        } else {
            $mysqli->close();
            return [false,"STMT Info_Get - "];
        }
        $mysqli->close();
        // ------------
        if(empty($buffUsuarioID)) {
            UsuarioNK_Login::Logout();
            return [false, "Error: Este usuario no existe"];
        }
        
        if(!password_verify($Pass, $buffContrasenia)) {
            UsuarioNK_Login::Logout();
            return [false, "Error: Usuario y/o Contraseña Incorrectos"];
        }
        
        if(!$buffVerificado) {
            UsuarioNK_Login::Logout();
            return [false, "Lo siento, esta cuenta no se ha verificado. Intenta recuperar tu contraseña o comunicate con nosotros."];
        }
        
        $PersonaHashStatus=UsuarioNK_Login::CrearHash($buffUsuarioID);

        return $PersonaHashStatus;
    }

    //Crea un HASH de Login para continuar la sesion
    public static function CrearHash($UsuarioID) {
        if(empty($UsuarioID)) {
            return [false, "se necesita ID de usuario para hash"];
        }
        $UsuarioObj=new UsuarioNK($UsuarioID, "");
        if(empty($UsuarioObj->UsuarioID)) {
            return [false, "El usuario no existe para crear el hash"];
        }
        $UsuarioNombres=$UsuarioObj->Nombres." ".$UsuarioObj->Apellidos;
        //hora Actual
        $now=new DateTime("now", new DateTimeZone("America/Bogota"));
        $nowText=$now->format("Y-m-d H:i:s");
        //Sumamos 24 horas para que el token expire
        $doceHoras=new DateTime("now", new DateTimeZone("America/Bogota"));
        $doceHoras->modify("+24 hours");
        $doceHorasText=$doceHoras->format("Y-m-d H:i:s");
        //Creamos el HASH
        $HASH=password_hash($nowText.$UsuarioID, PASSWORD_DEFAULT);
        $EstadoActivo=false;
        //-------------
        $sql=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuariostoken;
        $query="INSERT INTO $tabla (UsuarioTokenID, UsuarioID, UsuarioNombre, Fecha_Conexion, Fecha_Expiracion, Estado_Activo, Fecha_Registro) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if($stmt=$sql->prepare($query)) {
            $stmt->bind_param("sssssis", $HASH, $UsuarioID, $UsuarioNombres, $nowText, $doceHorasText, $EstadoActivo, $nowText);
            if(!$stmt->execute()) {
                return [false, $sql->error];
            }            
            $stmt->close();
        } else {
            return [false, $sql->error];
        }
        $sql->close();
        //-------------
        //Creamos las Variables de Sesion
        // session_start();
        $_SESSION["UsuarioNK"]=$UsuarioID;
        $_SESSION["UsuarioNombres"]=$UsuarioNombres;
        $_SESSION["UsuarioFoto"]=$UsuarioObj->PerfilS;
        $_SESSION["UsuarioKeyJX"]=$HASH;
        $_SESSION["UsuarioFechaExpiracion"]=$doceHorasText;
        $_SESSION["UsuarioAdministrador"]=boolval($UsuarioObj->EsAdministrador);
        return [true, $HASH, $doceHorasText];
    }

    public static function LoginCheck($KeyJX) {
        $buffStd=new stdClass();
        //$buffStd->Persona_ID="";
        $buffStd->Fecha_Conexion=""; //fecha de ultima conexion
        $buffStd->Usuario_Nombre=""; // nombre del usuario
        $buffStd->Usuario_Foto="";
        $buffStd->Fecha_Expiracion=""; //fecha de expiracion
        $buffStd->Activo=false; // Token Activo
        $buffStd->KeyJX=$KeyJX; // El token js
        //---------------------------------------------
        if(empty($KeyJX)) {
            return [false, $buffStd];
        }
        $now=new DateTime("now", new DateTimeZone("America/Bogota"));
        //---------------------------------------------
        if(!isset($_SESSION["UsuarioNK"])) {
            //---------Se obtienen los datos del token
            $mysql=UsuarioNK_Conexion::getConexion();
            $tabla=UsuarioNK_Conexion::$tabla_usuariostoken;
            $tabla2=UsuarioNK_Conexion::$tabla_usuarios;
            $tabla3=UsuarioNK_Conexion::$tabla_usuarioslogin;
            $sql="SELECT ut.UsuarioTokenID, ut.UsuarioID, ut.UsuarioNombre, Fecha_Conexion, Fecha_Expiracion, Estado_Activo, ut.Fecha_Registro, u.PerfilFotoS, ul.EsAdministrador FROM $tabla ut LEFT JOIN $tabla2 u ON ut.UsuarioID=u.UsuarioID LEFT JOIN $tabla3 ul ON ul.UsuarioID=u.UsuarioID WHERE ut.UsuarioTokenID=?";
            if($stmt=$mysql->prepare($sql)) {
                $stmt->bind_param("s", $KeyJX);
                if(!$stmt->execute()) {
                    return [false, "UsuarioNK_Login::LoginCheck() - Error: ".$mysql->error];
                }
                $buffUsuarioTokenID="";
                $buffUsuarioID="";
                $buffUsuarioNombre="";
                $buffFechaConexion="";
                $buffFechaExpiracion="";
                $buffEstadoActivo="";
                $buffFechaRegistro="";
                $buffUsuarioFoto="";
                $buffEsAdministrador="";
                $stmt->bind_result(
                    $buffUsuarioTokenID,
                    $buffUsuarioID,
                    $buffUsuarioNombre,
                    $buffFechaConexion,
                    $buffFechaExpiracion,
                    $buffEstadoActivo,
                    $buffFechaRegistro,
                    $buffUsuarioFoto,
                    $buffEsAdministrador
                );
                $stmt->fetch();
            } else {
                return [false, "UsuarioNK_Login::LoginCheck() - Error: ".$mysql->error];
            };
            $mysql->close();
            //---------si esta vacio devolver falseo de lo contraro crear los datos de la sesion
            if(empty($buffUsuarioTokenID)) {
                return [false, $buffStd];
            }
            //--------------------------------
            $tokenFecha=new DateTime($buffFechaExpiracion, new DateTimeZone("America/Bogota"));
            if($now>$tokenFecha) {
                return [false, $buffStd, "La conexion ya expiro"];
            }
            //--------------------------------
            $_SESSION["UsuarioNK"]=$buffUsuarioID;
            $_SESSION["UsuarioNombres"]=$buffUsuarioNombre;
            $_SESSION["UsuarioFoto"]="";
            if(!empty($buffUsuarioFoto)) {
                $_SESSION["UsuarioFoto"]="Media/Usuarios/".$buffUsuarioID."/".$buffUsuarioFoto;
            };            
            $_SESSION["UsuarioKeyJX"]=$buffUsuarioTokenID;
            $_SESSION["UsuarioFechaExpiracion"] = $buffFechaExpiracion;
            $_SESSION["UsuarioAdministrador"]=boolval($buffEsAdministrador);
        }
        //---------Revisar la sesion y ver si los datos del key son compatibles con el actual o buscar la informacion en la DB
        if($KeyJX!=$_SESSION["UsuarioKeyJX"]) {
            return [false, $buffStd];
        }
        $buffStd->Usuario_Nombre=$_SESSION["UsuarioNombres"];
        $buffStd->Usuario_Foto=$_SESSION["UsuarioFoto"];
        $buffStd->Fecha_Conexion=$_SESSION["UsuarioFechaExpiracion"];
        $buffStd->Fecha_Expiracion=$_SESSION["UsuarioFechaExpiracion"];
        $buffStd->Activo=true;
        return [true, $buffStd];
    }

    public static function Logout() {
        if(!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION["UsuarioNK"]);
        unset($_SESSION["UsuarioNombres"]);
        unset($_SESSION["UsuarioFoto"]);
        unset($_SESSION["UsuarioKeyJX"]);
        unset($_SESSION["UsuarioFechaExpiracion"]);
        unset($_SESSION["UsuarioColaborador"]);
        unset($_SESSION["UsuarioAdministrador"]);
        unset($_SESSION["FavoritosSessionIDS"]);
        unset($_SESSION["CarritoSessionIDS"]);
        // unset($_SESSION["PersonaConexion"]);
        return [true];
    }


}


class UsuarioNK_TokenAdmin {
    // TokenTipo
    // [
    //      "UsuarioNew"
    // ]

    public static function TokenAdmin_New(string $TokenTipo, string $UsuarioCorreo) {
        if(empty($TokenTipo) || empty($UsuarioCorreo)) {
            return [false, "Valores Necesarios Vacios"];
        }
        $UsuarioID_Stat=UsuariosNK_Getters::GetFromCorreo($UsuarioCorreo);
        if(!$UsuarioID_Stat[0]) {
            return $UsuarioID_Stat;
        }
        $UsuarioID=$UsuarioID_Stat[1]->UsuarioID;
        $UsuarioCorreo=$UsuarioID_Stat[1]->Correo;
        $UsuarioNombres=$UsuarioID_Stat[1]->Nombres." ".$UsuarioID_Stat[1]->Apellidos;
        //-------------------------
        $fechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
        $fechaNow->modify("+5 hours");
        $TokenExpiracion=$fechaNow->format("Y-m-d H:i:s");
        $TokenID=hash("sha256", $UsuarioID.$TokenExpiracion);
        //-------------------------
        $tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
        $mysql=UsuarioNK_Conexion::getConexion();
        $query="INSERT INTO $tabla (UsuarioTokenAdminID, UsuarioID, Usuario_Nombre, Usuario_Correo, Token_Tipo, Fecha_Expiracion) VALUES (?, ?, ?, ?, ?, ?)";
        if($stmt=$mysql->prepare($query)) {
            $stmt->bind_param("ssssss", $TokenID, $UsuarioID, $UsuarioNombres, $UsuarioCorreo, $TokenTipo, $TokenExpiracion);
            if(!$stmt->execute()) {
                return [false, "UsuarioNK::TokenAdmin_New() - Error: ".$mysql->error];
            }
            $stmt->close();
        } else {
            return [false, "UsuarioNK::TokenAdmin_New() - Error: ".$mysql->error];
        };
        $mysql->close();
        //-------------------------
        return [true, $TokenID];
    }

    public static function Get_All($dirRaiz)	{
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
        $sql="SELECT UsuarioTokenAdminID, UsuarioID, Usuario_Nombre, Usuario_Correo, Token_Tipo, Fecha_Expiracion FROM $tabla ORDER BY Usuario_Nombre ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$this->UsuarioID_In);
			$stmt->execute();
			// -----------------
            $buffUsuarioTokenAdminID="";
            $buffUsuarioID="";
            $buffNombres="";
            $buffCorreo="";
            $buffTokenTipo="";
            $buffFechaExpiracion="";
			$stmt->bind_result(
				$buffUsuarioTokenAdminID,
                $buffUsuarioID,
                $buffNombres,
                $buffCorreo,
                $buffTokenTipo,
                $buffFechaExpiracion
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->TokenAdminID=$buffUsuarioTokenAdminID;
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Correo=$buffCorreo;
                $buffStd->TokenTipo=$buffTokenTipo;
                $buffStd->Fecha_Expiracion=$buffFechaExpiracion;
                array_push($Resultados, $buffStd);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT UsuarioNK_TokenAdmin::Get_All() - "];
		}
		$mysqli->close();
		//-------------------
		return [true, $Resultados];
	}
    
    public static function Get_ID($TokenAdminID) {
        if(empty($TokenAdminID)) {
            return [false, "Sin info de Token"];
        }
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
        $sql="SELECT UsuarioTokenAdminID, UsuarioID, Usuario_Nombre, Usuario_Correo, Token_Tipo, Fecha_Expiracion FROM $tabla WHERE UsuarioTokenAdminID=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$TokenAdminID);
			$stmt->execute();
			// -----------------
            $buffUsuarioTokenAdminID="";
            $buffUsuarioID="";
            $buffNombres="";
            $buffCorreo="";
            $buffTokenTipo="";
            $buffFechaExpiracion="";
			$stmt->bind_result(
				$buffUsuarioTokenAdminID,
                $buffUsuarioID,
                $buffNombres,
                $buffCorreo,
                $buffTokenTipo,
                $buffFechaExpiracion
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->TokenAdminID=$buffUsuarioTokenAdminID;
                $buffStd->UsuarioID=$buffUsuarioID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Correo=$buffCorreo;
                $buffStd->TokenTipo=$buffTokenTipo;
                $buffStd->Fecha_Expiracion=$buffFechaExpiracion;
                array_push($Resultados, $buffStd);
            }
			
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT UsuarioNK_TokenAdmin::Get_All() - "];
		}
		$mysqli->close();
		//-------------------
        if(count($Resultados)<1) {
            return [false,"Sin resultados"];
        }
		return [true, $Resultados[0]];
	}

    public static function TokenAdmin_Del($TokenAdminID) {
        if(empty($TokenAdminID)) {
            return [false, "Sin info de Token"];
        }
		$mysqli=UsuarioNK_Conexion::getConexion();
		$tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
        $sql="DELETE FROM $tabla WHERE UsuarioTokenAdminID=?";
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$TokenAdminID);
			if(!$stmt->execute()) {
                return [false,"Error en la ejecucion de la constulta a eliminar"];
            };
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT UsuarioNK_TokenAdmin::Get_All() - "];
		}
		$mysqli->close();
		return [true];
	}
}


class UsuariosNK_Fix {
    
    public static $queryTablaUsuariosNK="CREATE TABLE IF NOT EXISTS UsuariosNK (
        UsuarioID varchar(255) not null primary key,
        Nombres varchar(255) not null,
        Apellidos varchar(255) not null,
        Alias varchar(255),
        Descripcion varchar(255),
        Cargo varchar(255),
        Sexo varchar(255),
        PerfilFotoH varchar(255),
        PerfilFotoM varchar(255),
        PerfilFotoS varchar(255),
        PerfilFotoT varchar(255),
        Fecha_Nacimiento date,
        Fecha_Registro datetime not null
    )";

    public static $queryTablaUsuariosLoginNK="CREATE TABLE IF NOT EXISTS UsuariosNK_Login (
        UsuarioID varchar(255) not null primary key,
        Correo varchar(255) not null unique,
        Contrasenia varchar(255) not null COLLATE utf8_bin,
        Nick varchar(255) not null COLLATE utf8_bin,
        Verificado int(11) not null default 0,
        Habilitado int(11) not null default 1,
        EsAdministrador int(11) not null default 0,
        FOREIGN KEY (UsuarioID) REFERENCES UsuariosNK(UsuarioID)
    )";
    
    public static $queryTablaUsuariosTokenNK="CREATE TABLE IF NOT EXISTS UsuariosNK_Token (
        UsuarioTokenID varchar(255) not null primary key,
        UsuarioID varchar(255) not null,
        UsuarioNombre varchar(255) not null,
        Fecha_Conexion datetime not null,
        Fecha_Expiracion datetime not null,
        Estado_Activo tinyint default 1,
        Fecha_Registro datetime not null,
        FOREIGN KEY (UsuarioID) REFERENCES UsuariosNK(UsuarioID)
    )";

    public static $queryTablaUsuariosTokenAdminNK="CREATE TABLE IF NOT EXISTS UsuariosNK_TokenAdmin (
        UsuarioTokenAdminID varchar(255) primary key not null,
        UsuarioID varchar(255) not null,
        Usuario_Nombre varchar(255) not null,
        Usuario_Correo varchar(255) not null,
        Token_Tipo varchar (255) not null,
        Fecha_Expiracion datetime not null,
        FOREIGN KEY (UsuarioID) REFERENCES UsuariosNK(UsuarioID)
    )";

    public static function TablaCrear_UsuariosNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(UsuariosNK_Fix::$queryTablaUsuariosNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_UsuariosNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_UsuariosNK: ".$mysql->error];
        }
        $mysql->close();
        return [true, "TablaUsuarios Creado Correctamente"];
    }

    public static function TablaEliminar_UsuariosNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuarios;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_UsuariosNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_UsuariosNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaCrear_UsuariosLoginNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(UsuariosNK_Fix::$queryTablaUsuariosLoginNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_UsuariosLoginNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_UsuariosLoginNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_UsuariosLoginNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuarioslogin;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_UsuariosLoginNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_UsuariosLoginNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaCrear_UsuariosTokenNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(UsuariosNK_Fix::$queryTablaUsuariosTokenNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_UsuariosTokenNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_UsuariosTokenNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_UsuariosTokenNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuariostoken;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_UsuariosTokenNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_UsuariosTokenNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaCrear_UsuariosTokenAdminNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(UsuariosNK_Fix::$queryTablaUsuariosTokenAdminNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_UsuariosTokenAdminNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_UsuariosTokenAdminNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_UsuariosTokenAdminNK() {
        $mysql=UsuarioNK_Conexion::getConexion();
        $tabla=UsuarioNK_Conexion::$tabla_usuariostokenadmin;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_UsuariosTokenAdminNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_UsuariosTokenAdminNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }


    public static function TablasAll_Crear() {
		UsuariosNK_Fix::TablaCrear_UsuariosNK();
		UsuariosNK_Fix::TablaCrear_UsuariosLoginNK();
		UsuariosNK_Fix::TablaCrear_UsuariosTokenNK();
		UsuariosNK_Fix::TablaCrear_UsuariosTokenAdminNK();
	}

}





?>