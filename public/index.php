<?php

require_once '../vendor/autoload.php';

use App\Controller\Api\EmployeeController;
use App\Controller\DefaultController;

switch ($_GET['route']) {
    case '':
        $controller = new DefaultController();
        $controller->showEmployeesPage();
        break;
    case 'api/employee/list':
        $controller = new EmployeeController();
        $controller->getEmployeesList();
        break;
    case 'api/employee/add':
        $controller = new EmployeeController();
        $controller->addEmployee(file_get_contents('php://input'));
        break;
    case 'api/employee/edit':
        $controller = new EmployeeController();
        $controller->editEmployee(file_get_contents('php://input'));
        break;
    case 'api/employee/remove':
        $controller = new EmployeeController();
        $controller->removeEmployee(file_get_contents('php://input'));
}