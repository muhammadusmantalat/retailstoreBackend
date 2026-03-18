<?php

namespace App\Helpers;

use Carbon\Carbon;

class Notificationhelper
{

    public static function deliveryNotification($deliveryFrequencyWeeks,$deliveryDay)
    {
        $currentDate = Carbon::now();

        // Get the current week of the year
        $currentWeekOfYear = $currentDate->weekOfYear;

        // Number of weeks to add
        $weeksToAdd = $deliveryFrequencyWeeks ; // You can change this to whatever number of weeks you want to add

        // Day of the week to set (e.g., 'Monday', 'Friday')
        $desiredDayOfWeek = $deliveryDay; // You can change this to any day

        // Calculate the new week number
        $newWeekNumber = $currentWeekOfYear + $weeksToAdd;

        // Set the date to the first day of the year
        $firstDayOfYear = Carbon::create($currentDate->year, 1, 1);

        // Move to the calculated week of the year and set the desired day
        $resultDate = $firstDayOfYear->setISODate($currentDate->year, $newWeekNumber, Carbon::parse($desiredDayOfWeek)->dayOfWeek);

        return $resultDate;
    }
}
