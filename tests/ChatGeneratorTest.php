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

        $this->assertEquals('<script>alert(\x27xss\x27)</script>', $ret);
    }

    public function test_javascriptEscape_someSpecialChars()
    {
        $ret = $this->chat->javascriptEscape("\"'*!@#$%^&*()_+}{:?><.,/`~");

        $this->assertEquals('"\x27*!@#$%^&*()_+}{:?><.,/`~', $ret);
    }

    public function test_hideWidget()
    {
        $this->chat->hideWidget();

        $this->assertTrue(self::getPrivateField($this->chat, 'hide_widget'));
    }

    public function test_setGoogleAnalytics()
    {
        $this->chat->setGoogleAnalytics('UA-123456789');

        $this->assertEquals('UA-123456789', self::getPrivateField($this->chat, 'ga_key'));
    }

    public function test_setGoogleAnalytics_withOptions()
    {
        $options = array('cookieDomain' => 'foo.example.com');
        $this->chat->setGoogleAnalytics('UA-123456789', $options);

        $this->assertEquals('UA-123456789', self::getPrivateField($this->chat, 'ga_key'));
        $this->assertEquals($options, self::getPrivateField($this->chat, 'ga_options'));
    }

    public function test_widget()
    {
        $this->chat->setWidget('button');
        $this->assertEquals('button', self::getPrivateField($this->chat, 'widget'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Widget value foo is not allowed value. You can use only one of values: button, widget.
     */
    public function test_widget_badParam()
    {
        $this->chat->setWidget('foo');
    }

    public function test_setBoxPosition()
    {
        $this->chat->setAlign('left', 'side', 20, 120);

        $this->assertEquals('left', self::getPrivateField($this->chat, 'align_x'));
        $this->assertEquals('side', self::getPrivateField($this->chat, 'align_y'));
        $this->assertEquals('20', self::getPrivateField($this->chat, 'offset_x'));
        $this->assertEquals('120', self::getPrivateField($this->chat, 'offset_y'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage AllignX value foo is not allowed value. You can use only one of values: right, left.
     */
    public function test_setBoxPosition_badParam()
    {
        $this->chat->setAlign('foo', 'side', 20, 120);
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage AllignY value foo is not allowed value. You can use only one of values: side, bottom.
     */
    public function test_setBoxPosition_badParam2()
    {
        $this->chat->setAlign('left', 'foo', 20, 120);
    }

    public function test_setVariable()
    {
        $this->chat->setVariable('userId', 'User ID', 123);
        $this->chat->setVariable('orderedPrice', 'Ordered Price in Eshop', '128 000');

        $this->assertEquals(
            array(
                array('id' => 'userId', 'label' => 'User ID', 'value' => 123),
                array('id' => 'orderedPrice', 'label' => 'Ordered Price in Eshop', 'value' => '128 000')
            ),
            self::getPrivateField($this->chat, 'variables')
        );
    }

    public function test_setUserBasicInformation()
    {
        $this->chat->setName('Johny Depp');
        $this->chat->setEmail('johny@depp.com');

        $this->assertEquals('Johny Depp', self::getPrivateField($this->chat, 'name'));
        $this->assertEquals('johny@depp.com', self::getPrivateField($this->chat, 'email'));
    }

    public function test_enableRating()
    {
        $this->chat->enableRating('advanced', true);

        $this->assertTrue(self::getPrivateField($this->chat, 'rating_enabled'));
        $this->assertEquals('advanced', self::getPrivateField($this->chat, 'rating_type'));
        $this->assertTrue(self::getPrivateField($this->chat, 'rating_comment'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Rating type foo is not allowed value. You can use only one of values: advanced, simple.
     */
    public function test_enableRating_badParam()
    {
        $this->chat->enableRating('foo');
    }

    public function test_disableSendEmailTranscript()
    {
        $this->assertTrue(self::getPrivateField($this->chat, 'send_email_transcript'));
        $this->chat->disableSendEmailTranscript();
        $this->assertFalse(self::getPrivateField($this->chat, 'send_email_transcript'));
    }

    public function test_setCookieDomain()
    {
        $this->assertNull(self::getPrivateField($this->chat, 'cookie_domain'));
        $this->chat->setCookieDomain('.foo.bar');
        $this->assertEquals('.foo.bar', self::getPrivateField($this->chat, 'cookie_domain'));
    }

    public function test_setKey()
    {
        $this->assertNull(self::getPrivateField($this->chat, 'key'));
        $this->chat->setKey('123456');
        $this->assertEquals('123456', self::getPrivateField($this->chat, 'key'));
    }

    public function test_setCharset()
    {
        $this->assertEquals('utf-8', self::getPrivateField($this->chat, 'charset'));
        $this->chat->setCharset('utf-32');
        $this->assertEquals('utf-32', self::getPrivateField($this->chat, 'charset'));
    }

    public function test_setLanguage()
    {
        $this->assertEquals('en', self::getPrivateField($this->chat, 'language'));
        $this->chat->setLanguage('cs');
        $this->assertEquals('cs', self::getPrivateField($this->chat, 'language'));
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionMessage At least KEY param must be set!
     */
    public function test_render_keyNotSet()
    {
        $this->chat->render();
    }

    public function test_render_simple()
    {
        $this->chat->setKey('XYZ123456');
        $ret = $this->chat->render();

        $expected = "<script type=\"text/javascript\">
            var _smartsupp = _smartsupp || {};
            _smartsupp.key = 'XYZ123456';

window.smartsupp||(function(d) {
                var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
                s=d.getElementsByTagName('script')[0];c=d.createElement('script');
                c.type='text/javascript';c.charset='utf-8';c.async=true;
                c.src='//www.smartsuppchat.com/loader.js';s.parentNode.insertBefore(c,s);
            })(document);
            </script>";

        $this->assertEquals($expected, $ret);
    }

    public function test_render_simpleOutput()
    {
        $this->chat->setKey('XYZ123456');
        $ret = $this->chat->render(true);

        $this->assertNull($ret);
        $this->expectOutputRegex('/.*window.smartsupp.*/');
    }

    public function test_render_allParams()
    {
        $this->chat->setKey('XYZ123456');
        $this->chat->setCookieDomain('.foo.bar');
        $this->chat->disableSendEmailTranscript();
        $this->chat->enableRating('advanced', true);
        $this->chat->setAlign('left', 'side', 20, 120);
        $this->chat->setWidget('button');
        $this->chat->setName('Johny Depp');
        $this->chat->setEmail('johny@depp.com');
        $this->chat->setVariable('orderTotal', 'Total orders', 150);
        $this->chat->setVariable('lastOrder', 'Last ordered', '2015-07-09');
        $this->chat->setGoogleAnalytics('UA-123456', array('cookieDomain' => '.foo.bar'));
        $this->chat->hideWidget();

        $ret = $this->chat->render();

        $this->assertEquals(file_get_contents(dirname(__FILE__) . '/chat_code.txt'), $ret);
    }


    /**
     * Get private / protected field value using \ReflectionProperty object.
     *
     * @static
     * @param mixed $object object to be used
     * @param string $fieldName object property name
     * @return mixed given property value
     */
    public static function getPrivateField($object, $fieldName)
    {
        $refId = new \ReflectionProperty($object, $fieldName);
        $refId->setAccessible(true);
        $value = $refId->getValue($object);
        $refId->setAccessible(false);

        return $value;
    }
}
