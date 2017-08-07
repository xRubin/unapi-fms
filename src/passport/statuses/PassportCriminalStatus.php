<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportCriminalStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 3;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Числится в розыске';
    }
}