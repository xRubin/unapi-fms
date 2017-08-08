<?php
namespace unapi\fms\common;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\AnticaptchaInterface;
use unapi\anticaptcha\common\AnticaptchaServiceInterface;
use unapi\anticaptcha\common\task\ImageTask;

class ImageAnticaptcha implements AnticaptchaInterface
{
    /** @var AnticaptchaServiceInterface */
    private $service;

    public function __construct(AnticaptchaServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param ClientInterface $client
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function getAnticaptchaPromise(ClientInterface $client, ResponseInterface $response): PromiseInterface
    {
        return $client->requestAsync('GET', '/services/captcha.jpg')
            ->then(function (ResponseInterface $response) {
                return $this->service->resolve(new ImageTask([
                    'body' => $response->getBody()->getContents(),
                    'numeric' => ImageTask::NUMERIC_ONLY_DIGITS,
                    'minLength' => 6,
                    'maxLength' => 6
                ]));
            });
    }
}