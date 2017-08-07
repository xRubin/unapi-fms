<?php

namespace unapi\fms\common;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use unapi\anticaptcha\common\AnticaptchaInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class AbstractService
 */
abstract class AbstractService implements LoggerAwareInterface
{
    /** @var FmsClient */
    private $client;
    /** @var AnticaptchaInterface */
    private $anticaptcha;
    /** @var StatusFactoryInterface */
    private $statusFactory;
    /** @var LoggerInterface */
    private $logger;


    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['client'])) {
            $this->client = new FmsClient();
        } elseif ($config['client'] instanceof FmsClient) {
            $this->client = $config['client'];
        } else {
            throw new \InvalidArgumentException('Client must be instance of FmsClient');
        }

        if (!isset($config['logger'])) {
            $this->logger = new NullLogger();
        } elseif ($config['logger'] instanceof LoggerInterface) {
            $this->setLogger($config['logger']);
        } else {
            throw new \InvalidArgumentException('Logger must be instance of LoggerInterface');
        }

        if (!isset($config['anticaptcha'])) {
            throw new \InvalidArgumentException('Anticaptcha required');
        } elseif ($config['anticaptcha'] instanceof AnticaptchaInterface) {
            $this->anticaptcha = $config['anticaptcha'];
        } else {
            throw new \InvalidArgumentException('Anticaptcha must be instance of AnticaptchaInterface');
        }

        if (!isset($config['statusFactory'])) {
            throw new \InvalidArgumentException('Status factory required');
        } elseif ($config['statusFactory'] instanceof StatusFactoryInterface) {
            $this->statusFactory = $config['statusFactory'];
        } else {
            throw new \InvalidArgumentException('Status factory must be instance of StatusFactoryInterface');
        }
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param QueryInterface $query
     * @return PromiseInterface
     */
    public function getQueryPromise(QueryInterface $query): PromiseInterface
    {
        return $this->initialPage($this->client)->then(function (ResponseInterface $response) use ($query) {
            return $this->anticaptcha->getAnticaptchaPromise($this->client, $response)->then(function (string $code) use ($query) {
                return $this->submitForm($this->client, $query, $code)->then(function (ResponseInterface $response) {
                    return $this->statusFactory->factory($response->getBody()->getContents());
                });
            });
        });
    }

    /**
     * @param ClientInterface $client
     * @return PromiseInterface
     */
    abstract protected function initialPage(ClientInterface $client): PromiseInterface;

    /**
     * @param ClientInterface $client
     * @param QueryInterface $query
     * @param string $code
     * @return PromiseInterface
     */
    abstract protected function submitForm(ClientInterface $client, QueryInterface $query, string $code): PromiseInterface;

}