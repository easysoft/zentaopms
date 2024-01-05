<?php
declare(strict_types=1);
class bugZen extends bug
{
    /**
     * 检查bug是否已经存在。
     * Check whether bug is exist.
     *
     * @param  object    $bug
     * @access protected
     * @return bool
     */
    protected function checkExistBug(object $bug): bool
    {
        $result = $this->loadModel('common')->removeDuplicate('bug', $bug, "product={$bug->product}");

        if($result && $result['stop'])
        {
            $message = sprintf($this->lang->duplicate, $this->lang->bug->common);
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->createLink('bug', 'view', "bugID={$result['duplicate']}")));
        }

        return true;
    }

    /**
     * 检查用户是否拥有所属执行的权限。
     * Check bug execution priv.
     *
     * @param  object      $bug
     * @access protected
     * @return bool|int
     */
    protected function checkBugExecutionPriv(object $bug): bool|int
    {
        if($bug->execution && !$this->loadModel('execution')->checkPriv($bug->execution))
        {
            if(isInModal())
            {
                echo js::alert($this->lang->bug->notice->executionAccessDenied);

                $loginLink = $this->createLink('user', 'login');
                if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate($this->createLink('bug', 'index', '')));

                if($this->app->tab == 'my') return print(js::reload('parent'));

                return print(js::locate('back'));
            }
            else
            {
                $locate    = array('load' => true);
                $loginLink = $this->createLink('user', 'login');
                if(strpos($this->server->http_referer, $loginLink) !== false) $locate = $this->createLink('bug', 'index');
                return $this->send(array('result' => 'fail', 'message' => $this->lang->bug->notice->executionAccessDenied, 'load' => array('alert' => $this->lang->bug->notice->executionAccessDenied, 'locate' => $locate)));
            }
        }

        return true;
    }

    /**
     * 检查 bug 编辑时的必填项。
     * Check required fields when edit bug.
     *
     * @param  object    $bug
     * @access protected
     * @return bool
     */
    protected function checkRquiredForEdit(object $bug): bool
    {
        $requiredFields = explode(',', $this->config->bug->edit->requiredFields);
        $editErrors         = array();
        /* Check required fields. */
        foreach($requiredFields as $requiredField)
        {
            if(isset($this->config->bug->form->edit[$requiredField])
                && (!isset($bug->{$requiredField}) || (isset($bug->{$requiredField}) && strlen(trim((string)$bug->{$requiredField})) == 0)))
            {
                $fieldName = isset($this->config->bug->form->edit[$requiredField]) && $this->config->bug->form->edit[$requiredField]['type'] != 'array' ? $requiredField : "{$requiredField}[]";
                $editErrors[$fieldName] = sprintf($this->lang->error->notempty, zget($this->lang->bug, $requiredField));
            }
        }

        if(!empty($bug->resolvedBy) && empty($bug->resolution)) $editErrors['resolution'] = sprintf($this->lang->error->notempty, $this->lang->bug->resolution);
        if($bug->resolution == 'duplicate' && empty($bug->duplicateBug)) $editErrors['duplicateBug'] = sprintf($this->lang->error->notempty, $this->lang->bug->duplicateBug);
        if(!empty($editErrors)) dao::$errors = $editErrors;
        return true;
    }

    /**
     * 检查解决bug时表单数据的完整性。
     * Check the integrity of form data when resolving bug.
     *
     * @param  object    $bug
     * @access protected
     * @return bool
     */
    protected function checkRequiredForResolve(object $bug, int $oldExecution): bool
    {
        /* Set lang for error. */
        $this->lang->bug->comment = $this->lang->comment;

        /* When creating a new build, the execution of the build cannot be empty. */
        if($bug->createBuild == 'on' && empty($bug->buildExecution))
        {
            $executionLang = $this->lang->bug->execution;
            if($oldExecution)
            {
                $execution = $this->dao->findByID($oldExecution)->from(TABLE_EXECUTION)->fetch();
                if($execution and $execution->type == 'kanban') $executionLang = $this->lang->bug->kanban;
            }
            dao::$errors['buildExecution'][] = sprintf($this->lang->error->notempty, $executionLang);
        }

        /* When creating a new build, the build name cannot be empty. */
        if($bug->createBuild == 'on' && empty($bug->buildName)) dao::$errors['buildName'][] = sprintf($this->lang->error->notempty, $this->lang->bug->placeholder->newBuildName);

        /* Check required fields of resolving bug. */
        foreach(explode(',', $this->config->bug->resolve->requiredFields) as $requiredField)
        {
            if(!isset($bug->{$requiredField}) or strlen(trim($bug->{$requiredField})) == 0)
            {
                $fieldName = $requiredField;
                if(isset($this->lang->bug->$requiredField)) $fieldName = $this->lang->bug->$requiredField;
                dao::$errors[$requiredField][] = sprintf($this->lang->error->notempty, $fieldName);
            }
        }

        /* If the resolution of bug is duplicate, duplicate bug id cannot be empty. */
        if($bug->resolution == 'duplicate' && empty($bug->duplicateBug)) dao::$errors['duplicateBug'][] = sprintf($this->lang->error->notempty, $this->lang->bug->duplicateBug);

        /* When creating a new build, the build name cannot be empty. */
        if($bug->resolution == 'fixed' && empty($bug->resolvedBuild)) dao::$errors['resolvedBuild'][] = sprintf($this->lang->error->notempty, $this->lang->bug->resolvedBuild);

        return !dao::isError();
    }

    /**
     * 检查批量创建bug时表单数据的完整性。
     * Check the batch created bugs.
     *
     * @param  array     $bugs
     * @param  int       $productID
     * @access protected
     * @return array
     */
    protected function checkBugsForBatchCreate(array $bugs, int $productID): array
    {
        $this->loadModel('common');

        /* Check whether the bugs meet the requirements, and if not, remove it. */
        foreach($bugs as $index => $bug)
        {
            $result = $this->common->removeDuplicate('bug', $bug, "product={$productID}");
            if(zget($result, 'stop', false) !== false)
            {
                unset($bugs[$index]);
                continue;
            }
        }

        /* Check required fields. */
        foreach($bugs as $index => $bug)
        {
            foreach(explode(',', $this->config->bug->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field and empty($bug->$field) and $field != 'title') dao::$errors["{$field}[{$index}]"] = sprintf($this->lang->error->notempty, $this->lang->bug->$field);
            }
        }

        return $bugs;
    }

    /**
     * 为批量编辑 bugs 检查数据。
     * Check bugs for batch update.
     *
     * @param  array     $bugs
     * @access protected
     * @return bool
     */
    protected function checkBugsForBatchUpdate(array $bugs): bool
    {
        $requiredFields = explode(',', $this->config->bug->edit->requiredFields);
        foreach($bugs as $bug)
        {
            /* Check required fields. */
            foreach($requiredFields as $requiredField)
            {
                if(isset($this->config->bug->form->batchEdit[$requiredField])
                 && (!isset($bug->{$requiredField}) || (isset($bug->{$requiredField}) && strlen(trim((string)$bug->{$requiredField})) == 0)))
                {
                    $fieldName = isset($this->config->bug->form->batchEdit[$requiredField]) && $this->config->bug->form->batchEdit[$requiredField]['type'] != 'array' ? "{$requiredField}[{$bug->id}]" : "{$requiredField}[{$bug->id}][]";
                    dao::$errors[$fieldName] = sprintf($this->lang->error->notempty, zget($this->lang->bug, $requiredField));
                }
            }

            if(!empty($bug->resolvedBy) && empty($bug->resolution)) dao::$errors["resolution[{$bug->id}]"] = sprintf($this->lang->error->notempty, $this->lang->bug->resolution);
            if($bug->resolution == 'duplicate' && empty($bug->duplicateBug)) dao::$errors["duplicateBug[{$bug->id}]"] = sprintf($this->lang->error->notempty, $this->lang->bug->duplicateBug);
        }

        return !dao::isError();
    }

    /**
     * 获取列表页面的 branch 参数。
     * Get browse branch param.
     *
     * @param  string    $branch
     * @param  string    $productType
     * @access protected
     * @return string
     */
    protected function getBrowseBranch(string $branch, string $productType): string
    {
        if($productType == 'normal') return 'all';

        if($branch === '') $branch = $this->cookie->preBranch;
        if($branch === '' || $branch === false) $branch = '0';
        $this->session->set('branch', $branch, 'qa');
        helper::setcookie('preBranch', $branch);

        return $branch;
    }

    /**
     * 获取列表页面的 bug 列表。
     * Get browse bugs.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  string    $browseType
     * @param  array     $executions
     * @param  int       $moduleID
     * @param  int       $queryID
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getBrowseBugs(int $productID, string $branch, string $browseType, array $executions, int $moduleID, int $queryID, string $orderBy, object $pager): array
    {
        $bugs = $this->bug->getList($browseType, (array)$productID, $this->projectID, $executions, $branch, $moduleID, $queryID, $orderBy, $pager);

        /* 把查询条件保存到 session。*/
        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->bug->dao->get(), 'bug', $browseType == 'needconfirm' ? false : true);

        /* 检查 bug 是否有过变更。*/
        /* Process bug for check story changed. */
        $bugs = $this->loadModel('story')->checkNeedConfirm($bugs);

        /* 处理 bug 的版本信息。*/
        /* Process the openedBuild and resolvedBuild fields. */
        return $this->bug->processBuildForBugs($bugs);
    }

    /**
     * 获取分支。
     * Get branch options.
     *
     * @param  int     $productID
     * @access private
     * @return array
     */
    private function getBranchOptions(int $productID): array
    {
        $branches        = $this->loadModel('branch')->getList($productID, 0, 'all');
        $branchTagOption = array();
        foreach($branches as $branchInfo)
        {
            $branchTagOption[$branchInfo->id] = $branchInfo->name . ($branchInfo->status == 'closed' ? ' (' . $this->lang->branch->statusList['closed'] . ')' : '');
        }

        return $branchTagOption;
    }

    /**
     * 通过$_POST的值和解析出来的$output，获得看板的laneID和columnID。
     * Get kanban laneID and columnID from $_POST and $output from extra().
     *
     * @param  array   $output
     * @access private
     * @return array
     */
    private function getKanbanVariable(array $output): array
    {
        $laneID = isset($output['laneID']) ? $output['laneID'] : 0;
        if(!empty($this->post->lane)) $laneID = $this->post->lane;

        $columnID = $this->loadModel('kanban')->getColumnIDByLaneID((int)$laneID, 'unconfirmed');
        if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;

        return array((int)$laneID, (int)$columnID);
    }

    /**
     * 获取bug创建页面的branches和branch，并绑定到bug上。
     * Get the branches and branch for the bug create page and bind them to bug.
     *
     * @param  object  $bug
     * @param  object  $currentProduct
     * @access private
     * @return object
     */
    private function getBugBranches(object $bug, object $currentProduct): object
    {
        $productID = $bug->productID;
        $branch    = $bug->branch;

        if($this->app->tab == 'execution' or $this->app->tab == 'project')
        {
            $objectID        = $this->app->tab == 'project' ? $bug->projectID : $bug->executionID;
            $productBranches = $currentProduct->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($productID), $objectID, 'noclosed|withMain') : array();
            $branches        = isset($productBranches[$productID]) ? $productBranches[$productID] : array('');
            $branch          = key($branches);
        }
        else
        {
            $branches = $currentProduct->type != 'normal' ? $this->loadModel('branch')->getPairs($productID, 'active') : array('');
        }

        return $this->updateBug($bug, array('branches' => $branches, 'branch' => (string)$branch));
    }

    /**
     * 获取bug创建页面的builds和stories，并绑定到bug上。
     * Get the builds and stories for the bug create page and bind them to bug.
     *
     * @param  object  $bug
     * @access private
     * @return object
     */
    private function getBuildsAndStoriesForCreate(object $bug): object
    {
        $this->loadModel('build');
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;
        $moduleID    = $bug->moduleID ? $bug->moduleID : 0;

        if($executionID)
        {
            $builds  = $this->build->getBuildPairs(array($productID), $branch, 'noempty,noterminate,nodone,noreleased', $executionID, 'execution');
            $stories = $this->story->getExecutionStoryPairs($executionID);
            if(!$projectID) $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        }
        else
        {
            $builds   = $this->build->getBuildPairs(array($productID), $branch, 'noempty,noterminate,nodone,withbranch,noreleased');
            $stories  = $this->story->getProductStoryPairs($productID, $branch, $moduleID, 'all', 'id_desc', 0, 'full', 'story', false);
        }

        return $this->updateBug($bug, array('stories' => $stories, 'builds' => $builds, 'projectID' => $projectID));
    }

    /**
     * 获取bug创建页面的产品成员。
     * Get the product members for bug create page.
     *
     * @param  object  $bug
     * @access private
     * @return array
     */
    private function getProductMembersForCreate(object $bug): array
    {
        $productMembers = $this->bug->getProductMemberPairs($bug->productID, (string)$bug->branch);
        $productMembers = array_filter($productMembers);
        if(empty($productMembers)) $productMembers = $this->view->users;

        return $productMembers;
    }

    /**
     * 获取bug创建页面的products和projects，并绑定到bug上。
     * Get the products and projects for the bug create page and bind them to bug.
     *
     * @param  object  $bug
     * @access private
     * @return object
     */
    private function getProductsAndProjectsForCreate(object $bug): object
    {
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;
        $projects    = array();
        $products    = $this->config->CRProduct ? $this->products : $this->product->getPairs('noclosed', 0, '', 'all');

        if($executionID)
        {
            $products       = array();
            $linkedProducts = $this->product->getProducts($executionID);
            foreach($linkedProducts as $product) $products[$product->id] = $product->name;

            /* Set project menu. */
            if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($executionID);
        }
        elseif($projectID)
        {
            $products    = array();
            $productList = $this->config->CRProduct ? $this->product->getOrderedProducts('all', 40, $projectID) : $this->product->getOrderedProducts('normal', 40, $projectID);
            foreach($productList as $product) $products[$product->id] = $product->name;

            /* Set project menu. */
            if($this->app->tab == 'project') $this->project->setMenu($projectID);
        }
        else
        {
            $projects += $this->product->getProjectPairsByProduct($productID, (string)$branch);
        }

        return $this->updateBug($bug, array('products' => $products, 'projects' => $projects));
    }

    /**
     * 获得项目的模式。
     * Get project model.
     *
     * @param  object  $bug
     * @access private
     * @return object
     */
    private function getProjectModelForCreate(object $bug): object
    {
        $projectID    = $bug->projectID;
        $executionID  = $bug->executionID;
        $project      = $bug->project;
        $projectModel = '';

        if($projectID and $project)
        {
            if(!empty($project->model) and $project->model == 'waterfall') $this->lang->bug->execution = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->bug->execution);
            $projectModel = $project->model;

            if(!$project->multiple) $executionID = $this->loadModel('execution')->getNoMultipleID($projectID);
        }

        return $this->updateBug($bug, array('projectModel' => $projectModel, 'executionID' => $executionID));
    }

    /**
     * 获得bug创建页面的products和projects，并绑定到bug上。
     * Get the executions and projects for the bug create page and bind them to bug.
     *
     * @param  object  $bug
     * @access private
     * @return object
     */
    private function getExecutionsForCreate(object $bug): object
    {
        $productID   = $bug->productID;
        $branch      = $bug->branch;
        $projectID   = $bug->projectID;
        $executionID = $bug->executionID;

        $executions  = array();
        $execution   = null;

        $execution   = '';
        $executions += $this->product->getExecutionPairsByProduct($productID, $branch ? "0,$branch" : '0', (int)$projectID, !$projectID ? 'multiple|stagefilter' : 'stagefilter');
        if($executionID)
        {
            $execution  = $this->loadModel('execution')->getByID($executionID);
            $executions = isset($executions[$executionID]) ? $executions : $executions + array($executionID => $execution->name);
        }

        if($execution and !$execution->multiple)
        {
            $this->config->bug->list->customCreateFields = str_replace('execution,', '', $this->config->bug->list->customCreateFields);
            $this->config->bug->custom->createFields     = str_replace('execution,', '', $this->config->bug->custom->createFields);
        }

        return $this->updateBug($bug, array('executions' => $executions, 'execution' => $execution));
    }

    /**
     * 获取bug创建页面的projects，并绑定到bug上。
     * Append the projects for the bug create page and bind them to bug.
     *
     * @param  object  $bug
     * @access private
     * @return object
     */
    private function getProjectsForCreate(object $bug): object
    {
        $productID = $bug->productID;
        $branch    = $bug->branch;
        $projects  = $bug->projects;

        $projectID = $bug->projectID;
        $project   = $bug->project;

        /* Link all projects to product when copying bug under qa. */
        if($this->app->tab == 'qa')
        {
            $projects += $this->product->getProjectPairsByProduct($productID, $branch);
        }
        elseif($projectID and $project)
        {
            $projects += array($projectID => $project->name);
        }

        return $this->updateBug($bug, array('projects' => $projects));
    }

    /**
     * 基于当前bug获取指派给。
     * Get assigned pairs by bug.
     *
     * @param  object    $bug
     * @access protected
     * @return string[]
     */
    protected function getAssignedToPairs(object $bug): array
    {
        /* If the execution of the bug is not empty, get the team members for the execution. */
        if($bug->execution)
        {
            $users = $this->loadModel('user')->getTeamMemberPairs($bug->execution, 'execution');
        }
        /* If the project of the bug is not empty, get the team members for the project. */
        elseif($bug->project)
        {
            $users = $this->loadModel('project')->getTeamMemberPairs($bug->project);
        }
        /* If the execution and project of the bug are both empty, get the team member of the bug's product. */
        else
        {
            $users = $this->bug->getProductMemberPairs($bug->product, (string)$bug->branch);
            $users = array_filter($users);
            /* If the team member of the product is empty, get all user. */
            if(empty($users)) $users = $this->loadModel('user')->getPairs('devfirst|noclosed');
        }

        /* If the assigned person doesn't exist in the user list and the assigned person is not closed, append it. */
        if($bug->assignedTo && !isset($users[$bug->assignedTo]) && $bug->assignedTo != 'closed')
        {
            $assignedTo = $this->user->getByID($bug->assignedTo);
            $users[$bug->assignedTo] = $assignedTo->realname;
        }

        return $users;
    }

    /**
     * 获取导出文件名。
     * Get export file name.
     *
     * @param  int         $executionID
     * @param  string      $browseType
     * @param  object|bool $product
     * @access protected
     * @return string
     */
    protected function getExportFileName(int $executionID, string $browseType, object|bool $product): string
    {
        $fileName = $this->lang->bug->common;
        if($executionID)
        {
            $executionName = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch('name');
            $fileName      = $executionName . $this->lang->dash . $fileName;
        }
        else
        {
            $productName = !empty($product->name) ? $product->name : '';
            $browseType  = isset($this->lang->bug->featureBar['browse'][$browseType]) ? $this->lang->bug->featureBar['browse'][$browseType] : zget($this->lang->bug->moreSelects, $browseType, '');

            $fileName = $productName . $this->lang->dash . $browseType . $fileName;
        }

        return $fileName;
    }

    /**
     * 获取导出字段。
     * Get export fields.
     *
     * @param  int         $executionID
     * @param  object|bool $product
     * @access protected
     * @return string
     */
    protected function getExportFields(int $executionID, object|bool $product): string
    {
        $exportFields = $this->config->bug->exportFields;
        if(isset($product->type) and $product->type == 'normal') $exportFields = str_replace('branch,', '', $exportFields);
        if($this->app->tab == 'project' or $this->app->tab == 'execution')
        {
            $execution = $this->loadModel('execution')->getByID($executionID);
            if(empty($execution->multiple)) $exportFields = str_replace('execution,', '', $exportFields);
            if(!empty($product->shadow))    $exportFields = str_replace('product,',   '', $exportFields);
        }

        return $exportFields;
    }

    /**
     * 获取批量解决bug的数据。
     * Get batch resolve bug data.
     *
     * @param  object[]  $oldBugs
     * @access protected
     * @return array
     */
    protected function getBatchResolveVars(array $oldBugs): array
    {
        $bug       = reset($oldBugs);
        $productID = $bug->product;
        $product   = $this->loadModel('product')->getByID($productID);
        $stmt      = $this->dao->query($this->loadModel('tree')->buildMenuQuery($productID, 'bug'));
        $modules   = array();
        while($module = $stmt->fetch()) $modules[$module->id] = $module;

        return array($modules, $product->QD);
    }

    /**
     * 获取BUG详情页面的基本信息列表。
     * Get bug legend basic info.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getBasicInfoTable(object $view): array
    {
        extract((array)$view);

        $this->app->loadLang('product');
        $canViewProduct = common::hasPriv('project', 'view');
        $canBrowseBug   = common::hasPriv('bug', 'browse');
        $canViewPlan    = common::hasPriv('productplan', 'view');
        $canViewCase    = common::hasPriv('testcase', 'view');

        $branchTitle  = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
        $fromCaseName = $bug->case ? "#{$bug->case} {$bug->caseTitle}" : '';
        $productLink  = $bug->product && $canViewProduct ? helper::createLink('product',     'view',   "productID={$bug->product}")                           : '';
        $branchLink   = $bug->branch  && $canBrowseBug   ? helper::createLink('bug',         'browse', "productID={$bug->product}&branch={$bug->branch}")     : '';
        $planLink     = $bug->plan    && $canViewPlan    ? helper::createLink('productplan', 'view',   "planID={$bug->plan}&type=bug")                        : '';
        $fromCaseLink = $bug->case    && $canViewCase    ? helper::createLink('testcase',    'view',   "caseID={$bug->case}&caseVersion={$bug->caseVersion}") : '';

        $legendBasic = array();
        if(empty($product->shadow))    $legendBasic['product'] = array('name' => $this->lang->bug->product, 'text' => $product->name, 'href' => $productLink, 'attr' => array('data-app' => 'product'));
        if($product->type != 'normal') $legendBasic['branch']  = array('name' => $branchTitle,        'text' => $branchName,    'href' => $branchLink);
        $legendBasic['module'] = array('name' => $this->lang->bug->module, 'text' => $bug->module);
        if(empty($product->shadow) || !empty($project->multiple)) $legendBasic['productplan'] = array('name' => $this->lang->bug->plan, 'text' => $bug->planName, 'href' => $planLink);
        $legendBasic['fromCase']       = array('name' => $this->lang->bug->fromCase,       'text' => $fromCaseName, 'href' => $fromCaseLink, 'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
        $legendBasic['type']           = array('name' => $this->lang->bug->type,           'text' => zget($this->lang->bug->typeList, $bug->type));
        $legendBasic['severity']       = array('name' => $this->lang->bug->severity,       'text' => zget($this->lang->bug->severityList, $bug->severity));
        $legendBasic['pri']            = array('name' => $this->lang->bug->pri,            'text' => zget($this->lang->bug->priList, $bug->pri));
        $legendBasic['status']         = array('name' => $this->lang->bug->status,         'text' => $this->processStatus('bug', $bug), 'attr' => array('class' => 'status-' . $bug->status));
        $legendBasic['activatedCount'] = array('name' => $this->lang->bug->activatedCount, 'text' => $bug->activatedCount);
        $legendBasic['activatedDate']  = array('name' => $this->lang->bug->activatedDate,  'text' => $bug->activatedDate);
        $legendBasic['confirmed']      = array('name' => $this->lang->bug->confirmed,      'text' => $this->lang->bug->confirmedList[$bug->confirmed]);
        $legendBasic['assignedTo']     = array('name' => $this->lang->bug->lblAssignedTo,  'text' => zget($users, $bug->assignedTo) . $this->lang->at . $bug->assignedDate);
        $legendBasic['deadline']       = array('name' => $this->lang->bug->deadline,       'text' => $bug->deadline . (isset($bug->delay) ? sprintf($this->lang->bug->notice->delayWarning, $bug->delay) : ''));
        $legendBasic['feedbackBy']     = array('name' => $this->lang->bug->feedbackBy,     'text' => $bug->feedbackBy);
        $legendBasic['notifyEmail']    = array('name' => $this->lang->bug->notifyEmail,    'text' => $bug->notifyEmail);
        $legendBasic['os']             = array('name' => $this->lang->bug->os,             'text' => $bug->os);
        $legendBasic['browser']        = array('name' => $this->lang->bug->browser,        'text' => $bug->browser);
        $legendBasic['keywords']       = array('name' => $this->lang->bug->keywords,       'text' => $bug->keywords);
        $legendBasic['mailto']         = array('name' => $this->lang->bug->mailto,         'text' => $bug->mailto);

        return $legendBasic;
    }

    /**
     * 获取BUG的一生信息。
     * Get bug a life data.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getBugLifeTable(object $view): array
    {
        extract((array)$view);

        $legendLife  = array();
        $legendLife['openedBy']      = array('name' => $this->lang->bug->openedBy,      'text' => zget($users, $bug->openedBy) . ($bug->openedDate ? $this->lang->at . $bug->openedDate : ''));
        $legendLife['openedBuild']   = array('name' => $this->lang->bug->openedBuild,   'text' => $bug->openedBuild);
        $legendLife['resolvedBy']    = array('name' => $this->lang->bug->lblResolved,   'text' => zget($users, $bug->resolvedBy) . ($bug->resolvedDate ? $this->lang->at . formatTime($bug->resolvedDate, DT_DATE1) : ''));
        $legendLife['resolvedBuild'] = array('name' => $this->lang->bug->resolvedBuild, 'text' => zget($builds, $bug->resolvedBuild));
        $legendLife['resolution']    = array('name' => $this->lang->bug->resolution,    'text' => '');
        $legendLife['closedBy']      = array('name' => $this->lang->bug->closedBy,      'text' => zget($users, $bug->closedBy) . ($bug->closedDate ? $this->lang->at . $bug->closedDate : ''));
        $legendLife['lastEditedBy']  = array('name' => $this->lang->bug->lblLastEdited, 'text' => zget($users, $bug->lastEditedBy, $bug->lastEditedBy) . ($bug->lastEditedDate ? $this->lang->at . $bug->lastEditedDate : ''));

        return $legendLife;
    }

    /**
     * 获取BUG的项目、迭代、研发需求、任务信息。
     * Get bug main related data.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getMainRelatedTable(object $view): array
    {
        extract((array)$view);

        $canViewProduct     = common::hasPriv('product', 'view');
        $canBrowseExecution = common::hasPriv('execution', 'browse');
        $canViewStory       = common::hasPriv('story', 'view');
        $canViewTask        = common::hasPriv('task', 'view');

        $executionTitle = isset($project->model) && $project->model == 'kanban' ? $this->lang->bug->kanban : $this->lang->bug->execution;
        $projectLink    = $bug->project   && $canViewProduct     ? helper::createLink('project',   'view',   "projectID={$bug->project}")     : '';
        $executionLink  = $bug->execution && $canBrowseExecution ? helper::createLink('execution', 'browse', "executionID={$bug->execution}") : '';
        $storyLink      = $bug->story     && $canViewStory       ? helper::createLink('story',     'view',   "storyID={$bug->story}")         : '';
        $taskLink       = $bug->task      && $canViewTask        ? helper::createLink('task',      'view',   "taskID={$bug->task}")           : '';

        $legendExecStoryTask = array();
        $legendExecStoryTask['project']   = array('name' => $this->lang->bug->project, 'text' => zget($bug, 'projectName', ''), 'href' => $projectLink);
        $legendExecStoryTask['execution'] = array('name' => $executionTitle,           'text' => $bug->executionName,           'href' => $executionLink);
        $legendExecStoryTask['story']     = array('name' => $this->lang->bug->story,   'text' => $bug->storyTitle,              'href' => $storyLink, 'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
        $legendExecStoryTask['task']      = array('name' => $this->lang->bug->task,    'text' => $bug->taskName,                'href' => $taskLink,  'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));

        return $legendExecStoryTask;
    }

    /**
     * 获取BUG的其他相关信息。
     * Get bug other related data.
     *
     * @param  object    $view
     * @access protected
     * @return array
     */
    protected function getOtherRelatedTable(object $view): array
    {
        extract((array)$view);

        $canViewStory = common::hasPriv('story', 'view');
        $canViewTask  = common::hasPriv('task', 'view');

        $toStoryName  = $bug->toStory ? "#{$bug->toStory} {$bug->toStoryTitle}" : '';
        $toTaskName   = $bug->toTask  ? "#{$bug->toTask} {$bug->toTaskTitle}"   : '';
        $toStoryLink  = $bug->toStory && $canViewStory ? helper::createLink('story', 'view', "storyID={$bug->toStory}") : '';
        $toTaskLink   = $bug->toTask  && $canViewTask  ? helper::createLink('task',  'view', "taskID={$bug->toTask}")   : '';

        $legendMisc = array();
        $legendMisc['relatedBug'] = array('name' => $lang->bug->relatedBug, 'text' => isset($bug->relatedBugTitles) ? $bug->relatedBugTitles : array());
        $legendMisc['toCase']     = array('name' => $lang->bug->toCase,     'text' => $bug->toCases);
        $legendMisc['toStory']    = array('name' => $lang->bug->toStory,    'text' => $toStoryName,  'href' => $toStoryLink,  'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
        $legendMisc['toTask']     = array('name' => $lang->bug->toTask,     'text' => $toTaskName,   'href' => $toTaskLink,   'attr' => array('data-toggle' => 'modal', 'data-size' => 'lg'));
        $legendMisc['linkMR']     = array('name' => $lang->bug->linkMR,     'text' => $bug->linkMRTitles);
        $legendMisc['linkCommit'] = array('name' => $lang->bug->linkCommit, 'text' => $linkCommits);

        return $legendMisc;
    }

    /**
     * 设置浏览页面的 cookie。
     * Set cookie in browse view.
     *
     * @param  object    $product
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @access protected
     * @return bool
     */
    protected function setBrowseCookie(object $product, string $branch, string $browseType, int $param, string $orderBy): bool
    {
        /* 如果产品或者分支变了，清空 bug 模块的 cookie。*/
        /* Clear cookie of bug module if the product or the branch is changed. */
        $productChanged = $this->cookie->preProductID != $product->id;
        $branchChanged  = $product->type != 'normal' && $this->cookie->preBranch != $branch;
        if($productChanged || $branchChanged) helper::setcookie('bugModule', '0', 0);

        /* 如果浏览类型为按模块浏览或者浏览类型为空，设置 bug 模块的 cookie 为当前模块，清空 bug 分支的 cookie。*/
        /* Set cookie of bug module and clear cookie of bug branch if browse type is by module or is empty. */
        helper::setcookie('bugModule', (string)$param, 0);
        if($browseType == 'bymodule' || $browseType == '') helper::setcookie('bugBranch', '0', 0);

        /* 设置测试应用的 bug 排序 cookie。*/
        /* Set the cookie of bug order in qa. */
        helper::setcookie('qaBugOrder', $orderBy, 0);

        return true;
    }

    /**
     * 设置浏览界面的 session。
     * Set session in browse view.
     *
     * @param  string    $browseType
     * @access protected
     * @return bool
     */
    protected function setBrowseSession(string $browseType): bool
    {
        /* 设置浏览方式的 session，记录刚刚是搜索还是按模块浏览。*/
        /* Set session of browse type. */
        if($browseType != 'bymodule') $this->session->set('bugBrowseType', $browseType);
        if(($browseType == 'bymodule') && $this->session->bugBrowseType == 'bysearch') $this->session->set('bugBrowseType', 'unclosed');

        $this->session->set('bugList', $this->app->getURI(true) . "#app={$this->app->tab}", 'qa');

        return true;
    }

    /**
     * 获取模块下拉菜单，如果是空的，则返回到模块维护页面。
     * Get moduleOptionMenu, if moduleOptionMenu is empty, return tree-browse.
     *
     * @param  object    $bug
     * @param  object    $product
     * @access protected
     * @return object
     */
    protected function setOptionMenu(object $bug, object $product): object
    {
        $bug = $this->getBugBranches($bug, $product);
        $moduleOptionMenu = $this->tree->getOptionMenu($bug->productID, 'bug', 0, ($bug->branch === 'all' or !isset($bug->branches[$bug->branch])) ? 'all' : (string)$bug->branch);
        if(empty($moduleOptionMenu)) return print(js::locate(helper::createLink('tree', 'browse', "productID={$bug->productID}&view=story")));

        $this->view->moduleOptionMenu = $moduleOptionMenu;

        return $bug;
    }

    /**
     * 为创建 bug 设置导航数据。
     * Set menu for create bug page.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  array     $output
     * @access protected
     * @return bool
     */
    protected function setCreateMenu(int $productID, string $branch, array $output): bool
    {
        if(empty($this->products)) $this->locate($this->createLink('product', 'create'));

        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type) unset($this->lang->bug->typeList[$type]);

        if($this->app->tab == 'execution')
        {
            if(isset($output['executionID']))
            {
                $this->loadModel('execution')->setMenu((int)$output['executionID']);
                $this->view->executionID = $output['executionID'];
            }
            $execution = $this->dao->findById($this->session->execution)->from(TABLE_EXECUTION)->fetch();
            if($execution->type == 'kanban') $this->assignKanbanVars($execution, $output);
        }
        elseif($this->app->tab == 'project')
        {
            if(isset($output['projectID']))
            {
                $this->loadModel('project')->setMenu((int)$output['projectID']);
                $this->view->projectID = $output['projectID'];
            }
        }
        else
        {
            $this->qa->setMenu($productID, $branch);
        }

        $this->view->users = $this->user->getPairs('devfirst|noclosed|nodeleted');
        $this->app->loadLang('release');

        return true;
    }

    /**
     * 设置编辑页面的导航。
     * Set edit menu.
     *
     * @param  object    $bug
     * @access protected
     * @return bool
     */
    protected function setEditMenu(object $bug): bool
    {
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->execution->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->loadModel('qa')->setMenu($bug->product, $bug->branch);
        if($this->app->tab == 'devops')
        {
            session_write_close();

            $repoPairs = $this->loadModel('repo')->getRepoPairs('project', $bug->project);
            $this->repo->setMenu($repoPairs);

            $this->lang->navGroup->bug = 'devops';
        }

        return true;
    }

    /**
     * 如果不是弹窗，调用该方法为查看bug设置导航。
     * If it's not a iframe, call this method to set menu for view bug page.
     *
     * @param  object $bug
     * @return bool
     */
    protected function setViewMenu(object $bug): bool
    {
        if($this->app->tab == 'project')   $this->loadModel('project')->setMenu($bug->project);
        if($this->app->tab == 'execution') $this->loadModel('execution')->setMenu($bug->execution);
        if($this->app->tab == 'qa')        $this->qa->setMenu($bug->product, $bug->branch);

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

        return true;
    }

    /**
     * 处理列表页面的参数。
     * Processing browse params.
     *
     * @param  string    $browseType
     * @param  int       $param
     * @param  string    $orderBy
     * @param  int       $recTotal
     * @param  int       $recPerPage
     * @param  int       $pageID
     * @access protected
     * @return array
     */
    protected function prepareBrowseParams(string $browseType, int $param, string $orderBy, int $recTotal, int $recPerPage, int $pageID): array
    {
        /* 设置模块 ID。*/
        /* Set module id. */
        $moduleID = 0;
        if($this->cookie->bugModule)  $moduleID = (int)$this->cookie->bugModule;
        if($browseType == 'bymodule') $moduleID = $param;

        /* 设置搜索查询 ID。*/
        /* Set query id. */
        $queryID = $browseType == 'bysearch' ? $param : 0;

        /* 设置 id 为第二排序规则。*/
        /* Append id for second sort rule. */
        $realOrderBy = common::appendOrder($orderBy);

        /* 加载分页器。*/
        /* Load pager. */
        $viewType = $this->app->getViewType();
        if($viewType == 'mhtml' || $viewType == 'xhtml') $recPerPage = 10;

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        return array($moduleID, $queryID, $realOrderBy, $pager);
    }

    /**
     * 处理创建 bug 请求数据。
     * Processing request data for creating bug.
     *
     * @param  form      $formData
     * @access protected
     * @return object
     */
    protected function prepareCreateExtras(form $formData): object
    {
        $bug = $formData->setIF($this->lang->navGroup->bug != 'qa', 'project', $this->session->project)
            ->setIF($formData->data->assignedTo != '', 'assignedDate', helper::now())
            ->setIF($formData->data->story !== false, 'storyVersion', $this->loadModel('story')->getVersion((int)$formData->data->story))
            ->get();

        return $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $this->post->uid);
    }

    /**
     * 处理更新请求数据。
     * Processing request data.
     *
     * @param  form         $formData
     * @param  object       $oldBug
     * @access protected
     * @return object|false
     */
    protected function prepareEditExtras(form $formData, object $oldBug): object|false
    {
        if(!empty($_POST['lastEditedDate']) and $oldBug->lastEditedDate != $this->post->lastEditedDate)
        {
            dao::$errors[] = $this->lang->error->editedByOther;
            return false;
        }

        $now = helper::now();
        $bug = $formData->add('id', $oldBug->id)
            ->setDefault('product', $oldBug->product)
            ->setDefault('deleteFiles', array())
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->join('openedBuild,mailto,relatedBug,os,browser', ',')
            ->setIF($formData->data->assignedTo  != $oldBug->assignedTo, 'assignedDate', $now)
            ->setIF($formData->data->resolvedBy  != '' && $formData->data->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($formData->data->resolution  != '' && $formData->data->resolvedDate == '', 'resolvedDate', $now)
            ->setIF($formData->data->resolution  != '' && $formData->data->resolvedBy   == '', 'resolvedBy',   $this->app->user->account)
            ->setIF($formData->data->closedDate  != '' && $formData->data->closedBy     == '', 'closedBy',     $this->app->user->account)
            ->setIF($formData->data->closedBy    != '' && $formData->data->closedDate   == '', 'closedDate',   $now)
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'assignedTo',   'closed')
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'assignedDate', $now)
            ->setIF($formData->data->resolution  != '' || $formData->data->resolvedDate != '', 'status',       'resolved')
            ->setIF($formData->data->closedBy    != '' || $formData->data->closedDate   != '', 'status',       'closed')
            ->setIF(($formData->data->resolution != '' || $formData->data->resolvedDate != '') && $formData->data->assignedTo == '', 'assignedTo', $oldBug->openedBy)
            ->setIF(($formData->data->resolution != '' || $formData->data->resolvedDate != '') && $formData->data->assignedTo == '', 'assignedDate', $now)
            ->setIF($formData->data->resolution  == '' && $formData->data->resolvedDate == '', 'status', 'active')
            ->setIF($formData->data->resolution  != '' && $formData->data->resolution   != 'duplicate', 'duplicateBug', 0)
            ->setIF($formData->data->assignedTo  == '' && $oldBug->status               == 'closed', 'assignedTo', 'closed')
            ->setIF($formData->data->resolution  != '', 'confirmed', 1)
            ->setIF($formData->data->story && $formData->data->story != $oldBug->story, 'storyVersion', $this->loadModel('story')->getVersion((int)$formData->data->story))
            ->stripTags($this->config->bug->editor->edit['id'], $this->config->allowedTags)
            ->get();

        $bug = $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->create['id'], $bug->uid);

        return $bug;
    }

    /**
     * 设置列表页面的搜索表单。
     * Build browse search form.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildBrowseSearchForm(int $productID, string $branch, int $queryID): void
    {
        $this->config->bug->search['onMenuBar'] = 'yes';

        $actionURL      = $this->createLink('bug', 'browse', "productID=$productID&branch=$branch&browseType=bySearch&queryID=myQueryID");
        $searchProducts = $this->product->getPairs('', 0, '', 'all');

        $this->bug->buildSearchForm($productID, $searchProducts, $queryID, $actionURL, $branch);
    }

    /**
     * 获取浏览页面所需的变量, 并输出到前台。
     * Get the data required by the browse page and output.
     *
     * @param  array     $bugs
     * @param  object    $product
     * @param  string    $branch
     * @param  string    $browseType
     * @param  int       $moduleID
     * @param  array     $executions
     * @param  int       $param
     * @param  string    $orderBy
     * @param  object    $pager
     * @access protected
     * @return void
     */
    protected function buildBrowseView(array $bugs, object $product, string $branch, string $browseType, int $moduleID, array $executions, int $param, string $orderBy, object $pager): void
    {
        $this->loadModel('datatable');

        /* 获取分支列表。*/
        /* Get branch options. */
        $branchTagOption = array();
        if($product->type != 'normal') $branchTagOption = $this->getBranchOptions((int)$product->id);

        /* 获取需求和任务的 id 列表。*/
        /* Get story and task id list. */
        $storyIdList = $taskIdList = array();
        foreach($bugs as $bug)
        {
            if($bug->story)  $storyIdList[$bug->story] = $bug->story;
            if($bug->task)   $taskIdList[$bug->task]   = $bug->task;
            if($bug->toTask) $taskIdList[$bug->toTask] = $bug->toTask;
        }

        $showModule = !empty($this->config->bug->browse->showModule) ? $this->config->bug->browse->showModule : '';

        /* Set view. */
        $this->view->title           = $product->name . $this->lang->colon . $this->lang->bug->common;
        $this->view->product         = $product;
        $this->view->branch          = $branch;
        $this->view->browseType      = $browseType;
        $this->view->currentModuleID = $moduleID;
        $this->view->param           = $param;
        $this->view->orderBy         = $orderBy;
        $this->view->pager           = $pager;
        $this->view->modulePairs     = $showModule ? $this->tree->getModulePairs($product->id, 'bug', $showModule) : array();
        $this->view->modules         = $this->tree->getOptionMenu((int)$product->id, 'bug', 0, $branch);
        $this->view->moduleTree      = $this->tree->getTreeMenu((int)$product->id, 'bug', 0, array('treeModel', 'createBugLink'), array(), $branch);
        $this->view->branchTagOption = $branchTagOption;
        $this->view->projectPairs    = $this->loadModel('project')->getPairsByProgram();
        $this->view->executions      = $executions;
        $this->view->plans           = $this->loadModel('productplan')->getPairs((int)$product->id);
        $this->view->tasks           = $this->loadModel('task')->getPairsByIdList($taskIdList);
        $this->view->stories         = $this->loadModel('story')->getPairsByList($storyIdList);
        $this->view->builds          = $this->loadModel('build')->getBuildPairs(array($product->id), $branch);
        $this->view->bugs            = $bugs;
        $this->view->users           = $this->user->getPairs('noletter');
        $this->view->memberPairs     = $this->user->getPairs('noletter|noclosed');
    }

    /**
     *
     * 构建创建bug页面数据。
     * Build form fields for create bug.
     *
     * @param  object    $bug
     * @param  array     $param
     * @param  string    $from
     * @access protected
     * @return void
     */
    protected function buildCreateForm(object $bug, array $param, string $from): void
    {
        extract($param);
        if(isset($executionID)) $projectID = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($executionID)->fetch('project');

        $currentProduct = $this->product->getByID($bug->productID);

        /* 获得版本下拉和需求下拉列表。 */
        /* Get builds and stroies. */
        $bug = $this->getBuildsAndStoriesForCreate($bug);
        /* 如果bug有所属项目，查询这个项目。 */
        /* Get project. */
        if($bug->projectID) $bug = $this->updateBug($bug, array('project' => $this->loadModel('project')->getByID($bug->projectID)));
        /* 获得产品下拉和项目下拉列表。 */
        /* Get products and projects. */
        $bug = $this->getProductsAndProjectsForCreate($bug);
        /* 追加下拉列表的内容。 */
        /* Append projects. */
        $bug = $this->getProjectsForCreate($bug);
        /* 获得项目的管理方式。 */
        /* Get project model. */
        $bug = $this->getProjectModelForCreate($bug);
        /* 获得执行下拉列表。 */
        /* Get executions. */
        $bug = $this->getExecutionsForCreate($bug);

        $this->view->title                 = isset($this->products[$bug->productID]) ? $this->products[$bug->productID] . $this->lang->colon . $this->lang->bug->create : $this->lang->bug->create;
        $this->view->productMembers        = $this->getProductMembersForCreate($bug);
        $this->view->gobackLink            = $from == 'global' ? $this->createLink('bug', 'browse', "productID=$bug->productID") : '';
        $this->view->productName           = isset($this->products[$bug->productID]) ? $this->products[$bug->productID] : '';
        $this->view->projectExecutionPairs = $this->loadModel('project')->getProjectExecutionPairs();
        $this->view->projects              = commonModel::isTutorialMode() ? $this->loadModel('tutorial')->getProjectPairs() : $bug->projects;
        $this->view->products              = $bug->products;
        $this->view->branches              = $bug->branches;
        $this->view->builds                = $bug->builds;
        $this->view->bug                   = $bug;
        $this->view->executions            = commonModel::isTutorialMode() ? $this->loadModel('tutorial')->getExecutionPairs() : $bug->executions;
        $this->view->releasedBuilds        = $this->loadModel('release')->getReleasedBuilds($bug->productID, $bug->branch);
        $this->view->resultFiles           = !empty($resultID) && !empty($stepIdList) ? $this->loadModel('file')->getByObject('stepResult', (int)$resultID, str_replace('_', ',', $stepIdList)) : array();
        $this->view->product               = $currentProduct;
        $this->view->contactList           = $this->loadModel('user')->getContactLists();
        $this->view->projectID             = isset($projectID) ? $projectID : $bug->projectID;
        $this->view->executionID           = isset($executionID) ? $executionID : (!empty($bug->execution->id) ? $bug->execution->id : '');
        $this->view->branchID              = $bug->branch != 'all' ? $bug->branch : '0';
    }

    /**
     * 获取页面所需的变量, 并输出到前台。
     * Get the data required by the view page and output.
     *
     * @param  object    $bug
     * @access protected
     * @return void
     */
    protected function buildEditForm(object $bug): void
    {
        /* 删掉当前 bug 类型不属于的并且已经弃用的类型。*/
        /* Unset discarded types. */
        foreach($this->config->bug->discardedTypes as $type)
        {
            if($bug->type != $type) unset($this->lang->bug->typeList[$type]);
        }

        $product   = $this->product->getByID($bug->product);
        $execution = $this->loadModel('execution')->getByID($bug->execution);

        /* Get module option menu. */
        $moduleOptionMenu = $this->tree->getOptionMenu($bug->product, 'bug', 0, (string)$bug->branch);
        if(!isset($moduleOptionMenu[$bug->module])) $moduleOptionMenu += $this->tree->getModulesName((array)$bug->module);

        /* Get bugs of current product. */
        $branch = '';
        if($product->type == 'branch') $branch = $bug->branch > 0 ? "{$bug->branch},0" : '0';
        $productBugs = $this->bug->getProductBugPairs($bug->product, $branch);
        unset($productBugs[$bug->id]);

        /* Get execution pairs. */
        $executions = $this->product->getExecutionPairsByProduct($bug->product, (string)$bug->branch, (int)$bug->project);
        if(!empty($bug->execution) && empty($executions[$bug->execution])) $executions[$execution->id] = $execution->name . "({$this->lang->bug->deleted})";

        /* Get project pairs. */
        $projects = $this->product->getProjectPairsByProduct($bug->product, (string)$bug->branch);
        if(!empty($bug->project) && empty($projects[$bug->project]))
        {
            $project = $this->loadModel('project')->getByID($bug->project);
            $projects[$project->id] = $project->name . "({$this->lang->bug->deleted})";
        }

        /* 获取分支列表。*/
        /* Get branch options. */
        $branchTagOption = array();
        if($product->type != 'normal') $branchTagOption = $this->getBranchOptions($product->id);

        $this->assignVarsForEdit($bug);

        $this->view->title            = $this->lang->bug->edit . "BUG #$bug->id $bug->title - " . $this->products[$bug->product];
        $this->view->bug              = $bug;
        $this->view->product          = $product;
        $this->view->moduleOptionMenu = $moduleOptionMenu;
        $this->view->projectID        = $bug->project;
        $this->view->projects         = $projects;
        $this->view->executions       = $executions;
        $this->view->productBugs      = $productBugs;
        $this->view->branchTagOption  = $branchTagOption;
    }

    /**
     * 为编辑 bug 指派版本数据。
     * Assign variables for editing bug.
     *
     * @param  object    $bug
     * @access protected
     * @return void
     */
    protected function assignVarsForEdit(object $bug): void
    {
        /* Add product related to the bug when it is not in the products. */
        if(!isset($this->products[$bug->product]))
        {
            $this->products[$bug->product] = $product->name;
            $this->view->products = $this->products;
        }

        if($bug->execution)
        {
            $openedBuilds   = $this->loadModel('build')->getBuildPairs(array($bug->product), $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased', $bug->execution, 'execution');
            $assignedToList = $this->user->getTeamMemberPairs($bug->execution, 'execution');
        }
        elseif($bug->project)
        {
            $openedBuilds   = $this->loadModel('build')->getBuildPairs(array($bug->product), $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased', $bug->project, 'project');
            $assignedToList = $this->loadModel('project')->getTeamMemberPairs($bug->project);
        }
        else
        {
            $openedBuilds   = $this->loadModel('build')->getBuildPairs(array($bug->product), $bug->branch, 'noempty,noterminate,nodone,withbranch,noreleased');
            $assignedToList = $this->bug->getProductMemberPairs($bug->product, (string)$bug->branch);
            $assignedToList = array_filter($assignedToList);
            if(empty($assignedToList)) $assignedToList = $this->user->getPairs('devfirst|noclosed');
        }

        if($bug->assignedTo && !isset($assignedToList[$bug->assignedTo]) && $bug->assignedTo != 'closed')
        {
            $assignedTo = $this->user->getById($bug->assignedTo);
            $assignedToList[$bug->assignedTo] = $assignedTo->realname;
        }
        if($bug->status == 'closed') $assignedToList['closed'] = 'Closed';

        $cases = array();
        if($bug->case)
        {
            $case  = $this->loadModel('testcase')->getByID($bug->case);
            $cases = $this->loadmodel('testcase')->getPairsByProduct($bug->product, array(0, $bug->branch), $case->title, $this->config->maxCount);
        }

        $this->config->moreLinks['case'] = inlink('ajaxGetProductCases', "bugID={$bug->id}");

        $this->view->openedBuilds   = $openedBuilds;
        $this->view->resolvedBuilds = $this->build->getBuildPairs(array($bug->product), $bug->branch, 'noempty');
        $this->view->plans          = $this->loadModel('productplan')->getPairs($bug->product, $bug->branch, '', true);
        $this->view->stories        = $bug->execution ? $this->story->getExecutionStoryPairs($bug->execution) : $this->story->getProductStoryPairs($bug->product, $bug->branch, 0, 'all', 'id_desc', 0, 'full', 'story', false);
        $this->view->tasks          = $this->task->getExecutionTaskPairs($bug->execution);
        $this->view->testtasks      = $this->loadModel('testtask')->getPairs($bug->product, $bug->execution, $bug->testtask);
        $this->view->cases          = $cases;
        $this->view->users          = $this->user->getPairs('noclosed', "$bug->assignedTo,$bug->resolvedBy,$bug->closedBy,$bug->openedBy");
        $this->view->actions        = $this->loadModel('action')->getList('bug', $bug->id);
        $this->view->contactList    = $this->loadModel('user')->getContactLists();
        $this->view->assignedToList = $assignedToList;
        $this->view->execution      = $this->loadModel('execution')->getByID($bug->execution);
    }

    /**
     * 为解决bug构造bug数据。
     * Build bug for resolving a bug.
     *
     * @param  object    $oldBug
     * @access protected
     * @return object
     */
    protected function buildBugForResolve(object $oldBug): object
    {
        $bug = form::data($this->config->bug->form->resolve)
            ->setDefault('assignedTo', $oldBug->openedBy)
            ->setDefault('resolvedDate', helper::now())
            ->add('id',        $oldBug->id)
            ->add('execution', $oldBug->execution)
            ->add('status',    'resolved')
            ->add('confirmed', 1)
            ->removeIF($this->post->resolution != 'duplicate', 'duplicateBug')
            ->get();

        /* If the resolved build is not the trunk, get test plan id. */
        if(isset($bug->resolvedBuild) && $bug->resolvedBuild != 'trunk')
        {
            $testtaskID = (int)$this->dao->select('id')->from(TABLE_TESTTASK)->where('build')->eq($bug->resolvedBuild)->orderBy('id_desc')->limit(1)->fetch('id');
            if($testtaskID and empty($oldBug->testtask)) $bug->testtask = $testtaskID;
        }

        return $this->loadModel('file')->processImgURL($bug, $this->config->bug->editor->resolve['id'], $this->post->uid);
    }

    /**
     * 为批量创建 bug 构造数据。
     * Build bugs for the batch creation.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @access protected
     * @return array
     */
    protected function buildBugsForBatchCreate(int $productID, string $branch): array
    {
        $bugs = form::batchData($this->config->bug->form->batchCreate)->get();

        /* Get pairs(moduleID => moduleOwner) for bug. */
        $stmt         = $this->app->dbQuery($this->loadModel('tree')->buildMenuQuery($productID, 'bug', 0, $branch));
        $moduleOwners = array();
        while($module = $stmt->fetch()) $moduleOwners[$module->id] = $module->owner;

        /* Construct data. */
        foreach($bugs as $bug)
        {
            $bug->openedBy    = $this->app->user->account;
            $bug->openedDate  = helper::now();
            $bug->product     = $productID;
            $bug->steps       = nl2br($bug->steps);

            /* Assign the bug to the person in charge of the module. */
            if(!empty($moduleOwners[$bug->module]))
            {
                $bug->assignedTo   = $moduleOwners[$bug->module];
                $bug->assignedDate = helper::now();
            }
        }

        return $bugs;
    }

    /**
     * 展示批量创建bug的相关变量。
     * Show the variables associated with the batch creation bugs.
     *
     * @param  int       $executionID
     * @param  object    $product
     * @param  string    $branch
     * @param  array     $output
     * @param  array     $bugImagesFile
     * @access protected
     * @return void
     */
    protected function assignBatchCreateVars(int $executionID, object $product, string $branch, array $output, array $bugImagesFile): void
    {
        if($executionID)
        {
            /* Get builds, stories and branches of this execution. */
            $builds          = $this->loadModel('build')->getBuildPairs(array($product->id), $branch, 'noempty,noreleased', $executionID, 'execution');
            $stories         = $this->story->getExecutionStoryPairs($executionID);
            $productBranches = $product->type != 'normal' ? $this->loadModel('execution')->getBranchByProduct(array($product->id), $executionID) : array();
            $branches        = isset($productBranches[$product->id]) ? $productBranches[$product->id] : array();
            $branch          = key($branches) ? key($branches) : 'all';

            /* Get the variables associated with kanban. */
            $execution = $this->loadModel('execution')->getById($executionID);
            if($execution->type == 'kanban') $this->assignKanbanVars($execution, $output);
        }
        else
        {
            /* Get builds, stories and branches of the product. */
            $builds   = $this->loadModel('build')->getBuildPairs(array($product->id), $branch, 'noempty,noreleased');
            $stories  = $this->story->getProductStoryPairs($product->id, $branch);
            $branches = $product->type != 'normal' ? $this->loadModel('branch')->getPairs($product->id, 'active') : array();
        }

        /* Get project information. */
        $projectID = isset($execution) ? $execution->project : 0;
        $project   = $this->loadModel('project')->getByID($projectID);

        if(!$project) $project = new stdclass();
        $this->assignVarsForBatchCreate($product, $project, $bugImagesFile);

        $this->view->projects         = $this->product->getProjectPairsByProduct($product->id, $branch ? "0,{$branch}" : '0');
        $this->view->project          = $project;
        $this->view->projectID        = $projectID;
        $this->view->executions       = $this->product->getExecutionPairsByProduct($product->id, $branch ? "0,{$branch}" : '0', (int)$projectID, 'multiple,stagefilter');
        $this->view->executionID      = $executionID;
        $this->view->stories          = $stories;
        $this->view->builds           = $builds;
        $this->view->branch           = $branch;
        $this->view->branches         = $branches;
        $this->view->moduleOptionMenu = $this->tree->getOptionMenu($product->id, 'bug', 0, $branch === 'all' ? 'all' : (string)$branch);
    }

    /**
     * 展示看板的相关变量。
     * Show the variables associated with the kanban.
     *
     * @param  object    $execution
     * @param  array     $output
     * @access protected
     * @return void
     */
    protected function assignKanbanVars(object $execution, array $output): void
    {
        $regionPairs = $this->loadModel('kanban')->getRegionPairs($execution->id, 0, 'execution');
        $regionID    = !empty($output['regionID']) ? $output['regionID'] : key($regionPairs);
        $lanePairs   = $this->kanban->getLanePairsByRegion((int)$regionID, 'bug');
        $laneID      = !empty($output['laneID']) ? $output['laneID'] : key($lanePairs);

        $this->view->executionType = $execution->type;
        $this->view->regionID      = $regionID;
        $this->view->laneID        = $laneID;
        $this->view->regionPairs   = $regionPairs;
        $this->view->lanePairs     = $lanePairs;
    }

    /**
     * 展示字段相关变量。
     * Show the variables associated with the batch created fields.
     *
     * @param  object  $product
     * @param  object  $project
     * @param  array   $bugImagesFile
     * @access private
     * @return void
     */
    private function assignVarsForBatchCreate(object $product, object $project, array $bugImagesFile): void
    {
        /* Set custom fields. */
        foreach(explode(',', $this->config->bug->list->customBatchCreateFields) as $field)
        {
            $customFields[$field] = $this->lang->bug->$field;
        }
        if($product->type != 'normal') $customFields[$product->type] = $this->lang->product->branchName[$product->type];
        if(isset($project->model) && $project->model == 'kanban') $customFields['execution'] = $this->lang->bug->kanban;

        /* Set display fields. */
        $showFields = $this->config->bug->custom->batchCreateFields;
        if($product->type != 'normal')
        {
            $showFields = sprintf($showFields, $product->type);
            $showFields = str_replace(array(",branch,", ",platform,"), '', ",$showFields,");
            $showFields = trim($showFields, ',');
        }
        else
        {
            $showFields = trim(sprintf($showFields, ''), ',');
        }

        /* Get titles from uploaded images. */
        if(!empty($bugImagesFile))
        {
            foreach($bugImagesFile as $fileName => $file)
            {
                $title = $file['title'];
                $titles[$title] = $fileName;
            }
            $this->view->titles = $titles;
        }

        $this->view->customFields = $customFields;
        $this->view->showFields   = $showFields;
    }

    /**
     * 设置关联相关 bug 页面的搜索表单。
     * Build search form for link related bugs page.
     *
     * @param  object    $bug
     * @param  string    $excludeBugs
     * @param  int       $queryID
     * @access protected
     * @return void
     */
    protected function buildSearchFormForLinkBugs(object $bug, string $excludeBugs, int $queryID): void
    {
        /* 无产品项目搜索时隐藏产品、执行和所属计划字段。*/
        /* Hide plan, execution and product in no product project. */
        if($bug->project && $this->app->tab != 'qa')
        {
            $project = $this->loadModel('project')->getByID($bug->project);
            if(!$project->hasProduct)
            {
                unset($this->config->bug->search['fields']['product']);

                /* 单迭代项目搜索时隐藏执行和所属计划字段。*/
                /* Hide execution and plan in single project. */
                if(!$project->multiple)
                {
                    unset($this->config->bug->search['fields']['execution']);
                    unset($this->config->bug->search['fields']['plan']);
                }
            }
        }

        $actionURL = $this->createLink('bug', 'linkBugs', "bugID={$bug->id}&bySearch=true&excludeBugs={$excludeBugs}&queryID=myQueryID", '', true);
        $this->bug->buildSearchForm($bug->product, $this->products, $queryID, $actionURL);
    }

    /**
     * 为批量编辑 bugs 构造数据。
     * Build bugs for the batch edit.
     *
     * @access protected
     * @return array
     */
    protected function buildBugsForBatchEdit(): array
    {
        /* Get bug ID list. */
        $bugIdList = $this->post->id ? $this->post->id : array();
        if(empty($bugIdList)) return array();

        /* Get bugs and old bugs. */
        $bugs    = form::batchData($this->config->bug->form->batchEdit)->get();
        $oldBugs = $this->bug->getByIdList($bugIdList);

        /* Process bugs. */
        $now     = helper::now();
        foreach($bugs as $bug)
        {
            $oldBug = $oldBugs[$bug->id];

            if(is_array($bug->os))      $bug->os      = implode(',', $bug->os);
            if(is_array($bug->browser)) $bug->browser = implode(',', $bug->browser);

            /* If bug is closed, the assignee will not be changed. */
            if($oldBug->status == 'closed') $bug->assignedTo = $oldBug->assignedTo;

            /* If resolution of the bug is not duplicate, duplicateBug is zero. */
            if($bug->resolution != '' && $bug->resolution != 'duplicate') $bug->duplicateBug = 0;

            /* If assignee is changes, set the assigned date. */
            if($bug->assignedTo != $oldBug->assignedTo) $bug->assignedDate = $now;

            /* If resolution is not empty, set the confirmed. */
            if($bug->resolution != '') $bug->confirmed = 1;

            /* If the bug is resolved, set resolved date and bug status. */
            if(($bug->resolvedBy != '' || $bug->resolution != '') && strpos(',resolved,closed,', ",{$oldBug->status},") === false)
            {
                $bug->resolvedDate = $now;
                $bug->status       = 'resolved';
            }

            /* If the bug without resolver is resolved, set resolver. */
            if($bug->resolution != '' && $bug->resolvedBy == '') $bug->resolvedBy = $this->app->user->account;

            /* If the bug without assignee is resolved, set assignee and assigned date. */
            if($bug->resolution != '' && $bug->assignedTo == '')
            {
                $bug->assignedTo   = $oldBug->openedBy;
                $bug->assignedDate = $now;
            }
        }
        return $bugs;
    }

    /**
     * 为批量编辑 bug 分配变量。
     * Assign variables for batch edit.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @access protected
     * @return void
     */
    protected function assignBatchEditVars(int $productID, string $branch): void
    {
        /* Initialize vars.*/
        $bugIdList = array_unique($this->post->bugIdList);
        $bugs      = $this->bug->getByIdList($bugIdList);

        /* Set menu and get product id list. */
        if($this->app->tab == 'product') $this->product->setMenu($productID);
        if($productID)
        {
            $this->qa->setMenu($productID, $branch);

            $productIdList = array($productID => $productID);
        }
        else
        {
            $this->app->loadLang('my');
            $this->lang->task->menu = $this->lang->my->menu->work;
            $this->lang->my->menu->work['subModule'] = 'bug';

            $productIdList = array_column($bugs, 'product', 'product');
        }

        /* Get products. */
        $products = $this->product->getByIdList($productIdList);

        /* Get custom Fields. */
        foreach(explode(',', $this->config->bug->list->customBatchEditFields) as $field) $customFields[$field] = $this->lang->bug->$field;

        $this->view->title        = ($productID ? (zget($products, $productID, '', $products[$productID]->name . $this->lang->colon) . "BUG") : '') . $this->lang->bug->batchEdit;
        $this->view->customFields = $customFields;

        /* Judge whether the editedBugs is too large and set session. */
        $countInputVars  = count($bugs) * (count(explode(',', $this->config->bug->custom->batchEditFields)) + 2);
        $showSuhosinInfo = common::judgeSuhosinSetting($countInputVars);
        if($showSuhosinInfo)
        {
            $this->view->suhosinInfo = extension_loaded('suhosin') ? sprintf($this->lang->suhosinInfo, $countInputVars) : sprintf($this->lang->maxVarsInfo, $countInputVars);
            $this->display();
        }

        /* Assign product related variables. */
        $branchTagOption = $this->assignProductRelatedVars($bugs, $products);
        $this->view->productID = $productID;
        $this->view->branch    = $branch;

        /* Assign users. */
        $this->assignUsersForBatchEdit($bugs, $productIdList, $branchTagOption);
    }

    /**
     * 分配产品相关的变量。
     * Assign product related variables.
     *
     * @param  array   $bugs
     * @param  array   $products
     * @access private
     * @return array
     */
    private function assignProductRelatedVars(array $bugs, array $products): array
    {
        /* Get modules, bugs and plans of the products. */
        $branchProduct   = false;
        $modules         = array();
        $branchTagOption = array();
        $productBugList  = array();
        $productPlanList = array();
        foreach($products as $product)
        {
            if(!isset($productPlanList[$product->id])) $productPlanList[$product->id] = array();

            $branches = '0';
            if($product->type != 'normal')
            {
                $branchPairs   = $this->loadModel('branch')->getPairs($product->id, 'withClosed');
                $branches      = array_keys($branchPairs);
                $branchProduct = true;

                foreach($branchPairs as $branchID => $branchName)
                {
                    $branchTagOption[$product->id][$branchID] = "/{$product->name}/{$branchName}";
                    $productPlanList[$product->id][$branchID] = $this->loadModel('productplan')->getPairs($product->id, $branchID, '', true);
                    $productBugList[$product->id][$branchID]  = $this->bug->getProductBugPairs($product->id, "0,{$branchID}");
                }
            }
            else
            {
                $productPlanList[$product->id][0] = $this->loadModel('productplan')->getPairs($product->id, 0, '', true);
                $productBugList[$product->id][0]  = $this->bug->getProductBugPairs($product->id, "");
            }

            $modulePairs           = $this->tree->getOptionMenu($product->id, 'bug', 0, $branches);
            $modules[$product->id] = $product->type != 'normal' ? $modulePairs : array(0 => $modulePairs);
        }

        $productPlanOptions = array();
        foreach($productPlanList as $productID => $productPlans)
        {
            $productPlanOptions[$productID] = array();
            foreach($productPlans as $branchID => $branchPlans)
            {
                $productPlanOptions[$productID][$branchID] = array();
                foreach($branchPlans as $bugID => $bugTitle)
                {
                    $productPlanOptions[$productID][$branchID][] = array('text' => $bugTitle, 'value' => $bugID);
                }
            }
        }

        /* Get module of the bugs, and set bug plans. */
        foreach($bugs as $bug)
        {
            if(!isset($modules[$bug->product][0])) $modules[$bug->product][0] = array();
            if(!isset($modules[$bug->product][$bug->branch]) && isset($modules[$bug->product])) $modules[$bug->product][$bug->branch] = $modules[$bug->product][0] + $this->tree->getModulesName($bug->module);
            $bug->plans = isset($productPlanOptions[$bug->product]) && isset($productPlanOptions[$bug->product][$bug->branch]) ? $productPlanOptions[$bug->product][$bug->branch] : array();
        }

        $bugModules = array();
        foreach($modules as $productID => $productModules)
        {
            $bugModules[$productID] = array();
            foreach($productModules as $branchID => $branchModules)
            {
                $bugModules[$productID][$branchID] = array();
                foreach($branchModules as $moduleID => $module)
                {
                    $bugModules[$productID][$branchID][] = array('text' => $module, 'value' => $moduleID);
                }
            }
        }

        $branchOptions = array();
        foreach($branchTagOption as $productID => $productBranches)
        {
            $branchOptions[$productID] = array();
            foreach($productBranches as $branchID => $branchName)
            {
                $branchOptions[$productID][] = array('text' => $branchName, 'value' => $branchID);
            }
        }

        $productBugOptions = array();
        foreach($productBugList as $productID => $productBugs)
        {
            $productBugOptions[$productID] = array();
            foreach($productBugs as $branchID => $branchBugs)
            {
                $productBugOptions[$productID][$branchID] = array();
                foreach($branchBugs as $bugID => $bugTitle)
                {
                    $productBugOptions[$productID][$branchID][] = array('text' => $bugTitle, 'value' => $bugID);
                }
            }
        }

        $this->view->bugs              = $bugs;
        $this->view->branchProduct     = $branchProduct;
        $this->view->modules           = $bugModules;
        $this->view->productBugOptions = $productBugOptions;
        $this->view->branchTagOption   = $branchOptions;
        $this->view->products          = $products;

        return $branchTagOption;
    }

    /**
     * 为批量编辑 bugs 分配人员。
     * Assign users for batch edit.
     *
     * @param  array   $bugs
     * @param  array   $productIdList
     * @param  array   $branchTagOption
     * @access private
     * @return void
     */
    private function assignUsersForBatchEdit(array $bugs, array $productIdList, array $branchTagOption): void
    {
        /* If current tab is execution or project, get project, execution, product team members of bugs.*/
        if($this->app->tab == 'execution' || $this->app->tab == 'project')
        {
            $projectIdList = array_column($bugs, 'project', 'project');
            $project       = $this->loadModel('project')->getByID(key($projectIdList));
            if(!empty($project) && empty($project->multiple))
            {
                $this->config->bug->custom->batchEditFields = str_replace('productplan', '', $this->config->bug->custom->batchEditFields);
                $this->config->bug->list->customBatchEditFields = str_replace(',productplan,', ',', $this->config->bug->list->customBatchEditFields);
            }

            $productMembers = array();
            foreach($productIdList as $id)
            {
                $branches   = zget($branchTagOption, $id, array());
                $branchList = array_keys($branches);
                foreach($branchList as $branchID)
                {
                    $members = $this->bug->getProductMemberPairs($id, (string)$branchID);
                    $productMembers[$id][$branchID] = array_filter($members);
                }
            }

            /* Get members of projects. */
            $projectMembers     = array();
            $projectMemberGroup = $this->project->getTeamMemberGroup($projectIdList);
            foreach($projectIdList as $projectID)
            {
                $projectTeam = zget($projectMemberGroup, $projectID, array());
                foreach($projectTeam as $user) $projectMembers[$projectID][$user->account] = $user->realname;
            }

            /* Get members of executions. */
            $executionMembers     = array();
            $executionIdList      = array_column($bugs, 'execution', 'execution');
            $executionMemberGroup = $this->loadModel('execution')->getMembersByIdList($executionIdList);
            foreach($executionIdList as $executionID)
            {
                $executionTeam = zget($executionMemberGroup, $executionID, array());
                foreach($executionTeam as $user) $executionMembers[$executionID][$user->account] = $user->realname;
            }

            $this->view->productMembers   = $productMembers;
            $this->view->projectMembers   = $projectMembers;
            $this->view->executionMembers = $executionMembers;
        }

        $this->view->users = $this->user->getPairs('devfirst');
    }

    /**
     * 批量创建bug前处理上传图片。
     * Before batch creating bugs, process the uploaded images.
     *
     * @param  object    $bug
     * @param  string    $uploadImage
     * @param  array     $bugImagesFiles
     * @access protected
     * @return array
     */
    protected function processImageForBatchCreate(object $bug, string|null $uploadImage, array $bugImagesFiles): array
    {
        /* When the bug is created by uploading an image, add the image to the step of the bug. */
        if(!empty($uploadImage))
        {
            $this->loadModel('file');

            $file     = $bugImagesFiles[$uploadImage];
            $realPath = $file['realpath'];

            if(rename($realPath, $this->file->savePath . $this->file->getSaveName($file['pathname'])))
            {
                if(in_array($file['extension'], $this->config->file->imageExtensions))
                {
                    $file['addedBy']    = $this->app->user->account;
                    $file['addedDate']  = helper::now();
                    $this->loadModel('file')->saveFile($file, 'realpath');

                    $fileID = $this->dao->lastInsertID();
                    $bug->steps .= '<img src="{' . $fileID . '.' . $file['extension'] . '}" alt="" />';
                }
            }
            else
            {
                unset($file);
            }
        }

        return !empty($file) ? $file : array();
    }

    /**
     * 创建bug后存储上传的文件。
     * Save files after create a bug.
     *
     * @param  int     $bugID
     * @access private
     * @return bool
     */
    private function updateFileAfterCreate(int $bugID): bool
    {
        if(isset($this->post->resultFiles))
        {
            $resultFiles = $this->post->resultFiles;
            if(isset($this->post->deleteFiles))
            {
                foreach($this->post->deleteFiles as $deletedCaseFileID) $resultFiles = trim(str_replace(",$deletedCaseFileID,", ',', ",$resultFiles,"), ',');
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

        $uid = $this->post->uid ? $this->post->uid : '';
        $this->loadModel('file')->updateObjectID($uid, $bugID, 'bug');
        $this->file->saveUpload('bug', $bugID);

        return !dao::isError();
    }

    /**
     * 创建bug后更新执行看板。
     * Update execution kanban after create a bug.
     *
     * @param  object  $bug
     * @param  int     $laneID
     * @param  int     $columnID
     * @param  string  $from
     * @access private
     * @return void
     */
    private function updateKanbanAfterCreate(object $bug, int $laneID, int $columnID, string $from): void
    {
        $bugID       = $bug->id;
        $executionID = $bug->execution;

        if($executionID)
        {
            $this->loadModel('kanban');

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'bug', (string)$bugID);
            if(empty($laneID) or empty($columnID))    $this->kanban->updateLane($executionID, 'bug');
        }

        /* Callback the callable method to process the related data for object that is transfered to bug. */
        if($from && isset($this->config->bug->fromObjects[$from]) && is_callable(array($this, $this->config->bug->fromObjects[$from]['callback']))) call_user_func(array($this, $this->config->bug->fromObjects[$from]['callback']), $bugID);
    }

    /**
     * 为create方法添加动态。
     * Add action for create function.
     *
     * @param  int     $bug
     * @param  int     $todoID
     * @access private
     * @return bool
     */
    private function updateTodoAfterCreate(int $bugID, int $todoID): bool
    {
        $this->dao->update(TABLE_TODO)->set('status')->eq('done')->where('id')->eq($todoID)->exec();
        $this->action->create('todo', $todoID, 'finished', '', "BUG:$bugID");
        if($this->config->edition != 'open')
        {
            $todo = $this->dao->select('type, objectID')->from(TABLE_TODO)->where('id')->eq($todoID)->fetch();
            if($todo->type == 'feedback' && $todo->objectID) $this->loadModel('feedback')->updateStatus('todo', $todo->objectID, 'done');
        }

        return !dao::isError();
    }

    /**
     * 更新bug模板。
     * Update bug templete.
     *
     * @param  object  $bug
     * @param  array   $fields
     * @access private
     * @return object
     */
    private function updateBug(object $bug, array $fields): object
    {
        foreach($fields as $field => $value) $bug->$field = $value;

        return $bug;
    }

    /**
     * 创建完 bug 后的相关处理。
     * Relevant processing after create bug.
     *
     * @param  object    $bug
     * @param  array     $params
     * @param  string    $from
     * @access protected
     * @return bool
     */
    protected function afterCreate(object $bug, array $params, string $from = ''): bool
    {
        /* 将 bug 的模块保存到 cookie。*/
        /* Set module of bug to cookie. */
        helper::setcookie('lastBugModule', (string)$bug->module);

        $this->updateFileAfterCreate($bug->id);

        list($laneID, $columnID) = $this->getKanbanVariable($params);
        $this->updateKanbanAfterCreate($bug, $laneID, $columnID, $from);

        $todoID = isset($params['todoID']) ? $params['todoID'] : 0;
        if($todoID) $this->updateTodoAfterCreate($bug->id, (int)$todoID);

        return !dao::isError();
    }

    /**
     * 更新完 bug 后的相关处理。
     * Relevant processing after updating bug.
     *
     * @param  object    $bug
     * @param  object    $oldBug
     * @access protected
     * @return bool
     */
    protected function afterUpdate(object $bug, object $oldBug): bool
    {
        /* 解除旧的版本关联关系，关联新的版本。*/
        /* Unlink old resolved build and link new resolved build. */
        if($bug->resolution == 'fixed' && !empty($bug->resolvedBuild) && $bug->resolvedBuild != $oldBug->resolvedBuild)
        {
            if(!empty($oldBug->resolvedBuild)) $this->loadModel('build')->unlinkBug($oldBug->resolvedBuild, $bug->id);
            $this->bug->linkBugToBuild($bug->id, $bug->resolvedBuild);
        }

        /* 记录解除旧的计划关联关系和关联新的计划的历史。*/
        /* Create actions for linking new plan and unlinking old plan. */
        if($bug->plan != $oldBug->plan)
        {
            $this->loadModel('action');
            if(!empty($oldBug->plan)) $this->action->create('productplan', $oldBug->plan, 'unlinkbug', '', $bug->id);
            if(!empty($bug->plan))    $this->action->create('productplan', $bug->plan,    'linkbug',   '', $bug->id);
        }

        $this->bug->updateRelatedBug($bug->id, $bug->relatedBug, $oldBug->relatedBug);

        /* 给 bug 解决者积分奖励。*/
        /* Add score to the user who resolved the bug. */
        if(!empty($bug->resolvedBy)) $this->loadModel('score')->create('bug', 'resolve', $bug);

        /* 更新 bug 所属看板的泳道。*/
        /* Update the lane of the bug kanban. */
        if($bug->execution and $bug->status != $oldBug->status) $this->loadModel('kanban')->updateLane($bug->execution, 'bug');

        /* 更新反馈的状态。*/
        /* Update the status of feedback. */
        if(($this->config->edition != 'open') && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);

        /* 更新 bug 的附件。*/
        /* Update the files of bug. */
        $this->loadModel('file')->processFile4Object('bug', $oldBug, $bug);

        return !dao::isError();
    }

    /**
     * 批量创建bug后的其他处理。
     * Processing after batch creation of bug.
     *
     * @param  object    $bug
     * @param  array     $output
     * @param  string    $uploadImage
     * @param  array     $file
     * @access protected
     * @return bool
     */
    protected function afterBatchCreate(object $bug, array $output, string|null $uploadImage, array $file): bool
    {
        /* If bug has the execution, update kanban data. */
        if($bug->execution)
        {
            /* Get lane id, remove laneID from bug.  */
            $laneID = !empty($bug->laneID) ? $bug->laneID : zget($output, 'laneID', 0);
            unset($bug->laneID);

            $columnID = $this->loadModel('kanban')->getColumnIDByLaneID((int)$laneID, 'unconfirmed');
            if(empty($columnID)) $columnID = zget($output, 'columnID', 0);

            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($bug->execution, $laneID, $columnID, 'bug', (string)$bug->id);
            if(empty($laneID) or empty($columnID))    $this->kanban->updateLane($bug->execution, 'bug');
        }

        /* When the bug is created by uploading the image, add the image to the file of the bug. */
        if(!empty($uploadImage) and !empty($file))
        {
            $file['objectType'] = 'bug';
            $file['objectID']   = $bug->id;
            $file['addedBy']    = $this->app->user->account;
            $file['addedDate']  = helper::now();
            $this->dao->insert(TABLE_FILE)->data($file, 'realpath')->exec();
            unset($file);
        }

        return !dao::isError();
    }

    /**
     * 返回不同的结果。
     * Respond after updating bug.
     *
     * @param  int       $bugID
     * @param  array     $changes
     * @param  int       $regionID
     * @param  string    $message
     * @param  bool      $isInKanban true|false
     * @access protected
     * @return bool|int
     */
    protected function responseAfterOperate(int $bugID, array $changes = array(), string $message = '', bool $isInKanban = false): bool|int
    {
        if(!$message) $message = $this->lang->saveSuccess;
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'message' => $message, 'data' => $bugID));

        /* 如果 bug 转任务并且 bug 的状态发生变化，提示是否更新任务状态。*/
        /* This bug has been converted to a task, update the status of the related task or not. */
        $bug = $this->bug->getByID($bugID);
        if($bug->toTask and !empty($changes))
        {
            foreach($changes as $change)
            {
                if($change['field'] == 'status')
                {
                    $confirmedURL = $this->createLink('task', 'view', "taskID=$bug->toTask");
                    $canceledURL  = $this->server->http_referer;
                    return $this->send(array('result' => 'success', 'message' => $message, 'load' => array('confirm' => $this->lang->bug->notice->remindTask, 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
                }
            }
        }

        /* 在弹窗里编辑 bug 时的返回。*/
        /* Respond after updating in modal. */
        if(isInModal()) return $this->responseInModal($message, $isInKanban);

        return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => $this->createLink('bug', 'view', "bugID=$bugID")));
    }

    /**
     * 在弹窗中操作后的返回。
     * Respond after operating in modal.
     *
     * @param  string    $message
     * @param  bool      $isInKanban true|false
     * @access protected
     * @return void
     */
    protected function responseInModal(string $message = '', bool $isInKanban = false)
    {
        /* 在执行应用下，编辑看板中的 bug 数据时，更新看板数据。*/
        /* Update kanban data after updating bug in kanban. */
        if(!$message) $message = $this->lang->saveSuccess;
        if($this->app->tab == 'execution' && $isInKanban) return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

        return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true, 'load' => true));
    }

    /**
     * 创建 bug 后的返回结果。
     * respond after deleting.
     *
     * @param  object    $bug
     * @param  array     $params
     * @param  string    $message
     * @access protected
     * @return bool
     */
    protected function responseAfterCreate(object $bug, array $params, string $message = ''): bool
    {
        $executionID = $bug->execution ? $bug->execution : (int)zget($params, 'executionID', $this->session->execution);

        /* Return bug id when call the API. */
        if(!$message) $message = $this->lang->saveSuccess;
        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'id' => $bug->id));
        if(defined('RUN_MODE') && RUN_MODE == 'api') return $this->send(array('status' => 'success', 'data' => $bug->id));

        if(isInModal()) return $this->send($this->responseInModal());

        if($this->app->tab == 'execution')
        {
            if(!preg_match("/(m=|\/)execution(&f=|-)bug(&|-|\.)?/", $this->session->bugList))
            {
                $location = $this->session->bugList;
            }
            else
            {
                $location = $this->createLink('execution', 'bug', "executionID=$executionID");
            }
        }
        elseif($this->app->tab == 'project')
        {
            $location = $this->createLink('project', 'bug', "projectID=" . zget($params, 'projectID', $this->session->project));
        }
        else
        {
            helper::setcookie('bugModule', '0', 0);
            $location = $this->createLink('bug', 'browse', "productID={$bug->product}&branch=$bug->branch&browseType=byModule&param={$bug->module}&orderBy=id_desc");
        }
        if($this->app->getViewType() == 'xhtml') $location = $this->createLink('bug', 'view', "bugID={$bug->id}", 'html');

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $location));
    }

    /**
     * 删除 bug 后不同的返回结果。
     * respond after deleting.
     *
     * @param  object    $bug
     * @param  string    $from
     * @param  string    $message
     * @access protected
     * @return array
     */
    protected function responseAfterDelete(object $bug, string $from, string $message = ''): array
    {
        if(!$message) $message = $this->lang->saveSuccess;
        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message));

        /* 如果 bug 转任务，删除 bug 时确认是否更新任务状态。*/
        /* If the bug has been transfered to a task, confirm to update task when delete the bug. */
        if($bug->toTask)
        {
            $task = $this->task->getByID($bug->toTask);
            if(!$task->deleted)
            {
                $confirmedURL = $this->createLink('task', 'view', "taskID={$bug->toTask}");
                $canceledURL  = $this->createLink('bug', 'view', "bugID={$bug->id}");
                return $this->send(array('result' => 'success', 'load' => array('confirm' => sprintf($this->lang->bug->notice->remindTask, $bug->toTask), 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
            }
        }

        /* 在弹窗中删除 bug 时的返回。*/
        /* Respond when delete bug in modal.。*/
        if(isInModal()) return $this->send(array('result' => 'success', 'load' => true));

        /* 在任务看板中删除 bug 时的返回。*/
        /* Respond when delete in task kanban. */
        if($from == 'taskkanban') return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "refreshKanban()"));

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->session->bugList ? $this->session->bugList : inlink('browse', "productID={$bug->product}")));
    }

    /**
     * 批量创建bug后返回响应。
     * Response after batch create.
     *
     * @param  int       $productID
     * @param  string    $branch
     * @param  int       $executionID
     * @param  array     $bugIdList
     * @param  string    $message
     * @access protected
     * @return bool
     */
    protected function responseAfterBatchCreate(int $productID, string $branch, int $executionID, array $bugIdList, string $message = ''): bool
    {
        helper::setcookie('bugModule', '0', 0);

        /* Remove upload image file and session. */
        if(!empty($this->post->uploadImage) and !empty($this->session->bugImagesFile))
        {
            $classFile = $this->app->loadClass('zfile');
            $file      = current($_SESSION['bugImagesFile']);
            $realPath  = dirname($file['realpath']);
            if(is_dir($realPath)) $classFile->removeDir($realPath);
            unset($_SESSION['bugImagesFile']);
        }

        if(!$message) $message = $this->lang->saveSuccess;
        /* Return bug id list when call the API. */
        if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $message, 'idList' => $bugIdList));

        /* Respond after updating in modal. */
        if(isInModal() && $executionID) return $this->responseInModal();

        /* If link from no head then reload. */
        if(isInModal()) return $this->send(array('result' => 'success', 'message' => $message, 'closeModal' => true));

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->createLink('bug', 'browse', "productID={$productID}&branch={$branch}&browseType=unclosed&param=0&orderBy=id_desc")));
    }

    /**
     * 批量编辑 bug 后的一些操作。
     * Operate after batch edit bugs.
     *
     * @param  object    $bug
     * @param  object    $oldBug
     * @access protected
     * @return void
     */
    protected function operateAfterBatchEdit(object $bug, object $oldBug): void
    {
        /* 解决 bug 奖励积分。*/
        /* Record score when bug is resolved. */
        if(isset($bug->status) && $bug->status == 'resolved' && $oldBug->status == 'active') $this->loadModel('score')->create('bug', 'resolve', $bug, $bug->resolvedBy);

        /* 更新相关反馈的状态。*/
        /* Update status of related feedback. */
        if($this->config->edition != 'open' && $oldBug->feedback) $this->loadModel('feedback')->updateStatus('bug', $oldBug->feedback, $bug->status, $oldBug->status);
    }

    /**
     * 获取待处理的任务和计划列表。
     * Get toTaskIdList, unlinkPlans and link2Plans to be processed.
     *
     * @param  object    $bug
     * @param  object    $oldBug
     * @access protected
     * @return array
     */
    protected function getToBeProcessedData(object $bug, object $oldBug): array
    {
        static $toTaskIdList = array();
        static $unlinkPlans  = array();
        static $link2Plans   = array();

        /* 获取转任务的任务列表。*/
        /* Get task list that is transfered by bug. */
        if($oldBug->toTask != 0 && isset($bug->status) && $bug->status != $oldBug->status) $toTaskIdList[$oldBug->toTask] = $oldBug->toTask;

        /* Bug 的所属计划变更后，获取变更的计划列表。*/
        /* Get plan list that has been changed. */
        if($bug->plan != $oldBug->plan)
        {
            if(!empty($oldBug->plan)) $unlinkPlans[$oldBug->plan] = empty($unlinkPlans[$oldBug->plan]) ? $bug->id : "{$unlinkPlans[$oldBug->plan]},{$oldBug->id}";
            if(!empty($bug->plan))    $link2Plans[$bug->plan]     = empty($link2Plans[$bug->plan])     ? $bug->id : "{$link2Plans[$bug->plan]},{$oldBug->id}";
        }

        return array($toTaskIdList, $unlinkPlans, $link2Plans);
    }

    /**
     * 获得 batchEdit 方法的 response。
     * Get response for batchEdit.
     *
     * @param  array     $output
     * @param  string    $message
     * @access protected
     * @return bool
     */
    protected function responseAfterBatchEdit(array $toTaskIdList, string $message = ''): bool
    {
        if(!empty($toTaskIdList))
        {
            $taskID       = key($toTaskIdList);
            $confirmedURL = $this->createLink('task', 'view', 'taskID=' . $taskID);
            $canceledURL  = $this->server->HTTP_REFERER;
            return $this->send(array('result' => 'success', 'message' => $message, 'load' => array('confirm' => sprintf($this->lang->bug->notice->remindTask, $taskID), 'confirmed' => $confirmedURL, 'canceled' => $canceledURL)));
        }

        return $this->send(array('result' => 'success', 'message' => $message, 'load' => $this->session->bugList));
    }

    /**
     * 初始化一个默认的bug模板。
     * Init a default bug templete.
     *
     * @param  array     $fields
     * @access protected
     * @return object
     */
    protected function initBug(array $fields): object
    {
        $bug = new stdclass();
        $bug->projectID   = 0;
        $bug->moduleID    = 0;
        $bug->executionID = 0;
        $bug->productID   = 0;
        $bug->taskID      = 0;
        $bug->storyID     = 0;
        $bug->buildID     = 0;
        $bug->caseID      = 0;
        $bug->runID       = 0;
        $bug->testtask    = 0;
        $bug->version     = 0;
        $bug->title       = '';
        $bug->steps       = $this->lang->bug->tplStep . $this->lang->bug->tplResult . $this->lang->bug->tplExpect;
        $bug->os          = '';
        $bug->browser     = '';
        $bug->assignedTo  = '';
        $bug->deadline    = '';
        $bug->mailto      = '';
        $bug->keywords    = '';
        $bug->severity    = 3;
        $bug->type        = 'codeerror';
        $bug->pri         = 3;
        $bug->color       = '';
        $bug->feedbackBy  = '';
        $bug->notifyEmail = '';

        $bug->project      = '';
        $bug->branch       = '';
        $bug->execution    = '';
        $bug->projectModel = '';
        $bug->projects   = array();
        $bug->executions = array();
        $bug->products   = array();
        $bug->stories    = array();
        $bug->builds     = array();
        $bug->branches   = array();

        if(!empty($fields)) $bug = $this->updateBug($bug, $fields);

        return $bug;
    }

    /**
     * 解析extras，如果bug来源于某个对象 (bug, case, testtask, todo) ，使用对象的一些属性对bug赋值。
     * Extract extras, if bug come from an object(bug, case, testtask, todo), get some value from object.
     *
     * @param  object    $bug
     * @param  array     $output
     * @access protected
     * @return object
     */
    protected function extractObjectFromExtras(object $bug, array $output): object
    {
        extract($output);
        if(isset($resultID)) $resultID = (int)$resultID;
        if(isset($caseID))   $caseID   = (int)$caseID;

        /* 获取用例的标题、步骤、所属需求、所属模块、版本、所属执行。 */
        /* Get title, steps, storyID, moduleID, version, executionID from case. */
        if(isset($runID) and $runID and isset($resultID) and $resultID) $fields = $this->bug->getBugInfoFromResult($resultID, 0, isset($stepIdList) ? $stepIdList : '');// If set runID and resultID, get the result info by resultID as template.
        if(isset($runID) and !$runID and isset($caseID) and $caseID) $fields = $this->bug->getBugInfoFromResult($resultID, $caseID, isset($stepIdList) ? $stepIdList : '');// If not set runID but set caseID, get the result info by resultID and case info.
        if(isset($fields))
        {
            if(isset($fields['moduleID']) && !isset($this->view->moduleOptionMenu[$fields['moduleID']])) $fields['moduleID'] = 0;
            $bug = $this->updateBug($bug, $fields);
        }

        /* 获得bug的所属项目、所属模块、所属执行、关联产品、关联任务、关联需求、关联版本、关联用例、标题、步骤、严重程度、类型、指派给、截止日期、操作系统、浏览器、抄送给、关键词、颜色、所属测试单、反馈人、通知邮箱、优先级。 */
        /* Get projectID, moduleID, executionID, productID, taskID, storyID, buildID, caseID, title, steps, severity, type, assignedTo, deadline, os, browser, mailto, keywords, color, testtask, feedbackBy, notifyEmail, pri from case. */
        if(isset($bugID) and $bugID)
        {
            $bugInfo = $this->bug->getById((int)$bugID);

            $fields = array('projectID' => $bugInfo->project, 'moduleID' => $bugInfo->module, 'executionID' => $bugInfo->execution, 'productID' => $bugInfo->product, 'taskID' => $bugInfo->task, 'storyID' => $bugInfo->story, 'buildID' => $bugInfo->openedBuild,
                'caseID' => $bugInfo->case, 'title' => $bugInfo->title, 'steps' => $bugInfo->steps, 'severity' => $bugInfo->severity, 'type' => $bugInfo->type, 'assignedTo' => $bugInfo->assignedTo, 'deadline' => (helper::isZeroDate($bugInfo->deadline) ? '' : $bugInfo->deadline),
                'os' => $bugInfo->os, 'browser' => $bugInfo->browser, 'mailto' => $bugInfo->mailto, 'keywords' => $bugInfo->keywords, 'color' => $bugInfo->color, 'testtask' => $bugInfo->testtask, 'feedbackBy' => $bugInfo->feedbackBy, 'notifyEmail' => $bugInfo->notifyEmail,
                'pri' => ($bugInfo->pri == 0 ? 3 : $bugInfo->pri));

            $bug = $this->updateBug($bug, $fields);
        }

        /* 获取测试单的版本。 */
        /* Get buildID from testtask. */
        if(isset($testtask) and $testtask)
        {
            $testtask = $this->loadModel('testtask')->getByID((int)$testtask);
            $bug      = $this->updateBug($bug, array('buildID' => $testtask->build));
        }

        /* 获得代办的标题、步骤和优先级。 */
        /* Get title, steps, pri from todo. */
        if(isset($todoID) and $todoID)
        {
            $todo = $this->loadModel('todo')->getById((int)$todoID);
            $bug  = $this->updateBug($bug, array('title' => $todo->name, 'steps' => $todo->desc, 'pri' => $todo->pri));
        }

        return $bug;
    }

    /**
     * 将报表的默认设置合并到当前报表。
     * Merge the default chart settings and the settings of current chart.
     *
     * @param  string    $chartCode
     * @param  string    $chartType
     * @access protected
     * @return object
     */
    protected function mergeChartOption(string $chartCode, string $chartType = 'default'): object
    {
        $chartOption  = $this->lang->bug->report->$chartCode;
        $commonOption = $this->lang->bug->report->options;

        $chartOption->graph->caption = $this->lang->bug->report->charts[$chartCode];
        if(!empty($chartType) && $chartType != 'default') $chartOption->type = $chartType;

        if(!isset($chartOption->type))   $chartOption->type   = $commonOption->type;
        if(!isset($chartOption->width))  $chartOption->width  = $commonOption->width;
        if(!isset($chartOption->height)) $chartOption->height = $commonOption->height;

        foreach($commonOption->graph as $key => $value) if(!isset($chartOption->graph->$key)) $chartOption->graph->$key = $value;

        return $chartOption;
    }
}
