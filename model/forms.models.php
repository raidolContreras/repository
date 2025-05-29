<?php 
include "conection.php";

class FormsModel {

    static public function mdlUploadUsers($data) {
        $pdo = Conexion::conectar();
        $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname, email=:email,password=:password,level=:level WHERE idUser = :idUser";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":firstname", $data["firstname"], PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $data["lastname"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
        $stmt->bindParam(":level", $data["level"], PDO::PARAM_INT);
        $stmt->bindParam(":idUser", $data["idUser"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $response = "ok";
        } else {
            $response = "error";
        }
        $stmt->closeCursor();
        $stmt = null;
        return $response;
    }

    static public function mdlSearchUsers($email = null) {
        $pdo = Conexion::conectar();

        if ($email == null) {
            $sql = "SELECT * FROM users WHERE status = 1";
        } else {
            $sql = "SELECT * FROM users WHERE status = 1 AND email = :email";
        }

        $stmt = $pdo->prepare($sql);
        
        if ($email == null) {
            $stmt->execute();
            $response = $stmt->fetchAll();
        } else {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();
            $response = $stmt->fetch();
        }
        $stmt->closeCursor();
        $stmt = null;
        return $response;
    }

    static public function mdlAddUser($data){
        $pdo = Conexion::conectar();
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, level) VALUES (:firstname, :lastname, :email, :password, :level)");
        $stmt->bindParam(":firstname", $data["firstname"], PDO::PARAM_STR);
        $stmt->bindParam(":lastname", $data["lastname"], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data["password"], PDO::PARAM_STR);
        $stmt->bindParam(":level", $data["level"], PDO::PARAM_INT);
        if ($stmt->execute()) {
            $response = "ok";
        } else {
            $response = "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }
    
}