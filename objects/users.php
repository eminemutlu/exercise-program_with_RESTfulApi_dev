<?php
class Users{
 
    private $conn;
    private $table_name = "users";
    private $table_name1 = "users_plan";
    private $table_name2 = "logs";


    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $status;
    public $user_id;
    public $mail_situation;
 
    public function __construct($db){
        $this->conn = $db;
    }

    
    function read(){
     
        $query = "SELECT id, firstname, lastname, email, status FROM ".$this->table_name. " ORDER BY id ASC";
     
        $stmt = $this->conn->prepare($query);
     
        $stmt->execute();
     
        return $stmt;
    }


    function create(){
     
        $query = "INSERT INTO
                    ". $this->table_name ."
                SET
                    firstname=:firstname, lastname=:lastname, email=:email";
     
        $stmt = $this->conn->prepare($query);
     
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
     
        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);
     
       
        if($stmt->execute()){
            return true;
        }
        return false;
         
    }

    function readOne(){
     
        $query = "SELECT
                    id, firstname, lastname, email, status
                FROM
                    ".$this->table_name."
                WHERE
                    id = ?
                LIMIT
                    0,1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->bindParam(1, $this->id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->email = $row['email'];
    }
    

    function update(){
     
        $query = "UPDATE
                    " .$this->table_name. "
                SET
                    firstname = :firstname,
                    lastname = :lastname,
                    email = :email
                WHERE
                    id = :id";
 
        $stmt = $this->conn->prepare($query);
     
        $this->firstname=htmlspecialchars(strip_tags($this->firstname));
        $this->lastname=htmlspecialchars(strip_tags($this->lastname));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->id=htmlspecialchars(strip_tags($this->id));
      
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
     
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }


    function delete(){
         
            
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
     
        $stmt = $this->conn->prepare($query);
      
        $this->id=htmlspecialchars(strip_tags($this->id));
     
        $stmt->bindParam(1, $this->id);
     
        if($stmt->execute()){
            return true;
        }
     
        return false;
     
    }


    function user_plan(){
     
        $query = "SELECT id, plan_id, plan_name, user_ids FROM ".$this->table_name1. " ORDER BY id ASC";
     
        $stmt = $this->conn->prepare($query);
     
        $stmt->execute();
     
        return $stmt;
    }



    function user_plan_readOne(){
     
        $query = "SELECT 
                    p.*, 
                    GROUP_CONCAT(s.email) AS value_email,
                    GROUP_CONCAT(s.firstname) AS value_name
                FROM ".$this->table_name1." p 
                LEFT JOIN ".$this->table_name." s ON FIND_IN_SET(s.id, p.user_ids)
                WHERE p.plan_id= ?
                GROUP BY p.id
                ORDER BY p.plan_id";  

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->plan_id);

        $stmt->execute();

        return $stmt;
    }   

    function logs()
    {
        $query = "INSERT INTO
                    ".$this->table_name2."
                SET
                    user_id=:user_id, plan_id=:plan_id, mail_situation=:mail_situation, record_date=:record_date";
     
        $stmt = $this->conn->prepare($query);
     
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
        $this->mail_situation=htmlspecialchars(strip_tags($this->mail_situation));
        $this->record_date=htmlspecialchars(strip_tags($this->record_date));
    
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":plan_id", $this->plan_id);
        $stmt->bindParam(":mail_situation", $this->mail_situation);
        $stmt->bindParam(":record_date", $this->record_date);

        
        if($stmt->execute()){
            return true;
        }
     
        return false;
    }



    public function count(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        return $row['total_rows'];
    }



}
