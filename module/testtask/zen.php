<?php
class testtaskZen extends testtask
{
    /**
     * 根据不同情况设置菜单。
     * Set menu according different situations.
     *
     * @param  array     $products
     * @param  int       $productID
     * @param  int       $branch
     * @param  int       $projectID
     * @param  int       $executionID
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function setMenu(array $products, int $productID, int $branch, int $projectID, int $executionID, int $taskID)
    {
        if($this->app->tab == 'project') return $this->loadModel('project')->setMenu($projectID);
        if($this->app->tab == 'execution') return $this->loadModel('execution')->setMenu($executionID);
        return $this->testtask->setMenu($products, $productID, $branch, $taskID);
    }

    /**
     * 构建编辑的测试单数据。
     * Build task for editing.
     *
     * @param  int       $taskID
     * @param  int       $productID
     * @access protected
     * @return object
     */
    protected function buildTaskForEdit(int $taskID, int $productID): object
    {
        $task = form::data($this->config->testtask->form->edit)
            ->add('id', $taskID)
            ->add('product', $productID)
            ->stripTags($this->config->testtask->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $task = $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->edit['id'], $this->post->uid);
        return $task;
    }

    /**
     * 构建开始测试单的数据。
     * Build task for start a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForStart(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->start)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->start['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->start['id'], $task->uid);
    }

    /**
     * 构建关闭测试单的数据。
     * Build task for close a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForClose(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->close)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->close['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->close['id'], $task->uid);
    }

    /**
     * 构建激活测试单的数据。
     * Build task for activate a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForActivate(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->activate)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->activate['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->activate['id'], $task->uid);
    }

    /**
     * 构建阻塞测试单的数据。
     * Build task for block a testtask.
     *
     * @param  int       $taskID
     * @access protected
     * @return object
     */
    protected function buildTaskForBlock(int $taskID): object
    {
        $task = form::data($this->config->testtask->form->block)
            ->add('id', $taskID)
            ->stripTags($this->config->testtask->editor->block['id'], $this->config->allowedTags)
            ->get();
        return $this->loadModel('file')->processImgURL($task, $this->config->testtask->editor->block['id'], $task->uid);
    }

    /**
     * 检查编辑的测试单数据是否符合要求。
     * Check task for editing.
     *
     * @param  object    $task
     * @access protected
     * @return void
     */
    protected function checkTaskForEdit(object $task): bool
    {
        $requiredErrors = array();
        /* Check required fields of editing task . */
        foreach(explode(',', $this->config->testtask->edit->requiredFields) as $requiredField)
        {
            if(!isset($task->{$requiredField}) || strlen(trim($task->{$requiredField})) == 0) $requiredErrors[$requiredField][] = sprintf($this->lang->error->notempty, isset($this->lang->testtask->{$requiredField}) ? $this->lang->testtask->$requiredField : $requiredField);
        }
        if(!empty($requiredErrors)) dao::$errors = $requiredErrors;

        if($task->end && $task->begin > $task->end) dao::$errors['end'][] = sprintf($this->lang->error->ge, $this->lang->testtask->end, $this->lang->testtask->begin);

        return !dao::isError();
    }

    /**
     * Assign variables for editing test task.
     *
     * @param  object    $task
     * @param  int       $productID
     * @access protected
     * @return void
     */
    protected function assignForEdit(object $task, int $productID): void
    {
        /* Create testtask from testtask of test.*/
        $this->loadModel('project');
        $productID   = $productID ? $productID : key($this->products);
        $projectID   = $this->lang->navGroup->testtask == 'qa' ? 0 : $this->session->project;
        $executionID = $task->execution;
        $executions  = empty($productID) ? array() : $this->product->getExecutionPairsByProduct($productID, 0, 'id_desc', $projectID);
        if($executionID && !isset($executions[$executionID]))
        {
            $execution = $this->loadModel('execution')->getById($executionID);
            $executions[$executionID] = $execution->name;
            if(empty($execution->multiple))
            {
                $project = $this->project->getById($execution->project);
                $executions[$executionID] = "{$project->name}({$this->lang->project->disableExecution})";
            }
        }

        $this->view->title        = $this->products[$productID] . $this->lang->colon . $this->lang->testtask->edit;
        $this->view->task         = $task;
        $this->view->project      = $this->project->getByID($projectID);
        $this->view->productID    = $productID;
        $this->view->executions   = $executions;
        $this->view->builds       = empty($productID) ? array() : $this->loadModel('build')->getBuildPairs($productID, 'all', 'noempty,notrunk,withexecution', $executionID ? $executionID : $task->project, $executionID ? 'execution' : 'project', $task->build, false);
        $this->view->testreports  = $this->loadModel('testreport')->getPairs($task->product, $task->testreport);
        $this->view->users        = $this->loadModel('user')->getPairs('nodeleted|noclosed', $task->owner);
        $this->view->contactLists = $this->user->getContactLists($this->app->user->account, 'withnote');
    }

    /**
     * 根据不同情况获取不同的产品列表，大多用于1.5级导航。
     * Get products.
     *
     * @access protected
     * @return array
     */
    protected function getProducts(): array
    {
        /* Get product data. */
        $this->loadModel('product');
        $products = array();
        $objectID = 0;
        $tab      = ($this->app->tab == 'project' or $this->app->tab == 'execution') ? $this->app->tab : 'qa';
        if(!isonlybody())
        {
            if($tab == 'project')
            {
                /* 如果是在项目应用下打开的测试单，则获取当前项目对应的产品。 */
                $objectID = $this->session->project;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            elseif($tab == 'execution')
            {
                /* 如果是在执行应用下打开的测试单，则获取当前执行对应的产品。 */
                $objectID = $this->session->execution;
                $products = $this->product->getProducts($objectID, 'all', '', false);
            }
            else
            {
                /* 如果是在测试应用下打开的测试单，则获取所有产品。 */
                $products = $this->product->getPairs('', 0, '', 'all');
            }
            if(empty($products) and !helper::isAjaxRequest()) helper::end($this->locate($this->createLink('product', 'showErrorNone', "moduleName=$tab&activeMenu=testtask&objectID=$objectID")));
        }
        else
        {
            /* 如果是在弹窗下打开的测试单，则获取所有产品。 */
            $products = $this->product->getPairs('', 0, '', 'all');
        }

        return $products;
    }
}
