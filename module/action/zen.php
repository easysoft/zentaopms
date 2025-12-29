<?php
declare(strict_types=1);
class actionZen extends action
{
    /**
     * 获取回收站的导航栏。
     * Get the navigation bar of the trashes.
     *
     * @param  array $objectTypeList
     * @access public
     * @return array
     */
    public function getTrashesHeaderNavigation(array $objectTypeList): array
    {
        $preferredType       = array();
        $moreType            = array();
        $preferredTypeConfig = $this->config->action->preferredType->ALM;
        $preferredTypeConfig = $this->config->systemMode == 'light' ? $this->config->action->preferredType->light : $this->config->action->preferredType->ALM;
        foreach($objectTypeList as $objectType)
        {
            if(!isset($this->config->objectTables[$objectType])) continue;
            in_array($objectType, $preferredTypeConfig) ? $preferredType[$objectType] = $objectType : $moreType[$objectType] = $objectType;
        }
        if(count($preferredType) < $this->config->action->preferredTypeNum)
        {
            $toPreferredType = array_splice($moreType, 0, $this->config->action->preferredTypeNum - count($preferredType));
            $preferredType   = $preferredType + $toPreferredType; //填充至设定的展示数量。
        }

        $this->view->moreType            = $moreType;
        $this->view->preferredTypeConfig = $preferredTypeConfig;

        return $preferredType;
    }

    /**
     * 保存当前页面的URL到Session中。
     * Save the current page URL to the session.
     *
     * @access public
     * @return void
     */
    public function saveUrlIntoSession()
    {
        $uri = $this->app->getURI(true);
        $this->session->set('productList',        $uri, 'product');
        $this->session->set('productPlanList',    $uri, 'product');
        $this->session->set('releaseList',        $uri, 'product');
        $this->session->set('programList',        $uri, 'program');
        $this->session->set('projectList',        $uri, 'project');
        $this->session->set('executionList',      $uri, 'execution');
        $this->session->set('taskList',           $uri, 'execution');
        $this->session->set('buildList',          $uri, 'execution');
        $this->session->set('bugList',            $uri, 'qa');
        $this->session->set('caseList',           $uri, 'qa');
        $this->session->set('testtaskList',       $uri, 'qa');
        $this->session->set('docList',            $uri, 'doc');
        $this->session->set('opportunityList',    $uri, 'project');
        $this->session->set('riskList',           $uri, 'project');
        $this->session->set('trainplanList',      $uri, 'project');
        $this->session->set('roomList',           $uri, 'admin');
        $this->session->set('researchplanList',   $uri, 'project');
        $this->session->set('researchreportList', $uri, 'project');
        $this->session->set('meetingList',        $uri, 'project');
        $this->session->set('designList',         $uri, 'project');
        $this->session->set('storyLibList',       $uri, 'assetlib');
        $this->session->set('issueLibList',       $uri, 'assetlib');
        $this->session->set('riskLibList',        $uri, 'assetlib');
        $this->session->set('opportunityLibList', $uri, 'assetlib');
        $this->session->set('practiceLibList',    $uri, 'assetlib');
        $this->session->set('componentLibList',   $uri, 'assetlib');
    }

    /*
     * 构建回收站内容的属性。
     * Build the attributes of the trashes.
     *
     * @param  object $trash
     * @param  array  $projectList
     * @param  array  $productList
     * @param  array  $executionList
     * @access public
     * @return void
     */
    public function processTrash(object $trash, array $projectList, array $productList, array $executionList)
    {
        if($trash->objectType == 'pivot')
        {
            $pivotNames = json_decode($trash->objectName, true);
            $pivotNames = empty($pivotNames) ? array() : $pivotNames;
            $trash->objectName = zget($pivotNames, $this->app->getClientLang(), '') ? : reset(array_filter($pivotNames));
        }
        else
        {
            $module     = $trash->objectType == 'case'         ? 'testcase'                     : $trash->objectType;
            $module     = $trash->objectType == 'doctemplate'  ? 'doc'                          : $module;
            $params     = $trash->objectType == 'user'         ? "account={$trash->objectName}" : "id={$trash->objectID}";
            $methodName = 'view';
            if($module == 'basicmeas')
            {
                $module     = 'measurement';
                $methodName = 'setSQL';
                $params     = "id={$trash->objectID}";
            }
            if($module == 'deploystep')
            {
                $module     = 'deploy';
                $methodName = 'viewStep';
            }
            if($trash->objectType == 'api')
            {
                $params     = "libID=0&moduelID=0&apiID={$trash->objectID}";
                $methodName = 'index';
            }
            if(in_array($module, array('traincourse','traincontents')))
            {
                $methodName = $module == 'traincourse' ? 'viewcourse' : 'viewchapter';
                $module     = 'traincourse';
            }
            if(isset($this->config->action->customFlows[$trash->objectType]))
            {
                $flow   = $this->config->action->customFlows[$trash->objectType];
                $module = $flow->module;
            }
            if(strpos($this->config->action->noLinkModules, ",{$module},") === false)
            {
                $tab     = '';
                $canView = common::hasPriv($module, $methodName);
                if($trash->objectType == 'meeting') $tab = $trash->project ? "data-app='project'" : "data-app='my'";
                $trash->objectName = $canView ? html::a($this->createLink($module, $methodName, $params), $trash->objectName, '_self', "title='{$trash->objectName}' $tab") : "<span title='$trash->objectName'>$trash->objectName</span>";
            }
        }

        if(!empty($projectList[$trash->project]))     $trash->project   = $projectList[$trash->project]->name          . ($projectList[$trash->project]->deleted         ? "<span class='label danger ml-2'>{$this->lang->project->deleted}</span>" : '');
        if(!empty($productList[$trash->objectID]))    $trash->product   = $productList[$trash->objectID]->productTitle . ($productList[$trash->objectID]->productDeleted ? "<span class='label danger ml-2'>{$this->lang->story->deleted}</span>" : '');
        if(!empty($executionList[$trash->execution])) $trash->execution = $executionList[$trash->execution]->name      . ($executionList[$trash->execution]->deleted     ? "<span class='label danger ml-2'>{$this->lang->execution->deleted}</span>" : '');
    }

