<?php

use Jenssegers\Date\Date;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\MessageSelector;

class AutomaticTest extends TestCase
{
    public function setUp()
    {
        $this->languages = array_slice(scandir('src/Lang'), 2);
    }

    public function translateMonthsProvider()
    {
        return [
            ['january'],
            ['february'],
            ['march'],
            ['april'],
            ['may'],
            ['june'],
            ['july'],
            ['august'],
            ['september'],
            ['october'],
            ['november'],
            ['december'],
        ];
    }

    /**
     * @dataProvider translateMonthsProvider
     */
    public function testTranslatesMonths($months)
    {
        $selector = new MessageSelector;

        foreach ($this->languages as $language) {
            $language = str_replace('.php', '', $language);
            $translations = include "src/Lang/$language.php";

            $date = new Date("1 $months");
            $date->setLocale($language);

            // Full
            $translation = $selector->choose($translations[$months], 0, $language);
            $this->assertNotEmpty($translation);
            $this->assertEquals($translation, $date->format('F'), "Language: $language");

            // Short
            $monthShortEnglish = mb_substr($months, 0, 3);
            if (isset($translations[$monthShortEnglish])) {
                $this->assertEquals($translations[$monthShortEnglish], $date->format('M'), "Language: $language");
            } else {
                $this->assertEquals(mb_substr($translation, 0, 3), $date->format('M'), "Language: $language");
            }
        }
    }

    public function translateDaysProvider()
    {
        return [
            ['monday'],
            ['tuesday'],
            ['wednesday'],
            ['thursday'],
            ['friday'],
            ['saturday'],
            ['sunday'],
        ];
    }

    /**
     * @dataProvider translateDaysProvider
     */
    public function testTranslatesDays($days)
    {
        foreach ($this->languages as $language) {
            $language = str_replace('.php', '', $language);
            $translations = include "src/Lang/$language.php";

            $date = new Date($days);
            $date->setLocale($language);

            // Full
            $this->assertNotEmpty($translations[$days]);
            $this->assertEquals($translations[$days], $date->format('l'), "Language: $language");

            // Short
            $dayShortEnglish = mb_substr($days, 0, 3);
            if (isset($translations[$dayShortEnglish])) {
                $this->assertEquals($translations[$dayShortEnglish], $date->format('D'), "Language: $language");
            } else {
                $this->assertEquals(mb_substr($translations[$days], 0, 3), $date->format('D'), "Language: $language");
            }
        }
    }

    public function timeItemsProvider()
    {
        return [
            ['ago'],
            ['from_now'],
            ['after'],
            ['before'],
            ['year'],
            ['month'],
            ['week'],
            ['day'],
            ['hour'],
            ['minute'],
            ['second'],
        ];
    }

    /**
     * @dataProvider timeItemsProvider
     */
    public function testTranslatesDiffForHumans($items)
    {
        foreach ($this->languages as $language) {
            $language = str_replace('.php', '', $language);
            $translations = include "src/Lang/$language.php";

            $this->assertNotEmpty($translations[$items], "Language: $language >> $items");

            if (! $translations[$items]) {
                echo "\nWARNING! '$items' not set for language $language";
                continue;
            }

            if (in_array($items, ['ago', 'from_now', 'after', 'before'])) {
                $this->assertContains(':time', $translations[$items], "Language: $language");
            } else {
                $this->assertContains(':count', $translations[$items], "Language: $language");
            }
        }
    }

    /**
     * @dataProvider timeItemsProvider
     */
    public function testTranslatesCounts($items)
    {
        foreach ($this->languages as $language) {
            $language = str_replace('.php', '', $language);
            $translations = include "src/Lang/$language.php";

            $translator = Date::getTranslator();
            $translator->setLocale($language);

            $this->assertNotEmpty($translations[$items], "Language: $language >> $items");

            if (! $translations[$items]) {
                echo "\nWARNING! '$items' not set for language $language\n";
                continue;
            }

            for ($i = 0; $i <= 60; $i++) {
                if (in_array($items, ['ago', 'from_now', 'after', 'before'])) {
                    $translation = $translator->transChoice($items, $i, [':time' => $i]);
                    $this->assertNotNull($translation, "Language: $language ($i)");
                    $this->assertNotContains(':time', $translation, "Language: $language ($i)");
                } else {
                    $translation = $translator->transChoice($items, $i, [':count' => $i]);
                    $this->assertNotNull($translation, "Language: $language ($i)");
                    $this->assertNotContains(':count', $translation, "Language: $language ($i)");
                }
            }
        }
    }
}
