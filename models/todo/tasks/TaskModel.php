<?php
namespace models\todo\tasks;

use models\Database;

class TaskModel {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();

        try{
            $result = $this->db->query("SELECT 1 FROM `todo_list` LIMIT 1");
        } catch(\PDOException $e){
            $this->createTable();
        }
    }

    public function createTable(){
        $query = "CREATE TABLE IF NOT EXISTS `todo_list` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(11) NOT NULL,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `category_id` INT(11) NOT NULL,
            `status` ENUM('new', 'in_progress', 'completed', 'on_hold', 'cancelled') NOT NULL,
            `priority` ENUM('low', 'medium', 'high', 'urgent') NOT NULL,
            `assigned_to` INT,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `finish_date` DATETIME,
            `completed_at` DATETIME,
            `reminder_at` DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )";

        $remindersTable = "CREATE TABLE IF NOT EXISTS `todo_reminders` (
            `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT(11) NOT NULL,
            `task_id` INT(11) NOT NULL,
            `reminder_at` DATETIME,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (task_id) REFERENCES todo_list(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        );";

        try{
            $this->db->exec($query);
            $this->db->exec($remindersTable);
            return true;
        } catch(\PDOException $e){
            return false;
        }
    }

   public function getAllTasks(){

        try{
            $stmt = $this->db->query("SELECT * FROM todo_list");
            $todo_list = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_list[] = $row;
            }
            return $todo_list;
        }catch(\PDOException $e){
            return false;
        } 
    }

    public function getAllTasksByIdUser($user_id){

        try{
            $stmt = $this->db->prepare("SELECT * FROM todo_list WHERE finish_date > NOW() AND user_id = :user_id AND status != 'completed' ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), finish_date))" );
            $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $stmt->execute();
            $todo_list = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_list[] = $row;
            }
            return $todo_list ? $todo_list : [];
        }catch(\PDOException $e){
            return [];
        }
    }

    public function getAllCompletedTasksByIdUser($user_id){

        try{
            $stmt = $this->db->prepare("SELECT * FROM todo_list WHERE user_id = :user_id AND status = 'completed' ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), finish_date))" );
            $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $stmt->execute();
            $todo_list = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_list[] = $row;
            }
            return $todo_list ? $todo_list : [];
        }catch(\PDOException $e){
            return [];
        }
    }

    public function getAllExpiredTasksByIdUser($user_id){

        try{
            $stmt = $this->db->prepare("SELECT * FROM todo_list WHERE finish_date < NOW() AND user_id = :user_id ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), finish_date))" );
            $stmt->bindParam(':user_id', $user_id, \PDO::PARAM_INT);
            $stmt->execute();
            $todo_list = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $todo_list[] = $row;
            }
            return $todo_list ? $todo_list : [];
        }catch(\PDOException $e){
            return [];
        }
    }

    public function createTask($data)
    
    {
        $query = "INSERT INTO todo_list (user_id, title, category_id, status, priority, finish_date, reminder_at) VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$data['user_id'], $data['title'], $data['category_id'], $data['status'], $data['priority'], $data['finish_date'],  $data['reminder_at']]);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function getTaskById($id)
    {
        $query = "SELECT * FROM todo_list WHERE id = ?";

        try{
            $stmt =$this->db->prepare($query);
            $stmt->execute([$id]);
            $todo_task = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $todo_task ? $todo_task : false;
        } catch(\PDOException $e){
            return false;
        }
    }

    public function getTaskByIdAndByIdUser($id_task, $id_user)
    {
        $query = "SELECT * FROM todo_list WHERE id = ? AND user_id = ?";

        try{
            $stmt =$this->db->prepare($query);
            $stmt->execute([$id_task, $id_user]);
            $todo_task = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $todo_task ? $todo_task : [];
        } catch(\PDOException $e){
            return false;
        }
    }

    public function updateTask($data)
    {
        $query = "UPDATE todo_list SET title = ?, category_id = ?, finish_date = ?, reminder_at = ?, status = ?, priority = ?, description = ? WHERE id = ?";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute([$data['title'], $data['category_id'], $data['finish_date'], $data['reminder_at'], $data['status'], $data['priority'], $data['description'], $data['id']]);
            
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

    public function getTasksByTagId($tag_id, $user_id){

        $query = "SELECT * FROM todo_list
        JOIN task_tags ON todo_list.id = task_tags.task_id
        WHERE task_tags.tag_id = :tag_id AND todo_list.user_id = :user_id ORDER BY ABS(TIMESTAMPDIFF(SECOND, NOW(), finish_date));";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute(['tag_id' => $tag_id, 'user_id' => $user_id]);
            $todo_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $todo_list ? $todo_list : $todo_list = [];
        }catch(\PDOException $e){
            return false;
        } 
    }

    public function updateTaskStatus($id, $status, $datetime){

        $query = "UPDATE todo_list SET status = :status";

        try{
            if($datetime !== null){
                $query .= ", completed_at = :completed_at";
            }else{
                $query .= ", completed_at = NULL";
            }

            $query .= " WHERE id = :id";
            
            $stmt = $this->db->prepare($query);

            $params = [':status' => $status, ':id' => $id];

            if($datetime !== null){
                $params[':completed_at'] = $datetime;
            }

            $stmt->execute($params);
            return $stmt->rowCount() > 0;
        }catch(\PDOException $e){
            return false;
        } 
    }

    public function getTasksCountAndStatusByUserId($user_id){

        $query = "SELECT
        COUNT(*) AS all_tasks,
        SUM(status = 'completed') AS completed,
        SUM(status != 'completed' AND finish_date < NOW() OR status != 'completed' AND finish_date IS NULL) AS expired,
        SUM(status != 'completed' AND finish_date > NOW() OR status != 'completed' AND finish_date IS NULL) AS opened
        FROM
            todo_list
        WHERE user_id = :user_id";

        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute(['user_id' => $user_id]);
            $todo_list = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            return $todo_list ? $todo_list : $todo_list = [];
        }catch(\PDOException $e){
            return [];
        } 
    }

// ГЕНЕРАЦИЯ ЗАДАННОГО ВАМИ КОЛИЧЕСТВА ТЕСТОВЫХ ЗАДАЧ
public function generateTasks($data)
    {
    $query = "INSERT INTO todo_list (user_id, title, category_id, description, status, priority, created_at, finish_date, reminder_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    try {
        $i = 1;
        $stmt = $this->db->prepare($query);
        $finish_date_min = strtotime($data['finish_date_range'][0]);
        $finish_date_max = isset($data['finish_date_range'][1]) ? strtotime($data['finish_date_range'][1]) : $finish_date_min;
        $created_at_min = strtotime($data['created_at_range'][0]);
        $created_at_max = isset($data['created_at_range'][1]) ? strtotime($data['created_at_range'][1]) : $created_at_min;
        while ($i <= $data['count']) {
            $user_id = rand($data['users_range'][0], $data['users_range'][1]);
            $title = "Генеренная задача номер $i";
            $category_id = rand($data['categories_range'][0], $data['categories_range'][1]);
            $description = "Описание автогенеренной задачи номер $i";
            $statuses = ['new', 'in_progress', 'completed', 'on_hold', 'cancelled'];
            $status = $statuses[rand(0, 4)];
            $priorities = ['low', 'medium', 'high', 'urgent'];
            $priority = $priorities[rand(0, 3)];
            // finishDates
            $random_timestamp = rand($finish_date_min, $finish_date_max);
            $finish_date = date('Y-m-d H:i:s', $random_timestamp);
            // created_at_range
            $random_timestamp = rand($created_at_min, $created_at_max);
            $created_at = date('Y-m-d H:i:s', $random_timestamp);
            $reminder_timestamp = strtotime($finish_date) - 3600; // вычитаем 3600 секунд (1 час) из временной метки $finish_date
            $reminder_at = date('Y-m-d H:i:s', $reminder_timestamp); // форматируем новую временную метку в дату и время
            
            $values = [$user_id, $title, $category_id, $description, $status, $priority, $created_at, $finish_date, $reminder_at];
            $stmt->execute($values);
            $i++;
        }
        return true;
    } catch(\PDOException $e) {
        return $e->getMessage();
    }
    
    
    }
}