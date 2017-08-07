<?php
namespace unapi\fms\passport\statuses;

use unapi\fms\passport\PassportStatusInterface;

class PassportDestroyedStatus implements PassportStatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return 6;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Изьят, уничтожен';
    }
}