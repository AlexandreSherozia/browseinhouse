<?php

namespace App\Tests\Entity;


use App\Entity\Section;
use PHPUnit\Framework\TestCase;

class SectionTest extends TestCase
{

    public function testSectionCanSetAndGetAnId()
    {
        $section = new Section();
        $section->setId(2);

        $this->assertEquals(2, $section->getId());
    }

    public function testSectionCanSetAndGetALabel()
    {
        $section = new Section();
        $section->setLabel('LabelOfSection');

        $this->assertEquals('LabelOfSection', $section->getLabel());
    }

}