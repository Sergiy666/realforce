<?php


namespace App\Test;


use App\Service\DbService;
use DateTime;
use Exception;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class BaseTestCase extends TestCase
{
    /** @var PDO $db */
    protected $db;

    /** @var array $employees */
    protected $employees = [
        [
            'name' => 'Alice',
            'age' => 26,
            'kids_num' => 2,
            'salary' => 6000,
            'company_car' => false,
            'calculated_salary' => 4800
        ],
        [
            'name' => 'Bob',
            'age' => 52,
            'kids_num' => 0,
            'salary' => 4000,
            'company_car' => true,
            'calculated_salary' => 3024
        ],
        [
            'name' => 'Charlie',
            'age' => 36,
            'kids_num' => 3,
            'salary' => 5000,
            'company_car' => true,
            'calculated_salary' => 3690
        ]
    ];

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    protected function setUp()
    {
        $db_service = new DbService();

        $this->db = $db_service->getInstance();

        foreach ($this->employees as $key => $employee) {
            $this->employees[$key] = $this->insertEmployee($employee);
        }
    }

    /**
     * @param array $employee
     * @return array
     * @throws Exception
     */
    protected function insertEmployee(array $employee): array
    {
        $birth_date = new DateTime();

        $birth_date->modify('-' . $employee['age'] . ' year');

        $query = $this->db->prepare('INSERT INTO `employees` (`name`, `birth_date`, `kids_num`, `salary`, `company_car`) VALUES (:name, :birth_date, :kids_num, :salary, :company_car)');

        $employee['birth_date'] = $birth_date->format('Y-m-d');

        $query->execute([
            'name' => $employee['name'],
            'birth_date' => $employee['birth_date'],
            'kids_num' => $employee['kids_num'],
            'salary' => $employee['salary'],
            'company_car' => (int)$employee['company_car']
        ]);

        $employee['employee_id'] = (int)$this->db->lastInsertId();

        return $employee;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        $ids = [];

        foreach ($this->employees as $employee) {
            $ids[] = $employee['employee_id'];
        }

        $query = $this->db->prepare('DELETE FROM `employees` WHERE `id` IN (' . implode(',', $ids) . ')');

        $query->execute();
    }
}