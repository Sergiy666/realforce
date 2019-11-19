<?php


namespace App\Repository;


use App\Entity\AbstractEntity;
use App\Exception\InternalServerException;
use App\Service\DbService;
use Exception;
use PDO;

abstract class AbstractRepository
{
    /** @var string $table_name */
    protected $table_name;

    /** @var string $entity_class */
    protected $entity_class;

    /** @var PDO $db */
    protected $db;

    /**
     * AbstractRepository constructor.
     */
    public function __construct()
    {
        $db_service = new DbService();

        $this->db = $db_service->getInstance();
    }

    /**
     * @return array
     * @throws InternalServerException
     */
    public function findAll(): array
    {
        $result = [];

        $sql = "SELECT * FROM `$this->table_name`";

        try
        {
            $query = $this->db->prepare($sql);

            $query->execute();

            while ($entity = $query->fetchObject($this->entity_class)) {
                $result[] = $entity;
            }

            return $result;
        }
        catch (Exception $e)
        {
            throw new InternalServerException();
        }
    }

    /**
     * @param int $id
     * @return AbstractEntity|null
     * @throws InternalServerException
     */
    public function findById(int $id): ?AbstractEntity
    {
        $sql = "SELECT * FROM `$this->table_name` WHERE `id` = :id";

        try
        {
            $query = $this->db->prepare($sql);

            $query->execute([':id' => $id]);

            $result = $query->fetchObject($this->entity_class);

            return ($result) ? $result : null;
        }
        catch (Exception $e)
        {
            throw new InternalServerException();
        }
    }

    /**
     * @param int $id
     * @throws InternalServerException
     */
    public function remove(int $id): void
    {
        $sql = "DELETE FROM `$this->table_name` WHERE `id` = :id";

        try
        {
            $query = $this->db->prepare($sql);

            $query->execute([':id' => $id]);
        }
        catch (Exception $e)
        {
            throw new InternalServerException();
        }
    }
}