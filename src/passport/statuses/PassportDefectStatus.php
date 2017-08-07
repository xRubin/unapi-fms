<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportDefectStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 8;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Технический брак';
    }
}