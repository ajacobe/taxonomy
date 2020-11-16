<?php
namespace clio\taxonomy;

class Employee{
    public $Id;
    public $FirstName;
    public $LastName;
    public $Role;
    public $Department;
    public $Language;

    public function fetchAll()
    {
        $query = "SELECT * FROM Employees WHERE Status = ?";
        $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare($query);
        $stmt->execute([1]);
        return $stmt->fetchAll();
    }

    public function create($employee)
    {
        $params = [
            ":FirstName" => $employee["FirstName"],
            ":LastName" => $employee["LastName"],
            ":RoleId" => $employee["RoleId"],
            ":DepartmentId" => $employee["DepartmentId"] ? $employee["DepartmentId"] : NULL,
            ":Language" => $employee["Language"] ? $employee["Language"] : NULL,
        ];
        
        $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare("INSERT INTO Employees(FirstName, LastName, RoleId, DepartmentId, Language) Values(:FirstName, :LastName, :RoleId, :DepartmentId, :Language);");
        $stmt->execute($params);
        $employeeId = (int)PDO::getInstance($_ENV["DB_INSTANCE"])->lastInsertId();
        if($employeeId){
            return $this->addToOrganizationModel($employeeId, $employee["ParentId"]);
        }
        return false;
    }

    public function addToOrganizationModel($employeeId, $parentId)
    {
        if($employeeId > 0 && $parentId > 0){
            $params = [
                ":ParentId" => $parentId,
                ":EmployeeId" => $employeeId
            ];
            $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare("INSERT INTO Employees_Network(EmployeeId, ParentId, DateCreated) Values(:EmployeeId, :ParentId, NOW());");
            return $stmt->execute($params);
        }
        return false;
    }

    public function getOrganizationalModel()
    {
        $query = "SELECT en.EmployeeId as Id, en.ParentId, CONCAT(e.FirstName, ' ', e.LastName) as Name, Language, r.Name as Position, d.Name as Department 
            FROM Employees_Network en 
            LEFT JOIN Employees e ON e.Id = en.EmployeeId 
            LEFT JOIN Departments d ON d.Id = e.DepartmentId 
            LEFT JOIN Roles r ON r.Id = e.RoleId
            ORDER BY en.ParentId";
        $stmt = PDO::getInstance($_ENV["DB_INSTANCE"])->prepare($query);
        $stmt->execute();
        $employees = $stmt->fetchAll();
        $tree = new CompanyTree($employees);
        return $tree;
    }
}