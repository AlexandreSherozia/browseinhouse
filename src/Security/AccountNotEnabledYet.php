<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 20/08/2018
 * Time: 11:04
 */

namespace App\Security;


use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class AccountNotEnabledYet
 * @package App\Security
 */
class AccountNotEnabledYet extends Exception
{

    private $errorMessage;
    /**
     * AccountNotEnabledYet constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct();
        $this->errorMessage = $string;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

}