<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportUnknownStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 0;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Сведениями по заданным реквизитам не располагаем';
    }
}