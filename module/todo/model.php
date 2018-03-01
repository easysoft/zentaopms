<?php
/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: model.php 5035 2013-07-06 05:21:58Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class todoModel extends model
{
    /**
     * Create a todo.
     *
     * @param  date   $date
     * @param  string $account
     * @access public
     * @return void
     */
    public function create($date, $account)
    {
        $todo = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->setDefault('idvalue', 0)
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type == 'bug'  and $this->post->bug,  'idvalue', $this->post->bug)
            ->setIF($this->post->type == 'task' and $this->post->task, 'idvalue', $this->post->task)
            ->setIF($this->post->type == 'story' and $this->post->story, 'idvalue', $this->post->story)
            ->setIF($this->post->date == false,  'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end',   '2400')
            ->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)
            ->remove('bug, task, uid')
            ->get();
        if(empty($todo->cycle)) unset($todo->config);
        if(!empty($todo->cycle))
        {
            $todo->config['begin'] = $todo->date;
            if($todo->config['type'] == 'day')
            {
                unset($todo->config['week']);
                unset($todo->config['month']);
            }
            if($todo->config['type'] == 'week')
            {
                unset($todo->config['day']);
                unset($todo->config['month']);
                $todo->config['week'] = join(',', $todo->config['week']);
            }
            if($todo->config['type'] == 'month')
            {
                unset($todo->config['day']);
                unset($todo->config['week']);
                $todo->config['month'] = join(',', $todo->config['month']);
            }
            $todo->config = json_encode($todo->config);
        }

        $todo = $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(!in_array($todo->type, array('bug', 'task', 'story')), $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF($todo->type == 'bug'   and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->checkIF($todo->type == 'task'  and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->checkIF($todo->type == 'story' and $todo->idvalue == 0, 'idvalue', 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $todoID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $todoID, 'todo');
            $this->loadModel('score')->create('todo', 'create', $todoID);
            if(!empty($todo->cycle)) $this->createByCycle(array($todoID => $todo));
            return $todoID;
        }
    }

    /**
     * Create batch todo
     *
     * @access public
     * @return void
     */
    public function batchCreate()
    {
        $todos = fixer::input('post')->cleanInt('date')->get();
        for($i = 0; $i < $this->config->todo->batchCreate; $i++)
        {
            if($todos->names[$i] != '' || isset($todos->bugs[$i + 1]) || isset($todos->tasks[$i + 1]))
            {
                $todo          = new stdclass();
                $todo->account = $this->app->user->account;
                if($this->post->date == false)
                {
                    $todo->date = '2030-01-01';
                }
                else
                {
                    $todo->date = $this->post->date;
                }
                $todo->type    = $todos->types[$i];
                $todo->pri     = $todos->pris[$i];
                $todo->name    = isset($todos->names[$i]) ? $todos->names[$i] : '';
                $todo->desc    = $todos->descs[$i];
                $todo->begin   = isset($todos->begins[$i]) ? $todos->begins[$i] : 2400;
                $todo->end     = isset($todos->ends[$i]) ? $todos->ends[$i] : 2400;
                $todo->status  = "wait";
                $todo->private = 0;
                $todo->idvalue = 0;
                if($todo->type == 'bug')   $todo->idvalue = isset($todos->bugs[$i + 1]) ? $todos->bugs[$i + 1] : 0;
                if($todo->type == 'task')  $todo->idvalue = isset($todos->tasks[$i + 1]) ? $todos->tasks[$i + 1] : 0;
                if($todo->type == 'story') $todo->idvalue = isset($todos->storys[$i + 1]) ? $todos->storys[$i + 1] : 0;

                $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
                if(dao::isError())
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }
                $todoID = $this->dao->lastInsertID();
                $this->loadModel('score')->create('todo', 'create', $todoID);
                $this->loadModel('action')->create('todo', $todoID, 'opened');
            }
            else
            {
                unset($todos->types[$i]);
                unset($todos->pris[$i]);
                unset($todos->names[$i]);
                unset($todos->descs[$i]);
                unset($todos->begins[$i]);
                unset($todos->ends[$i]);
            }
        }
    }

    /**
     * update a todo.
     *
     * @param  int    $todoID
     * @access public
     * @return void
     */
    public function update($todoID)
    {
        $oldTodo = $this->dao->findById((int)$todoID)->from(TABLE_TODO)->fetch();
        if(in_array($oldTodo->type, array('bug', 'task', 'story'))) $oldTodo->name = '';
        $todo = fixer::input('post')
            ->cleanInt('date, pri, begin, end, private')
            ->add('account', $oldTodo->account)
            ->setIF(in_array($oldTodo->type, array('bug', 'task', 'story')), 'name', '')
            ->setIF($this->post->date  == false, 'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid')
            ->get();
        if(!empty($oldTodo->cycle))
        {
            $todo->config['begin'] = $todo->date;
            if($todo->config['type'] == 'day')
            {
                unset($todo->config['week']);
                unset($todo->config['month']);
            }
            if($todo->config['type'] == 'week')
            {
                unset($todo->config['day']);
                unset($todo->config['month']);
                $todo->config['week'] = join(',', $todo->config['week']);
            }
            if($todo->config['type'] == 'month')
            {
                unset($todo->config['day']);
                unset($todo->config['week']);
                $todo->config['month'] = join(',', $todo->config['month']);
            }
            $todo->config = json_encode($todo->config);
        }

        $todo = $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')
            ->where('id')->eq($todoID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $todoID, 'todo');
            if(!empty($oldTodo->cycle)) $this->createByCycle(array($todoID => $todo));
            return common::createChanges($oldTodo, $todo);
        }
    }

    /**
     * Batch update todos.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $todos      = array();
        $allChanges = array();
        $data       = fixer::input('post')->get();
        $todoIDList = $this->post->todoIDList ? $this->post->todoIDList : array();

        if(!empty($todoIDList))
        {
            /* Initialize todos from the post data. */
            foreach($todoIDList as $todoID)
            {
                $todo = new stdclass();
                $todo->date   = $data->dates[$todoID];
                $todo->type   = $data->types[$todoID];
                $todo->pri    = $data->pris[$todoID];
                $todo->status = $data->status[$todoID];
                $todo->name   = $todo->type == 'custom' ? $data->names[$todoID] : '';
                $todo->begin  = $data->begins[$todoID];
                $todo->end    = $data->ends[$todoID];
                if($todo->type == 'task') $todo->idvalue = isset($data->tasks[$todoID]) ? $data->tasks[$todoID] : 0;
                if($todo->type == 'bug')  $todo->idvalue = isset($data->bugs[$todoID]) ? $data->bugs[$todoID] : 0;

                $todos[$todoID] = $todo;
            }

            $oldTodos = $this->dao->select('*')->from(TABLE_TODO)->where('id')->in(array_keys($todos))->fetchAll('id');
            foreach($todos as $todoID => $todo)
            {
                $oldTodo = $oldTodos[$todoID];
                if($oldTodo->type == 'bug' or $oldTodo->type == 'task') $oldTodo->name = '';
                $this->dao->update(TABLE_TODO)->data($todo)
                    ->autoCheck()
                    ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')
                    ->checkIF($todo->type == 'bug', 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'task', 'idvalue', 'notempty')
                    ->where('id')->eq($todoID)
                    ->exec();

                if($oldTodo->status != 'done' and $todo->status == 'done') $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

                if(!dao::isError())
                {
                    $allChanges[$todoID] = common::createChanges($oldTodo, $todo);
                }
                else
                {
                    die(js::error('todo#' . $todoID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Change the status of a todo.
     *
     * @param  string $todoID
     * @param  string $status
     * @access public
     * @return void
     */
    public function finish($todoID)
    {
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('done')
            ->set('finishedBy')->eq($this->app->user->account)
            ->set('finishedDate')->eq(helper::now())
            ->where('id')->eq((int)$todoID)
            ->exec();
        $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');
        return;
    }

    /**
     * Get info of a todo.
     *
     * @param  int    $todoID
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getById($todoID, $setImgSize = false)
    {
        $todo = $this->dao->findById((int)$todoID)->from(TABLE_TODO)->fetch();
        if(!$todo) return false;
        $todo = $this->loadModel('file')->replaceImgURL($todo, 'desc');
        if($setImgSize) $todo->desc = $this->file->setImgSize($todo->desc);
        if($todo->type == 'story') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_STORY)->fetch('title');
        if($todo->type == 'task')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')   $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
    }

    /**
     * Get todo list of a user.
     *
     * @param  date   $date
     * @param  string $account
     * @param  string $status   all|today|thisweek|lastweek|before, or a date.
     * @param  int    $limit
     * @access public
     * @return void
     */
    public function getList($date = 'today', $account = '', $status = 'all', $limit = 0, $pager = null, $orderBy="date, status, begin")
    {
        $this->app->loadClass('date');
        $todos = array();
        $date = strtolower($date);

        if($date == 'today')
        {
            $begin = date::today();
            $end   = $begin;
        }
        elseif($date == 'yesterday')
        {
            $begin = date::yesterday();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract(date::getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract(date::getLastWeek());
        }
        elseif($date == 'thismonth')
        {
            extract(date::getThisMonth());
        }
        elseif($date == 'lastmonth')
        {
            extract(date::getLastMonth());
        }
        elseif($date == 'thisseason')
        {
            extract(date::getThisSeason());
        }
        elseif($date == 'thisyear')
        {
            extract(date::getThisYear());
        }
        elseif($date == 'future')
        {
            $begin = '2030-01-01';
            $end   = $begin;
        }
        elseif($date == 'all')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($date == 'before')
        {
            $begin = '1970-01-01';
            $end   = date::yesterday();
        }
        elseif($date == 'cycle')
        {
            $begin = $end = '';
        }
        else
        {
            $begin = $end = $date;
        }

        if($account == '')   $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('1')
            ->andWhere('account', true)->eq($account)
            ->orWhere('assignedTo')->eq($account)
            ->orWhere('finishedBy')->eq($account)
            ->markRight(1)
            ->beginIF($begin)->andWhere('date')->ge($begin)->fi()
            ->beginIF($end)->andWhere('date')->le($end)->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->ne('done')->fi()
            ->beginIF($date == 'cycle')->andWhere('cycle')->eq('1')->fi()
            ->beginIF($date != 'cycle')->andWhere('cycle')->eq('0')->fi()
            ->orderBy($orderBy)
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->page($pager)
            ->query();

        /* Set session. */
        $sql = explode('WHERE', $this->dao->get());
        $sql = explode('ORDER', $sql[1]);
        $this->session->set('todoReportCondition', $sql[0]);

        while($todo = $stmt->fetch())
        {
            if($this->config->global->flow == 'onlyTest' and $todo->type == 'task') continue;
            if($this->config->global->flow == 'onlyTask' and $todo->type == 'bug') continue;
            if($this->config->global->flow == 'onlyStory' and $todo->type != 'custom') continue;

            if($todo->type == 'story') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_STORY)->fetch('title');
            if($todo->type == 'task')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')   $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;

            $todos[] = $todo;
        }
        return $todos;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object    $todo
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable($todo, $action)
    {
        $action = strtolower($action);

        if($action == 'finish')
        {
            if(!empty($todo->cycle)) return false;
            return $todo->status != 'done';
        }

        return true;
    }

    /**
     * CreateByCycle
     *
     * @param  int    $todoList
     * @access public
     * @return void
     */
    public function createByCycle($todoList)
    {
        $this->loadModel('action');
        $lastCycleList = $this->dao->select('*')->from(TABLE_TODO)->where('type')->eq('cycle')->andWhere('idvalue')->in(array_keys($todoList))->orderBy('date_asc')->fetchAll('idvalue');
        foreach($todoList as $todoID => $todo)
        {
            $todo->config = json_decode($todo->config);
            if(!empty($todo->config->end) and time() > strtotime($todo->config->end)) continue;
            $lastCycle = zget($lastCycleList, $todoID, '');
            $time      = time();
            for($i = 0; $i <= $todo->config->beforeDays; $i++)
            {
                $newDate = '';
                $newTodo = new stdclass();
                $newTodo->account = $todo->account;
                $newTodo->begin   = $todo->begin;
                $newTodo->end     = $todo->end;
                $newTodo->type    = 'cycle';
                $newTodo->idvalue = $todoID;
                $newTodo->pri     = $todo->pri;
                $newTodo->name    = $todo->name;
                $newTodo->desc    = $todo->desc;
                $newTodo->status  = 'wait';
                $newTodo->private = $todo->private;

                $date = date('Y-m-d', $time + $i * 24 * 3600);
                if($todo->config->type == 'day')
                {
                    if(empty($lastCycle)) $newDate = date('Y-m-d', $time + ($todo->config->day - 1) * 24 * 3600);
                    if(!empty($lastCycle->date)) $newDate = date('Y-m-d', strtotime($lastCycle->date) + $todo->config->day * 24 * 3600);
                }
                elseif($todo->config->type == 'week')
                {
                    $week = date('w', strtotime($date));
                    if(strpos(",{$todo->config->week},", ",{$week},") !== false)
                    {
                        if(empty($lastCycle)) $newDate = $date;
                        if($lastCycle->date < $date) $newDate = $date;
                    }
                }
                elseif($todo->config->type == 'month')
                {
                    $day = date('j', strtotime($date));
                    if(strpos(",{$todo->config->month},", ",{$day},") !== false)
                    {
                        if(empty($lastCycle)) $newDate = $date;
                        if($lastCycle->date < $date) $newDate = $date;
                    }
                }

                if($date == $newDate)
                {
                    $newTodo->date = $newDate;
                    $this->dao->insert(TABLE_TODO)->data($newTodo)->exec();
                    $this->action->create('todo', $this->dao->lastInsertID(), 'opened');
                    $lastCycleList[$todo->id] = $newTodo;
                }
            }
        }
    }

    /**
     * Activate todo.
     *
     * @param $todoID
     *
     * @access public
     * @return bool
     */
    public function activate($todoID)
    {
        $this->dao->update(TABLE_TODO)->set('status')->eq('wait')->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'activated', '', 'wait');
        return !dao::isError();
    }

    /**
     * Closed todo.
     *
     * @param $todoID
     *
     * @access public
     * @return bool
     */
    public function close($todoID)
    {
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('closed')
            ->set('closedBy')->eq($this->app->user->account)
            ->set('closedDate')->eq(helper::now())
            ->where('id')->eq((int)$todoID)
            ->exec();
        $this->loadModel('action')->create('todo', $todoID, 'closed', '', 'closed');
        return !dao::isError();
    }

    public function assignTo($todoID)
    {
        $todo = fixer::input('post')
            ->add('assignedBy', $this->app->user->account)
            ->add('assignedDate', helper::now())
            ->setIF(isset($_POST['future']),  'date', '2030-01-01')
            ->setIF(isset($_POST['lblDisableDate']), 'begin', '2400')
            ->setIF(isset($_POST['lblDisableDate']), 'end',   '2400')
            ->remove('future,lblDisableDate')
            ->get();

        $this->dao->update(TABLE_TODO)->data($todo)->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'assigned', '', $todo->assignedTo);
        return !dao::isError();
    }
}
