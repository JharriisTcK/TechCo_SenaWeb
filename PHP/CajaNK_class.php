<?php

class CajaNK_Conexion {
    public static $tabla_productos="CajaNK";
    public static $tabla_cotizacionventa="CajaNK_CotizacionVentas";
    public static $tabla_ventas="CajaNK_Ventas";

	public static $folder_cajas="Media/Caja/";

	public static $sqlite_cajas="Media/Caja/CajaNK.sqlite3";

	public static function getConexion() {
		$mysqli=new MySQLi("localhost", NK_USER, NK_PASS);

		if($mysqli->connect_errno)		{
			echo "Error Conexion: ".$mysqli->connect_error;
			exit();
		}

		if(!$mysqli->select_db(DBNAME_MAIN)) {
			echo "no se encuentra base de datos: ".$mysqli->error ;
			exit();
		};

		$mysqli->set_charset("utf8");
		return $mysqli;
	}
}

class CajaNK_CotizacionVenta {

    public $CotizacionVentaID_in="";
    
    public $CotizacionVentaID="";
    public $ClienteTelefono="";
    public $ClienteCorreo="";
    public $ClienteTipo="";
    
    public $PersonaNombres="";
    public $PersonaIdentificacionTipo="";
    public $PersonaIdentificacion="";
    
    public $EmpresaRazonSocial="";
    public $EmpresaNIT="";
    public $EmpresaNITDV="";
    
    public $ClienteTelefono2="";
    public $ClienteDireccion="";
    
    public $ItemsJSON="";
    public $Items="";
    
    public $PrecioTotalBase="";
    public $PrecioTotalIva="";
    public $PrecioTotal="";
    
    public $PagoTipo="";
    public $PagoBanco="";
    public $PagoConfirmado="";
    
    public $UsuarioID="";
    public $EmpleadoID="";
    public $FechaRegistro="";
    public $FechaExpiracion="";

    public $VentaEstado=0;
    public $VentaObservaciones="";
    
    public $dirRaiz="";
    public $folderCotizacion="";
    public $dirSqliteCotizacion="";

    public function __construct($CotizacionVentaID, $dirRaiz) {
        $this->CotizacionVentaID_in=$CotizacionVentaID;
        $this->dirRaiz=$dirRaiz;
        $this->InfoGet();
    }

