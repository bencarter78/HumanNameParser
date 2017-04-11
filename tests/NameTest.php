<?php

namespace Tck\HumanNameParser\Tests;

use Tck\HumanNameParser\Name;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testSetStrRemovesWhitespaceAtEnds()
    {
        $this->assertEquals("Björn O'Malley", (new Name("	Björn O'Malley \r\n"))->getString());
    }

    public function testSetStrRemovesRedundantWhitespace()
    {
        $this->assertEquals("Björn O'Malley", (new Name(" Björn	O'Malley"))->getString());
    }

    public function testChopWithRegexReturnsChoppedSubstring()
    {
        $this->assertEquals('Björn', (new Name("Björn O'Malley"))->chopWithRegex('/^([^ ]+)(.+)/', 1));
    }

    public function testChopWithRegexChopsStartOffNameStr()
    {
        $name = new Name("Björn O'Malley");
        $name->chopWithRegex('/^[^ ]+/', 0);
        $this->assertEquals("O'Malley", $name->getString());
    }

    public function testChopWithRegexChopsEndOffNameStr()
    {
        $name = new Name("Björn O'Malley");
        $name->chopWithRegex('/ (.+)$/', 1);
        $this->assertEquals('Björn', $name->getString());
    }

    public function testChopWithRegexChopsMiddleFromNameStr()
    {
        $name = new Name("Björn 'Bill' O'Malley");
        $name->chopWithRegex("/\ '[^']+' /", 0);
        $this->assertEquals("Björn O'Malley", $name->getString());
    }

    public function testFlip()
    {
        $name = new Name("O'Malley, Björn");
        $name->flip(",");
        $this->assertEquals("Björn O'Malley", $name->getString());
    }
}
