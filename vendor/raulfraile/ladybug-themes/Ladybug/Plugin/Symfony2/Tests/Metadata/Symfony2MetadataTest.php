<?php

namespace Ladybug\Tests\Plugin\Symfony\Metadata;

use Ladybug\Plugin\Symfony2\Metadata\Symfony2Metadata;
use Ladybug\Model\VariableWrapper;
use Symfony\Component\HttpFoundation\Request;

class SymfonyMetadataTest extends \PHPUnit_Framework_TestCase
{

    /** @var Metadata\Symfony2Metadata */
    protected $metadata;

    public function setUp()
    {
        $this->metadata = new Symfony2Metadata();
    }

    public function testMetadataForValidValues()
    {
        $className = 'Symfony\HttpFoundation\Request';

        $wrapper = new VariableWrapper($className, new Request(), VariableWrapper::TYPE_CLASS);

        $this->assertTrue($this->metadata->supports($wrapper));

        $metadata = $this->metadata->get($wrapper);
        $this->assertArrayHasKey('help_link', $metadata);
        $this->assertArrayHasKey('icon', $metadata);
        $this->assertArrayHasKey('version', $metadata);
    }

    public function testMetadataForInvalidValues()
    {
        $wrapper = new VariableWrapper('\stdClass', new \stdClass(), VariableWrapper::TYPE_CLASS);

        $this->assertFalse($this->metadata->supports($wrapper));

        $metadata = $this->metadata->get($wrapper);
        $this->assertEmpty($metadata);
    }

}
