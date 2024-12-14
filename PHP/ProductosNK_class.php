<?php

class ProductosNK_Conexion {
    public static $tabla_productos="ProductosNK";
	public static $tabla_productoscategorias="ProductosNK_Categorias";
	public static $tabla_productosmarcas="ProductosNK_Marcas";
	public static $tabla_productosfotos="ProductosNK_Fotos";

	public static $folder_productos="Media/Productos/";
	public static $folder_productoscategorias="Media/ProductosCategorias/";
	public static $folder_productosmarcas="Media/ProductosMarcas/";
	public static $sqlite_productos="Media/Productos/ProductosNK.sqlite3";

	public static $Categorias=[
		"General",
		"Procesadores",
		"Memorias RAM",
		"MotherBoard",
		"Discos HDD",
		"Discos SSD",
		"Discos M2",
		"Tarjetas Graficas",
		"Fuentes de Poder",
		"Mouse",
		"Teclado"
	];

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

class ProductoNK {

	private $ProductoID_in="";
	public $dirRaiz="";

	public $ProductoID=""; 
	public $ProductoNickDir="";
	public $ProductoCodeID="";
	public $CategoriaID="";
	public $MarcaID="";
	public $Nombre="";
	public $Descripcion="";
	public $Contenido="";
	public $CaracteristicasJSON="";
	public $PrecioDistribuidor="";
	public $PrecioFinal="";
	public $PrecioFinalOferta="";
	public $Disponibles="";
	public $PortadaH="";
	public $PortadaM="";
	public $PortadaS="";
	public $PortadaT="";
	public $PortadaC="";
	public $PortadaFB="";
	public $EnOferta="";
	public $Visitas="";
	public $Habilitado="";
	public $Fecha_Registro="";
	
	public $CategoriaNombre="";
	public $CategoriaNickDir="";

	public $Imagenes=[];
	public $Videos=[];
	
	public $link="";

	public $folder_producto="";
	public $sqlite_producto="";

	public function __construct($ProductoID, $dirRaiz) {
		$this->ProductoID_in=$ProductoID;
		$this->dirRaiz=$dirRaiz;
		$this->Info_Get();
	}

	public function Info_Get()	{
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$tablacategorias=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT p.ProductoID, p.ProductoNickDir, p.ProductoCodeID, p.ProductoCategoriaID, p.MarcaID, p.Nombre, p.Descripcion, p.Contenido, p.CaracteristicasJSON, p.PrecioDistribuidor, p.PrecioFinal, p.PrecioFinalOferta, p.Disponibles, p.PortadaH, p.PortadaM, p.PortadaS, p.PortadaT, p.PortadaC, p.PortadaFB, p.EnOferta, p.Visitas, p.Habilitado, p.Fecha_Registro, pc.Nombre, pc.NickDir FROM $tabla p INNER JOIN $tablacategorias pc ON p.ProductoCategoriaID=pc.ProductoCategoriaID WHERE p.ProductoID=? ORDER BY p.ProductoCategoriaID ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$this->ProductoID_in);
			$stmt->execute();
			// -----------------
			$stmt->bind_result(
				$this->ProductoID,
                $this->ProductoNickDir,
                $this->ProductoCodeID,
                $this->CategoriaID,
                $this->MarcaID,
                $this->Nombre,
                $this->Descripcion,
                $this->Contenido,
                $this->CaracteristicasJSON,
                $this->PrecioDistribuidor,
                $this->PrecioFinal,
                $this->PrecioFinalOferta,
                $this->Disponibles,
                $buffPortadaH,
                $buffPortadaM,
                $buffPortadaS,
                $buffPortadaT,
                $buffPortadaC,
                $buffPortadaFB,
                $this->EnOferta,
                $this->Visitas,
                $this->Habilitado,
                $this->Fecha_Registro,
				$this->CategoriaNombre,
				$this->CategoriaNickDir,
			);
			$stmt->fetch();
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		$Fecha_Obj=new DateTime($this->Fecha_Registro, new DateTimeZone("America/Bogota"));
		$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
		$this->folder_producto=ProductosNK_Conexion::$folder_productos.$this->ProductoID."/";
		$buffTituloLink=neoKiri::encodeTitulo2Link($this->Nombre);
		$this->link="Producto/".$this->ProductoNickDir;
		$this->sqlite_producto=$this->dirRaiz.$this->folder_producto.$this->ProductoID.".sqlite3";
		//-----------------------------
		if(!empty($buffPortadaH)) {
			$this->PortadaH=$this->folder_producto.$buffPortadaH;
			$this->PortadaM=$this->folder_producto.$buffPortadaM;
			$this->PortadaS=$this->folder_producto.$buffPortadaS;
			$this->PortadaT=$this->folder_producto.$buffPortadaT;
		}
		//------Portada Facebook
		if(!empty($buffPortadaFB)) {
			$this->PortadaFB=$this->folder_producto.$buffPortadaFB;
		}
		//-------------------
		return [true];
	}

	public function Info_Set($CategoriaID, $CodeID, $NickDir, $Nombre, $MarcaID, $Descripcion, $PrecioDistribuidor, $PrecioFinal, $PrecioFinalOferta, $Disponibles) {
		if(empty($this->ProductoID)) {
			return [false, "Sin id producto a editar"];
		}
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$query="UPDATE $tabla SET ProductoCategoriaID=?, ProductoCodeID=?, ProductoNickDir=?, Nombre=?,  MarcaID=?, Descripcion=?, PrecioDistribuidor=?, PrecioFinal=?, PrecioFinalOferta=?, Disponibles=? WHERE ProductoID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("ssssssiiiis", $CategoriaID, $CodeID, $NickDir, $Nombre, $MarcaID, $Descripcion, $PrecioDistribuidor, $PrecioFinal, $PrecioFinalOferta, $Disponibles, $this->ProductoID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
			return [false, $sql->error];
		}
		$sql->close();

