<?php
namespace unapi\fms\common;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

interface StatusFactoryInterface
{
    /**
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function factory(ResponseInterface $response): PromiseInterface;
}