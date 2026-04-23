<?php

class database{

    function opencon(): PDO{
        return new PDO("mysql:host=localhost; dbname=DBS_lumbera", username:"root", password: "");
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
function insertBorroweraddress($borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO Borroweraddress (borrower_id, ba_house_number, ba_street, ba_barangay, ba_city, ba_province, ba_postal_code, is_primary) VALUES (?,?,?,?,?,?,?,?) ");
        $stmt->execute([$borrower_id, $ba_house_number, $ba_street, $ba_barangay, $ba_city, $ba_province, $ba_postal_code, $is_primary]);
        $ba_id =$con->lastInsertId();
        $con->commit();
        return $ba_id;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
         
        }
        throw $e;
    }
}

function insertBooks($book_title, $book_isbn, $book_publication, $book_edition, $book_publisher){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO Books (book_title, book_isbn, book_publication, book_edition, book_publisher) VALUES (?,?,?,?,?) ");
        $stmt->execute([$book_title, $book_isbn, $book_publication, $book_edition, $book_publisher]);
        $book_id =$con->lastInsertId();
        $con->commit();
        return $book_id;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}

function insertbookcopy($book_id, $status){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO bookcopy (book_id, status) VALUES (?,?) ");
        $stmt->execute([$book_id, $status]);
        $book_id =$con->lastInsertId();
        $con->commit(); 
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}

function viewbook()
        {
            $con = $this->opencon();
            return $con->query("SELECT
            Books.book_id,
            Books.book_title,
            Books.book_isbn,
            Books.book_publication,
            Books.book_publisher,
            COUNT(BookCopy.copy_id) AS Copies,
            SUM(BookCopy.status = 'Available') AS Available_Copies
            FROM
            Books
            JOIN BookCopy ON Books.book_id = BookCopy.book_id
            GROUP BY 1")->fetchAll();
        }

function updateBook($book_id, $title, $isbn, $year, $publisher)
{
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE Books
            SET book_title = ?,
                book_isbn = ?,
                book_publication = ?,
                book_publisher = ?
            WHERE book_id = ?
        ");
 
        $stmt->execute([$title, $isbn, $year, $publisher, $book_id]);
 
        $con->commit();
        return true; // Successfully updated
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
}

function insertbookauthors($book_id, $author_id){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO bookauthor (book_id, author_id) VALUES (?,?) ");
        $stmt->execute([$book_id, $author_id]);
        $book_id =$con->lastInsertId();
        $con->commit(); 
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}

function insertbookgenres($book_id, $genre_id){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO bookgenre (book_id, genre_id) VALUES (?,?) ");
        $stmt->execute([$book_id, $genre_id]);
        $book_id =$con->lastInsertId();
        $con->commit(); 
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}

function viewborrowers(){
    $con = $this->opencon();
    return $con->query("SELECT * FROM borrowers")->fetchAll();
}

function viewbooks(){
    $con = $this->opencon();
    return $con->query("SELECT * FROM books")->fetchAll();
}

function viewauthors(){
    $con = $this->opencon();
    return $con->query("SELECT * FROM authors")->fetchAll();
}

function viewgenres(){
    $con = $this->opencon();
    return $con->query("SELECT * FROM genres")->fetchAll();
}
}

?>