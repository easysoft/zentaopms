<?php
/**
 * The model file of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     todo
 * @version     $Id$
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
            ->specialChars('type,name')
            ->cleanInt('date, pri, begin, end, private')
            ->setIF($this->post->type != 'custom', 'name', '')
            ->setIF($this->post->type == 'bug'  and $this->post->bug,  'idvalue', $this->post->bug)
            ->setIF($this->post->type == 'task' and $this->post->task, 'idvalue', $this->post->task)
            ->setIF($this->post->date == false,  'date', '2030-01-01')
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
                $todo->begin   = $todos->begins[$i];
                $todo->end     = $todos->ends[$i];
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
            ->specialChars('type,name')
            ->setIF($this->post->type  != 'custom', 'name', '')
            ->setIF($this->post->date  == false, 'date', '2030-01-01')
            ->setIF($this->post->begin == false, 'begin', '2400')
            ->setIF($this->post->end   == false, 'end', '2400')
            ->setDefault('private', 0)
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
        $todoIDList = $this->post->todoIDList ? $this->post->todoIDList : array();

        /* Adjust whether the post data is complete, if not, remove the last element of $todoIDList. */
        if($this->session->showSuhosinInfo) array_pop($taskIDList);

        if(!empty($todoIDList))
        {
            /* Initialize todos from the post data. */
            foreach($todoIDList as $todoID)
            {
                $todo->date  = $this->post->dates[$todoID];
                $todo->type  = $this->post->types[$todoID];
                $todo->pri   = $this->post->pris[$todoID];
                $todo->name  = $todo->type == 'custom' ? htmlspecialchars($this->post->names[$todoID]) : '';
                $todo->begin = $this->post->begins[$todoID];
                $todo->end   = $this->post->ends[$todoID];
                if($todo->type == 'task') $todo->idvalue = isset($this->post->tasks[$todoID]) ? $this->post->tasks[$todoID] : 0;
                if($todo->type == 'bug')  $todo->idvalue = isset($this->post->bugs[$todoID]) ? $this->post->bugs[$todoID] : 0;

                $todos[$todoID] = $todo;
                unset($todo);
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
    public function mark($todoID, $status)
    {
        $status = ($status == 'done') ? 'wait' : 'done';
        $this->dao->update(TABLE_TODO)->set('status')->eq($status)->where('id')->eq((int)$todoID)->exec();
        $this->loadModel('action')->create('todo', $todoID, 'marked', '', $status);
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
        $this->loadModel('date');
        $todos = array();
        if($date == 'today') 
        {
            $begin = $this->date->today();
            $end   = $begin;
        }
        elseif($date == 'yesterday') 
        {
            $begin = $this->date->yesterday();
            $end   = $begin;
        }
        elseif($date == 'thisweek')
        {
            extract($this->date->getThisWeek());
        }
        elseif($date == 'lastweek')
        {
            extract($this->date->getLastWeek());
        }
        elseif($date == 'thismonth')
        {
            extract($this->date->getThisMonth());
        }
        elseif($date == 'lastmonth')
        {
            extract($this->date->getLastMonth());
        }
        elseif($date == 'thisseason')
        {
            extract($this->date->getThisSeason());
        }
        elseif($date == 'thisyear')
        {
            extract($this->date->getThisYear());
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
            $end   = $this->date->yesterday();
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
            $todo->begin = $this->date->formatTime($todo->begin);
            $todo->end   = $this->date->formatTime($todo->end);

            /* If is private, change the title to private. */
            if($todo->private and $this->app->user->account != $todo->account) $todo->name = $this->lang->todo->thisIsPrivate;
            $todos[] = $todo;
        }
        return $todos;
    }
}



