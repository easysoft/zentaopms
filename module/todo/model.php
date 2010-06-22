<?php
/**
 * The model file of todo module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *                                                                             
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php
class todoModel extends model
{
    const DAY_IN_FEATURE = 20300101;

    /* 新增一个todo。*/
    public function create($date, $account)
    {
        $todo = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->add('idvalue', 0)
            ->specialChars('type,name,desc')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type != 'custom', 'name', '')
            ->setIF($this->post->type == 'bug'  and $this->post->bug,  'idvalue', $this->post->bug)
            ->setIF($this->post->type == 'task' and $this->post->task, 'idvalue', $this->post->task)
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end',   '2400')
            ->remove('bug, task')
            ->get();

        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF($todo->type == 'bug'  and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->checkIF($todo->type == 'task' and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();
        return $this->dao->lastInsertID();
    }

    /* 更新一个todo。*/
    public function update($todoID)
    {
        $oldTodo = $this->getById($todoID);
        if($oldTodo->type != 'custom') $oldTodo->name = '';
        $todo = fixer::input('post')
            ->cleanInt('date, pri, begin, end, private')
            ->specialChars('type,name,desc')
            ->setIF($this->post->type != 'custom', 'name',  '')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->get();
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')->where('id')->eq($todoID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldTodo, $todo);
    }
    
    /* 更改状态。*/
    public function mark($todoID, $status)
    {
        $status = ($status == 'done') ? 'wait' : 'done';
        $this->dao->update(TABLE_TODO)->set('status')->eq($status)->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'marked', '', $status);
        return;
    }

    /* 获得一条todo信息。*/
    public function getById($todoID)
    {
        $todo = $this->dao->findById((int)$todoID)->from(TABLE_TODO)->fetch();
        if(!$todo) return false;
        if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
    }

    /* 获得用户的todo列表。*/
    public function getList($date = 'today', $account = '', $status = 'all')
    {
        $todos = array();
        if($date == 'today') 
        {
            $begin = $this->today();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract($this->getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract($this->getLastWeek());
        }
        elseif($date == 'all')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($date == 'before')
        {
            $begin = '1970-01-01';
            $end   = $this->yesterday();
        }
        else
        {
            $begin = $end = $date;
        }

        if($account == '')   $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('account')->eq($account)
            ->andWhere("date >= '$begin'")
            ->andWhere("date <= '$end'")
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->endIF()
            ->beginIF($status == 'undone')->andWhere('status')->ne('done')->endIF()
            ->orderBy('date, status, begin')
            ->query();
        while($todo = $stmt->fetch())
        {
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
            $todo->begin = $this->formatTime($todo->begin);
            $todo->end   = $this->formatTime($todo->end);

            /* 如果是私人事务，且当前用户非本人，更改标题。*/
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }
        return $todos;
    }

    /* 生成日期列表。*/
    public function buildDateList($before = 7, $after = 7)
    {
        $today = strtotime($this->today());
        $delta = 60 * 60 * 24;
        $dates = array();
        $weekList     = range(1, 7);
        $weekDateList = explode(',', $this->lang->todo->weekDateList);
        for($i = -1 * $before; $i <= $after; $i ++)
        {
            $time   = $today + $i * $delta;
            $label  = date(DT_DATE1, $time);
            if($i == 0)
            {
                $label .= " ({$this->lang->todo->today})";
            }
            else
            {
                $label .= str_replace($weekList, $weekDateList, date(" ({$this->lang->todo->week}N)", $time));
            }
            $date   = date(DT_DATE2, $time);
            $dates[$date] = $label;
        }
        $dates[self::DAY_IN_FEATURE] = $this->lang->todo->dayInFeature;
        return $dates;
    }

    /* 生成时钟列表。*/
    public function buildTimeList($begin = 9, $end = 22, $delta = 15)
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

    /* 获得当天日期。*/
    public function today()
    {
        return date(DT_DATE2, time());
    }

    /* 获得昨天的日期。*/
    public function yesterday()
    {
        return date(DT_DATE1, strtotime('yesterday'));
    }

    /* 获得当前的时间。*/
    public function now($delta = 15)
    {
        $range = range($delta, 60 - $delta, $delta);
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

    /* 格式化时间显示。*/
    public function formatTime($time)
    {
        if(strlen($time) != 4 or $time == '2400') return '';
        return substr($time, 0, 2) . ':' . substr($time, 2, 2);
    }

    /* 获得本周起止时间。*/
    public function getThisWeek()
    {
        $baseTime = $this->getMiddleOfThisWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime));
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime));
        return array('begin' => $begin, 'end' => $end);
    }

    /* 获得上周起止时间。*/
    public function getLastWeek()
    {
        $baseTime = $this->getMiddleOfLastWeek();
        $begin = date(DT_DATE1, strtotime('last monday', $baseTime));
        $end   = date(DT_DATE1, strtotime('next sunday', $baseTime));
        return array('begin' => $begin, 'end' => $end);
    }

    /* 获得周中的时间戳，如果当前时间为礼拜一，则往后取一天，为礼拜天，则往前取一天，保证基准时间落在周中。*/
    private function getMiddleOfThisWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        if($weekDay == 1) $baseTime = time() + 86400;
        if($weekDay == 7) $baseTime = time() - 86400;
        return $baseTime;
    }

    /* 获得上周周中的时间。*/
    private function getMiddleOfLastWeek()
    {
        $baseTime = time();
        $weekDay  = date('N');
        $baseTime = time() - 86400 * 7;
        if($weekDay == 1) $baseTime = time() - 86400 * 4;  // 上个礼拜四
        if($weekDay == 7) $baseTime = time() - 86400 * 10; // 上个礼拜四
        return $baseTime;
    }
}
