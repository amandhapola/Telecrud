<?php
// require 'Slim/Slim.php'
require 'vendor/autoload.php';
$app = new \Slim\Slim();
// $app=new Slim();
$app->get('/users','getUsers');
$app->post('/add_user','addUser');
$app->get('/users/:id', 'getUser');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');
$app->run();


function deleteUser($id){
	$sql = "DELETE FROM users WHERE id=".$id;
	try{
		$db=getConnection();
		$stmt = $db->query($sql);
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	}catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateUser($id){
	$request = \Slim\Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "UPDATE users SET username=:username, first_name=:first_name, last_name=:last_name, address=:address WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("username", $user->username);
		$stmt->bindParam("first_name", $user->first_name);
		$stmt->bindParam("last_name", $user->last_name);
		$stmt->bindParam("address", $user->address);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}

}

function getUser($id){
	$sql = "select * FROM users WHERE id=".$id." ORDER BY id";
	try{
		$db=getConnection();
		$stmt = $db->query($sql);  
		$wines = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($wines);
	}catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}
function getUsers(){
	 $sql = "select * FROM users ORDER BY id";
	 try{
	 	$db=getConnection();
	 	$stmt=$db->query($sql);
	 	$wines=$stmt->fetchAll(PDO::FETCH_OBJ);
	 	$db=null;
	 	echo json_encode($wines);
	 }catch(PDOException $e){
	 	echo '{"error":{"text":'. $e->getMessage() .'}}';
	 }
}

function addUser(){
	$request=\Slim\Slim::getInstance()->request();
	$user=json_decode($request->getBody());
	$sql = "INSERT INTO users (username, first_name, last_name, address) VALUES (:username, :first_name, :last_name, :address)";
	try{
		$db=getConnection();
		$stmt=$db->prepare($sql);
		$stmt->bindParam("username", $user->username);
    	$stmt->bindParam("first_name", $user->first_name);
    	$stmt->bindParam("last_name", $user->last_name);
    	$stmt->bindParam("address", $user->address);
    	$stmt->execute();
    	$user->id = $db->lastInsertId();
    	$db=null;
    	echo json_encode($user);
	}catch(PDOException $e) {
    	echo '{"error":{"text":'. $e->getMessage() .'}}';
  	}
}


function getConnection() {
  $dbhost="localhost";
  $dbuser="root";
  $dbpass="root";
  $dbname="crud";
  $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo "connected";
  return $dbh;
}
?>