		return [true];
	}

	public function Contenido_Set($Contenido) {
		if(empty($this->ProductoID)) {
			return [false, "Sin id producto a editar contenido"];
		}
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$query="UPDATE $tabla SET Contenido=? WHERE ProductoID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("ss", $Contenido, $this->ProductoID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
			return [false, $sql->error];
		}
		$sql->close();
		return [true];
	}

	//**********************************************
	public function PortadaImg_Upload($fileimg, $caption) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto"];
		}
		//--imagen Portada
		if(!is_uploaded_file($fileimg["tmp_name"])) {
			return [false, "Error PortadaImg_Upload: NO se encuentra el archivo subido:".$fileimg];
		}
		$filenamePortadaO=$this->ProductoID."_Portada_.png";
		$filenamePortadaH=$this->ProductoID."_PortadaH.webp";
		$filenamePortadaM=$this->ProductoID."_PortadaM.webp";
		$filenamePortadaS=$this->ProductoID."_PortadaS.webp";
		$filenamePortadaT=$this->ProductoID."_PortadaT.webp";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$sql="UPDATE $tabla SET PortadaH=?, PortadaM=?, PortadaS=?, PortadaT=?, PortadaC=? WHERE ProductoID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $caption, $this->ProductoID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		$dirFileO=$this->dirRaiz.$this->folder_producto.$filenamePortadaO;
		if(move_uploaded_file($fileimg["tmp_name"], $dirFileO)) {
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_producto.$filenamePortadaH, 1024, 768, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_producto.$filenamePortadaM, 800, 600, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_producto.$filenamePortadaS, 640, 480, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_producto.$filenamePortadaT, 200, 200, "webp");
			// neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_publicacion.$filenamePortadaT, 320, 240, "webp");
			unlink($dirFileO);
		} else {
			return [false, "Error setPortadaImg: Error al mover imagen de portada"];
		}
		return [true, $filenamePortadaH];		
	}
	
	public function PortadaImg_Del() {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID PublicacionNK"];
		}
		//--imagen Portada
		if(is_file($this->dirRaiz.$this->PortadaH)) {
			unlink($this->dirRaiz.$this->PortadaH);
			unlink($this->dirRaiz.$this->PortadaM);
			unlink($this->dirRaiz.$this->PortadaS);
			unlink($this->dirRaiz.$this->PortadaT);
		} else {
			return [false, "No se encontro archivo a eliminar"];
		}
		$filenamePortadaH="";
		$filenamePortadaM="";
		$filenamePortadaS="";
		$filenamePortadaT="";
		$caption="";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$sql="UPDATE $tabla SET PortadaImgH=?, PortadaImgM=?, PortadaImgS=?, PortadaImgT=?, PortadaImgC=? WHERE ProductoID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $caption, $this->ProductoID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		
		return [true, $filenamePortadaH];		
	}

	//**********************************************
	public function PortadaFB_Upload($fileimg) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto"];
		}
		$this->PortadaFB_Del();
		//--imagen
		if(!is_uploaded_file($fileimg["tmp_name"])) {
			return [false, "Error PortadaFBSet: NO se encuentra el archivo:".$fileimg];
		}
		$filenamePortadaO=$this->ProductoID."Portada__.png";
		$filenamePortadaH=$this->ProductoID."Portada_FB.webp";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$sql="UPDATE $tabla SET PortadaFB=? WHERE ProductoID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ss", $filenamePortadaH, $this->ProductoID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		$dirFileO=$this->dirRaiz.$this->folder_producto.$filenamePortadaO;
		if(move_uploaded_file($fileimg["tmp_name"], $dirFileO)) {
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_producto.$filenamePortadaH, 1200, 630, "webp");
			unlink($dirFileO);
		} else {
			return [false, "Error PortadaFB_Set: Error al copiar imagen de portada"];
		}
		return [true, $filenamePortadaH];		
	}

	public function PortadaFB_Del() {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID PublicacionNK"];
		}
		//--imagen Portada
		if(is_file($this->dirRaiz.$this->PortadaFB)) {
			// unlink($this->dirRaiz.$this->PortadaFB);
			echo "Eliminando :".$this->dirRaiz.$this->PortadaFB;
		}
		return [false, "ELIMINANDO PORTADA FB STAT"];
		$filenamePortadaH="";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$sql="UPDATE $tabla SET PortadaImgFB=? WHERE PublicacionID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ss", $filenamePortadaH, $this->ProductoID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		
		return [true, $filenamePortadaH];		
	}

	public function ImagenesGet() {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto"];
		}
		$imagesObtenidas=[];
		$sqlite=new SQLite3($this->sqlite_producto,SQLITE3_OPEN_READONLY,"NeoKiri");
		$querylite="SELECT ImagenID, ImagenH, ImagenM, ImagenS, ImagenT, ImagenC, Categoria, Fecha_Registro FROM Imagenes";
		$resultImages=$sqlite->query($querylite);
		while($image=$resultImages->fetchArray(SQLITE3_ASSOC)) {
			array_push($imagesObtenidas, (object)$image);
		};
		$sqlite->close();
		unset($image);
		//---------------------------------
		$arrayImagenes=[];
		foreach($imagesObtenidas as $imagen) {
			$imgObj=new stdClass();
			$imgObj->id_image=(string)$imagen->ImagenID;
			$imgObj->folderDir=$this->folder_producto;
			$imgObj->filenameH=$imagen->ImagenH;
			$imgObj->SrcH=$this->folder_producto.$imagen->ImagenH;
			$imgObj->filenameM=$imagen->ImagenM;
			$imgObj->SrcM=$this->folder_producto.$imagen->ImagenM;
			$imgObj->filenameS=$imagen->ImagenS;
			$imgObj->SrcS=$this->folder_producto.$imagen->ImagenS;
			$imgObj->filenameT=$imagen->ImagenT;
			$imgObj->SrcT=$this->folder_producto.$imagen->ImagenT;
			$imgObj->Caption=$imagen->ImagenC;
			$imgObj->Date=$imagen->Fecha_Registro;
			$imgObj->Categoria=$imagen->Categoria;
			array_push($arrayImagenes,$imgObj);
		}
		$this->Imagenes=$arrayImagenes;
		return [true,$arrayImagenes,$imagesObtenidas];
	}

	public function ImagenGet($ImagenID) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Publicacion"];
		}
		$sqlite=new SQLite3($this->sqlite_producto,SQLITE3_OPEN_READONLY,"NeoKiri");
		$query="SELECT * FROM Imagenes WHERE ImagenID=:idimage LIMIT 1";
		$img="";
		if($stmtl=$sqlite->prepare($query)) {
			$stmtl->bindParam(":idimage", $ImagenID, SQLITE3_INTEGER);
			$resultado=$stmtl->execute();
			$img=$resultado->fetchArray(SQLITE3_ASSOC);
			$stmtl->close();
		}
		$sqlite->close();
		//---------------------------------
		$imgObj=(object)$img;
		$imgObj->id_image=$imgObj->ImagenID;
		$imgObj->SrcH=$this->folder_producto.$imgObj->ImagenH;
		$imgObj->SrcM=$this->folder_producto.$imgObj->ImagenM;
		$imgObj->SrcS=$this->folder_producto.$imgObj->ImagenS;
		$imgObj->SrcT=$this->folder_producto.$imgObj->ImagenT;
		return [true,$imgObj];
	}

	public function ImagenSubir($fileImage, $caption) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto"];
		}
		if(!is_uploaded_file($fileImage['tmp_name'])) {
			return [false, "no hay datos de imagen"];
		}
		$caption=str_replace("\r\n","",trim(addslashes(nl2br($caption))));
		$FechaObj=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fechaUpload=$FechaObj->format("Y-m-d H:i:s");
		$fechapref=hash("sha256",date("YmdHis"));
		$prefFile = $this->ProductoID."_Cont".$fechapref;
		$FilenameO= $prefFile."_O.webp";
		$FilenameH= $prefFile."_H.webp";
		$FilenameM= $prefFile."_M.webp";
		$FilenameS= $prefFile."_S.webp";
		$FilenameT= $prefFile."_T.webp";
		//----------------------
		$dirFileO=$this->dirRaiz.$this->folder_producto.$FilenameO;
		if(!move_uploaded_file($fileImage['tmp_name'], $dirFileO)) {
			return [false, "no se escribio el archivo"];
		}
		//---------------------
		if(!is_file($dirFileO)) {
			return [false, "no se encontro el archivo escrito"];
		}
		//-----
		$buffCategoria="Album";
		//-----
		$sqlite=new SQLite3($this->sqlite_producto,SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sql_setImageRich="INSERT INTO Imagenes (ImagenH, ImagenM, ImagenS, ImagenT, ImagenC, Categoria, Fecha_Registro) VALUES (:fnh, :fnm, :fns, :fnt, :capt, :cat, :fch)";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
			$stmtlit->bindParam(":fnh",$FilenameH,SQLITE3_TEXT);
			$stmtlit->bindParam(":fnm",$FilenameM,SQLITE3_TEXT);
			$stmtlit->bindParam(":fns",$FilenameS,SQLITE3_TEXT);
			$stmtlit->bindParam(":fnt",$FilenameT,SQLITE3_TEXT);
			$stmtlit->bindParam(":capt",$caption,SQLITE3_TEXT);
			$stmtlit->bindParam(":cat",$buffCategoria,SQLITE3_TEXT);
			$stmtlit->bindParam(":fch",$fechaUpload,SQLITE3_TEXT);
			$stmtlit->execute();
			if(!$stmtlit->close()) {
				return [false, $sqlite->lastErrorMsg()];
			};
		} else {
			return [false, $sqlite->lastErrorMsg()];
		}
		$lastID=$sqlite->lastInsertRowID();
		$sqlite->close();
		//------
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_producto.$FilenameH, 1200, 1200, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_producto.$FilenameM, 900, 900, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_producto.$FilenameS, 600, 600, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_producto.$FilenameT, 300, 300, "webp");
		unlink($dirFileO);
		return [true, $lastID];
	}

	public function ImagenDel($ImagenID) {
		$ImgBuff=$this->ImagenGet($ImagenID);
		// return $ImgBuff;
		if(!$ImgBuff[0]) {
			return $ImgBuff;
		}
		if(is_file($this->dirRaiz.$ImgBuff[1]->SrcH)) {
			unlink($this->dirRaiz.$ImgBuff[1]->SrcH);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcM);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcS);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcT);
		}
		//---------------------------------
		$sqlite=new SQLite3($this->sqlite_producto,SQLITE3_OPEN_READWRITE,"NeoKiri");
		$query="DELETE FROM Imagenes WHERE ImagenID=:idimage";
		if($stmtl=$sqlite->prepare($query)) {
			$stmtl->bindParam(":idimage", $ImagenID, SQLITE3_INTEGER);
			if(!$stmtl->execute()) {
				return [false, $sqlite->lastErrorMsg()];
			};
			$stmtl->close();
		}
		$sqlite->close();
		return [true,$ImagenID];
	}

	public function VideoYoutubeSet($Titulo, $VideoID) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto"];
		}
		$sqlite=new SQLite3($this->sqlite_producto, SQLITE3_OPEN_READWRITE, "NeoKiri");
		$stat=[];
		$queryLiteYoutube="INSERT INTO Videos (Titulo, Src_ID) VALUES (:tit, :srcid)";
		if($stmtYoutube=$sqlite->prepare($queryLiteYoutube)) {
			$stmtYoutube->bindParam(":tit", $Titulo, SQLITE3_TEXT);
			$stmtYoutube->bindParam(":srcid", $VideoID, SQLITE3_TEXT);
			if(!$stmtYoutube->execute()) {
				array_push($stat, [false, $sqlite->lastErrorMsg()]);
				return [false, $stat];
			};
			$LastID=$sqlite->lastInsertRowID();
			$stmtYoutube->close();
		} else {
			array_push($stat, [false, $sqlite->lastErrorMsg()]);
			return [false, $stat];
		}
		$sqlite->close();
		return [true, $LastID];
	}

	public function VideosGet() {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto de videos"];
		}
		$sqlite=new SQLite3($this->sqlite_producto, SQLITE3_OPEN_READONLY, "NeoKiri");
		$query="SELECT * FROM Videos ORDER BY VideoTipo ASC";
		$resultado=[];
		if($stmtLite=$sqlite->prepare($query)) {
			$stmtLiteResult=$stmtLite->execute();
			while ($row=$stmtLiteResult->fetchArray(SQLITE3_ASSOC)) {
				$row=(object)$row;
				$row->id_video=$row->VideoID;
				array_push($resultado, $row);
				unset($row);
			}
			$stmtLite->close();
		} else {
			return [false, $sqlite->lastErrorMsg()];
		}
		$sqlite->close();
		$this->Videos = $resultado;
		return [true, $resultado];
	}

	public function VideoDel($VideoID) {
		if(empty($this->ProductoID)) {
			return [false, "Sin ID Producto para eliminar video"];
		}
		/*
		$ImgBuff=$this->ImagenGet($VideoID);
		if(!$ImgBuff[0]) {
			return $ImgBuff;
		}
		unlink($this->dirRaiz.$ImgBuff[1]->SrcH);
		unlink($this->dirRaiz.$ImgBuff[1]->SrcM);
		unlink($this->dirRaiz.$ImgBuff[1]->SrcS);
		unlink($this->dirRaiz.$ImgBuff[1]->SrcT);
		*/
		//---------------------------------
		$sqlite=new SQLite3($this->sqlite_producto,SQLITE3_OPEN_READWRITE,"NeoKiri");
		$query="DELETE FROM Videos WHERE VideoID=:idvideo";
		if($stmtl=$sqlite->prepare($query)) {
			$stmtl->bindParam(":idvideo", $VideoID, SQLITE3_INTEGER);
			if(!$stmtl->execute()) {
				return [false, $sqlite->lastErrorMsg()];
			};
			$stmtl->close();
		}
		$sqlite->close();
		return [true,$VideoID];
	}

	public function HabilitarStat() {
		if(empty($this->ProductoID)) {
			return [false, "Sin id producto a habilitar"];
		}
		$Habilitado = $this->Habilitado ? false : true ;
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$query="UPDATE $tabla SET Habilitado=? WHERE ProductoID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("is", $Habilitado, $this->ProductoID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
			return [false, $sql->error];
		}
		$sql->close();
		return [true, $Habilitado];
	}

	public function VisitaSumar() {
		if(empty($this->ProductoID)) {
			return [false, "Sin id producto a editar contenido"];
		}
		if(!$this->Habilitado) {
			return [false, "No se ha habilitado para la suma"];
		}
		$VisitasNew=$this->Visitas+1;
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$query="UPDATE $tabla SET Visitas=? WHERE ProductoID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("is", $VisitasNew, $this->ProductoID);
			if(!$stmt->execute()) {
				return [false, $sql->error];
			};
			$stmt->close();
		} else {
			return [false, $sql->error];
		}
		$sql->close();
		return [true];
	}

	public function Producto_Del()	{
		if(empty($this->ProductoID)) {
			return [false,"sin id producto a eliminar"];
		}
		// return [false, "Eliminando: ".$this->dirRaiz.$this->folder_producto];
		//-------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		//----------------------------
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$sql="DELETE FROM $tabla WHERE ProductoID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->ProductoID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Publicacion_Delete :: ".$mysqli->error];
		}
		//----------------------------
		$tabla=ProductosNK_Conexion::$tabla_productosfotos;
		$sql="DELETE FROM $tabla WHERE ProductoID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->ProductoID);
			$stmt->execute();
			$stmt->close();
		} else {
			return [false,"Publicacion_Delete :: ".$mysqli->error];
		}
		//----------------------------
		$mysqli->close();
		//--------------------------------
		$statDelDirectory=neoKiri::delDirectorio($this->dirRaiz.$this->folder_producto,true);
		if(!$statDelDirectory[0])	{
			return [false, $statDelDirectory];
		}
		return [true, $this->ProductoID, $statDelDirectory ];
	}


    public static function Producto_New($Nombre, $CodeID, $CategoriaID, $MarcaID, $dirRaiz) {	
		//--------------------------
		//Comprobar datos vacios
		if(empty($Nombre) || empty($CategoriaID) || empty($CodeID) || empty($MarcaID)) {
			return [false, "Faltan datos necesarios para registro de producto"];
		}

		//--------------------------
		//Preparar datos
		//Año/Mes/Dia/Hora Militar/Minuto/Segundo/
        $FechaPublicacion=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fecha_publicacionid=$FechaPublicacion->format("YmdHis");
		$fecha_publicacion=$FechaPublicacion->format("Y-m-d H:i:s");
		$fecha_ano=$FechaPublicacion->format("Y");
		$fecha_mes=$FechaPublicacion->format("m");
		//--------------------------
		//Generar Un Identificador de PublicacionNK
        $str="";
		$separar=explode(" ",stripslashes($Nombre));
		foreach ($separar as $line) {
			$lineAct=preg_replace("/\W/","",$line);
			if(empty($lineAct)) {
				continue;
			} else {
				$str.=substr($lineAct,0,1);
			}
		}
		$ProductoID="Producto".hash("sha256", $Nombre.date("YmdHis"));
		$newID=neoKiri::GenerarRandomID(4, "Producto");
		$ProductoIDAle=$newID;
		//--------------------------
		//Generar Consulta SQL para agregar a la DB 
		$mysqli=ProductosNK_Conexion::getConexion();
		$stat="";
        $tabla=ProductosNK_Conexion::$tabla_productos;
		$nickDir=neoKiri::encodeTitulo2Link($Nombre)."-".$ProductoIDAle;
		$sql="INSERT INTO $tabla (ProductoID, ProductoCodeID, ProductoNickDir, ProductoCategoriaID, MarcaID, Nombre, Fecha_Registro) VALUES (?, ?, ?, ?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("sssssss",$ProductoID, $CodeID, $nickDir, $CategoriaID, $MarcaID, $Nombre, $fecha_publicacion);
			if(!$stmt->execute()) {
				return [false, "Producto New Error: ".$mysqli->error];
			};
			$stmt->close();
		} else {
			$stat=$mysqli->error;
			return [false, "Producto New Error ".$stat];
		}
		$mysqli->close();

		//--Crear carpetas
		$folderProducto=$dirRaiz.ProductosNK_Conexion::$folder_productos.$ProductoID."/";
		if(!is_dir($folderProducto)) {
			mkdir($folderProducto,0755,true);
		}
		return [true, $ProductoID];
		//--------------------------------------------------------
	}
    
	public function DBLite_Crear() {
		if(empty($this->ProductoID)) {
			return [false, "Sin id producto"];
		}
		$sqliteDir=$this->sqlite_producto;
		$sqlite=new SQLite3($sqliteDir, SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sqlite->exec(ProductosNK_Fix::$queryTablaProductosCategoriasNK);
		$sqlite->exec(ProductosNK_Fix::$queryTablaProductosNK);
		$sqlite->exec(ProductosNK_Fix::$queryTablaProductosFotosNK);
		//---------------------------
		$sqlite->exec(neoKiri::$tablaLite_Imagenes);
		$sqlite->exec(neoKiri::$tablaLite_Videos);
		$sqlite->exec(neoKiri::$tablaLite_Archivos);
		$sqlite->exec(neoKiri::$tablaLite_Comentarios);
		$sqlite->close();
		return [true];
	}
}


