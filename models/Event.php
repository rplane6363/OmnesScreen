<?php
require_once '../config/database.php';

class Event {
    private $conn;
    private $table_name = "events";
    
    public $id;
    public $title;
    public $description;
    public $event_date;
    public $event_time;
    public $location;
    public $campus;
    public $association_name;
    public $status;
    public $created_by;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                (title, description, event_date, event_time, location, campus, association_name, created_by) 
                VALUES (:title, :description, :event_date, :event_time, :location, :campus, :association_name, :created_by)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':event_date', $this->event_date);
        $stmt->bindParam(':event_time', $this->event_time);
        $stmt->bindParam(':location', $this->location);
        $stmt->bindParam(':campus', $this->campus);
        $stmt->bindParam(':association_name', $this->association_name);
        $stmt->bindParam(':created_by', $this->created_by);
        
        return $stmt->execute();
    }
    
    public function getApprovedByCampus($campus) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE campus = :campus AND status = 'approved' AND event_date >= CURDATE() 
                ORDER BY event_date ASC, event_time ASC LIMIT 20";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':campus', $campus);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllPending() {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE status = 'pending' 
                ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name . " 
                ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getByAssociation($created_by) {
        $query = "SELECT * FROM " . $this->table_name . " 
                WHERE created_by = :created_by 
                ORDER BY created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':created_by', $created_by);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute();
    }
}
?>
