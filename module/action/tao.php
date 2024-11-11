<?php
declare(strict_types=1);
class actionTao extends actionModel
{
    /**
     * 获取一个action的基础数据。
     * Fetch base info of a action.
     *
     * @param  int         $actionID
     * @access public
     * @return object|bool
     */
    public function fetchBaseInfo(int $actionID): object|bool
    {
        return $this->dao->select('*')->from(TABLE_ACTION)->where('id')->eq($actionID)->fetch();
    }

    /**
     * 获取一个基础对象的信息。
     * Get object base info.
     *
     * @param  string      $table
     * @param  array       $queryParam
     * @param  string      $field
     * @param  string      $orderby
     * @access public
     * @return object|bool
     */
    public function getObjectBaseInfo(string $table, array $queryParam, string $field = '*', string $orderby = ''): object|bool
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
     * @access public
     * @return array
     */
    public function getNoFilterRequiredRelation(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;
        switch($objectType)
        {
            case 'product':
                $product = array($objectID);
                break;
            case 'project':
            case 'execution':
                $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($objectID)->fetchPairs();
                if($products) $product = $products;
                ${$objectType} = $objectID;

                if($objectType == 'execution')
                {
                    $project = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($objectID)->fetch('project');
                    if(!$project) $project = 0;
                }
                break;
            case 'marketresearch':
                $project = $objectID;
                break;
        }

        return array($product, $project, $execution);
    }

