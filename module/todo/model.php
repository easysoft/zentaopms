<?php
/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id: model.php 5035 2013-07-06 05:21:58Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class todoModel extends model
{
    /**
     * Create a todo.
     *
     * @param  date   $date
     * @param  string $account
     * @access public
     * @return bool|int
     */
    public function create($date, $account)
    {
        $objectType = $this->post->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);

        $idvalue = 0;
        if($hasObject && $objectType) $idvalue = $this->post->uid ? $this->post->$objectType : $this->post->idvalue;

        $todo = fixer::input('post')
            ->add('account', $this->app->user->account)
            ->setDefault('idvalue', 0)
            ->setDefault('vision', $this->config->vision)
            ->setDefault('assignedTo', $this->app->user->account)
            ->setDefault('assignedBy', $this->app->user->account)
            ->setDefault('assignedDate', helper::now())
            ->cleanInt('pri, begin, end, private')
            ->setIF($hasObject && $objectType,  'idvalue', $idvalue)
            ->setIF($this->post->date == false,  'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->begin == false or $this->post->end == false, 'end', '2400')
            ->setIF($this->post->status == 'done', 'finishedBy', $this->app->user->account)
            ->setIF($this->post->status == 'done', 'finishedDate', helper::now())
            ->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        if(!isset($todo->pri) and in_array($this->post->type, $this->config->todo->moduleList) and $this->post->type !== 'review' and $this->post->type !== 'feedback')
        {
            $todo->pri = $this->dao->select('pri')->from($this->config->objectTables[$this->post->type])->where('id')->eq($this->post->idvalue)->fetch('pri');

            if($todo->pri == 'high')   $todo->pri = 1;
            if($todo->pri == 'middle') $todo->pri = 2;
            if($todo->pri == 'low')    $todo->pri = 3;
        }

        if($todo->type != 'custom' and $todo->idvalue)
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($this->post->$type);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin)
        {
            dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(empty($todo->cycle)) unset($todo->config);
        if(!empty($todo->cycle))
        {
            $todo->date = date('Y-m-d');

            $todo->config['begin'] = $todo->date;
            if($todo->config['type'] == 'day')
            {
                unset($todo->config['week']);
                unset($todo->config['month']);
                if(empty($todo->config['specifiedDate']))
                {
                    if(!$todo->config['day'])
                    {
                        dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->todo->cycleDaysLabel);
                        return false;
                    }
                    if(!validater::checkInt($todo->config['day']))
                    {
                        dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->cycleDaysLabel);
                        return false;
                    }
                }
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

            if($todo->config['beforeDays'] and !validater::checkInt($todo->config['beforeDays']))
            {
                dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->beforeDaysLabel);
                return false;
            }
            $todo->config['beforeDays'] = (int)$todo->config['beforeDays'];
            $todo->config = json_encode($todo->config);
            $todo->type   = 'cycle';
        }

        $todo = $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(!$hasObject, $this->config->todo->create->requiredFields, 'notempty')
            ->checkIF($hasObject && $todo->idvalue == 0, 'idvalue', 'notempty')
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
     * @return array
     */
    public function batchCreate()
    {
        $todos = fixer::input('post')->get();

        $validTodos = array();
        $now        = helper::now();
        $assignedTo = $this->app->user->account;
        for($i = 0; $i < $this->config->todo->batchCreate; $i++)
        {
            $isExist    = false;
            $assignedTo = $todos->assignedTos[$i] == 'ditto' ? $assignedTo : $todos->assignedTos[$i];
            foreach($this->config->todo->objectList as $objects)
            {
                if(isset($todos->{$objects}[$i + 1]))
                {
                    $isExist = true;
                    break;
                }
            }

            if($todos->names[$i] != '' || $isExist)
            {
                $todo          = new stdclass();
                $todo->account = $this->app->user->account;
                if($this->post->switchDate == 'on' or $this->post->date == false)
                {
                    $todo->date = '2030-01-01';
                }
                else
                {
                    $todo->date = $this->post->date;
                }

                $todo->type         = $todos->types[$i];
                $todo->pri          = $todos->pris[$i];
                $todo->name         = isset($todos->names[$i]) ? $todos->names[$i] : '';
                $todo->desc         = $todos->descs[$i];
                $todo->begin        = isset($todos->begins[$i]) ? $todos->begins[$i] : 2400;
                $todo->end          = isset($todos->ends[$i]) ? $todos->ends[$i] : 2400;
                $todo->status       = "wait";
                $todo->private      = 0;
                $todo->idvalue      = 0;
                $todo->assignedTo   = $assignedTo;
                $todo->assignedBy   = $this->app->user->account;
                $todo->assignedDate = $now;
                $todo->vision       = $this->config->vision;

                if(in_array($todo->type, $this->config->todo->moduleList)) $todo->idvalue = isset($todos->{$this->config->todo->objectList[$todo->type]}[$i + 1]) ? $todos->{$this->config->todo->objectList[$todo->type]}[$i + 1] : 0;

                if($todo->type != 'custom' and $todo->idvalue)
                {
                    $type   = $todo->type;
                    $object = $this->loadModel($type)->getByID($todo->idvalue);
                    if(isset($object->name))  $todo->name = $object->name;
                    if(isset($object->title)) $todo->name = $object->title;
                }

                if($todo->end < $todo->begin) return print(js::alert(sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin)));

                $validTodos[] = $todo;
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

        $todoIDList = array();
        foreach($validTodos as $todo)
        {
            $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
            if(dao::isError())
            {
                echo js::error(dao::getError());
                return print(js::reload('parent'));
            }
            $todoID       = $this->dao->lastInsertID();
            $todoIDList[] = $todoID;
            $this->loadModel('score')->create('todo', 'create', $todoID);
            $this->loadModel('action')->create('todo', $todoID, 'opened');
        }

        return $todoIDList;
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

        $idvalue    = 0;
        $objectType = $this->post->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);
        if($hasObject && $objectType) $idvalue = $this->post->uid ? $this->post->$objectType : $this->post->idvalue;
        $todo = fixer::input('post')
            ->cleanInt('pri, begin, end, private')
            ->add('account', $oldTodo->account)
            ->setIF(in_array($this->post->type, array('bug', 'task', 'story')), 'name', '')
            ->setIF($hasObject && $objectType,  'idvalue', $idvalue)
            ->setIF($this->post->date  == false, 'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setIF($this->post->type  == false, 'type', $oldTodo->type)
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        if(in_array($todo->type, $this->config->todo->moduleList))
        {
            $type   = $todo->type;
            $object = $this->loadModel($type)->getByID($objectType);
            if(isset($object->name))  $todo->name = $object->name;
            if(isset($object->title)) $todo->name = $object->title;
        }

        if($todo->end < $todo->begin)
        {
            dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(!empty($oldTodo->cycle))
        {
            $todo->date = date('Y-m-d');

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
            $todo->config['beforeDays'] = (int)$todo->config['beforeDays'];
            $todo->config = json_encode($todo->config);
        }

        $todo = $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF(in_array($todo->type, array('custom', 'feedback')), $this->config->todo->edit->requiredFields, 'notempty')
            ->checkIF($hasObject && $todo->idvalue == 0, 'idvalue', 'notempty')
            ->where('id')->eq($todoID)
            ->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $todoID, 'todo');
            if(!empty($oldTodo->cycle)) $this->createByCycle(array($todoID => $todo));
            if($this->config->edition != 'open' && $todo->type == 'feedback' && $todo->idvalue) $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, $todo->status);
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
            $oldTodos = $this->dao->select('*')->from(TABLE_TODO)->where('id')->in($todoIDList)->fetchAll('id');
            foreach($todoIDList as $todoID)
            {
                $oldTodo = $oldTodos[$todoID];

                $todo = new stdclass();
                $todo->date       = $data->dates[$todoID];
                $todo->type       = $data->types[$todoID];
                $todo->pri        = $data->pris[$todoID];
                $todo->status     = $data->status[$todoID];
                $todo->name       = !in_array($todo->type, $this->config->todo->moduleList) ? $data->names[$todoID] : '';
                $todo->begin      = isset($data->begins[$todoID]) ? $data->begins[$todoID] : 2400;
                $todo->end        = isset($data->ends[$todoID]) ? $data->ends[$todoID] : 2400;
                $todo->assignedTo = isset($data->assignedTos[$todoID]) ? $data->assignedTos[$todoID] : $oldTodo->assignedTo;

                if(in_array($todo->type, $this->config->todo->moduleList))
                {
                    $todo->idvalue = isset($data->{$this->config->todo->objectList[$todo->type]}[$todoID]) ? $data->{$this->config->todo->objectList[$todo->type]}[$todoID] : 0;
                }
                if($todo->end < $todo->begin) return print(js::alert(sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin)));

                $todos[$todoID] = $todo;
            }

            foreach($todos as $todoID => $todo)
            {
                $oldTodo = $oldTodos[$todoID];
                if(in_array($todo->type, $this->config->todo->moduleList)) $oldTodo->name = '';
                $this->dao->update(TABLE_TODO)->data($todo)
                    ->autoCheck()
                    ->checkIF(!in_array($todo->type, $this->config->todo->moduleList), $this->config->todo->edit->requiredFields, 'notempty')
                    ->checkIF($todo->type == 'bug'   and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'task'  and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'story' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'issue' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'risk' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'opportunity' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'review' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'testtask' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->checkIF($todo->type == 'feedback' and $todo->idvalue == 0, 'idvalue', 'notempty')
                    ->where('id')->eq($todoID)
                    ->exec();

                if($oldTodo->status != 'done' and $todo->status == 'done') $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

                if(!dao::isError())
                {
                    if($this->config->edition != 'open' && $todo->type == 'feedback' && $todo->idvalue && !isset($feedbacks[$todo->idvalue]))
                    {
                        $feedbacks[$todo->idvalue] = $todo->idvalue;
                        $this->loadModel('feedback')->updateStatus('todo', $todo->idvalue, $todo->status);
                    }

                    $allChanges[$todoID] = common::createChanges($oldTodo, $todo);
                }
                else
                {
                    return print(js::error('todo#' . $todoID . dao::getError(true)));
                }
            }
        }

        return $allChanges;
    }

    /**
     * Start one todo.
     *
     * @param  string $todoID
     * @access public
     * @return void
     */
    public function start($todoID)
    {
        $this->dao->update(TABLE_TODO)->set('status')->eq('doing')->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'started');
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
        if(!dao::isError())
        {
            $this->loadModel('action')->create('todo', $todoID, 'finished', '', 'done');

            if($this->config->edition != 'open')
            {
                $feedbackID = $this->dao->select('idvalue')->from(TABLE_TODO)->where('id')->eq($todoID)->andWhere('type')->eq('feedback')->fetch('idvalue');
                if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'done');
            }
            return true;
        }
        return false;
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
        if($todo->type == 'story')    $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_STORY)->fetch('title');
        if($todo->type == 'task')     $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')      $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
        if($todo->type == 'issue' and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_ISSUE)->fetch('title');
        if($todo->type == 'risk' and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_RISK)->fetch('name');
        if($todo->type == 'opportunity' and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_OPPORTUNITY)->fetch('name');
        if($todo->type == 'review' and ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_REVIEW)->fetch('title');
        if($todo->type == 'testtask') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TESTTASK)->fetch('name');
        if($todo->type == 'feedback') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_FEEDBACK)->fetch('title');
        $todo->date = str_replace('-', '', $todo->date);
        return $todo;
    }

    /**
     * Get todo list of a user.
     *
     * @param  string $type
     * @param  string $account
     * @param  string $status   all|today|thisweek|lastweek|before, or a date.
     * @param  int    $limit
     * @param  object $pager
     * @param  string $orderBy
     * @access public
     * @return void
     */
    public function getList($type = 'today', $account = '', $status = 'all', $limit = 0, $pager = null, $orderBy="date, status, begin")
    {
        $this->app->loadClass('date');
        $todos = array();
        $type = strtolower($type);

        if($type == 'all' or $type == 'assignedtoother')
        {
            $begin = '1970-01-01';
            $end   = '2109-01-01';
        }
        elseif($type == 'today')
        {
            $begin = date::today();
            $end   = $begin;
        }
        elseif($type == 'yesterday')
        {
            $begin = date::yesterday();
            $end   = $begin;
        }
        elseif($type == 'thisweek')
        {
            extract(date::getThisWeek());
        }
        elseif($type == 'lastweek')
        {
            extract(date::getLastWeek());
        }
        elseif($type == 'thismonth')
        {
            extract(date::getThisMonth());
        }
        elseif($type == 'lastmonth')
        {
            extract(date::getLastMonth());
        }
        elseif($type == 'thisseason')
        {
            extract(date::getThisSeason());
        }
        elseif($type == 'thisyear')
        {
            extract(date::getThisYear());
        }
        elseif($type == 'future')
        {
            $begin = '2030-01-01';
            $end   = $begin;
        }
        elseif($type == 'before')
        {
            $begin = '1970-01-01';
            $end   = date::today();
        }
        elseif($type == 'cycle')
        {
            $begin = $end = '';
        }
        else
        {
            $begin = $end = $type;
        }

        if(empty($account)) $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($type == 'assignedtoother')->andWhere('account', true)->eq($account)->fi()
            ->beginIF($type != 'assignedtoother')->andWhere('assignedTo', true)->eq($account)->fi()
            ->orWhere('finishedBy')->eq($account)
            ->orWhere('closedBy')->eq($account)
            ->markRight(1)
            ->beginIF($begin)->andWhere('date')->ge($begin)->fi()
            ->beginIF($end)->andWhere('date')->le($end)->fi()
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notin('done,closed')->fi()
            ->beginIF($type == 'cycle')->andWhere('cycle')->eq('1')->fi()
            ->beginIF($type != 'cycle')->andWhere('cycle')->eq('0')->fi()
            ->beginIF($type == 'assignedtoother')->andWhere('assignedTo')->notin(array($account, ''))->fi()
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
            if($todo->type == 'story')    $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_STORY)->fetch('title');
            if($todo->type == 'task')     $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')      $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
            if($todo->type == 'testtask') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TESTTASK)->fetch('name');
            if($todo->type == 'issue' && ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_ISSUE)->fetch('title');
            if($todo->type == 'risk' && ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_RISK)->fetch('name');
            if($todo->type == 'opportunity' && ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_OPPORTUNITY)->fetch('name');
            if($todo->type == 'review' && ($this->config->edition == 'max' or $this->config->edition == 'ipd')) $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_REVIEW)->fetch('title');
            if($todo->type == 'feedback' and $this->config->edition != 'open') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_FEEDBACK)->fetch('title');
            $todo->begin = date::formatTime($todo->begin);
            $todo->end   = date::formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;

            $todos[] = $todo;
        }

        return $todos;
    }

    /**
     * Get by id list.
     *
     * @param  array $todoIDList
     * @access public
     * @return object
     */
    public function getByList($todoIDList = 0)
    {
        return $this->dao->select('*')->from(TABLE_TODO)
            ->beginIF($todoIDList)->where('id')->in($todoIDList)->fi()
            ->fetchAll('id');
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

        if($action == 'start')
        {
            if(!empty($todo->cycle)) return false;
            return $todo->status == 'wait';
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
        $today = helper::today();
        $now   = helper::now();
        $lastCycleList = $this->dao->select('*')->from(TABLE_TODO)->where('type')->eq('cycle')->andWhere('deleted')->eq('0')->andWhere('idvalue')->in(array_keys($todoList))->orderBy('date_asc')->fetchAll('idvalue');
        $activedUsers  = $this->dao->select('account')->from(TABLE_USER)->where('deleted')->eq(0)->fetchPairs('account', 'account');
        foreach($todoList as $todoID => $todo)
        {
            if(!isset($activedUsers[$todo->account])) continue;

            $todo->config = json_decode($todo->config);
            $begin      = $todo->config->begin;
            $end        = $todo->config->end;
            $beforeDays = (int)$todo->config->beforeDays;
            if(!empty($beforeDays) && $beforeDays > 0) $begin = date('Y-m-d', strtotime("$begin -{$beforeDays} days"));
            if($today < $begin or (!empty($end) && $today > $end)) continue;

            $newTodo = new stdclass();
            $newTodo->account    = $todo->account;
            $newTodo->begin      = $todo->begin;
            $newTodo->end        = $todo->end;
            $newTodo->type       = 'cycle';
            $newTodo->idvalue    = $todoID;
            $newTodo->pri        = $todo->pri;
            $newTodo->name       = $todo->name;
            $newTodo->desc       = $todo->desc;
            $newTodo->status     = 'wait';
            $newTodo->private    = isset($todo->private) ? $todo->private : '';
            $newTodo->assignedTo = isset($todo->assignedTo) ? $todo->assignedTo : '';
            $newTodo->assignedBy = isset($todo->assignedBy) ? $todo->assignedBy : '';
            if(isset($todo->assignedTo) and $todo->assignedTo) $newTodo->assignedDate = $now;

            $start  = strtotime($begin);
            $finish = strtotime("$today +{$beforeDays} days");
            foreach(range($start, $finish, 86400) as $today)
            {
                $today     = date('Y-m-d', $today);
                $lastCycle = zget($lastCycleList, $todoID, '');
                $date      = '';

                if($todo->config->type == 'day')
                {
                    if(isset($todo->config->day))
                    {
                        $day = (int)$todo->config->day;
                        if($day <= 0) continue;

                        /* If no data, judge the interval from the begin time. */
                        if(empty($lastCycle))
                        {
                            $todayTime = new DateTime($today);
                            $beginTime = new DateTime($todo->config->begin);
                            $interval  = $todayTime->diff($beginTime)->days;

                            if($interval != $day) continue;

                            $date = $today;
                        }

                        /* If have data, judge the interval from the last cycle time. */
                        if(!empty($lastCycle->date))
                        {
                            $todayTime     = new DateTime($today);
                            $lastCycleTime = new DateTime($lastCycle->date);
                            $interval      = $todayTime->diff($lastCycleTime)->days;

                            if($interval != $day) continue;

                            $date = date('Y-m-d', strtotime("{$lastCycle->date} +{$day} days"));
                        }
                    }
                    if(isset($todo->config->specifiedDate))
                    {
                        $date          = $today;
                        $specifiedDate = $todo->config->specify->month + 1 . '-' . $todo->config->specify->day;

                        /* If not set cycle every year and have data, continue. */
                        if(!empty($lastCycle) and !isset($todo->config->cycleYear)) continue;

                        /* If set specified date, only judge month and day. */
                        if(date('m-d', strtotime($date)) != $specifiedDate) continue;
                    }
                }
                elseif($todo->config->type == 'week')
                {
                    $week = date('w', strtotime($today));
                    if(strpos(",{$todo->config->week},", ",{$week},") !== false)
                    {
                        if(empty($lastCycle))         $date = $today;
                        if($lastCycle and $lastCycle->date < $today) $date = $today;
                    }
                }
                elseif($todo->config->type == 'month')
                {
                    $day = date('j', strtotime($today));
                    if(strpos(",{$todo->config->month},", ",{$day},") !== false)
                    {
                        if(empty($lastCycle))         $date = $today;
                        if($lastCycle and $lastCycle->date < $today) $date = $today;
                    }
                }

                if(!$date)                                     continue;
                if($date < $todo->config->begin)               continue;
                if($date < date('Y-m-d'))                      continue;
                if($date > date('Y-m-d', $finish))             continue;
                if(!empty($end) && $date > $end)               continue;
                if($lastCycle and ($date == $lastCycle->date)) continue;

                $newTodo->date = $date;

                $this->dao->insert(TABLE_TODO)->data($newTodo)->exec();
                $this->action->create('todo', $this->dao->lastInsertID(), 'opened', '', '', $newTodo->account);
                $lastCycleList[$todoID] = $newTodo;
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
        $now = helper::now();
        $this->dao->update(TABLE_TODO)
            ->set('status')->eq('closed')
            ->set('closedBy')->eq($this->app->user->account)
            ->set('closedDate')->eq($now)
            ->set('assignedTo')->eq('closed')
            ->set('assignedDate')->eq($now)
            ->where('id')->eq((int)$todoID)
            ->exec();

        if(!dao::isError())
        {
            $this->loadModel('action')->create('todo', $todoID, 'closed', '', 'closed');

            if($this->config->edition != 'open')
            {
                $feedbackID = $this->dao->select('idvalue')->from(TABLE_TODO)->where('id')->eq($todoID)->andWhere('type')->eq('feedback')->fetch('idvalue');
                if($feedbackID) $this->loadModel('feedback')->updateStatus('todo', $feedbackID, 'closed');
            }
            return true;
        }
        return false;
    }

    /**
     * Assign todo.
     *
     * @param  int    $todoID
     * @access public
     * @return bool
     */
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

    /**
     * Get todo count.
     *
     * @param  string $account
     * @access public
     * @return int
     */
    public function getCount($account = '')
    {
        if(empty($account)) $account = $this->app->user->account;
        return $this->dao->select('count(*) as count')->from(TABLE_TODO)
            ->where('cycle')->eq('0')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('account', true)->eq($account)
            ->orWhere('assignedTo')->eq($account)
            ->orWhere('finishedBy')->eq($account)
            ->markRight(1)
            ->fetch('count');
    }

    /**
     * Gets the project ID of the to-do object.
     *
     * @param  array $todoList
     * @access public
     * @return array
     */
    public function getTodoProjects($todoList = array())
    {
        $projectIdList = array();
        foreach($todoList as $type => $todos)
        {
            $todoIdList = array_keys($todos);
            if(empty($todoIdList))
            {
                $projectIdList[$type] = array();
                continue;
            }

            $todoIdList = array_unique($todoIdList);
            if($type == 'task')     $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_TASK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'bug')      $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_BUG)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'issue')    $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_ISSUE)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'risk')     $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_RISK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'opportunity') $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_OPPORTUNITY)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'review')   $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_REVIEW)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
            if($type == 'testtask') $projectIdList[$type] = $this->dao->select('id,project')->from(TABLE_TESTTASK)->where('id')->in($todoIdList)->fetchPairs('id', 'project');
        }

        return $projectIdList;
    }

    /**
     * Delete a todo.
     *
     * @param string $table
     * @param int    $todoID
     * @return bool
     */
    public function delete($table, $todoID)
    {
        $todo = $this->dao->select('account, assignedTo')->from($table)->where('id')->eq($todoID)->fetch();
        if(!$this->app->user->admin && $todo && $todo->account != $this->app->user->account && $todo->assignedTo != $this->app->user->account) return false;

        return parent::delete($table, $todoID);
    }
}
