<?php

namespace unapi\fms\passport;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use unapi\anticaptcha\common\dto\CaptchaSolvedInterface;
use unapi\fms\common\AbstractFmsService;
use unapi\fms\common\AudioAnticaptcha;
use unapi\fms\common\StatusFactoryInterface;
use unapi\fms\passport\dto\PassportInterface;
use unapi\fms\passport\statuses\PassportStatusFactory;

class FmsPassportService extends AbstractFmsService
{
    /** @var StatusFactoryInterface */
    private $statusFactory;

    /**
     * @param array $config Service configuration settings.
     */
    public function __construct(array $config = [])
    {
        if (!isset($config['anticaptcha']))
            $config['anticaptcha'] = new AudioAnticaptcha();

        parent::__construct($config);

        if (!isset($config['statusFactory'])) {
            $this->statusFactory = new PassportStatusFactory();
        } elseif ($config['statusFactory'] instanceof StatusFactoryInterface) {
            $this->statusFactory = $config['statusFactory'];
        } else {
            throw new \InvalidArgumentException('Status factory must be instance of StatusFactoryInterface');
        }
    }

    /**
     * @param PassportInterface $passport
     * @return PromiseInterface
     */
    public function getStatus(PassportInterface $passport): PromiseInterface
    {
        return $this->initialPage($this->getClient())->then(function (ResponseInterface $response) use ($passport) {
            return $this->getAnticaptcha()->getAnticaptchaPromise($this->getClient(), $response)->then(function (CaptchaSolvedInterface $solved) use ($passport) {
                return $this->submitForm($this->getClient(), $passport, $solved)->then(function (ResponseInterface $response) {
                    return $this->statusFactory->factory($response);
                });
            });
        });
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
     * @param PassportInterface $passport
     * @param CaptchaSolvedInterface $solved
     * @return PromiseInterface
     */
    protected function submitForm(ClientInterface $client, PassportInterface $passport, CaptchaSolvedInterface $solved): PromiseInterface
    {
        return $client->requestAsync('POST', '/info-service.htm', [
            'form_params' => [
                'sid' => 2000,
                'form_name' => 'form',
                'DOC_NUMBER' => $passport->getNumber(),
                'DOC_SERIE' => $passport->getSeries(),
                'captcha-input' => $solved->getCode()
            ]
        ]);
    }

}