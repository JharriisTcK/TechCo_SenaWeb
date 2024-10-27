<?php

class NeoKiriWeb {
    public $Nombre="TechCo - Tecnología e Innovación";
    public $Ciudad="San Luis - Antioquia, Colombia";
    public $Direccion="Carrera 15B";
    public $Telefono=3215303550;
	
    
	public static $TablaEmpresaInfo="NeoKiri_Info";
	public static $TablaEmpresaRedes="NeoKiri_Sedes";
	public static $TablaEmpresaSedes="NeoKiri_Redes";
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

class NeoKiriWeb_Fix {
	public static $queryTablaEmpresaInfo="CREATE TABLE IF NOT EXISTS NeoKiri_Info ( 
		NeoKiriID VARCHAR(255) PRIMARY KEY NOT NULL,
		Nombre VARCHAR(255) NOT NULL , 
		Descripcion VARCHAR (255) , 
		Contenido TEXT , 
		Mision TEXT , 
		Vision TEXT , 
		NIT int(11) default 0,
		Destacado TINYINT default 0,
		Visitas INT UNSIGNED DEFAULT 0, 
		Habilitado TINYINT DEFAULT 0, 
		LogoSVG varchar(255) ,
		LogoImgH varchar(255) ,
		LogoImgM varchar(255) ,
		LogoImgS varchar(255) ,
		LogoImgT varchar(255) ,
		LogoImgC varchar(255) ,
		LogoImgFB varchar(255),
		UserMaster varchar(255) not null,
		PassMaster varchar(255) not null,
		Fecha_Registro DATETIME not null , 
		Fecha_Modificacion DATETIME 
	)";

	public static $queryTablaEmpresaSedes="CREATE TABLE IF NOT EXISTS NeoKiri_Sedes (
		NeoKiriSedeID varchar (255) NOT NULL PRIMARY KEY, 
		NeoKiriID varchar (255) NOT NULL, 
		Pais VARCHAR(255), 
		Departamento VARCHAR(255), 
		Ciudad VARCHAR(255), 
		Direccion VARCHAR(255), 
		Foto VARCHAR(255), 
		Fecha_Registro DATETIME not null
	)";

	public static $queryTablaEmpresaRedes="CREATE TABLE IF NOT EXISTS NeoKiri_Redes (
		NeoKiriRedID varchar (255) NOT NULL PRIMARY KEY, 
		NeoKiriID varchar (255) NOT NULL, 
		Tipo VARCHAR(255), 
		Valor VARCHAR(255), 
		Fecha_Registro DATETIME not null,
		Habilitado TINYINT DEFAULT 1
	)";

	public static function FoldersMediaCrear($dirRaiz) {
		//--Crear carpetas
		$folderMedia=$dirRaiz."Media/";
		if(!is_dir($folderMedia)) {
			mkdir($folderMedia,0755,true);
		} else {
			chmod($folderMedia, 0755);
		}
		
		$folderMediaProductos=$dirRaiz."Media/Productos/";
		if(!is_dir($folderMediaProductos)) {
			mkdir($folderMediaProductos,0755,true);
		} else {
			chmod($folderMediaProductos, 0755);
		}

		$folderMediaProductosCategorias=$dirRaiz."Media/ProductosCategorias/";
		if(!is_dir($folderMediaProductosCategorias)) {
			mkdir($folderMediaProductosCategorias,0755,true);
		} else {
			chmod($folderMediaProductosCategorias, 0755);
		}

		$folderMediaProductosMarcas=$dirRaiz."Media/ProductosMarcas/";
		if(!is_dir($folderMediaProductosMarcas)) {
			mkdir($folderMediaProductosMarcas,0755,true);
		} else {
			chmod($folderMediaProductosMarcas, 0755);
		}
		
		$folderMediaUsuarios=$dirRaiz."Media/Usuarios/";
		if(!is_dir($folderMediaUsuarios)) {
			mkdir($folderMediaUsuarios,0755,true);
		} else {
			chmod($folderMediaUsuarios, 0755);
		}
	}

	public static function TablaCrear_NeoKiriWeb() {
		$mysql=NeoKiriWeb::getConexionDB();
		// ---------------
		if($stmt=$mysql->prepare(NeoKiriWeb_Fix::$queryTablaEmpresaInfo)) {
			if(!$stmt->execute()) {
				return [false, "TablaCrear_NeoKiri: ".$mysql->error];
			};
			$stmt->close();
		} else {
			return [false, "TablaCrear_NeoKiri: ".$mysql->error];
		}
		// ---------------
		if($stmt=$mysql->prepare(NeoKiriWeb_Fix::$queryTablaEmpresaSedes)) {
			if(!$stmt->execute()) {
				return [false, "TablaCrear_NeoKiri: ".$mysql->error];
			};
			$stmt->close();
		} else {
			return [false, "TablaCrear_NeoKiri: ".$mysql->error];
		}
		// ---------------
		if($stmt=$mysql->prepare(NeoKiriWeb_Fix::$queryTablaEmpresaRedes)) {
			if(!$stmt->execute()) {
				return [false, "TablaCrear_NeoKiri: ".$mysql->error];
			};
			$stmt->close();
		} else {
			return [false, "TablaCrear_NeoKiri: ".$mysql->error];
		}
		$mysql->close();
		return [true];
	}
	
	public static function TablaEliminar_PublicacionNK() {
		$mysql=NeoKiriWeb::getConexionDB();
		// ---------------
		$tabla=NeoKiriWeb::$TablaEmpresaInfo;
		$queryTabla="DROP TABLE $tabla";
		if($stmt=$mysql->prepare($queryTabla)) {
			if(!$stmt->execute()) {
				return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
			};
			$stmt->close();
		} else {
			return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
		}
		// ---------------
		$tabla=NeoKiriWeb::$TablaEmpresaSedes;
		$queryTabla="DROP TABLE $tabla";
		if($stmt=$mysql->prepare($queryTabla)) {
			if(!$stmt->execute()) {
				return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
			};
			$stmt->close();
		} else {
			return [false, "TablaEliminar_PublicacionNK: ".$mysql->error];
		}
		// ---------------
		$tabla=NeoKiriWeb::$TablaEmpresaRedes;
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
		return [true];
	}

	public static function TablaAllCrear() {
		NeoKiriWeb_Fix::TablaCrear_NeoKiriWeb();
	}
}

?>