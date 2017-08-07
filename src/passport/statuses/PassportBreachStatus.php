<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportBreachStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 9;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Выдан с нарушением';
    }
}