    /**
     * 获取重复的名称和代号。
     * Get the repeated name and code.
     *
     * @param  object $repeatObject
     * @param  object $object
     * @param  string $table
     * @access public
     * @return array
     */
    public function getReplaceNameAndCode(object $repeatObject, object $object, string $table): array
    {
        $replaceName = '';
        $existNames = $this->action->getLikeObject($table, 'name', 'name', $repeatObject->name . '_%');
        for($i = 1; $i < 10000; $i ++)
        {
            $replaceName = $repeatObject->name . '_' . $i;
            if(!in_array($replaceName, $existNames)) break;
        }
        $replaceCode = '';
        if(isset($object->code))
        {
            $existCodes = $this->action->getLikeObject($table, 'code', 'code', $repeatObject->code . '_%');
            for($i = 1; $i < 10000; $i ++)
            {
                $replaceCode = $repeatObject->code . '_' . $i;
                if(!in_array($replaceCode, $existCodes)) break;
            }
        }

        return array($replaceName, $replaceCode);
    }

    /**
     * 检查操作记录是否存在。
     * Check if the action record exists.
     *
     * @param  int    $actionID
     * @access public
     * @return array|object
     */
    public function checkActionExist(int $actionID): array|object
    {
        if($actionID <= 0) return ['result' => 'fail', 'message' => $this->lang->notFound];

        $action = $this->action->getById($actionID);
        if(!$action) return ['result' => 'fail', 'message' => $this->lang->notFound];

        return $action;
    }

    /**
     * 获取需要确认为否状态下的信息。
     * Get the information that needs to be confirmed as no.
     *
     * @param  object $object
     * @param  object $repeatObject
     * @access public
     * @return string
     */
    public function getConfirmNoMessage(object $repeatObject, object $object, object $oldAction, string $replaceName, string $replaceCode): string
    {
        $message = '';
        if($repeatObject->name == $object->name && !empty($repeatObject->code) && $repeatObject->code == $object->code)
        {
            $message = sprintf($this->lang->action->repeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName, $replaceCode);
        }
        elseif($repeatObject->name == $object->name)
        {
            $message = sprintf($this->lang->action->nameRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceName);
        }
        elseif(!empty($repeatObject->code) && $repeatObject->code == $object->code)
        {
            $message = sprintf($this->lang->action->codeRepeatChange, $this->lang->{$oldAction->objectType}->common, $replaceCode);
        }

        return $message;
    }

    /**
     * 恢复对象。
     * Recover the object.
     *
     * @param  object $repeatObject
     * @param  object $object
     * @param  string $replaceName
     * @param  string $replaceCode
     * @param  string $table
     * @param  object $oldAction
     * @access public
     * @return void
     */
    public function recoverObject(object $repeatObject, object $object, string $replaceName, string $replaceCode, string $table, object $oldAction)
    {
        $recoverData = array();
        if($repeatObject->name == $object->name && !empty($repeatObject->code) && $repeatObject->code == $object->code)
        {
            $recoverData = array('code' => $replaceCode, 'name' => $replaceName);
        }
        elseif($repeatObject->name == $object->name)
        {
            $recoverData = array('name' => $replaceName);
        }
        elseif(!empty($repeatObject->code) && $repeatObject->code == $object->code)
        {
            $recoverData = array('code' => $replaceCode);
        }

        if(!empty($recoverData)) $this->action->updateObjectByID($table, $oldAction->objectID, $recoverData);
    }

