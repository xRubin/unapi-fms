<?php

namespace unapi\fms\passport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use unapi\fms\common\AbstractService;
use unapi\fms\common\AudioAnticaptcha;
use unapi\fms\common\QueryInterface;

class PassportService extends AbstractService
{
    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['anticaptcha']))
            $config['anticaptcha'] = new AudioAnticaptcha();

        if (!isset($config['statusFactory']))
            $config['statusFactory'] = new PassportStatusFactory();

        return parent::__construct($config);
    }


    /**
     * @param ClientInterface $client
     * @return PromiseInterface
     */
    protected function initialPage(ClientInterface $client): PromiseInterface
    {
        return $client->requestAsync('GET', '/info-service.htm?sid=2000');
    }

    /**
     * @param ClientInterface $client
     * @param QueryInterface $query
     * @param string $code
     * @return PromiseInterface
     */
    protected function submitForm(ClientInterface $client, QueryInterface $query, string $code): PromiseInterface
    {
        /** @var PassportQuery $query */

        return $client->requestAsync('POST', '/info-service.htm', [
            'form_params' => [
                'sid' => 2000,
                'form_name' => 'form',
                'DOC_NUMBER' => $query->getNumber(),
                'DOC_SERIE' => $query->getSeries(),
                'captcha-input' => $code
            ]
        ]);
    }
}