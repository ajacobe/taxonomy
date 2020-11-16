<?php
namespace clio\taxonomy;

class Role{
    public function fetchAll()
    {
        $query = "SELECT * FROM Roles WHERE Status = ?";
        $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare($query);
        $stmt->execute([1]);
        return $stmt->fetchAll();
    }
}