    /**
     * 恢复阶段。
     * Restore stages.
     *
     * @param  object $action
     * @param  string $confirmChange
     * @access public
     * @return bool|string
     */
    public function restoreStages(object $action, string $confirmChange = 'no'): bool|string
    {
        /* 检查父阶段是否创建过任务。 */
        /* Check parent stage isCreateTask. */
        $execution      = $this->loadModel('execution')->getByID($action->objectID);
        $hasCreatedTask = $this->loadModel('programplan')->isCreateTask($execution->parent);
        if(!$hasCreatedTask) return $this->lang->action->hasCreatedTask;

        /* 检查同级执行的类型。 */
        /* Check type of siblings. */
        $siblings = $this->execution->getSiblingsTypeByParentID($execution->parent);

        if($execution->type == 'stage' && (isset($siblings['sprint']) || isset($siblings['kanban']))) return $this->lang->action->hasOtherType[$execution->type];
        if(($execution->type == 'sprint' || $execution->type == 'kanban') && isset($siblings['stage'])) return $this->lang->action->hasOtherType[$execution->type];

        /* 如果父阶段不存在，你应该恢复父级阶段，并且刷新状态。 */
        /* If parent stage is not exists, you should recover its parent stages, refresh status. */
        $stagePathList    = explode(',', trim($execution->path, ','));
        $deletedStageList = $this->action->getDeletedStagedByList($stagePathList);
        $deletedParents   = $deletedStageList;
        array_pop($deletedParents);

        $needChangeAttr    = false; //是否需要修改attribute值
        $deletedTitle      = ''; //被删除的标题
        $startChangedStage = $execution;

        if(!empty($deletedParents))
        {
            foreach($deletedParents as $deletedParent) $deletedTitle .= "'{$deletedParent->name}',";

            /* 如果父阶段的状态已经被改变，子阶段的的状态也要改变。 */
            /* If parent stage's attribute has changed, sub-stage's attribute need change. */
            $deletedTopParent = current($deletedStageList);
            $isTopStage       = $this->loadModel('programplan')->isTopStage($deletedTopParent->id);
            $parentAttr       = $deletedTopParent->attribute;
            if(!$isTopStage) $parentAttr = $this->action->getAttributeByExecutionID();

            /* 从父阶段开始逐级下查，如果发现有跟父阶段不一样的attribute，对后面所有的attrubite值进行修改。 */
            /* Check down step by step from the parent stage, if you find that there is an attribute different from the parent stage, modify all the later attrubite values*. */
            $startChangedStage = $execution;
            foreach($deletedStageList as $deletedStage)
            {
                if($parentAttr != 'mix' && $parentAttr != $deletedStage->attribute)
                {
                    $startChangedStage = $deletedStage;
                    $needChangeAttr    = true;
                    break;
                }
                $parentAttr = $deletedStage->attribute;
            }
        }

        /* 确认是否要恢复父阶段并且修改不一样的attribute值。 */
        /* Confirm whether you want to restore the parent stage and modify the different attribute value. */
        if(!empty($deletedTitle) || $needChangeAttr)
        {
            $this->app->loadLang('stage');

            $confirmLang  = sprintf($this->lang->action->hasDeletedParent, trim($deletedTitle, ',')) . $this->lang->action->whetherToRestore;
            if($needChangeAttr) $confirmLang = sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;
            if(!empty($deletedTitle) && $needChangeAttr) $confirmLang = sprintf($this->lang->action->hasDeletedParent, $deletedTitle) . sprintf($this->lang->action->hasChangedAttr, zget($this->lang->stage->typeList, $parentAttr)) . $this->lang->action->whetherToRestore;

            if($confirmChange == 'no') return $confirmLang;

            /* 如果被删除的标题集合不为空，则更新所有的阶段。 */
            /* If the collection of titles being deleted is not empty, all stages are updated. */
            if(!empty($deletedTitle)) $this->action->restoreStages($deletedParents);

            /* 如果需要更新attribute的值，则恢复路径上所有的父节点以及更新attrubute值。 */
            /* If the attribute value needs to be updated, restore all parent nodes on the path and update the attribute value. */
            if($needChangeAttr)
            {
                $needChangedStages = substr($execution->path, strpos($execution->path, ",{$startChangedStage->id},"));
                $needChangedStages = explode(',', trim($needChangedStages, ','));
                $this->action->updateStageAttribute($parentAttr, $needChangedStages);
            }
        }

        $this->programplan->computeProgress($startChangedStage->parent);

        return true;
    }
}
