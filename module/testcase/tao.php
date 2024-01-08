<?php
declare(strict_types=1);
class testcaseTao extends testcaseModel
{
    /**
     * 获取用例的基础数据。
     * Fetch base info of a case.
     *
     * @param  int       $caseID
     * @access protected
     * @return object|false
     */
    protected function fetchBaseInfo(int $caseID): object|false
    {
        return $this->dao->select('*')->from(TABLE_CASE)->where('id')->eq($caseID)->fetch();
    }

    /**
     * 查询场景名称。
     * Fetch scene name.
     *
     * @param  int       $sceneID
     * @access protected
     * @return void
     */
    protected function fetchSceneName(int $sceneID): string|null
    {
        return $this->dao->findByID($sceneID)->from(TABLE_SCENE)->fetch('title');
    }

    /**
     * 通过用例的 id 列表查询步骤。
     * Fetch step by id list.
     *
     * @param  array     $caseIdList
     * @access protected
     * @return array
     */
    protected function fetchStepsByList(array $caseIdList): array
    {
        return $this->dao->select('t2.*')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_CASESTEP)->alias('t2')->on('t1.id = t2.case')
            ->where('t1.id')->in($caseIdList)
            ->andWhere('t1.version=t2.version')
            ->orderBy('t2.id')
            ->fetchGroup('case');
    }

    /**
     * 获取某个项目下某个模块的用例列表。
     * Get project cases of a module.
     *
     * @param  int        $productID
     * @param  int|string $branch
     * @param  int        $moduleIdList
     * @param  string     $browseType
     * @param  string     $auto   no|unit
     * @param  string     $caseType
     * @param  string     $orderBy
     * @param  object     $pager
     * @access protected
     * @return array
     */
    protected function getModuleProjectCases(int $productID, int|string $branch = 0, int|array $moduleIdList = 0, string $browseType = '', string $auto = 'no', string $caseType = '', string $orderBy = 'id_desc', object $pager = null): array
    {
        $executions = $this->loadModel('execution')->getIdList((int)$this->session->project);
        array_push($executions, $this->session->project);

        return $this->dao->select('distinct t1.*, t2.*, t4.title AS storyTitle')->from(TABLE_PROJECTCASE)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t3')->on('t3.story = t2.story')
            ->leftJoin(TABLE_STORY)->alias('t4')->on('t3.story = t4.id')
            ->where('t1.project')->in($executions)
            ->beginIF(!empty($productID))->andWhere('t2.product')->eq($productID)->fi()
            ->beginIF(!empty($productID) && $branch !== 'all')->andWhere('t2.branch')->eq($branch)->fi()
            ->beginIF($moduleIdList)->andWhere('t2.module')->in($moduleIdList)->fi()
            ->beginIF($browseType == 'all')->andWhere('t2.scene')->eq(0)->fi()
            ->beginIF($browseType == 'wait')->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t2.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t2.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t2.type')->eq($caseType)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager, 't1.`case`')
            ->fetchAll('id');
    }

    /**
     * 获取待确认的用例列表。
     * Get need confirm case list.
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @param  array      $modules
     * @param  string     $auto
     * @param  string     $caseType
     * @param  string     $sort
     * @param  object     $pager
     * @access protected
     * @return array
     */
    protected function getNeedConfirmList(int $productID, string|int $branch, array $modules, string $auto, string $caseType, string $sort, object $pager = null): array
    {
        return $this->dao->select('distinct t1.*, t2.title AS storyTitle')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_PROJECTCASE)->alias('t3')->on('t1.id = t3.case')
            ->where('t2.status')->eq('active')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t2.version > t1.storyVersion')
            ->beginIF(!empty($productID))->andWhere('t1.product')->eq($productID)->fi()
            ->beginIF(!empty($productID) && $branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($this->app->tab == 'project')->andWhere('t3.project')->eq($this->session->project)->fi()
            ->beginIF($modules)->andWhere('t1.module')->in($modules)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->beginIF($caseType)->andWhere('t1.type')->eq($caseType)->fi()
            ->orderBy($sort)
            ->page($pager, 't1.id')
            ->fetchAll();
    }

    /**
     * 根据套件获取用例。
     * Get cases by suite.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  int         $suiteID
     * @param  array       $moduleIdList
     * @param  string      $auto    no|unit
     * @param  string      $orderBy
     * @param  object      $pager
     * @access public
     * @return array
     */
    public function getBySuite(int $productID, int|string $branch = 0, int $suiteID = 0, array|int $moduleIdList = 0, string $auto = 'no', string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.title AS storyTitle, t3.version AS version')->from(TABLE_CASE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_SUITECASE)->alias('t3')->on('t1.id = t3.case')
            ->where('t1.product')->eq($productID)
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
            ->beginIF($this->app->tab == 'project')->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t3.suite')->eq($suiteID)
            ->beginIF($moduleIdList)->andWhere('t1.module')->in($moduleIdList)->fi()
            ->beginIF($auto == 'auto' || $auto == 'unit')->andWhere('t1.auto')->eq($auto)->fi()
            ->beginIF($auto != 'auto' && $auto != 'unit')->andWhere('t1.auto')->ne('unit')->fi()
            ->andWhere('t1.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 插入用例的步骤。
     * Insert the steps of the case.
     *
     * @param  int       $caseID
     * @param  array     $steps
     * @param  array     $expects
     * @param  array     $stepTypes
     * @access protected
     * @return void
     */
    protected function insertSteps(int $caseID, array $steps, array $expects, array $stepTypes, int $version = 1)
    {
        $preGrade     = 0;
        $parentStepID = $grandPaStepID = 0;
        foreach($steps as $stepKey => $stepDesc)
        {
            /* 跳过步骤描述为空的步骤。 */
            /* If step desc is empty, skip it. */
            if(empty($stepDesc)) continue;

            /* 计算步骤类型和层级。 */
            /* Set step type and step grade. */
            $stepType = $stepTypes[$stepKey];
            $grade    = substr_count((string)$stepKey, '.');

            /* 如果当前步骤层级为0，父ID和祖父ID清0。 */
            /* If step grade is zero, set parent step id and grand step id to zero. */
            if($grade == 0)
            {
                $parentStepID = $grandPaStepID = 0;
            }
            /* 如果前一个步骤的层级比当前步骤的层级大，将父ID设置为祖父ID，祖父ID清0。 */
            /* If previous step grade is greater than current step grade, set parent step id to grand step id, and set grand step id to zero. */
            elseif($preGrade > $grade)
            {
                $parentStepID  = $grandPaStepID;
                $grandPaStepID = 0;
            }

            /* 构建步骤数据，插入步骤。 */
            /* Build step data, and insert it. */
            $step = new stdClass();
            $step->type    = $stepType;
            $step->parent  = $parentStepID;
            $step->case    = $caseID;
            $step->version = $version;
            $step->desc    = rtrim(htmlSpecialString($stepDesc));
            $step->expect  = $stepType == 'group' ? '' : rtrim(htmlSpecialString(zget($expects, $stepKey, '')));

            $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();

            /* 如果步骤类型是group，将祖父ID设置为父ID，父ID设置为当前步骤ID。 */
            /* If step type is group, set grand step id to parent step id and set parent step id to current step id. */
            if($stepType == 'group')
            {
                $grandPaStepID = $parentStepID;
                $parentStepID  = $this->dao->lastInsertID();
            }

            $preGrade = $grade;
        }
    }

    /**
     * 获取用例步骤。
     * Get case steps.
     *
     * @param  int       $caseID
     * @param  int       $version
     * @access protected
     * @return void
     */
    protected function getSteps(int $caseID, int $version)
    {
        $caseSteps     = array();
        $steps         = $this->dao->select('*')->from(TABLE_CASESTEP)->where('`case`')->eq($caseID)->andWhere('version')->eq($version)->orderBy('id')->fetchAll('id');
        $preGrade      = 1;
        $parentSteps   = array();
        $key           = array(0, 0, 0);
        foreach($steps as $step)
        {
            $parentSteps[$step->id] = $step->parent;
            $grade = 1;
            if(isset($parentSteps[$step->parent])) $grade = isset($parentSteps[$parentSteps[$step->parent]]) ? 3 : 2;

            if($grade > $preGrade)
            {
                $key[$grade - 1] = 1;
            }
            else
            {
                if($grade < $preGrade)
                {
                    if($grade < 2) $key[1] = 0;
                    if($grade < 3) $key[2] = 0;
                }
                $key[$grade - 1] ++;
            }

            $name = implode('.', $key);
            $name = str_replace('.0', '', $name);

            $data = new stdclass();
            $data->name   = str_replace('.0', '', $name);
            $data->id     = $step->id;
            $data->step   = $step->desc;
            $data->desc   = $step->desc;
            $data->expect = $step->expect;
            $data->type   = $step->type;
            $data->parent = $step->parent;
            $data->grade  = $grade;

            $caseSteps[] = $data;

            $preGrade = $grade;
        }

        return $caseSteps;
    }

    /**
     * 获取相关的需求。
     * Get related stories.
     *
     * @param  array  $cases
     * @access public
     * @return array
     */
    protected function getRelatedStories(array $cases): array
    {
        $relatedStoryIdList = array();
        foreach($cases as $case) $relatedStoryIdList[$case->story] = $case->story;

        return $this->dao->select('id, title')->from(TABLE_STORY)->where('id')->in($relatedStoryIdList)->fetchPairs();
    }

    /**
     * 获取相关的用例。
     * Get related cases.
     *
     * @param  array  $cases
     * @access public
     * @return array
     */
    protected function getRelatedCases(array $cases): array
    {
        $relatedCaseIdList  = array();
        foreach($cases as $case)
        {
            $linkCases = explode(',', $case->linkCase);
            foreach($linkCases as $linkCaseID)
            {
                if($linkCaseID) $relatedCaseIdList[$linkCaseID] = trim($linkCaseID);
            }
        }

        return $this->dao->select('id, title')->from(TABLE_CASE)->where('id')->in($relatedCaseIdList)->fetchPairs();
    }

    /**
     * 获取相关的步骤。
     * Get related steps.
     *
     * @param  array  $caseIdList
     * @access public
     * @return array
     */
    protected function getRelatedSteps(array $caseIdList): array
    {
        return $this->dao->select('id, parent, `case`, version, type, `desc`, expect')->from(TABLE_CASESTEP)->where('`case`')->in($caseIdList)->orderBy('version desc,id')->fetchGroup('case', 'id');
    }

    /**
     * 获取相关的附件。
     * Get related files.
     *
     * @param  array  $caseIdList
     * @access public
     * @return array
     */
    protected function getRelatedFiles(array $caseIdList): array
    {
        return $this->dao->select('*')->from(TABLE_FILE)->where('objectType')->eq('testcase')->andWhere('objectID')->in($caseIdList)->andWhere('extra')->ne('editor')->fetchGroup('objectID');
    }

    /**
     * 创建一个测试用例。
     * Create a test case.
     *
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function doCreate(object $case): bool
    {
        if(empty($case->product)) $this->config->testcase->create->requiredFields = str_replace('story', '', $this->config->testcase->create->requiredFields);
        if(!empty($case->lib)) $this->config->testcase->create->requiredFields = str_replace('product', '', $this->config->testcase->create->requiredFields);

        $this->dao->insert(TABLE_CASE)->data($case, 'steps,expects,files,labels,stepType,needReview,scriptFile,scriptName')
            ->autoCheck()
            ->batchCheck($this->config->testcase->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();
         return !dao::isError();
    }

    /**
     * 更新一个测试用例。
     * Update a test case.
     *
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function doUpdate(object $case): bool
    {
        /* Remove the require field named story when the case is a lib case.*/
        $requiredFields = $this->config->testcase->edit->requiredFields;
        if(!empty($case->lib)) $requiredFields = str_replace(',story,', ',', ",$requiredFields,");

        $this->dao->update(TABLE_CASE)->data($case, 'deleteFiles,uid,stepChanged,comment,steps,expects,stepType,linkBug')
            ->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->checkFlow()
            ->where('id')->eq((int)$case->id)
            ->exec();
         return !dao::isError();
    }

    /*
     * 处理用例和项目的关系。
     * Deal with the relationship between the case and project when edit the case.
     *
     * @param  object  $oldCase
     * @param  object  $case
     * @access public
     * @return void
     */
    protected function updateCase2Project(object $oldCase, object $case): bool
    {
        $productChanged = $oldCase->product != $case->product;
        $storyChanged   = $oldCase->story   != $case->story;

        if(!$productChanged && !$storyChanged) return true;

        if($productChanged) $this->dao->update(TABLE_PROJECTCASE)->set('product')->eq($case->product)->set('version')->eq($case->version)->where('`case`')->eq($oldCase->id)->exec();

        if($storyChanged)
        {
            /* 取消之前需求对应项目和用例的关联关系。*/
            /* If the new related story isn't linked the project, unlink the case. */
            $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($oldCase->story)->fetchAll('project');
            $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->in(array_keys($projects))->andWhere('`case`')->eq($oldCase->id)->exec();

            /* 设置需求对应项目和用例的关联关系。*/
            /* If the new related story is not null, make the case link the project which link the new related story. */
            if(!empty($case->story))
            {
                $projects = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($case->story)->fetchAll('project');
                if($projects)
                {
                    $projects   = array_keys($projects);
                    $lastOrders = $this->dao->select('project, MAX(`order`) AS lastOrder')->from(TABLE_PROJECTCASE)->where('project')->in($projects)->groupBy('project')->fetchPairs();

                    foreach($projects as $projectID)
                    {
                        $lastOrder = isset($lastOrders[$projectID]) ? $lastOrders[$projectID] : 0;

                        $data = new stdclass();
                        $data->project = $projectID;
                        $data->product = $case->product;
                        $data->case    = $oldCase->id;
                        $data->version = $oldCase->version;
                        $data->order   = ++ $lastOrder;

                        $this->dao->replace(TABLE_PROJECTCASE)->data($data)->autoCheck()->exec();
                    }
                }
            }
        }

        return !dao::isError();
    }

    /**
     * 更新用例步骤。
     * Update step.
     *
     * @param  object    $case
     * @param  object    $oldCase
     * @access protected
     * @return bool
     */
    protected function updateStep(object $case, object $oldCase): bool
    {
        if($case->steps)
        {
            $this->insertSteps($oldCase->id, $case->steps, $case->expects, (array)$case->stepType, $case->version);
        }
        else
        {
            foreach($oldCase->steps as $step)
            {
                unset($step->id);
                unset($step->name);
                unset($step->step);
                unset($step->grade);

                $step->case    = $oldCase->id;
                $step->version = $case->version;
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 更新用例和 bug 的关联关系。
     * Link bugs with case.
     *
     * @param  int       $caseID
     * @param  array     $linkedBugs
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function linkBugs(int $caseID, array $linkedBugs, object $case): bool
    {
        $toLinkBugs = $case->linkBug;
        $newBugs    = array_diff($toLinkBugs, $linkedBugs);
        $removeBugs = array_diff($linkedBugs, $toLinkBugs);

        foreach($newBugs as $bugID)    $this->dao->update(TABLE_BUG)->set('`case`')->eq($caseID)->set('caseVersion')->eq($case->version)->set('`story`')->eq($case->story)->set('storyVersion')->eq($case->storyVersion)->where('id')->eq($bugID)->exec();
        foreach($removeBugs as $bugID) $this->dao->update(TABLE_BUG)->set('`case`')->eq(0)->set('caseVersion')->eq(0)->set('`story`')->eq(0)->set('storyVersion')->eq(0)->where('id')->eq($bugID)->exec();

        return !dao::isError();
    }

    /**
     * 解除用例和测试单的关联。
     * Unlink case from test task.
     *
     * @param  int       $caseID
     * @param  array     $testtasks
     * @access protected
     * @return bool
     */
    protected function unlinkCaseFromTesttask(int $caseID, int $branch, array $testtasks): bool
    {
        $this->loadModel('action');
        foreach($testtasks as $taskID => $testtask)
        {
            if($testtask->branch != $branch && $taskID)
            {
                $this->dao->delete()->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->exec();
                $this->action->create('case' ,$caseID, 'unlinkedfromtesttask', '', $taskID);
            }
        }

        return !dao::isError();
    }

    /**
     * 获取待评审的用例数量。
     * Get the amount of cases pending review.
     *
     * @access protected
     * @return int
     */
    protected function getReviewAmount(): int
    {
        return $this->dao->select('COUNT(id) AS count')->from(TABLE_CASE)->where('status')->eq('wait')->fetch('count');
    }

    /**
     * 导入步骤。
     * Import steps.
     *
     * @param  int    $caseID
     * @param  array  $steps
     * @access public
     * @return bool
     */
    public function importSteps(int $caseID, array $steps): bool
    {
        /* 插入步骤。 */
        /* Insert steps. */
        $parentSteps = array();
        foreach($steps as $stepID => $step)
        {
            $step->case = $caseID;
            if(!empty($parentSteps[$step->parent])) $step->parent = $parentSteps[$step->parent];
            unset($step->id);

            $this->dao->insert(TABLE_CASESTEP)->data($step)->exec();

            $parentSteps[$stepID] = $this->dao->lastInsertID();
        }
        return !dao::isError();
    }

    /**
     * 导入文件。
     * Import files.
     *
     * @param  int    $caseID
     * @param  array  $files
     * @access public
     * @return bool
     */
    public function importFiles(int $caseID, array $files): bool
    {
        /* 插入文件。 */
        /* Insert files. */
        foreach($files as $fileID => $file)
        {
            if(isset($file->oldpathname))
            {
                if(!empty($file->oldpathname))
                {
                    $originName = pathinfo($file->oldpathname, PATHINFO_FILENAME);
                    $datePath   = substr($file->oldpathname, 0, 6);
                    $originFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" . $originName;

                    $copyName = $originName . 'copy' . $caseID;
                    $copyFile = $this->app->getAppRoot() . "www/data/upload/{$this->app->company->id}/" . "{$datePath}/" .  $copyName;
                    copy($originFile, $copyFile);
                }

                unset($file->oldpathname);
            }

            $file->objectID  = $caseID;
            $file->addedBy   = $this->app->user->account;
            $file->addedDate = helper::now();
            $file->downloads = 0;
            unset($file->id);
            $this->dao->insert(TABLE_FILE)->data($file)->exec();
        }
        return !dao::isError();
    }

    /**
     * 保存场景。
     * Save scene.
     *
     * @param  array     $sceneData
     * @param  array     $sceneList
     * @access protected
     * @return array
     */
    protected function saveScene(array $sceneData, array $sceneList): array
    {
        $scene          = new stdclass();
        $scene->title   = $sceneData['name'];
        $scene->product = $sceneData['product'];
        $scene->branch  = $sceneData['branch'];
        $scene->module  = $sceneData['module'] ?? 0;

        if(!isset($sceneData['id']))
        {
            $scene->openedBy   = $this->app->user->account;
            $scene->openedDate = helper::now();

            $this->dao->insert(TABLE_SCENE)->data($scene)->autoCheck()->exec();
            $sceneID = $this->dao->lastInsertID();

            $this->dao->update(TABLE_SCENE)->set('`order`')->eq($sceneID)->where('id')->eq($sceneID)->exec();
        }
        else
        {
            $sceneID = $sceneData['id'];

            $scene->lastEditedBy   = $this->app->user->account;
            $scene->lastEditedDate = helper::now();

            $affectedRows = $this->dao->update(TABLE_SCENE)->data($scene)->where('id')->eq($sceneID)->exec();
            if(empty($affectedRows)) return array('result' => 'fail', 'message' => sprintf($this->lang->testcase->errorSceneNotExist, $sceneID));
        }

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        $tmpPId = $sceneData['tmpPId'];
        $pScene = zget($sceneList, $tmpPId, array());
        $this->fixScenePath($sceneID, $pScene);

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());

        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'sceneID' => $sceneID);
    }

    /**
     * 调整场景的路径。
     * Fix the scene path.
     *
     * @param  int       $sceneID
     * @param  array     $pScene
     * @access protected
     * @return bool
     */
    protected function fixScenePath(int $sceneID, array $pScene = array()): bool
    {
        $parent = 0;
        $grade  = 1;
        $path   = ",{$sceneID},";

        if(!empty($pScene))
        {
            $parent      = $pScene['id'];
            $parentScene = $this->dao->findById((int)$parent)->from(TABLE_SCENE)->fetch();
            $path        = $parentScene->path . "{$sceneID},";
            $grade       = $parentScene->grade + 1;
        }

        $this->dao->update(TABLE_SCENE)->set('parent')->eq($parent)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($sceneID)->exec();

        return !dao::isError();
    }

    /**
     * 基于用例构建场景数据。
     * Build scene base on case.
     *
     * @param  object $scene
     * @param  array  $fieldTypes
     * @param  array  $cases
     * @access public
     * @return object
     */
    public function buildSceneBaseOnCase(object $scene, array $fieldTypes, array $cases): object
    {
        /* Set default value for the fields exist in TABLE_CASE but not in TABLE_SCENE. */
        foreach($fieldTypes as $field => $type)
        {
            if(!isset($scene->{$field})) $scene->{$field} = $type['rule'] == 'int' ? '0' : '';
        }

        $scene->caseID     = $scene->id;
        $scene->bugs       = 0;
        $scene->results    = 0;
        $scene->caseFails  = 0;
        $scene->stepNumber = 0;
        $scene->isScene    = true;

        if(!empty($cases))
        {
            foreach($cases as $case)
            {
                $case->caseID  = $case->id;
                $case->id      = 'case_' . $case->id;
                $case->parent  = $scene->id;
                $case->grade   = $scene->grade + 1;
                $case->path    = $scene->path . $case->id . ',';
                $case->isScene = false;
            }
            $scene->cases = $cases;
        }

        return $scene;
    }
}
