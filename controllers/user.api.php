<?php

require_once '../models/User.php';

$user = new User();

header('Content-Type: application/json');

$verbo = $_SERVER['REQUEST_METHOD'];

function sendErrorResponse($message, $statusCode = 400) {
  echo json_encode(['error' => $message]);
  http_response_code($statusCode);
  exit; //detiene la ejecucion del codigo
}

switch($verbo){
  case 'GET':
    echo json_encode($user->list_users());
    break;
  case 'POST':
    $datosRecibidos = json_decode(file_get_contents('php://input'), true);
    $params = [
      'nombre'=>$datosRecibidos['nombre'],
      'correo'=>$datosRecibidos['correo'],
      'password'=>$datosRecibidos['password'],
      'telefono'=>$datosRecibidos['telefono'],
      'direccion'=>$datosRecibidos['direccion']
    ];
    echo json_encode(['idusuario'=>$user->add($params)]);
    break;
  case 'PUT':
    $datosRecibidos = json_decode(file_get_contents('php://input'), true);
    $params=[
      'idusuario'=>$datosRecibidos['idusuario'],
      'nombre'=>$datosRecibidos['nombre'],
      'telefono'=>$datosRecibidos['telefono'],
      'direccion'=>$datosRecibidos['direccion']
    ];
    echo json_encode(['updated'=>$user->update_user($params)]);
    break;
  case 'DELETE':
    if(!isset($_GET['idusuario'])){
      sendErrorResponse("Te falta el id del usuario");
    }
    $params=["idusuario"=>$_GET['idusuario']];
    echo json_encode(['deleted'=>$user->delete_usuario($params)]);
    break;
}