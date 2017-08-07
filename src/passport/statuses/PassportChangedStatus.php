<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportChangedStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 4;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Заменен на новый';
    }
}