<?php


namespace App\Service;


use PDO;

class DbService
{
    /** @var PDO|null $instance */
    protected static $instance = null;

    /**
     * DbService constructor.
     */
    public function __construct()
    {
        $config = new ConfigService();

        if (self::$instance === null)
        {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => TRUE,
            );

            $dsn = 'mysql:host=' . $config->get('db_host') . ';dbname=' . $config->get('db_name') . ';charset=' . $config->get('db_char');

            self::$instance = new PDO($dsn, $config->get('db_user'), $config->get('db_password'), $opt);
        }
    }

    /**
     * @return PDO
     */
    public function getInstance(): PDO
    {
        return self::$instance;
    }
}