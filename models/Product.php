<?php

require_once 'ExecQuery.php';

class Product extends ExecQuery{
  public function list_products():array{
    return parent::getData("sp_list_products");
  }

  public function add($params=[]):int{
    try{
      $pdo = parent::getConexion();
      $cmd = $pdo->prepare("CALL sp_add_product(@idproducto,?,?,?,?,?)");
      $cmd->execute(
        array(
          $params['nombre'],
          $params['descripcion'],
          $params['precio'],
          $params['stock'],
          $params['idcategoria']
        )
      );
      $respuesta = $pdo->query("SELECT @idproducto AS idproducto")->fetch(PDO::FETCH_ASSOC);
      return $respuesta['idproducto'];
    }catch(Exception $e){
      die($e->getMessage());
      return -1;
    }
  }

  public function update_product($params=[]):bool{
    try{
      $status = false;
      $cmd = parent::execQ("CALL sp_update_product(?,?,?,?,?)");
      $status = $cmd->execute(
        array(
          $params['idproducto'],
          $params['nombre'],
          $params['descripcion'],
          $params['precio'],
          $params['stock']
        )
      );
      return $status;
    }catch(Exception $e){
      die($e->getMessage());
      return false;
    }
  }

  public function delete_product($params=[]):bool{
    try{
      $status = false;
      $cmd = parent::execQ("DELETE FROM productos WHERE id=?");
      $status=$cmd->execute(
        array($params['idproducto'])
      );
      return $status;
    }catch(Exception $e){
      die($e->getMessage());
      return false;
    }
  }
}

//$prod = new Product();

//echo json_encode($prod->list_products());

// $inserted = $prod->add([
//   'nombre'=>'Camiseta Club Barcelona',
//   'descripcion'=>'Camiseta del Club Barcelona 2024-2025, alterna',
//   'precio'=>120.6,
//   'stock'=>50,
//   'idcategoria'=>1
// ]);
// echo $inserted;

// $updated = $prod->update_product([
//   'idproducto'=>3,
//   'nombre'=>'Camiseta Club Barcelona',
//   'descripcion'=>'Camiseta del Club Barcelona 2024-2025, oficial',
//   'precio'=>125.6,
//   'stock'=>27,
// ]);

// echo $updated;

// $deleted = $prod->delete_product([
//   'idproducto'=>3
// ]);

// var_dump($deleted);