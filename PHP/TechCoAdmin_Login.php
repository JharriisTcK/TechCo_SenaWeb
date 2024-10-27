<?php
class NeoKiriAdmin_Conexion {
    
    public static $TablaNeoKiriTokensAdmin="NeoKiri_TokenAdmin";
    
    public static function getConexionDB() {
        $mysql=new MySQLi("localhost",NK_USER, NK_PASS);
        if($mysql->connect_errno) {
            return "Error Conexion: ".$mysql->connect_error;
        }
        if(!$mysql->select_db(DBNAME_MAIN)) {
            return "no se encuentra base de datos: ".$mysql->error;
        };
        $mysql->set_charset("utf8");
        return $mysql;
    }
}

class NeoKiriAdmin_Login {
    
    public static function Login($UsuarioID, $dirRaiz) {
        NeoKiriAdmin_Login::Logout();

        $UsuarioObj=new UsuarioNK($UsuarioID, $dirRaiz);
        if(empty($UsuarioObj->UsuarioID)) {
            return [false, "NO se encontro usuario"];
        }
        if(!$UsuarioObj->EsAdministrador) {
            return [false, "Este usuario no es administrador"];
        }
        $Fecha_Now=new DateTime("now", new DateTimeZone("America/Bogota"));
        $Fecha_NowID=$Fecha_Now->format("YmdHis");
        $Fecha_NowText=$Fecha_Now->format("Y-m-d H:i:s");

        $doceHoras=new DateTime("now", new DateTimeZone("America/Bogota"));
        $doceHoras->modify("+8 hours");
        $doceHorasText=$doceHoras->format("Y-m-d H:i:s");

        $HASH=password_hash($Fecha_NowID.$UsuarioObj->Nombres.$UsuarioObj->Apellidos."AdminNK", PASSWORD_DEFAULT);
        //-------------
        $sql=NeoKiriAdmin_Conexion::getConexionDB();
        $tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $query="INSERT INTO $tabla (NeoKiriTokenAdminID, UsuarioID, Nombre, Fecha_Registro, Fecha_Expiracion) VALUES (?, ?, ?, ?, ?)";
        $buffUsuarioNombre=$UsuarioObj->Nombres." ".$UsuarioObj->Apellidos;
        if($stmt=$sql->prepare($query)) {
            $stmt->bind_param("sssss", $HASH, $UsuarioObj->UsuarioID, $buffUsuarioNombre, $Fecha_NowText, $doceHorasText);
            if(!$stmt->execute()) {
                return [false, $sql->error];
            }            
            $stmt->close();
        } else {
            return [false, $sql->error];
        }
        $sql->close();
        //-------------
        $_SESSION["AdminKeyJX"]=$HASH;
        return [true, $HASH];
    }

    public static function LoginCheck($KeyJX) {
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
        $FechaNow_Text=$FechaNow->format("Y-m-d H:i:s");

        $mysql=NeoKiriAdmin_Conexion::getConexionDB();
        $tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $sql="SELECT UsuarioID, Nombre FROM $tabla WHERE NeoKiriTokenAdminID=? AND Fecha_Expiracion<=?";
        
        if($stmt=$mysql->prepare($sql)) {
            $stmt->bind_param("ss", $KeyJX, $FechaNow_Text);
            $buffUsuarioID="";
            $buffUsuarioNombre="";
            $stmt->bind_result(
                $buffUsuarioID,
                $buffUsuarioNombre
            );
            if(!$stmt->execute()) {
                return [false, "NeoKiriAdmin_Login::LoginCheck() - Error: ".$mysql->error];
            }
            $stmt->fetch();
        } else {
            return [false, "UsuarioNK_Login::LoginCheck() - Error: ".$mysql->error];
        };
        $mysql->close();
        //---------si esta vacio devolver falseo de lo contraro crear los datos de la sesion
        if(empty($buffUsuarioID)) {
            return [false, "No se encontro token"];
        }
        //--------------------------------
        $_SESSION["AdminKeyJX"]=$KeyJX;
        //---------Revisar la sesion y ver si los datos del key son compatibles con el actual o buscar la informacion en la DB
        return [true, $KeyJX];
    }

    public static function Logout() {
        if(!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION["AdminKeyJX"]);
        return [true];
    }

