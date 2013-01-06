<?php
/**
 * The model file of date module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     date 
 * @version     $Id: model.php 3938 2013-01-06 01:57:35Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class dateModel extends model
{
    const DAY_IN_FUTURE = 20300101;

    /**
     * Build date list, for selection use.
     * 
     * @param  int    $before 
     * @param  int    $after 
     * @access public
     * @return void
     */
    public function buildDateList($before = 7, $after = 7)
    {
        $today = strtotime($this->today());
        $delta = 60 * 60 * 24;
        $dates = array();
        $weekList     = range(1, 7);
        $weekDateList = explode(',', $this->lang->date->weekDateList);
        for($i = -1 * $before; $i <= $after; $i ++)
        {
            $time   = $today + $i * $delta;
            $label  = date(DT_DATE1, $time);
            if($i == 0)
            {
                $label .= " ({$this->lang->date->today})";
            }
            else
            {
                if($this->cookie->lang == 'zh-cn' or $this->cookie->lang == 'zh-tw')
                {
                    $label .= str_replace($weekList, $weekDateList, date(" ({$this->lang->date->week}N)", $time));
                }
                else
                {
                    $label .= date($this->lang->date->week, $time);
                }
            }
            $date   = date(DT_DATE2, $time);
            $dates[$date] = $label;
        }
        $dates[self::DAY_IN_FUTURE] = $this->lang->date->dayInFuture;
        return $dates;
    }

    /**
     * Build hour time list.
     * 
     * @param  int $begin 
     * @param  int $end 
     * @param  int $delta 
     * @access public
     * @return array
     */
    public function buildTimeList($begin, $end, $delta)
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
    public function today()
    {
        return date(DT_DATE2, time());
    }

    /**
     * Get yesterday 
     * 
     * @access public
     * @return date
     */
    public function yesterday()
    {
        return date(DT_DATE1, strtotime('yesterday'));
    }

    /**
     * Get tomorrow.
     * 
     * @access public
     * @return date
     */
    public function tomorrow()
    {
        return date(DT_DATE1, strtotime('tomorrow'));
    }

    /**
     * Get the day before yesterday.
     * 
     * @access public
     * @return date
     */
    public function twoDaysAgo()
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
    public function now($delta = 10)
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
    public function formatTime($time)
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
    public function getThisWeek()
    {
        $baseTime = $this->getMiddleOfThisWeek();
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
    public function getLastWeek()
    {
        $baseTime = $this->getMiddleOfLastWeek();
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
    public function getMiddleOfThisWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        if($weekDay == 1) $baseTime = time() + 86400;
        if($weekDay == 7) $baseTime = time() - 86400;
        return $baseTime;
    }

    /**
     * Get middle of last week 
     * 
     * @access public
     * @return time
     */
    public function getMiddleOfLastWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        $baseTime = time() - 86400 * 7;
        if($weekDay == 1) $baseTime = time() - 86400 * 4;  // Make sure is last thursday.
        if($weekDay == 7) $baseTime = time() - 86400 * 10; // Make sure is last thursday.
        return $baseTime;
    }

    /**
     * Get this month begin and end time
     * 
     * @access public
     * @return array
     */
    public function getThisMonth()
    {
        $begin = date('Y-m') . '-01 00:00:00';
        $end   = date('Y-m', strtotime('next month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    /**
     * Get last month begin and end time
     * 
     * @access public
     * @return array
     */
    public function getLastMonth()
    {
        $begin = date('Y-m', strtotime('last month')) . '-01 00:00:00';
        $end   = date('Y-m', strtotime('this month')) . '-00 23:59:59';
        return array('begin' => $begin, 'end' => $end);
    }

    public function getThisSeason()
    {
        $year  = date("Y-");
        $month = date("n");
        if($month % 3)
        {
            $seasonBegin = $month - ($month % 3) + 1;
            $seasonEnd   = $seasonBegin + 3;
        }
        else
        {
            $seasonEnd   = $month + 1;
            $seasonBegin = $seasonEnd - 3;
        }

        if(strlen($seasonBegin) == 1) $seasonBegin = "0$seasonBegin";
        if(strlen($seasonEnd) == 1)   $seasonEnd   = "0$seasonEnd";
        $begin = $year . $seasonBegin;
        $end   = $year . $seasonEnd;

        return array('begin' => $begin, 'end' => $end);
    }

    public function getThisYear()
    {
        $begin = date(DT_DATE1, strtotime('1/1 this year'));
        $end   = date(DT_DATE1, strtotime('1/1 next year -1 day'));  
        return array('begin' => $begin, 'end' => $end);
    }
}
