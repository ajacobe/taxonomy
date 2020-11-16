<?php
namespace clio\taxonomy;

class ApiController
{
    public function fetchAllRoles()
    {
        $role = new Role();
        return $role->fetchAll();
    }

    public function fetchAllEmployees()
    {
        $employee = new Employee();
        return $employee->fetchAll();
    }

    public function fetchAllDepartments()
    {
        $department = new Department();
        return $department->fetchAll();
    }

    public function createEmployee($data)
    {
        $employee = new Employee();
        return $employee->create($data);
    }

    function getOrganizationalModel()
    {
        $employee = new Employee();
        return $employee->getOrganizationalModel();
    }
}