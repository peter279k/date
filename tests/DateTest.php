<?php

use Carbon\Carbon;
use Jenssegers\Date\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
        Date::setLocale('en');
    }

    public function testConstructs()
    {
        $date = new Date;
        $this->assertInstanceOf(Date::class, $date);
    }

    public function testStaticNow()
    {
        $date = Date::now();
        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals(time(), $date->getTimestamp());
    }

    public function testConstructFromString()
    {
        $date = new Date('2013-01-31');
        $this->assertSame(1359590400, $date->getTimestamp());

        $date = new Date('1 day ago');
        $this->assertSame(time() - 86400, $date->getTimestamp());
    }

    public function testConstructWithTimezone()
    {
        $date = new Date('now', 'Europe/Paris');
        date_default_timezone_set('Europe/Paris');
        $this->assertSame(time(), $date->getTimestamp());

        date_default_timezone_set('Europe/Brussels');

        $date = new Date(null, 'Europe/Paris');
        date_default_timezone_set('Europe/Paris');
        $this->assertSame(time(), $date->getTimestamp());
    }

    public function testConstructTimestamp()
    {
        $date = new Date(1367186296);
        $this->assertSame(1367186296, $date->getTimestamp());
    }

    public function testMake()
    {
        $date1 = Date::make('Sunday 28 April 2013 21:58:16');
        $date2 = new Date('Sunday 28 April 2013 21:58:16');
        $this->assertEquals($date1, $date2);
    }

    public function testCreateFromCarbon()
    {
        $date = Date::make(Carbon::createFromFormat('U', 1367186296));
        $this->assertInstanceOf(Date::class, $date);
        $this->assertEquals(1367186296, $date->getTimestamp());
    }

    public function testManipulation()
    {
        $this->assertInstanceOf(Date::class, Date::now()->add('1 day'));
        $this->assertInstanceOf(Date::class, Date::now()->sub('1 day'));

        $this->assertSame(86400, Date::now()->add('1 day')->getTimestamp() - Date::now()->getTimestamp());
        $this->assertSame(4 * 86400, Date::now()->add('4 day')->getTimestamp() - Date::now()->getTimestamp());

        $this->assertSame(-86400, Date::now()->sub('1 day')->getTimestamp() - Date::now()->getTimestamp());
        $this->assertSame(-4 * 86400, Date::now()->sub('4 day')->getTimestamp() - Date::now()->getTimestamp());

        $this->assertSame(10 * 86400, Date::now()->add('P10D')->getTimestamp() - Date::now()->getTimestamp());
        $this->assertSame(-10 * 86400, Date::now()->sub('P10D')->getTimestamp() - Date::now()->getTimestamp());
    }

    public function testFormat()
    {
        $date = new Date(1367186296);
        $this->assertSame('Sunday 28 April 2013 21:58:16', $date->format('l j F Y H:i:s'));
    }

    public function testAge()
    {
        $date = Date::parse('-5 years');
        $this->assertSame(5, $date->age);
    }

    public function agoProvider()
    {
        return [
            ['-5 years', '5 years ago'],
            ['-5 months', '5 months ago'],
            ['-32 days', '1 month ago'],
            ['-4 days', '4 days ago'],
            ['-1 day', '1 day ago'],
            ['-3 hours', '3 hours ago'],
            ['-1 hour', '1 hour ago'],
            ['-2 minutes', '2 minutes ago'],
            ['-1 minute', '1 minute ago'],
            ['-50 seconds', '50 seconds ago'],
            ['-1 second', '1 second ago'],
            ['+5 days', '5 days from now'],
        ];
    }

    /**
     * @dataProvider agoProvider
     */
    public function testAgo($dateDescription, $expected)
    {
        $date = Date::parse($dateDescription);
        $this->assertSame($expected, $date->ago());
    }

    public function ageNowDateProvider()
    {
        return [
            ['+5 days', '5 days after'],
            ['-5 days', '5 days before'],
        ];
    }

    /**
     * @dataProvider ageNowDateProvider
     */
    public function testAgeOnNowDate($dateDescription, $expected)
    {
        $date = Date::parse($dateDescription);
        $this->assertSame($expected, $date->ago(Date::now()));
    }

    public function absoluteAgoProvider()
    {
        return [
            ['-5 days', '5 days'],
            ['+5 days', '5 days']
        ];
    }

    /**
     * @dataProvider absoluteAgoProvider
     */
    public function testAbsoluteAgo($dateDescription, $expected)
    {
        $date = Date::parse($dateDescription);
        $this->assertSame($expected, $date->ago(Date::now(), true));
    }

    public function diffForHumansProvider()
    {
        return [
            ['-5 years', '5 years ago'],
            ['-15 days', '2 weeks ago'],
            ['-13 days', '1 week ago'],
        ];
    }

    /**
     * @dataProvider diffForHumansProvider
     */
    public function testDiffForHumans($dateDescription, $expected)
    {
        $date = Date::parse($dateDescription);
        $this->assertSame($expected, $date->diffForHumans());
    }

    public function testDiffForHumapnsOnDays()
    {
        $date = Date::parse('-13 days');
        $this->assertSame('1 week', $date->diffForHumans(null, true));
    }

    public function testDiffForHumapnsOnMonths()
    {
        $date = Date::parse('-3 months');
        $this->assertSame('3 months', $date->diffForHumans(null, true));
    }

    public function testDiffForHumansOnWeeks()
    {
        $date = Date::parse('-1 week');
        $future = Date::parse('+1 week');
        $this->assertSame('2 weeks after', $future->diffForHumans($date));
        $this->assertSame('2 weeks before', $date->diffForHumans($future));
    }

    public function testTimespan()
    {
        $date = new Date(1403619368);
        $date = $date->sub('-100 days -3 hours -20 minutes');

        $this->assertSame('3 months, 1 week, 1 day, 3 hours, 20 minutes', $date->timespan(1403619368));
    }

    public function translateTimeStringProvider()
    {
        return [
            ['ru', 'понедельник 21 март 2015', 'monday 21 march 2015'],
            ['de', 'Montag 21 März 2015', 'monday 21 march 2015'],
        ];
    }

    /**
     * @dataProvider translateTimeStringProvider
     */
    public function testTranslateTimeString($locale, $localeString, $expected)
    {
        Date::setLocale($locale);
        $date = Date::translateTimeString($localeString);
        $this->assertSame($expected, $date);
    }
}
