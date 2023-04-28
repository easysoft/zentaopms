<?php
declare(strict_types=1);
class bugZen extends bug
{
    /**
     * 处理请求数据。
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
            ->setIF($this->formData->data->assignedTo != '', 'assignedDate', $now)
            ->setIF($this->formData->data->story != false, 'storyVersion', $this->loadModel('story')->getVersion($this->formData->data->story))
            ->setIF(strpos($this->config->bug->create->requiredFields, 'deadline') !== false, 'deadline', $this->formData->data->deadline)
            ->setIF(strpos($this->config->bug->create->requiredFields, 'execution') !== false, 'execution', $this->formData->data->execution)
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
     * 创建bug后数据处理。
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

    /**
     * 为创建bug设置导航数据。
     * Set menu for create bug page.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  array  $output
     * @return void
     */
    protected function setMenu4Create(int $productID, string $branch, array $output): void
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type) unset($this->lang->bug->typeList[$type]);

        if($this->app->tab == 'execution')
        {
            if(isset($output['executionID'])) $this->loadModel('execution')->setMenu($output['executionID']);
            $execution = $this->dao->findById($this->session->execution)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'kanban')
            {
                $this->loadModel('kanban');
                $regionPairs = $this->kanban->getRegionPairs($execution->id, 0, 'execution');
                $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
                $lanePairs   = $this->kanban->getLanePairsByRegion($regionID, 'bug');
                $laneID      = isset($output['laneID']) ? $output['laneID'] : key($lanePairs);

                $this->view->executionType = $execution->type;
                $this->view->regionID      = $regionID;
                $this->view->laneID        = $laneID;
                $this->view->regionPairs   = $regionPairs;
                $this->view->lanePairs     = $lanePairs;
            }
        }
        else if($this->app->tab == 'project')
        {
            if(isset($output['projectID'])) $this->loadModel('project')->setMenu($output['projectID']);
        }
        else
        {
            $this->qa->setMenu($this->products, $productID, $branch);
        }

        $this->view->users = $this->user->getPairs('devfirst|noclosed|nodeleted');
        $this->app->loadLang('release');
    }

    /**
     * 如果不是弹窗，调用该方法为查看bug设置导航。
     * If it's not a iframe, call this method to set menu for view bug page.
     *
     * @param  object $bug
     * @return void
     */
    protected function setMenu4View(object $bug): void
    {
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->qa->setMenu($this->products, $bug->product, $bug->branch);

        if($this->app->tab == 'devops')
        {
            $repos = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
            $this->repo->setMenu($repos);
            $this->lang->navGroup->bug = 'devops';
        }

        if($this->app->tab == 'product')
        {
            $this->loadModel('product')->setMenu($bug->product);
            $this->lang->product->menu->plan['subModule'] .= ',bug';
        }
    }

    /**
     * 为查看bug页面设置View数据。
     * Set $this->view for view bug page.
     *
     * @param  object $bug
     * @param  string $from
     * @return void
     */
    protected function setView4View(object $bug, string $from): void
    {
        /* Get product info. */
        $bugID     = $bug->id;
        $productID = $bug->product;
        $product   = $this->loadModel('product')->getByID($productID);
        $branches  = $product->type == 'normal' ? array() : $this->loadModel('branch')->getPairs($bug->product);

        $projects = $this->loadModel('product')->getProjectPairsByProduct($productID, $bug->branch);
        $this->session->set("project", key($projects), 'project');

        $this->executeHooks($bugID);

        /* Header and positon. */
        $this->view->title      = "BUG #$bug->id $bug->title - " . $product->name;
        $this->view->position[] = html::a($this->createLink('bug', 'browse', "productID=$productID"), $product->name);
        $this->view->position[] = $this->lang->bug->view;

        /* Assign. */
        $this->view->project     = $this->loadModel('project')->getByID($bug->project);
        $this->view->productID   = $productID;
        $this->view->branches    = $branches;
        $this->view->modulePath  = $this->tree->getParents($bug->module);
        $this->view->bugModule   = empty($bug->module) ? '' : $this->tree->getById($bug->module);
        $this->view->bug         = $bug;
        $this->view->from        = $from;
        $this->view->branchName  = $product->type == 'normal' ? '' : zget($branches, $bug->branch, '');
        $this->view->users       = $this->user->getPairs('noletter');
        $this->view->actions     = $this->action->getList('bug', $bugID);
        $this->view->builds      = $this->loadModel('build')->getBuildPairs($productID, 'all');
        $this->view->preAndNext  = $this->loadModel('common')->getPreAndNextObject('bug', $bugID);
        $this->view->product     = $product;
        $this->view->linkCommits = $this->loadModel('repo')->getCommitsByObject($bugID, 'bug');

        $this->view->projects = array('' => '') + $projects;
    }
}
