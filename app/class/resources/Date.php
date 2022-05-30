<?php

class Date
{


    private $_format = "d/m/Y";
    private $_date;
    private $_holidays;

    public function __construct()
    {
        try {
            $year = intval(date("Y"));
            $nextYear = $year + 1;
            $this->_holidays = array("$year-01-01", "$year-01-02", "$year-04-21", "$year-05-01", "$year-06-11", "$year-09-07", "$year-10-12", "$year-11-02", "$year-11-15", "$year-12-24", "$year-12-25", "$year-12-26", "$year-12-27", "$year-12-28", "$year-12-29", "$year-12-30", "$year-12-31", "$nextYear-01-01");
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function set($date): Date
    {
        if (not_empty($date)) {
            $date = str_replace("T", " ", $date);
            $date = str_replace("Z", " ", $date);
            $date = str_replace("/", "-", $date);
            $date = date("Y-m-d H:i:s", strtotime($date));
            $this->_date = date("Y-m-d H:i:s", strtotime($date));
        } else {
            $this->_date = null;
        }
        return $this;
    }

    public function now(): Date
    {
        $this->_date = date("Y-m-d H:i:s");
        return $this;
    }

    public function database()
    {
        if (not_empty($this->_date)) {
            $date = implode('-', array_reverse(explode('/', $this->_date)));
            return date("Y-m-d H:i:s", strtotime($date));
        }
        return null;
    }

    public function add(string $string): Date
    {
        $this->_date = date("Y-m-d H:i:s", strtotime($string . " " . $this->_date));
        return $this;
    }

    public function differenceDaysFromNow(): float
    {
        return round((strtotime(date("Y-m-d H:i:s")) - strtotime($this->_date)) / (60 * 60 * 24), 3);
    }

    public function differenceDaysToNow(): float
    {
        return round((strtotime($this->_date) - strtotime(date("Y-m-d H:i:s"))) / (60 * 60 * 24), 3);
    }


    public function strftime($format): string
    {
        return ucwords(strftime($format, strtotime($this->_date)));
    }

    public function plusMinutes(int $minutes): Date
    {
        return $this->add("+$minutes minutes");
    }

    public function plusHours(int $hours): Date
    {
        return $this->add("+$hours hours");
    }

    public function plusDays(int $days): Date
    {
        return $this->add("+$days days");
    }

    public function plusMonths(int $months): Date
    {
        return $this->add("+$months months");
    }

    public function plusYears(int $years): Date
    {
        return $this->add("+$years years");
    }

    public function nextBusinessDay(): Date
    {
        $holiday_leave = $this->getDaysToLeaveHoliday($this->_date);
        $left_holiday_date = date("Y-m-d", strtotime("$this->_date +$holiday_leave days"));
        $next_workday = $this->getDaysToNextWorkday($left_holiday_date);
        $this->_date = date("Y-m-d H:i:s", strtotime("$left_holiday_date +$next_workday days"));
        return $this;
    }

    public function format(string $format): Date
    {
        $this->_format = $format;
        return $this;
    }

    public function output()
    {
        return not_empty($this->_date) ? date($this->_format, strtotime($this->_date)) : null;
    }

    public function isHoliday($custom_date = null): bool
    {
        if (!not_empty($custom_date)) $custom_date = $this->_date;
        $year = date("Y");
        $nextYear = $year + 1;
        $holidays = array("$year-01-01", "$year-01-02", "$year-04-21", "$year-05-01", "$year-06-11", "$year-09-07", "$year-10-12", "$year-11-02", "$year-11-15", "$year-12-24", "$year-12-25", "$year-12-26", "$year-12-27", "$year-12-28", "$year-12-29", "$year-12-30", "$year-12-31", "$nextYear-01-01");
        foreach ($holidays as $key) {
            if ($custom_date === $key) {
                return true;
            }
        }
        return false;
    }

    public function getNextWorkdaysByFuturePeriods($number_of_future_months = 1, $due_day_number = 1, $last_date = null)
    {
        $days_to_consider = 20;
        $date = new Date();
        $today = date("Y-m-d");
        $result = $today;
        $last_date = str_replace('/', '-', $last_date);
        if ($number_of_future_months > 0) {
            if (not_empty($last_date) && $number_of_future_months > 1) {
                $result = date("Y-m-$due_day_number", strtotime("$last_date +1 month"));
                $day_num = date("d", strtotime($result));
                if ($day_num > $due_day_number) {
                    $result = date("Y-m-" . $due_day_number, strtotime($result));
                }
            } else {
                $result = date("Y-m-" . $due_day_number);
                $diff_last_and_now = $date->getDaysOfDifference($last_date, $result);
                if ($diff_last_and_now < $days_to_consider) {
                    $result = date("Y-m-$due_day_number", strtotime($result . "+ 2 month"));
                    if (intval(date("m", strtotime($last_date))) === intval(date("m", strtotime($result)))) {
                        $result = date("Y-m-$due_day_number", strtotime($result . "+ 2 month"));
                    }
                }
            }
        } else {
            $result = date("Y-m-d", strtotime("$result +1 day"));
        }

        return $this->getNextBusinessDay($result);
    }


    /* === PRIVATES */

    private function getDaysOfDifference($date1, $date2)
    {
        $return = $this->getDataDifference($date1, $date2);
        if ($date1 > $date2) {
            $return = ($return * -1);
        }
        return $return;
    }

    private function getDaysToLeaveHoliday($date, $ignore_weekends = false): int
    {
        $add_day = 0;
        while ($this->isHoliday($date)) {
            $date = date('Y-m-d', strtotime("$date +1 days"));
            $day_of_week = date('w', strtotime($date));
            if ($ignore_weekends && ($day_of_week !== 6 || $day_of_week !== 0)) {
                $add_day++;
            } else if (!$ignore_weekends) {
                $add_day++;
            }
        }
        return $add_day;
    }

    private function getDaysToNextWorkday($date): int
    {
        $add_day = 0;
        $weekday = date('w', strtotime($date));
        if ($weekday === 6 || $weekday === 0) {
            do {
                $add_day++;
                $new_date = date('Y-m-d', strtotime("$this->_date +$add_day Days"));
                $new_day_of_week = intval(date('w', strtotime($new_date)));
            } while ($new_day_of_week === 6 || $new_day_of_week === 0);
        }
        return $add_day;
    }

    public function getNextBusinessDay($date)
    {
        $holiday_leave = $this->getDaysToLeaveHoliday($date);
        $left_holiday_date = date("Y-m-d", strtotime("$date +$holiday_leave days"));
        $next_workday = $this->getDaysToNextWorkday($left_holiday_date);
        return date($this->_format, strtotime("$left_holiday_date +$next_workday days"));
    }

    private function getDataDifference($date1, $date2)
    {
        $date1 = strtotime($date1);
        $date2 = strtotime($date2);
        $diff = abs($date2 - $date1);
        return floor(abs($diff) / (60 * 60 * 24));
    }

    public function getTimeAgo($timestamp): ?string
    {
        $timestamp = date("Y-m-d H:i:s", strtotime($timestamp));
        $time_today = date("Y-m-d H:i:s");
        $time_ago = strtotime($timestamp);
        $current_time = strtotime($time_today);
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);
        if ($seconds <= 60) {
            return translate("Just Now");
        } else if ($minutes <= 60) {
            if ($minutes == 1) {
                return translate("1 minute");
            } else {
                return $minutes . " " . translate("minutes");
            }
        } else if ($hours <= 24) {
            if ($hours == 1) {
                return translate("1 hour");
            } else {
                return $hours . " " . translate("hours");
            }
        } else if ($days <= 7) {
            if ($days == 1) {
                return translate("yesterday");
            } else {
                return $days . " " . translate("days");
            }
        } else if ($weeks <= 4.3) //4.3 == 52/12
        {
            if ($weeks == 1) {
                return translate("1 week");
            } else {
                return $weeks . " " . translate("weeks");
            }
        } else if ($months <= 12) {
            if ($months == 1) {
                return "1 month";
            } else {
                return $months . " " . translate("months");
            }
        } else {
            if ($years == 1) {
                return "1 year";
            } else {
                return $years . " " . translate("years");
            }
        }
    }

}