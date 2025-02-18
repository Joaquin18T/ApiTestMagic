<?php

require_once '../models/Product.php';

$product = new Product();

header('Content-Type: application/json');

$verbo = $_SERVER['REQUEST_METHOD'];

switch($verbo){
  case 'GET':
    echo json_encode($product->list_products());
    break;
  case 'POST':
    $datosRecibidos = json_decode(file_get_contents('php://input'), true);

    if(!isset($datosRecibidos['nombre'], $datosRecibidos['descripcion'], $datosRecibidos['precio'], $datosRecibidos['stock'],
      $datosRecibidos['idcategoria'])){

        echo json_encode(['error'=>'Te falto un campo']);
        http_response_code(400);

        exit;
    }

    $params = [
      'nombre'=>$datosRecibidos['nombre'],
      'descripcion'=>$datosRecibidos['descripcion'],
      'precio'=>$datosRecibidos['precio'],
      'stock'=>$datosRecibidos['stock'],
      'idcategoria'=>$datosRecibidos['idcategoria']
    ];

    echo json_encode(['idproducto'=>$product->add($params)]);
    break;
  case 'PUT':
    $datosRecibidos = json_decode(file_get_contents('php://input'), true);

    if(!isset($datosRecibidos['idproducto'],$datosRecibidos['nombre'], $datosRecibidos['descripcion'], $datosRecibidos['precio'], 
      $datosRecibidos['stock'])){

        echo json_encode(['error'=>'Te falto un campo']);
        http_response_code(400);

        exit;
    }

    $params = [
      'idproducto'=>$datosRecibidos['idproducto'],
      'nombre'=>$datosRecibidos['nombre'],
      'descripcion'=>$datosRecibidos['descripcion'],
      'precio'=>$datosRecibidos['precio'],
      'stock'=>$datosRecibidos['stock']
    ];

    echo json_encode(['updated'=>$product->update_product($params)]);
    break;
  case 'DELETE':

    if(!isset($_GET['idproducto'])){
      echo json_encode(['error'=>"Falta el id"]);
      http_response_code(400);

      exit;
    }
    $params = ['idproducto'=>$_GET['idproducto']];
    echo json_encode(['deleted'=>$product->delete_product($params)]);
    break;
}