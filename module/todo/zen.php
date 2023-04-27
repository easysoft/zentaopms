<?php
declare(strict_types=1);

class todoZen extends todo
{
    /**
     * 处理请求数据
     * Processing request data.
     *
     * @param  object $formData
     * @return object|false
     */
    protected function beforeCreate(object $formData): object|bool
    {
        $formData = $formData->remove(implode(',', $this->config->todo->moduleList) . ',uid')->stripTags($this->config->todo->editor->create['id'], $this->config->allowedTags)->get();

        $idvalue   = 0;
        $hasObject = in_array($formData->type, $this->config->todo->moduleList);
        if($hasObject && $formData->type) $idvalue = $formData->uid ? $formData->type : $formData->idvalue;

        $formData->account    = $this->app->user->account;
        $formData->assignedTo = zget($formData, 'assignedTo', $this->app->user->account);
        $formData->assignedBy = zget($formData, 'assignedBy', $this->app->user->account);
        if($hasObject && $formData->type) $formData->idvalue      = $idvalue;
        if($formData->status == 'done')   $formData->finishedBy   = $this->app->user->account;
        if($formData->status == 'done')   $formData->finishedDate = helper::now();

        if(!isset($formData->pri) and in_array($formData->type, $this->config->todo->moduleList) and $formData->type !== 'review' and $formData->type !== 'feedback')
        {
            // TODO
            $formData->pri = $this->dao->select('pri')->from($this->config->objectTables[$formData->type])->where('id')->eq($formData->idvalue)->fetch('pri');

            if($formData->pri == 'high')   $formData->pri = 1;
            if($formData->pri == 'middle') $formData->pri = 2;
            if($formData->pri == 'low')    $formData->pri = 3;
        }

        if($formData->type != 'custom' and $formData->idvalue)
        {
            $type   = $formData->type;
            $object = $this->loadModel($type)->getByID($formData->$type);
            if(isset($object->name))  $formData->name = $object->name;
            if(isset($object->title)) $formData->name = $object->title;
        }

        if($formData->end < $formData->begin)
        {
            dao::$errors[] = sprintf($this->lang->error->gt, $this->lang->todo->end, $this->lang->todo->begin);
            return false;
        }

        if(!empty($formData->cycle)) $formData = $this->setCycle($formData);
        else unset($formData->config);

        $formData = $this->loadModel('file')->processImgURL($formData, $this->config->todo->editor->create['id'], $this->post->uid);

        return $formData;
    }

    /**
     * 创建待办
     * Create a todo.
     *
     * @param  object $todo
     * @return int|false
     */
    protected function doCreate(object $todo): int|bool
    {
        return $this->todo->create($todo);
    }

    /**
     * 创建完成待办后数据处理
     * Create a todo after data processing
     *
     * @param  object $todo
     * @return object
     */
    protected function afterCreate(object $todo): object
    {
        $this->file->updateObjectID($this->post->uid, $todo->id, 'todo');

        $this->loadModel('score')->create('todo', 'create', $todo->id);

        // TODO
        if(!empty($todo->cycle)) $this->todo->createByCycle(array($todo->id => $todo));

        $this->loadModel('action')->create('todo', $todo->id, 'opened');

        $date = str_replace('-', '', $todo->date);
        if($date == '')          $date = 'future';
        if($date == date('Ymd')) $date = 'today';

        return $todo;
    }

    /**
     * 处理编辑待办的请求数据
     * Processing edit request data.
     *
     * @param  int    $todoID
     * @param  object $formData
     * @return object|false
     */
    protected function beforeEdit(int $todoID, object $formData)
    {
        $oldTodo = $this->dao->findById($todoID)->from(TABLE_TODO)->fetch();

        $idvalue    = 0;
        $rowData    = $formData->rawdata;
        $objectType = $rowData->type;
        $hasObject  = in_array($objectType, $this->config->todo->moduleList);
        if($hasObject && $objectType) $idvalue = $rowData->uid ? $rowData->$objectType : $rowData->idvalue;

        $todo = $formData->add('account', $oldTodo->account)
            ->cleanInt('pri, begin, end, private')
            ->setIF(in_array($rowData->type, array('bug', 'task', 'story')), 'name', '')
            ->setIF($hasObject && $objectType,  'idvalue', $idvalue)
            ->setIF($rowData->date  == false, 'date', '2030-01-01')
            ->setIF($rowData->begin == false, 'begin', '2400')
            ->setIF($rowData->end   == false, 'end', '2400')
            ->setIF($rowData->type  == false, 'type', $oldTodo->type)
            ->setDefault('private', 0)
            ->stripTags($this->config->todo->editor->edit['id'], $this->config->allowedTags)
            ->remove(implode(',', $this->config->todo->moduleList) . ',uid')
            ->get();

        $todo = (object) array_merge((array) $todo, (array) $rowData);

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

        if(!empty($oldTodo->cycle)) $this->handleCycleConfig($todo);

        $todo = $this->loadModel('file')->processImgURL($todo, $this->config->todo->editor->edit['id'], $rowData->uid);

        return $todo;
    }

    /**
     * 编辑完成待办后数据处理
     * Handle data after edit todo.
     *
     * @param  object $todo
     * @return void
     */
    protected function afterEdit(int $todoID, array $changes): void
    {
        if(empty($changes)) return;

        $actionID = $this->loadModel('action')->create('todo', $todoID, 'edited');
        $this->action->logHistory($actionID, $changes);
    }

    /**
     * 处理循环待办的配置文件
     * Handle cycle config.
     *
     * @param  object $todo
     * @return void
     */
    private function handleCycleConfig(object &$todo): void
    {
        $todo->date            = date('Y-m-d');
        $todo->config['begin'] = $todo->date;

        if($todo->config['type'] == 'day') unset($todo->config['week'], $todo->config['month']);
        if($todo->config['type'] == 'week')
        {
            unset($todo->config['day'], $todo->config['month']);
            $todo->config['week'] = join(',', $todo->config['week']);
        }
        if($todo->config['type'] == 'month')
        {
            unset($todo->config['day'], $todo->config['week']);
            $todo->config['month'] = join(',', $todo->config['month']);
        }

        $todo->config['beforeDays'] = (int)$todo->config['beforeDays'];
        $todo->config = json_encode($todo->config);
    }

    /**
     * 设置周期待办
     * Set cycle todo.
     *
     * @param  object $formData
     * @return object
     */
    private function setCycle(object $formData): object
    {
        $formData->date = date('Y-m-d');

        $formData->config['begin'] = $formData->date;
        if($formData->config['type'] == 'day')
        {
            unset($formData->config['week'], $formData->config['month']);
            if(!$formData->config['day'])
            {
                dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->todo->cycleDaysLabel);
                return false;
            }
            if(!validater::checkInt($formData->config['day']))
            {
                dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->cycleDaysLabel);
                return false;
            }
        }
        if($formData->config['type'] == 'week')
        {
            unset($formData->config['day'], $formData->config['month']);
            $formData->config['week'] = join(',', $formData->config['week']);
        }
        if($formData->config['type'] == 'month')
        {
            unset($formData->config['day'], $formData->config['week']);
            $formData->config['month'] = join(',', $formData->config['month']);
        }

        if($formData->config['beforeDays'] and !validater::checkInt($formData->config['beforeDays']))
        {
            dao::$errors[] = sprintf($this->lang->error->int[0], $this->lang->todo->beforeDaysLabel);
            return false;
        }
        $formData->config['beforeDays'] = (int)$formData->config['beforeDays'];
        $formData->config = json_encode($formData->config);
        $formData->type   = 'cycle';

        return $formData;
    }
}
