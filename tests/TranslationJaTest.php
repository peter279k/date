<?php

use Jenssegers\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class TranslationJaTest
 */
class TranslationJaTest extends TestCase
{
    public function setUp()
    {
        date_default_timezone_set('UTC');
        Date::setLocale('ja');
    }

    public function translateMonthProvider()
    {
        return [
            ['m-d', '01-01', '1月'],
            ['m-d', '02-01', '2月'],
            ['m-d', '03-01', '3月'],
            ['m-d', '04-01', '4月'],
            ['m-d', '05-01', '5月'],
            ['m-d', '06-01', '6月'],
            ['m-d', '07-01', '7月'],
            ['m-d', '08-01', '8月'],
            ['m-d', '09-01', '9月'],
            ['m-d', '10-01', '10月'],
            ['m-d', '11-01', '11月'],
            ['m-d', '12-01', '12月'],
        ];
    }

    /**
     * @dataProvider translateMonthProvider
     */
    public function testTranslateMonth($dateFormat, $dateString, $expected)
    {
        $date = Date::createFromFormat($dateFormat, $dateString);

        $this->assertEquals($expected, $date->format('F'));
    }

    public function translateWeekdaysProvider()
    {
        return [
            ['next monday', '月曜日'],
            ['next tuesday', '火曜日'],
            ['next wednesday', '水曜日'],
            ['next thursday', '木曜日'],
            ['next friday', '金曜日'],
            ['next saturday', '土曜日'],
            ['next sunday', '日曜日'],
        ];
    }

    /**
     * @dataProvider translateWeekdaysProvider
     */
    public function testTranslateWeekdays($dayString, $expected)
    {
        $day = Date::parse($dayString);

        $this->assertEquals($expected, $day->format('l'));
    }

    public function translateWeekdaysShortFormProvider()
    {
        return [
            ['next monday', '月'],
            ['next tuesday', '火'],
            ['next wednesday', '水'],
            ['next thursday', '木'],
            ['next friday', '金'],
            ['next saturday', '土'],
            ['next sunday', '日'],
        ];
    }

    /**
     * @dataProvider translateWeekdaysShortFormProvider
     */
    public function testTranslateWeekdaysShortForm($dayString, $expected)
    {
        $day = Date::parse($dayString);

        $this->assertEquals($expected, $day->format('D'));
    }

    public function translateSecondsAgoProvider()
    {
        return [
            ['-1 second', '1 秒 前'],
            ['-5 second', '5 秒 前'],
        ];
    }

    /**
     * @dataProvider translateSecondsAgoProvider
     */
    public function testTranslateSecondsAgo($timeDescription, $expected)
    {
        $secondAgo = Date::parse($timeDescription);

        $this->assertEquals($expected, $secondAgo->ago());
    }

    public function translateMinutesAgoProvider()
    {
        return [
            ['-1 minute', '1 分 前'],
            ['-5 minute', '5 分 前'],
        ];
    }

    /**
     * @dataProvider translateMinutesAgoProvider
     */
    public function testTranslateMinutesAgo($timeDescription, $expected)
    {
        $minuteAgo = Date::parse($timeDescription);

        $this->assertEquals($expected, $minuteAgo->ago());
    }

    public function translateHoursAgoProvider()
    {
        return [
            ['-1 hour', '1 時間前'],
            ['-5 hours', '5 時間前'],
        ];
    }

    /**
     * @dataProvider translateHoursAgoProvider
     */
    public function testTranslateHoursAgo($timeDescription, $expected)
    {
        $hourAgo = Date::parse($timeDescription);

        $this->assertEquals($expected, $hourAgo->ago());
    }

    public function translateDaysAgoProvider()
    {
        return [
            ['-1 day', '1 日 前'],
            ['-3 days', '3 日 前'],
        ];
    }

    /**
     * @dataProvider translateDaysAgoProvider
     */
    public function testTranslateDaysAgo($dayDescription, $expected)
    {
        $dayAgo = Date::parse($dayDescription);

        $this->assertEquals($expected, $dayAgo->ago());
    }

    public function translateWeeksAgoProvider()
    {
        return [
            ['-1 week', '1 週間 前'],
            ['-3 weeks', '3 週間 前'],
        ];
    }

