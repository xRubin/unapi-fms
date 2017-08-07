<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportCorrectStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 2;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Среди недействительных не значится';
    }
}