<?php

class database{

    function opencon(): PDO{
        return new PDO("mysql:host=localhost; dbname=INF242_LMS", username:"root", password: "");
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
        $stmt = $con->prepare("INSERT INTO borrowers (email, borrower_firstname, borrower_lastname, borrower_phone_number, borrower_member_since, is_active) VALUES (?,?,?,?,?,?) ");
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
}

?>