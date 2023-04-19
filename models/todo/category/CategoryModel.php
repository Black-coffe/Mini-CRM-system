<?php
namespace models\todo\category;

use models\Database;

class CategoryModel {
    private $db;
    private $userID;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();

        $this->userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        try{
            $result = $this->db->query("SELECT 1 FROM `todo_category` LIMIT 1");
        } catch(\PDOException $e){
            $this->createTable();
        }
    }

    public function createTable(){
        $query = "CREATE TABLE IF NOT EXISTS `todo_category` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `usability` TINYINT DEFAULT 1,
            `user_id` INT(11) NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        try{
            $this->db->exec($query);
            return true;
        } catch(\PDOException $e){
            return false;
        }
    }

    public function getAllCategories(){
        try{
            $stmt = $this->db->prepare("SELECT * FROM todo_category WHERE user_id = ?");
            $stmt->execute([$this->userID]);
            $todo_category = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_category[] = $row;
            }
            return $todo_category ? $todo_category : [];
        }catch(\PDOException $e){
            return [];
        }
    }

    // Для использования внутри создания Task
    public function getAllCategoriesWithUsability(){
        try{
            $stmt = $this->db->prepare("SELECT * FROM todo_category WHERE user_id = ? AND usability = 1");
            $stmt->execute([$this->userID]);
            $todo_category = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_category[] = $row;
            }
            return $todo_category;
        }catch(\PDOException $e){
            return false;
        }
    }

    public function createCategory($title, $description, $user_id)
    {
        $query = "INSERT INTO todo_category (title, description, user_id) VALUES (?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $description, $user_id]);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function getCategoryById($id)
    {
        $query = "SELECT * FROM todo_category WHERE id = ?";

        try{
            $stmt =$this->db->prepare($query);
            $stmt->execute([$id]);
            $todo_category = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $todo_category ? $todo_category : [];
        } catch(\PDOException $e){
            return [];
        }
    }

    public function updateCategory($id, $title, $description, $usability)
    {
        $query = "UPDATE todo_category SET title = ?, description = ?, usability = ? WHERE id = ?";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$title, $description, $usability, $id]);
            
            return true;
        } catch(\PDOException $e){
            return false;
        }
    }

    public function deleteCategory($id)
    {
        $query = "DELETE FROM todo_category WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }
}
?>