class ProductosNK_Getters {
    public static function GetAll($SoloHabilitados=true) {
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="WHERE P.Habilitado=1 AND P.PrecioFinal>0";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$tablacategorias=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT P.ProductoID, P.ProductoNickDir, P.ProductoCodeID, P.ProductoCategoriaID, P.MarcaID, P.Nombre, P.Descripcion, P.Contenido, P.CaracteristicasJSON, P.PrecioDistribuidor, P.PrecioFinal, P.PrecioFinalOferta, P.Disponibles, P.PortadaH, P.PortadaM, P.PortadaS, P.PortadaT, P.PortadaC, P.PortadaFB, P.EnOferta, P.Visitas, P.Habilitado, P.Fecha_Registro, PC.Nombre FROM $tabla P LEFT JOIN $tablacategorias PC ON P.ProductoCategoriaID=PC.ProductoCategoriaID $sqlwherehabiitados ORDER BY P.Habilitado ASC, P.Nombre ASC, P.ProductoCategoriaID ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$PublicacionID);
			$stmt->execute();
			// -----------------
			$buffProductoID="";
			$buffNickDir="";
			$buffCodeID="";
			$buffCategoriaID="";
			$buffMarcaID="";
			$buffNombre="";
			$buffDescripcion="";
			$buffContenido="";
			$buffCaracteristicasJSON="";
			$buffPrecioDistribuidor="";
			$buffPrecioFinal="";
			$buffPrecioFinalOferta="";
			$buffDisponibles="";
			$buffPortadaH="";
			$buffPortadaM="";
			$buffPortadaS="";
			$buffPortadaT="";
			$buffPortadaC="";
			$buffPortadaFB="";
			$buffEnOferta="";
			$buffVisitas="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$buffCategoriaNombre="";
			$stmt->bind_result(
				$buffProductoID, $buffNickDir, $buffCodeID, $buffCategoriaID, $buffMarcaID, $buffNombre, $buffDescripcion, $buffContenido, $buffCaracteristicasJSON, $buffPrecioDistribuidor, $buffPrecioFinal, $buffPrecioFinalOferta, $buffDisponibles, $buffPortadaH, $buffPortadaM, $buffPortadaS, $buffPortadaT, $buffPortadaC, $buffPortadaFB, $buffEnOferta, $buffVisitas, $buffHabilitado, $buffFechaRegistro, $buffCategoriaNombre
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoID=$buffProductoID;
				$buffStd->NickDir=$buffNickDir;
				$buffStd->CodeID=$buffCodeID;
				$buffStd->CategoriaID=$buffCategoriaID;
				$buffStd->MarcaID=$buffMarcaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->Contenido=$buffContenido;
				$buffStd->CaracteristicasJSON=$buffCaracteristicasJSON;
				$buffStd->PrecioDistribuidor=$buffPrecioDistribuidor;
				$buffStd->PrecioFinal=$buffPrecioFinal;
				$buffStd->PrecioFinalOferta=$buffPrecioFinalOferta;
				$buffStd->Disponibles=$buffDisponibles;
				$buffStd->PortadaH=$buffPortadaH;
				$buffStd->PortadaM=$buffPortadaM;
				$buffStd->PortadaS=$buffPortadaS;
				$buffStd->PortadaT=$buffPortadaT;
				$buffStd->PortadaC=$buffPortadaC;
				$buffStd->PortadaFB=$buffPortadaFB;
				$buffStd->EnOferta=$buffEnOferta;
				$buffStd->Visitas=$buffVisitas;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				$buffStd->CategoriaNombre=$buffCategoriaNombre;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderProducto=ProductosNK_Conexion::$folder_productos.$Resultado->ProductoID."/";
			$buffTituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Productos/".$Resultado->NickDir;
			$Resultado->sqlite_dir=$buffFolderProducto.$Resultado->ProductoID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->PortadaH)) {
				$Resultado->PortadaH=$buffFolderProducto.$Resultado->PortadaH;
				$Resultado->PortadaM=$buffFolderProducto.$Resultado->PortadaM;
				$Resultado->PortadaS=$buffFolderProducto.$Resultado->PortadaS;
				$Resultado->PortadaT=$buffFolderProducto.$Resultado->PortadaT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB)) {
				$Resultado->PortadaFB=$buffFolderProducto.$Resultado->PortadaFB;
			}
		}
		//-------------------
		return [true, $Resultados];
	}

    public static function FromIDS(array $IDS_in, $SoloHabilitados=true) {
		if(!is_array($IDS_in)) {
			return [false, "No es array"];
		}
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="AND P.Habilitado=1 AND P.PrecioFinal>0";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$tablacategorias=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT P.ProductoID, P.ProductoNickDir, P.ProductoCodeID, P.ProductoCategoriaID, P.MarcaID, P.Nombre, P.Descripcion, P.Contenido, P.CaracteristicasJSON, P.PrecioDistribuidor, P.PrecioFinal, P.PrecioFinalOferta, P.Disponibles, P.PortadaH, P.PortadaM, P.PortadaS, P.PortadaT, P.PortadaC, P.PortadaFB, P.EnOferta, P.Visitas, P.Habilitado, P.Fecha_Registro, PC.Nombre FROM $tabla P LEFT JOIN $tablacategorias PC ON P.ProductoCategoriaID=PC.ProductoCategoriaID WHERE FIND_IN_SET(P.ProductoID, ?) $sqlwherehabiitados ORDER BY P.Nombre ASC, P.Habilitado ASC, P.ProductoCategoriaID ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			$ids_imp=implode(",", $IDS_in);
			// -----------------
			$stmt->bind_param("s",$ids_imp);
			$stmt->execute();
			// -----------------
			$buffProductoID="";
			$buffNickDir="";
			$buffCodeID="";
			$buffCategoriaID="";
			$buffMarcaID="";
			$buffNombre="";
			$buffDescripcion="";
			$buffContenido="";
			$buffCaracteristicasJSON="";
			$buffPrecioDistribuidor="";
			$buffPrecioFinal="";
			$buffPrecioFinalOferta="";
			$buffDisponibles="";
			$buffPortadaH="";
			$buffPortadaM="";
			$buffPortadaS="";
			$buffPortadaT="";
			$buffPortadaC="";
			$buffPortadaFB="";
			$buffEnOferta="";
			$buffVisitas="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$buffCategoriaNombre="";
			$stmt->bind_result(
				$buffProductoID, $buffNickDir, $buffCodeID, $buffCategoriaID, $buffMarcaID, $buffNombre, $buffDescripcion, $buffContenido, $buffCaracteristicasJSON, $buffPrecioDistribuidor, $buffPrecioFinal, $buffPrecioFinalOferta, $buffDisponibles, $buffPortadaH, $buffPortadaM, $buffPortadaS, $buffPortadaT, $buffPortadaC, $buffPortadaFB, $buffEnOferta, $buffVisitas, $buffHabilitado, $buffFechaRegistro, $buffCategoriaNombre
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoID=$buffProductoID;
				$buffStd->NickDir=$buffNickDir;
				$buffStd->CodeID=$buffCodeID;
				$buffStd->CategoriaID=$buffCategoriaID;
				$buffStd->MarcaID=$buffMarcaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->Contenido=$buffContenido;
				$buffStd->CaracteristicasJSON=$buffCaracteristicasJSON;
				$buffStd->PrecioDistribuidor=$buffPrecioDistribuidor;
				$buffStd->PrecioFinal=$buffPrecioFinal;
				$buffStd->PrecioFinalOferta=$buffPrecioFinalOferta;
				$buffStd->Disponibles=$buffDisponibles;
				$buffStd->PortadaH=$buffPortadaH;
				$buffStd->PortadaM=$buffPortadaM;
				$buffStd->PortadaS=$buffPortadaS;
				$buffStd->PortadaT=$buffPortadaT;
				$buffStd->PortadaC=$buffPortadaC;
				$buffStd->PortadaFB=$buffPortadaFB;
				$buffStd->EnOferta=$buffEnOferta;
				$buffStd->Visitas=$buffVisitas;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				$buffStd->CategoriaNombre=$buffCategoriaNombre;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderProducto=ProductosNK_Conexion::$folder_productos.$Resultado->ProductoID."/";
			$buffTituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Productos/".$Resultado->NickDir;
			$Resultado->sqlite_dir=$buffFolderProducto.$Resultado->ProductoID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->PortadaH)) {
				$Resultado->PortadaH=$buffFolderProducto.$Resultado->PortadaH;
				$Resultado->PortadaM=$buffFolderProducto.$Resultado->PortadaM;
				$Resultado->PortadaS=$buffFolderProducto.$Resultado->PortadaS;
				$Resultado->PortadaT=$buffFolderProducto.$Resultado->PortadaT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB)) {
				$Resultado->PortadaFB=$buffFolderProducto.$Resultado->PortadaFB;
			}
		}
		//-------------------
		return [true, $Resultados];
	}

    public static function GetIDFromNickDir($NickDir) {
		if(empty($NickDir)) {
			return [false, "Sin Nick a buscar"];
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
        $sql="SELECT P.ProductoID, P.Habilitado FROM $tabla P WHERE P.ProductoNickDir=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$NickDir);
			$stmt->execute();
			// -----------------
			$buffProductoID="";
			$buffHabilitado="";
			$stmt->bind_result(
				$buffProductoID,
                $buffHabilitado
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoID=$buffProductoID;
				$buffStd->Habilitado=$buffHabilitado;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		if(count($Resultados)<1) {
			return [false, "Sin Resultados"];
		}
		return [true, $Resultados[0]];
	}

	public static function GetAll_FromCategoriaID(string $CategoriaID, $SoloHabilitados=true) {
		if(empty($CategoriaID)) {
			return [false, "Sin Categoria a buscar"];
		}
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="AND P.Habilitado=1";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$tablacategorias=ProductosNK_Conexion::$tabla_productoscategorias;
		$tablamarcas=ProductosNK_Conexion::$tabla_productosmarcas;
        $sql="SELECT P.ProductoID, P.ProductoNickDir, P.ProductoCodeID, P.ProductoCategoriaID, P.MarcaID, P.Nombre, P.Descripcion, P.Contenido, P.CaracteristicasJSON, P.PrecioDistribuidor, P.PrecioFinal, P.PrecioFinalOferta, P.Disponibles, P.PortadaH, P.PortadaM, P.PortadaS, P.PortadaT, P.PortadaC, P.PortadaFB, P.EnOferta, P.Visitas, P.Habilitado, P.Fecha_Registro, PC.Nombre, pm.Nombre, pm.NickDir FROM $tabla P INNER JOIN $tablacategorias PC ON P.ProductoCategoriaID=PC.ProductoCategoriaID INNER JOIN $tablamarcas pm ON P.MarcaID=pm.ProductoMarcaID WHERE PC.ProductoCategoriaID=? $sqlwherehabiitados ORDER BY P.Habilitado ASC, P.Nombre ASC, P.ProductoCategoriaID ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$CategoriaID);
			$stmt->execute();
			// -----------------
			$buffProductoID="";
			$buffNickDir="";
			$buffCodeID="";
			$buffCategoriaID="";
			$buffMarcaID="";
			$buffNombre="";
			$buffDescripcion="";
			$buffContenido="";
			$buffCaracteristicasJSON="";
			$buffPrecioDistribuidor="";
			$buffPrecioFinal="";
			$buffPrecioFinalOferta="";
			$buffDisponibles="";
			$buffPortadaH="";
			$buffPortadaM="";
			$buffPortadaS="";
			$buffPortadaT="";
			$buffPortadaC="";
			$buffPortadaFB="";
			$buffEnOferta="";
			$buffVisitas="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$buffCategoriaNombre="";
			$buffMarcaNombre="";
			$buffMarcaNickDir="";
			$stmt->bind_result(
				$buffProductoID, $buffNickDir, $buffCodeID, $buffCategoriaID, $buffMarcaID, $buffNombre, $buffDescripcion, $buffContenido, $buffCaracteristicasJSON, $buffPrecioDistribuidor, $buffPrecioFinal, $buffPrecioFinalOferta, $buffDisponibles, $buffPortadaH, $buffPortadaM, $buffPortadaS, $buffPortadaT, $buffPortadaC, $buffPortadaFB, $buffEnOferta, $buffVisitas, $buffHabilitado, $buffFechaRegistro, $buffCategoriaNombre, $buffMarcaNombre, $buffMarcaNickDir
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoID=$buffProductoID;
				$buffStd->NickDir=$buffNickDir;
				$buffStd->CodeID=$buffCodeID;
				$buffStd->CategoriaID=$buffCategoriaID;
				$buffStd->MarcaID=$buffMarcaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->Contenido=$buffContenido;
				$buffStd->CaracteristicasJSON=$buffCaracteristicasJSON;
				$buffStd->PrecioDistribuidor=$buffPrecioDistribuidor;
				$buffStd->PrecioFinal=$buffPrecioFinal;
				$buffStd->PrecioFinalOferta=$buffPrecioFinalOferta;
				$buffStd->Disponibles=$buffDisponibles;
				$buffStd->PortadaH=$buffPortadaH;
				$buffStd->PortadaM=$buffPortadaM;
				$buffStd->PortadaS=$buffPortadaS;
				$buffStd->PortadaT=$buffPortadaT;
				$buffStd->PortadaC=$buffPortadaC;
				$buffStd->PortadaFB=$buffPortadaFB;
				$buffStd->EnOferta=$buffEnOferta;
				$buffStd->Visitas=$buffVisitas;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				$buffStd->CategoriaNombre=$buffCategoriaNombre;
				$buffStd->MarcaNombre=$buffMarcaNombre;
				$buffStd->MarcaNickDir=$buffMarcaNickDir;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderProducto=ProductosNK_Conexion::$folder_productos.$Resultado->ProductoID."/";
			// $this->folderArticuloAbsolute=_Arriero::$direccionWebComplete.PublicacionNK_Conexion::$folder_publicaciones.$this->Fecha_Obj->Format("Y/m/").$this->PublicacionID."/";
			$buffTituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Productos/".$Resultado->NickDir;
			// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
			$Resultado->sqlite_dir=$buffFolderProducto.$Resultado->ProductoID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->PortadaH)) {
				$Resultado->PortadaH=$buffFolderProducto.$Resultado->PortadaH;
				$Resultado->PortadaM=$buffFolderProducto.$Resultado->PortadaM;
				$Resultado->PortadaS=$buffFolderProducto.$Resultado->PortadaS;
				$Resultado->PortadaT=$buffFolderProducto.$Resultado->PortadaT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB)) {
				$Resultado->PortadaFB=$buffFolderProducto.$Resultado->PortadaFB;
			}
		}
		//-------------------
		return [true, $Resultados];
	}

	public static function GetAll_FromMarcaID(string $MarcaID, $SoloHabilitados=true) {
		if(empty($MarcaID)) {
			return [false, "Sin Categoria a buscar"];
		}
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="AND P.Habilitado=1";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productos;
		$tablacategorias=ProductosNK_Conexion::$tabla_productoscategorias;
		$tablamarcas=ProductosNK_Conexion::$tabla_productosmarcas;
        $sql="SELECT P.ProductoID, P.ProductoNickDir, P.ProductoCodeID, P.ProductoCategoriaID, P.MarcaID, P.Nombre, P.Descripcion, P.Contenido, P.CaracteristicasJSON, P.PrecioDistribuidor, P.PrecioFinal, P.PrecioFinalOferta, P.Disponibles, P.PortadaH, P.PortadaM, P.PortadaS, P.PortadaT, P.PortadaC, P.PortadaFB, P.EnOferta, P.Visitas, P.Habilitado, P.Fecha_Registro, PC.Nombre, pm.Nombre, pm.NickDir FROM $tabla P INNER JOIN $tablacategorias PC ON P.ProductoCategoriaID=PC.ProductoCategoriaID INNER JOIN $tablamarcas pm ON P.MarcaID=pm.ProductoMarcaID WHERE P.MarcaID=? $sqlwherehabiitados ORDER BY P.Habilitado ASC, P.Nombre ASC, P.ProductoCategoriaID ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$MarcaID);
			$stmt->execute();
			// -----------------
			$buffProductoID="";
			$buffNickDir="";
			$buffCodeID="";
			$buffCategoriaID="";
			$buffMarcaID="";
			$buffNombre="";
			$buffDescripcion="";
			$buffContenido="";
			$buffCaracteristicasJSON="";
			$buffPrecioDistribuidor="";
			$buffPrecioFinal="";
			$buffPrecioFinalOferta="";
			$buffDisponibles="";
			$buffPortadaH="";
			$buffPortadaM="";
			$buffPortadaS="";
			$buffPortadaT="";
			$buffPortadaC="";
			$buffPortadaFB="";
			$buffEnOferta="";
			$buffVisitas="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$buffCategoriaNombre="";
			$buffMarcaNombre="";
			$buffMarcaNickDir="";
			$stmt->bind_result(
				$buffProductoID, $buffNickDir, $buffCodeID, $buffCategoriaID, $buffMarcaID, $buffNombre, $buffDescripcion, $buffContenido, $buffCaracteristicasJSON, $buffPrecioDistribuidor, $buffPrecioFinal, $buffPrecioFinalOferta, $buffDisponibles, $buffPortadaH, $buffPortadaM, $buffPortadaS, $buffPortadaT, $buffPortadaC, $buffPortadaFB, $buffEnOferta, $buffVisitas, $buffHabilitado, $buffFechaRegistro, $buffCategoriaNombre, $buffMarcaNombre, $buffMarcaNickDir
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoID=$buffProductoID;
				$buffStd->NickDir=$buffNickDir;
				$buffStd->CodeID=$buffCodeID;
				$buffStd->CategoriaID=$buffCategoriaID;
				$buffStd->MarcaID=$buffMarcaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->Contenido=$buffContenido;
				$buffStd->CaracteristicasJSON=$buffCaracteristicasJSON;
				$buffStd->PrecioDistribuidor=$buffPrecioDistribuidor;
				$buffStd->PrecioFinal=$buffPrecioFinal;
				$buffStd->PrecioFinalOferta=$buffPrecioFinalOferta;
				$buffStd->Disponibles=$buffDisponibles;
				$buffStd->PortadaH=$buffPortadaH;
				$buffStd->PortadaM=$buffPortadaM;
				$buffStd->PortadaS=$buffPortadaS;
				$buffStd->PortadaT=$buffPortadaT;
				$buffStd->PortadaC=$buffPortadaC;
				$buffStd->PortadaFB=$buffPortadaFB;
				$buffStd->EnOferta=$buffEnOferta;
				$buffStd->Visitas=$buffVisitas;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				$buffStd->CategoriaNombre=$buffCategoriaNombre;
				$buffStd->MarcaNombre=$buffMarcaNombre;
				$buffStd->MarcaNickDir=$buffMarcaNickDir;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderProducto=ProductosNK_Conexion::$folder_productos.$Resultado->ProductoID."/";
			// $this->folderArticuloAbsolute=_Arriero::$direccionWebComplete.PublicacionNK_Conexion::$folder_publicaciones.$this->Fecha_Obj->Format("Y/m/").$this->PublicacionID."/";
			$buffTituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Productos/".$Resultado->NickDir;
			// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
			$Resultado->sqlite_dir=$buffFolderProducto.$Resultado->ProductoID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->PortadaH)) {
				$Resultado->PortadaH=$buffFolderProducto.$Resultado->PortadaH;
				$Resultado->PortadaM=$buffFolderProducto.$Resultado->PortadaM;
				$Resultado->PortadaS=$buffFolderProducto.$Resultado->PortadaS;
				$Resultado->PortadaT=$buffFolderProducto.$Resultado->PortadaT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB)) {
				$Resultado->PortadaFB=$buffFolderProducto.$Resultado->PortadaFB;
			}
		}
		//-------------------
		return [true, $Resultados];
	}
}

