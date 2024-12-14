<?php

define("NK_USER", "TechCoSenaUser");
define("NK_PASS", "TechCoSenaPass");
define("DBNAME_MAIN", "TechCoSena_MainDB");

function UsuarioDB_Crear() {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $tabla="CREATE TABLE IF NOT EXISTS UsuariosLoginReact(
    id_usuario int(11) not null primary key auto_increment,
    nombres varchar(255) not null,
    apellidos varchar(255) not null,
    correo varchar(255) not null,
    contrasenia varchar(255) not null,
    fecha_registro datetime not null
    )";
    if(!$stmt=$mysqli->prepare($tabla)) {
        return [false];
    }
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
}

function UsuarioNew($nombre, $apellido, $correo, $contrasenia) {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $query="INSERT INTO UsuariosLoginReact (nombres, apellidos, correo, contrasenia, fecha_registro) VALUES (?,?,?,?,?)";
    $fechaObj=new DateTime("now", new DateTimeZone("America/Bogota"));
    $fechasql=$fechaObj->format("Y-m-d H:i:s");
    if(!$stmt=$mysqli->prepare($query)) {
        return [false];
    }
    $stmt->bind_param("sssss", $nombre, $apellido, $correo, $contrasenia, $fechasql);
    if(!$stmt->execute()) {
        return [false, $mysqli->error];
    };
    $stmt->close();
    $mysqli->close();
    return [true];
}

function UsuariosGet() {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $query="SELECT id_usuario, nombres, apellidos, correo, fecha_registro FROM UsuariosLoginReact";
    if(!$stmt=$mysqli->prepare($query)) {
        return [false];
    }
    $stmt->execute();

    $buffID="";
    $buffNombres="";
    $buffApellidos="";
    $buffCorreo="";
    $buffFecha="";

    $buffResultados=[];
    $stmt->bind_result($buffID, $buffNombres, $buffApellidos, $buffCorreo, $buffFecha);
    
    while ($stmt->fetch()) {
        $buffStd=new stdClass();
        $buffStd->id=$buffID;
        $buffStd->nombres=$buffNombres;
        $buffStd->apellidos=$buffApellidos;
        $buffStd->correo=$buffCorreo;
        $buffStd->fecha=$buffFecha;
        array_push($buffResultados, $buffStd);
    }
    $stmt->close();
    $mysqli->close();
    return [true, $buffResultados];
}

function UsuarioGetID($id) {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $query="SELECT id_usuario, nombres, apellidos, correo, fecha_registro FROM UsuariosLoginReact WHERE id_usuario=? LIMIT 1";
    if(!$stmt=$mysqli->prepare($query)) {
        return [false];
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $buffID="";
    $buffNombres="";
    $buffApellidos="";
    $buffCorreo="";
    $buffFecha="";

    $buffResultados=[];
    $stmt->bind_result($buffID, $buffNombres, $buffApellidos, $buffCorreo, $buffFecha);
    
    while ($stmt->fetch()) {
        $buffStd=new stdClass();
        $buffStd->id=$buffID;
        $buffStd->nombres=$buffNombres;
        $buffStd->apellidos=$buffApellidos;
        $buffStd->correo=$buffCorreo;
        $buffStd->fecha=$buffFecha;
        array_push($buffResultados, $buffStd);
    }
    $stmt->close();
    $mysqli->close();
    if(count($buffResultados)<1){
        return [false, "No se encontraron resultados"];

    }
    return [true, $buffResultados[0]];
}

function UsuarioEdit($usuarioid, $nombre, $apellido, $correo, $contrasenia) {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $query="UPDATE UsuariosLoginReact SET nombres=?, apellidos=?, correo=?, contrasenia=? WHERE id_usuario=?";
    if(!$stmt=$mysqli->prepare($query)) {
        return [false];
    }
    $stmt->bind_param("ssssi", $nombre, $apellido, $correo, $contrasenia, $usuarioid);
    if(!$stmt->execute()) {
        return [false, $mysqli->error];
    };
    $stmt->close();
    $mysqli->close();
    return [true];
}

function UsuarioDel($UsuarioID) {
    $mysqli=new MySQLi("localhost", NK_USER, NK_PASS, DBNAME_MAIN);
    $query="DELETE FROM UsuariosLoginReact WHERE id_usuario=?";
    if(!$stmt=$mysqli->prepare($query)) {
        return [false];
    }
    $stmt->bind_param("i", $UsuarioID);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return [true];
}

// -----------------------------------

UsuarioDB_Crear();

$ResponseObj=new stdClass();
$ResponseObj->RespuestaBool=false;
$ResponseObj->RespuestaError="";

if(isset($_GET["Usuarios"])){
    switch ($_GET["Usuarios"]) {
        case 'GetAll':
            $USGet_Stat=UsuariosGet();
            if($USGet_Stat[0]) {
                $ResponseObj->Usuarios=$USGet_Stat[1];
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$USGet_Stat[1];
            }
        break;
    }
}

if(isset($_GET["Usuario"])){
    switch ($_GET["Usuario"]) {
        case 'UsuarioGet':
            $UsuarioGet_ID=$_GET["UsuarioID"];
            $UsuarioGet_Stat=UsuarioGetID($UsuarioGet_ID);
            if($UsuarioGet_Stat[1]) {
                $ResponseObj->RespuestaBool=true;
                $ResponseObj->Usuario=$UsuarioGet_Stat[1];
            } else {
                $ResponseObj->RespuestaError=$UsuarioGet_Stat[1];
            }
        break;
    }
}

if(isset($_POST["UsuarioForm"])){
    switch ($_POST["UsuarioForm"]) {
        case 'UsuarioNew':
            $UFN_Nombres=$_POST["nombres"];
            $UFN_Apellidos=$_POST["apellidos"];
            $UFN_Correo=$_POST["correo"];
            $UFN_Contrasenia=$_POST["contrasenia"];
            $UFN_Status=UsuarioNew($UFN_Nombres, $UFN_Apellidos, $UFN_Correo, $UFN_Contrasenia);
            if($UFN_Status[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UFN_Status[1];
            }
        break;

        case 'UsuarioEdit':
            $UFE_ID=$_POST["usuarioid"];
            $UFE_Nombres=$_POST["nombres"];
            $UFE_Apellidos=$_POST["apellidos"];
            $UFE_Correo=$_POST["correo"];
            $UFE_Contrasenia=$_POST["contrasenia"];
            $UFE_Status=UsuarioEdit($UFE_ID, $UFE_Nombres, $UFE_Apellidos, $UFE_Correo, $UFE_Contrasenia);
            if($UFE_Status[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UFE_Status[1];
            }
        break;

        case 'UsuarioDel':
            $UFD_ID=$_POST["usuarioid"];
            $UFD_Status=UsuarioDel($UFD_ID);
            if($UFD_Status[0]) {
                $ResponseObj->RespuestaBool=true;
            } else {
                $ResponseObj->RespuestaError=$UFD_Status[1];
            }
        break;
    }

}

// $ResponseObj->GET=$_GET;
// $ResponseObj->POST=$_POST;
// $ResponseObj->FILES=$_FILES;

echo json_encode($ResponseObj);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


?>