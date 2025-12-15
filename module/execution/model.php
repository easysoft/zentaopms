<?php
declare(strict_types=1);
/**
 * The model file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: model.php 5118 2013-07-12 07:41:41Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class executionModel extends model
{
    /* The members every linking. */
    const LINK_MEMBERS_ONE_TIME = 20;

    /**
     * 检查执行的权限
     * Check the privilege.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function checkPriv(int $executionID): bool
    {
        if(commonModel::isTutorialMode()) return true;
        return !empty($executionID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->sprints},", ",{$executionID},") !== false));
    }

    /**
     * 提示没有查看执行的权限并跳转到执行列表。
     * Tip no permission to view the execution and jump to the execution list.
     *
     * @access public
     * @return bool|void
     */
    public function accessDenied()
    {
        if(commonModel::isTutorialMode()) return true;

        return $this->app->control->sendError($this->lang->execution->accessDenied, helper::createLink('execution', 'all'));
    }

    /**
     * 获取系统关闭的功能。
     * Get the system close function.
     *
     * @param  object $execution
     * @access public
     * @return array
     */
    public function getExecutionFeatures(object $execution): array
    {
        $features = array('story' => true, 'task' => true, 'qa' => true, 'devops' => true, 'burn' => true, 'build' => true, 'other' => true, 'plan' => true);

        /* Unset story, bug, build and testtask if type is ops. */
        if($execution->lifetime == 'ops')
        {
            $features['story']  = false;
            $features['qa']     = false;
            $features['build']  = false;
            $features['burn']   = false;
        }
        elseif(!empty($execution->attribute))
        {
            $features['other'] = false;

            /* Product-related features are disabled during the request, design, and review stage. */
            if(in_array($execution->attribute, array('request', 'design', 'review')))
            {
                $features['qa']     = false;
                $features['devops'] = false;
                $features['build']  = false;

                if(in_array($execution->attribute, array('request', 'review'))) $features['plan'] = false;
                if($execution->attribute == 'review') $features['story'] = false;
            }
            if(empty($execution->hasProduct) && in_array($execution->attribute, array('plan', 'develop', 'qualify', 'launch'))) $features['plan'] = false;
        }

        /* The plan function is disabled for no-product project. */
        if(isset($execution->projectInfo) && !empty($execution->projectInfo->model) && in_array($execution->projectInfo->model, array('waterfall', 'kanban', 'waterfallplus')) && empty($execution->projectInfo->hasProduct))
        {
            $features['plan'] = false;
        }

        return $features;
    }

    /**
     * 设置执行导航。
     * Set menu.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setMenu(int $executionID)
    {
        $execution = commonModel::isTutorialMode() ? $this->loadModel('tutorial')->getExecution() : $this->fetchByID((int)$executionID);
        if(!$execution) return;

        if($execution->type == 'kanban') $this->executionTao->setKanbanMenu();

        /* Check execution permission. */
        $executions = $this->fetchPairs($execution->project, 'all');
        if(!$executionID && $this->session->execution) $executionID = $this->session->execution;
        if(!$executionID) $executionID = key($executions);
        $canAccess = !empty($executions) && isset($executions[$executionID]) && $this->checkPriv($executionID);
        if($execution->multiple && !$execution->isTpl && !$canAccess) return $this->accessDenied();
        if(empty($executionID)) return;

        /* Replaces the iterated language with the stage. */
        if($execution->type == 'stage')
        {
            global $lang;
            $this->app->loadLang('project');
            $lang->executionCommon = $lang->project->stage;
            include $this->app->getModulePath('', 'execution') . 'lang/' . $this->app->getClientLang() . '.php';
        }

        /* Set secondary navigation based on the conditions. */
        $this->removeMenu($execution);

        if($this->cookie->executionMode == 'noclosed' && $execution && ($execution->status == 'done' || $execution->status == 'closed'))
        {
            helper::setcookie('executionMode', 'all');
            $this->cookie->executionMode = 'all';
        }

        $this->session->set('execution', $executionID, $this->app->tab);
        $this->lang->switcherMenu = $this->getSwitcher($executionID, (string)$this->app->rawModule, (string)$this->app->rawMethod);
        common::setMenuVars('execution', $executionID);

        if($execution->type != 'kanban' && ($this->app->getModuleName() == 'repo' || $this->app->getModuleName() == 'mr')) $this->loadModel('repo')->setHideMenu($executionID);

        /* Set stroy navigation for no-product project. */
        $this->loadModel('project')->setNoMultipleMenu($executionID);
        if(isset($this->lang->execution->menu->story['dropMenu']) && $this->app->getMethodName() == 'storykanban')
        {
            $this->lang->execution->menu->story['link']            = str_replace(array($this->lang->common->story, 'story'), array($this->lang->SRCommon, 'storykanban'), $this->lang->execution->menu->story['link']);
            $this->lang->execution->menu->story['dropMenu']->story = str_replace('execution|story', 'execution|storykanban', $this->lang->execution->menu->story['dropMenu']->story);
        }

        /* 模板执行过滤部分导航菜单。 */
        if(!empty($execution->isTpl))
        {
            dao::$filterTpl = 'never';
            $this->lang->execution->common = $this->lang->execution->template;
            if(empty($execution->multiple)) $this->lang->project->common = $this->lang->execution->template;

            unset($this->lang->execution->menu->burn);
            unset($this->lang->execution->menu->kanban);
            unset($this->lang->execution->menu->story);
            unset($this->lang->execution->menu->qa);
            unset($this->lang->execution->menu->devops);
            unset($this->lang->execution->menu->build);
            unset($this->lang->execution->menu->release);
            unset($this->lang->execution->menu->effort);
            unset($this->lang->execution->menu->more);

            if(!empty($this->lang->execution->menu->view['subMenu']->gantt))
            {
                $this->lang->execution->menu->gantt = $this->lang->execution->menu->view['subMenu']->gantt;

                $taskOrder = 0;
                foreach($this->lang->execution->menuOrder as $order => $menu) if($menu == 'task') $taskOrder = $order;
                $this->lang->execution->menuOrder[$taskOrder + 1] = 'gantt';

                if(!empty($this->lang->project->menuOrder))
                {
                    $taskOrder = 0;
                    foreach($this->lang->project->menuOrder as $order => $menu) if($menu == 'task') $taskOrder = $order;
                    $this->lang->project->menuOrder[$taskOrder + 1] = 'gantt';
                }
            }

            if(!empty($this->lang->execution->menu->other['dropMenu']->pssp))
            {
                $this->lang->execution->menu->pssp = $this->lang->execution->menu->other['dropMenu']->pssp;
                if(isset($this->lang->execution->menu->other['dropMenu']->auditplan))
                {
                    $this->lang->execution->menu->auditplan = $this->lang->execution->menu->other['dropMenu']->auditplan;
                }

                $docOrder = 0;
                foreach($this->lang->execution->menuOrder as $order => $menu) if($menu == 'doc') $docOrder = $order;
                $this->lang->execution->menuOrder[$docOrder + 1] = 'pssp';
                $this->lang->execution->menuOrder[$docOrder + 2] = 'auditplan';

                if(!empty($this->lang->project->menuOrder))
                {
                    $docOrder = 0;
                    foreach($this->lang->project->menuOrder as $order => $menu) if($menu == 'doc') $docOrder = $order;
                    $this->lang->project->menuOrder[$docOrder + 1] = 'pssp';
                    $this->lang->project->menuOrder[$docOrder + 2] = 'auditplan';
                }
            }

            unset($this->lang->execution->menu->view);
            unset($this->lang->execution->menu->other);
            if(isset($this->lang->execution->menu->settings['subMenu']->products))  unset($this->lang->execution->menu->settings['subMenu']->products);  // 模板下隐藏产品
            if(isset($this->lang->execution->menu->settings['subMenu']->whitelist)) unset($this->lang->execution->menu->settings['subMenu']->whitelist); // 模板下隐藏白名单
        }
    }

    /**
     * 根据条件设置执行二级导航。
     * Set secondary navigation based on the conditions.
     *
     * @param  object $execution
     * @access public
     * @return bool
     */
    public function removeMenu(object $execution): bool
    {
        $project = $this->loadModel('project')->fetchByID($execution->project);
        if($execution->type == 'stage' || (!empty($project) && $project->model == 'waterfallplus')) unset($this->lang->execution->menu->settings['subMenu']->products);

        if(empty($execution->hasProduct)) unset($this->lang->execution->menu->settings['subMenu']->products);
        if(isset($execution->acl) && $execution->acl != 'private') unset($this->lang->execution->menu->settings['subMenu']->whitelist);

        $features = $this->getExecutionFeatures($execution);
        if(!$features['story'])  unset($this->lang->execution->menu->story);
        if(!$features['story'])  unset($this->lang->execution->menu->view['subMenu']->groupTask);
        if(!$features['story'])  unset($this->lang->execution->menu->view['subMenu']->tree);
        if(!$features['qa'])     unset($this->lang->execution->menu->qa);
        if(!$features['devops']) unset($this->lang->execution->menu->devops);
        if(!$features['build'])  unset($this->lang->execution->menu->build);
        if(!$features['burn'])   unset($this->lang->execution->menu->burn);
        if(!$features['other'])  unset($this->lang->execution->menu->other);
        if(!$features['story'] && $this->config->edition == 'open') unset($this->lang->execution->menu->view);
        if($this->config->inCompose)
        {
            $repoServers = $this->loadModel('pipeline')->getPairs($this->config->pipeline->checkRepoServers);
            if(empty($repoServers)) unset($this->lang->execution->menu->devops);
        }

        return true;
    }

    /**
     * 检查用户是否可以访问当前执行。
     * Check whether access to the current execution is allowed or not.
     *
     * @param  int   $executionID
     * @param  array $executions
     * @access public
     * @return int
     */
    public function checkAccess(int $executionID, array $executions): int
    {
        if(commonModel::isTutorialMode()) return $executionID;

        /* When the cookie and session do not exist, get it from the config. */
        if(!$executionID)
        {
            if($this->cookie->lastExecution) $executionID = (int)$this->cookie->lastExecution;
            if(!$executionID && $this->session->execution) $executionID = (int)$this->session->execution;
            if(!$executionID && isset($this->config->execution->lastExecution)) $executionID = (int)$this->config->execution->lastExecution;
        }

        /* 项目模板不校验访问权限。 */
        $isTpl = $this->dao->select('isTpl')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->andWhere('id')->in($this->app->user->view->sprints)->fetch('isTpl');
        /* If the execution doesn't exist in the list, use the first execution in the list. */
        if(empty($isTpl) && !isset($executions[$executionID]))
        {
            /* Check execution. */
            if($executionID)
            {
                $execution = $this->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch();
                if(empty($execution)) return $this->app->control->sendError($this->lang->notFound, helper::createLink('execution', 'all'));
                if(!$this->app->user->admin && strpos(",{$this->app->user->view->sprints},", ",{$executionID},") === false) $this->accessDenied();
            }

            $executionID = key($executions);
        }

        /* Save session. */
        $this->executionTao->saveSession((int)$executionID);

        /* Return execution id. */
        return (int)$executionID;
    }

    /**
     * 给执行所属的项目ID设置session。
     * Set project into session.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function setProjectSession(int $executionID)
    {
        $execution = $this->fetchByID($executionID);
        if(!empty($execution)) $this->session->set('project', $execution->project, $this->app->tab);
    }

    /**
     * Create a execution.
     *
     * @param  object $execution
     * @param  array  $postMembers
     * @access public
     * @return int|false
     */
    public function create(object $execution, array $postMembers): int|false
    {
        $skipFlow = isset($execution->multiple) && $execution->multiple == 0;
        $this->dao->insert(TABLE_EXECUTION)->data($execution, 'products,plans,branch')
            ->autoCheck('begin,end')
            ->batchCheck($this->config->execution->create->requiredFields, 'notempty')
            ->checkIF(!empty($execution->name), 'name', 'unique', "`type` in ('sprint','stage', 'kanban') and `project` = " . (int)$execution->project . " and `deleted` = '0'")
            ->checkIF(!empty($execution->code), 'code', 'unique', "`type` in ('sprint','stage', 'kanban') and `project` = " . (int)$execution->project . " and `deleted` = '0'")
            ->checkIF($execution->begin != '', 'begin', 'date')
            ->checkIF($execution->end != '', 'end', 'date')
            ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
            ->checkFlow($skipFlow) // 影子迭代跳过检查 skip check flow for shadow iteration
            ->exec();

        /* Add the creator to the team. */
        if(dao::isError()) return false;

        $executionID = $this->dao->lastInsertId();
        $project     = $this->loadModel('project')->fetchByID($execution->project);
        if(empty($project) || $project->model != 'kanban')
        {
            $execution->id = $executionID;
            if(!isset($execution->attribute)) $execution->attribute = '';
            $this->loadModel('kanban')->createExecutionLane($execution);
        }

        /* Api create infinites stages. */
        if(isset($execution->parent) && ($execution->parent != $execution->project) && ($execution->type == 'stage' || $project->model == 'ipd'))
        {
            $parent = $this->fetchByID((int)$execution->parent);
            $grade  = $parent->grade + 1;
            $path   = rtrim($parent->path, ',') . ",{$executionID}";
            $attribute = $parent->attribute;
            $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($attribute)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($executionID)->exec();
        }

        /* Save order. */
        $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($executionID * 5)->where('id')->eq($executionID)->exec();
        $this->loadModel('file')->updateObjectID($this->post->uid, $executionID, 'execution');

        /* Update the path. */
        $this->setTreePath($executionID);

        $this->executionTao->addExecutionMembers($executionID, $postMembers);
        $this->executionTao->createMainLib($execution->project, $executionID, $execution->type);

        $this->loadModel('personnel')->updateWhitelist(explode(',', $execution->whitelist), 'sprint', $executionID);
        if($execution->acl != 'open') $this->updateUserView($executionID);

        $this->updateProducts($executionID, $execution);
        $this->loadModel('programplan')->computeProgress($executionID, 'create');
        $this->loadModel('score')->create('program', 'createguide', $executionID);
        return $executionID;
    }

    /**
     * 更新一个迭代。
     * Update a execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function update(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        /* Judge workdays is legitimate. */
        $this->app->loadLang('project');
        $workdays = helper::diffDate($postData->end, $postData->begin) + 1;
        if(isset($postData->days) and $postData->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if(!empty($postData->products))
        {
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs();
            if(isset($postData->branch) and !empty($postData->branch)) $postData->branch = is_array($postData->branch) ? $postData->branch : json_decode($postData->branch, true);
            foreach($postData->products as $index => $productID)
            {
                if(!isset($postData->branch[$index])) continue;
                $branches = is_array($postData->branch[$index]) ? implode(',', $postData->branch[$index]) : $postData->branch[$index];
                if(isset($multipleProducts[$productID]) && $branches == '')
                {
                    dao::$errors["branch[$index][]"] = $this->lang->project->error->emptyBranch;
                    return false;
                }
            }
        }

        if(!empty($postData->heightType) && $postData->heightType == 'custom' && !$this->loadModel('kanban')->checkDisplayCards($postData->displayCards)) return false;

        if(in_array($postData->status, array('closed', 'suspended'))) $this->computeBurn($executionID);

        /* Check the begin date and end date. */
        $parentExecution = !empty($postData->parent) ? $postData : $oldExecution;
        if($oldExecution->multiple &&(empty($postData->project) || $postData->project == $oldExecution->project)) $this->checkBeginAndEndDate($oldExecution->project, $postData->begin, $postData->end, $parentExecution->parent);
        if(dao::isError()) return false;

        /* Child stage inherits parent stage permissions. */
        if(!isset($postData->acl)) $postData->acl = $oldExecution->acl;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->edit['id'], (string)$this->post->uid);

        /* Check the workload format and total, such as check Workload Ratio if it enabled. */
        if(!empty($execution->percent) && isset($this->config->setPercent) && $this->config->setPercent == 1) $this->checkWorkload('update', (float)$execution->percent, $oldExecution);
        if(dao::isError()) return false;

        /* Set planDuration and realDuration. */
        if(in_array($this->config->edition, array('max', 'ipd')))
        {
            $execution->planDuration = $this->loadModel('programplan')->getDuration($execution->begin, $execution->end);
            if(!empty($execution->realBegan) and !empty($execution->realEnd)) $execution->realDuration = $this->programplan->getDuration($execution->realBegan, $execution->realEnd);
        }

        /* Redefines the language entries for the fields in the project table. */
        foreach(explode(',', $this->config->execution->edit->requiredFields) as $field)
        {
            if(isset($this->lang->execution->$field)) $this->lang->project->$field = $this->lang->execution->$field;
            if($oldExecution->type == 'stage' and $field == 'name') $this->lang->project->name = str_replace($this->lang->executionCommon, $this->lang->project->stage, $this->lang->project->name);
        }

        $relatedExecutionsID = $this->getRelatedExecutions($executionID);
        $relatedExecutionsID = !empty($relatedExecutionsID) ? implode(',', array_keys($relatedExecutionsID)) : '0';

        /* Update data. */
        $this->lang->error->unique = $this->lang->error->repeat;
        $executionProject = isset($execution->project) ? $execution->project : $oldExecution->project;
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'products, branch, uid, plans, syncStories, contactListMenu, teamMembers, heightType, delta')
            ->autoCheck('begin,end')
            ->batchCheck($this->config->execution->edit->requiredFields, 'notempty')
            ->checkIF($execution->begin != '', 'begin', 'date')
            ->checkIF($execution->end != '', 'end', 'date')
            ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
            ->checkIF(!empty($execution->name), 'name', 'unique', "id in ($relatedExecutionsID) and type in ('sprint','stage', 'kanban') and `project` = '$executionProject' and `deleted` = '0'")
            ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage', 'kanban') and `project` = '$executionProject' and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        if(dao::isError()) return false;

        if(isset($postData->parent)) $this->loadModel('programplan')->setTreePath($executionID);

        /* Update the team. */
        $this->executionTao->updateTeam($executionID, $oldExecution, $execution);

        /* Update whitelist. */
        $whitelist = array();
        if(!empty($execution->whitelist))  $whitelist = is_string($execution->whitelist) ? explode(',', $execution->whitelist) : $execution->whitelist;
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'sprint', $executionID);

        /* Set the product for project same to execution. */
        if(isset($execution->project))
        {
            $executionProductList   = $this->loadModel('product')->getProducts($executionID);
            $projectProductList     = $this->product->getProducts((int)$execution->project);
            $executionProductIdList = array_keys($executionProductList);
            $projectProductIdList   = array_keys($projectProductList);
            $diffProductIdList      = array_diff($executionProductIdList, $projectProductIdList);
            if(!empty($diffProductIdList))
            {
                foreach($diffProductIdList as $newProductID)
                {
                    $data = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
                        ->where('project')->eq($executionID)
                        ->andWhere('product')->eq($newProductID)
                        ->fetch();
                    $data->project = $execution->project;
                    $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
                }
            }
        }

        if(!dao::isError())
        {
            if(isset($execution->project) and $execution->project != $oldExecution->project)
            {
                $execution->parent = $execution->project;
                $execution->path   = ",{$execution->project},{$executionID},";
                $this->changeProject((int)$execution->project, $oldExecution->project, $executionID, $postData->syncStories ?? 'no');
            }

            $this->file->updateObjectID($this->post->uid, $executionID, 'execution');
            return common::createChanges($oldExecution, $execution);
        }
    }

    /**
     * 批量编辑执行。
     * Batch update executions.
     *
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function batchUpdate(object $postData): array|false
    {
        $this->loadModel('user');
        $this->loadModel('project');
        $this->app->loadLang('programplan');

        $allChanges    = array();
        $oldExecutions = $this->getByIdList($postData->id);
        $executions    = $this->buildBatchUpdateExecutions($postData, $oldExecutions);
        if(dao::isError()) return false;

        /* Update burn before close execution. */
        $closedIdList = array();
        foreach($executions as $executionID => $execution)
        {
            if(isset($execution->status) and in_array($execution->status, array('done', 'closed', 'suspended'))) $closedIdList[$executionID] = $executionID;
        }
        $this->computeBurn($closedIdList);

        foreach($executions as $executionID => $execution)
        {
            $oldExecution = $oldExecutions[$executionID];
            $team         = $this->user->getTeamMemberPairs($executionID, 'execution');
            $projectID    = isset($execution->project) ? (int)$execution->project : (int)$oldExecution->project;

            if(isset($execution->project))
            {
                $executionProductList   = $this->loadModel('product')->getProducts($executionID);
                $projectProductList     = $this->product->getProducts((int)$execution->project);
                $executionProductIdList = array_keys($executionProductList);
                $projectProductIdList   = array_keys($projectProductList);
                $diffProductIdList      = array_diff($executionProductIdList, $projectProductIdList);
                if(!empty($diffProductIdList))
                {
                    foreach($diffProductIdList as $newProductID)
                    {
                        $projectProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
                            ->where('project')->eq($executionID)
                            ->andWhere('product')->eq($newProductID)
                            ->fetch();
                        $projectProduct->project = $execution->project;
                        $this->dao->insert(TABLE_PROJECTPRODUCT)->data($projectProduct)->exec();
                    }
                }
            }

            $this->dao->update(TABLE_EXECUTION)->data($execution)
                ->autoCheck('begin,end')
                ->batchCheck($this->config->execution->edit->requiredFields, 'notempty')
                ->checkIF($execution->begin != '', 'begin', 'date')
                ->checkIF($execution->end != '', 'end', 'date')
                ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
                ->checkIF(!empty($execution->name), 'name', 'unique', "id != $executionID and type in ('sprint','stage','kanban') and `project` = $projectID and `deleted` = '0'")
                ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage','kanban') and `project` = $projectID and `deleted` = '0'")
                ->checkFlow()
                ->where('id')->eq($executionID)
                ->exec();

            if(dao::isError()) return false;

            if(!empty($execution->project) and $oldExecution->project != $execution->project)
            {
                $execution->parent = $execution->project;
                $execution->path   = ",{$execution->project},{$executionID},";
                $this->changeProject((int)$execution->project, $oldExecution->project, $executionID, isset($postData->syncStories[$executionID]) ? $postData->syncStories[$executionID] : 'no');
            }

            if(!empty($execution->attribute) && $oldExecution->attribute != $execution->attribute && $execution->attribute != 'mix')
            {
                $this->dao->update(TABLE_EXECUTION)->set('attribute')->eq($execution->attribute)->where('parent')->eq($executionID)->exec();
            }

            $changedAccounts = array();
            foreach($this->config->execution->ownerFields as $ownerField)
            {
                $owner = zget($execution, $ownerField, '');
                if(empty($owner) or isset($team[$owner])) continue;

                $member = new stdclass();
                $member->root    = (int)$executionID;
                $member->account = $owner;
                $member->join    = helper::today();
                $member->role    = $this->lang->execution->$ownerField;
                $member->days    = zget($execution, 'days', 0);
                $member->type    = 'execution';
                $member->hours   = $this->config->execution->defaultWorkhours;
                $this->dao->replace(TABLE_TEAM)->data($member)->exec();

                $changedAccounts[] = $owner;
            }
            if(!empty($changedAccounts)) $this->updateUserView($executionID, 'sprint', $changedAccounts);

            $allChanges[$executionID] = common::createChanges($oldExecution, $execution);
        }

        $this->fixOrder();
        return $allChanges;
    }

    /**
     * 批量更改执行的状态。
     * Batch change status.
     *
     * @param  array     $executionIdList
     * @param  string    $status
     * @access public
     * @return array     返回不符合条件被过滤了的执行，来提示执行下任务或子阶段已经开始，无法修改，已过滤。参见 story#41875。
     */
    public function batchChangeStatus(array $executionIdList, string $status): array
    {
        /* Sort the IDs, the child stage comes first, and the parent stage follows. */
        $executionList = $this->dao->select('id,name,status,grade,type,attribute,project')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->filterTpl(false)->orderBy('grade_desc')->fetchAll('id');

        $this->loadModel('programplan');
        $message = array('byChild' => '', 'byDeliverable' => '');
        foreach($executionList as $executionID => $execution)
        {
            $needCheckDeliverable = $status == 'closed' && $execution->status == 'doing' && $execution->grade == 1;
            if(in_array($this->config->edition, array('max', 'ipd')) && $needCheckDeliverable && !$this->canCloseByDeliverable($execution))
            {
                $message['byDeliverable'] .= '#' . $execution->id . ' ' . $execution->name . "\n";
                continue;
            }

            /* The state of the parent stage or the sibling stage may be affected by the child stage before the change, so it cannot be checked in advance. */
            $selfAndChildrenList = $this->programplan->getSelfAndChildrenList($executionID);
            $selfAndChildren     = $selfAndChildrenList[$executionID];
            $execution           = $selfAndChildren[$executionID];

            if($status == 'wait' and $execution->status != 'wait')
            {
                $message['byChild'] .= $this->changeStatus2Wait($executionID, $selfAndChildren);
            }

            if($status == 'doing' and $execution->status != 'doing')
            {
                $this->changeStatus2Doing($executionID, $selfAndChildren);
            }

            if(($status == 'suspended' and $execution->status != 'suspended') or ($status == 'closed' and $execution->status != 'closed'))
            {
                $message['byChild'] .= $this->changeStatus2Inactive($executionID, $status, $selfAndChildren);
            }
        }

        $message['byChild'] = trim($message['byChild'], ',');
        return $message;
    }

    /**
     * 设置状态为未开始。
     * Change status to wait.
     *
     * @param  int    $executionID
     * @param  array  $selfAndChildren
     * @access public
     * @return string
     */
    public function changeStatus2Wait(int $executionID, array $selfAndChildren): string
    {
        /* There are already tasks consuming work in this phase or its sub-phases already have start times. */
        $hasStartedChildren = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('deleted')->eq('0')
            ->andWhere('realBegan')->notNULL()
            ->andWhere('id')->in(array_keys($selfAndChildren))
            ->andWhere('id')->ne($executionID)
            ->fetchPairs();
        $hasConsumedTasks   = $this->dao->select('count(consumed) AS count')->from(TABLE_TASK)
            ->where('deleted')->eq('0')
            ->andWhere('execution')->in(array_keys($selfAndChildren))
            ->andWhere('consumed')->gt(0)
            ->fetch('count');
        if($hasStartedChildren or $hasConsumedTasks) return "'{$selfAndChildren[$executionID]->name}',";

        $newExecution = $this->buildExecutionByStatus('wait');
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();

        if(!dao::isError())
        {
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->loadModel('action')->create('execution', $executionID, 'Edited');
            $this->action->logHistory($actionID, $changes);

            /* This stage has a parent stage. */
            $isTopStage = $this->loadModel('programplan')->isTopStage($executionID);
            if(!$isTopStage) $this->programplan->computeProgress($executionID);
        }
        return '';
    }

    /**
     * 设置状态为进行中。
     * Change status to doing.
     *
     * @param  int    $executionID
     * @param  array  $selfAndChildren
     * @access public
     * @return string
     */
    public function changeStatus2Doing(int $executionID, array $selfAndChildren): string
    {
        $this->loadModel('programplan');
        $this->loadModel('action');

        $newExecution = $this->buildExecutionByStatus('doing');
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();
        if(!dao::isError())
        {
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->action->create('execution', $executionID, 'Started');
            $this->action->logHistory($actionID, $changes);

            /* This stage has a parent stage. */
            $isTopStage = $this->programplan->isTopStage($executionID);
            if(!$isTopStage) $this->programplan->computeProgress($executionID);
        }
        return '';
    }

    /**
     * 设置状态为暂停或者关闭。
     * Change status to suspended or closed.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  array  $selfAndChildren
     * @access public
     * @return string
     */
    public function changeStatus2Inactive(int $executionID, string $status, array $selfAndChildren): string
    {
        $checkedStatus = $status == 'suspended' ? 'wait,doing' : 'wait,doing,suspended';

        /* If status is suspended, the rules is there are sub-stages under this stage, and not all sub-stages are suspended or closed. */
        /* If status is closed, the rules is there are sub-stages under this stage, and not all sub-stages are closed. */
        $checkLeafStage = $this->loadModel('programplan')->checkLeafStage($executionID);
        if(!$checkLeafStage)
        {
            foreach($selfAndChildren as $childID => $child)
            {
                if($childID == $executionID) continue;

                if(strpos($checkedStatus, $child->status) !== false) return "'{$selfAndChildren[$executionID]->name}',";
            }
        }

        $newExecution = $this->buildExecutionByStatus($status);
        $this->dao->update(TABLE_EXECUTION)->data($newExecution)->where('id')->eq($executionID)->exec();
        if(!dao::isError())
        {
            $changes  = common::createChanges($selfAndChildren[$executionID], $newExecution);
            $actionID = $this->loadModel('action')->create('execution', $executionID, strtoupper($status));
            $this->action->logHistory($actionID, $changes);

            /* Suspended: When all child stages at the same level are suspended or closed, the status of the parent stage becomes "suspended". */
            /* Closed: When all child stages at the same level are closed, the status of the parent stage becomes "closed". */
            $isTopStage = $this->programplan->isTopStage($executionID);
            if(!$isTopStage) $this->programplan->computeProgress($executionID);
        }

        return '';
    }

    /**
     * 开始一个执行。
     * Start the execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function start(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->fetchById($executionID);

        $execution = $postData;
        if(!empty($postData->uid)) $execution = $this->loadModel('file')->processImgURL($execution, $this->config->execution->editor->start['id'], $postData->uid);

        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->check($this->config->execution->start->requiredFields, 'notempty')
            ->checkIF(!empty($execution->realBegan) && $execution->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() and count(dao::$errors['realBegan']) > 1) dao::$errors['realBegan'] = dao::$errors['realBegan'][0];
        if(dao::isError()) return false;

        /* Record the end date as firstEnd when the project is started. */
        $this->loadModel('project')->recordFirstEnd($executionID);

        $changes = common::createChanges($oldExecution, $execution);

        if(!empty($postData->comment) || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Started', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 延期一个迭代。
     * Delay the execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function putoff(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->fetchById($executionID);

        $this->checkBeginAndEndDate($oldExecution->project, $postData->begin, $postData->end);
        if(dao::isError()) return false;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->putoff['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment,delta')
            ->autoCheck()
            ->checkFlow()
            ->batchCheck($this->config->execution->putoff->requiredFields, 'notempty')
            ->where('id')->eq($executionID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if($postData->comment != '' || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Delayed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * 挂起一个执行。
     * Suspend a execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function suspend(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->fetchById($executionID);

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->suspend['id'], (string)$this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if(!empty($changes) || $this->post->comment != '')
        {
            $actionID = $this->loadModel('action')->create('execution', $executionID, 'Suspended', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * 激活一个执行。
     * Activate a execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return array|false
     */
    public function activate(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->fetchById($executionID);

        if(empty($oldExecution->totalConsumed) and helper::isZeroDate($oldExecution->realBegan)) $postData->status = 'wait';

        /* Check the date which user input. */
        $begin = $postData->begin;
        $end   = $postData->end;
        if($begin > $end) dao::$errors['end'] = sprintf($this->lang->execution->errorLesserPlan, $end, $begin); /* The begin date should larger than end. */
        if(dao::isError()) return false;

        /* Check the begin and end date if the execution has a parent, such as a child Stage, Sprint or Kanban. */
        if($oldExecution->parent != 0)
        {
            $parent = $this->dao->select('begin,end')->from(TABLE_PROJECT)->where('id')->eq($oldExecution->parent)->fetch();
            if(!$parent) return false;

            $parentBegin = $parent->begin;
            $parentEnd   = $parent->end;
            if($begin < $parentBegin)
            {
                dao::$errors['begin'] = sprintf($this->lang->execution->errorLesserParent, $parentBegin); /* The begin date of child execution should larger than parent. */
            }

            if($end > $parentEnd)
            {
                dao::$errors['end'] = sprintf($this->lang->execution->errorGreaterParent, $parentEnd); /* The end date of child execution should lesser than parent. */
            }
        }

        if(dao::isError()) return false;

        /* Do update for this execution. */
        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->activate['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment,readjustTask')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$executionID)
            ->exec();

        /* 顺延任务的起止时间。Adjust the begin and end date for tasks in this execution. */
        if(!empty($postData->readjustTask))
        {
            $beginTimeStamp = strtotime($execution->begin);
            $tasks = $this->dao->select('id,estStarted,deadline,status')->from(TABLE_TASK)
                ->where('execution')->eq($executionID)
                ->andWhere('deadline')->notNULL()
                ->andWhere('status')->in('wait,doing')
                ->fetchAll();

            $this->loadModel('action');
            foreach($tasks as $task)
            {
                if(helper::isZeroDate($task->deadline)) continue;

                if($task->status == 'wait' and !helper::isZeroDate($task->estStarted))
                {
                    $taskDays   = helper::diffDate($task->deadline, $task->estStarted);
                    $taskOffset = helper::diffDate($task->estStarted, $oldExecution->begin);

                    $estStartedTimeStamp = $beginTimeStamp + $taskOffset * 24 * 3600;
                    $estStarted = date('Y-m-d', $estStartedTimeStamp);
                    $deadline   = date('Y-m-d', $estStartedTimeStamp + $taskDays * 24 * 3600);

                    if($estStarted > $execution->end) $estStarted = $execution->end;
                    if($deadline > $execution->end)   $deadline   = $execution->end;
                    $this->dao->update(TABLE_TASK)->set('estStarted')->eq($estStarted)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }
                else
                {
                    $taskOffset = helper::diffDate($task->deadline, $oldExecution->begin);
                    $deadline   = date('Y-m-d', $beginTimeStamp + $taskOffset * 24 * 3600);

                    if($deadline > $execution->end) $deadline = $execution->end;
                    $this->dao->update(TABLE_TASK)->set('deadline')->eq($deadline)->where('id')->eq($task->id)->exec();
                }

                $this->action->create('task', $task->id, 'Edited', $this->lang->execution->readjustTask );
            }
        }

        $changes = common::createChanges($oldExecution, $execution);
        if($this->post->comment != '' or !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 关闭迭代。
     * Close execution.
     *
     * @param  int    $executionID
     * @param  object $postData
     * @access public
     * @return int|false
     */
    public function close(int $executionID, object $postData): int|false
    {
        $oldExecution = $this->fetchById($executionID); /* Save previous execution to variable for later compare. */

        $this->lang->error->ge = $this->lang->execution->ge;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->close['id'], (string)$this->post->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->check($this->config->execution->close->requiredFields,'notempty')
            ->checkIF($execution->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($execution->realEnd != '', 'realEnd', 'ge', $oldExecution->realBegan)
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() && !empty(dao::$errors['realEnd']) && count(dao::$errors['realEnd']) > 1) dao::$errors['realEnd'] = dao::$errors['realEnd'][0];
        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if($this->post->comment != '' || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('score')->create('execution', 'close', $oldExecution);
        return $actionID;
    }

    /**
     * 设置看板配置。
     * Set Kanban.
     *
     * @param  int    $executionID
     * @param  object $execution
     * @access public
     * @return void
     */
    public function setKanban(int $executionID, object $execution)
    {
        $this->loadModel('kanban');
        $this->lang->project->colWidth    = $this->lang->kanban->colWidth;
        $this->lang->project->minColWidth = $this->lang->kanban->minColWidth;
        $this->lang->project->maxColWidth = $this->lang->kanban->maxColWidth;
        $this->dao->update(TABLE_EXECUTION)->data($execution)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->checkIF(!$execution->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
            ->batchCheckIF($execution->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
            ->checkIF($execution->minColWidth >= $this->config->minColWidth && $execution->fluidBoard, 'maxColWidth', 'gt', $execution->minColWidth)
            ->where('id')->eq((int)$executionID)
            ->exec();
    }

    /**
     * 检查阶段的工作量占比。
     * Check the workload format and total.
     *
     * @param  string $type        create|update
     * @param  float  $percent
     * @param  object $oldExecution
     * @access public
     * @return bool
     */
    public function checkWorkload(string $type = '', float $percent = 0, ?object $oldExecution = null): bool
    {
        /* Check whether the workload is positive. */
        if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", (string)$percent))
        {
            dao::$errors['percent'] = $this->lang->programplan->error->percentNumber;
            return false;
        }

        $oldExecutionGrade   = !empty($oldExecution->grade)   ? $oldExecution->grade   : 0;
        $oldExecutionType    = !empty($oldExecution->type)    ? $oldExecution->type    : '';
        $oldExecutionID      = !empty($oldExecution->id)      ? $oldExecution->id      : 0;
        $oldExecutionParent  = !empty($oldExecution->parent)  ? $oldExecution->parent  : 0;
        $oldExecutionPercent = !empty($oldExecution->percent) ? $oldExecution->percent : 0;
        $oldExecutionProject = !empty($oldExecution->project) ? $oldExecution->project : 0;

        /* The total workload of the first stage should not exceed 100%. */
        if($type == 'create' || ($oldExecutionGrade == 1 && isset($this->lang->execution->typeList[$oldExecutionType])))
        {
            $branchID        = !empty($_POST['branch'][0]) ? current($this->post->branch[0]) : 0;
            $oldPercentTotal = $this->dao->select('SUM(t2.percent) as total')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($this->post->products[0])
                ->beginIF($branchID)->andWhere('t1.branch')->eq($branchID)->fi()
                ->andWhere('t2.type')->eq('stage')
                ->andWhere('t2.grade')->eq(1)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($type == 'create')->andWhere('t2.parent')->eq($oldExecutionID)->fi()
                ->beginIF(!empty($oldExecution) && isset($this->lang->execution->typeList[$oldExecutionType]))->andWhere('t2.parent')->eq($oldExecutionParent)->fi()
                ->fetch('total');

            if(!$oldPercentTotal) $oldPercentTotal = 0;
            if($type == 'create') $percentTotal = $percent + $oldPercentTotal;
            if(!empty($oldExecution) && isset($this->lang->execution->typeList[$oldExecutionType])) $percentTotal = $oldPercentTotal - $oldExecutionPercent + $percent;

            if($percentTotal > 100)
            {
                $printPercent = $type == 'create' ? $oldPercentTotal : $percentTotal;
                dao::$errors['percent'] = sprintf($this->lang->execution->workloadTotal, '%', $printPercent . '%');
                return false;
            }
        }

        if($type == 'update' && $oldExecutionGrade > 1)
        {
            $childrenTotalPercent = $this->dao->select('SUM(percent) as total')->from(TABLE_EXECUTION)->where('parent')->eq($oldExecutionParent)->andWhere('project')->eq($oldExecutionProject)->andWhere('deleted')->eq(0)->fetch('total');
            $childrenTotalPercent = $childrenTotalPercent - $oldExecutionPercent + $percent;

            if($childrenTotalPercent > 100)
            {
                dao::$errors['percent'] = sprintf($this->lang->execution->workloadTotal, '%', $childrenTotalPercent . '%');
                return false;
            }
        }

        return true;
    }

    /**
     * 检查执行开始、结束日期是否正确。
     * Check begin and end date.
     *
     * @param  int    $projectID
     * @param  string $begin
     * @param  string $end
     * @param  int    $parentID
     * @access public
     * @return void
     */
    public function checkBeginAndEndDate(int $projectID, string $begin, string $end, int $parentID = 0)
    {
        $project = $this->loadModel('project')->fetchByID($projectID);
        if(empty($project)) return;

        if(in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')) && $parentID != $projectID)
        {
            $this->app->loadLang('programplan');
            $parent = $this->fetchByID($parentID);
            if($parent && $begin < $parent->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
            if($parent && $end > $parent->end)     dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
        }
        if(dao::isError()) return;

        if($begin < $project->begin) dao::$errors['begin'] = sprintf($this->lang->execution->errorCommonBegin, $project->begin);
        if(!helper::isZeroDate($project->end) && $end > $project->end) dao::$errors['end'] = sprintf($this->lang->execution->errorCommonEnd, $project->end);
    }

    /**
     * 获取执行id:name的键值对。
     * Get execution pairs.
     *
     * @param  int    $projectID
     * @param  string $type      all|sprint|stage|kanban
     * @param  string $mode      all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|empty|noprefix|withobject|hideMultiple
     * @param  bool   $ignoreVision
     * @access public
     * @return array
     */
    public function getPairs(int $projectID = 0, string $type = 'all', string $mode = '', bool $ignoreVision = false): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionPairs();

        $mode        .= $this->cookie->executionMode;
        $orderBy      = $this->config->execution->orderBy;
        $projectModel = '';
        if($projectID)
        {
            $projectModel = $this->dao->select('model')->from(TABLE_EXECUTION)->where('id')->eq($projectID)->andWhere('deleted')->eq(0)->fetch('model');
            $orderBy      = in_array($projectModel, array('waterfall', 'waterfallplus')) ? 'sortStatus_asc,begin_asc,id_asc' : 'id_desc';

            /* Waterfall execution, when all phases are closed, in reverse order of date. */
            if(in_array($projectModel, array('waterfall', 'waterfallplus')))
            {
                $statistic = $this->dao->select("count(id) as executions, sum(IF(INSTR('closed', status) < 1, 0, 1)) as closedExecutions")->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->fetch();
                if($statistic->executions == $statistic->closedExecutions) $orderBy = 'sortStatus_asc,begin_desc,id_asc';
            }
        }

        /* Order by status's content whether or not done. */
        $executions = $this->dao->select("*, IF(INSTR('done,closed', status) < 2, 0, 1) AS isDone, INSTR('doing,wait,suspended,closed', status) AS sortStatus")->from(TABLE_EXECUTION)
            ->where('1=1')
            ->beginIF(!$ignoreVision)->andWhere('vision')->eq($this->config->vision)->fi()
            ->beginIF((!$this->session->multiple && $this->app->tab == 'execution') || strpos($mode, 'multiple') !== false)->andWhere('multiple')->eq('1')->fi()
            ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
            ->beginIF($type == 'all')->andWhere('type')->in('stage,sprint,kanban')->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(strpos($mode, 'withdelete') === false)->andWhere('deleted')->eq(0)->fi()
            ->beginIF(!$this->app->user->admin && strpos($mode, 'all') === false)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
        if(strpos($mode, 'order_asc') !== false) $executions = $this->resetExecutionSorts($executions);

        /* If mode == leaf, only show leaf executions. */
        $allExecutions = $this->dao->select('id,name,parent,grade')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->beginIf($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchAll('id');

        $parents = array();
        foreach($allExecutions as $exec) $parents[$exec->parent] = true;

        $projectPairs = strpos($mode, 'withobject') !== false ? $this->dao->select('id,name')->from(TABLE_PROJECT)->fetchPairs('id') : array();

        return $this->executionTao->buildExecutionPairs($mode, $allExecutions, $executions, $parents, $projectPairs, $projectModel);
    }

    /**
     * 根据执行ID列表获取执行列表信息。
     * Get the execution list information by the execution ID list.
     *
     * @param  array  $executionIdList
     * @param  string $mode           all
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getByIdList(array $executionIdList = array(), string $mode = '', string $orderBy = 'id_asc'): array
    {
        return $this->dao->select('*,whitelist')->from(TABLE_EXECUTION)
            ->where('id')->in($executionIdList)
            ->beginIF($mode != 'all')->andWhere('deleted')->eq(0)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 获取执行列表信息。
     * Get execution list information.
     *
     * @param  int         $projectID
     * @param  string      $type      all|sprint|stage|kanban
     * @param  string      $status    all|undone|wait|running
     * @param  int         $limit
     * @param  int         $productID
     * @param  int         $branch
     * @param  object|null $pager
     * @param  bool        $withChildren
     * @access public
     * @return array
     */
    public function getList(int $projectID = 0, string $type = 'all',string  $status = 'all', int $limit = 0, int $productID = 0, int $branch = 0, ?object $pager = null, bool $withChildren = true)
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStats($type);

        if($status == 'involved') return $this->getInvolvedExecutionList($projectID, $status, $limit, $productID, $branch);

        if($productID != 0)
        {
            return $this->dao->select('t2.*, t2.`desc`')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project= t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->beginIF($type == 'all')->andWhere('t2.type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('t2.type')->eq($type)->fi()
                ->beginIF($status == 'undone')->andWhere('t2.status')->notIN('done,closed')->fi()
                ->beginIF($status == 'delayed')->andWhere('t2.end')->gt('1970-1-1')->andWhere('t2.end')->lt(date(DT_DATE1))->andWhere('t2.status')->notin('done,closed,suspended')->fi()
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($status != 'all' && $status != 'undone' && $status != 'delayed')->andWhere('t2.status')->in($status)->fi()
                ->beginIF(!$this->app->user->admin and isset($this->app->user->view))->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->beginIF(!$withChildren)->andWhere('grade')->eq(1)->fi()
                ->orderBy('order_desc')
                ->page($pager)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("*, `desc`, IF(INSTR(' done,closed', status) < 2, 0, 1) AS isDone")->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF($type == 'all')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
                ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
                ->beginIF($status == 'delayed')->andWhere('end')->gt('1970-1-1')->andWhere('end')->lt(date(DT_DATE1))->andWhere('status')->notin('done,closed,suspended')->fi()
                ->beginIF($status != 'all' && $status != 'undone' && $status != 'delayed')->andWhere('status')->in($status)->fi()
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->beginIF(!$this->app->user->admin and isset($this->app->user->view))->andWhere('id')->in($this->app->user->view->sprints)->fi()
                ->beginIF(!$withChildren)->andWhere('grade')->eq(1)->fi()
                ->orderBy('order_desc')
                ->page($pager)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * 获取泳道的最后更新日期。
     * Get the last updated date of the lane.
     *
     * @param  int    $executionID
     * @access public
     * @return string|null
     */
    public function getLaneMaxEditedTime(int $executionID): string|null
    {
        return $this->dao->select("max(lastEditedTime) as lastEditedTime")->from(TABLE_KANBANLANE)->where('execution')->eq($executionID)->fetch('lastEditedTime');
    }

    /**
     * 获取我参与的执行列表信息。
     * Get involved execution list information.
     *
     * @param  int      $projectID
     * @param  string   $status  involved
     * @param  int      $limit
     * @param  int      $productID
     * @param  int      $branch
     * @access public
     * @return object[]
     */
    public function getInvolvedExecutionList(int $projectID = 0, string $status = 'involved', int $limit = 0, int $productID = 0, int $branch = 0): array
    {
        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project= t2.id')
                ->leftJoin(TABLE_TEAM)->alias('t3')->on('t3.root=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.type')->in('sprint,stage,kanban')
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->andWhere('t2.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t3.account')->eq($this->app->user->account)
                ->markRight(1)
                ->orderBy('order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("t1.*, IF(INSTR(' done,closed', t1.status) < 2, 0, 1) AS isDone")->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t2.root=t1.id')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.type')->in('sprint,stage,kanban')
                ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
                ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
                ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
                ->orWhere('t2.account')->eq($this->app->user->account)
                ->markRight(1)
                ->orderBy('t1.order_desc')
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
    }

    /**
     * 获取给定项目下所有执行的Id列表。
     * Get execution id list by project.
     *
     * @param  int    $projectID
     * @param  string $status all|undone|wait|doing|suspended|closed
     * @access public
     * @return array
     */
    public function getIdList(int $projectID, string $status = 'all'): array
    {
        return $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage,kanban')
            ->andWhere('deleted')->eq('0')
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF(!in_array($status, array('all', 'undone')))->andWhere('status')->in($status)->fi()
            ->fetchPairs('id', 'id');
    }

    /**
     * Get execution count.
     *
     * @param  int    $projectID
     * @param  string $browseType all|undone|wait|doing|suspended|closed|involved|review
     * @access public
     * @return int
     */
    public function getExecutionCounts(int $projectID = 0, string $browseType = 'all'): int
    {
        $executions = $this->dao->select('t1.*,t2.`name` as projectName, t2.`model` as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'involved', 'review', 'bySearch')))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->fetchAll('id');

        return count($executions);
    }

    /**
     * 获取执行数据。
     * Get execution stat data.
     *
     * @param  int         $projectID
     * @param  string      $browseType all|undone|wait|doing|suspended|closed|involved|bySearch|review
     * @param  int         $productID
     * @param  int         $branch
     * @param  bool        $withTasks
     * @param  string|int  $param      skipParent|hasParentName
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getStatData(int $projectID = 0, string $browseType = 'undone', int $productID = 0, int $branch = 0, bool $withTasks = false, string|int $param = '', string $orderBy = 'id_asc', ?object $pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionStats($browseType);

        $executions = $this->fetchExecutionList($projectID, $browseType, $productID, (int) $param, $orderBy, $pager);
        $executions = $this->batchProcessExecution($executions, $projectID, $productID, $withTasks, $param);

        return array_values($executions);
    }

    /**
     * 获取搜索执行的查询语句。
     * Get execution query SQL.
     *
     * @param  int    $queryID
     * @access public
     * @return string
     */
    public function getExecutionQuery(int $queryID): string
    {
        /* Get query SQL. */
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('executionQuery', $query->sql);
                $this->session->set('executionForm', $query->form);
            }
        }
        if($this->session->executionQuery === false) $this->session->set('executionQuery', ' 1 = 1');

        $executionQuery = $this->session->executionQuery;

        /* If all projects are searched change the query SQL to 1=1. */
        $allProject = "`project` = 'all'";
        if(strpos($executionQuery, $allProject) !== false) $executionQuery = str_replace($allProject, '1', $executionQuery);

        /* Replace field. */
        $executionQuery = preg_replace('/(`\w*`)/', 't1.$1',$executionQuery);

        return $executionQuery;
    }

    /**
     * 获取执行id=>name的键值对。
     * Get an array of execution id:name.
     *
     * @param  int     $projectID
     * @param  string  $type
     * @param  bool    $filterMulti
     * @param  bool    $queryAll
     * @access public
     * @return array
     */
    public function fetchPairs($projectID = 0, $type = 'all', $filterMulti = true, $queryAll = false): array
    {
        return $this->dao->select('id,name')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF($type == 'all')->andWhere('type')->in('stage,sprint,kanban')->fi()
            ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
            ->beginIF($filterMulti)->andWhere('multiple')->eq('1')->fi()
            ->beginIF(!$queryAll && !$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->fetchPairs();
    }

    /**
     * 批量查询关联传入项目的执行，并按照项目分组。
     * Fetch executions of linked project by project id list.
     *
     * @param  array   $projectIdList
     * @access public
     * @return array
     */
    public function fetchExecutionsByProjectIdList(array $projectIdList = array()): array
    {
        return $this->dao->select('t1.*,t2.`name` projectName, t2.`model` as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->andWhere('t1.project')->in($projectIdList)
            ->fetchGroup('project', 'id');
    }

    /**
     * 获取执行列表信息。
     * Get execution list information.
     *
     * @param  int         $projectID
     * @param  string      $browseType all|undone|wait|doing|suspended|closed|involved|bySearch|review
     * @param  int         $productID
     * @param  string      $orderBy
     * @param  int         $param
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function fetchExecutionList(int $projectID = 0, string $browseType = 'undone', int $productID = 0, int $param = 0, string $orderBy = 'id_asc', ?object $pager = null): array
    {
        /* Construct the query SQL at search executions. */
        $executionQuery = $browseType == 'bySearch' ? $this->getExecutionQuery($param) : '';
        $projectModel = $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch('model');

        return $this->dao->select('t1.*,t2.`name` as projectName, t2.`model` as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->beginIF($productID)->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.id=t3.project')->fi()
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->beginIF($projectModel == 'ipd')->andWhere('t1.enabled')->eq('on')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(!empty($executionQuery))->andWhere($executionQuery)->fi()
            ->beginIF($productID)->andWhere('t3.product')->eq($productID)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'involved', 'review', 'bySearch', 'delayed')))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'delayed')->andWhere('t1.end')->gt('1970-1-1')->andWhere('t1.end')->lt(date(DT_DATE1))->andWhere('t1.status')->notin('done,closed,suspended')->fi()
            ->beginIF($browseType == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id', false);
    }

    /**
     * 批量处理执行数据。
     * Batch process execution data.
     *
     * @param  array      $executions
     * @param  int        $projectID
     * @param  int        $productID
     * @param  bool       $withTasks
     * @param  string|int $param          skipParent|hasParentName
     * @param  array      $executionTasks
     * @access public
     * @return array
     */
    public function batchProcessExecution(array $executions, int $projectID = 0, int $productID = 0, bool $withTasks = false, string|int $param = '', array $executionTasks = array()): array
    {
        if(empty($executions)) return $executions;

        $project     = $this->loadModel('project')->fetchByID($projectID);
        $productList = $this->executionTao->getProductList($projectID);   // Get product name of the linked execution.

        if($withTasks && empty($executionTasks)) $executionTasks = $this->getTaskGroupByExecution(array_keys($executions), false);

        $parentList       = array();
        $today            = helper::today();
        $burns            = $this->getBurnData($executions);
        $parentExecutions = $this->dao->select('parent,parent')->from(TABLE_EXECUTION)->where('parent')->ne(0)->andWhere('deleted')->eq(0)->fetchPairs();
        $statusGroup      = $this->dao->select('parent,status')->from(TABLE_EXECUTION)->where('parent')->in(array_keys($executions))->andWhere('deleted')->eq(0)->fetchGroup('parent', 'status');

        /* Get workingDays. */
        $earliestEnd = $today;
        foreach($executions as $execution)
        {
            if(!empty($execution->end) && !helper::isZeroDate($execution->end) && $execution->end < $earliestEnd) $earliestEnd = $execution->end;
        }
        $workingDays = $this->loadModel('holiday')->getActualWorkingDays($earliestEnd, $today);

        foreach($executions as $execution)
        {
            $execution->productName   = isset($productList[$execution->id]) ? trim($productList[$execution->id]->productName, ',') : '';
            $execution->product       = $productID;
            $execution->productID     = $productID;
            $execution->delay         = 0;
            $execution->totalEstimate = $execution->estimate;
            $execution->totalConsumed = $execution->consumed;
            $execution->totalLeft     = $execution->left;
            if($execution->end) $execution->end = date(DT_DATE1, strtotime($execution->end));
            if(!isset($execution->projectName))  $execution->projectName  = $project->name;
            if(!isset($execution->projectModel)) $execution->projectModel = $project->model;

            if(isset($parentExecutions[$execution->id])) $executions[$execution->id]->isParent = 1;
            if(empty($productID) && !empty($productList[$execution->id])) $execution->product = trim($productList[$execution->id]->product, ',');

            /** 如果子执行都关闭了，则父执行可以手动关闭。 */
            if(isset($statusGroup[$execution->id]))
            {
                $childStatus = array_keys($statusGroup[$execution->id]);
                if(count($childStatus) == 1 && $childStatus[0] == 'closed') $execution->parentCanClose = true;
            }

            /* Judge whether the execution is delayed. */
            if($execution->status != 'done' && $execution->status != 'closed' && $execution->status != 'suspended' && !empty($workingDays))
            {
                $betweenDays = $this->holiday->getDaysBetween($execution->end, $today);
                if($betweenDays)
                {
                    $delayDays = array_intersect($betweenDays, $workingDays);
                    $delay     = count($delayDays) - 1;
                    if($delay > 0) $execution->delay = $delay;
                }
            }

            /* Process the burns. */
            $execution->burns = array();
            $burnData = isset($burns[$execution->id]) ? $burns[$execution->id] : array();
            foreach($burnData as $data) $execution->burns[] = (float)$data->value;

            if(isset($executionTasks) && isset($executionTasks[$execution->id])) $execution->tasks = $executionTasks[$execution->id];

            /* In the case of the waterfall model, calculate the sub-stage. */
            if($param === 'skipParent')
            {
                if($execution->parent) $parentList[$execution->parent] = $execution->parent;
                if($execution->projectName) $execution->name = $execution->projectName . ' / ' . $execution->name;
            }
            elseif(strpos((string)$param, 'hasParentName') !== false)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                $executions[$execution->id]->title = implode('/', $parentExecutions);
            }
        }

        foreach($parentList as $parentID) unset($executions[$parentID]);
        return $executions;
    }

    /**
     * 根据项目ID获取项目下的执行信息。
     * Get executions data by project.
     *
     * @param  int     $projectID
     * @param  string  $status
     * @param  int     $limit
     * @param  bool    $pairs
     * @param  bool    $devel
     * @param  int     $appendedID
     * @access public
     * @return object[]|array
     */
    public function getByProject(int $projectID, string $status = 'all', int $limit = 0, bool $pairs = false, bool $devel = false, int $appendedID = 0): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionPairs();

        $project    = $this->loadModel('project')->fetchByID($projectID);
        $executions = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('type')->in('stage,sprint,kanban')
            ->andWhere('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(!in_array($status, array('all', 'undone', 'noclosed')))->andWhere('status')->in($status)->fi()
            ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
            ->beginIF($status == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($devel === true)->andWhere('attribute')->in('dev,qa,release')->fi()
            ->beginIF($appendedID)->orWhere('id')->eq($appendedID)->fi()
            ->orderBy('order_asc')
            ->beginIF($limit)->limit($limit)->fi()
            ->fetchAll('id');

        /* Add product name and parent stage name to stage name. */
        $executions = $this->executionTao->batchProcessName($executions, $project);
        if(!$pairs) return $executions;

        $projectPairs = array();
        if(empty($projectID))
        {
            $projectPairs = $this->dao->select('t1.id,t1.name')->from(TABLE_PROJECT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.id=t2.project')
                ->where('t2.id')->in(array_keys($executions))
                ->fetchPairs('id', 'name');
        }
        else
        {
            $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetchPairs('id', 'name');
        }

        $this->app->loadLang('project');
        $executionPairs = array();
        foreach($executions as $execution)
        {
            $executionPairs[$execution->id]  = '';
            $executionPairs[$execution->id] .= isset($projectPairs[$execution->project]) ? ($projectPairs[$execution->project] . '/') : '';
            $executionPairs[$execution->id] .= $execution->name;

            if(empty($execution->multiple)) $executionPairs[$execution->id] = $projectPairs[$execution->project] . "({$this->lang->project->disableExecution})";
        }
        return $executionPairs;
    }

    /**
     * 批量处理执行的名称。
     * The name of the batch process execution.
     *
     * @param  array       $executions
     * @param  object|bool $project
     * @access protected
     * @return array
     */
    protected function batchProcessName(array $executions, object|bool $project): array
    {
        if(!$project) return $executions;

        if(isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus', 'ipd')))
        {
            $executionProducts = array();
            if($project->hasProduct && ($project->stageBy == 'product'))
            {
                $executionProducts = $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
                    ->where('project')->in(array_keys($executions))
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetchPairs();
            }

            $allExecutions = $this->dao->select('id,name,parent,grade')->from(TABLE_EXECUTION)
                ->where('type')->in('stage,sprint,kanban')
                ->andWhere('deleted')->eq('0')
                ->andWhere('project')->eq($project->id)
                ->fetchAll('id');

            $parents = array();
            foreach($allExecutions as $id => $execution) $parents[$execution->parent] = $execution->parent;

            $executions = $this->resetExecutionSorts($executions);
            foreach($executions as $id => $execution)
            {
                if(isset($parents[$execution->id]))
                {
                    unset($executions[$id]);
                    continue;
                }

                $executionName = '';
                $paths         = array_slice(explode(',', trim($execution->path, ',')), 1);
                foreach($paths as $path)
                {
                    if(isset($allExecutions[$path])) $executionName .= '/' . $allExecutions[$path]->name;
                }

                if($executionName) $execution->name = ltrim($executionName, '/');
                if(isset($executionProducts[$id])) $execution->name = $executionProducts[$id] . '/' . $execution->name;
            }
        }

        return $executions;
    }

    /**
     * 获取影子执行的ID。
     * Get no multiple execution id.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function getNoMultipleID(int $projectID): int
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getExecution()->id;

        return (int)$this->dao->select('id')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('multiple')->eq(0)->andWhere('deleted')->eq(0)->fetch('id');
    }

    /**
     * 获取执行下分支列表。
     * Get branches of execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getBranches(int $executionID): array
    {
        $productBranchPairs = $this->dao->select('product, branch')->from(TABLE_PROJECTPRODUCT)
            ->where('project')->eq($executionID)
            ->fetchPairs();
        $branches = $this->loadModel('branch')->getByProducts(array_keys($productBranchPairs));
        foreach($productBranchPairs as $product => $branch)
        {
            if($branch == 0 && isset($branches[$product])) $productBranchPairs[$product] = implode(',', array_keys($branches[$product]));
        }

        return $productBranchPairs;
    }

    /**
     * 获取执行树状图数据。
     * Get executions tree data.
     * @param  int     $executionID
     * @access public
     * @return array
     */
    public function getTree(int $executionID): array
    {
        $firstTree = array(
            'id'      => 0,
            'name'    => '/',
            'type'    => 'task',
            'actions' => false,
            'root'    => $executionID,
        );

        $fullTrees = $this->loadModel('tree')->getTaskStructure($executionID, 0);
        array_unshift($fullTrees, $firstTree);
        foreach($fullTrees as $i => &$tree)
        {
            $tree = (object) $tree;
            if($tree->type == 'product')
            {
                $firstTree['type'] = 'story';
                $firstTree['root'] = $tree->root;
                array_unshift($tree->children, $firstTree);
            }

            $tree = $this->fillTasksInTree($tree, $executionID);
            if(empty($tree->children)) unset($fullTrees[$i]);
        }

        return array_values($fullTrees);
    }

    /**
     * 获取完整路径的执行名称。
     * Get full path name of execution.
     * @param  array $executions
     * @access public
     * @return array
     */
    public function getFullNameList(array $executions): array
    {
        $allExecutions = $this->dao->select('id,name,parent')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');

        $nameList = array();
        foreach($executions as $executionID => $execution)
        {
            if($execution->grade <= 1)
            {
                $nameList[$executionID] = $execution->name;
                continue;
            }

            /* Set execution name. */
            $paths = array_slice(explode(',', trim($execution->path, ',')), 1);
            $executionName = array();
            foreach($paths as $path)
            {
                if(isset($allExecutions[$path])) $executionName[] = $allExecutions[$path]->name;
            }

            $nameList[$executionID] = implode('/', $executionName);
        }

        return $nameList;
    }

    /**
     * 获取执行关联的产品下的执行列表。
     * Get related executions.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getRelatedExecutions(int $executionID): array
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)->where('project')->eq((int)$executionID)->fetchAll('product');
        if(!$products) return array();

        return $this->dao->select('t1.id, t1.name')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')
            ->on('t1.id = t2.project')
            ->where('t2.product')->in(array_keys($products))
            ->andWhere('t1.id')->ne((int)$executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id')
            ->fetchPairs();
    }

    /**
     * 获取子阶段列表。
     * Get child executions.
     *
     * @param  int    $executionID
     * @param  string $orderBy
     * @param  string $type         all|child
     * @access public
     * @return array
     */
    public function getChildExecutions(int $executionID, string $orderBy = 'id_desc', string $type = 'child'): array
    {
        $executionID = (int)$executionID;
        if(empty($executionID)) return array();

        $path = '';
        if($type != 'child')
        {
            $path = $this->dao->select('path')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('path');
            if(empty($path)) return array();
        }

        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->beginIF($type == 'child')->andWhere('parent')->eq($executionID)->fi()
            ->beginIF($type != 'child')->andWhere('path')->like("{$path}%")->andWhere('id')->ne($executionID)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id', false);
    }

    /**
     * 获取受限执行id并保存到session中。
     * Get limited execution id and save it to session.
     *
     * @access public
     * @return bool|string
     */
    public function getLimitedExecution(): bool|string
    {
        /* If is admin, return true. */
        if($this->app->user->admin) return true;

        /* Get all teams of all limited projects and group by projects. */
        $subExecutions = array();
        $projects      = $this->dao->select('root, limited')->from(TABLE_TEAM)
            ->where('type')->eq('project')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('limited')->eq('yes')
            ->orderBy('root asc')
            ->fetchPairs('root', 'root');
        if($projects) $subExecutions = $this->dao->select('id, id')->from(TABLE_EXECUTION)->where('project')->in($projects)->fetchPairs();

        /* Get no limited executions in limited projects. */
        $notLimitedExecutions = $this->dao->select('root, limited')->from(TABLE_TEAM)
            ->where('type')->eq('execution')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('limited')->eq('no')
            ->andWhere('root')->in($subExecutions)
            ->orderBy('root asc')
            ->fetchPairs('root', 'root');

        /* 获取当前用户再受限项目下的非受限执行以外的执行。 */
        $subExecutions = array_diff($subExecutions, $notLimitedExecutions);

        /* Get all teams of all executions and group by executions, save it as static. */
        $executions = $this->dao->select('root, limited')->from(TABLE_TEAM)
            ->where('type')->eq('execution')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('limited')->eq('yes')
            ->orderBy('root asc')
            ->fetchPairs('root', 'root');

        $executions = array_merge($executions, $subExecutions);

        $this->session->set('limitedExecutions', implode(',', $executions));
        return $this->session->limitedExecutions;
    }

    /**
     * 根据产品/执行等信息获取任务列表
     * Get tasks by product/execution etc.
     *
     * @param  int       $productID
     * @param  int|array $executionID
     * @param  array     $executions
     * @param  string    $browseType
     * @param  int       $queryID
     * @param  int       $moduleID
     * @param  string    $sort
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getTasks(int $productID, int|array $executionID, array $executions, string $browseType, int $queryID, int $moduleID, string $sort, ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getTasks();

        /* Set modules and $browseType. */
        $modules = array();
        if($moduleID) $modules = $this->loadModel('tree')->getAllChildID($moduleID);
        if(in_array($browseType, array('bymodule', 'byproduct')) && $this->session->taskBrowseType && $this->session->taskBrowseType != 'bysearch') $browseType = $this->session->taskBrowseType;

        /* Get tasks. */
        if($browseType != "bysearch")
        {
            $queryStatus = $browseType == 'byexecution' ? 'all' : $browseType;
            if($queryStatus == 'unclosed')
            {
                $queryStatus = $this->lang->task->statusList;
                unset($queryStatus['closed']);
                $queryStatus = array_keys($queryStatus);
            }
            return $this->loadModel('task')->getExecutionTasks($executionID, $productID, $queryStatus, $modules, $sort, $pager);
        }
        else
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('taskQuery', $query->sql);
                $this->session->set('taskForm', $query->form);
            }
            elseif(!$this->session->taskQuery)
            {
                $this->session->set('taskQuery', ' 1 = 1');
            }

            if(strpos($this->session->taskQuery, "deleted =") === false) $this->session->set('taskQuery', $this->session->taskQuery . " AND deleted = '0'");

            $taskQuery = $this->session->taskQuery;

            /* Limit current execution when no execution. */
            if(strpos($taskQuery, "`execution` =") === false && strpos($taskQuery, "`project` =") === false && $executionID) $taskQuery .= " AND `execution` = $executionID";
            if(strpos($taskQuery, "`execution` = 'all'") !== false)
            {
                $executions     = $this->getPairs(0, 'all', "nocode,noprefix,multiple");
                $executionQuery = "`execution` " . helper::dbIN(array_keys($executions));
                $taskQuery      = str_replace("`execution` = 'all'", $executionQuery, $taskQuery); // Search all execution.
            }
            if(strpos($taskQuery, "`execution`") === false) $taskQuery .= " AND `execution` " . helper::dbIN(array_keys($executions));

            /* Process all project query. */
            if(strpos($taskQuery, "`project` = 'all'") !== false)
            {
                $projects     = $this->loadModel('project')->getPairsByProgram();
                $projectQuery = "`project` " . helper::dbIN(array_keys($projects));
                $taskQuery    = str_replace("`project` = 'all'", $projectQuery, $taskQuery);
            }

            $this->session->set('taskQueryCondition', $taskQuery, $this->app->tab);
            $this->session->set('taskOnlyCondition', true, $this->app->tab);

            return $this->getSearchTasks($taskQuery, $sort, $pager);
        }
    }

    /**
     * 根据执行ID列表获取父任务分组信息。
     * Get the task data group by execution id list.
     *
     * @param  array  $executionIdList
     * @param  bool   $filterStatus
     * @access public
     * @return array
     */
    public function getTaskGroupByExecution(array $executionIdList = array(), bool $filterStatus = true): array
    {
        if(empty($executionIdList)) return array();

        $fields = "t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus";
        $executionTasks = $this->dao->select($fields)
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($filterStatus)->andWhere('t1.status')->notin('closed,cancel')->fi()
            ->andWhere('t1.execution')->in($executionIdList)
            ->orderBy('t1.order_asc, t1.id_asc')
            ->fetchGroup('execution', 'id');

        $begin      = $end = helper::today();
        $taskIdList = array();
        foreach($executionTasks as $tasks)
        {
            $taskIdList = array_merge($taskIdList, array_keys($tasks));
            foreach($tasks as $task)
            {
                if(!empty($task->deadline) && !helper::isZeroDate($task->deadline) && $task->deadline < $begin) $begin = $task->deadline;
            }
        }
        $taskIdList        = array_unique($taskIdList);
        $teamGroups        = $this->dao->select('id,task,account,status')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'id');
        $storyVersionPairs = $this->loadModel('task')->getTeamStoryVersion($taskIdList);
        $workingDays       = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);

        foreach($executionTasks as $tasks)
        {
            foreach($tasks as $task)
            {
                if(isset($teamGroups[$task->id])) $task->team = $teamGroups[$task->id];

                /* Delayed or not?. */
                $isNotCancel    = !in_array($task->status, array('cancel', 'closed')) || ($task->status == 'closed' && !helper::isZeroDate($task->finishedDate) && $task->closedReason != 'cancel');
                $isComputeDelay = !helper::isZeroDate($task->deadline) && $isNotCancel;
                if($isComputeDelay) $task = $this->task->computeDelay($task, $task->deadline, $workingDays);

                /* Story changed or not. */
                $task->storyVersion = zget($storyVersionPairs, $task->id, $task->storyVersion);
                $task->needConfirm  = false;
                if(!empty($task->storyStatus) && $task->storyStatus == 'active' && !in_array($task->status, array('cancel', 'closed')) && $task->latestStoryVersion > $task->storyVersion)
                {
                    $task->needConfirm = true;
                    $task->status      = 'changed';
                }
            }
        }

        return $executionTasks;
    }

    /**
     * 获取执行的信息。
     * Get the execution information by ID.
     *
     * @param  int          $executionID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $executionID, bool $setImgSize = false): object|false
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecution();

        $execution = $this->fetchByID($executionID);
        if(!$execution) return false;

        /* Judge whether the execution is delayed. */
        if($execution->status != 'done' and $execution->status != 'closed' and $execution->status != 'suspended')
        {
            $delayDays = $this->loadModel('holiday')->getActualWorkingDays($execution->end, helper::today());
            if(!empty($delayDays))
            {
                $delay = count($delayDays) - 1;
                if($delay > 0) $execution->delay = $delay;
            }
        }

        $totalHours = $this->dao->select('sum(t1.days * t1.hours) AS totalHours')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')
            ->on('t1.account=t2.account')
            ->where('t1.root')->eq($execution->id)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq(0)
            ->fetch('totalHours');

        /* Set the hours information for the task. */
        $execution->totalHours    = $totalHours;
        $execution->days          = $execution->days ? $execution->days : 0;
        $execution->totalEstimate = round((float)$execution->estimate, 1);
        $execution->totalConsumed = round((float)$execution->consumed, 1);
        $execution->totalLeft     = round((float)$execution->left, 1);

        $execution = $this->loadModel('file')->replaceImgURL($execution, 'desc');
        if($setImgSize) $execution->desc = $this->file->setImgSize($execution->desc);

        $child = $this->dao->select('id')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->andWhere('deleted')->eq(0)->fetch('id');
        $execution->isParent = !empty($child) ? 1 : 0;

        return $execution;
    }

    /**
     * Get execution by build id.
     *
     * @param  int    $buildID
     * @access public
     * @return object
     */
    public function getByBuild(int $buildID)
    {
        $build = $this->loadModel('build')->getById($buildID);
        if(!$build) return 0;
        return $this->getById((int)$build->execution);
    }

    /**
     * 获取执行的产品相关负责人。
     * Get the default managers for a execution from it's related products.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function getDefaultManagers(int $executionID): object
    {
        $managers = $this->dao->select('PO,QD,RD')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
            ->where('t2.project')->eq($executionID)
            ->fetch();
        if($managers) return $managers;

        $managers = new stdclass();
        $managers->PO = '';
        $managers->QD = '';
        $managers->RD = '';
        return $managers;
    }

    /**
     * 根据产品ID列表查询分支信息。
     * Get branch information by the product ID list.
     *
     * @param  array   $productIdList
     * @param  int     $projectID
     * @param  string  $param
     * @param  array   $appendBranch
     * @access public
     * @return array
     */
    public function getBranchByProduct(array $productIdList, int $projectID = 0, string $param = 'noclosed', array $appendBranch = array()): array
    {
        $branchGroup = $this->loadModel('branch')->getByProducts($productIdList, $param, $appendBranch);

        if($projectID)
        {
            $projectProducts = $this->loadModel('project')->getBranchesByProject($projectID);
            foreach($branchGroup as $productID => $branchPairs)
            {
                foreach($branchPairs as $branchID => $branchName)
                {
                    if(strpos($param, 'withMain') !== false and $branchID == BRANCH_MAIN) continue;
                    if(!isset($projectProducts[$productID][$branchID])) unset($branchGroup[$productID][$branchID]);
                }
            }
        }
        return $branchGroup;
    }

    /**
     * 获取排序后的执行列表信息。
     * Get ordered executions.
     *
     * @param  int    $executionID
     * @param  string $status
     * @param  int    $num
     * @param  string $param
     * @access public
     * @return array
     */
    public function getOrderedExecutions(int $executionID, string $status, int $num = 0, string $param = ''): array
    {
        $executionList = $this->getList($executionID, 'all', $status);
        if(empty($executionList)) return $executionList;

        $executions = array();
        $param      = strtolower($param);
        if($param == 'skipparent')
        {
            $parentExecutions = array();
            foreach($executionList as $execution) $parentExecutions[$execution->parent] = $execution->parent;
        }

        foreach($executionList as $execution)
        {
            if(empty($execution->multiple)) continue;
            if(!$this->app->user->admin && !$this->checkPriv($execution->id)) continue;
            if($param == 'skipparent' && isset($parentExecutions[$execution->id])) continue;

            if($execution->status != 'done' && $execution->status != 'closed' && $execution->PM == $this->app->user->account)
            {
                $executions[$execution->id] = $execution;
            }
            elseif($execution->status != 'done' && $execution->status != 'closed' && $execution->PM != $this->app->user->account)
            {
                $executions[$execution->id] = $execution;
            }
            elseif($execution->status == 'done' || $execution->status == 'closed')
            {
                $executions[$execution->id] = $execution;
            }
        }

        if(empty($num)) return $executions;
        return array_slice($executions, 0, $num, true);
    }

    /**
     * 构造需求的搜索表单。
     * Build story search form.
     *
     * @param  array  $products
     * @param  array  $branchGroups
     * @param  array  $modules
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type         executionStory
     * @param  object $execution
     * @access public
     * @return void
     */
    public function buildStorySearchForm(array $products, array $branchGroups, array $modules, int $queryID, string $actionURL, string $type = 'executionStory', ?object $execution = null): void
    {
        $this->loadModel('productplan');
        $this->app->loadLang('branch');

        $branchPairs  = array(BRANCH_MAIN => $this->lang->branch->main);
        $productType  = 'normal';
        $productPairs = array(0 => '');
        $branches     = empty($execution) ? array() : $this->loadModel('project')->getBranchesByProject($execution->id);
        $planGroup    = array();
        $planPairs    = array();

        /* Get the relevant data for the search. */
        foreach($products as $productID => $product)
        {
            $productPairs[$product->id] = $product->name;
            $planGroup = $this->productplan->getBranchPlanPairs($productID, array(BRANCH_MAIN) + $product->branches, '', true);
            foreach($planGroup as $plans) $planPairs += $plans;

            if($product->type == 'normal') continue;
            $productType = $product->type;

            if(!isset($branches[$product->id])) continue;
            foreach($branches[$product->id] as $branchID => $branch)
            {
                if(!isset($branchGroups[$product->id][$branchID])) continue;
                if($branchID != BRANCH_MAIN) $branchPairs[$branchID] = ((count($products) > 1) ? $product->name . '/' : '') . $branchGroups[$product->id][$branchID];
            }
        }

        /* Build search form. */
        if($type == 'executionStory') $this->config->product->search['module'] = 'executionStory';
        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;

        $this->config->product->search['fields']['title']             = $this->lang->story->name;
        $this->config->product->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);
        $this->config->product->search['params']['plan']['values']    = $planPairs;
        $this->config->product->search['params']['module']['values']  = $modules;
        $this->config->product->search['params']['status']            = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->story->statusList);
        $this->config->product->search['params']['stage']['values']   = array('' => '') + $this->lang->story->stageList;
        if($productType == 'normal')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->product->search['params']['branch']['values'] = $branchPairs;
        }

        $project = $execution;
        if(in_array($execution->type, array('sprint', 'stage', 'kanban'))) $project = $this->loadModel('project')->getByID($execution->project);
        if(empty($project->hasProduct))
        {
            unset($this->config->product->search['fields']['product']);
            unset($this->config->product->search['params']['product']);
            if($project->model != 'kanban')
            {
                unset($this->config->product->search['fields']['plan']);
                unset($this->config->product->search['params']['plan']);
            }
        }

        $gradePairs = array();
        $gradeList  = $this->loadModel('story')->getGradeList('');
        $storyTypes = isset($project->storyType) ? $project->storyType : 'story';
        if(!($execution->type == 'stage' && in_array($execution->attribute, array('mix', 'request', 'design')))) $storyTypes = 'story';
        foreach($gradeList as $grade)
        {
            if(strpos($storyTypes, $grade->type) === false) continue;
            $key = (string)$grade->type . (string)$grade->grade;
            $gradePairs[$key] = $grade->name;
        }
        asort($gradePairs);

        $this->config->product->search['params']['grade']['values'] = $gradePairs;

        $this->config->product->search['onMenuBar'] = 'yes';

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * 获取要导入的执行列表。
     * Get executions to import.
     *
     * @param  array  $executionIds
     * @param  string $type sprint|stage|kanban
     * @param  string $model
     * @access public
     * @return array
     */
    public function getToImport(array $executionIds, string $type, string $model = ''): array
    {
        return $this->dao->select("t1.id, concat_ws(' / ', t2.name, t1.name) as name")->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t2.id=t1.project')
            ->where('t1.id')->in($executionIds)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(empty($model) || strpos(',waterfallplus,agileplus,', ",$model,") === false)->andWhere('t1.type')->eq($type)->fi()
            ->beginIF(!empty($model) && $model == 'agileplus')->andWhere('t1.type')->in(array('sprint', 'kanban'))->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.id desc')
            ->fetchPairs('id', 'name');
    }

    /**
     * 更新执行关联的产品信息。
     * Update products of a execution.
     *
     * @param  int          $executionID
     * @param  object|array $postData
     * @access public
     * @return bool
     */
    public function updateProducts(int $executionID, object|array $postData): bool
    {
        $this->loadModel('user');
        $otherProducts = zget($postData, 'otherProducts', array());
        $products      = !empty($otherProducts) ? array_filter(array_merge(zget($postData, 'products', array()), $otherProducts)) : zget($postData, 'products', array());
        $branches      = zget($postData, 'branch', array(0));
        $plans         = zget($postData, 'plans',  array());
        $oldProducts   = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchGroup('product', 'branch');
        if(empty($_POST['otherProducts'])) $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->exec();
        $members = array_keys($this->getTeamMembers($executionID));
        if(empty($products))
        {
            $this->user->updateUserView(array_keys($oldProducts), 'product', $members);
            return true;
        }

        $existedProducts = array();
        foreach($products as $i => $productID)
        {
            if(!isset($existedProducts[$productID])) $existedProducts[$productID] = array();

            $oldPlan = '';
            $branch  = isset($branches[$i]) ? (array)$branches[$i] : array(0);
            foreach($branch as $branchID)
            {
                if(isset($existedProducts[$productID][$branchID])) continue;
                if(isset($oldProducts[$productID][$branchID]))
                {
                    $oldProduct = $oldProducts[$productID][$branchID];
                    if($this->app->rawModule == 'project' || $this->app->rawMethod != 'edit') $oldPlan = $oldProduct->plan;
                }

                $data = new stdclass();
                $data->project = $executionID;
                $data->product = (int)$productID;
                $data->branch  = (int)$branchID;
                $data->plan    = isset($plans[$productID]) ? implode(',', $plans[$productID]) : $oldPlan;
                $data->plan    = trim($data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";

                $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
                $existedProducts[$productID][$branchID] = true;
            }
        }

        $oldProductKeys = array_keys($oldProducts);
        $needUpdate     = array_merge(array_diff($oldProductKeys, $products), array_diff($products, $oldProductKeys));
        if($needUpdate) $this->user->updateUserView($needUpdate, 'product', $members);
        return true;
    }

    /**
     * 获取可被导入的任务列表。
     * Get tasks can be imported.
     *
     * @param  int    $toExecution
     * @param  array  $branches
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getTasks2Imported(int $toExecution, array $branches, string $orderBy = 'id_desc'): array
    {
        $execution       = $this->fetchById($toExecution);
        $project         = $this->loadModel('project')->fetchById($execution->project);
        $brotherProjects = $this->project->getBrotherProjects($project);
        $executions      = $this->dao->select('id')->from(TABLE_EXECUTION)
            ->where('project')->in($brotherProjects)
            ->andWhere('multiple')->eq('1')
            ->andWhere('status')->ne('closed')
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id');

        $branches = str_replace(',', "','", $branches);
        return $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.status')->in('wait,doing,pause,cancel')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.execution')->in(array_keys($executions))
            ->andWhere("(t1.story = 0 OR (t2.branch IN ('0','" . implode("','", $branches) . "') AND t2.product " . helper::dbIN(array_keys($branches)) . "))")
            ->orderBy($orderBy)
            ->fetchGroup('execution', 'id');
    }

    /**
     * 转入任务到指定的执行。
     * Import tasks.
     *
     * @param  int    $executionID
     * @param  array  $taskIdList
     * @access public
     * @return array
     */
    public function importTask(int $executionID, array $taskIdList): array
    {
        $this->loadModel('action');
        $dateExceed   = array();
        $taskStories  = array();
        $parents      = array();
        $execution    = $this->fetchByID($executionID);
        $tasks        = $this->loadModel('task')->getByIdList($taskIdList);
        $assignedToes = array();
        foreach($tasks as $task)
        {
            /* Save the assignedToes and stories, should linked to execution. */
            $assignedToes[$task->assignedTo] = $task->execution;
            $taskStories[$task->story]       = $task->story;
            if($task->isParent) $parents[$task->id] = $task->id;

            $data = new stdclass();
            $data->project      = $execution->project;
            $data->execution    = $executionID;
            $data->canceledBy   = '';
            $data->canceledDate = null;

            if(!empty($this->config->limitTaskDate))
            {
                if($task->estStarted < $execution->begin || $task->estStarted > $execution->end || $task->deadline > $execution->end || $task->deadline < $execution->begin) $dateExceed[] = "#{$task->id}";
                if($task->estStarted < $execution->begin || $task->estStarted > $execution->end) $data->estStarted = $execution->begin;
                if($task->deadline > $execution->end || $task->deadline < $execution->begin)     $data->deadline   = $execution->end;
            }

            /* Update tasks and save logs. */
            if($task->isParent) $this->dao->update(TABLE_TASK)->data($data)->where('parent')->eq($task->id)->exec();

            $data->status = $task->consumed > 0 ? 'doing' : 'wait';
            if($data->status == 'wait') $data->realStarted = null;
            $this->dao->update(TABLE_TASK)->data($data)->where('id')->eq($task->id)->exec();
            $this->action->create('task', $task->id, 'moved', '', $task->execution);
        }

        /* Other data process after task batch import. */
        $this->afterImportTask($execution, $parents, $assignedToes, $taskStories);

        return $dateExceed;
    }

    /**
     * 批量导入任务后的其他数据处理。
     * Other data process after task batch import.
     *
     * @param  object $execution
     * @param  array  $parents
     * @param  array  $assignedToes
     * @param  array  $taskStories
     * @access public
     * @return void
     */
    public function afterImportTask(object $execution, array $parents, array $assignedToes, array $taskStories)
    {
        /* Get stories of children task. */
        if(!empty($parents))
        {
            $children = $this->dao->select('*')->from(TABLE_TASK)->where('parent')->in($parents)->fetchAll('id');
            foreach($children as $child) $taskStories[$child->story] = $child->story;
        }

        /* Add members to execution team. */
        $teamMembers = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution');
        $today       = helper::today();
        foreach($assignedToes as $account => $preExecutionID)
        {
            if(!empty($account) && !isset($teamMembers[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)
                    ->where('root')->eq($preExecutionID)
                    ->andWhere('type')->eq('execution')
                    ->andWhere('account')->eq($account)
                    ->fetch();
                if(empty($role)) continue;

                $role->root = $execution->id;
                $role->join = $today;
                $this->dao->replace(TABLE_TEAM)->data($role)->exec();
            }
        }

        /* Link stories. */
        $executionStories = $this->loadModel('story')->getExecutionStoryPairs($execution->id);
        $lastOrder        = (int)$this->dao->select('`order`')->from(TABLE_PROJECTSTORY)->where('project')->eq($execution->id)->orderBy('order_desc')->limit(1)->fetch('order');
        $stories          = $this->dao->select("id as story, product, version")->from(TABLE_STORY)->where('id')->in(array_keys($taskStories))->fetchAll('story');

        $this->loadModel('action');
        foreach($taskStories as $storyID)
        {
            if(!isset($executionStories[$storyID]) && isset($stories[$storyID]))
            {
                $lastOrder ++;

                $story = $stories[$storyID];
                $story->project = $execution->id;
                $story->order   = $lastOrder;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($story)->exec();

                if($execution->multiple || $execution->type == 'project') $this->action->create('story', $storyID, 'linked2execution', '', $execution->id);
            }
        }
    }

    /**
     * 统计执行的需求数、任务数、Bug数。
     * Statistics the number of stories, tasks, and bugs for the execution.
     *
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function statRelatedData(int $executionID): object
    {
        $storyCount = $this->dao->select('count(t2.story) as storyCount')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id = t2.story')
            ->where('t2.project')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch('storyCount');

        $taskCount = $this->dao->select('count(id) as taskCount')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->fetch('taskCount');
        $bugCount  = $this->dao->select('count(id) as bugCount')->from(TABLE_BUG)->where('execution')->eq($executionID)->andWhere('deleted')->eq(0)->fetch('bugCount');

        $statData = new stdclass();
        $statData->storyCount = $storyCount;
        $statData->taskCount  = $taskCount;
        $statData->bugCount   = $bugCount;

        return $statData;
    }

    /**
     * 导入Bug。
     * Import task from Bug.
     *
     * @param  array      $tasks
     * @access public
     * @return array|bool
     */
    public function importBug(array $tasks): array|bool
    {
        $this->loadModel('story');
        $this->loadModel('action');

        foreach($tasks as $key => $task)
        {
            $bug = $task->bug;
            unset($task->bug);

            if(isset($task->estimate)) $task->estimate = round((float)$task->estimate, 2);
            if(isset($task->left)) $task->left = round((float)$task->left, 2);
            if(!$bug->confirmed) $this->dao->update(TABLE_BUG)->set('confirmed')->eq(1)->where('id')->eq($bug->id)->exec();
            $task->version = 1;
            $this->dao->insert(TABLE_TASK)->data($task)->exec();
            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_TASK)->set('path')->eq(",$taskID,")->where('id')->eq($taskID)->exec();

            /* Update story's stage and create a action. */
            if($task->story !== false) $this->story->setStage($task->story);
            $actionID = $this->action->create('task', $taskID, 'Opened', '');

            $this->dao->update(TABLE_BUG)->set('toTask')->eq($taskID)->where('id')->eq($key)->exec();
            $this->action->create('bug', $key, 'Totask', '', $taskID);

            $mails[$key] = new stdClass();
            $mails[$key]->taskID   = $taskID;
            $mails[$key]->actionID = $actionID;

            $this->afterImportBug($task, $bug);
            if(dao::isError()) return false;

            if($this->config->edition != 'open')
            {
                $relation = new stdClass();
                $relation->AType    = 'bug';
                $relation->AID      = $bug->id;
                $relation->relation = 'transferredto';
                $relation->BType    = 'task';
                $relation->BID      = $taskID;
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }
        }

        return $mails;
    }

    /**
     * 批量导入Bug后的其他数据处理。
     * other data process after import bugs.
     *
     * @param  object $task
     * @param  object $bug
     * @access public
     * @return bool
     */
    public function afterImportBug(object $task, object $bug): bool
    {
        $this->loadModel('action');

        /* activate bug if bug postponed. */
        $now = helper::now();
        if($bug->status == 'resolved' && $bug->resolution == 'postponed')
        {
            $newBug = new stdclass();
            $newBug->lastEditedBy   = $this->app->user->account;
            $newBug->lastEditedDate = $now;
            $newBug->assignedDate   = $now;
            $newBug->status         = 'active';
            $newBug->resolvedDate   = null;
            $newBug->resolution     = '';
            $newBug->resolvedBy     = '';
            $newBug->resolvedBuild  = '';
            $newBug->closedBy       = '';
            $newBug->closedDate     = null;
            $newBug->duplicateBug   = '0';

            $this->dao->update(TABLE_BUG)->data($newBug)->autoCheck()->where('id')->eq($bug->id)->exec();
            $this->dao->update(TABLE_BUG)->set('activatedCount = activatedCount + 1')->where('id')->eq($bug->id)->exec();

            $actionID = $this->action->create('bug', $bug->id, 'Activated');
            $changes  = common::createChanges($bug, $newBug);
            $this->action->logHistory($actionID, $changes);
        }

        if(isset($task->assignedTo) && $task->assignedTo && $task->assignedTo != $bug->assignedTo)
        {
            $newBug = new stdClass();
            $newBug->lastEditedBy   = $this->app->user->account;
            $newBug->lastEditedDate = $now;
            $newBug->assignedTo     = $task->assignedTo;
            $newBug->assignedDate   = $now;
            $this->dao->update(TABLE_BUG)->data($newBug)->where('id')->eq($bug->id)->exec();
            if(dao::isError()) return false;

            $actionID = $this->action->create('bug', $bug->id, 'Assigned', '', $newBug->assignedTo);
            $changes  = common::createChanges($bug, $newBug);
            $this->action->logHistory($actionID, $changes);
        }

        return !dao::isError();
    }

    /**
     * 修改执行的所属项目。
     * Change execution project.
     *
     * @param  int    $newProjectID
     * @param  int    $oldProjectID
     * @param  int    $executionID
     * @param  string $syncStories yes|no
     * @access public
     * @return void
     */
    public function changeProject(int $newProjectID, int $oldProjectID, int $executionID, string $syncStories = 'no'): void
    {
        if($newProjectID == $oldProjectID) return;

        $this->dao->update(TABLE_EXECUTION)->set('parent')->eq($newProjectID)->set('path')->eq(",$newProjectID,$executionID,")->where('id')->eq($executionID)->exec();

        /* Update the project to which the relevant data belongs. */
        $this->dao->update(TABLE_BUILD)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_BUG)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_CASE)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_DOC)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_DOCLIB)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_TASK)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_TESTREPORT)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();
        $this->dao->update(TABLE_TESTTASK)->set('project')->eq($newProjectID)->where('project')->eq($oldProjectID)->andWhere('execution')->eq($executionID)->exec();

        /* Update the team members and whitelist of the project. */
        $addedAccounts = $this->updateProjectUsers($executionID, $newProjectID);
        if($addedAccounts) $this->loadModel('user')->updateUserView(array($newProjectID), 'project', $addedAccounts);

        /* Sync stories to new project. */
        if($syncStories == 'yes')
        {
            $this->loadModel('action');
            $projectLinkedStories   = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($newProjectID)->fetchPairs('story', 'story');
            $executionLinkedStories = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->fetchAll();
            foreach($executionLinkedStories as $linkedStory)
            {
                if(isset($projectLinkedStories[$linkedStory->story])) continue;

                $linkedStory->project = $newProjectID;
                $this->dao->insert(TABLE_PROJECTSTORY)->data($linkedStory)->exec();
                $this->action->create('story', $linkedStory->story, 'linked2project', '', $newProjectID);
            }
        }
    }

    /**
     * 更新项目的团队成员和白名单。
     * Update the team members and whitelist of the project.
     *
     * @param  int    $executionID
     * @param  int    $newProjectID
     * @access public
     * @return array
     */
    public function updateProjectUsers(int $executionID, int $newProjectID): array
    {
        $executionTeam = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
        $projectTeam   = $this->user->getTeamMemberPairs($newProjectID, 'project');
        $addedAccounts = array();
        $today         = helper::today();
        foreach($executionTeam as $account => $realname)
        {
            if(isset($projectTeam[$account])) continue;

            $member = new stdclass();
            $member->root    = $newProjectID;
            $member->type    = 'project';
            $member->account = $account;
            $member->join    = $today;
            $member->days    = 0;
            $member->hours   = $this->config->execution->defaultWorkhours;
            $this->dao->replace(TABLE_TEAM)->data($member)->exec();

            $addedAccounts[$account] = $account;
        }

        $executionWhitelist = $this->loadModel('personnel')->getWhitelistAccount($executionID, 'sprint');
        $projectWhitelist   = $this->personnel->getWhitelistAccount($newProjectID, 'project');
        foreach($executionWhitelist as $account)
        {
            if(isset($projectWhitelist[$account])) continue;

            $whitelist = new stdclass();
            $whitelist->account    = $account;
            $whitelist->objectType = 'project';
            $whitelist->objectID   = $newProjectID;
            $whitelist->type       = 'whitelist';
            $whitelist->source     = 'sync';
            $this->dao->replace(TABLE_ACL)->data($whitelist)->exec();

            $addedAccounts[$account] = $account;
        }

        return $addedAccounts;
    }

    /**
     * 关联需求到项目或执行。
     * Link story for project or execution.
     *
     * @param  int    $executionID projectID|executionID
     * @param  array  $stories
     * @param  string $extra
     * @param  array  $lanes
     * @param  string $storyType
     * @access public
     * @return bool
     */
    public function linkStory(int $executionID, array $stories = array(), string $extra = '', array $lanes = array(), string $storyType = 'story'): bool
    {
        if(empty($executionID) || empty($stories)) return false;

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->loadModel('action');
        $this->loadModel('kanban');
        $this->loadModel('story');
        $versions         = $this->story->getVersions($stories);
        $linkedStories    = $this->dao->select('story,`order`')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('story', 'order');
        $lastOrder        = (int)reset($linkedStories);
        $storyList        = $this->story->getByList(array_values($stories));
        $execution        = $this->getByID($executionID);
        $notAllowedStatus = $this->app->rawMethod == 'batchcreate' ? 'closed' : 'draft,reviewing,closed';
        $laneID           = isset($output['laneID']) ? $output['laneID'] : 0;

        $project = $execution->type == 'project' ? $execution : $this->loadModel('project')->getByID($execution->project);

        foreach($stories as $storyID)
        {
            if(isset($linkedStories[$storyID])) continue;
            if(!isset($storyList[$storyID]))    continue;
            if(strpos($notAllowedStatus, (string)$storyList[$storyID]->status) !== false) continue;

            $storyID = (int)$storyID;
            $story   = zget($storyList, $storyID, '');
            if(empty($story)) continue;
            if(strpos($project->storyType, "$story->type") === false && $this->config->vision == 'rnd') continue;

            if($execution->multiple && $story->type != 'story' && (!($execution->type == 'stage' && in_array($execution->attribute, array('mix', 'request', 'design'))) && $execution->type != 'project') && $this->config->vision == 'rnd') continue;
            if(!empty($lanes[$storyID])) $laneID = $lanes[$storyID];

            $columnID = $this->kanban->getColumnIDByLaneID((int)$laneID, 'backlog');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;
            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, (int)$laneID, (int)$columnID, $storyType, (string)$storyID);

            $data = new stdclass();
            $data->project = $executionID;
            $data->product = (int)$story->product;
            $data->branch  = $story->branch;
            $data->story   = $storyID;
            $data->version = $versions[$storyID];
            $data->order   = ++ $lastOrder;
            $this->dao->replace(TABLE_PROJECTSTORY)->data($data)->exec();

            $this->story->setStage($storyID);
            $this->linkCases($executionID, $data->product, $storyID);

            $action = $execution->type == 'project' ? 'linked2project' : 'linked2execution';
            if($action == 'linked2execution' and $execution->type == 'kanban') $action = 'linked2kanban';
            if($execution->multiple or $execution->type == 'project') $this->action->create('story', $storyID, $action, '', $executionID);
        }

        if(!isset($output['laneID']) or !isset($output['columnID'])) $this->kanban->updateLane($executionID);
        return true;
    }

    /**
     * 执行批量关联用例。
     * Batch link cases.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function linkCases(int $executionID, int $productID, int $storyID): void
    {
        $this->loadModel('action');
        $linkedCases   = $this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('case', 'order');
        $lastCaseOrder = empty($linkedCases) ? 0 : (int)reset($linkedCases);
        $cases         = $this->dao->select('id, version')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchPairs();
        $execution     = $this->getByID($executionID);
        foreach($cases as $caseID => $version)
        {
            if(isset($linkedCases[$caseID])) continue;

            $object = new stdclass();
            $object->project = $executionID;
            $object->product = $productID;
            $object->case    = $caseID;
            $object->version = $version;
            $object->order   = ++ $lastCaseOrder;
            $this->dao->insert(TABLE_PROJECTCASE)->data($object)->exec();

            $action = $execution->type == 'project' ? 'linked2project' : 'linked2execution';
            if($execution->multiple || $execution->type == 'project') $this->action->create('case', $caseID, $action, '', $executionID);
        }
    }

    /**
     * 批量关联需求。
     * Link all stories by execution.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function linkStories(int $executionID): bool
    {
        $stories   = array();
        $plans     = $this->dao->select('product, plan')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchPairs('product', 'plan');
        $execution = $this->fetchByID($executionID);
        $project   = $this->fetchByID($execution->project);
        $this->session->set('project', $project->id);

        $this->loadModel('story');
        $executionProducts = $this->loadModel('project')->getBranchesByProject($executionID);
        foreach($plans as $productID => $planIdList)
        {
            if(empty($planIdList)) continue;

            $planIdList        = array_filter(explode(',', $planIdList));
            $executionBranches = zget($executionProducts, $productID, array());
            foreach($planIdList as $planID)
            {
                $planStories = $this->story->getPlanStories((int)$planID);
                if(empty($planStories)) continue;

                foreach($planStories as $id => $story)
                {
                    if($story->status != 'active' || (!empty($story->branch) && !empty($executionBranches) && !isset($executionBranches[$story->branch]))) unset($planStories[$id]);
                    if(strpos($project->storyType, $story->type) === false) unset($planStories[$id]);
                    if(!in_array($execution->attribute, array('mix', 'request', 'design')) && $story->type != 'story' && $execution->multiple) unset($planStories[$id]);
                }
                $stories = array_merge($stories, array_keys($planStories));
            }
        }

        $this->linkStory($project->id, $stories);
        $this->linkStory($executionID, $stories);

        return true;
    }

    /**
     * 移除需求。
     * Unlink a story.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @param  int    $laneID
     * @param  int    $columnID
     * @access public
     * @return array|bool
     */
    public function unlinkStory(int $executionID, int $storyID, int $laneID = 0, int $columnID = 0): array|bool
    {
        $storyFrozen = $this->dao->findById($storyID)->from(TABLE_STORY)->fetch('frozen');
        if(!empty($storyFrozen))
        {
            $this->app->loadLang('story');
            dao::$errors[] = sprintf($this->lang->story->frozenTip, $this->lang->story->unlink);
            return false;
        }

        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        if($execution->type == 'project')
        {
            $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->fetchAll('id');
            $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
            if(!empty($executionStories)) return dao::$errors[] = $this->lang->execution->notAllowedUnlinkStory;
        }
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('story')->eq($storyID)->exec();

        /* Resolve TABLE_KANBANCELL's field cards. */
        if($execution->type == 'kanban')
        {
            $cell = $this->dao->select('*')->from(TABLE_KANBANCELL)
                ->where('kanban')->eq($executionID)
                ->andWhere('`column`')->eq($columnID)
                ->andWhere('lane')->eq($laneID)
                ->fetch();
            if($cell)
            {
                /* Resolve signal ','. */
                $cell->cards = str_replace(",$storyID,", ',', $cell->cards);
                if(strlen($cell->cards) == 1) $cell->cards = '';
                $this->dao->update(TABLE_KANBANCELL)->data($cell)
                    ->where('kanban')->eq($executionID)
                    ->andWhere('`column`')->eq($columnID)
                    ->andWhere('lane')->eq($laneID)
                    ->exec();
            }
        }

        /* 因为有需求被移除了，所以需要将剩余的需求重新排序。*/
        $order     = 1;
        $relations = $this->dao->select('*')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order')->fetchAll();
        foreach($relations as $relation)
        {
            if($relation->order != $order) $this->dao->update(TABLE_PROJECTSTORY)->set('`order`')->eq($order)->where('project')->eq($executionID)->andWhere('story')->eq($relation->story)->exec();
            $order++;
        }

        return $this->afterUnlinkStory($execution, $storyID);
    }

    /**
     * 取消关联需求后的其他数据处理。
     * Other data process after unlink story.
     *
     * @param  object $execution
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function afterUnlinkStory(object $execution, int $storyID): bool
    {
        $this->loadModel('story')->setStage($storyID);
        $this->unlinkCases($execution->id, $storyID);
        $actionType = $execution->type == 'project' ? 'unlinkedFromProject' : 'unlinkedFromExecution';
        if($execution->multiple || $execution->type == 'project') $this->loadModel('action')->create('story', $storyID, $actionType, '', $execution->id);

        /* 从迭代中移除该需求，并记录日志。*/
        if(empty($execution->multiple) && $execution->type != 'project')
        {
            $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($execution->project)->andWhere('story')->eq($storyID)->exec();
            $this->loadModel('action')->create('story', $storyID, 'unlinkedFromProject', '', $execution->project);
        }

        /* 取消该需求关联的所有任务。*/
        $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('story')->eq($storyID)->andWhere('execution')->eq($execution->id)->andWhere('status')->in('wait,doing')->fetchAll();
        $now   = helper::now();
        foreach($tasks as $task)
        {
            if(empty($task)) continue;

            $cancelTask = new stdclass();
            $cancelTask->id           = $task->id;
            $cancelTask->status       = 'cancel';
            $cancelTask->assignedTo   = $task->openedBy;
            $cancelTask->assignedDate = $now;
            $cancelTask->canceledBy   = $task->lastEditedBy = $this->app->user->account;
            $cancelTask->canceledDate = $task->lastEditedDate = $now;
            $cancelTask->finishedBy   = '';
            $cancelTask->finishedDate = null;
            $cancelTask->parent       = $task->parent;

            $this->loadModel('task')->cancel($task, $cancelTask);
        }
        return !dao::isError();
    }

    /**
     * 解除用例跟执行的关联关系。
     * Unlink cases.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkCases(int $executionID, int $storyID): void
    {
        $this->loadModel('action');
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        $cases     = $this->dao->select('id')->from(TABLE_CASE)->where('story')->eq($storyID)->fetchAll('id');
        foreach($cases as $caseID => $case)
        {
            $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->andWhere('`case`')->eq($caseID)->exec();
            $action = $execution->type == 'project' ? 'unlinkedfromproject' : 'unlinkedfromexecution';
            if($execution->multiple || $execution->type == 'project') $this->action->create('case', $caseID, $action, '', $executionID);

            /* Sync unlink case in no multiple execution. */
            if(empty($execution->multiple) && $execution->type != 'project')
            {
                $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($execution->project)->andWhere('`case`')->eq($caseID)->exec();
                $this->action->create('case', $caseID, 'unlinkedfromproject', '', $execution->project);
            }
        }

        $order = 1;
        $cases = $this->dao->select('*')->from(TABLE_PROJECTCASE)->where('project')->eq($executionID)->orderBy('order')->fetchAll();
        foreach($cases as $case)
        {
            if($case->order != $order) $this->dao->update(TABLE_PROJECTCASE)->set('`order`')->eq($order)->where('project')->eq($executionID)->andWhere('`case`')->eq($case->case)->exec();
            $order ++;
        }
    }

    /**
     * 获取执行团队成员列表。
     * Get team members.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function getTeamMembers(int $executionID): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getTeamMembers();

        return $this->dao->select("t1.*, t1.hours * t1.days AS totalHours, t2.id as userID, if(t2.deleted='0', t2.realname, t1.account) as realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->eq((int)$executionID)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($this->config->vision)->andWhere("CONCAT(',', t2.visions, ',')")->like("%,{$this->config->vision},%")->fi()
            ->fetchAll('account');
    }

    /**
     * 获取给定执行的团队成员信息。
     * Get team members information for the execution id list.
     *
     * @param  array  $executionIdList
     * @access public
     * @return array
     */
    public function getMembersByIdList(array $executionIdList): array
    {
        return $this->dao->select("t1.root, t1.account, t2.realname")->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.root')->in($executionIdList)
            ->andWhere('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchGroup('root', 'account');
    }

    /**
     * 获取可导入的执行成员。
     * Get members of a execution who can be imported.
     *
     * @param  int    $executionID
     * @param  array  $currentMembers
     * @access public
     * @return array
     */
    public function getMembers2Import(int $executionID, array $currentMembers): array
    {
        if($executionID == 0) return array();

        return $this->dao->select('account, role, hours')
            ->from(TABLE_TEAM)
            ->where('root')->eq($executionID)
            ->andWhere('type')->in('project,execution')
            ->andWhere('account')->notIN($currentMembers)
            ->fetchAll('account');
    }

    /**
     * 获取可以复制团队的项目、执行列表。
     * Get projects and executions that copy the team.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getCanCopyObjects(int $projectID = 0): array
    {
        if(empty($projectID)) return array();

        $objectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)
            ->where('deleted')->eq(0)
            ->andWhere('project', true)->eq($projectID)
            ->andWhere('type')->ne('project')
            ->markRight(1)
            ->orWhere('id')->eq($projectID)
            ->orderBy('type_asc,openedDate_desc')
            ->limit('11')
            ->fetchPairs();

        $countPairs = $this->dao->select('root, COUNT(1) AS count')->from(TABLE_TEAM)
            ->where('( type')->eq('project')
            ->andWhere('root')->eq($projectID)
            ->markRight(1)
            ->orWhere('( type')->eq('execution')
            ->andWhere('root')->in(array_keys($objectPairs))
            ->markRight(1)
            ->groupBy('root')
            ->fetchPairs('root');

        foreach($objectPairs as $objectID => $objectName)
        {
            $memberCount = zget($countPairs, $objectID, 0);
            $countTip    = $memberCount > 1 ? str_replace('member', 'members', $this->lang->execution->countTip) : $this->lang->execution->countTip;
            $objectPairs[$objectID] = $objectName . sprintf($countTip, $memberCount);
        }

        return $objectPairs;
    }

    /**
     * 维护执行团队成员。
     * Manage team members.
     *
     * @param  object $execution
     * @param  array  $members
     * @access public
     * @return void
     */
    public function manageMembers(object $execution, array $members)
    {
        $oldJoin = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($execution->id)->andWhere('type')->eq('execution')->fetchPairs();
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($execution->id)->andWhere('type')->eq('execution')->exec();

        $accountList     = array();
        $executionMember = array();
        foreach($members as $member)
        {
            if(in_array($member->account, $accountList)) continue;

            $member->root = isset($member->root) ? $member->root : $execution->id;
            $member->type = isset($member->type) ? $member->type : 'execution';
            $member->join = isset($oldJoin[$member->account]) ? $oldJoin[$member->account] : helper::today();
            $executionMember[$member->account] = $member;
            $accountList[] = $member->account;

            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccountList     = array_keys($oldJoin);
        $addedAccountList   = array_diff($accountList, $oldAccountList);
        $removedAccountList = array_diff($oldAccountList, $accountList);
        $changedAccountList = array_merge($addedAccountList, $removedAccountList);
        $changedAccountList = array_unique($changedAccountList);

        /* Log history. */
        $actionID = $this->loadModel('action')->create('execution', $execution->id, 'managedTeam');

        if(empty($addedAccountList) && empty($removedAccountList)) return;

        $users              = $this->loadModel('user')->getPairs('noletter');
        $addedAccountList   = array_map(function($account) use ($users) { return zget($users, $account); }, $addedAccountList);
        $removedAccountList = array_map(function($account) use ($users) { return zget($users, $account); }, $removedAccountList);

        if(!empty($addedAccountList)) $changes[] = array('field' => 'addDiff', 'old' => '', 'new' => '', 'diff' => implode(',', $addedAccountList));
        if(!empty($removedAccountList)) $changes[] = array('field' => 'removeDiff', 'old' => '', 'new' => '', 'diff' => implode(',', $removedAccountList));
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);

        /* Add the execution team members to the project. */
        if($execution->project) $this->addProjectMembers($execution->project, $executionMember);

        /* Add the execution team members to parent executions. */
        foreach(explode(',', $execution->path) as $parentID)
        {
            if(empty($parentID) || $parentID == $execution->project) continue;
            $this->executionTao->addExecutionMembers((int)$parentID, array_keys($executionMember));
        }

        if($execution->acl != 'open') $this->updateUserView($execution->id, 'sprint', $changedAccountList);
    }

    /**
     * 添加项目团队成员。
     * Add the execution team members to the project.
     *
     * @param  int    $projectID
     * @param  array  $members
     * @access public
     * @return void
     */
    public function addProjectMembers(int $projectID = 0, array $members = array())
    {
        if(empty($members)) return;

        $projectType = 'project';
        $oldJoin     = $this->dao->select('`account`, `join`')->from(TABLE_TEAM)->where('root')->eq($projectID)->andWhere('type')->eq($projectType)->fetchPairs();

        $accountList = array();
        foreach($members as $member)
        {
            if(isset($oldJoin[$member->account])) continue;

            $accountList[]   = $member->account;
            $member->root = $projectID;
            $member->type = $projectType;
            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccountList     = array_keys($oldJoin);
        $changedAccountList = array_diff($accountList, $oldAccountList);
        $changedAccountList = array_merge($changedAccountList, array_diff($oldAccountList, $accountList));
        $changedAccountList = array_unique($changedAccountList);

        if($changedAccountList)
        {
            $this->loadModel('user')->updateUserView(array($projectID), $projectType, $changedAccountList);
            $linkedProducts = $this->dao->select("t2.id")->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t1.project')->eq($projectID)
                ->andWhere('t2.vision')->eq($this->config->vision)
                ->fetchPairs();

            if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccountList);
        }

        if(empty($accountList) || ($this->app->rawModule == 'project' && $this->app->rawMethod == 'create')) return;

        /* Log history. */
        $users       = $this->loadModel('user')->getPairs('noletter');
        $accountList = array_map(function($account) use ($users) { return zget($users, $account); }, $accountList);

        $actionID = $this->loadModel('action')->create('project', $projectID, 'syncExecutionTeam');

        $changes = array();
        if(!empty($accountList)) $changes[] = array('field' => 'addDiff', 'old' => '', 'new' => '', 'diff' => implode(',', $accountList));
        if(!empty($changes)) $this->action->logHistory($actionID, $changes);
    }

    /**
     * 移除执行团队成员。
     * Remove the user from the execution team members.
     *
     * @param  int    $executionID
     * @param  string $account
     * @access public
     * @return void
     */
    public function unlinkMember(int $executionID, string $account)
    {
        /* Remove the user from the execution team members. */
        $execution = $this->getByID($executionID);
        $type   = strpos(',stage,sprint,kanban,', ",$execution->type,") !== false ? 'execution' : $execution->type;
        $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($executionID)->andWhere('type')->eq($type)->andWhere('account')->eq($account)->exec();

        /* Update the user's execution permission. */
        $this->updateUserView($executionID, 'sprint', array($account));

        $linkedProducts = $this->loadModel('product')->getProductPairsByProject($execution->id);
        if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', array($account));
    }

    /**
     * 计算燃尽图数据。
     * Compute burn of a execution.
     *
     * @param  int|string|array $executionID
     * @access public
     * @return array
     */
    public function computeBurn(int|string|array $executionID = ''): array
    {
        if(is_int($executionID)) $executionID = (string)$executionID;
        $executions = $this->dao->select('id, code')->from(TABLE_EXECUTION)
            ->where('type')->in('sprint,stage')
            ->andWhere('lifetime')->ne('ops')
            ->andWhere('status')->notin('done,closed,suspended')
            ->beginIF($executionID)->andWhere('id')->in($executionID)->fi()
            ->fetchPairs();
        if(!$executions) return array();

        /* Get burn related data. */
        list($burns, $closedLefts, $finishedEstimates, $storyPoints) = $this->executionTao->fetchBurnData(array_keys($executions));

        /* Update today's data of burn. */
        foreach($burns as $executionID => $burn)
        {
            if(isset($closedLefts[$executionID]))
            {
                $closedLeft  = $closedLefts[$executionID];
                $burn->left -= (int)$closedLeft->left;
            }

            if(isset($finishedEstimates[$executionID]))
            {
                $finishedEstimate = $finishedEstimates[$executionID];
                $burn->estimate  -= (int)$finishedEstimate->estimate;
            }

            $burn->product = 0;
            $burn->task    = 0;
            if(isset($storyPoints[$executionID])) $burn->storyPoint = $storyPoints[$executionID]->storyPoint;

            $this->dao->replace(TABLE_BURN)->data($burn)->exec();
            $burn->executionName = $executions[$burn->execution];
        }

        return $burns;
    }

    /**
     * 计算累计流图的数据。
     * Compute cfd of a execution.
     *
     * @param  int    $executionID
     * @access public
     * @return array
     */
    public function computeCFD(int $executionID = 0)
    {
        $executions = $this->dao->select('id, code')->from(TABLE_EXECUTION)
            ->where('type')->eq('kanban')
            ->andWhere('status')->notin('done,closed,suspended')
            ->beginIF($executionID)->andWhere('id')->in($executionID)->fi()
            ->fetchPairs();
        if(!$executions) return array();

        /* Update today's data of cfd. */
        $cells = $this->dao->select("t1.id, t1.kanban as execution, t1.`column`, t1.type, t1.cards, t1.lane, t2.name, t2.parent")
            ->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t1.kanban')->in(array_keys($executions))
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t1.type')->in('story,bug,task')
            ->orderBy('t2.id asc')
            ->fetchAll('id');

        /* Group by execution/type/name/lane/column. */
        $columnGroup = array();
        $parentNames = array();
        foreach($cells as $column)
        {
            if($column->parent == '-1')
            {
                $parentNames[$column->column] = $column->name;
                continue;
            }

            $column->name = isset($parentNames[$column->parent]) ? $parentNames[$column->parent] . "($column->name)" : $column->name;
            $columnGroup[$column->execution][$column->type][$column->name][$column->lane][$column->column] = $column;
        }

        foreach($columnGroup as $executionID => $executionGroup)
        {
            foreach($executionGroup as $type => $columns)
            {
                foreach($columns as $colName => $laneGroup)
                {
                    $this->executionTao->updateTodayCFDData($executionID, $type, $colName, $laneGroup);
                }
            }
        }
    }

    /**
     * 查看指定的执行日期是否有数据，且没有更新最新日期的数据。
     * Check whether there is data on the specified date of execution, and there is no data with the latest date added.
     *
     * @param  int    $executionID
     * @param  string $date
     * @access public
     * @return void
     */
    public function updateCFDData(int $executionID, string $date)
    {
        $today = helper::today();
        if($date >= $today) return;

        $checkData = $this->dao->select("date, `count` AS value, `name`")->from(TABLE_CFD)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('date')->eq($date)
            ->orderBy('date DESC, id asc')->fetchGroup('name', 'date');

        if(!$checkData)
        {
            $closetoDate = $this->dao->select("max(date) as date")->from(TABLE_CFD)->where('execution')->eq((int)$executionID)->andWhere('date')->lt($date)->fetch('date');
            if($closetoDate)
            {
                $copyData = $this->dao->select("*")->from(TABLE_CFD)
                    ->where('execution')->eq((int)$executionID)
                    ->andWhere('date')->eq($closetoDate)
                    ->fetchAll();
                foreach($copyData as $data)
                {
                    unset($data->id);
                    $data->date = $date;
                    $this->dao->replace(TABLE_CFD)->data($data)->exec();
                }
            }
        }
    }

    /**
     * 修改燃尽图首天工时。
     * Fix burn for first day.
     *
     * @param  object $burn
     * @access public
     * @return bool
     */
    public function fixFirst(object $burn): bool
    {
        $this->dao->replace(TABLE_BURN)->data($burn)->exec();
        return !dao::isError();
    }

    /**
     * 获取累计流图的开始、结束日期。
     * Get begin and end for CFD.
     *
     * @param  object $execution
     * @access public
     * @return array
     */
    public function getBeginEnd4CFD(object $execution): array
    {
        $end   = (!helper::isZeroDate($execution->closedDate) && date('Y-m-d', strtotime($execution->closedDate)) < helper::today()) ? date('Y-m-d', strtotime($execution->closedDate)) : helper::today();
        $begin = (!helper::isZeroDate($execution->openedDate) && date('Y-m-d', strtotime($execution->openedDate)) > date('Y-m-d', strtotime('-13 days', strtotime($end)))) ? date('Y-m-d', strtotime($execution->openedDate)) : date('Y-m-d', strtotime('-13 days', strtotime($end)));
        return array($begin, $end);
    }

    /**
     * 获取燃尽图时间点数据。
     * Get burn chart flot data.
     *
     * @param  int    $executionID
     * @param  string $burnBy      left|estimate|storyPoint
     * @param  bool   $showDelay
     * @param  array  $dateList
     * @access public
     * @return array|null
     */
    public function getBurnDataFlot(int $executionID = 0, string $burnBy = '', bool $showDelay = false, array $dateList = array()): array|null
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionBurnData($dateList);

        /* Get execution and burn counts. */
        $execution = $this->getByID($executionID);

        /* If the burnCounts > $itemCounts, get the latest $itemCounts records. */
        $sets = $this->dao->select("date AS name, `$burnBy` AS value, `$burnBy`")->from(TABLE_BURN)->where('execution')->eq((int)$executionID)->andWhere('task')->eq(0)->orderBy('date DESC')->fetchAll('name');

        $burnData = array();
        foreach($sets as $date => $set)
        {
            if($date < $execution->begin) continue;
            if(!$showDelay && $date > $execution->end) $set->value = 'null';
            if($showDelay  && $date < $execution->end) $set->value = 'null';

            $burnData[$date] = $set;
        }

        foreach($dateList as $date)
        {
            if(isset($burnData[$date])) continue;
            if(($showDelay && $date < $execution->end) || (!$showDelay && $date > $execution->end))
            {
                $set = new stdClass();
                $set->name    = $date;
                $set->value   = 'null';
                $set->$burnBy = 0;

                $burnData[$date] = $set;
            }
        }

        krsort($burnData);
        $burnData = array_reverse($burnData);

        return $burnData;
    }

    /**
     * 获取执行的燃尽图数据。
     * Get execution burn data.
     *
     * @param  array  $executions
     * @access public
     * @return array
     */
    public function getBurnData(array $executions): array
    {
        if(empty($executions)) return array();

        /* Get burndown charts datas. */
        $burnList = $this->dao->select('execution, date AS name, `left` AS value')
            ->from(TABLE_BURN)
            ->where('execution')->in(array_keys($executions))
            ->andWhere('task')->eq(0)
            ->orderBy('date desc')
            ->fetchGroup('execution', 'name');

        foreach($burnList as $executionID => $executionBurnList)
        {
            /* If executionBurnList > $itemCounts, split it, else call processBurnData() to pad burnList. */
            $begin             = helper::isZeroDate($executions[$executionID]->begin) ? $executions[$executionID]->openedDate : $executions[$executionID]->begin;
            $end               = $executions[$executionID]->end;

            /* Unset burn information that is greater than the execution end date. */
            foreach($executionBurnList as $date => $burnInfo)
            {
                if($date > $end) unset($executionBurnList[$date]);
            }

            $executionBurnList = $this->processBurnData($executionBurnList, $this->config->execution->defaultBurnPeriod, $begin, $end);

            /* Shorter names. */
            foreach($executionBurnList as $executionBurn)
            {
                $executionBurn->name = substr($executionBurn->name, 5);
                unset($executionBurn->execution);
            }

            ksort($executionBurnList);
            $burnList[$executionID] = $executionBurnList;
        }

        return $burnList;
    }

    /**
     * 根据传入的条件筛选日期列表。
     * Process burndown datas when the sets is smaller than the itemCounts.
     *
     * @param  array   $dateList
     * @param  int     $itemCounts
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $mode        noempty
     * @access public
     * @return array
     */
    public function processBurnData(array $dateList, int $itemCounts, string $begin, string $end, string $mode = 'noempty'): array
    {
        /* Get the date interval if the $end is not empty, otherwise get the $end. */
        if(!helper::isZeroDate($end))
        {
            $period = helper::diffDate($end, $begin) + 1;
            $counts = $period > $itemCounts ? $itemCounts : $period;
        }
        else
        {
            $counts = $period = $itemCounts;
            $end    = date(DT_DATE1, strtotime("+$counts days", strtotime($begin)));
        }

        $current  = $begin;
        $today    = helper::today();
        $endTime  = strtotime($end);
        $preValue = 0;
        $todayTag = 0;

        /* Removes date that are not in the current date range. */
        foreach($dateList as $date => $value)
        {
            if($begin > $date) unset($dateList[$date]);
        }

        /* Update date that are not in the date list and are in the date range. */
        for($i = 0; $i < $period; $i++)
        {
            $currentTime = strtotime($current);
            if($currentTime > $endTime) break;
            if($currentTime > time() && !$todayTag) $todayTag = $i + 1;

            if(isset($dateList[$current])) $preValue = $dateList[$current]->value;
            if(!isset($dateList[$current]) && $mode == 'noempty')
            {
                $dateList[$current] = new stdclass();
                $dateList[$current]->name  = $current;
                $dateList[$current]->value = helper::diffDate($current, $today) < 0 ? $preValue : 'null';
            }

            $nextDay = date(DT_DATE1, $currentTime + 24 * 3600);
            $current = $nextDay;
        }
        ksort($dateList);

        if(count($dateList) <= $counts) return $dateList;
        if($endTime <= time()) return array_slice($dateList, -$counts, $counts);
        if($todayTag <= $counts) return array_slice($dateList, 0, $counts);
        if($todayTag > $counts) return array_slice($dateList, $todayTag - $counts, $counts);
    }

    /**
     * 构造累计流图数据。
     * Build CFD data.
     *
     * @param  int    $executionID
     * @param  array  $dateList
     * @param  string $type        story|task|bug
     * @access public
     * @return array
     */
    public function buildCFDData(int $executionID, array $dateList, string $type): array
    {
        $nameGroup = $this->getCFDData($executionID, $dateList, $type);

        if(empty($nameGroup)) return array();

        $chartData['labels'] = $this->loadModel('report')->convertFormat($dateList, DT_DATE5);
        $chartData['line']   = array();

        foreach($nameGroup as $name => $value)
        {
            $chartData['line'][$name] = $this->report->createSingleJSON($value, $dateList);
        }

        return $chartData;
    }

    /**
     * 获取累计流图的数据。
     * Get CFD data to display.
     *
     * @param  int    $executionID
     * @param  array  $dateList
     * @param  string $type        story|task|bug
     * @access public
     * @return array
     */
    public function getCFDData(int $executionID = 0, array $dateList = array(), string $type = 'story'): array
    {
        $execution = $this->getByID($executionID);
        $nameGroup = $this->dao->select("date, `count` AS value, `name`")->from(TABLE_CFD)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->eq($type)
            ->andWhere('date')->in($dateList)
            ->orderBy('date DESC, id asc')
            ->fetchGroup('name', 'date');

        $data = array();
        foreach($nameGroup as $name => $dateList)
        {
            foreach($dateList as $date => $value)
            {
                if($date < $execution->begin) continue;

                $data[$name][$date] = $value;
            }
        }

        return $data;
    }

    /**
     * 通过搜索条件获取任务列表信息。
     * Get tasks by search.
     *
     * @param  string $condition
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $queryKey
     * @access public
     * @return array
     */
    public function getSearchTasks(string $condition, string $orderBy, ?object $pager = null, string $queryKey = 'task'): array
    {
        /* 按指派人搜索的时候，可以搜索到参与的多人任务。 */
        if(strpos($condition, '`assignedTo`') !== false)
        {
            preg_match_all("/`assignedTo`\s+(([^']*) ('([^']*)'))/", $condition, $matches);
            $condition = preg_replace('/`(\w+)`/', 't1.`$1`', $condition);

            foreach($matches[0] as $matchIndex => $match)
            {
                $subQuery = $this->dao->select('1')->from(TABLE_TASKTEAM)
                    ->where('task = t1.id')
                    ->andWhere('account' . $matches[1][$matchIndex])
                    ->get();

                $condition = str_replace(
                    "t1.{$match}",
                    "(t1.{$match} OR EXISTS (" . $subQuery . "))",
                    $condition
                );
            }

            $this->session->set("{$queryKey}QueryCondition", $condition, $this->app->tab);
        }

        $orderBy = array_map(function($value)
        {
            return strpos($value, '.') === false ? 't1.' . $value : $value;
        }, explode(',', $orderBy));
        $orderBy = str_replace('t1.storyTitle', 't2.title', implode(',', $orderBy));
        $orderBy = str_replace(array('t1.pri_', 't1.`pri'), array('priOrder_', '`priOrder_'), $orderBy);

        if(strpos($condition, 't1.') === false)
        {
            $condition = preg_replace('/`(\w+)`/', 't1.`$1`', $condition);
        }
        $condition = str_replace("AND deleted = '0'", '', $condition);

        $tasks = $this->dao->select('DISTINCT t1.*,
            t2.id AS storyID,
            t2.title AS storyTitle,
            t2.product,
            t2.branch,
            t2.version AS latestStoryVersion,
            t2.status AS storyStatus,
            t3.realname AS assignedToRealName,
            IF(t1.`pri` = 0, 999, t1.`pri`) as priOrder')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.deleted')->eq(0)
            ->andWhere($condition)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'task', true);

        return $this->processTasks($tasks);
    }

    /**
     * 批量处理任务，团队、父子层级、泳道等信息。
     * Batch process tasks, teams, parent-child, lanes, etc.
     *
     * @param  array  $tasks
     * @access public
     * @return array
     */
    public function processTasks(array $tasks): array
    {
        if(empty($tasks)) return array();

        $taskTeam = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->in(array_keys($tasks))->fetchGroup('task');
        if(!empty($taskTeam))
        {
            foreach($taskTeam as $taskID => $team) $tasks[$taskID]->team = $team;
        }

        if($this->config->vision == 'lite') $tasks = $this->loadModel('task')->appendLane($tasks);
        return $this->loadModel('task')->processTasks($tasks);
    }

    /**
     * 获取任务列表的统计信息。
     * Get the summary of execution.
     *
     * @param  array  $tasks
     * @access public
     * @return string
     */
    public function summary(array $tasks): string
    {
        $taskSum = 0;
        $totalEstimate = $totalConsumed = $totalLeft = 0.0;

        $summations = array();
        $this->app->loadLang('task');

        /* 当前只需要显示wait 和 doing 状态，但是从代码分析将来可能需要统计其他状态的，所以取全部状态。 */
        foreach($this->lang->task->statusList as $statusCode => $statusName) $summations[$statusCode] = 0;

        foreach($tasks as $task)
        {
            if($task->isParent == '0')
            {
                $totalEstimate += $task->estimate;
                $totalConsumed += $task->consumed;

                if($task->status != 'cancel' and $task->status != 'closed') $totalLeft += (float)$task->left;
            }

            if(isset($summations[$task->status])) $summations[$task->status] ++;
            if(isset($task->children))
            {
                foreach($task->children as $child)
                {
                    if(isset($summations[$child->status])) $summations[$child->status] ++;
                    $taskSum ++;
                }
            }
            $taskSum ++;
        }

        return sprintf($this->lang->execution->taskSummary, $taskSum, $summations['wait'], $summations['doing'], round($totalEstimate, 1), round($totalConsumed, 1), round($totalLeft, 1));
    }

    /**
     * 判断操作按钮是否可以点击。
     * Judge an action is clickable or not.
     *
     * @param  object $execution
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $execution, string $action): bool
    {
        if(!empty($execution->frozen) && in_array($action, array('edit', 'createChildStage', 'delete', 'putoff'))) return false;
        if($action == 'createChildStage') return commonModel::hasPriv('programplan', 'create') && $execution->type == 'stage';
        if($action == 'createTask')  return commonModel::hasPriv('task', 'create') && commonModel::hasPriv('execution', 'create') && empty($execution->isParent);
        if(!commonModel::hasPriv('execution', $action)) return false;

        $action = strtolower($action);
        if($action == 'start')    return $execution->status == 'wait';
        if($action == 'close')    return $execution->status != 'closed' && (empty($execution->isParent) || !empty($execution->parentCanClose));
        if($action == 'suspend')  return $execution->status == 'wait' || $execution->status == 'doing';
        if($action == 'putoff')   return $execution->status == 'wait' || $execution->status == 'doing';
        if($action == 'activate') return $execution->status == 'suspended' || $execution->status == 'closed';
        if($action == 'delete')   return empty($execution->isParent);

        return true;
    }

    /**
     * 获取日期列表数据。
     * Get date list data.
     *
     * @param  string     $begin
     * @param  string     $end
     * @param  string     $type noweekend|withweekend
     * @param  int|string $interval
     * @param  string     $format
     * @param  string     $executionDeadline
     * @access public
     * @return array
     */
    public function getDateList(string $begin, string $end, string $type, int|string $interval = 0, string $format = 'm/d/Y', string $executionDeadline = ''): array
    {
        $this->app->loadClass('date', true);
        $dateList = date::getDateList($begin, $end, $format, $type, $this->config->execution->weekend);

        if(!$interval) $interval = floor(count($dateList) / $this->config->execution->maxBurnDay);

        /* Remove date by interval. */
        if($interval)
        {
            $spaces   = (int)$interval;
            $counter  = $spaces;
            foreach($dateList as $i => $date)
            {
                $counter ++;
                if($date == $executionDeadline) continue;
                if($counter <= $spaces)
                {
                    unset($dateList[$i]);
                    continue;
                }

                $counter = 0;
            }
        }

        return array(array_values($dateList), $interval);
    }

    /**
     * 获取当前执行下任务的总预计工时。
     * Get the total estimate for the current execution's tasks.
     *
     * @param  int    $executionID
     * @access public
     * @return float
     */
    public function getTotalEstimate(int $executionID): float
    {
        $estimate = $this->dao->select('SUM(estimate) as estimate')->from(TABLE_TASK)->where('execution')->eq($executionID)->andWhere('deleted')->eq('0')->fetch('estimate');
        return round((float)$estimate);
    }

    /**
     * 修复执行的排序顺序。
     * Fix the sort order of execution.
     *
     * @access public
     * @return void
     */
    public function fixOrder(): void
    {
        $executions = $this->dao->select('id,`order`')->from(TABLE_EXECUTION)->orderBy('order')->fetchPairs('id', 'order');

        $i = 0;
        foreach($executions as $id => $order)
        {
            $i++;
            $newOrder = $i * 5;
            if($order == $newOrder) continue;
            $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($newOrder)->where('id')->eq($id)->exec();
        }
    }

    /**
     * 构造Bug的搜索表单。
     * Build bug search form.
     *
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  string $type
     * @access public
     * @return void
     */
    public function buildBugSearchForm(array $products, int $queryID, string $actionURL, string $type = 'execution')
    {
        $modules = array();
        $builds  = array('' => '', 'trunk' => $this->lang->trunk);
        foreach($products as $product)
        {
            $productModules = $this->loadModel('tree')->getOptionMenu($product->id, 'bug');
            foreach($productModules as $moduleID => $moduleName) $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;

            $productBuilds  = $this->loadModel('build')->getBuildPairs(array($product->id), 'all', 'noempty|notrunk|withbranch');
            foreach($productBuilds as $buildID => $buildName) $builds[$buildID] = ((count($products) >= 2 and $buildID) ? $product->name . '/' : '') . $buildName;
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $branchPairs  = array();
        $productType  = 'normal';
        $productPairs = array(0 => '');
        foreach($products as $product)
        {
            $productPairs[$product->id] = $product->name;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                if(isset($product->branches))
                {
                    foreach($product->branches as $branch)
                    {
                        if(isset($branchGroups[$product->id][$branch])) $branchPairs[$branch] = (count($products) > 1 ? $product->name . '/' : '') . $branchGroups[$product->id][$branch];
                    }
                }
                else
                {
                    $productBranches = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(0);
                    if(count($products) > 1)
                    {
                        foreach($productBranches as $branchID => $branchName) $productBranches[$branchID] = $product->name . '/' . $branchName;
                    }
                    $branchPairs += $productBranches;
                }
            }
        }

        $projects = $this->loadModel('project')->getPairsByProgram();

        $this->config->bug->search['module']    = $type == 'execution' ? 'executionBug' : 'projectBug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;

        $this->config->bug->search['params']['project']['values']       = $projects + array('all' => $this->lang->project->allProjects);
        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array_keys($products));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['openedBuild']['values']   = $builds;
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if(isset($this->config->bug->search['params']['product'])) $this->config->bug->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);

        if($type == 'execution' || !$this->session->multiple)
        {
            unset($this->config->bug->search['fields']['execution']);
            unset($this->config->bug->search['params']['execution']);
        }
        if($productType == 'normal')
        {
            unset($this->config->bug->search['fields']['branch']);
            unset($this->config->bug->search['params']['branch']);
        }
        else
        {
            $this->config->bug->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->bug->search['params']['branch']['values'] = $branchPairs;
        }
        $this->config->bug->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->bug->statusList);

        $this->loadModel('search')->setSearchParams($this->config->bug->search);
    }

    /**
     * 构造用例列表的搜索表单。
     * Build testcase search form.
     *
     * @param  array  $products
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function buildCaseSearchForm(array $products, int $queryID, string $actionURL, int $executionID)
    {
        $modules = array();
        foreach($products as $product)
        {
            $productModules = $this->loadModel('tree')->getOptionMenu($product->id, 'case');
            foreach($productModules as $moduleID => $moduleName) $modules[$moduleID] = ((count($products) >= 2 and $moduleID) ? $product->name : '') . $moduleName;
        }

        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));
        $branchPairs  = array();
        $productType  = 'normal';
        $productPairs = array(0 => '');
        foreach($products as $product)
        {
            $productPairs[$product->id] = $product->name;
            if($product->type != 'normal')
            {
                $productType = $product->type;
                if(isset($product->branches))
                {
                    foreach($product->branches as $branch)
                    {
                        if(isset($branchGroups[$product->id][$branch])) $branchPairs[$branch] = (count($products) > 1 ? $product->name . '/' : '') . $branchGroups[$product->id][$branch];
                    }
                }
                else
                {
                    $productBranches = isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(0);
                    if(count($products) > 1)
                    {
                        foreach($productBranches as $branchID => $branchName) $productBranches[$branchID] = $product->name . '/' . $branchName;
                    }
                    $branchPairs += $productBranches;
                }
            }
        }

        unset($this->config->testcase->search['fields']['execution']);
        unset($this->config->testcase->search['params']['execution']);

        $this->config->testcase->search['module']    = 'executionCase';
        $this->config->testcase->search['actionURL'] = $actionURL;
        $this->config->testcase->search['queryID']   = $queryID;

        $this->config->testcase->search['params']['module']['values'] = $modules;
        if(isset($this->config->testcase->search['params']['product'])) $this->config->testcase->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);

        if($productType == 'normal')
        {
            unset($this->config->testcase->search['fields']['branch']);
            unset($this->config->testcase->search['params']['branch']);
        }
        else
        {
            $this->config->testcase->search['fields']['branch']           = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productType]);
            $this->config->testcase->search['params']['branch']['values'] = $branchPairs;
        }
        $this->config->testcase->search['params']['status'] = array('operator' => '=', 'control' => 'select', 'values' => $this->lang->testcase->statusList);
        $this->config->testcase->search['params']['story']['values'] = $this->loadModel('story')->getExecutionStoryPairs($executionID);

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * 构建搜索任务的表单。
     * Build task search form.
     *
     * @param  int    $executionID
     * @param  int    $productID
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $actionURL
     * @param  bool   $cacheSearchFunc 是否缓存构造搜索参数的方法。默认缓存可以提高性能，构造搜索表单时再加载真实值。
     * @access public
     * @return void
     */
    public function buildTaskSearchForm(int $executionID, array $executions, int $queryID, string $actionURL, string $module = 'task', bool $cacheSearchFunc = true)
    {
        $searchConfig = $this->config->execution->search;
        if($cacheSearchFunc)
        {
            $this->cacheSearchFunc($module, __METHOD__, func_get_args());
            return $searchConfig;
        }

        $searchConfig['module']    = $module;
        $searchConfig['actionURL'] = $actionURL;
        $searchConfig['queryID']   = $queryID;

        $showAll = empty($executionID) && empty($executions);
        if($showAll)
        {
            $executions  = $this->getPairs(0, 'all', "nocode,noprefix,multiple");
            $executionID = empty($executions) ? 0 : current(array_keys($executions));
        }
        $execution = $this->getByID($executionID);

        $searchConfig['params']['story']['values'] = $this->loadModel('story')->getExecutionStoryPairs($executionID, 0, 'all', '', 'full', 'unclosed', 'story', false);

        if($module == 'task')
        {
            $searchConfig['onMenuBar'] = 'yes';
            if(!$execution->multiple) unset($searchConfig['fields']['execution']);
        }
        elseif($module == 'projectTask')
        {
            unset($searchConfig['fields']['project']);
            unset($searchConfig['fields']['module']);
        }

        if(isset($execution->type) && $execution->type == 'project')
        {
            unset($searchConfig['fields']['project']);
            if(isset($searchConfig['fields']['execution'])) $searchConfig['params']['execution']['values'] = array('' => '') + $executions;
        }
        else
        {
            if(isset($searchConfig['fields']['execution'])) $searchConfig['params']['execution']['values'] = $showAll ? $executions : array(''=>'', $executionID => zget($executions, $executionID, ''), 'all' => $this->lang->execution->allExecutions);
        }

        if(isset($searchConfig['fields']['project']))
        {
            $projects = $this->loadModel('project')->getPairsByProgram();
            $searchConfig['params']['project']['values'] = $projects + array('all' => $this->lang->project->allProjects);
        }

        if(isset($searchConfig['fields']['module']))
        {
            $showAllModule = $this->config->execution->task->allModule ?? '';
            $searchConfig['params']['module']['values'] = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, $showAllModule ? 'allModule' : '');
        }

        $this->loadModel('search')->setSearchParams($searchConfig);

        return $searchConfig;
    }

    /**
     * 获取看板的任务卡片数据。
     * Get the Kanban task card data.
     *
     * @param  int         $executionID
     * @param  string      $orderBy
     * @param  object|null $pager
     * @param  array       $excludeTasks
     * @access public
     * @return array
     */
    public function getKanbanTasks(int $executionID, string $orderBy = 'status_asc, id_desc', array $excludeTasks = array(), ?object $pager = null): array
    {
        $excludeTasks = array_filter($excludeTasks);
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.execution')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!$this->cookie->showParent)->andWhere('t1.isParent')->ne('1')->fi()
            ->beginIF($excludeTasks)->andWhere('t1.id')->notIN($excludeTasks)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if($tasks) return $this->loadModel('task')->processTasks($tasks);
        return array();
    }

    /**
     * 获取需求看板视图的分组数据。
     * Get group data for stories in Kanban view.
     *
     * @param  array  $stories
     * @param  array  $tasks
     * @param  array  $bugs
     * @access public
     * @return array
     */
    public function getKanbanGroupData(array $stories, array $tasks = array(), array $bugs = array(), string $type = 'story'): array
    {
        $kanbanGroup = array();
        if($type == 'story') $kanbanGroup = $stories;

        foreach($tasks as $task)
        {
            $groupKey = $type == 'story' ? $task->storyID : $task->$type;

            $status = $task->status;
            if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
            {
                if($type == 'assignedTo' and $groupKey == 'closed')
                {
                    $closedTasks[$groupKey][] = $task;
                }
                else
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->tasks[$status][] = $task;
                }
            }
            else
            {
                $noKeyTasks[$status][] = $task;
            }
        }

        foreach($bugs as $bug)
        {
            $groupKey = $type == 'finishedBy' ? $bug->resolvedBy : $bug->$type;

            $status = $bug->status == 'active' ? 'wait' : $bug->status;
            if($status == 'resolved') $status = $bug->resolution == 'postponed' ? 'cancel' : 'done';

            if(!empty($groupKey) and (($type == 'story' and isset($stories[$groupKey])) or $type != 'story'))
            {
                if($type == 'assignedTo' and $groupKey == 'closed')
                {
                    $closedBugs[$groupKey][] = $bug;
                }
                else
                {
                    if(!isset($kanbanGroup[$groupKey])) $kanbanGroup[$groupKey] = new stdclass();
                    $kanbanGroup[$groupKey]->bugs[$status][] = $bug;
                }
            }
            else
            {
                $noKeyBugs[$status][] = $bug;
            }
        }

        $kanbanGroup['closed'] = new stdclass();
        if(isset($closedTasks)) $kanbanGroup['closed']->tasks = $closedTasks;
        if(isset($closedBugs))  $kanbanGroup['closed']->bugs  = $closedBugs;

        $kanbanGroup['nokey'] = new stdclass();
        if(isset($noKeyTasks)) $kanbanGroup['nokey']->tasks = $noKeyTasks;
        if(isset($noKeyBugs))  $kanbanGroup['nokey']->bugs = $noKeyBugs;

        return $kanbanGroup;
    }

    /**
     * 保存看板数据。
     * Save Kanban Data.
     *
     * @param  int    $executionID
     * @param  array  $kanbanDataList
     * @access public
     * @return void
     */
    public function saveKanbanData(int $executionID, array $kanbanDataList)
    {
        $data = array();
        foreach($kanbanDataList as $type => $kanbanData) $data[$type] = array_keys($kanbanData);
        $this->loadModel('setting')->setItem("null.execution.kanban.execution$executionID", json_encode($data));

    }

    /**
     * 获取上一个看板的数据。
     * Get the data from the previous Kanban.
     *
     * @param  int        $executionID
     * @access public
     * @return array|null
     */
    public function getPrevKanban(int $executionID): array|null
    {
        $prevKanbans = $this->loadModel('setting')->getItem("owner=null&module=execution&section=kanban&key=execution$executionID");
        return json_decode($prevKanbans, true);
    }

    /**
     * 获取看板的设置。
     * Get kanban setting.
     *
     * @access public
     * @return object
     */
    public function getKanbanSetting(): object
    {
        $allCols    = '1';
        $showOption = '0';
        if(isset($this->config->execution->kanbanSetting->allCols)) $allCols = $this->config->execution->kanbanSetting->allCols;

        $colorList = $this->config->execution->kanbanSetting->colorList;
        if(!is_array($colorList)) $colorList = json_decode($colorList, true);

        $kanbanSetting = new stdclass();
        $kanbanSetting->allCols    = $allCols;
        $kanbanSetting->showOption = $showOption;
        $kanbanSetting->colorList  = $colorList;

        return $kanbanSetting;
    }

    /**
     * 获取看板列的列表。
     * Get the list of kanban columns.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColumns(object $kanbanSetting): array
    {
        $kanbanColumns = array('wait', 'doing', 'pause', 'done');
        if(!empty($kanbanSetting->allCols)) array_push($kanbanColumns, 'cancel', 'closed');
        return $kanbanColumns;
    }

    /**
     * 获取状态和方法的映射关系，此关系决定了看板内容能否从一个泳道拖动到另一个泳道，以及拖动后执行什么方法。
     * Get the mapping between state and method. This relationship determines whether kanban content can be dragged from one lane
     * to another, and what method is executed after dragging.
     *
     * 映射关系的基本格式为 map[$mode][$fromStatus][$toStatus] = $methodName。
     * The basic format of the mapping relationship is map[$mode][$fromStatus][$toStatus] = $methodName.
     *
     * @param string $mode          看板内容类型，可选值 task|bug   The content mode of kanban, should be task or bug.
     * @param string $fromStatus    拖动内容的来源泳道              The origin lane the content dragged from.
     * @param string $toStatus      拖动内容的目标泳道              The destination lane the content dragged to.
     * @param string $methodName    拖动到目标泳道后执行的方法名    The method to execute after dragged the content.
     *
     * 例如 map['task']['doing']['done'] = 'close' 表示：任务(task)看板从进行中(doing)泳道拖动到已完成(done)泳道时，执行关闭(close)方法。
     * For example, map['task']['doing']['done'] = 'close' means: when the task kanban is dragged from the doing lane to the done lane,
     * execute the close method.
     *
     * @param  object $kanbanSetting    This param is used in the biz version, don't remove it.
     * @access public
     * @return string
     */
    public function getKanbanStatusMap($kanbanSetting)
    {
        $statusMap = array();
        if(common::hasPriv('task', 'start')) $statusMap['task']['wait']['doing']  = 'start';
        if(common::hasPriv('task', 'pause')) $statusMap['task']['doing']['pause'] = 'pause';
        if(common::hasPriv('task', 'finish'))
        {
            $statusMap['task']['wait']['done']  = 'finish';
            $statusMap['task']['doing']['done'] = 'finish';
            $statusMap['task']['pause']['done'] = 'finish';
        }
        if(common::hasPriv('task', 'cancel'))
        {
            $statusMap['task']['wait']['cancel']  = 'cancel';
            $statusMap['task']['pause']['cancel'] = 'cancel';
        }
        if(common::hasPriv('task', 'activate'))
        {
            $statusMap['task']['pause']['doing']  = 'activate';
            $statusMap['task']['done']['doing']   = 'activate';
            $statusMap['task']['cancel']['doing'] = 'activate';
            $statusMap['task']['closed']['doing'] = 'activate';
        }
        if(common::hasPriv('task', 'close'))
        {
            $statusMap['task']['done']['closed']   = 'close';
            $statusMap['task']['cancel']['closed'] = 'close';
        }

        if(common::hasPriv('bug', 'resolve'))
        {
            $statusMap['bug']['wait']['done']   = 'resolve';
            $statusMap['bug']['wait']['cancel'] = 'resolve';
        }
        if(common::hasPriv('bug', 'close'))
        {
            $statusMap['bug']['done']['closed'] = 'close';
            $statusMap['bug']['cancel']['closed'] = 'close';
        }
        if(common::hasPriv('bug', 'activate'))
        {
            $statusMap['bug']['done']['wait']   = 'activate';
            $statusMap['bug']['cancel']['wait']   = 'activate';
            $statusMap['bug']['closed']['wait'] = 'activate';
        }

        return $statusMap;
    }

    /**
     * 获取看板状态列表。
     * Get status list of kanban.
     *
     * @param  object $kanbanSetting    This param is used in the biz version, don't remove it.
     * @access public
     * @return array
     */
    public function getKanbanStatusList(object $kanbanSetting): array
    {
        return $this->lang->task->statusList;
    }

    /**
     * 获取看板颜色列表。
     * Get color list of kanban.
     *
     * @param  object $kanbanSetting
     * @access public
     * @return array
     */
    public function getKanbanColorList(object $kanbanSetting): array
    {
        return $kanbanSetting->colorList;
    }

    /**
     * 构建燃尽图数据。
     * Build burn data.
     *
     * @param  int    $executionID
     * @param  array  $dateList
     * @param  string $burnBy       left|estimate|storyPoint
     * @param  string $executionEnd
     * @access public
     * @return array
     */
    public function buildBurnData(int $executionID, array $dateList, string $burnBy = 'left', string $executionEnd = ''): array
    {
        $this->loadModel('report');
        $burnBy = $burnBy ? $burnBy : 'left';

        $sets      = $this->getBurnDataFlot($executionID, $burnBy, false, $dateList);
        $firstBurn = empty($sets) ? 0 : reset($sets);
        $firstTime = !empty($firstBurn->$burnBy) ? $firstBurn->$burnBy : 0;
        if(!$firstTime && !empty($firstBurn->value)) $firstTime = $firstBurn->value;
        if($firstTime == 'null') $firstTime = 0;

        /* If the $executionEnd  is passed, the guide should end of execution. */
        $days     = $executionEnd ? array_search($executionEnd, $dateList) : count($dateList) - 1;
        $rate     = $days ? $firstTime / $days : '';
        $baseline = array();
        foreach($dateList as $i => $date)
        {
            $value = ($i > $days ? 0 : round(($days - $i) * (float)$rate, 3));
            $baseline[] = $value;
        }

        $chartData['labels']   = $this->report->convertFormat($dateList, DT_DATE5);
        $chartData['burnLine'] = $this->report->createSingleJSON($sets, $dateList);
        $chartData['baseLine'] = $baseline;

        $execution = $this->getByID($executionID);

        /*
         * 1. Execution status is not closed and suspended, end date less than today;
         * 2. Execution status is closed, end date less than closed date;
         * 3. Execution status is suspended, end date less than suspended date;
         * Processing burn down chart Information.
         */
        $endDate = helper::today();
        if($execution->status == 'closed')    $endDate = empty($execution->closedDate) ? '' : substr($execution->closedDate, 0, 10);
        if($execution->status == 'suspended') $endDate = $execution->suspendedDate;

        if($endDate > $execution->end)
        {
            $delaySets = $this->getBurnDataFlot($executionID, $burnBy, true, $dateList);
            $chartData['delayLine'] = $this->report->createSingleJSON($delaySets, $dateList);
        }

        return $chartData;
    }

    /**
     * 在树状图中填充任务。
     * Fill tasks in tree.
     * @param  object $tree
     * @param  int    $executionID
     * @access public
     * @return object
     */
    public function fillTasksInTree(object $node, int $executionID): object
    {
        static $taskGroups = array();
        if(empty($taskGroups) && !$this->cookie->showStory) $taskGroups = $this->executionTao->getTaskGroups($executionID);
        if(!empty($node->children))
        {
            foreach($node->children as $i => &$child)
            {
                $child = $this->fillTasksInTree((object)$child, $executionID);
                /* Remove no children node. */
                if($child->type != 'story' && $child->type != 'task' && empty($child->children)) unset($node->children[$i]);
            }
        }

        if(!isset($node->id)) $node->id = 0;
        if($node->type == 'story')
        {
            static $storyGroups;
            if(empty($storyGroups))
            {
                if($this->config->vision == 'lite') $execution = $this->getById($executionID);
                $stories = $this->loadModel('story')->getListByProject(isset($execution->project) ? $execution->project : $executionID);

                $storyGroups = array();
                foreach($stories as $story) $storyGroups[$story->product][$story->module][$story->id] = $story;
            }

            $node = $this->executionTao->processStoryNode($node, $storyGroups, $taskGroups, $executionID);
        }
        elseif($node->type == 'task')
        {
            $node = $this->executionTao->processTaskNode($node, $taskGroups);
        }
        elseif($node->type == 'product')
        {
            $node->title = $node->name;
            if(isset($node->children[0]) && empty($node->children[0]->children)) array_shift($node->children);
        }

        $node->actions = false;
        if(!empty($node->children)) $node->children = array_values($node->children);
        return $node;
    }

    /**
     * 通过产品的ID列表获取计划数据。
     * Get plan data from the ID list of the product.
     *
     * @param  array  $productID
     * @param  string $param       withMainPlan|skipParent|unexpired|noclosed|sortedByDate
     * @param  int    $executionID
     * @return array
     */
    public function getPlans(array $productIdList, string $param = '', int $executionID = 0): array
    {
        $param        = strtolower($param);
        $branchIdList = strpos($param, 'withmainplan') !== false ? array(BRANCH_MAIN => BRANCH_MAIN) : array();
        $branchGroups = $this->getBranchByProduct($productIdList, $executionID, 'noclosed');
        foreach($branchGroups as $branches)
        {
            foreach($branches as $branchID => $branchName) $branchIdList[] = $branchID;
        }

        $branchQuery = '(';
        if(!empty($branchIdList))
        {
            $branchCount = count($branchIdList);
            foreach($branchIdList as $index => $branchID)
            {
                $branchQuery .= "FIND_IN_SET('$branchID', branch)";
                if($index < $branchCount - 1) $branchQuery .= ' OR ';
            }
        }
        else
        {
            $branchQuery .= "FIND_IN_SET('0', branch)";
        }

        $branchQuery .= " OR branch = '')";

        $plans = $this->dao->select('t1.id,t1.title,t1.product,t1.parent,t1.begin,t1.end,t1.branch,t2.type as productType')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t2.id=t1.product')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere($branchQuery)
            ->beginIF(strpos($param, 'unexpired') !== false)->andWhere('t1.end')->ge(helper::today())->fi()
            ->beginIF(strpos($param, 'noclosed')  !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->orderBy('t1.begin desc, t1.id desc')
            ->fetchAll('id');

        return $this->processProductPlans($plans, $param);
    }

    /**
     * 处理产品计划的数据。
     * Process product planning data.
     *
     * @param  array  $plans
     * @param  string $param withmainplan|skipparent
     * @access public
     * @return array
     */
    public function processProductPlans(array $plans, string $param = ''): array
    {
        if(strpos($param, 'sortedbydate') !== false)
        {
            $pendPlans   = array();
            $normalPlans = array();
            foreach($plans as $plan)
            {
                if($plan->begin == '2030-01-01' && $plan->end == '2030-01-01')
                {
                    $pendPlans[$plan->id] = $plan;
                }
                else
                {
                    $normalPlans[$plan->id] = $plan;
                }
            }
            $plans = array_merge($normalPlans, $pendPlans);
        }

        $plans        = $this->loadModel('productplan')->reorder4Children($plans);
        $plans        = $this->productplan->relationBranch($plans);
        $productPlans = array();
        foreach($plans as $plan)
        {
            if($plan->parent == '-1' && strpos($param, 'skipparent') !== false) continue;
            if($plan->parent > 0 && isset($plans[$plan->parent])) $plan->title = $plans[$plan->parent]->title . ' /' . $plan->title;
            $productPlans[$plan->product][$plan->id] = $plan->title . " [{$plan->begin} ~ {$plan->end}]";
            if($plan->begin == '2030-01-01' && $plan->end == '2030-01-01') $productPlans[$plan->product][$plan->id] = $plan->title . ' ' . $this->lang->productplan->future;
            if($plan->productType != 'normal') $productPlans[$plan->product][$plan->id] = $productPlans[$plan->product][$plan->id] . ' / ' . ($plan->branchName ? $plan->branchName : $this->lang->branch->main);
        }

        return $productPlans;
    }

    /**
     * 构造树状图的数据。
     * Build tree data.
     *
     * @param  array $trees
     * @param  bool  $hasProduct
     * @param  array $gradeGroup
     * @access pubic
     * @return array
     */
    public function buildTree(array $trees, bool $hasProduct = true, array $gradeGroup = array()): array
    {
        $treeData     = array();
        $canViewTask  = common::hasPriv('execution', 'treeTask');
        $canViewStory = common::hasPriv('execution', 'treeStory');

        foreach($trees as $index => $tree)
        {
            $tree = (object)$tree;
            $treeData[$index] = array('className' => 'py-2 cursor-pointer ' . $tree->type);
            $assigedToHtml    = !empty($tree->assignedTo) ?  ' <span class="user align-bottom"><div class="avatar rounded-full size-xs ml-1 align-' . (strlen(zget($tree, 'avatar')) == 1 ? 'middle primary' : 'sub') . '">' . zget($tree, 'avatar') . '</div> ' . zget($tree, 'avatarAccount') . '</span>' : '';
            switch($tree->type)
            {
                case 'task':
                    $label = $this->lang->task->common;
                    if($tree->parent > 0 && !$tree->isParent) $label = $this->lang->task->children;
                    if($tree->isParent) $label = $this->lang->task->parent;
                    $treeData[$index]['url']     = $canViewTask ? helper::createLink('execution', 'treeTask', "taskID={$tree->id}") : '';
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'><span class='label gray-pale rounded-full align-sub'>{$label}</span><span class='ml-4 align-sub'>{$tree->id}</span><span class='title ml-4 " . ($canViewTask ? 'text-primary' : '') . " align-sub' title='{$tree->title}'>" . $tree->title . '</span>'. $assigedToHtml . '</div>',
                    );
                    break;
                case 'product':
                    $treeData[$index]['content'] = array('html' => "<span class='label rounded-full p-2 gray-outline' title='{$tree->name}'>{$tree->name}</span>");
                    break;
                case 'story':
                    $this->app->loadLang('story');
                    $gradePairs = zget($gradeGroup, $tree->type, array());
                    $grade      = zget($gradePairs, $tree->grade, $tree->grade);
                    $gradeName  = ($grade && isset($grade->name)) ? "<span class='label gray-pale rounded-full'>{$grade->name}</span>" : '';
                    $treeData[$index]['url']     = $canViewStory ? helper::createLink('execution', 'treeStory', "taskID={$tree->storyId}") : '';
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'>{$gradeName}<span class='ml-4'>{$tree->storyId}</span><span class='title " . ($canViewStory ? 'text-primary' : '') . " ml-4' title='{$tree->title}'>{$tree->title}</span>" . $assigedToHtml . '</div>',
                    );
                    break;
                case 'requirement':
                    $this->app->loadLang('requirement');
                    $gradePairs = zget($gradeGroup, $tree->type, array());
                    $grade      = zget($gradePairs, $tree->grade, $tree->grade);
                    $gradeName  = ($grade && isset($grade->name)) ? "<span class='label gray-pale rounded-full'>{$grade->name}</span>" : '';
                    $treeData[$index]['url']     = helper::createLink('execution', 'treeStory', "taskID={$tree->storyId}");
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'>{$gradeName}<span class='ml-4'>{$tree->storyId}</span><span class='title text-primary ml-4' title='{$tree->title}'>{$tree->title}</span>" . $assigedToHtml . '</div>',
                    );
                    break;
                case 'epic':
                    $this->app->loadLang('epic');
                    $gradePairs = zget($gradeGroup, $tree->type, array());
                    $grade      = zget($gradePairs, $tree->grade, $tree->grade);
                    $gradeName  = ($grade && isset($grade->name)) ? "<span class='label gray-pale rounded-full'>{$grade->name}</span>" : '';
                    $treeData[$index]['url']     = helper::createLink('execution', 'treeStory', "taskID={$tree->storyId}");
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'>{$gradeName}<span class='ml-4'>{$tree->storyId}</span><span class='title text-primary ml-4' title='{$tree->title}'>{$tree->title}</span>" . $assigedToHtml . '</div>',
                    );
                    break;
                case 'branch':
                    $this->app->loadLang('branch');
                    $treeData[$index]['content'] = array(
                        'html' => "<span class='label gray-pale rounded-full'>{$this->lang->branch->common}</span><span class='title ml-4' title='{$tree->name}'>{$tree->name}</span>"
                    );
                    break;
                default:
                    $firstClass = $tree->id == 0 ? 'label rounded-full p-2 gray-outline' : '';
                    $treeData[$index]['content'] = array('html' => "<span class='{$firstClass} title' title='{$tree->name}'>" . $tree->name . '</span>');
                    break;
            }
            if(isset($tree->children))
            {
                if($tree->type == 'task')
                {
                    $label = $this->lang->task->common;
                    if($tree->parent > 0 && !$tree->isParent) $label = $this->lang->task->children;
                    if($tree->isParent) $label = $this->lang->task->parent;
                    $treeData[$index]['url']     = $canViewTask ? helper::createLink('execution', 'treeTask', "taskID={$tree->id}") : '';
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'><span class='label gray-pale rounded-full align-sub'>{$label}</span><span class='ml-4 align-sub'>{$tree->id}</span><span class='title ml-4 " . ($canViewTask ? 'text-primary' : '') . " align-sub' title='{$tree->title}'>" . $tree->title . '</span>'. $assigedToHtml . '</div>',
                    );
                }
                $treeData[$index]['items'] = $this->buildTree($tree->children, $hasProduct, $gradeGroup);
            }
        }
        return $treeData;
    }

    /**
     * 更新用户可查看的执行和产品。
     * Update the execution and product that users can view.
     *
     * @param  int    $executionID
     * @param  string $objectType
     * @param  array  $users
     * @access public
     * @return void
     */
    public function updateUserView(int $executionID, string $objectType = 'sprint', array $users = array())
    {
        $this->loadModel('user')->updateUserView(array($executionID), $objectType, $users);

        $products = $this->loadModel('product')->getProducts($executionID, 'all', '', false);
        if(!empty($products)) $this->user->updateUserView(array_keys($products), 'product', $users);
    }

    /**
     * 获取阶段关联的产品。
     * Get the products associated with the stage.
     *
     * @param  array  $stageIdList
     * @access public
     * @return array
     */
    public function getStageLinkProductPairs(array $stageIdList = array()): array
    {
        return $this->dao->select('t1.project, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.project')->in($stageIdList)
            ->fetchPairs('project', 'name');
    }

    /**
     * 设置阶段的层级和父子关系。
     * Set the level and parent-child relationship of the stage.
     *
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function setTreePath(int $executionID): bool
    {
        $execution = $this->fetchByID($executionID);
        if(!$execution) return false;

        $parent = $this->fetchByID($execution->parent);
        if(!$parent) return false;

        $path = array();
        if($parent->type == 'project')
        {
            $path['path']  =  ",{$parent->id},{$execution->id},";
            $path['grade'] = 1;
        }
        elseif($parent->type == 'stage')
        {
            $path['path']  = $parent->path . "{$execution->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROJECT)->data($path)->where('id')->eq($execution->id)->exec();

        return !dao::isError();
    }

    /**
     * 生成dtable的行数据。
     * Generate row for dtable.
     *
     * @param  array  $executions
     * @param  array  $users
     * @param  array  $avatarList
     * @access public
     * @return array
     */
    public function generateRow(array $executions, array $users, array $avatarList): array
    {
        $rows = array();
        $this->app->loadConfig('project');

        $this->getLimitedExecution();

        $executionList = array();
        $groupIdList   = array();
        foreach($executions as $execution)
        {
            $executionList[$execution->id] = $execution;
            $groupIdList[$execution->workflowGroup] = $execution->workflowGroup;
        }

        $flowActionConditions = array();
        if($this->config->edition != 'open')
        {
            $this->loadModel('flow');
            $this->loadModel('workflowaction');
            foreach($groupIdList as $groupID)
            {
                $flowActions = $this->workflowaction->getList('execution', 'status_desc,order_asc', $groupID);
                foreach($flowActions as $flowAction)
                {
                    if(!empty($flowAction->conditions)) $flowActionConditions[$groupID][$flowAction->action] = $flowAction->conditions;
                }
            }
        }

        $canCreateChildStage = commonModel::hasPriv('programplan', 'create');
        $canCreateTask       = commonModel::hasPriv('task', 'create');
        $canEditStage        = commonModel::hasPriv('programplan', 'edit');
        foreach($executionList as $execution)
        {
            $execution->rawID       = $execution->id;
            $execution->isExecution = 1;
            $execution->id          = 'pid' . (string)$execution->id;
            $execution->projectID   = $execution->project;
            $execution->project     = $execution->projectName;
            $execution->rawParent   = $execution->parent;
            $execution->parent      = (isset($executionList[$execution->parent]) && $execution->parent && $execution->grade > 1) ? 'pid' . (string)$execution->parent : '';
            $execution->hasChild    = !empty($execution->isParent);
            $execution->isParent    = !empty($execution->isParent) or !empty($execution->tasks);
            $execution->actions     = array();

            $canModify = common::canModify('execution', $execution);
            if($canModify && isset($this->config->project->execution->dtable->actionsRule[$execution->projectModel]))
            {
                $isStage = in_array($execution->projectModel, array('waterfall', 'waterfallplus', 'ipd'));
                foreach($this->config->project->execution->dtable->actionsRule[$execution->projectModel] as $actionKey)
                {
                    $action  = array();
                    $actions = explode('|', $actionKey);
                    foreach($actions as $actionName)
                    {
                        if($actionName == 'createChildStage' && !$canCreateChildStage) continue;
                        if($actionName == 'createTask' && !$canCreateTask)  continue;
                        if($actionName == 'edit' && $isStage && !$canEditStage) continue;
                        if(!in_array($actionName, array('createTask', 'createChildStage')) && !commonModel::hasPriv('execution', $actionName)) continue;

                        $action = array('name' => $actionName, 'disabled' => $this->isClickable($execution, $actionName) ? false : true);

                        if($actionName == 'createChildStage' && $action['disabled'] && $execution->type != 'stage') $action['hint'] = $this->lang->programplan->error->notStage;
                        if(!$action['disabled']) break;
                        if($actionName == 'close' && $execution->status != 'closed') break;
                        if(!empty($execution->frozen) && in_array($actionName, array('edit', 'createChildStage', 'delete', 'putoff'))) $action['hint'] = sprintf($this->lang->execution->stageFrozenTip, $this->lang->execution->$actionName);
                    }

                    if(!empty($action))
                    {
                        if(!empty($flowActionConditions[$execution->workflowGroup][$action['name']]) && !$action['disabled']) $action['disabled'] = !$this->flow->checkConditions($flowActionConditions[$execution->workflowGroup][$action['name']], $execution);
                        $execution->actions[] = $action;
                    }
                }
            }

            /* For user's avatar. */
            if($execution->PM)
            {
                $realname = zget($users, $execution->PM);
                if(empty($realname)) continue;

                $execution->PMAccount = $execution->PM;
                $execution->PM        = $realname;
                $execution->PMAvatar  = zget($avatarList, $execution->PMAccount, '');
            }

            $rows[$execution->id] = $execution;

            /* Append tasks and child stages. */
            if(!empty($execution->tasks))  $rows = $this->appendTasks($execution->tasks, $rows, $users, $avatarList, $canModify);
        }

        if(in_array($this->config->edition, array('max', 'ipd'))) $rows = $this->loadModel('project')->countDeliverable($rows, 'execution');
        return $rows;
    }

    /**
     * 追加任务列表到执行列表。
     * Append tasks to execution list.
     *
     * @param  array  $tasks
     * @param  array  $rows
     * @param  array  $users
     * @param  array  $avatarList
     * @param  bool   $canModify
     * @access public
     * @return array
     */
    public function appendTasks(array $tasks, array $rows, array $users = array(), array $avatarList = array(), bool $canModify = true): array
    {
        $this->loadModel('task');
        $this->app->loadConfig('project');

        $flowActionConditions = array();
        if($this->config->edition != 'open')
        {
            $this->loadModel('flow');

            $flowActions = $this->loadModel('workflowaction')->getList('task');
            foreach($flowActions as $flowAction)
            {
                if(!empty($flowAction->conditions)) $flowActionConditions[$flowAction->action] = $flowAction->conditions;
            }
        }

        foreach($tasks as $task)
        {
            if(!$canModify) continue;
            if($task->status == 'changed')
            {
                if(!commonModel::hasPriv('task', 'confirmStoryChange')) continue;
                if(!common::hasDBPriv($task, 'task', 'confirmStoryChange')) continue;
                $clickable = $this->task->isClickable($task, 'confirmStoryChange');
                $task->actions[] = array('name' => 'confirmStoryChange', 'disabled' => !$clickable);

                $task = $this->task->processConfirmStoryChange($task);
            }
            else
            {
                foreach($this->config->project->execution->dtable->actionsRule['task'] as $action)
                {
                    $rawAction = str_replace('Task', '', $action);
                    if(!commonModel::hasPriv('task', $rawAction)) continue;
                    if(!common::hasDBPriv($task, 'task', $rawAction)) continue;

                    $clickable = $this->task->isClickable($task, $rawAction);
                    if($clickable && !empty($flowActionConditions[$rawAction])) $clickable = $this->flow->checkConditions($flowActionConditions[$rawAction], $task);

                    $action = array('name' => $action);
                    if(!$clickable) $action['disabled'] = true;
                    $task->actions[] = $action;
                }
            }

            $prefixLabel = "<span class='pri-{$task->pri} mr-1'>" . zget($this->lang->task->priList, $task->pri) . '</span> ';
            if($task->isParent > 0)
            {
                $prefixLabel .= "<span class='label gray-pale rounded-xl mx-1'>{$this->lang->task->parentAB}</span>";
            }
            elseif($task->parent > 0)
            {
                $prefixLabel .= "<span class='label gray-pale rounded-xl mx-1'>{$this->lang->task->childrenAB}</span>";
            }

            $task->prefixLabel   = $prefixLabel;
            $task->rawName       = $task->name;
            $task->name          = $prefixLabel . html::a(helper::createLink('task', 'view', "id={$task->id}"), $task->name);
            $task->rawID         = $task->id;
            $task->id            = 'tid' . (string)$task->id;
            $task->totalEstimate = $task->estimate;
            $task->totalConsumed = $task->consumed;
            $task->totalLeft     = $task->left;
            $task->parent        = $task->parent > 0 && isset($tasks[$task->parent]) ? "tid{$task->parent}" : "pid{$task->execution}";
            $task->progress      = ($task->consumed + $task->left) == 0 ? 0 : round($task->consumed / ($task->consumed + $task->left), 2) * 100;
            $task->begin         = $task->estStarted;
            $task->end           = $task->deadline;
            $task->realBegan     = $task->realStarted;
            $task->realEnd       = $task->finishedDate;
            $task->PM            = $task->assignedTo;
            if($task->PM)
            {
                $realname = zget($users, $task->PM);
                if(empty($realname)) continue;

                $task->PMAccount = $task->PM;
                $task->PM        = $realname;
                $task->PMAvatar  = zget($avatarList, $task->PMAccount, '');
            }

            $task->needConfirm = false;
            if(!empty($task->storyStatus) && $task->storyStatus == 'active' && !in_array($task->status, array('closed', 'cancel')) && $task->latestStoryVersion > $task->storyVersion)
            {
                $task->needConfirm = true;
                $task->rawStatus   = $task->status;
                $task->status      = 'changed';
            }

            $rows[] = $task;
        }

        return $rows;
    }

    /*
     * 构建执行列表的搜索表单。
     * Build search form for execution list.
     *
     * @param int     $queryID
     * @param string  $actionURL
     * @return void
     * */
    public function buildSearchForm(int $queryID, string $actionURL)
    {
        $this->config->execution->all->search['queryID']   = $queryID;
        $this->config->execution->all->search['actionURL'] = $actionURL;

        $projectPairs  = array(0 => '');
        $projectPairs += $this->loadModel('project')->getPairsByProgram(0, 'all', false, 'order_asc', '', '', 'multiple');
        $this->config->execution->all->search['params']['project']['values'] = $projectPairs + array('all' => $this->lang->execution->allProject);

        $this->loadModel('search')->setSearchParams($this->config->execution->all->search);
    }

    /*
     * 不启用迭代的项目，创建默认迭代。
     * Create default sprint for project which is not using sprint.
     *
     * @param  int $projectID
     * @return int
     * */
    public function createDefaultSprint(int $projectID): int
    {
        $project = $this->fetchByID($projectID);

        $executionData = new stdclass();
        $executionData->project     = $projectID;
        $executionData->name        = $project->name;
        $executionData->grade       = 1;
        $executionData->storyType   = $project->storyType;
        $executionData->begin       = $project->begin;
        $executionData->end         = $project->end;
        $executionData->status      = 'wait';
        $executionData->type        = $project->model == 'kanban' ? 'kanban' : 'sprint';
        $executionData->days        = $project->days;
        $executionData->team        = $project->team;
        $executionData->desc        = $project->desc;
        $executionData->acl         = 'open';
        $executionData->PO          = $project->PO;
        $executionData->QD          = $project->QD;
        $executionData->PM          = $project->PM;
        $executionData->RD          = $project->RD;
        $executionData->multiple    = '0';
        $executionData->whitelist   = '';
        $executionData->plans       = array();
        $executionData->hasProduct  = $project->hasProduct;
        $executionData->openedBy    = $this->app->user->account;
        $executionData->openedDate  = helper::now();
        $executionData->parent      = $projectID;
        $executionData->isTpl       = $project->isTpl;
        if($project->code) $executionData->code = $project->code;

        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
        foreach($projectProducts as $projectProduct)
        {
            if($projectProduct->product) $executionData->products[] = $projectProduct->product;
            if($projectProduct->branch)  $executionData->branch[]   = $projectProduct->branch;
            if($projectProduct->plan)
            {
                $plans = explode(',', trim($projectProduct->plan, ','));
                $executionData->plans[$projectProduct->product] = isset($executionData->plans[$projectProduct->product]) ? array_merge($executionData->plans[$projectProduct->product], $plans) : $plans;
            }
        }

        $executionID = $this->create($executionData, array($this->app->user->account));
        if($project->model == 'kanban')
        {
            $execution = $this->fetchById($executionID);
            $this->loadModel('kanban')->createRDKanban($execution);
        }

        $this->linkStories($executionID);

        return $executionID;
    }

    /**
     * 同步无迭代项目下的影子迭代。
     * Sync no multiple project to sprint.
     *
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function syncNoMultipleSprint(int $projectID): int
    {
        $project = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
        if(empty($project)) return 0;

        $postData = new stdclass();
        $postData->project   = $projectID;
        $postData->name      = $project->name;
        $postData->storyType = $project->storyType;
        $postData->begin     = $project->begin;
        $postData->end       = $project->end;
        $postData->realBegan = $project->realBegan ? $project->realBegan : null;
        $postData->realEnd   = $project->realEnd ? $project->realEnd : null;
        $postData->days      = $project->days;
        $postData->team      = $project->team;
        $postData->PO        = $project->PO;
        $postData->QD        = $project->QD;
        $postData->PM        = $project->PM;
        $postData->RD        = $project->RD;
        $postData->status    = $project->status;
        $postData->acl       = 'open';
        $postData->products  = '';
        $postData->code      = empty($project->code) ? $project->name : $project->code;
        $postData->uid       = '';

        /* Handle extend fields. */
        $extendFields = $this->loadModel('project')->getFlowExtendFields($projectID);
        foreach($extendFields as $field) $_POST[$field->field] = $project->{$field->field};
        if(isset($this->config->setCode) and $this->config->setCode == 1) $postData->code = $project->code;

        $updateProductsData = new stdclass();
        $updateProductsData->products = array();
        $updateProductsData->branch   = array();
        $updateProductsData->plans    = array();
        $projectProducts    = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->fetchAll();
        foreach($projectProducts as $projectProduct)
        {
            $updateProductsData->products[] = $projectProduct->product;
            $updateProductsData->branch[]   = $projectProduct->branch;
            if($projectProduct->plan) $updateProductsData->plans[$projectProduct->product] = explode(',', trim($projectProduct->plan, ','));
        }

        $teamMembers = $this->dao->select('*')->from(TABLE_TEAM)->where('type')->eq('project')->andWhere('root')->eq($projectID)->fetchPairs('account', 'account');
        $postData->teamMembers = array_values($teamMembers);

        /* Update execution and linked product. */
        $executionID = (int)$this->dao->select('*')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('type')->in('sprint,kanban')->andWhere('multiple')->eq(0)->fetch('id');
        if($executionID)
        {
            $this->config->execution->edit->requiredFields = ''; // 从项目同步过来的字段，不需要验证必填。
            $this->update($executionID, $postData);
            $this->updateProducts($executionID, (array)$updateProductsData);
        }

        return $executionID;
    }

    /**
     * 根据状态创建和设置迭代用于更新迭代的状态。
     * Build execution object by status.
     *
     * @param  string $status
     * @access public
     * @return object
     */
    public function buildExecutionByStatus($status)
    {
        $execution = new stdclass();
        $execution->status         = $status;
        $execution->lastEditedBy   = $this->app->user->account;
        $execution->lastEditedDate = helper::now();

        if($status == 'wait')
        {
            $execution->closedBy      = '';
            $execution->canceledBy    = '';
            $execution->closedDate    = null;
            $execution->canceledDate  = null;
            $execution->realBegan     = null;
            $execution->realEnd       = null;
            $execution->suspendedDate = null;
        }
        elseif($status == 'doing')
        {
            $execution->realBegan  = helper::today();
            $execution->closedBy   = '';
            $execution->canceledBy = '';
        }
        elseif($status == 'suspended')
        {
            $execution->suspendedDate = helper::now();
            $execution->closedBy      = '';
            $execution->closedDate    = null;
            $execution->realEnd       = null;
        }
        elseif($status == 'closed')
        {
            $execution->closedBy   = $this->app->user->account;
            $execution->realEnd    = helper::today();
            $execution->closedDate = helper::now();
        }

        return $execution;
    }

    /**
     * 给执行列表重新排序。
     * Reset execution orders.
     *
     * @param  array  $executions
     * @param  array  $parentExecutions
     * @param  array  $childExecutions
     * @access public
     * @return array
     */
    public function resetExecutionSorts(array $executions, array $parentExecutions = array(), array $childExecutions = array()): array
    {
        if(empty($executions)) return array();

        if(empty($parentExecutions) && empty($childExecutions))
        {
            foreach($executions as $execution)
            {
                if($execution->grade == 1) $parentExecutions[$execution->id] = $execution;
                if($execution->grade > 1 && $execution->parent) $childExecutions[$execution->parent][$execution->id] = $execution;
            }
        }

        if(empty($parentExecutions)) return $executions;

        $sortedExecutions = array();
        foreach($parentExecutions as $executionID => $execution)
        {
            if(!isset($sortedExecutions[$executionID]) and isset($executions[$executionID])) $sortedExecutions[$executionID] = $executions[$executionID];
            if(!empty($childExecutions[$executionID])) $sortedExecutions += $this->resetExecutionSorts($executions, $childExecutions[$executionID], $childExecutions);
        }
        return $sortedExecutions;
    }

    /**
     * 删除一个执行。
     * Delete an execution.
     *
     * @param  string $table
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function delete(string $table, int $executionID): void
    {
        $this->dao->update($table)->set('deleted')->eq(1)->where('id')->eq($executionID)->exec();
        $this->loadModel('action')->create('execution', $executionID, 'deleted', '' , 1);
    }

    /*
     * 通过父级id获取同级的所有执行类型。
     * Get all execution types of the same level through the parent id.
     *
     * @param  int    $parentID
     * @access public
     * @return void
     */
    public function getSiblingsTypeByParentID($parentID)
    {
        return $this->dao->select('DISTINCT type')->from(TABLE_EXECUTION)->where('deleted')->eq(0)->andWhere('parent')->eq($parentID)->fetchPairs('type');
    }

    /**
     * 通过 ID 列表获取执行键对。
     * Get execution pairs by id list.
     *
     * @param  array  $executionIdList
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPairsByList(array $executionIdList, string $type = '', string $orderBy = 'id_asc'): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionPairs();

        return $this->dao->select('id,name')->from(TABLE_EXECUTION)
            ->where('id')->in($executionIdList)
            ->beginIF(!empty($type))->andWhere('type')->in($type)->fi()
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 通过执行ID列表获取执行的子级ID列表组。
     * Get the children id list of the execution group by the parent id list.
     *
     * @param  array  $executionIdList
     * @access public
     * @return array
     */
    public function getChildIdGroup(array $parentIdList): array
    {
        return $this->dao->select('id,parent')->from(TABLE_EXECUTION)->where('parent')->in($parentIdList)->andWhere('type')->in('stage,kanban,sprint')->fetchGroup('parent', 'id');
    }

    /*
     * 获取旧页面1.5级下拉。
     * Get execution switcher.
     *
     * @param  int     $executionID
     * @param  string  $currentModule
     * @param  string  $currentMethod
     * @access public
     * @return string
     */
    public function getSwitcher(int $executionID, string $currentModule, string $currentMethod): string
    {
        if($currentModule == 'execution' and in_array($currentMethod,  array('index', 'all', 'batchedit', 'create'))) return '';

        $currentExecutionName = $this->lang->execution->common;
        if($executionID)
        {
            $currentExecution     = $this->getById($executionID);
            $currentExecutionName = $currentExecution->name;
        }

        if($this->app->viewType == 'mhtml' and $executionID)
        {
            $output  = html::a(helper::createLink('execution', 'index'), $this->lang->executionCommon) . $this->lang->hyphen;
            $output .= "<a id='currentItem' href=\"javascript:showSearchMenu('execution', '$executionID', '$currentModule', '$currentMethod', '')\">{$currentExecutionName} <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            return $output;
        }

        $dropMenuLink = helper::createLink('execution', 'ajaxGetDropMenu', "executionID=$executionID&module=$currentModule&method=$currentMethod&extra=");
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentExecutionName}'><span class='text'>{$currentExecutionName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='dropmenu' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        return $output;
    }

    /**
     * 获取执行的收件人和抄送人列表。
     * Get to list and cc list of the execution.
     *
     * @param  object $execution
     * @access public
     * @return array
     */
    public function getToAndCcList(object $execution): array
    {
        $teamMembers = $this->loadModel('user')->getTeamMemberPairs($execution->id, 'execution');
        $whitelist   = !empty($execution->whitelist) ? explode(',', $execution->whitelist) : array();
        $whitelist   = array_filter($whitelist);

        $toList = $ccList = '';
        $toList = array_merge(array_keys($teamMembers), $whitelist);

        return array(implode(',', $toList), $ccList);
    }

    /**
     * 检查迭代是否可以关闭。
     * Check if the execution can be closed.
     *
     * @param  object  $execution
     * @access public
     * @return bool
     */
    public function canCloseByDeliverable(object $execution): bool
    {
        $stageType    = $execution->type == 'stage' ? $execution->attribute : $execution->type;
        $project      = $this->loadModel('project')->fetchByID((int)$execution->project);
        $deliverables = $this->dao->select('t1.template,t1.name,t2.required,t1.id')->from(TABLE_DELIVERABLE)->alias('t1')
            ->leftJoin(TABLE_DELIVERABLESTAGE)->alias('t2')->on('t1.id = t2.deliverable')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.workflowGroup')->eq((int)$project->workflowGroup)
            ->andWhere('t1.status')->eq('enabled')
            ->andWhere('t2.stage')->eq($stageType)
            ->fetchAll('id');

        if(empty($deliverables)) return true;

        $executionDeliverables = $execution->deliverable ? json_decode($execution->deliverable, true) : array();

        foreach($deliverables as $id => $deliverable)
        {
            if(empty($deliverable->required)) continue;

            if(!isset($executionDeliverables[$id])) return false;
            if(empty($executionDeliverables[$id]['fileID']) && empty($executionDeliverables[$id]['doc'])) return false;
        }

        return true;
    }
}
