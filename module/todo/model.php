<?php
/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
            ->add('idvalue', 0)
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type == 'bug'  and $this->post->bug,  'idvalue', $this->post->bug)
            ->setIF($this->post->type == 'task' and $this->post->task, 'idvalue', $this->post->task)
            ->setIF($this->post->date == false,  'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end',   '2400')
            ->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)
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
                    $todo->date    = '2030-01-01';
                }
                else
                {
                    $todo->date    = $this->post->date;
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
                if($todo->type == 'bug')  $todo->idvalue = isset($todos->bugs[$i + 1]) ? $todos->bugs[$i + 1] : 0;
                if($todo->type == 'task') $todo->idvalue = isset($todos->tasks[$i + 1]) ? $todos->tasks[$i + 1] : 0;

                $this->dao->insert(TABLE_TODO)->data($todo)->autoCheck()->exec();
                if(dao::isError()) 
                {
                    echo js::error(dao::getError());
                    die(js::reload('parent'));
                }
                $this->loadModel('action')->create('todo', $this->dao->lastInsertID(), 'opened');
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
        $oldTodo = $this->getById($todoID);
        if($oldTodo->type != 'custom') $oldTodo->name = '';
        $todo = fixer::input('post')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type  != 'custom', 'name', '')
            ->setIF($this->post->date  == false, 'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->get();
        $this->dao->update(TABLE_TODO)->data($todo)
            ->autoCheck()
            ->checkIF($todo->type == 'custom', $this->config->todo->edit->requiredFields, 'notempty')->where('id')->eq($todoID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldTodo, $todo);
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

        /* Adjust whether the post data is complete, if not, remove the last element of $todoIDList. */
        if($this->session->showSuhosinInfo) array_pop($taskIDList);

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

            foreach($todos as $todoID => $todo)
            {
                $oldTodo = $this->getById($todoID);
                if($oldTodo->type != 'custom') $oldTodo->name = '';
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
        $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq((int)$todoID)->exec();
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
        if($setImgSize) $todo->desc = $this->loadModel('file')->setImgSize($todo->desc);
        if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
        if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
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
        else
        {
            $begin = $end = $date;
        }

        if($account == '')   $account = $this->app->user->account;

        $stmt = $this->dao->select('*')->from(TABLE_TODO)
            ->where('account')->eq($account)
            ->andWhere("date >= '$begin'")
            ->andWhere("date <= '$end'")
            ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->ne('done')->fi()
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
            if($todo->type == 'task') $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_TASK)->fetch('name');
            if($todo->type == 'bug')  $todo->name = $this->dao->findById($todo->idvalue)->from(TABLE_BUG)->fetch('title');
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

        if($action == 'finish') return $todo->status != 'done';

        return true;
    }
}



