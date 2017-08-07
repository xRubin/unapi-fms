<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportDeceasedStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 7;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'В связи со смертью владельца';
    }
}