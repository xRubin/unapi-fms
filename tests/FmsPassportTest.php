<?php

use unapi\fms\passport\FmsPassportService;
use unapi\fms\passport\dto\PassportDto;
use unapi\fms\passport\statuses\PassportCorrectStatus;

class FmsPassportTest extends \PHPUnit_Framework_TestCase
{
    public function testDetection()
    {
        $service = new FmsPassportService();

        $this->assertInstanceOf(
            PassportCorrectStatus::class,
            $service->getStatus(new PassportDto(4505, 384596))->wait()
        );
    }
}