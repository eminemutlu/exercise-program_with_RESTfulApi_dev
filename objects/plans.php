<?php
class Plans{
 
    private $conn;

    private $table_name0 = "plans";
    private $table_name1 = "users";
    private $table_name2 = "exercise";
    private $table_name3 = "body_parts";
    private $table_name4 = "plandays";
    private $table_name5 = "users_plan";
 
    public $id;
    public $plan_id;
    public $user_ids;
    public $body_part_title;
    public $exercise_id;
    public $value;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function all_plans(){

        $query = "SELECT 
                    p.*, 
                    GROUP_CONCAT(s.firstname) AS value
                FROM ".$this->table_name5." p 
                LEFT JOIN ".$this->table_name1." s ON FIND_IN_SET(s.id, p.user_ids)
                GROUP BY p.id
                ORDER BY p.plan_id";  

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }


    function read_exercise(){
     
        $query = "SELECT id, title, status FROM ".$this->table_name2. " ORDER BY id ASC";
  
        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

 
    function createdays(){
     
        $query = "INSERT INTO
                    ".$this->table_name4."
                SET
                    plan_id=:plan_id, sort_order=:sort_order, body_part_title=:body_part_title, exercise_id=:exercise_id";
     
        $stmt = $this->conn->prepare($query);
     
        $this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
        $this->sort_order=htmlspecialchars(strip_tags($this->sort_order));
        $this->body_part_title=htmlspecialchars(strip_tags($this->body_part_title));
        $this->exercise_id=htmlspecialchars(strip_tags($this->exercise_id));
    
        $stmt->bindParam(":plan_id", $this->plan_id);
        $stmt->bindParam(":sort_order", $this->sort_order);
        $stmt->bindParam(":body_part_title", $this->body_part_title);
        $stmt->bindParam(":exercise_id", $this->exercise_id);
     
        $stmt->execute();
     
        return $stmt;
        
    }


    function create_plan_user(){
     
        $query = "INSERT INTO
                    ".$this->table_name5."
                SET
                    plan_id=:plan_id,  plan_name=:plan_name, user_ids=:user_ids, status=:status";
     
        $stmt = $this->conn->prepare($query);
     
        $this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
        $this->plan_name=htmlspecialchars(strip_tags($this->plan_name));
        $this->user_ids=htmlspecialchars(strip_tags($this->user_ids));
        $this->status=htmlspecialchars(strip_tags($this->status));
    
        $stmt->bindParam(":plan_id", $this->plan_id);
        $stmt->bindParam(":plan_name", $this->plan_name);
        $stmt->bindParam(":user_ids", $this->user_ids);
        $stmt->bindParam(":status", $this->status);
        
        if($stmt->execute()){
            return true;
        }
     
        return false;
        
    }

    function create_excercise(){

        $query = "INSERT INTO
                    ".$this->table_name2."
                SET
                    title=:title,  status=:status";
     
        $stmt = $this->conn->prepare($query);
     
        $this->title=htmlspecialchars(strip_tags($this->title));
        $this->status=htmlspecialchars(strip_tags($this->status));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":status", $this->status);
     
        $stmt->execute();
     
        return $stmt;
        
    }

    function delete_excercise(){
            
        $query = "DELETE FROM " . $this->table_name2;
     
        $stmt = $this->conn->prepare($query);
     
        if($stmt->execute()){
            return true;
        }
     
        return false;
     
    }

    
    function read_plan_detail(){
     
        $query = "SELECT 
                    p.id,
                    p.plan_id,
                    p.sort_order,
                    p.body_part_title,
                    p.exercise_id,
                    u.plan_name, 
                    u.user_ids 
                FROM ".$this->table_name4." p 
                LEFT JOIN ".$this->table_name5." u ON u.plan_id=p.plan_id
                where p.plan_id = ? ORDER BY sort_order ASC";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->plan_id);

        $stmt->execute();

        return $stmt;

    }   
    
    function delete(){
            
        $query = "DELETE T1, T2
                FROM ".$this->table_name5." T1
                INNER JOIN ".$this->table_name4." T2 ON T1.plan_id = T2.plan_id
                WHERE T1.plan_id = ?";
        
        $stmt = $this->conn->prepare($query);
      
        $this->plan_id=htmlspecialchars(strip_tags($this->plan_id));
     
        $stmt->bindParam(1, $this->plan_id);
     
        if($stmt->execute()){
            return true;
        }
     
        return false;
     
    }
    
    public function max_get_id(){
        $query = "SELECT max(plan_id) as plan_id FROM " .$this->table_name4. "  ORDER BY plan_id DESC LIMIT 1";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        return $row['plan_id'];
    }

    public function count_exercise(){
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name2 . "";
     
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
     
        return $row['total_rows'];
    }



}
