<?php
declare(strict_types=1);
class bugZen extends bug
{
    /**
     * 处理请求数据
     * Processing request data.
     *
     * @param  object $formData
     * @access protected
     * @return object
     */
    protected function beforeCreate(object $formData): object
    {
        $now = helper::now();
        $bug = $formData->setDefault('openedBy', $this->app->user->account)
            ->setDefault('openedDate', $now)
            ->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
            ->setIF($this->post->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->post->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->post->story))
            ->setIF(strpos($this->config->bug->create->requiredFields, 'deadline') !== false, 'deadline', $this->post->deadline)
            ->setIF(strpos($this->config->bug->create->requiredFields, 'execution') !== false, 'execution', $this->post->execution)
            ->stripTags($this->config->bug->editor->create['id'], $this->config->allowedTags)
            ->cleanInt('product,execution,module,severity')
            ->remove('files,labels,uid,oldTaskID,contactListMenu,region,lane,ticket,deleteFiles,resultFiles')
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $formData->rawdata->uid);

        return $bug;
    }

    /**
     * 创建bug。
     * Create a bug.
     *
     * @param  object $bug
     * @access protected
     * @return array|false
     */
    protected function doCreate(object $bug): array|false
    {
        /* Check repeat bug. */
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");
        if($result and $result['stop']) return array('status' => 'exists', 'id' => $result['duplicate']);

        return $this->bug->create($bug);
    }

    /**
     * 创建bug后数据处理
     * Do thing after create a bug.
     *
     * @param  object $bug
     * @param  object $formData
     * @param  string $extra
     * @return void
     */
    protected function afterCreate(object $bug, object $formData, string $extras): void
    {
        $bugID = $bug->id;
        $extras = str_replace(array(',', ' '), array('&', ''), $extras);
        parse_str($extras, $output);
        $from = isset($output['from']) ? $output['from'] : '';

        if(isset($formData->rawdata->resultFiles))
        {
            $resultFiles = $formData->rawdata->resultFiles;
            if(isset($formData->rawdata->deleteFiles))
            {
                foreach($formData->rawdata->deleteFiles as $deletedCaseFileID) $resultFiles = trim(str_replace(",$deletedCaseFileID,", ',', ",$resultFiles,"), ',');
            }
            $files = $this->dao->select('*')->from(TABLE_FILE)->where('id')->in($resultFiles)->fetchAll('id');
            foreach($files as $file)
            {
                unset($file->id);
                $file->objectType = 'bug';
                $file->objectID   = $bugID;
                $this->dao->insert(TABLE_FILE)->data($file)->exec();
            }
        }

        $this->file->updateObjectID($formData->rawdata->uid, $bugID, 'bug');
        $this->file->saveUpload('bug', $bugID);
        empty($bug->case) ? $this->loadModel('score')->create('bug', 'create', $bugID) : $this->loadModel('score')->create('bug', 'createFormCase', $bug->case);

        if($bug->execution)
        {
            $this->loadModel('kanban');

            $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
            if(!empty($formData->rawdata->lane)) $laneID = $formData->rawdata->lane;

            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'unconfirmed');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($bug->execution, $laneID, $columnID, 'bug', $bugID);
            if(empty($laneID) or empty($columnID)) $this->kanban->updateLane($bug->execution, 'bug');
        }

        /* Callback the callable method to process the related data for object that is transfered to bug. */
        if($from && is_callable(array($this, $this->config->bug->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->bug->fromObjects[$from]['callback']), $bugID);
    }

}
