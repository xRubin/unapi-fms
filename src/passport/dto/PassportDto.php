<?php

namespace unapi\fms\passport\dto;

use unapi\interfaces\DtoInterface;

class PassportDto implements PassportInterface
{
    /** @var string */
    private $series;

    /** @var string */
    private $number;

    /**
     * PassportQuery constructor.
     * @param $series
     * @param $number
     */
    public function __construct($series, $number)
    {
        $this->series = sprintf('%04s', $series);
        $this->number = sprintf('%06s', $number);
    }

    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @param array $data
     * @return static
     */
    public static function toDto(array $data): DtoInterface
    {
        return new PassportDto($data['series'], $data['number']);
    }
}