    /**
     * @dataProvider translateWeeksAgoProvider
     */
    public function testTranslateWeeksAgo($weekDescription, $expected)
    {
        $weekAgo = Date::parse($weekDescription);

        $this->assertEquals($expected, $weekAgo->ago());
    }

    public function translateMonthsAgoProvider()
    {
        return [
            ['-1 month', '1 ヶ月 前'],
            ['-2 months', '2 ヶ月 前'],
        ];
    }

    /**
     * @dataProvider translateMonthsAgoProvider
     */
    public function testTranslateMonthsAgo($monthDescription, $expected)
    {
        $monthAgo = Date::parse($monthDescription);

        $this->assertEquals($expected, $monthAgo->ago());
    }

    public function translateYearsAgoProvider()
    {
        return [
            ['-1 year', '1 年 前'],
            ['-2 years', '2 年 前'],
        ];
    }

    /**
     * @dataProvider translateYearsAgoProvider
     */
    public function testTranslateYearsAgo($yearDescription, $expected)
    {
        $yearAgo = Date::parse($yearDescription);
 
        $this->assertEquals($expected, $yearAgo->ago());
    }

    public function translateSecondsFromNowProvider()
    {
        return [
            ['1 second', '今から 1 秒'],
            ['5 seconds', '今から 5 秒'],
        ];
    }

    /**
     * @dataProvider translateSecondsFromNowProvider
     */
    public function testTranslateSecondsFromNow($secondDescription, $expected)
    {
        $secondFromNow = Date::parse($secondDescription);

        $this->assertEquals($expected, $secondFromNow->diffForHumans());
    }

    public function translateMinutesFromNowProvider()
    {
        return [
            ['1 minute', '今から 1 分'],
            ['5 minute', '今から 5 分'],
        ];
    }

    /**
     * @dataProvider translateMinutesFromNowProvider
     */
    public function testTranslateMinutesFromNow($minuteDescription, $expected)
    {
        $minuteFromNow = Date::parse($minuteDescription);

        $this->assertEquals($expected, $minuteFromNow->diffForHumans());
    }

    public function translateHoursFromNowProvider()
    {
        return [
            ['1 hour', '今から 1 時間'],
            ['5 hours', '今から 5 時間'],
        ];
    }

    /**
     * @dataProvider translateHoursFromNowProvider
     */
    public function testTranslateHoursFromNow($hourDescription, $expected)
    {
        $hourFromNow = Date::parse($hourDescription);

        $this->assertEquals($expected, $hourFromNow->diffForHumans());
    }

    public function translateDaysFromNowProvider()
    {
        return [
            ['1 day', '今から 1 日'],
            ['3 days', '今から 3 日'],
        ];
    }

    /**
     * @dataProvider translateDaysFromNowProvider
     */
    public function testTranslateDaysFromNow($dayDescription, $expected)
    {
        $dayFromNow = Date::parse($dayDescription);

        $this->assertEquals($expected, $dayFromNow->diffForHumans());
    }

    public function translateWeeksFromNowProvider()
    {
        return [
            ['1 week', '今から 1 週間'],
            ['3 weeks', '今から 3 週間'],
        ];
    }

    /**
     * @dataProvider translateWeeksFromNowProvider
     */
    public function testTranslateWeeksFromNow($weekDescription, $expected)
    {
        $weekFromNow = Date::parse($weekDescription);

        $this->assertEquals($expected, $weekFromNow->diffForHumans());
    }

    public function translateMonthsFromNow()
    {
        return [
            ['1 month', '今から 1 ヶ月'],
            ['2 months', '今から 2 ヶ月'],
        ];
    }

    /**
     * @dataProvider translateMonthsFromNow
     */
    public function testTranslateMonthsFromNow($monthDescription, $expected)
    {
        $monthFromNow = Date::parse($monthDescription);

        $this->assertEquals($expected, $monthFromNow->diffForHumans());
    }

    public function translateYearsFromNowProvider()
    {
        return [
            ['1 year', '今から 1 年'],
            ['2 years', '今から 2 年'],
        ];
    }

    /**
     * @dataProvider translateYearsFromNowProvider
     */
    public function testTranslateYearsFromNow($yearDescription, $expected)
    {
        $yearFromNow = Date::parse($yearDescription);

        $this->assertEquals($expected, $yearFromNow->diffForHumans());
    }
}
