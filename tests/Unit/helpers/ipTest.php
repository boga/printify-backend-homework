<?php

namespace Tests\Unit;

use App\Helpers\IP;
use PHPUnit\Framework\TestCase;

class IPTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testGetIPCantGetIpAddressFromAbsentServer()
    {
        $this->assertNull(IP::getIP());
    }

    public function testGetIPCanGetIpAddressFromServer()
    {
        if (empty($_SERVER)) {
            $_SERVER = [];
        }
        $_SERVER["REMOTE_ADDR"] = "1.1.1.1";
        $this->assertEquals("1.1.1.1", IP::getIP());
    }
}