class ProductoCategoriaNK {
	public $ProductoCategoriaID_In="";

	public $ProductoCategoriaID="";
	public $NickDir="";
	public $Nombre="";
	public $Descripcion="";
	public $Contenido="";
	public $CategoriaIconSVG="";
	public $CategoriaIconMinSVG="";
	public $PortadaH="";
	public $PortadaM="";
	public $PortadaS="";
	public $PortadaT="";
	public $PortadaC="";
	public $PortadaFB="";
	public $TablaAsignada="";
	public $Habilitado="";
	public $FechaRegistro="";

	public $Imagenes=[];
	
	public $dirRaiz="";
	public $link="";
	public $folder_categoria="";
	public $sqliteDir="";

	public function __construct($ProductoCategoriaID, $dirRaiz) {
		$this->ProductoCategoriaID_In=$ProductoCategoriaID;
		$this->dirRaiz=$dirRaiz;
		$this->Info_Get();
	}

	public function Info_Get() {
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT ProductoCategoriaID, NickDir, Nombre, Descripcion, Contenido, CategoriaIconSVG, CategoriaIconMinSVG, PortadaH, PortadaM, PortadaS, PortadaT, PortadaC, PortadaFB, Tabla_Asignada, Habilitado, Fecha_Registro FROM $tabla WHERE ProductoCategoriaID=? ORDER BY Habilitado ASC, Nombre ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$this->ProductoCategoriaID_In);
			$stmt->bind_result(
				$this->ProductoCategoriaID,
				$this->NickDir, 
				$this->Nombre, 
				$this->Descripcion, 
				$this->Contenido, 
				$this->CategoriaIconSVG, 
				$this->CategoriaIconMinSVG, 
				$this->PortadaH, 
				$this->PortadaM, 
				$this->PortadaS, 
				$this->PortadaT, 
				$this->PortadaC, 
				$this->PortadaFB,
				$this->TablaAsignada, 
				$this->Habilitado, 
				$this->FechaRegistro
			);
			if(!$stmt->execute()) {
				return [false, "Error en la ejecucion"];
			};
			$stmt->fetch();
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		if(empty($this->ProductoCategoriaID)) {
			return false;
		}
		//-------------------
		$Fecha_Obj=new DateTime($this->FechaRegistro, new DateTimeZone("America/Bogota"));
		$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
		//--------------------
		$buffFolderProductoCategoria=ProductosNK_Conexion::$folder_productoscategorias.$this->ProductoCategoriaID."/";
		$this->folder_categoria=$buffFolderProductoCategoria;
		$this->link="Categoria/".$this->NickDir;
		// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
		$this->sqliteDir=$this->dirRaiz.$this->folder_categoria.$this->ProductoCategoriaID.".sqlite3";
		//-----------------------------
		if(!empty($this->PortadaH)) {
			$this->PortadaH=$this->folder_categoria.$this->PortadaH;
			$this->PortadaM=$this->folder_categoria.$this->PortadaM;
			$this->PortadaS=$this->folder_categoria.$this->PortadaS;
			$this->PortadaT=$this->folder_categoria.$this->PortadaT;
		}
		//------Portada Facebook
		if(!empty($this->PortadaFB)) {
			$this->PortadaFB=$this->folder_categoria.$this->PortadaFB;
		}
		//-------------------
		return [true];
	}

	public function Info_Set($Nombre, $Descripcion) {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin id producto Categoria a editar"];
		}
		if(empty($Nombre)) {
			return [false, "¿ Sin nombre de producto ?"];
		}
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$query="UPDATE $tabla SET Nombre=?, Descripcion=? WHERE ProductoCategoriaID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("sss", $Nombre, $Descripcion, $this->ProductoCategoriaID);
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

	//**********************************************
	public function PortadaImg_Upload($fileimg, $caption) {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Producto Categoria"];
		}
		//--imagen Portada
		if(!is_uploaded_file($fileimg["tmp_name"])) {
			return [false, "Error PortadaImg_Upload: NO se encuentra el archivo subido:".$fileimg];
		}
		$filenamePortadaO=$this->ProductoCategoriaID."_Portada_.png";
		$filenamePortadaH=$this->ProductoCategoriaID."_PortadaH.webp";
		$filenamePortadaM=$this->ProductoCategoriaID."_PortadaM.webp";
		$filenamePortadaS=$this->ProductoCategoriaID."_PortadaS.webp";
		$filenamePortadaT=$this->ProductoCategoriaID."_PortadaT.webp";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$sql="UPDATE $tabla SET PortadaH=?, PortadaM=?, PortadaS=?, PortadaT=?, PortadaC=? WHERE ProductoCategoriaID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $caption, $this->ProductoCategoriaID);
			if(!$stmt->execute()) {
				return [false, $mysqli->error];
			};
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		$dirFileO=$this->dirRaiz.$this->folder_categoria.$filenamePortadaO;
		if(move_uploaded_file($fileimg["tmp_name"], $dirFileO)) {
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_categoria.$filenamePortadaH, 1024, 768, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_categoria.$filenamePortadaM, 800, 600, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_categoria.$filenamePortadaS, 640, 480, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_categoria.$filenamePortadaT, 200, 200, "webp");
			// neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_publicacion.$filenamePortadaT, 320, 240, "webp");
			unlink($dirFileO);
		} else {
			return [false, "Error setPortadaImg: Error al mover imagen de portada"];
		}
		return [true, $filenamePortadaH];		
	}
	
	public function PortadaImg_Del() {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Categoria"];
		}
		//--imagen Portada
		if(is_file($this->dirRaiz.$this->PortadaH)) {
			unlink($this->dirRaiz.$this->PortadaH);
			unlink($this->dirRaiz.$this->PortadaM);
			unlink($this->dirRaiz.$this->PortadaS);
			unlink($this->dirRaiz.$this->PortadaT);
		} else {
			return [false, "No se encontro archivo a eliminar"];
		}
		$filenamePortadaH="";
		$filenamePortadaM="";
		$filenamePortadaS="";
		$filenamePortadaT="";
		$caption="";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$sql="UPDATE $tabla SET PortadaH=?, PortadaM=?, PortadaS=?, PortadaT=?, PortadaC=? WHERE ProductoCategoriaID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenamePortadaH, $filenamePortadaM, $filenamePortadaS, $filenamePortadaT, $caption, $this->ProductoCategoriaID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		
		return [true, $filenamePortadaH];		
	}
	
	public function Contenido_Set($Contenido) {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin id producto Categoria a editar"];
		}
		$Contenido=trim($Contenido);
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$query="UPDATE $tabla SET Contenido=? WHERE ProductoCategoriaID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("ss", $Contenido, $this->ProductoCategoriaID);
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

	public function ImagenesGet() {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Producto Categoria"];
		}
		$imagesObtenidas=[];
		$sqlite=new SQLite3($this->sqliteDir,SQLITE3_OPEN_READONLY,"NeoKiri");
		$querylite="SELECT ImagenID, ImagenH, ImagenM, ImagenS, ImagenT, ImagenC, Categoria, Fecha_Registro FROM Imagenes";
		$resultImages=$sqlite->query($querylite);
		while($image=$resultImages->fetchArray(SQLITE3_ASSOC)) {
			array_push($imagesObtenidas, (object)$image);
		};
		$sqlite->close();
		unset($image);
		//---------------------------------
		$arrayImagenes=[];
		foreach($imagesObtenidas as $imagen) {
			$imgObj=new stdClass();
			$imgObj->id_image=(string)$imagen->ImagenID;
			$imgObj->folderDir=$this->folder_categoria;
			$imgObj->filenameH=$imagen->ImagenH;
			$imgObj->SrcH=$this->folder_categoria.$imagen->ImagenH;
			$imgObj->filenameM=$imagen->ImagenM;
			$imgObj->SrcM=$this->folder_categoria.$imagen->ImagenM;
			$imgObj->filenameS=$imagen->ImagenS;
			$imgObj->SrcS=$this->folder_categoria.$imagen->ImagenS;
			$imgObj->filenameT=$imagen->ImagenT;
			$imgObj->SrcT=$this->folder_categoria.$imagen->ImagenT;
			$imgObj->Caption=$imagen->ImagenC;
			$imgObj->Date=$imagen->Fecha_Registro;
			$imgObj->Categoria=$imagen->Categoria;
			array_push($arrayImagenes,$imgObj);
		}
		$this->Imagenes=$arrayImagenes;
		return [true,$arrayImagenes,$imagesObtenidas];
	}

	public function ImagenGet($ImagenID) {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Publicacion"];
		}
		$sqlite=new SQLite3($this->sqliteDir,SQLITE3_OPEN_READONLY,"NeoKiri");
		$query="SELECT * FROM Imagenes WHERE ImagenID=:idimage LIMIT 1";
		$img="";
		if($stmtl=$sqlite->prepare($query)) {
			$stmtl->bindParam(":idimage", $ImagenID, SQLITE3_INTEGER);
			$resultado=$stmtl->execute();
			$img=$resultado->fetchArray(SQLITE3_ASSOC);
			$stmtl->close();
		}
		$sqlite->close();
		//---------------------------------
		$imgObj=(object)$img;
		$imgObj->id_image=$imgObj->ImagenID;
		$imgObj->SrcH=$this->folder_categoria.$imgObj->ImagenH;
		$imgObj->SrcM=$this->folder_categoria.$imgObj->ImagenM;
		$imgObj->SrcS=$this->folder_categoria.$imgObj->ImagenS;
		$imgObj->SrcT=$this->folder_categoria.$imgObj->ImagenT;
		return [true,$imgObj];
	}

	public function ImagenSubir($fileImage, $caption) {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Producto Categoria"];
		}
		if(!is_uploaded_file($fileImage['tmp_name'])) {
			return [false, "no hay datos de imagen"];
		}
		$caption=str_replace("\r\n","",trim(addslashes(nl2br($caption))));
		$FechaObj=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fechaUpload=$FechaObj->format("Y-m-d H:i:s");
		$fechapref=hash("crc32",date("YmdHis"));
		$prefFile = $this->ProductoCategoriaID."_Album".$fechapref;
		$FilenameO= $prefFile."_O.webp";
		$FilenameH= $prefFile."_H.webp";
		$FilenameM= $prefFile."_M.webp";
		$FilenameS= $prefFile."_S.webp";
		$FilenameT= $prefFile."_T.webp";
		//----------------------
		$dirFileO=$this->dirRaiz.$this->folder_categoria.$FilenameO;
		if(!move_uploaded_file($fileImage['tmp_name'], $dirFileO)) {
			return [false, "no se escribio el archivo"];
		}
		//---------------------
		if(!is_file($dirFileO)) {
			return [false, "no se encontro el archivo escrito"];
		}
		//-----
		$buffCategoria="Album";
		//-----
		$sqlite=new SQLite3($this->sqliteDir,SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sql_setImageRich="INSERT INTO Imagenes (ImagenH, ImagenM, ImagenS, ImagenT, ImagenC, Categoria, Fecha_Registro) VALUES (:fnh, :fnm, :fns, :fnt, :capt, :cat, :fch)";
		if($stmtlit=$sqlite->prepare($sql_setImageRich)) {
			$stmtlit->bindParam(":fnh",$FilenameH,SQLITE3_TEXT);
			$stmtlit->bindParam(":fnm",$FilenameM,SQLITE3_TEXT);
			$stmtlit->bindParam(":fns",$FilenameS,SQLITE3_TEXT);
			$stmtlit->bindParam(":fnt",$FilenameT,SQLITE3_TEXT);
			$stmtlit->bindParam(":capt",$caption,SQLITE3_TEXT);
			$stmtlit->bindParam(":cat",$buffCategoria,SQLITE3_TEXT);
			$stmtlit->bindParam(":fch",$fechaUpload,SQLITE3_TEXT);
			$stmtlit->execute();
			if(!$stmtlit->close()) {
				return [false, $sqlite->lastErrorMsg()];
			};
		} else {
			return [false, $sqlite->lastErrorMsg()];
		}
		$lastID=$sqlite->lastInsertRowID();
		$sqlite->close();
		//------
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_categoria.$FilenameH, 1200, 1200, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_categoria.$FilenameM, 900, 900, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_categoria.$FilenameS, 600, 600, "webp");
		neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz . $this->folder_categoria.$FilenameT, 300, 300, "webp");
		unlink($dirFileO);
		return [true, $lastID];
	}

	public function ImagenDel($ImagenID) {
		$ImgBuff=$this->ImagenGet($ImagenID);
		// return $ImgBuff;
		if(!$ImgBuff[0]) {
			return $ImgBuff;
		}
		if(is_file($this->dirRaiz.$ImgBuff[1]->SrcH)) {
			unlink($this->dirRaiz.$ImgBuff[1]->SrcH);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcM);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcS);
			unlink($this->dirRaiz.$ImgBuff[1]->SrcT);
		}
		//---------------------------------
		$sqlite=new SQLite3($this->sqliteDir,SQLITE3_OPEN_READWRITE,"NeoKiri");
		$query="DELETE FROM Imagenes WHERE ImagenID=:idimage";
		if($stmtl=$sqlite->prepare($query)) {
			$stmtl->bindParam(":idimage", $ImagenID, SQLITE3_INTEGER);
			if(!$stmtl->execute()) {
				return [false, $sqlite->lastErrorMsg()];
			};
			$stmtl->close();
		}
		$sqlite->close();
		return [true,$ImagenID];
	}

	public function Habilitar() {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin id producto Categoria a habilitar"];
		}
		// ------------------------------------
		$buffHabilitado=false;
		if((bool)$this->Habilitado) {
			$buffHabilitado=false;
		} else {
			$buffHabilitado=true;
		}
		// ------------------------------------
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$query="UPDATE $tabla SET Habilitado=? WHERE ProductoCategoriaID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("is", $buffHabilitado, $this->ProductoCategoriaID);
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

    public static function Categoria_New($Nombre, $dirRaiz) {	
		//Preparar datos
		//Año/Mes/Dia/Hora Militar/Minuto/Segundo/
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fecha_nowid=$FechaNow->format("YmdHis");
		$fecha_now_sql=$FechaNow->format("Y-m-d H:i:s");
		$fecha_ano=$FechaNow->format("Y");
		$fecha_mes=$FechaNow->format("m");
		//--------------------------
		//Generar Un Identificador de PublicacionNK
        $newID=neoKiri::GenerarRandomID(4, "ProductoCategoria");
        $str="";
		$separar=explode(" ",stripslashes($Nombre));
		foreach ($separar as $line) {
			$lineAct=preg_replace("/\W/","",$line);
			if(empty($lineAct)) {
				continue;
			} else {
				$str.=substr($lineAct,0,3);
			}
		}
		$CategoriaID="Categoria".hash("crc32", $fecha_nowid.$str);
		$CategoriaNickDir=neoKiri::encodeTitulo2Link($Nombre);
		// $CategoriaID=$newID;
		//--------------------------
		//Generar Consulta SQL para agregar a la DB 
		$mysqli=ProductosNK_Conexion::getConexion();
		$stat="";
        $tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$sql="INSERT INTO $tabla (ProductoCategoriaID, NickDir, Nombre, Fecha_Registro) VALUES (?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssss",$CategoriaID, $CategoriaNickDir, $Nombre, $fecha_now_sql);
			if(!$stmt->execute()) {
				return [false, "Categoria_New Error: ".$mysqli->error];
			};
			$stmt->close();
		} else {
			$stat=$mysqli->error;
			return [false, "Categoria_New Error ".$stat];
		}
		$mysqli->close();
		//--Crear carpetas
		$folder_categoria=$dirRaiz.ProductosNK_Conexion::$folder_productoscategorias.$CategoriaID."/";
		if(!is_dir($folder_categoria)) {
			mkdir($folder_categoria,0755,true);
		}
		return [true, $CategoriaID];
	}

	public function ProductoCategoria_Delete()	{
		if(empty($this->ProductoCategoriaID)) {
			return [false,"sin id producto Categoria a eliminar"];
		}
		// return [false, $this->dirRaiz.$this->folder_categoria, "eliminado producto categoria"];
		//-------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		//----------------------------
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
		$sql="DELETE FROM $tabla WHERE ProductoCategoriaID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->ProductoCategoriaID);
			if(!$stmt->execute()) {
				return [false,"ProductoCategoria_Delete :: ".$mysqli->error];
			};
			$stmt->close();
		} else {
			return [false,"ProductoCategoria_Delete :: ".$mysqli->error];
		}
		//----------------------------
		$mysqli->close();
		//--------------------------------
		$statDelDirectory=neoKiri::delDirectorio($this->dirRaiz.$this->folder_categoria, true);
		if(!$statDelDirectory[0]) {
			return [false, $statDelDirectory];
		}
		return [true, $this->ProductoCategoriaID, $statDelDirectory];
	}

	public function DBLite_Crear() {
		if(empty($this->ProductoCategoriaID)) {
			return [false, "Sin ID Producto Categoria"];
		}
		$sqlite=new SQLite3($this->sqliteDir, SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sqlite->exec(ProductosNK_Fix::$queryTablaProductosCategoriasNK);
		//---------------------------
		$sqlite->exec(neoKiri::$tablaLite_Imagenes);
		$sqlite->exec(neoKiri::$tablaLite_Videos);
		$sqlite->exec(neoKiri::$tablaLite_Archivos);
		$sqlite->exec(neoKiri::$tablaLite_Comentarios);
		//--------------------------------------------
		$sqlite->close();
		return [true, $this->sqliteDir];
	}
}




