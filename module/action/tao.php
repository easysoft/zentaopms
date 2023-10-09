<?php
declare(strict_types=1);
class actionTao extends actionModel
{
    /**
     * 获取一个action的基础数据。
     * Fetch base info of a action.
     *
     * @param  int $actionID
     * @access protected
     * @return object|bool
     */
    protected function fetchBaseInfo(int $actionID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
    }

    /**
     * 获取一个基础对象的信息。
     * Get object base info.
     *
     * @param  string $table
     * @param  array  $queryParam
     * @param  string $field
     * @param  string $orderby
     * @access protected
     * @return object|bool
     */
    protected function getObjectBaseInfo(string $table, array $queryParam, string $field = '*', string $orderby = ''): object|bool
    {
        $querys = array_map(function($key, $query){return "`{$key}` = '{$query}'";}, array_keys($queryParam), $queryParam);
        return $this->dao->select($field)->from($table)->where(implode(' and ', $querys))->orderby($orderby)->fetch();
    }

    /**
     * 获取无需过滤的关联关系。
     * Get no filter required relation.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getNoFilterRequiredRelation(string $objectType, int $objectID): array
    {
        $product   = array(0);
        $project   = 0;
        $execution = 0;
        switch($objectType)
        {
            case 'product':
                $product = array($objectID);
                break;
            case 'project':
            case 'execution':
                $productList = $this->getProductByProject($objectID);
                extract(array($objectType => $objectID, 'product' => $productList));

                if($objectType == 'execution')
                {
                    $executionInfo = $this->loadModel('execution')->getById($objectID);
                    $project = $executionInfo ? $executionInfo->project : 0;
                }
                break;
        }

        return array($product, $project, $execution);
    }

    /**
     * 根据项目获取产品。
     * Get product by project.
     *
     * @param  int   $objectID
     * @access protected
     * @return array
     */
    protected function getProductByProject(int $objectID): array
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetchPairs('product');
        return $products ? array_keys($products) : array();
    }

    /**
     * 根据项目获取产品列表。
     * Get product list by project.
     *
     * @param  int   $projectID
     * @access protected
     * @return array
     */
    protected function getProductListByProject(int $projectID): array
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchPairs('product');
        return $products ? array_keys($products) : array();
    }

    /**
     * 获取用户故事相关的产品、项目、阶段。
     * Get story related product, project, stage.
     *
     * @param  string $actionType
     * @param  int    $objectID
     * @param  int    $extra
     * @access protected
     * @return array
     */
    protected function getStoryActionRelated(string $actionType, int $objectID, int $extra): array
    {
        $product = array(0);
        $project = $execution = 0;
        switch($actionType)
        {
            case 'linked2build':
            case 'unlinkedfrombuild':
                $build = $this->loadModel('build')->getByID($extra);
                if($build)
                {
                    $project   = $build->project;
                    $execution = $build->execution;
                }

                break;
            case 'estimated':
                $executionInfo = $this->loadModel('execution')->getById($extra);
                if($execution) $project = $executionInfo->project;
                $execution = $extra;

                break;
            default:
                $projectList = $this->dao->select('t2.id,t2.project,t2.type')->from(TABLE_PROJECTSTORY)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                    ->where('t1.story')->eq($objectID)
                    ->fetchAll();
                foreach($projectList as $projectInfo)
                {
                    if($projectInfo->type == 'project')
                    {
                        $project = $projectInfo->id;
                        continue;
                    }
                    $project   = $projectInfo->project;
                    $execution = $projectInfo->id;
                }
                break;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取用例相关的产品、项目、阶段。
     * Get case related product, project, stage.
     *
     * @param  string $objectType
     * @param  string $actionType
     * @param  string $table
     * @param  int    $objectID
     * @param  int    $extra
     * @access protected
     * @return array
     */
    protected function getCaseRelated(string $objectType, string $actionType, int $objectID, int $extra): array
    {
        $product = array(0);
        $project = $execution = 0;
        $result  = $this->dao->select('product, project, execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product   = explode(',', (string)$result->product);
            $project   = $result->project;
            $execution = $result->execution;
        }

        if(in_array($actionType, array('linked2testtask', 'unlinkedfromtesttask', 'assigned', 'run')) && $extra)
        {
            $testtask = $this->dao->select('project,execution')->from(TABLE_TESTTASK)->where('id')->eq($extra)->fetch();
            $project   = $testtask->project;
            $execution = $testtask->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 常规获取相关的产品、项目、执行。
     * Get general related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $field
     * @access protected
     * @return array
     */
    protected function getGenerateRelated(string $objectType, int $objectID, string $field = 'product, project, execution'): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result  = $this->dao->select($field)->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product   = implode(',', array($result->product));
            $project   = $result->project;
            $execution = $result->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取用例相关的产品、项目、执行。
     * Get case related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getReleaseRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result  = $this->dao->select('product, build')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $product = $result->product;
            $project = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->eq($result->build)->fetch('project');
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取任务相关的产品、项目、执行。
     * Get task related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getTaskReleated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $fields = 'project, execution, story';
        $result = $this->dao->select($fields)->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $table = $result->story != 0 ? TABLE_STORY : TABLE_PROJECTPRODUCT;
            $field = $result->story != 0 ? 'id' : 'project';
            $value = $result->story != 0 ? $result->story : $result->execution;

            $products = $this->dao->select('product')->from($table)->where($field)->eq($value)->fetch('product');
            $product  = $products ? array($products) : array();

            $project   = $result->project;
            $execution = $result->execution;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取需求相关的产品、项目、执行。
     * Get story related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access protected
     * @return array
     */
    protected function getReviewRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;

        $result = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();

        if($result)
        {
            $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($result->project)->fetchPairs('product');
            if(!empty($products)) array_keys($products);
            $project  = zget($result, 'project', 0);
        }

        return array($product, $project, $execution);
    }

    /**
     * 根据类型和ID获取操作记录列表。
     * Get action list by type and ID.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  array  $modules
     * @access protected
     * @return array
     */
    protected function getActionListByTypeAndID(string $objectType, array|int $objectID, array $modules): array
    {
        return $this->dao->select('*')->from(TABLE_ACTION)
            ->beginIF($objectType == 'project')
            ->where("objectType IN('project', 'testtask', 'build')")
            ->andWhere('project')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'story')
            ->where('objectType')->in('story,requirement')
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'case')
            ->where('objectType')->in('case,testcase')
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'module')
            ->where('objectType')->eq($objectType)
            ->andWhere('((action')->ne('deleted')->andWhere('objectID')->in($objectID)->markRight(1)
            ->orWhere('(action')->eq('deleted')->andWhere('objectID')->in($modules)->markRight(1)->markRight(1)
            ->fi()
            ->beginIF(strpos('project,case,story,module', $objectType) === false)
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->orderBy('date, id')
            ->fetchAll('id');
    }

    /**
     * 获取linked操作记录的附加信息。
     * Get action extra info.
     *
     * @param  object $action
     * @param  string $type
     * @access protected
     * @return void
     */
    protected function getLinked2Extra(object $action, string $type)
    {
        $table = $this->getTableByType($type);
        if(empty($table)) return false;

        $method = 'view';
        if(in_array($type, array('executon', 'kanban')))
        {
            $fields = 'name, type, multiple';
            $execution = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
            if($execution->type != 'project' && empty($execution->multiple)) return false;

            $name   = $execution->name;
            if($execution->type == 'kanban') $method = 'kanban';
            $action->extra = (!common::hasPriv('execution', $method) || ($method == 'kanban' && isonlybody())) ? $name : html::a(helper::createLink('execution', $method, "executionID={$action->execution}"), $name, '', "data-app='execution'");
        }
        elseif($type == 'project')
        {
            $fields  = 'name, model';
            $project = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
            if(empty($project->multiple)) return false;

            $name = $project->name;
            if($project->model == 'kanban') $method = 'kanban';
            if($name) $action->extra = common::hasPriv('project', $method) ? html::a(helper::createLink('project', $method, "projectID={$action->project}"), $name) : $name;
        }
        elseif($type == 'plan')
        {
            $fields = 'title';
            $plan   = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
            if($plan && $plan->title) $action->extra = common::hasPriv('productplan', 'view') ? html::a(helper::createLink('productplan', $method, "planID={$action->extra}"), $plan->title) : $plan->title;
        }
        elseif(in_array($type, array('build', 'bug', 'release', 'testtask')))
        {
            $fields = 'name';
            $object = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
            if($object && $object->name) $action->extra = common::hasPriv($type, $method) ? html::a(helper::createLink($type, $method, $this->processParamString($action, $type), $object->name)) : $object->name;
        }
        elseif($type == 'revision')
        {
            $this->processRevision($action, $table);
        }
    }

    /**
     * 通过ID获取对象信息。
     * Get object info by ID.
     *
     * @param  string $table
     * @param  int    $objectID
     * @access protected
     * @return object|bool
     */
    protected function fetchObjectInfoByID(string $table, int $objectID, string $field = '*'): object|bool
    {
        return $this->dao->select($field)->from($table)->where('id')->eq($objectID)->fetch();
    }

    /**
     * 获取unlinked操作记录的附加信息。
     * Get unlinked action extra info.
     *
     * @param  object $action
     * @param  string $type
     * @access protected
     * @return void
     */
    protected function getUnlinkedFromExtra(object $action, string $type)
    {
        $table = $this->getTableByType($type);
        if(empty($table)) return false;

        $method = 'view';
        if($type == 'revision')
        {
            $this->processAttribute($action, $table);
        }
        elseif(in_array($type, array('execution', 'project', 'build', 'release', 'testtask', 'productplan')))
        {
            $fields = $type == 'productplan' ? 'title' : 'name';
            $this->processActionExtra($table, $action, $fields, $type, $method);
        }
    }

    /**
     * 组建Action的extra信息。
     * Build action extra info.
     *
     * @param  string $table
     * @param  object $action
     * @param  string $fields
     * @param  string $type
     * @param  string $method
     * @param  bool   $onlyBody
     * @access protected
     * @return void
     */
    protected function processActionExtra(string $table, object $action, string $fields, string $type, string $method = 'view', bool $onlyBody = false)
    {
        $object = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
        $condition = common::hasPriv($type, $method);
        if($onlyBody) $condition = $condition && !isonlybody();
        if($object && $object->{$fields}) $action->extra = $condition ? html::a(helper::createLink($type, $method, $this->processParamString($action, $type)), "#{$action->extra} " . $object->{$fields}) : "#{$action->extra} " . $object->{$fields};
    }

    /**
     * 处理revision
     * Process revision.
     *
     * @param  object $action
     * @param  string $table
     * @access protected
     * @return void
     */
    protected function processRevision(object $action, string $table)
    {
        $fields = 'repo, revision';
        $commit = $this->fetchObjectInfoByID($table, $action->extra, $fields);
        if($commit)
        {
            $revision = substr($commit->revision, 0, 10);
            $action->extra = common::hasPriv('repo', 'revision') ? html::a(helper::createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}"), $revision) : $revision;
        }
    }

    /**
     * 根据类型获取对象表。
     * Get object table by type.
     *
     * @param  string $type
     * @access protected
     * @return string
     */
    protected function getTableByType(string &$type): string
    {
        if($type == 'plan')     $type = 'productplan';
        if($type == 'revision') $type = 'repohistory';
        if($type == 'bug')      $type = 'build';
        return $this->config->objectTables[$type] ?? '';
    }

    /**
     * 处理属性。
     * Process attribute.
     *
     * @param  string $type
     * @access protected
     * @return string
     */
    protected function processAttribute($type): string
    {
        if($type == 'testtask') $type = 'task';
        return $type;
    }

    /**
     * 处理参数字符串。
     * Process param string.
     *
     * @param  object $action
     * @param  string $type
     * @access protected
     * @return string
     */
    protected function processParamString(object $action, string $type): string
    {
        $paramString = '';
        switch($type)
        {
            case 'build':
            case 'bug':
            case 'release':
                $attribute = $this->processAttribute($type);
                $paramString = "{$attribute}ID={$action->extra}&type={$action->objectType}";
                break;
            case 'testtask':
                $paramString = "taskID={$action->extra}";
                break;
            case 'execution':
            case 'kanban':
            case 'task':
                $paramString = "{$type}ID={$action->extra}";
                break;
            case 'project':
                $productID = trim($action->product, ',');
                $paramString = "{$type}ID={$action->execution}&productID={$productID}";
                break;
            case 'productplan':
                $paramString = "planID={$action->extra}";
                break;
            case 'caselib':
                $paramString = "libID={$action->extra}";
                break;
        }

        return $paramString;
    }

    /**
     * 搭建创建子任务的Action的extra信息。
     * Build create children action extra info.
     *
     * @param  object $action
     * @access protected
     * @return void
     */
    protected function processCreateChildrenActionExtra(object $action)
    {
        $names = $this->dao->select('id,name')->from(TABLE_TASK)->where('id')->in($action->extra)->fetchPairs('id', 'name');
        $action->extra = '';
        if($names)
        {
            foreach($names as $id => $name) $action->extra .= common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID=$id"), "#$id " . $name) . ', ' : "#$id " . $name . ', ';
        }
        $action->extra = trim(trim($action->extra), ',');
    }

    /**
     * 搭建创建需求的Action的extra信息。
     * Build create requirement action extra info.
     *
     * @param object $action
     * @access protected
     * @return void
     */
    protected function processCreateRequirementsActionExtra(object $action)
    {
        $names = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($action->extra)->fetchPairs('id', 'title');
        $action->extra = '';
        if($names)
        {
            foreach($names as $id => $name) $action->extra .= common::hasPriv('requriement', 'view') ? html::a(helper::createLink('story', 'view', "storyID=$id"), "#$id " . $name) . ', ' : "#$id " . $name . ', ';
        }
        $action->extra = trim(trim($action->extra), ',');
    }

    /**
     * 搭建关闭用户故事和解决bug的Action的extra信息。
     * Build closed story and resolve bug action extra info.
     *
     * @param object $action
     * @access protected
     * @return void
     */
    protected function processClosedStoryAndResolvedBugActionExtra(object $action)
    {
        $action->appendLink = '';
        if(strpos($action->extra, '|') !== false) $action->extra = substr($action->extra, 0, strpos($action->extra, '|'));
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = $extra;
            if($id)
            {
                $table = $action->objectType == 'story' ? TABLE_STORY : TABLE_BUG;
                $name  = $this->dao->select('title')->from($table)->where('id')->eq($id)->fetch('title');
                if($name) $action->appendLink = html::a(helper::createLink($action->objectType, 'view', "id=$id"), "#$id " . $name);
            }
        }
    }

    /**
     * 结束待办Action的extra信息。
     * Finish todo action extra info.
     *
     * @param object $action
     * @access protected
     * @return void
     */
    protected function finishToDoActionExtra(object $action)
    {
        $action->appendLink = '';
        if(strpos($action->extra, ':')!== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            $action->extra    = strtolower($extra);
            if($id)
            {
                $table = $this->config->objectTables[$action->extra];
                $field = $this->config->action->objectNameFields[$action->extra];
                $name  = $this->dao->select($field)->from($table)->where('id')->eq($id)->fetch($field);
                if($name) $action->appendLink = html::a(helper::createLink($action->extra, 'view', "id=$id"), "#$id " . $name);
            }
        }
    }

    /**
     * 执行和项目相关Action的extr信息。
     * Build execution and project action extra info.
     *
     * @param object $action
     * @access protected
     * @return void
     */
    protected function processExecutionAndProjectActionExtra(object $action)
    {
        $this->app->loadLang('execution');
        $linkedProducts = $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->in($action->extra)->fetchPairs('id', 'name');
        $action->extra  = '';
        if($linkedProducts && $this->config->vision == 'rnd')
        {
            foreach($linkedProducts as $productID => $productName) $linkedProducts[$productID] = html::a(helper::createLink('product', 'browse', "productID=$productID"), "#{$productID} {$productName}");
            $action->extra = sprintf($this->lang->execution->action->extra, '<strong>' . join(', ', $linkedProducts) . '</strong>');
        }
    }

    /**
     * 搭建关联用户故事和bug的Action的extra信息。
     * Build link story and bug action extra info.
     *
     * @param object $action
     * @param string $control
     * @param string $method
     * @access protected
     * @return void
     */
    protected function processLinkStoryAndBugActionExtra(object $action, string $control, string $method)
    {
        $extra = '';
        foreach(explode(',', $action->extra) as $id) $extra .= common::hasPriv($control, $method) ? html::a(helper::createLink($control, $method, "{$control}ID=$id"), "#$id ") . ', ' : "#$id, ";
        $action->extra = trim(trim($extra), ',');
    }

    /**
     * 搭建与用户故事相关的Action的extra信息。
     * Build story related action extra info.
     *
     * @param object $action
     * @access protected
     * @return void
     */
    protected function processToStoryActionExtra(object $action)
    {
        $productShadow = $this->dao->select('shadow')->from(TABLE_PRODUCT)->where('id')->in(trim($action->product, ','))->fetch('shadow');
        $title         = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($action->extra)->fetch('title');
        $defaultExtra  = "#$action->extra " . $title;
        if($productShadow)
        {
            $projectID = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($action->extra)->fetch('project');
            if($title) $action->extra = (common::hasPriv('projectstory', 'view') && $projectID) ? html::a(helper::createLink('projectstory', 'view', "storyID={$action->extra}&projectID=$projectID"), $defaultExtra) : $defaultExtra;
        }
        else
        {
            if($title) $action->extra = common::hasPriv('story', 'view') ?  html::a(helper::createLink('story', 'view', "storyID=$action->extra"), $defaultExtra) : $defaultExtra;
        }
    }

    /**
     * 生成用户访问权限检查sql。
     * Build user access check sql.
     *
     * @param  string|int $productID
     * @param  string|int $projectID
     * @param  string|int $executionID
     * @param  array      $executions
     * @access protected
     * @return string
     */
    protected function buildUserAclsSearchCondition(string|int $productID, string|int $projectID, string|int $executionID, array &$executions): string
    {
        /* 验证用户的产品/项目/执行权限。 */
        /* Verify user's product/project/execution permissions。*/
        $aclViews = isset($this->app->user->rights['acls']['views']) ? $this->app->user->rights['acls']['views'] : array();
        if($productID == 'all')   $authedProducts   = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['product'])))   ? $this->app->user->view->products : '0';
        if($projectID == 'all')   $authedProjects   = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['project'])))   ? $this->app->user->view->projects : '0';
        if($executionID == 'all') $authedExecutions = (empty($aclViews) || (!empty($aclViews) && !empty($aclViews['execution']))) ? $this->app->user->view->sprints  : '0';

        if(empty($authedProducts)) $authedProducts = '0';

        /* 组建产品/项目/执行搜索条件。 */
        /* Build product/project/execution search condition. */
        if($productID == 'all' && $projectID == 'all')
        {
            $productCondition = '';
            foreach(explode(',', $authedProducts) as $product) $productCondition = empty($productCondition) ? "(execution = '0' AND project = '0' AND (product LIKE '%,$product,%'" : "$productCondition OR product LIKE '%,$product,%'";
            if(!empty($productCondition)) $productCondition .= '))';

            $projectCondition   = "(execution = '0' AND project != '0' AND project " . helper::dbIN($authedProjects) . ')';
            $executionCondition = isset($authedExecutions) ? "(execution != 0 AND execution " . helper::dbIN($authedExecutions) . ')' : "(execution != 0 AND execution = '$executionID')";
        }
        elseif($productID == 'all' && is_numeric($projectID))
        {
            $products   = $this->loadModel('product')->getProductPairsByProject($projectID);
            $executions = $this->loadModel('execution')->getPairs($projectID) + array(0 => 0);

            $authedExecutions = isset($authedExecutions) ? array_intersect(array_keys($executions), explode(',', $authedExecutions)) : array_keys($executions);

            $productCondition = '';
            foreach(array_keys($products) as $product) $productCondition = empty($productCondition) ? "(execution = '0' AND project = '0' AND (product LIKE '%,$product,%'" : "$productCondition OR product LIKE '%,$product,%'";
            if(!empty($productCondition)) $productCondition .= '))';

            $projectCondition   = "(execution = '0' AND project = '$projectID')";
            $executionCondition = "(execution != '0' AND execution " . helper::dbIN($authedExecutions) . ')';
        }
        elseif(is_numeric($productID) && $projectID == 'all')
        {
            $this->loadModel('product');
            $projects   = $this->product->getProjectPairsByProduct($productID);
            $executions = $this->product->getExecutionPairsByProduct($productID) + array(0 => 0);

            $authedProjects   = array_intersect(array_keys($projects), explode(',', $authedProjects));
            $authedExecutions = isset($authedExecutions) ? array_intersect(array_keys($executions), explode(',', $authedExecutions)) : array_keys($executions);

            $productCondition   = "(execution = '0' AND project = '0' AND product LIKE '%,$productID,%')";
            $projectCondition   = "(execution = '0' AND project != '0' AND project " . helper::dbIN($authedProjects) . ')';
            $executionCondition = "(execution != '0' AND execution " . helper::dbIN($authedExecutions) . ')';
        }

        $condition = "((product =',0,' OR product = '0' OR product=',,') AND project = '0' AND execution = '0')";
        if(!empty($productCondition))   $condition .= " OR $productCondition";
        if(!empty($projectCondition))   $condition .= " OR $projectCondition";
        if(!empty($executionCondition)) $condition .= " OR $executionCondition";

        return $condition;
    }

    /**
     * 处理effort相关查询条件。
     * Process effort related search condition.
     *
     * @param  string $condition
     * @access protected
     * @return void
     */
    public function processEffortCondition(string &$condition)
    {
        $efforts = $this->dao->select('id')->from(TABLE_EFFORT)->where($condition)->fetchPairs();
        $efforts = !empty($efforts) ? implode(',', $efforts) : 0;

        $condition .= " OR (`objectID` IN ({$efforts}) AND `objectType` = 'effort')";
    }

    /**
     * 处理项目集相关查询条件。
     * Process program related search condition.
     *
     * @param  string $condition
     * @access protected
     * @return void
     */
    public function processProgramCondition(string &$condition)
    {
        $programCondition = $this->app->user->view->programs ? : '0';

        $condition .= " or (`objectid` in ({$programCondition}) and `objecttype` = 'program')";
    }

    /**
     * 处理多产品执行相关查询条件。
     * Process multiple product execution related search condition.
     *
     * @param  string $condition
     * @access protected
     * @return void
     */
    public function processNoMultipleExecutionsConditon(string &$condition)
    {
        $noMultipleExecutions = $this->dao->select('id')->from(TABLE_PROJECT)->where('multiple')->eq(0)->andWhere('type')->in('sprint,kanban')->fetchPairs('id', 'id');

        if($noMultipleExecutions)
        {
            $condition .= count($noMultipleExecutions) > 1 ? " OR (`objectID` NOT " . helper::dbIN($noMultipleExecutions) . " AND `objectType` = 'execution')" : ' OR (`objectID` !' . helper::dbIN($noMultipleExecutions) . " AND `objectType` = 'execution')";
        }
    }

    /**
     * 通过条件获取操作记录列表。
     * Get action list by condition.
     *
     * @param  string $condition
     * @param  string $date
     * @param  string $period
     * @param  string $begin
     * @param  string $end
     * @param  string $direction
     * @param  string $account
     * @param  string $beginDate
     * @param  string $productID
     * @param  string $projectID
     * @param  string $executionID
     * @param  array  $executions
     * @param  string $actionCondition
     * @param  string $orderBy
     * @param  object $pager
     * @access protected
     * @return array|bool
     */
    protected function getActionListByCondition(string $condition, string $date, string $period, string $begin, string $end, string $direction, string $account, string $beginDate, string $productID, string $projectID, string $executionID, array $executions, string $actionCondition, string $orderBy, object $pager = null): array|bool
    {
        return $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->notIN($this->config->action->ignoreObjectType4Dynamic)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($period != 'all')->andWhere('date')->gt($begin)->fi()
            ->beginIF($period != 'all')->andWhere('date')->lt($end)->fi()
            ->beginIF($date)->andWhere('date' . ($direction == 'next' ? '<' : '>') . "'{$date}'")->fi()
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF($beginDate)->andWhere('date')->ge($beginDate)->fi()
            ->beginIF(is_numeric($productID))->andWhere('product')->like("%,$productID,%")->fi()
            ->andWhere('1=1', true)
            ->beginIF(is_numeric($projectID))->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!empty($executions))->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF(is_numeric($executionID))->andWhere('execution')->eq($executionID)->fi()
            ->markRight(1)
            /* lite模式下需要排除的一些类型。 */
            /* Types excluded from Lite. */
            ->beginIF($this->config->vision == 'lite')->andWhere('objectType')->notin('product')->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('objectType')->notin('program')->fi()
            ->beginIF($productID == 'notzero')->andWhere('product')->gt(0)->andWhere('product')->notlike('%,0,%')->fi()
            ->beginIF($projectID == 'notzero')->andWhere('project')->gt(0)->fi()
            ->beginIF($executionID == 'notzero')->andWhere('execution')->gt(0)->fi()
            ->andWhere($condition)
            ->beginIF($actionCondition)->andWhere("($actionCondition)")->fi()
            /* 过滤客户端的登陆登出操作。 */
            /* Filter out client login/logout actions. */
            ->andWhere('action')->notin('disconnectxuanxuan,reconnectxuanxuan,loginxuanxuan,logoutxuanxuan,editmr,removemr')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 检查Action是否合法。
     * Check if action is legal.
     *
     * @param  object $action
     * @param  array  $shadowProducts
     * @param  array  $docList
     * @param  array  $apiList
     * @param  array  $docLibList
     * @access protected
     * @return bool
     */
    protected function checkIsActionLegal(object $action, array $shadowProducts, array $docList, array $apiList, array $docLibList): bool
    {
        if($action->objectType == 'doc' && !isset($docList[$action->objectID])) return false;
        if($action->objectType == 'api' && !isset($apiList[$action->objectID])) return false;
        if($action->objectType == 'doclib' && !isset($docLibList[$action->objectID])) return false;
        if($action->objectType == 'product' && isset($shadowProducts[$action->objectID])) return false;

        return true;
    }

    /**
     * 为Action添加对象名称。
     * Add object name for action.
     *
     * @param  object $action
     * @param  array  $objectNames
     * @access protected
     * @return void
     */
    protected function AddObjectNameForAction(object $action, array $objectNames)
    {
        $action->objectName = isset($objectNames[$action->objectType][$action->objectID]) ? $objectNames[$action->objectType][$action->objectID] : '';

        if($action->objectType == 'program' && strpos('syncexecution,syncproject,syncprogram', $action->action) !== false)
        {
            $action->objectName .= $this->lang->action->label->startProgram;
        }
        elseif($action->objectType == 'branch' && $action->action == 'mergedbranch')
        {
            if($action->objectID == 0) $action->objectName = $this->lang->branch->main;
            $action->objectName = '"' . $action->extra . ' "' . $this->lang->action->to . ' "' . $action->objectName . '"';
        }
        elseif($action->objectType == 'user')
        {
            $user = $this->dao->select('id,realname')->from(TABLE_USER)->where('id')->eq($action->objectID)->fetch();
            if($user) $action->objectName = $user->realname;
        }
        elseif($action->objectType == 'kanbancard' && strpos($action->action, 'imported') !== false && $action->action != 'importedcard')
        {
            $objectType  = str_replace('imported', '', $action->action);
            $objectTable = zget($this->config->objectTables, $objectType);
            $objectName  = ($objectType == 'productplan' || $objectType == 'ticket') ? 'title' : 'name';
            $action->objectName = $this->dao->select($objectName)->from($objectTable)->where('id')->eq($action->extra)->fetch($objectName);
        }
        elseif(strpos(',module,chartgroup,', ",$action->objectType,") !== false && !empty($action->extra) && $action->action != 'deleted')
        {
            $modules = $this->dao->select('id,name')->from(TABLE_MODULE)->where('id')->in(explode(',', $action->extra))->fetchPairs('id');
            $action->objectName = implode(',', $modules);
        }
        elseif($action->objectType == 'mr' && $action->action == 'deleted')
        {
            $action->objectName = $action->extra;
        }
        elseif($action->objectType == 'pivot')
        {
            $pivotNames = json_decode($action->objectName, true);
            $action->objectName = zget($pivotNames, $this->app->getClientLang(), '');
            if(empty($action->objectName))
            {
                $pivotNames = array_filter($pivotNames);
                $action->objectName = reset($pivotNames);
            }
        }
    }

    /**
     * 旗舰版处理资产库的链接。
     * Process doc link for max.
     *
     * @param  object $action
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $vars
     * @access protected
     * @return void
     */
    protected function processMaxDocObjectLink(object $action, string $moduleName, string $methodName, string $vars)
    {
        if($action->objectType == 'doc')
        {
            $assetLibType = $this->dao->select('assetLibType')->from(TABLE_DOC)->where('id')->eq($action->objectID)->fetch('assetLibType');
            if($assetLibType) $method = $assetLibType == 'practice' ? 'practiceView' : 'componentView';
        }
        else
        {
            $method = $this->config->action->assetViewMethod[$action->objectType];
        }

        isset($method) ? $action->objectLink = helper::createLink('assetlib', $method, sprintf($vars, $action->objectID)) : helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID));
    }

    /**
     * 获取文档库链接参数。
     * Get doclib link params.
     *
     * @param  object $action
     * @access protected
     * @return array|bool
     */
    protected function getDocLibLinkParameters(object $action)
    {
        $libID = $action->objectID;
        $type  = 'custom';
        if(!empty($action->project))   $type = 'project';
        if(!empty($action->execution)) $type = 'execution';
        if(!empty($action->product))   $type = 'product';

        $libObjectID = $type != 'custom' ? $action->$type : '';
        $libObjectID = trim($libObjectID, ',');
        if(empty($libObjectID) && $type != 'custom') return false;

        return array($type, $libID, $libObjectID);
    }

    /**
     * 获取文档库类型参数。
     * Get doclib type params.
     *
     * @param  object $action
     * @access protected
     * @return array
     */
    protected function getDoclibTypeParams(object $action): array
    {
        $params = '';
        $docLib           = $this->dao->select('type,product,project,execution,deleted')->from(TABLE_DOCLIB)->where('id')->eq($action->objectID)->fetch();
        $docLib->objectID = in_array($docLib->type, array('product', 'project', 'execution')) ? $docLib->{$docLib->type} : 0;
        $appendLib        = $docLib->deleted == '1' ? $action->objectID : 0;
        if($docLib->type == 'api')
        {
            $moduleName = 'api';
            $methodName = 'index';
            $params = "libID={$action->objectID}&moduleID=0&apiID=0&version=0&release=0&appendLib={$appendLib}";
            if(!empty($docLib->project) || !empty($docLib->product))
            {
                $moduleName = 'doc';
                if(!empty($docLib->product))
                {
                    $objectID   = $docLib->product;
                    $methodName = 'productspace';
                }

                if(!empty($docLib->project))
                {
                    $objectID   = $docLib->project;
                    $methodName = 'projectspace';
                }
                $params = "objectID={$objectID}&libID={$action->objectID}";
            }
        }
        else
        {
            $moduleName = 'doc';
            $methodName = zget($this->config->doc->spaceMethod, $docLib->type, 'tablecontents');
            if($methodName == 'myspace') $params = "type=mine&libID={$action->objectID}";
            if(!in_array($methodName, array('myspace', 'tablecontents'))) $params = "objectID={$docLib->objectID}&libID={$action->objectID}";
        }

        return array($moduleName, $methodName, $params);
    }

    /**
     * 检查Action是否可点击。
     * Check if action is clickable.
     *
     * @param  object $action
     * @param  array  $deptUsers
     * @param  string $moduleName
     * @param  string $methodName
     * @access protected
     * @return bool
     */
    protected function checkActionClickable(object $action, array $deptUsers, string $moduleName, string $methodName): bool
    {
        if(empty($moduleName) || empty($methodName)) return false;
        if(!common::hasPriv($moduleName, $methodName)) return false;

        if($action->objectType == 'user' && !isset($deptUsers[$action->objectID]) && !$this->app->user->admin) return false;
        if($action->objectType == 'user' && ($action->action == 'login' || $action->action == 'logout')) return false;
        if($action->objectType == 'user')
        {
            $user = $this->dao->select('deleted')->from(TABLE_USER)->where('id')->eq($action->objectID)->fetch();
            if($user->deleted == '1') return false;
        }
        if($action->objectType == 'todo')
        {
            $todo = $this->dao->select('*')->from(TABLE_TODO)->where('id')->eq($action->objectID)->fetch();

            if($todo->private == 1 && $todo->account != $this->app->user->account) return false;
        }

        if($action->objectType == 'stakeholder' && $action->project == 0) return false;
        if($action->objectType == 'chartgroup') return false;
        if($action->objectType == 'branch' && $action->action == 'mergedbranch') return false;

        return true;
    }

    /**
     * 获取对象链接参数。
     * Get object link params.
     *
     * @param  object $action
     * @param  string $vars
     * @access protected
     * @return string
     */
    protected function getObjectLinkParams(object $action, string $vars): string
    {
        if($action->objectType == 'api')
        {
            $api    = $this->dao->select('id,lib,module')->from(TABLE_API)->where('id')->eq($action->objectID)->fetch();
            $params = sprintf($vars, $api->lib, $api->id, $api->module);
        }
        elseif($action->objectType == 'branch' || ($action->objectType == 'module' && $action->action == 'deleted'))
        {
            $params = sprintf($vars, trim($action->product, ','));
        }
        elseif($action->objectType == 'kanbanspace')
        {
            $kanbanSpace = $this->dao->select('type')->from(TABLE_KANBANSPACE)->where('id')->eq($action->objectID)->fetch();
            $params      = sprintf($vars, $kanbanSpace->type);
        }
        elseif($action->objectType == 'kanbancard')
        {
            $table    = $this->config->objectTables[$action->objectType];
            $kanbanID = $this->dao->select('kanban')->from($table)->where('id')->eq($action->objectID)->fetch('kanban');
            $params   =  sprintf($vars, $kanbanID);
        }
        else
        {
            $params = sprintf($vars, $action->objectID);
        }

        return $params;
    }

    /**
     * 根据对象类型获取恢复对象参数。
     * Get object params by object type.
     *
     * @param  string    $objectType
     * @access protected
     * @return array
     */
    protected function getUndeleteParamsByObjectType(string $objectType): array
    {
        $table   = $this->config->objectTables[$objectType];
        $orderby = '';
        $field   = '*';
        switch($objectType)
        {
            case 'product':
                $field = 'id, name, code, acl';
                break;
            case 'program':
            case 'project':
                $field = 'id, acl, name, hasProduct';
                break;
            case 'doc':
                $table   = TABLE_DOCCONTENT;
                $orderby = 'version desc';
            default:
                break;
       }
        return array($table, $orderby, $field);
    }
}
