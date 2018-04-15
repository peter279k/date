<?php

use Jenssegers\Date\Date;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\Translator;

class TranslationHuTest extends TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
        Date::setLocale('hu');
    }

    public function testGetsAndSetsTranslator()
    {
        $translator = new Translator('hu');
        $translator->addLoader('array', new ArrayLoader());
        $this->assertNotEquals($translator, Date::getTranslator());

        Date::setTranslator($translator);
        $this->assertEquals($translator, Date::getTranslator());
    }

    public function testTimespanTranslated()
    {
        $date = new Date(1403619368);
        $date = $date->sub('-100 days -3 hours -20 minutes');

        $this->assertSame('3 hónap, 1 hét, 1 nap, 3 óra, 20 perc', $date->timespan(1403619368));
    }

    public function agoTranslatedProvider()
    {
        return [
            ['-1 minute', '1 perce'],
            ['-21 hours', '21 órája'],
            ['-5 days', '5 napja'],
            ['-3 weeks', '3 hete'],
            ['-6 months', '6 hónapja'],
            ['-10 years', '10 éve'],
        ];
    }

    /**
     * @dataProvider agoTranslatedProvider
     */
    public function testAgoTranslated($timeDescription, $expected)
    {
        $date = Date::parse($timeDescription);
        $this->assertSame($expected, $date->ago());
    }

    public function fromNowTranslatedProvider()
    {
        return [
            ['+1 minute', '1 perc múlva'],
            ['+21 hours', '21 óra múlva'],
            ['+5 days', '5 nap múlva'],
            ['+3 weeks', '3 hét múlva'],
            ['+6 months', '6 hónap múlva'],
            ['+10 years', '10 év múlva'],
        ];
    }

    /**
     * @dataProvider fromNowTranslatedProvider
     */
    public function testFromNowTranslated($timeDescription, $expected)
    {
        $date = Date::parse($timeDescription);
        $this->assertSame($expected, $date->ago());
    }

    public function afterTranslatedProvider()
    {
        return [
            ['+21 hours', '21 órával később'],
            ['+5 days', '5 nappal később'],
            ['+3 weeks', '3 héttel később'],
            ['+6 months', '6 hónappal később'],
            ['+10 years', '10 évvel később'],
        ];
    }

    /**
     * @dataProvider afterTranslatedProvider
     */
    public function testAfterTranslated($timeDescription, $expected)
    {
        $date = Date::parse($timeDescription);
        $this->assertSame($expected, $date->ago(Date::now()));
    }

    public function beforeTranslatedProvider()
    {
        return [
            ['-21 hours', '21 órával korábban'],
            ['-5 days', '5 nappal korábban'],
            ['-3 weeks', '3 héttel korábban'],
            ['-6 months', '6 hónappal korábban'],
            ['-10 years', '10 évvel korábban'],
        ];
    }

    /**
     * @dataProvider beforeTranslatedProvider
     */
    public function testBeforeTranslated($timeDescription, $expected)
    {
        $date = Date::parse($timeDescription);
        $this->assertSame($expected, $date->ago(Date::now()));
    }

    public function createFromFormatProvider()
    {
        return [
            ['Y. F d.', '2015. január 1.', '2015-01-01'],
            ['Y. F d., D', '2015. március 21., szombat', '2015-03-21'],
        ];
    }

    /**
     * @dataProvider createFromFormatProvider
     */
    public function testCreateFromFormat($dateFormat, $dateDescription, $expected)
    {
        $date = Date::createFromFormat($dateFormat, $dateDescription);
        $this->assertSame($expected, $date->format('Y-m-d'));
    }
}