    /**
     * 获取对象的产品项目以及执行。
     * Get product, project, execution of the object.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  string $actionType
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getNeedRelatedFields(string $objectType, int $objectID, string $actionType = '', string $extra = ''): array
    {
        $product = array(0);
        $project = $execution = 0;
        switch($objectType)
        {
            case 'story':
                list($product, $project, $execution) = $this->getStoryActionRelated($objectType, $objectID, (int)$extra);
            case 'productplan':
            case 'branch':
                $product = $objectID == 0 ? $extra : $this->dao->select('product')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch('product');
                break;
            case 'testcase':
            case 'case':
                list($product, $project, $execution) = $this->getCaseRelated($objectType, $actionType, $objectID, (int)$extra);
                break;
            case 'repo':
            case 'kanbanlane':
                $execution = $this->dao->select('execution')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch('execution');
                break;
            case 'release':
                list($product, $project) = $this->getReleaseRelated($objectType, $objectID);
                break;
            case 'task':
                list($product, $project, $execution) = $this->getTaskRelated($objectType, $objectID);
                break;
            case 'kanbancolumn':
                $execution = $extra;
                break;
            case 'team':
                $type = $this->dao->select('type')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('type');
                if($type != 'project') $type = 'execution';
                ${$type} = $objectID;
                break;
            case 'whitelist':
                if($extra == 'product' || $extra == 'project') ${$extra} = $objectID;
                if($extra == 'sprint' || $extra == 'stage') $execution = $objectID;
                break;
            case 'module':
                $moduleID = $actionType != 'deleted' ? (int)$extra : (int)$objectID;
                $module   = $this->dao->select('type,root')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
                if(!empty($module) && $module->type == 'story') $product = array($module->root);
                break;
            case 'review':
                list($product, $project) = $this->getReviewRelated($objectType, $objectID);
                break;
            default:
                list($product, $project, $execution) = $this->getGenerateRelated($objectType, $objectID);
                break;
        }
        return array($product, $project, $execution);
    }

    /**
     * 获取用户故事相关的产品、项目、阶段。
     * Get story related product, project, stage.
     *
     * @param  string $actionType
     * @param  int    $objectID
     * @param  int    $extra
     * @access public
     * @return array
     */
    public function getStoryActionRelated(string $actionType, int $objectID, int $extra): array
    {
        $product = array(0);
        $project = $execution = 0;
        switch($actionType)
        {
            case 'linked2build':
            case 'unlinkedfrombuild':
                $build = $this->dao->select('project,execution')->from(TABLE_BUILD)->where('id')->eq($extra)->fetch();
                if($build)
                {
                    $project   = $build->project;
                    $execution = $build->execution;
                }
                break;
            case 'estimated':
                $project   = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($extra)->fetch('project');
                $execution = (int)$extra;
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
     * @access public
     * @return array
     */
    public function getCaseRelated(string $objectType, string $actionType, int $objectID, int $extra): array
    {
        list($product, $project, $execution) = $this->getGenerateRelated($objectType, $objectID);

        if(in_array($actionType, array('linked2testtask', 'unlinkedfromtesttask', 'assigned', 'run')) && $extra)
        {
            $testtask  = $this->dao->select('project,execution')->from(TABLE_TESTTASK)->where('id')->eq($extra)->fetch();
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
     * @access public
     * @return array
     */
    public function getGenerateRelated(string $objectType, int $objectID): array
    {
        $product = $project = $execution = 0;
        $result  = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        if($result)
        {
            $product   = zget($result, 'product', 0);
            $project   = zget($result, 'project', 0);
            $execution = zget($result, 'execution', 0);
        }
        return array(array($product), $project, $execution);
    }

    /**
     * 获取分支相关的产品、项目、执行。
     * Get case related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getReleaseRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = 0;
        $result  = $this->dao->select('product, build')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        if($result)
        {
            $product = $result->product;
            $project = $this->dao->select('project')->from(TABLE_BUILD)->where('id')->in($result->build)->fetch('project');
        }
        return array(array($product), $project);
    }

    /**
     * 获取任务相关的产品、项目、执行。
     * Get task related product, project, execution.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @access public
     * @return array
     */
    public function getTaskRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = $execution = 0;
        $result  = $this->dao->select('project,execution,story')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        if($result)
        {
            $table    = $result->story != 0 ? TABLE_STORY : TABLE_PROJECTPRODUCT;
            $field    = $result->story != 0 ? 'id' : 'project';
            $value    = $result->story != 0 ? $result->story : $result->execution;
            $products = $this->dao->select('product')->from($table)->where($field)->eq($value)->fetchPairs();
            if($products) $product = $products;

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
     * @access public
     * @return array
     */
    public function getReviewRelated(string $objectType, int $objectID): array
    {
        $product = array(0);
        $project = 0;
        $result  = $this->dao->select('*')->from($this->config->objectTables[$objectType])->where('id')->eq($objectID)->fetch();
        if($result)
        {
            $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($result->project)->fetchPairs();
            if($products) $product = $products;
            $project = zget($result, 'project', 0);
        }
        return array($product, $project);
    }

    /**
     * 根据类型和ID获取操作记录列表。
     * Get action list by type and ID.
     *
     * @param  string $objectType
     * @param  int    $objectID
     * @param  array  $modules
     * @access public
     * @return array
     */
    public function getActionListByTypeAndID(string $objectType, array|int $objectID, array $modules): array
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
            ->beginIF($objectType == 'case' || $objectType == 'testcase')
            ->where('objectType')->in('case,testcase')
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->beginIF($objectType == 'module')
            ->where('objectType')->eq($objectType)
            ->andWhere('((action')->ne('deleted')->andWhere('objectID')->in($objectID)->markRight(1)
            ->orWhere('(action')->eq('deleted')->andWhere('objectID')->in($modules)->markRight(1)->markRight(1)
            ->fi()
            ->beginIF(!in_array($objectType, array('project', 'case', 'testcase', 'story', 'module')))
            ->where('objectType')->eq($objectType)
            ->andWhere('objectID')->in($objectID)
            ->fi()
            ->orderBy('date, id')
            ->fetchAll('id');
    }

    /**
     * 获取 linked 和 unlinked 操作记录的附加信息。
     * Get action extra info.
     *
     * @param  object $action
     * @param  string $type
     * @access public
     * @return bool
     */
    public function getLinkedExtra(object $action, string $type): bool
    {
        if($type == 'plan')     $type = 'productplan';
        if($type == 'revision') $type = 'repohistory';
        if($type == 'bug')      $type = 'build';
        if($type == 'roadmap' && $action->objectType == 'story') $type = 'roadmap';
        $table = zget($this->config->objectTables, $type, '');
        if(empty($table)) return false;

        $method = 'view';
        if(in_array($type, array('execution', 'kanban')))
        {
            $execution = $this->fetchObjectInfoByID(TABLE_EXECUTION, (int)$action->extra, 'name, type, multiple');
            if(!$execution) return false;
            if($execution->type != 'project' && empty($execution->multiple)) return false;

            $name = $execution->name;
            if($execution->type == 'kanban') $method = 'kanban';
            if($name)
            {
                $isModalKanban = $method == 'kanban' && isonlybody();
                $canShowLink   = common::hasPriv('execution', $method) && !$isModalKanban && $this->config->vision != 'or';
                $action->extra = $canShowLink ? html::a(helper::createLink('execution', $method, "executionID={$action->execution}"), $name, '', "data-app='execution'") : $name;
            }
        }
        elseif($type == 'project')
        {
            $project = $this->fetchObjectInfoByID($table, (int)$action->extra, 'name, model, multiple');
            if(!$project) return false;

            $name = $project->name;
            if($project->model == 'kanban') $method = $project->multiple ? 'kanban' : 'index';
            if($name) $action->extra = (common::hasPriv('project', $method) and $this->config->vision != 'or') ? html::a(helper::createLink('project', $method, "projectID=$action->project"), $name, '_self', in_array($method, array('view', 'index')) ? 'data-app="project"' : '') : $name;
        }
        elseif($type == 'plan' || $type == 'productplan')
        {
            $plan = $this->fetchObjectInfoByID($table, (int)$action->extra, 'title');
            if($plan && $plan->title) $action->extra = common::hasPriv('productplan', 'view') && $this->config->vision != 'or' ? html::a(helper::createLink('productplan', $method, "planID={$action->extra}"), $plan->title) : $plan->title;
        }
        elseif(in_array($type, array('build', 'bug', 'release', 'testtask', 'roadmap')))
        {
            $object = $this->fetchObjectInfoByID($table, (int)$action->extra, 'name');
            if($object && $object->name) $action->extra = common::hasPriv($type, $method) ? html::a(helper::createLink($type, $method, $this->processParamString($action, $type)), $object->name) : $object->name;
        }
        elseif($type == 'revision')
        {
            $commit = $this->fetchObjectInfoByID($table, $action->extra, 'repo, revision');
            if($commit)
            {
                $revision = substr($commit->revision, 0, 10);
                $action->extra = common::hasPriv('repo', 'revision') ? html::a(helper::createLink('repo', 'revision', "repoID={$commit->repo}&objectID=0&revision={$commit->revision}"), $revision) : $revision;
            }
        }
        return true;
    }

    /**
     * 通过ID获取对象信息。
     * Get object info by ID.
     *
     * @param  string      $table
     * @param  int         $objectID
     * @access public
     * @return object|bool
     */
    public function fetchObjectInfoByID(string $table, int $objectID, string $field = '*'): object|bool
    {
        return $this->dao->select($field)->from($table)->where('id')->eq($objectID)->fetch();
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
     * @param  bool   $addlink
     * @access public
     * @return void
     */
    public function processActionExtra(string $table, object $action, string $fields, string $type, string $method = 'view', bool $onlyBody = false, bool $addLink = true): void
    {
        $object = $this->fetchObjectInfoByID($table, (int)$action->extra, $fields);
        $condition = common::hasPriv($type, $method);
        if($onlyBody) $condition = $condition && !isonlybody();
        if($object && $object->{$fields})
        {
            $misc = '';
            if($action->objectType == 'bug' && $action->action == 'converttotask' && $type == 'task' && $method == 'view')
            {
                $misc = "data-app='execution'";
                $isMultipleProject = $this->dao->select('multiple')->from(TABLE_PROJECT)->where('id')->eq($action->project)->fetch('multiple');
                if(!$isMultipleProject) $misc = "data-app='project'";
            }
            $action->extra = $condition && $addLink ? html::a(helper::createLink($type, $method, $this->processParamString($action, $type)), "#{$action->extra} " . $object->{$fields}, '', $misc) : "#{$action->extra} " . $object->{$fields};
        }
    }

    /**
     * 处理需求层级。
     * Process story grade.
     *
     * @param  object $action
     * @access public
     * @return object
     */
    public function processStoryGradeActionExtra($action): object
    {
        $action->extra = $this->dao->select('t1.name')->from(TABLE_STORYGRADE)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.grade = t2.grade AND t1.type = t2.type')
            ->where('t2.id')->eq($action->objectID)
            ->fetch('name');

        return $action;
    }

    /**
     * 处理属性。
     * Process attribute.
     *
     * @param  string $type
     * @access public
     * @return string
     */
    public function processAttribute($type): string
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
     * @access public
     * @return string
     */
    public function processParamString(object $action, string $type): string
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
            case 'story':
                $paramString = "{$type}ID={$action->extra}";
                break;
            case 'task':
            case 'story':
            case 'charter':
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
            case 'roadmap':
                $paramString = "roadmapID={$action->extra}";
                break;
            case 'assetlib':
                $paramString = "libID={$action->extra}";
                break;
            case 'demand':
                $paramString = "demandID={$action->extra}";
                break;
            case 'epic':
                $paramString = "storyID={$action->extra}";
                break;
        }

        return $paramString;
    }

    /**
     * 搭建创建子任务的Action的extra信息。
     * Build create children action extra info.
     *
     * @param  object $action
     * @access public
     * @return void
     */
    public function processCreateChildrenActionExtra(object $action): void
    {
        $names = $this->dao->select('id,name')->from(TABLE_TASK)->where('id')->in($action->extra)->fetchPairs();
        $action->extra = '';
        if($names)
        {
            foreach($names as $id => $name) $action->extra .= common::hasPriv('task', 'view') ? html::a(helper::createLink('task', 'view', "taskID={$id}"), "#{$id} " . $name) . ', ' : "#{$id} " . $name . ', ';
        }
        $action->extra = trim(trim($action->extra), ',');
    }

    /**
     * 搭建创建需求的Action的extra信息。
     * Build create requirement action extra info.
     *
     * @param  object $action
     * @access public
     * @return void
     */
    public function processCreateRequirementsActionExtra(object $action): void
    {
        $names = $this->dao->select('id,title')->from(TABLE_STORY)->where('id')->in($action->extra)->fetchPairs();
        $action->extra = '';
        if($names)
        {
            foreach($names as $id => $name) $action->extra .= common::hasPriv('requriement', 'view') ? html::a(helper::createLink('story', 'view', "storyID={$id}"), "#{$id} " . $name) . ', ' : "#{$id} " . $name . ', ';
        }
        $action->extra = trim(trim($action->extra), ',');
    }

    /**
     * 通过 extra 获取对象的 appendLink。
     * Get object appendLink by extra.
     *
     * @param  object $action
     * @access public
     * @return void
     */
    public function processAppendLinkByExtra(object $action): void
    {
        $action->appendLink = '';
        if(strpos($action->extra, '|') !== false) $action->extra = substr($action->extra, 0, strpos($action->extra, '|'));
        if(strpos($action->extra, ':') !== false)
        {
            list($extra, $id) = explode(':', $action->extra);
            if($id)
            {
                $extra  = strtolower($extra);
                $module = $action->objectType == 'todo' ? $extra : $action->objectType;
                $table  = $this->config->objectTables[$module];
                $field  = $this->config->action->objectNameFields[$module];
                $name   = $this->dao->select($field)->from($table)->where('id')->eq($id)->fetch($field);
                if($name) $action->appendLink = html::a(helper::createLink($module, 'view', "id={$id}"), "#{$id} " . $name);
            }
            $action->extra = $extra;
        }
    }

    /**
     * 搭建关联用户故事和bug的Action的extra信息。
     * Build link story and bug action extra info.
     *
     * @param  object $action
     * @param  string $module
     * @param  string $method
     * @access public
     * @return void
     */
    public function processLinkStoryAndBugActionExtra(object $action, string $module, string $method): void
    {
        $extra = '';
        foreach(explode(',', $action->extra) as $id) $extra .= common::hasPriv($module, $method) ? html::a(helper::createLink($module, $method, "{$module}ID={$id}"), "#{$id} ", '', "data-size='lg' data-toggle='modal'") . ', ' : "#{$id}, ";
        $action->extra = trim(trim($extra), ',');
    }

    /**
     * 搭建与用户故事相关的Action的extra信息。
     * Build story related action extra info.
     *
     * @param  object $action
     * @access public
     * @return void
     */
    public function processToStoryActionExtra(object $action): void
    {
        $productShadow = $this->dao->select('shadow')->from(TABLE_PRODUCT)->where('id')->in(trim($action->product, ','))->fetch('shadow');
        $title         = $this->dao->select('title')->from(TABLE_STORY)->where('id')->eq($action->extra)->fetch('title');
        $defaultExtra  = "#{$action->extra} {$title}";
        if($productShadow)
        {
            $projectID = $this->dao->select('project')->from(TABLE_PROJECTSTORY)->where('story')->eq($action->extra)->fetch('project');
            if($title) $action->extra = common::hasPriv('projectstory', 'view') && $projectID ? html::a(helper::createLink('projectstory', 'view', "storyID={$action->extra}&projectID={$projectID}"), $defaultExtra) : $defaultExtra;
        }
        else
        {
            if($title) $action->extra = common::hasPriv('story', 'view') ? html::a(helper::createLink('story', 'view', "storyID={$action->extra}"), $defaultExtra) : $defaultExtra;
        }
    }

    /**
     * 通过条件获取操作记录列表。
     * Get action list by condition.
     *
     * @param  string     $condition
     * @param  string     $date
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $account
     * @param  string|int $productID
     * @param  string|int $projectID
     * @param  string|int $executionID
     * @param  array      $executions
     * @param  string     $actionCondition
     * @param  string     $orderBy
     * @param  int        $limit
     * @access public
     * @return array|bool
     */
    public function getActionListByCondition(string $condition, string $date, string $begin, string $end, string $account, string|int $productID, string|int $projectID, string|int $executionID, array|string $executions, string $actionCondition, string $orderBy, int $limit = 50): array|bool
    {
        /* 获取最近一个月的动态用actionrecent表。 */
        $lastMonth   = date('Y-m-d', strtotime('-1 month'));
        $actionTable = ($begin >= $lastMonth && $end >= $lastMonth) ? TABLE_ACTIONRECENT : TABLE_ACTION;

        return $this->dao->select('*')->from($actionTable)
            ->where('objectType')->notIN($this->config->action->ignoreObjectType4Dynamic)
            ->andWhere('action')->notIN($this->config->action->ignoreActions4Dynamic)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($begin != EPOCH_DATE)->andWhere('date')->ge($begin)->fi()
            ->beginIF($end != FUTURE_DATE)->andWhere('date')->le($end)->fi()
            ->beginIF($account != 'all')->andWhere('actor')->eq($account)->fi()
            ->beginIF(is_numeric($productID) && $productID)->andWhere('product')->like("%,$productID,%")->fi()
            ->beginIF(is_numeric($projectID) && $projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!empty($executions))->andWhere('execution')->in(array_keys($executions))->fi()
            ->beginIF(is_numeric($executionID) && $executionID)->andWhere('execution')->eq($executionID)->fi()
            /* lite模式下需要排除的一些类型。 */
            /* Types excluded from Lite. */
            ->beginIF($this->config->vision == 'lite')->andWhere('objectType')->notin('product')->fi()
            ->beginIF($this->config->systemMode == 'light')->andWhere('objectType')->notin('program')->fi()
            ->beginIF($productID === 'notzero')->andWhere('product')->gt(0)->andWhere('product')->notlike('%,0,%')->fi()
            ->beginIF($projectID === 'notzero')->andWhere('project')->gt(0)->fi()
            ->beginIF($executionID === 'notzero')->andWhere('execution')->gt(0)->fi()
            ->andWhere($condition)
            ->beginIF($actionCondition)->andWhere("($actionCondition)")->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * 根据条件获取动态表。
     * Get action table by condition.
     *
     * @param  string $period
     * @access public
     * @return string
     */
    public function getActionTable(string $period): string
    {
        return in_array($period, $this->config->action->latestDateList) ? TABLE_ACTIONRECENT : TABLE_ACTION;
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
     * @access public
     * @return bool
     */
    public function checkIsActionLegal(object $action, array $shadowProducts, array $docList, array $apiList, array $docLibList): bool
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
     * @param  string $objectType
     * @access public
     * @return void
     */
    public function addObjectNameForAction(object $action, array $objectNames, string $objectType)
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
        elseif($action->objectType == 'aiassistant')
        {
            $action->objectName = $this->dao->select('name')->from(TABLE_AI_ASSISTANT)->where('id')->eq($action->objectID)->fetch('name');
        }
        if (empty($action->objectName) && preg_match('/^(gitlab|gitea|gogs|mr)/', $objectType)) $action->objectName = $action->extra;
    }

    /**
     * 旗舰版处理资产库的链接。
     * Process doc link for max.
     *
     * @param  object $action
     * @param  string $moduleName
     * @param  string $methodName
     * @param  string $vars
     * @access public
     * @return void
     */
    public function processMaxDocObjectLink(object $action, string $moduleName, string $methodName, string $vars)
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

        $action->objectLink = isset($method) ? helper::createLink('assetlib', $method, sprintf($vars, $action->objectID)) : helper::createLink($moduleName, $methodName, sprintf($vars, $action->objectID));
        $action->hasLink    = true;
    }

    /**
     * 获取文档库链接参数。
     * Get doclib link params.
     *
     * @param  object     $action
     * @access public
     * @return array|bool
     */
    public function getDocLibLinkParameters(object $action)
    {
        $libID = $action->objectID;
        $type  = 'custom';
        if(!empty($action->project))   $type = 'project';
        if(!empty($action->execution)) $type = 'execution';
        if(!empty($action->product))   $type = 'product';

        $libObjectID = $type != 'custom' ? $action->{$type} : '';
        $libObjectID = trim($libObjectID, ',');
        if(empty($libObjectID) && $type != 'custom') return false;

        return array($type, $libID, $libObjectID);
    }

    /**
     * 获取文档库类型参数。
     * Get doclib type params.
     *
     * @param  object $action
     * @access public
     * @return array
     */
    public function getDoclibTypeParams(object $action): array
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
     * @access public
     * @return bool
     */
    public function checkActionClickable(object $action, array $deptUsers, string $moduleName, string $methodName): bool
    {
        if(empty($moduleName) || empty($methodName)) return false;
        if(!common::hasPriv($moduleName, $methodName)) return false;

        if($action->objectType == 'user' && !isset($deptUsers[$action->objectID]) && !$this->app->user->admin) return false;
        if($action->objectType == 'user' && ($action->action == 'login' || $action->action == 'logout')) return false;
        if($action->objectType == 'user')
        {
            $user = $this->dao->select('deleted')->from(TABLE_USER)->where('id')->eq($action->objectID)->fetch();
            if($user && $user->deleted == '1') return false;
        }
        if($action->objectType == 'todo')
        {
            $todo = $this->dao->select('*')->from(TABLE_TODO)->where('id')->eq($action->objectID)->fetch();

            if($todo && $todo->private == 1 && $todo->account != $this->app->user->account) return false;
        }

        if($action->objectType == 'mr' && (empty($action->objectName) || $action->action == 'deleted')) return false;
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
     * @access public
     * @return string
     */
    public function getObjectLinkParams(object $action, string $vars): string
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
        elseif($action->objectType == 'module' && $action->action == 'undeleted')
        {
            $table    = $this->config->objectTables[$action->objectType];
            $moduleID = $this->dao->select('root')->from($table)->where('id')->eq($action->objectID)->fetch('root');
            $params = sprintf($vars, $moduleID);
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
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getUndeleteParamsByObjectType(string $objectType): array
    {
        $table    = $this->config->objectTables[$objectType];
        $orderby  = '';
        $field    = '*';
        $queryKey = 'id';
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
                $table    = TABLE_DOCCONTENT;
                $orderby  = 'version desc';
                $queryKey = 'doc';
            default:
                break;
        }
        return array($table, $orderby, $field, $queryKey);
    }
}
