<?php
require_once 'ExecQuery.php';

class User extends ExecQuery{
  public function list_users():array{
    $query = parent::execQ("SELECT * FROM usuarios");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
  }

  public function add($params=[]):int{
    try{
      $pdo = parent::getConexion();
      $cmd = $pdo->prepare("CALL sp_add_usuario(@idusuario,?,?,?,?,?)");
      $cmd->execute(
        array(
          $params['nombre'],
          $params['correo'],
          $params['password'],
          $params['telefono'],
          $params['direccion']
        )
      );
      $respuesta = $pdo->query("SELECT @idusuario AS idusuario")->fetch(PDO::FETCH_ASSOC);
      return $respuesta['idusuario'];
    }catch(Exception $e){
      die($e->getMessage());
      return -1;
    }
  }

  public function update_user($params=[]):bool{
    try{
      $status = false;
      $cmd= parent::execQ("CALL sp_update_usuario(?,?,?,?)");
      $status = $cmd->execute(
        array(
          $params['idusuario'],
          $params['nombre'],
          $params['telefono'],
          $params['direccion']
        )
      );
      return $status;
    }catch(Exception $e){
      die($e->getMessage());
      return false;
    }
  }

  public function delete_usuario($params=[]):bool{
    try{
      $status = false;
      $cmd = parent::execQ("DELETE FROM usuarios WHERE id = ?");
      $status = $cmd->execute(
        array($params['idusuario'])
      );
      return $status;
    }catch(Exception $e){
      die($e->getMessage());
      return false;
    }
  }
}

// $user = new User();

// $data = $user->add([
//   'nombre'=>'Pedro Quispe',
//   'correo'=>'pedro@gmail.com',
//   'password'=>'345345',
//   'telefono'=>'8678777',
//   'direccion'=>'Calle los Claveles'
// ]);

// echo $data;

// $updated = $user->update_user([
//   'idusuario'=>3,
//   'nombre'=>'PEDRO JUAN PEREZ',
//   'telefono'=>'94375834543',
//   'direccion'=>'Calle los Gerundios'
// ]);

// echo $updated;

// $deleted = $user->delete_usuario(['idusuario'=>3]);
// echo $deleted;

//echo json_encode($user->list_users());