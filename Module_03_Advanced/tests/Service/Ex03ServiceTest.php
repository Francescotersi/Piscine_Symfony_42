<?php

namespace App\Tests\Service;

use App\Service\Ex03Service;
use PHPUnit\Framework\TestCase;

class Ex03ServiceTest extends TestCase {
    private Ex03Service $service;

    protected function setUp(): void {
        $this->service = new Ex03Service();
    }

    public function testUppercaseWords(): void {
        $result1 = $this->service->uppercaseWords("hello world");
        $this->assertSame("Hello World", $result1);

        $result2 = $this->service->uppercaseWords("this? i!s a, test.");
        $this->assertSame("This? I!s A, Test.", $result2);

        $result3 = $this->service->uppercaseWords('');
        $this->assertSame('', $result3);
    }

    public function testCountNumbers(): void {
        $result1 = $this->service->countNumbers('symfony 42 has 123 exercises');
        $this->assertSame(5, $result1);

        $result2 = $this->service->countNumbers('there are no numbers here');
        $this->assertSame(0, $result2);

        $result3 = $this->service->countNumbers('1234567890');
        $this->assertSame(10, $result3);
    }
}