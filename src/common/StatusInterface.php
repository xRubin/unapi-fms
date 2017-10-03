<?php
namespace unapi\fms\common;

interface StatusInterface
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