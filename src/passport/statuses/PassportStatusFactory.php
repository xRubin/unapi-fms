<?php
namespace unapi\fms\passport\statuses;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use Psr\Http\Message\ResponseInterface;
use unapi\fms\common\StatusFactoryInterface;

class PassportStatusFactory implements StatusFactoryInterface
{
    protected $mapper = [
        'Среди недействительных не значится' => PassportCorrectStatus::class,
        'Cреди недействительных не значится' => PassportCorrectStatus::class,

        'Сведениями по заданным реквизитам не располагаем' => PassportUnknownStatus::class,
        'Числится в розыске' => PassportCriminalStatus::class,
        'Заменен на новый' => PassportChangedStatus::class,
        'Истек срок действия' => PassportExpiredStatus::class,
        'Изьят, уничтожен' => PassportDestroyedStatus::class,
        'В связи со смертью владельца' => PassportDeceasedStatus::class,
        'Технический брак' => PassportDefectStatus::class,
        'Выдан с нарушением' => PassportBreachStatus::class,
    ];

    /**
     * @param ResponseInterface $response
     * @return PromiseInterface
     */
    public function factory(ResponseInterface $response): PromiseInterface
    {
        $html = $response->getBody()->getContents();

        foreach ($this->mapper as $text => $class) {
            if (mb_stripos($html, $text) !== false)
                return new FulfilledPromise(new $class);
        }

        return new RejectedPromise('Unknown status');
    }
}