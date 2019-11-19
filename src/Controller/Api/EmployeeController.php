<?php


namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Exception\IncorrectRequestException;
use App\Service\EmployeeService;
use Exception;

class EmployeeController extends AbstractController
{
    public function getEmployeesList(): void
    {
        try
        {
            $employee_service = new EmployeeService();

            $data = $employee_service->getEmployeesList();

            $this->json(null, ['data' => $data]);
        }
        catch (Exception $e)
        {
            $this->json($e);
        }
    }

    /**
     * @param string $request
     */
    public function addEmployee(string $request): void
    {
        try
        {
            $employee_service = new EmployeeService();

            $json = json_decode($request, true);

            if (!is_array($json)) throw new IncorrectRequestException();

            $employee = $employee_service->addEmployee($json);

            $this->json(null, ['data' => $employee_service->serializeEmployee($employee)]);
        }
        catch (Exception $e)
        {
            $this->json($e);
        }
    }

    /**
     * @param string $request
     */
    public function editEmployee(string $request): void
    {
        try
        {
            $employee_service = new EmployeeService();

            $json = json_decode($request, true);

            if (!is_array($json)) throw new IncorrectRequestException();

            $employee = $employee_service->editEmployee($json);

            $this->json(null, ['data' => $employee_service->serializeEmployee($employee)]);
        }
        catch (Exception $e)
        {
            $this->json($e);
        }
    }

    /**
     * @param string $request
     */
    public function removeEmployee(string $request): void
    {
        try
        {
            $employee_service = new EmployeeService();

            $json = json_decode($request, true);

            if (empty($json['employee_id'])) throw new IncorrectRequestException();

            $employee_service->removeEmployee((int)$json['employee_id']);

            $this->json(null);
        }
        catch (Exception $e)
        {
            $this->json($e);
        }
    }
}