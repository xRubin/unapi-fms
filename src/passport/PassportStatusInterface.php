<?php
namespace unapi\fms\passport;

use unapi\fms\common\StatusInterface;

interface PassportStatusInterface extends StatusInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return string
     */
    public function getMessage(): string;
}