    public static function Get_All($dirRaiz)	{
		$mysqli=NeoKiriAdmin_Conexion::getConexionDB();
		$tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $sql="SELECT NeoKiriTokenAdminID, Token_Tipo, ID, Nombre, Correo, Habilitado, Fecha_Expiracion FROM $tabla ORDER BY Nombre ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$this->ColaboradorID_In);
			$stmt->execute();
			// -----------------
            $buffTokenAdminID="";
            $buffTokenTipo="";
            $buffID="";
            $buffNombres="";
            $buffCorreo="";
            $buffHabilitado="";
            $buffFechaExpiracion="";
			$stmt->bind_result(
				$buffTokenAdminID,
                $buffTokenTipo,
				$buffID,
                $buffNombres,
                $buffCorreo,
                $buffHabilitado,
                $buffFechaExpiracion
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->TokenAdminID=$buffTokenAdminID;
                $buffStd->TokenTipo=$buffTokenTipo;
                $buffStd->ID=$buffID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Habilitado=$buffHabilitado;
                $buffStd->Fecha_Expiracion=$buffFechaExpiracion;
                array_push($Resultados, $buffStd);
            }
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"NeoKiriWeb_TokenAdmin::Get_All() - "];
		}
		$mysqli->close();
		//-------------------
		return [true, $Resultados];
	}
	
    public static function Get_ID($TokenID, $dirRaiz)	{
		if (empty($TokenID)) {
			return [true, "Sin Token"];
		}
		// -----------------
		$mysqli=NeoKiriAdmin_Conexion::getConexionDB();
		$tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $sql="SELECT NeoKiriTokenAdminID, Token_Tipo, ID, Nombre, Correo, Habilitado, Fecha_Expiracion FROM $tabla WHERE NeoKiriTokenAdminID=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$TokenID);
			$stmt->execute();
			// -----------------
            $buffTokenAdminID="";
            $buffTokenTipo="";
            $buffID="";
            $buffNombres="";
            $buffCorreo="";
            $buffHabilitado="";
            $buffFechaExpiracion="";
			$stmt->bind_result(
				$buffTokenAdminID,
                $buffTokenTipo,
				$buffID,
                $buffNombres,
                $buffCorreo,
                $buffHabilitado,
                $buffFechaExpiracion
			);
            while ($stmt->fetch()) {
                $buffStd=new stdClass();
                $buffStd->TokenAdminID=$buffTokenAdminID;
                $buffStd->TokenTipo=$buffTokenTipo;
                $buffStd->ID=$buffID;
                $buffStd->Nombres=$buffNombres;
                $buffStd->Correo=$buffCorreo;
                $buffStd->Habilitado=$buffHabilitado;
                $buffStd->Fecha_Expiracion=$buffFechaExpiracion;
                array_push($Resultados, $buffStd);
            }
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"NeoKiriWeb_TokenAdmin::Get_All() - "];
		}
		$mysqli->close();
		//-------------------
		if(count($Resultados)<1) {
			return [false,"Sin Resultado de Token"];
		} else {
			return [true, $Resultados[0]];
		}
		//-------------------
	}

    public static function TokenAdmin_Del($TokenAdminID) {
        if(empty($TokenAdminID)) {
            return [false, "Sin info de Token"];
        }
		$mysqli=NeoKiriAdmin_Conexion::getConexionDB();
		$tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $sql="DELETE FROM $tabla WHERE NeoKiriTokenAdminID=?";
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$TokenAdminID);
			if(!$stmt->execute()) {
                return [false,"Error en la ejecucion de la constulta a eliminar"];
            };
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"NeoKiriWeb::TokenAdmin_Del() - "];
		}
		$mysqli->close();
		return [true, $TokenAdminID];
	}
}

class neoKiriAdmin_Login_Fix {
    public static $queryTablaNeoKiri_TokenAdminNK="CREATE TABLE IF NOT EXISTS NeoKiri_TokenAdmin (
		NeoKiriTokenAdminID varchar(255) primary key not null,
		UsuarioID varchar(255) not null,
		Nombre varchar(255) not null,
		Fecha_Registro datetime not null,
        Fecha_Expiracion datetime not null
	)";

    public static function TablaCrear_TokenAdmin() {
        $mysql=NeoKiriAdmin_Conexion::getConexionDB();
        // ---------------
        if($stmt=$mysql->prepare(neoKiriAdmin_Login_Fix::$queryTablaNeoKiri_TokenAdminNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_TokenAdmin: ".$mysql->error];
            };
            $stmt->close();
        } else {
            $mysql->close();
            return [false, "TablaCrear_TokenAdmin: ".$mysql->error];
        }
        // ---------------
        $mysql->close();
        $tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        return [true, "Tabla $tabla - > Creando > OK"];
    }

    public static function TablaEliminar_TokenAdmin() {
        $mysql=NeoKiriAdmin_Conexion::getConexionDB();
        // ---------------
        $tabla=NeoKiriAdmin_Conexion::$TablaNeoKiriTokensAdmin;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
        }
        $mysql->close();
        return [true, "Tabla $tabla - Eliminado correctamente"];
    }

    public static function TablaAllCrear() {
        neoKiriAdmin_Login_Fix::TablaCrear_TokenAdmin();
    }
}

?>