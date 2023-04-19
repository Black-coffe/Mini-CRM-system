<?php
namespace models\todo\tasks;

use models\Database;

class TagsModel {
    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();

        try{
            $result = $this->db->query("SELECT 1 FROM `tags` LIMIT 1");
        } catch(\PDOException $e){
            $this->createTables();
        }
    }

    public function createTables()
{
    // Создание таблицы tags
    $queryTags = "CREATE TABLE IF NOT EXISTS `tags` (
        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT(11),
        `name` VARCHAR(255) NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );";

    // Создание таблицы task_tags
    $queryTaskTags = "CREATE TABLE IF NOT EXISTS `task_tags` (
        `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        `task_id` INT(11) NOT NULL,
        `tag_id` INT(11) NOT NULL,
        FOREIGN KEY (task_id) REFERENCES todo_list(id),
        FOREIGN KEY (tag_id) REFERENCES tags(id)
    );";

    try {
        // выполнение первого разпроса
        $resultTags = $this->db->exec($queryTags);
        // выполнение второго разпроса
        $resultTagsTask = $this->db->exec($queryTaskTags);

        // Проверка на сколько успешно выполнены запросы
        if($resultTags !== false && $resultTagsTask !== false){
            return true;
        } else{
            throw new \PDOException("Ошибка при создании таблиц");
        }
    } catch (\PDOException $e) {
        echo "Ошибка: ". $e->getMessage();
        return false;
    }
}


    public function getTagsByTaskId($task_id) {
        $query = "SELECT tags.* FROM tags
        JOIN task_tags ON tags.id = task_tags.tag_id
        WHERE task_tags.task_id = :task_id";
        
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute(['task_id' => $task_id]);
    
            $tags = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return $tags ? $tags : [];
        } catch (\PDOException $e) {
            return [];
        }
    }
    

    public function removeAllTaskTags($task_id) {
        $query = "DELETE FROM task_tags WHERE task_id = :task_id";
        
        try{
            $stmt = $this->db->prepare($query);
            $stmt->execute(['task_id' => $task_id]);
        } catch(\PDOException $e){
            return false;
        }
    }

    public function getTagByNameAndUserId($tag_name, $user_id)
    {
        $query = "SELECT * FROM tags WHERE name = ? AND user_id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tag_name, $user_id]);
            return $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function addTag($tag_name, $user_id)
    {
        $tag_name = strtolower($tag_name);
        $query = "INSERT INTO tags (name, user_id) VALUE (LOWER(?), ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tag_name, $user_id]);
            return $this->db->lastInsertId();
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function addTaskTag($task_id, $tag_id)
    {
        $query = "INSERT INTO task_tags (task_id, tag_id) VALUE (?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$task_id, $tag_id]);
            return true;
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function removeUnusedTag($tag_id)
    {
        $query = "SELECT COUNT(*) FROM task_tags WHERE tag_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([ $tag_id]);
        $count = $stmt->fetch(\PDO::FETCH_ASSOC)['COUNT(*)'];
        try {
            if($count == 0){
                $query = "DELETE FROM tags WHERE id = ?";
                $stmt = $this->db->prepare($query);
                $stmt->execute([$tag_id]);
                return true;
            }
        } catch(\PDOException $e) {
            return false;
        }
    }

    public function getTagNameById($tag_id)
    {
        $query = "SELECT name FROM tags WHERE id = ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$tag_id]);
            $tag = $stmt->fetch(\PDO::FETCH_ASSOC);
            return $tag ? $tag['name'] : '';
        } catch(\PDOException $e) {
            return false;
        }
    }

}
?>