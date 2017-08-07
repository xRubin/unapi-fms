<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportExpiredStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 5;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Истек срок действия';
    }
}