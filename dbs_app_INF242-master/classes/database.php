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
        $stmt = $con->prepare("INSERT INTO books (book_title, book_isbn, book_publication, book_edition, book_publisher) VALUES (?,?,?,?,?) ");
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
            books.book_id,
            books.book_title,
            books.book_isbn,
            books.book_publication,
            books.book_publisher,
            COUNT(bookcopy.copy_id) AS Copies,
            SUM(bookcopy.status = 'AVAILABLE') AS Available_Copies
            FROM
            books
            LEFT JOIN bookcopy ON books.book_id = bookcopy.book_id
            GROUP BY books.book_id")->fetchAll();
        }

function updateBook($book_id, $title, $isbn, $year, $publisher)
{
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        $stmt = $con->prepare("
            UPDATE books
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
    return $con->query("SELECT * FROM Authors ORDER BY author_id")->fetchAll();
}

function viewgenres(){
    $con = $this->opencon();
    return $con->query("SELECT * FROM Genres ORDER BY genre_name")->fetchAll();
}

function countBook(){
    $con = $this->opencon();
    return $con->query("SELECT COUNT(*) AS total_books FROM books")->fetchColumn();
}

function countAvailBook(){
    $con = $this->opencon();
    return $con->query("SELECT SUM(status= 'AVAILABLE') as Total_Books from bookcopy")->fetchColumn();
}

function countOpenLoans(){
    $con = $this->opencon();
    return $con->query("SELECT COUNT(*) AS open_loans FROM loan WHERE loan_status = 'OPEN'")->fetchColumn();
}

function countOverdueLoans(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) AS overdue_loans FROM loan JOIN loanitem ON loan.loan_id = 
        loanitem.loan_id WHERE loan.loan_status = 'OPEN' AND loanitem.li_duedate < CURDATE()")->fetchColumn();
}

function countAuthors(){
    $con = $this->opencon();
    return $con->query("SELECT COUNT(*) AS total_authors FROM Authors")->fetchColumn();
}
function countGenres(){
    $con = $this->opencon();
    return $con->query("SELECT COUNT(*) AS total_genres FROM Genres")->fetchColumn();
}

function viewloans() {
    $con = $this->opencon();
    return $con->query ("SELECT loan.loan_id, 
    borrowers.borrower_firstname, 
    borrowers.borrower_lastname, 
    loan.loan_status AS loan_status, 
    loan.loan_date, 
    users.username
    FROM 
       loan 
    JOIN borrowers ON loan.borrower_id = borrowers.borrower_id 
    JOIN users ON loan.processed_by_user_id = users.user_id 
    ORDER BY loan.loan_date DESC")->fetchAll();
    }

function insertAuthor($firstname, $lastname, $birthYear, $nationality){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO authors (author_firstname, author_lastname, author_birth_year, author_nationality) VALUES (?,?,?,?) ");
        $stmt->execute([$firstname, $lastname, $birthYear, $nationality]);
        $author_id =$con->lastInsertId();
        $con->commit();
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}

function insertGenre($genreName){
    $con = $this->opencon();
    try{
        $con->beginTransaction();
        $stmt = $con->prepare("INSERT INTO genres (genre_name) VALUES (?) ");
        $stmt->execute([$genreName]);
        $genre_id =$con->lastInsertId();
        $con->commit();
        return true;
    }catch(PDOException $e){
        if($con->inTransaction()){
            $con->rollBack();
        }
        throw $e;
    }
}
function deletebooks($book_id){
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        // First, delete all copies of the book
        $stmtCopies = $con->prepare("DELETE FROM bookcopy WHERE book_id = ?");
        $stmtCopies->execute([$book_id]);
 
        $stmtBA = $con->prepare("DELETE FROM bookauthor WHERE book_id = ?");
        $stmtBA->execute([$book_id]);
 
        $stmtGenre = $con->prepare("DELETE FROM bookgenre WHERE book_id = ?");
        $stmtGenre->execute([$book_id]);
 
        // Then, delete the book itself
        $stmtBook = $con->prepare("DELETE FROM books WHERE book_id = ?");
        $stmtBook->execute([$book_id]);
 
        $con->commit();
        return true; // Successfully deleted
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
}

function deleteAuthor($author_id){
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        // First, delete associations from bookauthor
        $stmtBA = $con->prepare("DELETE FROM bookauthor WHERE author_id = ?");
        $stmtBA->execute([$author_id]);
 
        // Then, delete the author itself
        $stmtAuthor = $con->prepare("DELETE FROM Authors WHERE author_id = ?");
        $stmtAuthor->execute([$author_id]);
 
        $con->commit();
        return true;
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
}

function deleteGenre($genre_id){
    $con = $this->opencon();
 
    try {
        $con->beginTransaction();
 
        // First, delete associations from bookgenre
        $stmtBG = $con->prepare("DELETE FROM bookgenre WHERE genre_id = ?");
        $stmtBG->execute([$genre_id]);
 
        // Then, delete the genre itself
        $stmtGenre = $con->prepare("DELETE FROM Genres WHERE genre_id = ?");
        $stmtGenre->execute([$genre_id]);
 
        $con->commit();
        return true;
 
    } catch (PDOException $e) {
        if ($con->inTransaction()) {
            $con->rollBack();
        }
        throw $e;
    }
}
}





?>