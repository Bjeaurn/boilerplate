<?php
class ExampleTest extends PHPUnit_Framework_TestCase {
    public function testExample() {
        $a = new Example("Example");

        $this->assertEquals("Example", $a);
    }
}
