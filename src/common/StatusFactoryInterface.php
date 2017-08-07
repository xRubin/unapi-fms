<?php
namespace unapi\fms\common;

use GuzzleHttp\Promise\PromiseInterface;

interface StatusFactoryInterface
{
    /**
     * @param string $html
     * @return PromiseInterface
     */
    public function factory($html): PromiseInterface;
}