    public function InfoGet() {
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $query="SELECT CajaNK_CotizacionVentaID, ClienteTelefono, ClienteCorreo, ClienteTipo, PersonaNombres, PersonaIdentificacionTipo, PersonaIdentificacion, EmpresaRazonSocial, EmpresaNIT, EmpresaNITDV, ClienteTelefono2, ClienteDireccion, ItemsJSON, PrecioTotalBase, PrecioTotalIVA, PrecioTotal, PagoTipo, PagoBanco, PagoConfirmado, UsuarioID, EmpleadoID, VentaEstado, VentaObservaciones, Fecha_Registro, Fecha_Expiracion FROM $tabla WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("s", $this->CotizacionVentaID_in);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->bind_result(
            $this->CotizacionVentaID,
            $this->ClienteTelefono,
            $this->ClienteCorreo,
            $this->ClienteTipo,
            $this->PersonaNombres,
            $this->PersonaIdentificacionTipo,
            $this->PersonaIdentificacion,
            $this->EmpresaRazonSocial,
            $this->EmpresaNIT,
            $this->EmpresaNITDV,
            $this->ClienteTelefono2,
            $this->ClienteDireccion,
            $this->ItemsJSON,
            $this->PrecioTotalBase,
            $this->PrecioTotalIva,
            $this->PrecioTotal,
            $this->PagoTipo,
            $this->PagoBanco,
            $this->PagoConfirmado,
            $this->UsuarioID,
            $this->EmpleadoID,
            $this->VentaEstado,
            $this->VentaObservaciones,
            $this->FechaRegistro,
            $this->FechaExpiracion
        );
        $stmt->fetch();
        $stmt->close();
        $mysqli->close();
        $this->Items=json_decode($this->ItemsJSON);
        // -----------------
        if(empty($this->CotizacionVentaID)) {
            return [false, "No se encontro cotizacion"];    
        }
        // -----------------
        $Fecha_Obj=new DateTime($this->FechaRegistro, new DateTimeZone("America/Bogota"));
        $this->folderCotizacion=CajaNK_Conexion::$folder_cajas.$this->CotizacionVentaID."/";
    }
    
    public function ContactoInfo_Set($Telefono, $Correo, $Direccion, $Telefono2, $PagoTipo) {
        if(empty($this->CotizacionVentaID)) {
            return [false, "Sin ID Cotizacion"];
        }
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $query="UPDATE $tabla SET ClienteTelefono=?, ClienteCorreo=?, ClienteDireccion=?, ClienteTelefono2=?, PagoTipo=? WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("ississ", $Telefono, $Correo, $Direccion, $Telefono2, $PagoTipo, $this->CotizacionVentaID);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->close();
        $mysqli->close();
        return [true, "Informacion de contacto de cliente modificado"];
    }
    
    public function PersonaInfo_Set($Nombres, $IdentificacionTipo, $Identificacion) {
        if(empty($this->CotizacionVentaID)) {
            return [false, "Sin ID Cotizacion"];
        }
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $ClienteTipo="Persona Natural";
        $query="UPDATE $tabla SET ClienteTipo=?, PersonaNombres=?, PersonaIdentificacionTipo=?, PersonaIdentificacion=? WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("sssis", $ClienteTipo, $Nombres, $IdentificacionTipo, $Identificacion, $this->CotizacionVentaID);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->close();
        $mysqli->close();
        return [true, "Informacion de persona modificado"];
    }
    
    public function EmpresaInfo_Set($RazonSocial, $NIT, $NITDV) {
        if(empty($this->CotizacionVentaID)) {
            return [false, "Sin ID Cotizacion"];
        }
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $ClienteTipo="Persona Juridica";
        $query="UPDATE $tabla SET ClienteTipo=?, EmpresaRazonSocial=?, EmpresaNIT=?, EmpresaNITDV=? WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("ssiis", $ClienteTipo, $RazonSocial, $NIT, $NITDV, $this->CotizacionVentaID);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->close();
        $mysqli->close();
        return [true, "Informacion de empresa modificado"];
    }
    
    public function Precios_Set($Items, $PrecioItems, $PrecioIVA, $PrecioTotal) {
        if(empty($this->CotizacionVentaID)) {
            return [false, "Sin ID Cotizacion"];
        }
        $Items=json_encode($Items);
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $query="UPDATE $tabla SET ItemsJSON=?, PrecioTotalBase=?, PrecioTotalIVA=?, PrecioTotal=? WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("siiis", $Items, $PrecioItems, $PrecioIVA, $PrecioTotal, $this->CotizacionVentaID);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->close();
        $mysqli->close();
        return [true, "Informacion Precios modificado correctamente"];
    }

    public function VentaEstadoSet($Estado, $Observaciones) {
        if(empty($this->CotizacionVentaID)) {
            return [false, "Sin ID Cotizacion"];
        }
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $query="UPDATE $tabla SET VentaEstado=?, VentaObservaciones=? WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        $stmt->bind_param("iss", $Estado, $Observaciones, $this->CotizacionVentaID);
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $stmt->close();
        $mysqli->close();
        return [true, "Informacion de estado modificado"];
    }

    public static function CotizacionVenta_New($Telefono, $Correo, $UsuarioID, $dirRaiz) {	
		//--------------------------
		//Comprobar datos vacios
		if(empty($Telefono) || empty($Correo) || empty($UsuarioID)) {
			return [false, "Faltan datos necesarios para realizar la cotizacion"];
		}
		//--------------------------
		//Preparar datos
		//Año/Mes/Dia/Hora Militar/Minuto/Segundo/
        $FechaRegistro=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fecha_registroid=$FechaRegistro->format("YmdHis");
		$fecha_registro_sql=$FechaRegistro->format("Y-m-d H:i:s");
		$fecha_anomes=$FechaRegistro->format("Ym");
		//--------------------------
        // Preparar expiracion
        $FechaExpiracion=clone $FechaRegistro;
        $FechaExpiracion->modify("+8 hours");
        $FechaExpiracionSQL=$FechaExpiracion->format("Y-m-d H:i:s");
		//--------------------------
		//Generar Un Identificador de PublicacionNK
		$CotizacionVentaID="ccv".$fecha_anomes.hash("sha256", $Telefono.$Correo.$fecha_registroid);
		$newID=neoKiri::GenerarRandomID(4, "CajaCotizacionVenta_");
		//--------------------------
		//Generar Consulta SQL para agregar a la DB
		$mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
		// $nickDir=neoKiri::encodeTitulo2Link($Nombre)."-".$ProductoIDAle;
		$sql="INSERT INTO $tabla (CajaNK_CotizacionVentaID, ClienteTelefono, ClienteCorreo, UsuarioID, Fecha_Registro, Fecha_Expiracion) VALUES (?, ?, ?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss",$CotizacionVentaID, $Telefono, $Correo, $UsuarioID, $fecha_registro_sql, $FechaExpiracionSQL);
			if(!$stmt->execute()) {
				return [false, "CotizacionVenta:New-".$mysqli->error];
			};
			$stmt->close();
		} else {
			return [false, "CotizacionVenta:New-".$mysqli->error];
		}
		$mysqli->close();
		//--Crear carpetas
		$folderCotizacion=$dirRaiz.CajaNK_Conexion::$folder_cajas.$CotizacionVentaID."/";
		if(!is_dir($folderCotizacion)) {
			mkdir($folderCotizacion,0755,true);
		}
		return [true, $CotizacionVentaID];
	}

    public function VerificarPago() {
        //Obtener IDS Productos de Cotizacion
        if(empty($this->CotizacionVentaID)) {
            return [false, "No se encontro ID de producto"];
        }
        if($this->PagoConfirmado) {
            return [false, "Esta venta ya ha sido confirmada"];
        }
        if(!is_array($this->Items)) {
            return [false, "No es un array de productos"];
        }
        if(count($this->Items)<1) {
            return [false, "No hay productos disponibles"];
        }
        $IDS_Productos_Cotizacion=[];
        foreach($this->Items as $Item) {
            array_push($IDS_Productos_Cotizacion, $Item->ProductoID);
        }
        //Obtener Productos de IDs
        $Productos=ProductosNK_Getters::FromIDS($IDS_Productos_Cotizacion);
        if(!$Productos[0]) {
            return [false, "No se encontraron items"];
        }
        $Productos=$Productos[1];
        //Bucle para restar items disponibles
        $ProductosRestarDisponibles=[];
        foreach($this->Items as $Item) {
            foreach($Productos as $Producto) {
                if($Item->ProductoID!=$Producto->ProductoID) {
                    continue;
                }
                $buffProducto=new stdClass();
                $buffProducto->ProductoID=$Producto->ProductoID;
                $buffProducto->CantidadNueva=$Producto->Disponibles - $Item->Cantidad;
                array_push($ProductosRestarDisponibles, $buffProducto);
            }
        }
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
        $FechaNowStr=$FechaNow->format("YmdHis");
        $FechaNowSql=$FechaNow->format("Y-m-d H:i:s");
        
        //base de datos
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        //Cambiar valor cotizacion por verificado 1
        $query="UPDATE $tabla SET PagoConfirmado=1 WHERE CajaNK_CotizacionVentaID=?";
        if(!$stmt=$mysqli->prepare($query)) {
            return [false, "No se preparo la consulta"];
        }
        $stmt->bind_param("s", $this->CotizacionVentaID);
        $stmt->execute();
        $stmt->close();
        $mysqli->close();

        //Bucle restar items DB Productos
        $conexion2=ProductosNK_Conexion::getConexion();
        $tabla2=ProductosNK_Conexion::$tabla_productos;
        $query2="UPDATE $tabla2 SET Disponibles=? WHERE ProductoID=?";
        if(!$stmt2=$conexion2->prepare($query2)) {
            return [false, "No se preparo la segunda consulta"];
        }
        foreach ($ProductosRestarDisponibles as $ProductoRestar) {
            $stmt2->bind_param("is", $ProductoRestar->CantidadNueva, $ProductoRestar->ProductoID);
            $stmt2->execute();
        }
        $stmt2->close();
        $conexion2->close();

        // Crear codigos CUFE de Pago Confirmado
        $newCufe=hash("sha256", $this->CotizacionVentaID);
        $newNumeracion="";
        $newResolucion="";

        return [true, $ProductosRestarDisponibles];
    }
}

