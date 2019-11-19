<?php


namespace App\Repository;


use App\Entity\Employee;
use App\Exception\InternalServerException;
use Exception;

class EmployeeRepository extends AbstractRepository
{
    /**
     * EmployeeRepository constructor.
     */
    public function __construct()
    {
        $this->table_name = 'employees';

        $this->entity_class = 'App\Entity\Employee';

        parent::__construct();
    }

    /**
     * @param Employee $employee
     * @throws InternalServerException
     */
    public function add(Employee $employee): void
    {
        $sql = "INSERT INTO `$this->table_name` (`name`, `birth_date`, `kids_num`, `salary`, `company_car`) VALUES (:name, :birth_date, :kids_num, :salary, :company_car)";

        $params = [
            ':name' => $employee->getName(),
            ':birth_date' => $employee->getBirthDate()->format('Y-m-d'),
            ':kids_num' => $employee->getKidsNum(),
            ':salary' => $employee->getSalary(),
            ':company_car' => (int)$employee->getCompanyCar()
        ];

        try
        {
            $query = $this->db->prepare($sql);

            $query->execute($params);

            $employee->setId($this->db->lastInsertId());
        }
        catch (Exception $e)
        {
            throw new InternalServerException();
        }
    }

    /**
     * @param Employee $employee
     * @throws InternalServerException
     */
    public function edit(Employee $employee): void
    {
        $sql = "UPDATE `$this->table_name` SET `name` = :name, `birth_date` = :birth_date, `kids_num` = :kids_num, `salary` = :salary, `company_car` = :company_car WHERE `id` = :id";

        $params = [
            ':name' => $employee->getName(),
            ':birth_date' => $employee->getBirthDate()->format('Y-m-d'),
            ':kids_num' => $employee->getKidsNum(),
            ':salary' => $employee->getSalary(),
            ':company_car' => (int)$employee->getCompanyCar(),
            ':id' => $employee->getId()
        ];

        try
        {
            $query = $this->db->prepare($sql);

            $query->execute($params);
        }
        catch (Exception $e)
        {
            throw new InternalServerException();
        }
    }
}