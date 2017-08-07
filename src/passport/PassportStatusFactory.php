<?php
namespace unapi\fms\passport;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\RejectedPromise;
use unapi\fms\common\StatusFactoryInterface;
use unapi\fms\passport\statuses;

class PassportStatusFactory implements StatusFactoryInterface
{
    protected $mapper = [
        'Среди недействительных не значится' => statuses\PassportCorrectStatus::class,
        'Cреди недействительных не значится' => statuses\PassportCorrectStatus::class,

        'Сведениями по заданным реквизитам не располагаем' => statuses\PassportUnknownStatus::class,
        'Числится в розыске' => statuses\PassportCriminalStatus::class,
        'Заменен на новый' => statuses\PassportChangedStatus::class,
        'Истек срок действия' => statuses\PassportExpiredStatus::class,
        'Изьят, уничтожен' => statuses\PassportDestroyedStatus::class,
        'В связи со смертью владельца' => statuses\PassportDeceasedStatus::class,
        'Технический брак' => statuses\PassportDefectStatus::class,
        'Выдан с нарушением' => statuses\PassportBreachStatus::class,
    ];

    /**
     * @param string $html
     * @return PromiseInterface
     */
    public function factory($html): PromiseInterface
    {
        foreach ($this->mapper as $text => $class) {
            if (mb_stripos($html, $text) !== false)
                return new FulfilledPromise(new $class);
        }

        return new RejectedPromise('Unknown status');
    }
}