class CajaNK_Getters {
    public static function CotizacionesGet($SoloPendientes=true) {
        $mysqli=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $query="SELECT CajaNK_CotizacionVentaID, ClienteTelefono, ClienteCorreo, ClienteTipo, ItemsJSON, PrecioTotalBase, PrecioTotalIVA, PrecioTotal, PagoConfirmado, Fecha_Registro, Fecha_Expiracion FROM $tabla ORDER BY PagoConfirmado ASC, Fecha_Registro ASC";
        $resultados=[];
        if(!$stmt=$mysqli->prepare($query)) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];
        }
        if(!$stmt->execute()) {
            $error=$mysqli->error;
            $mysqli->close();
            return [false, $error];    
        };
        $buffCajaCotizacionVentaID="";
        $buffClienteTelefono="";
        $buffClienteCorreo="";
        $buffClienteTipo="";
        $buffItemsJSON="";
        $buffPrecioTotalBase="";
        $buffPrecioTotalIVA="";
        $buffPrecioTotal="";
        $buffPagoConfirmado="";
        $buffFechaRegistro="";
        $buffFechaExpiracion="";
        $stmt->bind_result(
            $buffCajaCotizacionVentaID,
            $buffClienteTelefono,
            $buffClienteCorreo,
            $buffClienteTipo,
            $buffItemsJSON,
            $buffPrecioTotalBase,
            $buffPrecioTotalIVA,
            $buffPrecioTotal,
            $buffPagoConfirmado,
            $buffFechaRegistro,
            $buffFechaExpiracion
        );
        while ($stmt->fetch()) {
            $buffStd=new stdClass();
            $buffStd->CotizacionVentaID=$buffCajaCotizacionVentaID;
            $buffStd->ClienteTelefono=$buffClienteTelefono;
            $buffStd->ClienteCorreo=$buffClienteCorreo;
            $buffStd->ClienteTipo=$buffClienteTipo;
            $buffStd->Items=$buffItemsJSON;
            $buffStd->PrecioTotalBase=$buffPrecioTotalBase;
            $buffStd->PrecioTotalIVA=$buffPrecioTotalIVA;
            $buffStd->PrecioTotal=$buffPrecioTotal;
            $buffStd->PagoConfirmado=$buffPagoConfirmado;
            $buffStd->FechaRegistro=$buffFechaRegistro;
            $buffStd->FechaExpiracion=$buffFechaExpiracion;
            array_push($resultados, $buffStd);
        }
        $stmt->close();
        $mysqli->close();
        // -----------------
        foreach ($resultados as $resultado) {
            $Fecha_Obj=new DateTime($resultado->FechaRegistro, new DateTimeZone("America/Bogota"));
            $resultado->folderCotizacion=CajaNK_Conexion::$folder_cajas.$resultado->CotizacionVentaID."/";
            $resultado->Items=json_decode($resultado->Items);
            $resultado->Titulo=$resultado->Items[0]->Nombre;
            if(count($resultado->Items)>1) {
                $resultado->Titulo.=" y ".(count($resultado->Items)-1)." articulos más.";
            }
        }
        // -----------------
        return [true, $resultados];
    }
}

