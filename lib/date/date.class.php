<?php
/**
 * The date library of zentaopms.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     Date
 * @version     $Id: date.class.php 2605 2013-01-09 07:22:58Z wwccss $
 * @link        http://www.zentao.net
 */
class date 
{
    /**
     * Build hour time list.
     * 
     * @param  int $begin 
     * @param  int $end 
     * @param  int $delta 
     * @access public
     * @return array
     */
    public static function buildTimeList($begin, $end, $delta)
    {
        $times = array();
        for($hour = $begin; $hour <= $end; $hour ++)
        {
            for($minutes = 0; $minutes < 60; $minutes += $delta)
            {
                $time  = sprintf('%02d%02d', $hour, $minutes);
                $label = sprintf('%02d:%02d', $hour, $minutes);
                $times[$time] = $label;
            }
        }
        return $times;
    }

    /**
     * Get today.
     * 
     * @access public
     * @return date
     */
    public static function today()
    {
        return date(DT_DATE2, time());
    }

    /**
     * Get yesterday 
     * 
     * @access public
     * @return date
     */
    public static function yesterday()
    {
        return date(DT_DATE1, strtotime('yesterday'));
    }

    /**
     * Get tomorrow.
     * 
     * @access public
     * @return date
     */
    public static function tomorrow()
    {
        return date(DT_DATE1, strtotime('tomorrow'));
    }

    /**
     * Get the day before yesterday.
     * 
     * @access public
     * @return date
     */
    public static function twoDaysAgo()
    {
        return date(DT_DATE1, strtotime('-2 days'));
    }

    /**
     * Get now time period.
     * 
     * @param  int    $delta 
     * @access public
     * @return string the current time period, like 0915
     */
    public static function now($delta = 10)
    {
        $range  = range($delta, 60 - $delta, $delta);
        $hour   = date('H', time());
        $minute = date('i', time());

        if($minute > 60 - $delta)
        {
            $hour += 1;
            $minute = 00;
        }
        else
        {
            for($i = 0; $i < $delta; $i ++)
            {
                if(in_array($minute + $i, $range))
                {
                    $minute = $minute + $i;
                    break;
                }
            }
        }

        return sprintf('%02d%02d', $hour, $minute);
    }

    /**
     * Format time 0915 to 09:15
     * 
     * @param  string $time 
     * @access public
     * @return string
     */
    public static function formatTime($time)
    {
        if(strlen($time) != 4 or $time == '2400') return '';
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    }

    /**
     * Get the begin and end date of this week.
     * 
     * @access public
     * @return array
     */
    public static function getThisWeek()
    {
        $baseTime = self::getMiddleOfThisWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime)) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime)) . ' 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get the begin and end date of last week.
     * 
     * @access public
     * @return array
     */
    public static function getLastWeek()
    {
        $baseTime = self::getMiddleOfLastWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime)) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime)) . ' 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get the time at the middle of this week.
     * 
     * If today in week is 1, move it one day in future. Else is 7, move it back one day. To keep the time geted in this week.
     *
     * @access public
     * @return time
     */
    public static function getMiddleOfThisWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        if($weekDay == 1) $baseTime = time() + 86400;
        if($weekDay == 7) $baseTime = time() - 86400;
        return $baseTime;
    }

    /**
     * Get middle of last week.
     * 
     * @access public
     * @return time
     */
    public static function getMiddleOfLastWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        $baseTime = time() - 86400 * 7;
        if($weekDay == 1) $baseTime = time() - 86400 * 4;  // Make sure is last thursday.
        if($weekDay == 7) $baseTime = time() - 86400 * 10; // Make sure is last thursday.
        return $baseTime;
    }

    /**
     * Get begin and end time of this month.
     * 
     * @access public
     * @return array
     */
    public static function getThisMonth()
    {
        $begin = date('Y-m') . '-01 00:00:00';
        $end   = date('Y-m', strtotime('next month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get begin and end time of last month.
     * 
     * @access public
     * @return array
     */
    public static function getLastMonth()
    {
        $begin = date('Y-m', strtotime('last month')) . '-01 00:00:00';
        $end   = date('Y-m', strtotime('this month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get begin and end time of this season. 
     * 
     * @static
     * @access public
     * @return array 
     */
    public static function getThisSeason()
    {
        $season = ceil((date('n')) / 3);                                                // Get this session.
        $begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 2, 1, date('Y')));
        $endDay = date('t', mktime(0, 0 , 0, $season * 3, 1, date("Y")));               // Get end day.
        $end    = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, $endDay, date('Y')));

        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get begin and end time of last season. 
     * 
     * @static
     * @access public
     * @return array 
     */
    public static function getLastSeason()
    {
        $season = ceil((date('n')) / 3) - 1;                                             // Get last session.
        $begin  = date('Y-m-d H:i:s', mktime(0, 0, 0, $season * 3 - 2, 1, date('Y')));
        $endDay = date('t', mktime(0, 0 , 0, $season * 3, 1, date("Y")));                // Get end day.
        $end    = date('Y-m-d H:i:s', mktime(23, 59, 59, $season * 3, $endDay, date('Y')));
        
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get begin and end time of this year.
     * 
     * @static
     * @access public
     * @return array 
     */
    public static function getThisYear()
    {
        $begin = date(DT_DATE1, strtotime('1/1 this year')) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('1/1 next year -1 day')) . ' 23:59:59';  
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get begin and end time of last year.
     * 
     * @static
     * @access public
     * @return array 
     */
    public static function getLastYear()
    {
        $begin = date(DT_DATE1, strtotime('1/1 last year')) . ' 00:00:00';
        $end   = date(DT_DATE1, strtotime('1/1 this year -1 day')) . ' 23:59:59';  
        return array('begin' => $begin, 'end' => $end);
    }
}
