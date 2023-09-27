<?php
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
        $this->session->set('storyList',          $uri, 'product');
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
            $trash->objectName = zget($pivotNames, $this->app->getClientLang(), '') ? : reset(array_filter($pivotNames));
        }
        else
        {
            $module     = $trash->objectType == 'case' ? 'testcase'                      : $trash->objectType;
            $params     = $trash->objectType == 'user' ? "account={$trash->objectName}" : "id={$trash->objectID}";
            $methodName = 'view';
            if($module == 'basicmeas')
            {
                $module     = 'measurement';
                $methodName = 'setSQL';
                $params     = "id={$trash->objectID}";
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
                if($module == 'requirement') $module = 'story';
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
        if($object->code)
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
     * @return object
     */
    public function checkActionExist(int $actionID): object
    {
        if($actionID <= 0) return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound));
        $action = $this->action->getById($actionID);

        if(!$action) return $this->send(array('result' => 'fail', 'message' => $this->lang->notFound));
        return $action;
    }
}
