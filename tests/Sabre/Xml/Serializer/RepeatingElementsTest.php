<?php

declare(strict_types=1);

namespace Sabre\Xml\Serializer;

use PHPUnit\Framework\TestCase;
use Sabre\Xml\Service;

class RepeatingElementsTest extends TestCase
{
    public function testSerialize(): void
    {
        $service = new Service();
        $service->namespaceMap['urn:test'] = null;
        $xml = $service->write('{urn:test}collection', function ($writer) {
            repeatingElements($writer, [
                'foo',
                'bar',
            ], '{urn:test}item');
        });

        $expected = <<<XML
<?xml version="1.0"?>
<collection xmlns="urn:test">
   <item>foo</item>
   <item>bar</item>
</collection>
XML;

        self::assertXmlStringEqualsXmlString($expected, $xml);
    }
}
