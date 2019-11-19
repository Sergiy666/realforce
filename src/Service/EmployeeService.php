<?php


namespace App\Service;


use App\Entity\Employee;
use App\Exception\IncorrectRequestException;
use App\Exception\InternalServerException;
use App\Exception\NotFoundException;
use App\Repository\EmployeeRepository;
use DateTime;
use Exception;

class EmployeeService
{
    /** @var EmployeeRepository $employee_repo */
    private $employee_repo;

    /** @var FormatsChecker $checker */
    private $checker;

    /**
     * EmployeeService constructor.
     */
    public function __construct()
    {
        $this->employee_repo = new EmployeeRepository();

        $this->checker = new FormatsChecker();
    }

    /**
     * @throws InternalServerException
     * @throws Exception
     */
    public function getEmployeesList(): array
    {
        $result = [];

        $employees = $this->employee_repo->findAll();

        foreach ($employees as $employee) {
            /** @var Employee $employee */
            $result[$employee->getId()] = $this->serializeEmployee($employee);
        }

        return $result;
    }

    /**
     * @param Employee $employee
     * @return array
     * @throws Exception
     */
    public function serializeEmployee(Employee $employee): array
    {
        return [
            'employee_id' => $employee->getId(),
            'name' => $employee->getName(),
            'birth_date' => $employee->getBirthDate()->format('Y-m-d'),
            'age' => $employee->getAge(),
            'kids_num' => $employee->getKidsNum(),
            'company_car' => $employee->getCompanyCar(),
            'salary' => $employee->getSalary(),
            'calculated_salary' => $employee->calculateSalary()
        ];
    }

    /**
     * @param array $data
     * @return Employee
     * @throws Exception
     */
    public function addEmployee(array $data): Employee
    {
        $employee = new Employee();

        $this->setData($employee, $data);

        $this->employee_repo->add($employee);

        return $employee;
    }

    /**
     * @param array $data
     * @return Employee
     * @throws IncorrectRequestException
     * @throws InternalServerException
     * @throws NotFoundException
     * @throws Exception
     */
    public function editEmployee(array $data): Employee
    {
        if (empty($data['employee_id'])) {
            throw new IncorrectRequestException();
        }

        /** @var Employee $employee */
        $employee = $this->employee_repo->findById((int)$data['employee_id']);

        if (!$employee) {
            throw new NotFoundException();
        }

        $this->setData($employee, $data);

        $this->employee_repo->edit($employee);

        return $employee;
    }

    /**
     * @param int $employee_id
     * @throws InternalServerException
     */
    public function removeEmployee(int $employee_id): void
    {
        $this->employee_repo->remove($employee_id);
    }

    /**
     * @param Employee $employee
     * @param array $data
     * @throws Exception
     */
    private function setData(Employee $employee, array $data): void
    {
        if (empty($data['name']) || empty($data['birth_date']) || !isset($data['kids_num']) || !isset($data['salary']) || !isset($data['company_car']) || !$this->checker->checkDate($data['birth_date'])) {
            throw new IncorrectRequestException();
        }

        $employee->setName($data['name'])
            ->setBirthDate(new DateTime($data['birth_date']))
            ->setKidsNum((int)$data['kids_num'])
            ->setCompanyCar((bool)$data['company_car'])
            ->setSalary((float)$data['salary'])
        ;
    }
}