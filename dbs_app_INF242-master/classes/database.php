<?php

class database{

    function opencon(): PDO{
        return new PDO("mysql:host=localhost; dbname=lumbera_dbs", username:"root", password: "");
    }

function insertUser($email, $user_password_hash, $is_active){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO Users (username, user_password_hash, is_active) VALUES (?,?,?) ");
        $stmt->execute([$email, $user_password_hash, $is_active]);
        $user_id =$con->lastInsertId();
        $con->commit();
        return $user_id;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
         
        }
        throw $e;
    }
}

function insertBorrowers($email, $borrower_firstname, $borrower_lastname, $borrower_phone_number, $borrower_member_since, $is_active){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO borrowers (borrower_email, borrower_firstname, borrower_lastname, borrower_phone_number, borrower_member_since, is_active) VALUES (?,?,?,?,?,?) ");
        $stmt->execute([$email, $borrower_firstname, $borrower_lastname, $borrower_phone_number, $borrower_member_since, $is_active]);
        $borrower_id =$con->lastInsertId();
        $con->commit();
        return $borrower_id;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}
function insertBorroweruser($user_id, $borrower_id){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO borroweruser (user_id, borrower_id) VALUES (?,?) ");
        $stmt->execute([$user_id, $borrower_id]);
        $borrower_id =$con->lastInsertId();
        $con->commit();
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}
}

?>