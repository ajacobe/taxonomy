<?php
namespace clio\taxonomy;

class Department{
    public function fetchAll()
    {
        $query = "SELECT * FROM Departments WHERE Status = ?";
        $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare($query);
        $stmt->execute([1]);
        return $stmt->fetchAll();
    }
}