<?php


namespace App\Test;


use App\Exception\IncorrectRequestException;
use App\Exception\InternalServerException;
use App\Exception\NotFoundException;
use App\Service\EmployeeService;
use Exception;

class EmployeeServiceTest extends BaseTestCase
{
    /** @var EmployeeService $es */
    private $es;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->es = new EmployeeService();

        parent::setUp();
    }

    public function testGetEmployeesList()
    {
        $exception = null;

        try
        {
            $result = $this->es->getEmployeesList();
        }
        catch (Exception $e)
        {
            $exception = $e;
        }

        $this->assertEquals(null, $exception);

        $this->assertEquals($this->employees[0], $result[$this->employees[0]['employee_id']]);

        $this->assertEquals($this->employees[1], $result[$this->employees[1]['employee_id']]);

        $this->assertEquals($this->employees[2], $result[$this->employees[2]['employee_id']]);
    }

    /**
     * @throws InternalServerException
     */
    public function testAddEmployee()
    {
        $employee = $this->employees[0];

        //Without employee name
        unset($employee['name']);
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        $employee['name'] = $this->employees[0]['name'];

        //Without kids number
        unset($employee['kids_num']);
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        $employee['kids_num'] = $this->employees[0]['kids_num'];

        //Without company car parameter
        unset($employee['company_car']);
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        $employee['company_car'] = $this->employees[0]['company_car'];

        //Without salary
        unset($employee['salary']);
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        $employee['salary'] = $this->employees[0]['salary'];

        //Without birth date
        unset($employee['birth_date']);
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        //Incorrect birth date format
        $employee['birth_date'] = '123';
        $exception = null;
        try
        {
            $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        $employee['birth_date'] = $this->employees[0]['birth_date'];

        //Successful creation
        $exception = null;
        try
        {
            $result = $this->es->addEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(null, $exception);
        $this->assertEquals($employee['name'], $result->getName());
        $this->assertEquals($employee['kids_num'], $result->getKidsNum());
        $this->assertEquals($employee['company_car'], $result->getCompanyCar());
        $this->assertEquals($employee['salary'], $result->getSalary());

        $employee['employee_id'] = $result->getId();

        //Getting created employee from the list
        $result = $this->es->getEmployeesList();
        $this->assertEquals($employee, $result[$employee['employee_id']]);

        //Removing employee
        $query = $this->db->prepare('DELETE FROM `employees` WHERE `id` = :id');
        $query->execute([':id' => $employee['employee_id']]);
    }

    /**
     * @throws InternalServerException
     */
    public function testEditEmployee()
    {
        $employee = $this->employees[0];

        $employee['name'] = 'John';

        $employee['company_car'] = true;

        $employee['calculated_salary'] = 4400;

        //Without employee id
        unset($employee['employee_id']);
        $exception = null;
        try
        {
            $this->es->editEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(IncorrectRequestException::class, get_class($exception));
        $this->assertEquals(400, $exception->getCode());

        //Incorrect employee id
        $employee['employee_id'] = -1;
        $exception = null;
        try
        {
            $this->es->editEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(NotFoundException::class, get_class($exception));
        $this->assertEquals(404, $exception->getCode());

        //Successful update
        $employee['employee_id'] = $this->employees[0]['employee_id'];
        $exception = null;
        try
        {
            $result = $this->es->editEmployee($employee);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(null, $exception);
        $this->assertEquals($employee['name'], $result->getName());
        $this->assertEquals($employee['kids_num'], $result->getKidsNum());
        $this->assertEquals($employee['company_car'], $result->getCompanyCar());
        $this->assertEquals($employee['salary'], $result->getSalary());

        //Getting updated employee from the list
        $result = $this->es->getEmployeesList();
        $this->assertEquals($employee, $result[$employee['employee_id']]);
    }

    /**
     * @throws InternalServerException
     */
    public function testRemoveEmployee()
    {
        //Successful removal
        $exception = null;
        try
        {
            $this->es->removeEmployee((int)$this->employees[0]['employee_id']);
        }
        catch (Exception $e)
        {
            $exception = $e;
        }
        $this->assertEquals(null, $exception);

        //Checking the list
        $result = $this->es->getEmployeesList();
        $this->assertEmpty($result[$this->employees[0]['employee_id']]);
    }
}