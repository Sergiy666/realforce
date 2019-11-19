<?php


namespace App\Exception;


use Exception;

class InternalServerException extends Exception
{
    /**
     * InternalServerException constructor.
     */
    public function __construct()
    {
        parent::__construct('Internal server error', 500);
    }
}