class CajaNK_Fix {

    public static $queryTablaCajaCotizacionVenta="CREATE TABLE IF NOT EXISTS CajaNK_CotizacionVentas (
        CajaNK_CotizacionVentaID varchar(255) not null primary key,
        ClienteTelefono varchar(255) not null,
        ClienteCorreo varchar(255),
        ClienteTipo varchar(255),
        PersonaNombres varchar(255),
        PersonaIdentificacionTipo varchar(255),
        PersonaIdentificacion double,
        EmpresaRazonSocial varchar(255),
        EmpresaNIT int(11),
        EmpresaNITDV int(11),
        ClienteTelefono2 varchar(255),
        ClienteDireccion varchar(255),
        ItemsJSON text,
        PrecioTotalBase double,
        PrecioTotalIVA double,
        PrecioTotal double,
        PagoTipo varchar(255),
        PagoBanco varchar(255),
        PagoConfirmado int(11) not null default 0,
        PagoVerificado int(11) not null default 0,
        UsuarioID varchar(255) not null default 'NeoKiriWeb',
        EmpleadoID varchar(255) not null default 'NeoKiriWeb',
        VentaEstado int(11) not null default 0,
        VentaObservaciones text,
        Fecha_Registro datetime not null,
        Fecha_Expiracion datetime not null
    )";

    public static $queryTablaCajaVentas="CREATE TABLE IF NOT EXISTS CajaNK_Ventas (
        CajaNK_VentaID varchar(255) not null primary key,
        CajaNK_CotizacionVentaID varchar(255) not null unique,
        Numeracion int(11) not null unique,
        CUFE varchar(255) not null unique,
        ResolucionNum int(11) not null,
        FilePDF varchar(255) not null,
        Referencia1 varchar(255),
        Referencia2 varchar(255), 
        Fecha_Registro datetime not null,
        FOREIGN KEY (CajaNK_CotizacionVentaID) REFERENCES CajaNK_CotizacionVentas(CajaNK_CotizacionVentaID)
    )";

    public static function TablaCrear_CajaCotizacionVentasNK() {
        $mysql=CajaNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(CajaNK_Fix::$queryTablaCajaCotizacionVenta)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_CajaCotizacionVentasNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_CajaCotizacionVentasNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_CajaCotizacionVentasNK() {
        $mysql=CajaNK_Conexion::getConexion();
        $tabla=CajaNK_Conexion::$tabla_cotizacionventa;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_CajaCotizacionVentasNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_CajaCotizacionVentasNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

	public static function TablaCrear_CajaVentasNK() {
        $mysql=CajaNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(CajaNK_Fix::$queryTablaCajaVentas)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_CajaVentasNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_CajaVentasNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_CajaVentasNK() {
        $mysql=CajaNK_Conexion::getConexion();
		$tabla=CajaNK_Conexion::$tabla_ventas;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_CajaVentasNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_CajaVentasNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablasAll_Crear($dirRaiz) {
        $folderMediaCaja=$dirRaiz."Media/Caja/";
		if(!is_dir($folderMediaCaja)) {
			mkdir($folderMediaCaja,0755,true);
		}
		CajaNK_Fix::TablaCrear_CajaCotizacionVentasNK();
		CajaNK_Fix::TablaCrear_CajaVentasNK();
	}

}

?>