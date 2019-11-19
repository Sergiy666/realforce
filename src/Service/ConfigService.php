<?php


namespace App\Service;


class ConfigService
{
    /** @var array|null $instance */
    protected static $configuration = null;

    /**
     * ConfigService constructor.
     */
    public function __construct()
    {
        if (self::$configuration === null)
        {
            $json = file_get_contents(__DIR__ . "/../../config.json");

            self::$configuration = json_decode($json, true);
        }
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (isset(self::$configuration[$key])) {
            return self::$configuration[$key];
        }

        return null;
    }
}