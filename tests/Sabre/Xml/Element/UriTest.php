<?php

declare(strict_types=1);

namespace Sabre\Xml\Element;

use PHPUnit\Framework\TestCase;
use Sabre\Xml\Reader;
use Sabre\Xml\Writer;

class UriTest extends TestCase
{
    public function testDeserialize(): void
    {
        $input = <<<BLA
<?xml version="1.0"?>
<root xmlns="http://sabredav.org/ns">
  <uri>/foo/bar</uri>
</root>
BLA;

        $reader = new Reader();
        $reader->contextUri = 'http://example.org/';
        $reader->elementMap = [
            '{http://sabredav.org/ns}uri' => 'Sabre\\Xml\\Element\\Uri',
        ];
        $reader->xml($input);

        $output = $reader->parse();

        self::assertEquals(
            [
                'name' => '{http://sabredav.org/ns}root',
                'value' => [
                    [
                        'name' => '{http://sabredav.org/ns}uri',
                        'value' => new Uri('http://example.org/foo/bar'),
                        'attributes' => [],
                    ],
                ],
                'attributes' => [],
            ],
            $output
        );
    }

    public function testSerialize(): void
    {
        $writer = new Writer();
        $writer->namespaceMap = [
            'http://sabredav.org/ns' => null,
        ];
        $writer->openMemory();
        $writer->startDocument('1.0');
        $writer->setIndent(true);
        $writer->contextUri = 'http://example.org/';
        $writer->write([
            '{http://sabredav.org/ns}root' => [
                '{http://sabredav.org/ns}uri' => new Uri('/foo/bar'),
            ],
        ]);

        $output = $writer->outputMemory();

        $expected = <<<XML
<?xml version="1.0"?>
<root xmlns="http://sabredav.org/ns">
 <uri>http://example.org/foo/bar</uri>
</root>

XML;

        self::assertEquals($expected, $output);
    }
}
