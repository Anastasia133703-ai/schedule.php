<?php
declare(strict_types=1);

function generateSchedule(int $year, int $month, int $monthsCount = 1): void
{
    $currentYear = $year;
    $currentMonth = $month;
    $cycleIndex = 0;

    echo "График на {$monthsCount} мес., начиная с $currentMonth.$currentYear\n\n";

    for ($m = 0; $m < $monthsCount; $m++) {

        $monthName = mb_convert_case(strftime('%B', strtotime("$currentYear-$currentMonth-01")), MB_CASE_TITLE, 'UTF-8');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        echo "===== $monthName $currentYear =====\n";

        for ($day = 1; $day <= $daysInMonth; $day++) {

            $timestamp = strtotime("$currentYear-$currentMonth-$day");
            $weekday = date('N', $timestamp);
            $isWorkDay = ($cycleIndex % 3 === 0);

            if ($isWorkDay && ($weekday == 6 || $weekday == 7)) {
                echo date('d.m.Y', $timestamp) . " (выходной) перенос → \033[32mПН\033[0m\n";
                continue;
            }

            if ($isWorkDay) {
                echo "\033[32m" . date('d.m.Y', $timestamp) . " +\033[0m\n";
            } else {
                echo date('d.m.Y', $timestamp) . "\n";
            }

            $cycleIndex++;
        }

        echo "\n";

        $currentMonth++;
        if ($currentMonth > 12) {
            $currentMonth = 1;
            $currentYear++;
        }
    }
}

$year = $argv[1] ?? date('Y');
$month = $argv[2] ?? date('m');
$monthsCount = $argv[3] ?? 1;

generateSchedule((int)$year, (int)$month, (int)$monthsCount);