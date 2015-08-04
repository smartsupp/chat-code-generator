<?php
namespace Smartsupp;

class ChatGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChatGenerator
     */
    protected $chat;

    protected function setUp()
    {
        parent::setUp();

        $this->chat = new ChatGenerator();
    }

    public function test_javascriptEscape()
    {
        $ret = $this->chat->javascriptEscape('abc123');

        $this->assertEquals('abc123', $ret);
    }

    public function test_javascriptEscape_someScript()
    {
        $ret = $this->chat->javascriptEscape("<script>alert('xss')</script>");

        $this->assertEquals('\x3cscript\x3ealert\x28\x27xss\x27\x29\x3c\x2fscript\x3e', $ret);
    }

    public function test_javascriptEscape_someSpecialChars()
    {
        $ret = $this->chat->javascriptEscape("\"'*!@#$%^&*()_+}{:?><.,/`~");

        $this->assertEquals('\x22\x27\x2a\x21\x40\x23\x24\x25\x5e\x26\x2a\x28\x29\x5f\x2b\x7d\x7b\x3a\x3f\x3e\x3c\x2e' .
            '\x2c\x2f\x60\x7e', $ret);
    }
}
