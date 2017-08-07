<?php
namespace unapi\fms\passport;

use unapi\fms\common\QueryInterface;

class PassportQuery implements QueryInterface
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
}
