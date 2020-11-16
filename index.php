<?php
namespace clio\taxonomy;
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
$method = $_SERVER['REQUEST_METHOD'];
if ($method == "OPTIONS") {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method,Access-Control-Request-Headers, Authorization");
    header("HTTP/1.1 200 OK");
    die();
}
require_once __DIR__.'/vendor/autoload.php';
use \Flight;
$dotenv = \Dotenv\Dotenv::create(__DIR__);
$dotenv->load();
$apiController = new ApiController();
Flight::route("/employees-network", function() use ($apiController){
    $organizatioModel = $apiController->getOrganizationalModel();
    $employees = $apiController->fetchAllEmployees();
    Flight::json(['status' => true, 'organization_model' => $organizatioModel->root, "employees" => $employees] );
});

Flight::route("/employee-form-data", function() use($apiController){
    $deparments = $apiController->fetchAllDepartments();
    $roles = $apiController->fetchAllRoles();
    $employees = $apiController->fetchAllEmployees();
    Flight::json(["success" => true, "departments"=>$deparments, "roles" => $roles, "employees"=>$employees]);exit;
});
Flight::route("POST /create-employee", function() use($apiController){
    $employee = Flight::request()->data["employee"];
    $status = $apiController->createEmployee($employee);
    if($status){
        $organizatioModel = $apiController->getOrganizationalModel();
    }
    Flight::json(["success" => $status, "organization_model" => $organizatioModel->root]);exit;
});

Flight::start();