class ProductosCategorias_Getters {
	public static function GetAll($SoloHabilitados=true) {
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="WHERE Habilitado=1";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT ProductoCategoriaID, Nombre, Descripcion, Contenido, PortadaH, PortadaM, PortadaS, PortadaT, PortadaC, PortadaFB, NickDir, Habilitado, Fecha_Registro FROM $tabla $sqlwherehabiitados ORDER BY Habilitado ASC, Nombre ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$PublicacionID);
			if(!$stmt->execute()) {
				return [false, "Error en la ejecucion"];
			};
			// -----------------
			$buffProductoCategoriaID="";
			$buffNombre="";
			$buffDescripcion="";
			$buffContenido="";
			$buffPortadaH="";
			$buffPortadaM="";
			$buffPortadaS="";
			$buffPortadaT="";
			$buffPortadaC="";
			$buffPortadaFB="";
			$buffNickDir="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$stmt->bind_result(
				$buffProductoCategoriaID, $buffNombre, $buffDescripcion, $buffContenido, $buffPortadaH, $buffPortadaM, $buffPortadaS, $buffPortadaT, $buffPortadaC, $buffPortadaFB, $buffNickDir, $buffHabilitado, $buffFechaRegistro
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoCategoriaID=$buffProductoCategoriaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->Contenido=$buffContenido;
				$buffStd->PortadaH=$buffPortadaH;
				$buffStd->PortadaM=$buffPortadaM;
				$buffStd->PortadaS=$buffPortadaS;
				$buffStd->PortadaT=$buffPortadaT;
				$buffStd->PortadaC=$buffPortadaC;
				$buffStd->PortadaFB="";
				$buffStd->NickDir=$buffNickDir;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderProducto=ProductosNK_Conexion::$folder_productoscategorias.$Resultado->ProductoCategoriaID."/";
			// $this->folderArticuloAbsolute=_Arriero::$direccionWebComplete.PublicacionNK_Conexion::$folder_publicaciones.$this->Fecha_Obj->Format("Y/m/").$this->PublicacionID."/";
			$Resultado->TituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Categorias/".$Resultado->NickDir."/";
			// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
			$Resultado->sqlite_dir=$buffFolderProducto.$Resultado->ProductoCategoriaID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->PortadaH)) {
				$Resultado->PortadaH=$buffFolderProducto.$Resultado->PortadaH;
				$Resultado->PortadaM=$buffFolderProducto.$Resultado->PortadaM;
				$Resultado->PortadaS=$buffFolderProducto.$Resultado->PortadaS;
				$Resultado->PortadaT=$buffFolderProducto.$Resultado->PortadaT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB)) {
				$Resultado->PortadaFB=$buffFolderProducto.$Resultado->PortadaFB;
			}
		}
		//-------------------
		return [true, $Resultados];
	}

	public static function GetIDFromNickDir($NickDir) {
		if(empty($NickDir)) {
			return [false, "Sin Nick a buscar"];
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productoscategorias;
        $sql="SELECT ProductoCategoriaID, Habilitado FROM $tabla WHERE NickDir=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$NickDir);
			$stmt->execute();
			// -----------------
			$buffProductoCategoriaID="";
			$buffHabilitado="";
			$stmt->bind_result(
				$buffProductoCategoriaID,
                $buffHabilitado
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoCategoriaID=$buffProductoCategoriaID;
				$buffStd->Habilitado=$buffHabilitado;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		if(count($Resultados)<1) {
			return [false, "Sin Resultados"];
		}
		return [true, $Resultados[0]];
	}
}

class ProductoMarcaNK {
	public $ProductoMarcaID_In="";

	public $ProductoMarcaID="";
	public $NickDir="";
	public $Nombre="";
	public $Descripcion="";
	public $LogoSVG="";
	public $LogoH="";
	public $LogoM="";
	public $LogoS="";
	public $LogoT="";
	public $LogoC="";
	public $PortadaFB="";
	public $Habilitado="";
	public $FechaRegistro="";
	
	public $dirRaiz="";
	public $link="";
	public $folder_marca="";
	public $sqliteDir="";

	public function __construct($ProductoMarcaID, $dirRaiz) {
		$this->ProductoMarcaID_In=$ProductoMarcaID;
		$this->dirRaiz=$dirRaiz;
		$this->Info_Get();
	}

	public function Info_Get() {
		if(empty($this->ProductoMarcaID_In)) {
			return [false];
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
        $sql="SELECT ProductoMarcaID, NickDir, Nombre, Descripcion, LogoSVG, LogoH, LogoM, LogoS, LogoT, LogoC, PortadaFB, Habilitado, Fecha_Registro FROM $tabla WHERE ProductoMarcaID=? ORDER BY Habilitado ASC, Nombre ASC LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$this->ProductoMarcaID_In);
			$stmt->bind_result(
				$this->ProductoMarcaID,
				$this->NickDir, 
				$this->Nombre, 
				$this->Descripcion, 
				$this->LogoSVG, 
				$this->LogoH, 
				$this->LogoM, 
				$this->LogoS, 
				$this->LogoT, 
				$this->LogoC, 
				$this->PortadaFB,
				$this->Habilitado, 
				$this->FechaRegistro
			);
			if(!$stmt->execute()) {
				return [false, "Error en la ejecucion"];
			};
			$stmt->fetch();
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		if(empty($this->ProductoMarcaID)) {
			return [false];
		}
		//-------------------
		$Fecha_Obj=new DateTime($this->FechaRegistro, new DateTimeZone("America/Bogota"));
		$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
		//--------------------
		$buffFolderProductoMarca=ProductosNK_Conexion::$folder_productosmarcas.$this->ProductoMarcaID."/";
		$this->folder_marca=$buffFolderProductoMarca;
		$this->link="Marca/".$this->NickDir;
		// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
		$this->sqliteDir=$this->dirRaiz.$this->folder_marca.$this->ProductoMarcaID.".sqlite3";
		//-----------------------------
		if(!empty($this->LogoH)) {
			$this->LogoH=$this->folder_marca.$this->LogoH;
			$this->LogoM=$this->folder_marca.$this->LogoM;
			$this->LogoS=$this->folder_marca.$this->LogoS;
			$this->LogoT=$this->folder_marca.$this->LogoT;
		}
		//------Portada Facebook
		if(!empty($this->PortadaFB)) {
			$this->PortadaFB=$this->folder_marca.$this->PortadaFB;
		}
		//-------------------
		return [true];
	}

	public function Info_Set($Nombre, $Descripcion, $NickDir) {
		if(empty($this->ProductoMarcaID)) {
			return [false, "Sin id producto marca a editar"];
		}
		if(empty($Nombre) || empty($NickDir)) {
			return [false, "Sin datos importantes de marca vacios"];
		}
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$query="UPDATE $tabla SET Nombre=?, Descripcion=?, NickDir=? WHERE ProductoMarcaID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("ssss", $Nombre, $Descripcion, $NickDir, $this->ProductoMarcaID);
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

	//**********************************************
	public function LogoImg_Upload($fileimg, $caption) {
		if(empty($this->ProductoMarcaID)) {
			return [false, "Sin ID Producto Marca"];
		}
		//--imagen Portada
		if(!is_uploaded_file($fileimg["tmp_name"])) {
			return [false, "Error LogoImg_Upload: NO se encuentra el archivo subido:".$fileimg];
		}
		$filenameLogoO=$this->ProductoMarcaID."_Logo_.png";
		$filenameLogoH=$this->ProductoMarcaID."_LogoH.webp";
		$filenameLogoM=$this->ProductoMarcaID."_LogoM.webp";
		$filenameLogoS=$this->ProductoMarcaID."_LogoS.webp";
		$filenameLogoT=$this->ProductoMarcaID."_LogoT.webp";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$sql="UPDATE $tabla SET LogoH=?, LogoM=?, LogoS=?, LogoT=?, LogoC=? WHERE ProductoMarcaID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenameLogoH, $filenameLogoM, $filenameLogoS, $filenameLogoT, $caption, $this->ProductoMarcaID);
			if(!$stmt->execute()) {
				return [false, $mysqli->error];
			};
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		$dirFileO=$this->dirRaiz.$this->folder_marca.$filenameLogoO;
		if(move_uploaded_file($fileimg["tmp_name"], $dirFileO)) {
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_marca.$filenameLogoH, 1024, 768, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_marca.$filenameLogoM, 800, 600, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_marca.$filenameLogoS, 640, 480, "webp");
			neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_marca.$filenameLogoT, 200, 200, "webp");
			// neoKiri::Imagen_Convert($dirFileO, $this->dirRaiz.$this->folder_publicacion.$filenamePortadaT, 320, 240, "webp");
			unlink($dirFileO);
		} else {
			return [false, "Error setPortadaImg: Error al mover imagen de portada"];
		}
		return [true, $filenameLogoH];		
	}
	
	public function LogoImg_Del() {
		if(empty($this->ProductoMarcaID)) {
			return [false, "Sin ID Marca"];
		}
		//--imagen Portada
		if(is_file($this->dirRaiz.$this->LogoH)) {
			unlink($this->dirRaiz.$this->LogoH);
			unlink($this->dirRaiz.$this->LogoM);
			unlink($this->dirRaiz.$this->LogoS);
			unlink($this->dirRaiz.$this->LogoT);
		} else {
			return [false, "No se encontro archivo a eliminar"];
		}
		$filenameLogoH="";
		$filenameLogoM="";
		$filenameLogoS="";
		$filenameLogoT="";
		$caption="";
		//----------------------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$sql="UPDATE $tabla SET LogoH=?, LogoM=?, LogoS=?, LogoT=?, LogoC=? WHERE ProductoMarcaID=?";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssssss", $filenameLogoH, $filenameLogoM, $filenameLogoS, $filenameLogoT, $caption, $this->ProductoMarcaID);
			$stmt->execute();
			$stmt->close();
		}
		$mysqli->close();
		//-------------------------------
		
		return [true, $filenameLogoH];		
	}

	public function Habilitar() {
		if(empty($this->ProductoMarcaID)) {
			return [false, "Sin id producto marca a habilitar"];
		}
		// ------------------------------------
		$buffHabilitado=false;
		if((bool)$this->Habilitado) {
			$buffHabilitado=false;
		} else {
			$buffHabilitado=true;
		}
		// ------------------------------------
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$query="UPDATE $tabla SET Habilitado=? WHERE ProductoMarcaID=?";
		$sql=ProductosNK_Conexion::getConexion();
		if($stmt=$sql->prepare($query)){
			$stmt->bind_param("is", $buffHabilitado, $this->ProductoMarcaID);
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

    public static function Marca_New($Nombre, $dirRaiz) {	
		//Preparar datos
		//Año/Mes/Dia/Hora Militar/Minuto/Segundo/
        $FechaNow=new DateTime("now", new DateTimeZone("America/Bogota"));
		$fecha_nowid=$FechaNow->format("YmdHis");
		$fecha_now_sql=$FechaNow->format("Y-m-d H:i:s");
		$fecha_ano=$FechaNow->format("Y");
		$fecha_mes=$FechaNow->format("m");
		//--------------------------
		//Generar Un Identificador de PublicacionNK
		$MarcaID="Marca".hash("crc32", $fecha_nowid.$Nombre);
		$MarcaNickDir=neoKiri::encodeTitulo2Link($Nombre).neoKiri::GenerarRandomID(4, "-");
		// $CategoriaID=$newID;
		//--------------------------
		//Generar Consulta SQL para agregar a la DB 
		$mysqli=ProductosNK_Conexion::getConexion();
		$stat="";
        $tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$sql="INSERT INTO $tabla (ProductoMarcaID, NickDir, Nombre, Fecha_Registro) VALUES (?, ?, ?, ?)";
		//Ejecutar Sentencia
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("ssss",$MarcaID, $MarcaNickDir, $Nombre, $fecha_now_sql);
			if(!$stmt->execute()) {
				return [false, "Marca_New Error: ".$mysqli->error];
			};
			$stmt->close();
		} else {
			$stat=$mysqli->error;
			return [false, "Marca_New Error ".$stat];
		}
		$mysqli->close();
		//--Crear carpetas
		$folder_marca=$dirRaiz.ProductosNK_Conexion::$folder_productosmarcas.$MarcaID."/";
		if(!is_dir($folder_marca)) {
			mkdir($folder_marca,0755,true);
		}
		return [true, $MarcaID];
	}

	public function ProductoMarca_Delete()	{
		if(empty($this->ProductoMarcaID)) {
			return [false,"sin id producto marca a eliminar"];
		}
		// return [false, $this->dirRaiz.$this->folder_marca, "eliminado producto marca"];
		//-------------------
		$mysqli=ProductosNK_Conexion::getConexion();
		//----------------------------
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
		$sql="DELETE FROM $tabla WHERE ProductoMarcaID=? ";
		if($stmt=$mysqli->prepare($sql)) {
			$stmt->bind_param("s",$this->ProductoMarcaID);
			if(!$stmt->execute()) {
				return [false,"ProductoMarca_Delete :: ".$mysqli->error];
			};
			$stmt->close();
		} else {
			return [false,"ProductoMarca_Delete :: ".$mysqli->error];
		}
		//----------------------------
		$mysqli->close();
		//--------------------------------
		$statDelDirectory=neoKiri::delDirectorio($this->dirRaiz.$this->folder_marca, true);
		if(!$statDelDirectory[0]) {
			return [false, $statDelDirectory];
		}
		return [true, $this->ProductoMarcaID, $statDelDirectory];
	}

	public function DBLite_Crear() {
		if(empty($this->ProductoMarcaID)) {
			return [false, "Sin ID Producto Marca"];
		}
		$sqlite=new SQLite3($this->sqliteDir, SQLITE3_OPEN_CREATE|SQLITE3_OPEN_READWRITE,"NeoKiri");
		$sqlite->exec(ProductosNK_Fix::$queryTablaProductosMarcasNK);
		//---------------------------
		$sqlite->exec(neoKiri::$tablaLite_Imagenes);
		$sqlite->exec(neoKiri::$tablaLite_Videos);
		$sqlite->exec(neoKiri::$tablaLite_Archivos);
		$sqlite->exec(neoKiri::$tablaLite_Comentarios);
		//--------------------------------------------
		$sqlite->close();
		return [true, $this->sqliteDir];
	}
}

class ProductosMarcas_Getters {
	public static function GetAll($SoloHabilitados=true) {
		$sqlwherehabiitados="";
		if ($SoloHabilitados) {
			$sqlwherehabiitados="WHERE Habilitado=1";
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
        $sql="SELECT ProductoMarcaID, NickDir, Nombre, Descripcion, LogoSVG, LogoH, LogoM, LogoS, LogoT, LogoC, PortadaFB, Habilitado, Fecha_Registro FROM $tabla $sqlwherehabiitados ORDER BY Habilitado ASC, Nombre ASC";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			// $stmt->bind_param("s",$PublicacionID);
			if(!$stmt->execute()) {
				return [false, "Error en la ejecucion"];
			};
			// -----------------
			$buffProductoMarcaID="";
			$buffNickDir="";
			$buffNombre="";
			$buffDescripcion="";
			$buffLogoSVG="";
			$buffLogoH="";
			$buffLogoM="";
			$buffLogoS="";
			$buffLogoT="";
			$buffLogoC="";
			$buffPortadaFB="";
			$buffHabilitado="";
			$buffFechaRegistro="";
			$stmt->bind_result(
				$buffProductoMarcaID, $buffNickDir, $buffNombre, $buffDescripcion, $buffLogoSVG, $buffLogoH, $buffLogoM, $buffLogoS, $buffLogoT, $buffLogoC, $buffPortadaFB, $buffHabilitado, $buffFechaRegistro
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoMarcaID=$buffProductoMarcaID;
				$buffStd->Nombre=$buffNombre;
				$buffStd->Descripcion=$buffDescripcion;
				$buffStd->LogoSVG_Buff="";
				$buffStd->LogoH=$buffLogoH;
				$buffStd->LogoM=$buffLogoM;
				$buffStd->LogoS=$buffLogoS;
				$buffStd->LogoT=$buffLogoT;
				$buffStd->LogoC=$buffLogoC;
				$buffStd->PortadaFB_Buff="";
				$buffStd->NickDir=$buffNickDir;
				$buffStd->Habilitado=$buffHabilitado;
				$buffStd->Fecha_Registro=$buffFechaRegistro;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		foreach ($Resultados as $Resultado) {
			$Fecha_Obj=new DateTime($Resultado->Fecha_Registro, new DateTimeZone("America/Bogota"));
			$Fecha_Texto=$Fecha_Obj->format("Y-m-d");
			//--------------------
			$buffFolderMarca=ProductosNK_Conexion::$folder_productosmarcas.$Resultado->ProductoMarcaID."/";
			$Resultado->TituloLink=neoKiri::encodeTitulo2Link($Resultado->Nombre);
			$Resultado->link="Marcas/".$Resultado->NickDir."/";
			// $this->link_complete=_Arriero::$direccionWebComplete.$this->link;
			$Resultado->sqlite_dir=$buffFolderMarca.$Resultado->ProductoMarcaID.".sqlite3";
			//-----------------------------
			if(!empty($Resultado->LogoH)) {
				$Resultado->LogoH=$buffFolderMarca.$Resultado->LogoH;
				$Resultado->LogoM=$buffFolderMarca.$Resultado->LogoM;
				$Resultado->LogoS=$buffFolderMarca.$Resultado->LogoS;
				$Resultado->LogoT=$buffFolderMarca.$Resultado->LogoT;
			}
			//------Portada Facebook
			if(!empty($Resultado->PortadaFB_Buff)) {
				$Resultado->PortadaFB=$buffFolderMarca.$Resultado->PortadaFB_Buff;
			}
			unset($Resultado->PortadaFB_Buff);
		}
		//-------------------
		return [true, $Resultados];
	}

	public static function GetIDFromNickDir($NickDir) {
		if(empty($NickDir)) {
			return [false, "Sin Nick a buscar"];
		}
		$mysqli=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
        $sql="SELECT ProductoMarcaID, Habilitado FROM $tabla WHERE NickDir=? LIMIT 1";
		$Resultados=[];
		if($stmt=$mysqli->prepare($sql)) {
			// -----------------
			$stmt->bind_param("s",$NickDir);
			$stmt->execute();
			// -----------------
			$buffProductoMarcaID="";
			$buffHabilitado="";
			$stmt->bind_result(
				$buffProductoMarcaID,
                $buffHabilitado
			);
			while($stmt->fetch()){
				$buffStd=new stdClass();
				$buffStd->ProductoMarcaID=$buffProductoMarcaID;
				$buffStd->Habilitado=$buffHabilitado;
				array_push($Resultados, $buffStd);
			};
			$stmt->close();
		} else {
			$mysqli->close();
			return [false,"STMT Info_Get - "];
		}
		$mysqli->close();
		//-------------------
		if(count($Resultados)<1) {
			return [false, "Sin Resultados"];
		}
		return [true, $Resultados[0]];
	}
}

class ProductosNK_Fix {
    
    public static $queryTablaProductosCategoriasNK="CREATE TABLE IF NOT EXISTS ProductosNK_Categorias (
        ProductoCategoriaID varchar(255) not null primary key,
        NickDir varchar(255) not null unique,
        Nombre varchar(255) not null,
        Descripcion varchar(255),
        Contenido text,
        CategoriaIconSVG varchar(255),
        CategoriaIconMinSVG varchar(255),
        PortadaH varchar(255),
        PortadaM varchar(255),
        PortadaS varchar(255),
        PortadaT varchar(255),
        PortadaC varchar(255),
        PortadaFB varchar(255),
        Tabla_Asignada varchar(255),
        Habilitado int(11) default 0,
        Fecha_Registro datetime not null
    )";

    public static $queryTablaProductosMarcasNK="CREATE TABLE IF NOT EXISTS ProductosNK_Marcas (
        ProductoMarcaID varchar(255) not null primary key,
        NickDir varchar(255) not null unique,
        Nombre varchar(255) not null,
        Descripcion varchar(255),
        LogoSVG varchar(255),
        LogoH varchar(255),
        LogoM varchar(255),
        LogoS varchar(255),
        LogoT varchar(255),
        LogoC varchar(255),
        PortadaFB varchar(255),
        Contenido text,
        Habilitado int(11) not null default 0,
        Fecha_Registro datetime not null
    )";

    public static $queryTablaProductosNK="CREATE TABLE IF NOT EXISTS ProductosNK(
        ProductoID varchar(255) not null primary key,
        ProductoNickDir varchar(255) not null unique,
        ProductoCodeID varchar(255) not null unique,
        ProductoCategoriaID varchar(255) not null,
        MarcaID varchar(255) not null,
        Nombre varchar(255) not null,
        Descripcion varchar(255),
        Contenido text,
        CaracteristicasJSON text,
        Disponibles int(11) default 0,
        PrecioDistribuidor int(11) default 0,
        PrecioFinal int(11) default 0,
        PrecioFinalOferta int(11) default 0,
        PortadaH varchar(255),
        PortadaM varchar(255),
        PortadaS varchar(255),
        PortadaT varchar(255),
        PortadaC varchar(255),
        PortadaFB varchar(255),
        UrlHomepage varchar(255),
        EnOferta int(11) default 0,
        Visitas int(11) default 0,
        Habilitado int(11) default 0,
        Fecha_Registro datetime not null,
        FOREIGN KEY (ProductoCategoriaID) REFERENCES ProductosNK_Categorias(ProductoCategoriaID),
        FOREIGN KEY (MarcaID) REFERENCES ProductosNK_Marcas(ProductoMarcaID)
    )";
    
	// Categoria --- Album | Vitrina
    public static $queryTablaProductosFotosNK="CREATE TABLE IF NOT EXISTS ProductosNK_Fotos (
        ProductoFotoID varchar(255) not null primary key,
        ProductoID varchar(255) not null,
        Categoria varchar(255) not null default 'Album',
        Nombre varchar(255),
        ImgH varchar(255),
        ImgM varchar(255),
        ImgS varchar(255),
        ImgT varchar(255),
        ImgC varchar(255),
        Principal int(11) not null default 0,
        Habilitado int(11) default 1,
        Fecha_Registro datetime not null,
        FOREIGN KEY (ProductoID) REFERENCES ProductosNK(ProductoID)
    )";

    public static function TablaCrear_ProductoCategoriaNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(ProductosNK_Fix::$queryTablaProductosCategoriasNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_ProductoCategoriaNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_ProductoCategoriaNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_ProductoCategoriaNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        $queryTabla="DROP TABLE ProductosNK_Categorias";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_ProductoCategoriaNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_ProductoCategoriaNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

	public static function TablaCrear_ProductoMarcaNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(ProductosNK_Fix::$queryTablaProductosMarcasNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_ProductoMarcaNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_ProductoMarcaNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_ProductoMarcaNK() {
        $mysql=ProductosNK_Conexion::getConexion();
		$tabla=ProductosNK_Conexion::$tabla_productosmarcas;
        $queryTabla="DROP TABLE $tabla";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_ProductoMarcaNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_ProductoMarcaNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaCrear_ProductoNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(ProductosNK_Fix::$queryTablaProductosNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_ProductoNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_ProductoNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_ProductoNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        $queryTabla="DROP TABLE ProductosNK";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_ProductoNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_ProductoNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaCrear_ProductosFotosNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        if($stmt=$mysql->prepare(ProductosNK_Fix::$queryTablaProductosFotosNK)) {
            if(!$stmt->execute()) {
                return [false, "TablaCrear_ProductosFotosNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaCrear_ProductosFotosNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablaEliminar_ProductosFotosNK() {
        $mysql=ProductosNK_Conexion::getConexion();
        $queryTabla="DROP TABLE ProductosNK_Fotos";
        if($stmt=$mysql->prepare($queryTabla)) {
            if(!$stmt->execute()) {
                return [false, "TablaEliminar_ProductosFotosNK: ".$mysql->error];
            };
            $stmt->close();
        } else {
            return [false, "TablaEliminar_ProductosFotosNK: ".$mysql->error];
        }
        $mysql->close();
        return [true];
    }

    public static function TablasAll_Crear() {
		ProductosNK_Fix::TablaCrear_ProductoCategoriaNK();
		ProductosNK_Fix::TablaCrear_ProductoMarcaNK();
		ProductosNK_Fix::TablaCrear_ProductoNK();
		ProductosNK_Fix::TablaCrear_ProductosFotosNK();
	}

}

?>