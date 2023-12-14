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

                if(in_array($execution->attribute, array('request', 'review')))
                {
                    $features['story'] = false;
                    $features['plan'] = false;
                }
            }
        }

        /* The plan function is disabled for no-product project. */
        if(isset($execution->projectInfo) && in_array($execution->projectInfo->model, array('waterfall', 'kanban', 'waterfallplus')) && empty($execution->projectInfo->hasProduct))
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
        $execution = $this->fetchByID($executionID);
        if(!$execution) return;

        if($execution->type == 'kanban') $this->executionTao->setKanbanMenu();

        /* Check execution permission. */
        $executions = $this->fetchPairs($execution->project, 'all');
        if(!$executionID && $this->session->execution) $executionID = $this->session->execution;
        if(!$executionID) $executionID = key($executions);
        if($execution->multiple and !isset($executions[$executionID])) $executionID = key($executions);
        if($execution->multiple and $executions and (!isset($executions[$executionID]) or !$this->checkPriv($executionID))) return $this->accessDenied();

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
        common::setMenuVars('execution', $executionID);

        if($execution->type != 'kanban' && $this->app->getModuleName() == 'repo' || $this->app->getModuleName() == 'mr')
        {
            $repoPairs = $this->loadModel('repo')->getRepoPairs('execution', $executionID);

            $showMR = false;
            if(common::hasPriv('mr', 'browse'))
            {
                foreach($repoPairs as $repoName)
                {
                    preg_match('/^\[(\w+)\]/', $repoName, $matches);
                    if(isset($matches[1]) && in_array($matches[1], $this->config->repo->gitServiceList)) $showMR = true;
                }
            }
            if(!$showMR) unset($this->lang->execution->menu->devops['subMenu']->mr);
            if(!$repoPairs || !common::hasPriv('repo', 'review')) unset($this->lang->execution->menu->devops['subMenu']->review);


            if(empty($this->lang->execution->menu->devops['subMenu']->mr) && empty($this->lang->execution->menu->devops['subMenu']->review))
            {
                unset($this->lang->execution->menu->devops['subMenu']);
                $this->lang->execution->menu->devops['link'] = str_replace($this->lang->devops->common, $this->lang->repo->common, $this->lang->execution->menu->devops['link']);
            }
        }

        /* Set stroy navigation for no-product project. */
        $this->loadModel('project')->setNoMultipleMenu($executionID);
        if(isset($this->lang->execution->menu->storyGroup)) unset($this->lang->execution->menu->storyGroup);
        if(isset($this->lang->execution->menu->story['dropMenu']) && $this->app->getMethodName() == 'storykanban')
        {
            $this->lang->execution->menu->story['link']            = str_replace(array($this->lang->common->story, 'story'), array($this->lang->SRCommon, 'storykanban'), $this->lang->execution->menu->story['link']);
            $this->lang->execution->menu->story['dropMenu']->story = str_replace('execution|story', 'execution|storykanban', $this->lang->execution->menu->story['dropMenu']->story);
        }
    }

    /**
     * 根据条件设置执行二级导航。
     * Set secondary navigation based on the conditions.
     *
     * @param  object $execution
     * @access public
     * @return void
     */
    public function removeMenu(object $execution)
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
            if(isset($this->cookie->lastExecution))            $executionID = (int)$this->cookie->lastExecution;
            if(isset($this->session->execution))               $executionID = (int)$this->session->execution;
            if(isset($this->config->execution->lastExecution)) $executionID = (int)$this->config->execution->lastExecution;
        }

        /* If the execution doesn't exist in the list, use the first execution in the list. */
        if(!isset($executions[$executionID])) $executionID = key($executions);

        /* Check execution again. */
        if($executionID)
        {
            $execution = $this->dao->findByID($executionID)->from(TABLE_EXECUTION)->fetch();
            if(empty($execution)) return $this->app->control->sendError($this->lang->notFound, helper::createLink('execution', 'all'));
            if(!$this->app->user->admin && strpos(",{$this->app->user->view->sprints},", ",{$executionID},") === false) $this->accessDenied();
        }

        /* Save session. */
        $this->executionTao->saveSession($executionID);

        /* Return execution id. */
        return $executionID;
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
        $execution = $this->getByID($executionID);
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
        $this->dao->insert(TABLE_EXECUTION)->data($execution, 'products,plans,branch')
            ->autoCheck('begin,end')
            ->batchCheck($this->config->execution->create->requiredFields, 'notempty')
            ->checkIF(!empty($execution->name), 'name', 'unique', "`type` in ('sprint','stage', 'kanban') and `project` = " . (int)$execution->project . " and `deleted` = '0'")
            ->checkIF(!empty($execution->code), 'code', 'unique', "`type` in ('sprint','stage', 'kanban') and `deleted` = '0'")
            ->checkIF($execution->begin != '', 'begin', 'date')
            ->checkIF($execution->end != '', 'end', 'date')
            ->checkIF($execution->end != '', 'end', 'ge', $execution->begin)
            ->checkFlow()
            ->exec();

        /* Add the creator to the team. */
        if(dao::isError()) return false;

        $executionID = $this->dao->lastInsertId();
        $project     = $this->loadModel('project')->fetchByID($execution->project);
        if(empty($project) || $project->model != 'kanban') $this->loadModel('kanban')->createExecutionLane($executionID);

        /* Api create infinites stages. */
        if(isset($execution->parent) && ($execution->parent != $execution->project) && $execution->type == 'stage')
        {
            $parent = $this->fetchByID($execution->parent);
            $grade  = $parent->grade + 1;
            $path   = rtrim($parent->path, ',') . ",{$executionID}";
            $this->dao->update(TABLE_EXECUTION)->set('path')->eq($path)->set('grade')->eq($grade)->where('id')->eq($executionID)->exec();
        }

        /* Save order. */
        $this->dao->update(TABLE_EXECUTION)->set('`order`')->eq($executionID * 5)->where('id')->eq($executionID)->exec();
        $this->loadModel('file')->updateObjectID($this->post->uid, $executionID, 'execution');

        /* Update the path. */
        $this->setTreePath($executionID);

        $this->executionTao->addExecutionMembers($executionID, $postMembers);
        $this->executionTao->createMainLib($execution->project, $executionID, $execution->type);

        $this->loadModel('personnel')->updateWhitelist(explode(',', $execution->whitelist), 'execution', $executionID);
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
     * @param  object $formData
     * @access public
     * @return array|false
     */
    public function update(int $executionID, object $postData, object $formData = null): array|false
    {
        $oldExecution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();

        /* Judgment of required items, such as execution code name. */
        if($oldExecution->type != 'stage' && empty($postData->code) && isset($this->config->setCode) && $this->config->setCode == 1)
        {
            /* $this->config->setCode is the value get from database with system owner and common module. */
            dao::$errors['code'] = sprintf($this->lang->error->notempty, $this->lang->execution->code);
            return false;
        }

        /* Judge workdays is legitimate. */
        $this->app->loadLang('project');
        $workdays = helper::diffDate($postData->end, $postData->begin) + 1;
        if(isset($postData->days) and $postData->days > $workdays)
        {
            dao::$errors['days'] = sprintf($this->lang->project->workdaysExceed, $workdays);
            return false;
        }

        if(!empty($formData->products))
        {
            $multipleProducts = $this->loadModel('product')->getMultiBranchPairs();
            if(isset($formData->branch) and !empty($formData->branch)) $formData->branch = is_array($formData->branch) ? $formData->branch : json_decode($formData->branch, true);
            foreach($formData->products as $index => $productID)
            {
                if(!isset($formData->branch[$index])) continue;
                $branches = is_array($formData->branch[$index]) ? implode(',', $formData->branch[$index]) : $formData->branch[$index];
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
        if(empty($postData->project) || $postData->project == $oldExecution->project) $this->checkBeginAndEndDate($oldExecution->project, $postData->begin, $postData->end, $parentExecution->parent);
        if(dao::isError()) return false;

        /* Child stage inherits parent stage permissions. */
        if(!isset($postData->acl)) $postData->acl = $oldExecution->acl;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->edit['id'], $postData->uid);

        /* Check the workload format and total, such as check Workload Ratio if it enabled. */
        if(!empty($execution->percent) and isset($this->config->setPercent) and $this->config->setPercent == 1) $this->checkWorkload('update', $execution->percent, $oldExecution);

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
            ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage', 'kanban') and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->limit(1)
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
            $projectProductList     = $this->product->getProducts($execution->project);
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
                $this->changeProject($execution->project, $oldExecution->project, $executionID, $postData->syncStories ?? 'no');
            }

            $this->file->updateObjectID($postData->uid, $executionID, 'execution');
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

        $executions    = array();
        $allChanges    = array();
        $oldExecutions = $this->getByIdList($postData->id);
        $nameList      = array();
        $codeList      = array();

        $parents = array();
        foreach($oldExecutions as $oldExecution) $parents[$oldExecution->id] = $oldExecution->parent;

        /* Replace required language. */
        if($this->app->tab == 'project')
        {
            $projectModel = $this->dao->select('model')->from(TABLE_PROJECT)->where('id')->eq($this->session->project)->fetch('model');
            if(empty($this->session->project) || $projectModel == 'scrum')
            {
                $this->lang->project->name = $this->lang->execution->name;
                $this->lang->project->code = $this->lang->execution->code;
            }
            else
            {
                $this->lang->project->name = str_replace($this->lang->project->common, $this->lang->project->stage, $this->lang->project->name);
                $this->lang->project->code = str_replace($this->lang->project->common, $this->lang->project->stage, $this->lang->project->code);
            }
        }
        else
        {
            $this->lang->project->name = $this->lang->execution->name;
            $this->lang->project->code = $this->lang->execution->code;
        }

        $this->lang->error->unique = $this->lang->error->repeat;
        $extendFields = $this->getFlowExtendFields();
        foreach($postData->id as $executionID)
        {
            $executionName = $postData->name[$executionID];
            if(isset($postData->code)) $executionCode = $postData->code[$executionID];

            $executionID = (int)$executionID;
            $executions[$executionID] = new stdClass();
            $executions[$executionID]->id             = $executionID;
            $executions[$executionID]->name           = $executionName;
            $executions[$executionID]->PM             = $postData->PM[$executionID];
            $executions[$executionID]->PO             = $postData->PO[$executionID];
            $executions[$executionID]->QD             = $postData->QD[$executionID];
            $executions[$executionID]->RD             = $postData->RD[$executionID];
            $executions[$executionID]->begin          = $postData->begin[$executionID];
            $executions[$executionID]->end            = $postData->end[$executionID];
            $executions[$executionID]->team           = $postData->team[$executionID];
            $executions[$executionID]->desc           = htmlspecialchars_decode($postData->desc[$executionID]);
            $executions[$executionID]->days           = $postData->days[$executionID];
            $executions[$executionID]->lastEditedBy   = $this->app->user->account;
            $executions[$executionID]->lastEditedDate = helper::now();

            if(isset($postData->code))    $executions[$executionID]->code    = $executionCode;
            if(isset($postData->project)) $executions[$executionID]->project = zget($postData->project, $executionID, 0);
            if(isset($postData->attribute[$executionID])) $executions[$executionID]->attribute = zget($postData->attribute, $executionID, '');
            if(isset($postData->lifetime[$executionID]))  $executions[$executionID]->lifetime  = $postData->lifetime[$executionID];

            $oldExecution = $oldExecutions[$executionID];
            $projectID    = isset($executions[$executionID]->project) ? $executions[$executionID]->project : $oldExecution->project;
            $project      = $this->project->getByID($projectID);

            /* Check unique code for edited executions. */
            if(isset($postData->code) and empty($executionCode))
            {
                dao::$errors["code$executionID"][] = sprintf($this->lang->error->notempty, $this->lang->execution->execCode);
            }
            elseif(isset($postData->code) and $executionCode)
            {
                if(isset($codeList[$executionCode]))
                {
                    dao::$errors["code$executionID"][] = sprintf($this->lang->error->unique, $this->lang->execution->execCode, $executionCode);
                }
                $codeList[$executionCode] = $executionCode;
            }

            /* Name check. */
            $parentID = $parents[$executionID];
            if(isset($nameList[$executionName]))
            {
                foreach($nameList[$executionName] as $repeatID)
                {
                    if($parentID == $parents[$repeatID])
                    {
                        $type = $oldExecution->type == 'stage' ? 'stage' : 'agileplus';
                        dao::$errors["name$executionID"][] = sprintf($this->lang->execution->errorNameRepeat, strtolower(zget($this->lang->programplan->typeList, $type)));
                        break;
                    }
                }
            }

            $nameList[$executionName][] = $executionID;

            /* Attribute check. */
            if(isset($postData->attribute) && isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus')))
            {
                $this->app->loadLang('stage');
                $attribute = $executions[$executionID]->attribute;

                if(isset($attributeList[$parentID]))
                {
                    $parentAttr = $attributeList[$parentID];
                }
                else
                {
                    $parentAttr = $this->dao->select('attribute')->from(TABLE_PROJECT)->where('id')->eq($parentID)->fetch('attribute');
                }

                if($parentAttr && $parentAttr != $attribute && $parentAttr != 'mix')
                {
                    $parentAttr = zget($this->lang->stage->typeList, $parentAttr);
                    dao::$errors["attribute$executionID"][] = sprintf($this->lang->execution->errorAttrMatch, $parentAttr);
                }

                $attributeList[$executionID] = $attribute;
            }

            /* Judge workdays is legitimate. */
            $workdays = helper::diffDate($postData->end[$executionID], $postData->begin[$executionID]) + 1;
            if(isset($postData->days[$executionID]) and $postData->days[$executionID] > $workdays)
            {
                $this->app->loadLang('project');
                dao::$errors["days{$executionID}"][] = sprintf($this->lang->project->workdaysExceed, $workdays);
            }

            /* Parent stage begin and end check. */
            if(isset($executions[$parentID]))
            {
                $begin       = $executions[$executionID]->begin;
                $end         = $executions[$executionID]->end;
                $parentBegin = $executions[$parentID]->begin;
                $parentEnd   = $executions[$parentID]->end;

                if($begin < $parentBegin)
                {
                    dao::$errors["begin$executionID"][] = sprintf($this->lang->execution->errorLesserParent, $parentBegin);
                }

                if($end > $parentEnd)
                {
                    dao::$errors["end$executionID"][] = sprintf($this->lang->execution->errorGreaterParent, $parentEnd);
                }
            }

            foreach($extendFields as $extendField)
            {
                $executions[$executionID]->{$extendField->field} = $postData->{$extendField->field}[$executionID];
                if(is_array($executions[$executionID]->{$extendField->field})) $executions[$executionID]->{$extendField->field} = implode(',', $executions[$executionID]->{$extendField->field});

                $executions[$executionID]->{$extendField->field} = htmlSpecialString($executions[$executionID]->{$extendField->field});
            }

            if(empty($executions[$executionID]->begin)) dao::$errors["begin{$executionID}"][] = sprintf($this->lang->error->notempty, $this->lang->execution->begin);
            if(empty($executions[$executionID]->end))   dao::$errors["end{$executionID}"][]   = sprintf($this->lang->error->notempty, $this->lang->execution->end);

            /* Project begin and end check. */
            if(!empty($executions[$executionID]->begin) and !empty($executions[$executionID]->end))
            {
                if($executions[$executionID]->begin > $executions[$executionID]->end)
                {
                    dao::$errors["end{$executionID}"][] = sprintf($this->lang->execution->errorLesserPlan, $executions[$executionID]->end, $executions[$executionID]->begin);
                }

                if($project and $executions[$executionID]->begin < $project->begin)
                {
                    dao::$errors["begin{$executionID}"][] = sprintf($this->lang->execution->errorLesserProject, $project->begin);
                }
                if($project and $executions[$executionID]->end > $project->end)
                {
                    dao::$errors["end{$executionID}"][] = sprintf($this->lang->execution->errorGreaterProject, $project->end);
                }
            }
        }

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
            $team         = $this->loadModel('user')->getTeamMemberPairs($executionID, 'execution');
            $projectID    = isset($execution->project) ? $execution->project : $oldExecution->project;

            if(isset($execution->project))
            {
                $executionProductList   = $this->loadModel('product')->getProducts($executionID);
                $projectProductList     = $this->product->getProducts($execution->project);
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
                ->checkIF(!empty($execution->code), 'code', 'unique', "id != $executionID and type in ('sprint','stage','kanban') and `deleted` = '0'")
                ->checkFlow()
                ->where('id')->eq($executionID)
                ->limit(1)
                ->exec();

            if(dao::isError()) return false;

            if(!empty($execution->project) and $oldExecution->project != $execution->project)
            {
                $execution->parent = $execution->project;
                $execution->path   = ",{$execution->project},{$executionID},";
                $this->changeProject($execution->project, $oldExecution->project, $executionID, isset($postData->syncStories[$executionID]) ? $postData->syncStories[$executionID] : 'no');
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
     * @return string    返回不符合条件被过滤了的执行，来提示执行下任务或子阶段已经开始，无法修改，已过滤。参见 story#41875。
     */
    public function batchChangeStatus(array $executionIdList, string $status): string
    {
        /* Sort the IDs, the child stage comes first, and the parent stage follows. */
        $executionIdList = $this->dao->select('id')->from(TABLE_EXECUTION)->where('id')->in($executionIdList)->orderBy('grade_desc')->fetchPairs();

        $this->loadModel('programplan');
        $filteredStages = '';
        foreach($executionIdList as $executionID)
        {
            /* The state of the parent stage or the sibling stage may be affected by the child stage before the change, so it cannot be checked in advance. */
            $selfAndChildrenList = $this->programplan->getSelfAndChildrenList($executionID);
            $selfAndChildren     = $selfAndChildrenList[$executionID];
            $execution           = $selfAndChildren[$executionID];

            if($status == 'wait' and $execution->status != 'wait')
            {
                $filteredStages .= $this->changeStatus2Wait($executionID, $selfAndChildren);
            }

            if($status == 'doing' and $execution->status != 'doing')
            {
                $this->changeStatus2Doing($executionID, $selfAndChildren);
            }

            if(($status == 'suspended' and $execution->status != 'suspended') or ($status == 'closed' and $execution->status != 'closed'))
            {
                $filteredStages .= $this->changeStatus2Inactive($executionID, $status, $selfAndChildren);
            }
        }

        return trim($filteredStages, ',');
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
        $oldExecution = $this->getById($executionID);

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->start['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->check($this->config->execution->start->requiredFields, 'notempty')
            ->checkIF($execution->realBegan != '', 'realBegan', 'le', helper::today())
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() and count(dao::$errors['realBegan']) > 1) dao::$errors['realBegan'] = dao::$errors['realBegan'][0];
        if(dao::isError()) return false;

        /* Record the end date as firstEnd when the project is started. */
        $this->loadModel('project')->recordFirstEnd($executionID);

        $changes = common::createChanges($oldExecution, $execution);

        if($postData->comment != '' || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Started', $postData->comment);
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
        $oldExecution = $this->getById($executionID);

        $this->checkBeginAndEndDate($oldExecution->project, $postData->begin, $postData->end);
        if(dao::isError()) return false;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->putoff['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment,delta')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if($postData->comment != '' || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Delayed', $postData->comment);
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
        $oldExecution = $this->getById($executionID);

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->suspend['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if(!empty($changes) || $postData->comment != '')
        {
            $actionID = $this->loadModel('action')->create('execution', $executionID, 'Suspended', $postData->comment);
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
        $oldExecution = $this->getById($executionID);

        if(empty($oldExecution->totalConsumed) and helper::isZeroDate($oldExecution->realBegan)) $postData->status = 'wait';

        /* Check the date which user input. */
        $begin = $postData->begin;
        $end   = $postData->end;
        if($begin > $end) dao::$errors['end'] = sprintf($this->lang->execution->errorLesserPlan, $end, $begin); /* The begin date should larger than end. */

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
        if($postData->comment != '' or !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Activated', $postData->comment);
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
     * @return array|false
     */
    public function close(int $executionID, object $postData): array|false
    {
        $oldExecution = $this->getById($executionID); /* Save previous execution to variable for later compare. */

        $this->lang->error->ge = $this->lang->execution->ge;

        $execution = $this->loadModel('file')->processImgURL($postData, $this->config->execution->editor->close['id'], $postData->uid);
        $this->dao->update(TABLE_EXECUTION)->data($execution, 'comment')
            ->autoCheck()
            ->check($this->config->execution->close->requiredFields,'notempty')
            ->checkIF($execution->realEnd != '', 'realEnd', 'le', helper::today())
            ->checkIF($execution->realEnd != '', 'realEnd', 'ge', $oldExecution->realBegan)
            ->checkFlow()
            ->where('id')->eq($executionID)
            ->exec();

        /* When it has multiple errors, only the first one is prompted */
        if(dao::isError() && count(dao::$errors['realEnd']) > 1) dao::$errors['realEnd'] = dao::$errors['realEnd'][0];
        if(dao::isError()) return false;

        $changes = common::createChanges($oldExecution, $execution);
        if($postData->comment != '' || !empty($changes))
        {
            $this->loadModel('action');
            $actionID = $this->action->create('execution', $executionID, 'Closed', $postData->comment);
            $this->action->logHistory($actionID, $changes);
        }

        $this->loadModel('score')->create('execution', 'close', $oldExecution);
        return $changes;
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
    public function checkWorkload(string $type = '', float $percent = 0, object $oldExecution = null): bool
    {
        /* Check whether the workload is positive. */
        if(!preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $percent))
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
        $project = $this->loadModel('project')->getByID($projectID);
        if(empty($project)) return;

        if($begin < $project->begin) dao::$errors['begin'] = sprintf($this->lang->execution->errorCommonBegin, $project->begin);
        if($end > $project->end)     dao::$errors['end']   = sprintf($this->lang->execution->errorCommonEnd, $project->end);
        if(($project->model == 'waterfall' || $project->model == 'waterfallplus') && $parentID != $projectID)
        {
            $this->app->loadLang('programplan');
            $parent = $this->getByID($parentID);
            if($parent && $begin < $parent->begin) dao::$errors['begin'] = sprintf($this->lang->programplan->error->letterParent, $parent->begin);
            if($parent && $end > $parent->end)     dao::$errors['end']   = sprintf($this->lang->programplan->error->greaterParent, $parent->end);
        }
    }

    /**
     * 获取执行id:name的键值对。
     * Get execution pairs.
     *
     * @param  int    $projectID
     * @param  string $type      all|sprint|stage|kanban
     * @param  string $mode      all|noclosed|stagefilter|withdelete|multiple|leaf|order_asc|empty|noprefix|withobject|hideMultiple
     * @access public
     * @return array
     */
    public function getPairs(int $projectID = 0, string $type = 'all', string $mode = ''): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getExecutionPairs();

        $mode   .= $this->cookie->executionMode;
        $orderBy = $this->config->execution->orderBy;
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
            ->where('vision')->eq($this->config->vision)
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

        return $this->executionTao->buildExecutionPairs($mode, $allExecutions, $executions, $parents, $projectPairs);
    }

    /**
     * 根据执行ID列表获取执行列表信息。
     * Get the execution list information by the execution ID list.
     *
     * @param  array  $executionIdList
     * @param  string $mode           all
     * @access public
     * @return array
     */
    public function getByIdList(array $executionIdList = array(), string $mode = ''): array
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('id')->in($executionIdList)
            ->beginIF($mode != 'all')->andWhere('deleted')->eq(0)->fi()
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
    public function getList(int $projectID = 0, string $type = 'all',string  $status = 'all', int $limit = 0, int $productID = 0, int $branch = 0, object|null $pager = null, bool $withChildren = true)
    {
        if($status == 'involved') return $this->getInvolvedExecutionList($projectID, $status, $limit, $productID, $branch);

        if($productID != 0)
        {
            return $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project= t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->beginIF($type == 'all')->andWhere('t2.type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('t2.type')->eq($type)->fi()
                ->beginIF($status == 'undone')->andWhere('t2.status')->notIN('done,closed')->fi()
                ->beginIF($branch)->andWhere('t1.branch')->eq($branch)->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
                ->beginIF(!$this->app->user->admin and isset($this->app->user->view))->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
                ->beginIF(!$withChildren)->andWhere('grade')->eq(1)->fi()
                ->orderBy('order_desc')
                ->page($pager)
                ->beginIF($limit)->limit($limit)->fi()
                ->fetchAll('id');
        }
        else
        {
            return $this->dao->select("*, IF(INSTR(' done,closed', status) < 2, 0, 1) AS isDone")->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('vision')->eq($this->config->vision)
                ->beginIF($type == 'all')->andWhere('type')->in('sprint,stage,kanban')->fi()
                ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
                ->beginIF($status == 'undone')->andWhere('status')->notIN('done,closed')->fi()
                ->beginIF($status != 'all' and $status != 'undone')->andWhere('status')->in($status)->fi()
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
        $executions = $this->dao->select('t1.*,t2.name projectName, t2.model as projectModel')->from(TABLE_EXECUTION)->alias('t1')
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
    public function getStatData(int $projectID = 0, string $browseType = 'undone', int $productID = 0, int $branch = 0, bool $withTasks = false, string|int $param = '', string $orderBy = 'id_asc', object|null $pager = null): array
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
    public function fetchExecutionList(int $projectID = 0, string $browseType = 'undone', int $productID = 0, int $param = 0, string $orderBy = 'id_asc', object|null $pager = null): array
    {
        /* Construct the query SQL at search executions. */
        $executionQuery = $browseType == 'bySearch' ? $this->getExecutionQuery($param) : '';

        return $this->dao->select('t1.*,t2.name projectName, t2.model as projectModel')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->beginIF($productID)->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t1.id=t3.project')->fi()
            ->where('t1.type')->in('sprint,stage,kanban')
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.multiple')->eq('1')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(!empty($executionQuery))->andWhere($executionQuery)->fi()
            ->beginIF($productID)->andWhere('t3.product')->eq($productID)->fi()
            ->beginIF($projectID)->andWhere('t1.project')->eq($projectID)->fi()
            ->beginIF(!in_array($browseType, array('all', 'undone', 'involved', 'review', 'bySearch')))->andWhere('t1.status')->eq($browseType)->fi()
            ->beginIF($browseType == 'undone')->andWhere('t1.status')->notIN('done,closed')->fi()
            ->beginIF($browseType == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');
    }

    /**
     * 批量处理执行数据。
     * Batch process execution data.
     *
     * @param  array      $executions
     * @param  int        $projectID
     * @param  int        $productID
     * @param  bool       $withTasks
     * @param  string|int $param     skipParent|hasParentName
     * @access public
     * @return array
     */
    public function batchProcessExecution(array $executions, int $projectID = 0, int $productID = 0, bool $withTasks = false, string|int $param = ''): array
    {
        if(empty($executions)) return $executions;

        $productList = $this->executionTao->getProductList($projectID); // Get product name of the linked execution.

        if($withTasks) $executionTasks = $this->getTaskGroupByExecution(array_keys($executions));

        $parentList = array();
        $today      = helper::today();
        $burns      = $this->getBurnData($executions);
        foreach($executions as $execution)
        {
            $execution->productName = isset($productList[$execution->id]) ? trim($productList[$execution->id]->productName, ',') : '';
            if($execution->end) $execution->end = date(DT_DATE1, strtotime($execution->end));

            if(isset($executions[$execution->parent])) $executions[$execution->parent]->isParent = 1;
            if(empty($productID) && !empty($productList[$execution->id])) $execution->product = trim($productList[$execution->id]->product, ',');

            /* Judge whether the execution is delayed. */
            if($execution->status != 'done' && $execution->status != 'closed' && $execution->status != 'suspended')
            {
                $delay = helper::diffDate($today, $execution->end);
                if($delay > 0) $execution->delay = $delay;
            }

            /* Process the burns. */
            $execution->burns = array();
            $burnData = isset($burns[$execution->id]) ? $burns[$execution->id] : array();
            foreach($burnData as $data) $execution->burns[] = $data->value;

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

        $project    = $this->loadModel('project')->getByID($projectID);
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

        if(isset($project->model) && in_array($project->model, array('waterfall', 'waterfallplus')))
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
     * @access public
     * @return array
     */
    public function getChildExecutions(int $executionID, string $orderBy = 'id_desc'): array
    {
        return $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->eq((int)$executionID)
            ->orderBy($orderBy)
            ->fetchAll('id');
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

        /* Get all teams of all executions and group by executions, save it as static. */
        $executions = $this->dao->select('root, limited')->from(TABLE_TEAM)
            ->where('type')->eq('execution')
            ->andWhere('account')->eq($this->app->user->account)
            ->andWhere('limited')->eq('yes')
            ->orderBy('root asc')
            ->fetchPairs('root', 'root');

        $this->session->set('limitedExecutions', implode(',', $executions));
        return $this->session->limitedExecutions;
    }

    /**
     * 根据产品/执行等信息获取任务列表
     * Get tasks by product/execution etc.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  array  $executions
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getTasks(int $productID, int $executionID, array $executions, string $browseType, int $queryID, int $moduleID, string $sort, object $pager = null): array
    {
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
            if(strpos($taskQuery, "`execution` =") === false) $taskQuery .= " AND `execution` = $executionID";
            $executionQuery = "`execution` " . helper::dbIN(array_keys($executions));
            $taskQuery      = str_replace("`execution` = 'all'", $executionQuery, $taskQuery); // Search all execution.
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
     * @access public
     * @return array
     */
    public function getTaskGroupByExecution(array $executionIdList = array()): array
    {
        if(empty($executionIdList)) return array();

        $executionTasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->notin('closed,cancel')
            ->andWhere('execution')->in($executionIdList)
            ->orderBy('order_asc')
            ->fetchGroup('execution', 'id');

        $taskIdList = array();
        foreach($executionTasks as $tasks) $taskIdList = array_merge($taskIdList, array_keys($tasks));
        $taskIdList = array_unique($taskIdList);
        $teamGroups = $this->dao->select('id,task,account,status')->from(TABLE_TASKTEAM)->where('task')->in($taskIdList)->fetchGroup('task', 'id');

        foreach($executionTasks as $tasks)
        {
            foreach($tasks as $task)
            {
                if(isset($teamGroups[$task->id])) $task->team = $teamGroups[$task->id];
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
            $delay = helper::diffDate(helper::today(), $execution->end);
            if($delay > 0) $execution->delay = $delay;
        }

        /* Get hours information. */
        $total = $this->dao->select('
            ROUND(SUM(estimate), 2) AS totalEstimate,
            ROUND(SUM(consumed), 2) AS totalConsumed,
            ROUND(SUM(`left`), 2) AS totalLeft')
            ->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->fetch();

        /* Get hours information of the closed and cancel task. */
        $closedTotalLeft = $this->dao->select('ROUND(SUM(`left`), 2) AS totalLeft')->from(TABLE_TASK)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->andWhere('status')->in('closed,cancel')
            ->fetch('totalLeft');

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
        $execution->totalEstimate = round((float)$total->totalEstimate, 1);
        $execution->totalConsumed = round((float)$total->totalConsumed, 1);
        $execution->totalLeft     = round(((float)$total->totalLeft - (float)$closedTotalLeft), 1);

        $execution = $this->loadModel('file')->replaceImgURL($execution, 'desc');
        if($setImgSize) $execution->desc = $this->file->setImgSize($execution->desc);

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
        return $this->getById($build->execution);
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
    public function buildStorySearchForm(array $products, array $branchGroups, array $modules, int $queryID, string $actionURL, string $type = 'executionStory', object $execution = null): void
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
            $planGroup = $this->productplan->getBranchPlanPairs($productID, array(BRANCH_MAIN) + $product->branches, 'unexpired', true);
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

        $this->config->product->search['fields']['title'] = str_replace($this->lang->SRCommon, $this->lang->SRCommon, $this->lang->story->title);
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
            if($project->model != 'kanban') unset($this->config->product->search['fields']['plan']);
        }

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
        $products    = array_filter(zget($postData, 'products', array()));
        $branches    = zget($postData, 'branch', array(0));
        $plans       = zget($postData, 'plans',  array());
        $oldProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchGroup('product', 'branch');
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->exec();
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
                    if($this->app->rawMethod != 'edit') $oldPlan = $oldProduct->plan;
                }

                $data = new stdclass();
                $data->project = $executionID;
                $data->product = (int)$productID;
                $data->branch  = (int)$branchID;
                $data->plan    = isset($plans[$productID]) ? implode(',', $plans[$productID]) : $oldPlan;
                $data->plan    = trim($data->plan, ',');
                $data->plan    = empty($data->plan) ? 0 : ",$data->plan,";

                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
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
        $execution       = $this->getById($toExecution);
        $project         = $this->loadModel('project')->getById($execution->project);
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
            ->andWhere('t1.parent')->lt(1)
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
            if($task->parent < 0) $parents[$task->id] = $task->id;

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
            if($task->parent < 0) $this->dao->update(TABLE_TASK)->data($data)->where('parent')->eq($task->id)->exec();

            $data->status = $task->consumed > 0 ? 'doing' : 'wait';
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
            if(!isset($teamMembers[$account]))
            {
                $role = $this->dao->select('*')->from(TABLE_TEAM)
                    ->where('root')->eq($preExecutionID)
                    ->andWhere('type')->eq('execution')
                    ->andWhere('account')->eq($account)
                    ->fetch();

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

            if(!$bug->confirmed) $this->dao->update(TABLE_BUG)->set('confirmed')->eq(1)->where('id')->eq($bug->id)->exec();
            $this->dao->insert(TABLE_TASK)->data($task)->exec();
            if(dao::isError()) return false;

            $taskID = $this->dao->lastInsertID();

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

        /* Update the team members and whitelist of the project. */
        $addedAccounts = $this->updateProjectUsers($executionID, $newProjectID);
        if($addedAccounts) $this->loadModel('user')->updateUserView($newProjectID, 'project', $addedAccounts);

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
     * @access public
     * @return bool
     */
    public function linkStory(int $executionID, array $stories = array(), string $extra = '', array $lanes = array()): bool
    {
        if(empty($executionID) || empty($stories)) return false;

        $extra = str_replace(array(',', ' '), array('&', ''), $extra);
        parse_str($extra, $output);

        $this->loadModel('action');
        $this->loadModel('kanban');
        $versions         = $this->loadModel('story')->getVersions($stories);
        $linkedStories    = $this->dao->select('story,`order`')->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->orderBy('order_desc')->fetchPairs('story', 'order');
        $lastOrder        = (int)reset($linkedStories);
        $storyList        = $this->dao->select('id, status, branch, product')->from(TABLE_STORY)->where('id')->in(array_values($stories))->fetchAll('id');
        $execution        = $this->fetchByID($executionID);
        $notAllowedStatus = $this->app->rawMethod == 'batchcreate' ? 'closed' : 'draft,reviewing,closed';
        $laneID           = isset($output['laneID']) ? $output['laneID'] : 0;

        foreach($stories as $storyID)
        {
            if(isset($linkedStories[$storyID])) continue;
            if(strpos($notAllowedStatus, $storyList[$storyID]->status) !== false) continue;

            $storyID = (int)$storyID;
            $story   = zget($storyList, $storyID, '');
            if(empty($story)) continue;
            if(!empty($lanes[$storyID])) $laneID = $lanes[$storyID];

            $columnID = $this->kanban->getColumnIDByLaneID($laneID, 'backlog');
            if(empty($columnID)) $columnID = isset($output['columnID']) ? $output['columnID'] : 0;
            if(!empty($laneID) and !empty($columnID)) $this->kanban->addKanbanCell($executionID, $laneID, $columnID, 'story', $storyID);

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

        if(!isset($output['laneID']) or !isset($output['columnID'])) $this->kanban->updateLane($executionID, 'story');
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
        $projectID = $this->dao->select('project')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch('project');
        $this->session->set('project', $projectID);

        $this->loadModel('story');
        $executionProducts = $this->loadModel('project')->getBranchesByProject($executionID);
        foreach($plans as $productID => $planIdList)
        {
            if(empty($planIdList)) continue;

            $planIdList        = array_filter(explode(',', $planIdList));
            $executionBranches = zget($executionProducts, $productID, array());
            foreach($planIdList as $planID)
            {
                $planStories = $this->story->getPlanStories($planID);
                if(empty($planStories)) continue;

                foreach($planStories as $id => $story)
                {
                    if($story->status != 'active' || (!empty($story->branch) && !empty($executionBranches) && !isset($executionBranches[$story->branch]))) unset($planStories[$id]);
                }
                $stories = array_merge($stories, array_keys($planStories));
            }
        }

        $this->linkStory($projectID, $stories);
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
        $execution = $this->dao->findById($executionID)->from(TABLE_EXECUTION)->fetch();
        if($execution->type == 'project')
        {
            $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($executionID)->fetchAll('id');
            $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq($storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
            if(!empty($executionStories)) return dao::$errors[] = $this->lang->execution->notAllowedUnlinkStory;
        }
        $this->dao->delete()->from(TABLE_PROJECTSTORY)->where('project')->eq($executionID)->andWhere('story')->eq($storyID)->limit(1)->exec();

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

            $task->status       = 'cancel';
            $task->assignedTo   = $task->openedBy;
            $task->assignedDate = $now;
            $task->canceledBy   = $task->lastEditedBy = $this->app->user->account;
            $task->canceledDate = $task->lastEditedDate = $now;
            $task->finishedBy   = '';
            $task->finishedDate = null;

            if(!$task->closedDate)    unset($task->closedDate);
            if(!$task->activatedDate) unset($task->activatedDate);

            $this->loadModel('task')->cancel($task);
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
                $this->dao->delete()->from(TABLE_PROJECTCASE)->where('project')->eq($execution->project)->andWhere('`case`')->eq($caseID)->limit(1)->exec();
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

        $countPairs = $this->dao->select('root, COUNT(*) as count')->from(TABLE_TEAM)
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

            $member->join = isset($oldJoin[$member->account]) ? $oldJoin[$member->account] : helper::today();
            $executionMember[$member->account] = $member;
            $accountList[] = $member->account;

            $this->dao->insert(TABLE_TEAM)->data($member)->exec();
        }

        /* Only changed account update userview. */
        $oldAccountList     = array_keys($oldJoin);
        $changedAccountList = array_diff($accountList, $oldAccountList);
        $changedAccountList = array_merge($changedAccountList, array_diff($oldAccountList, $accountList));
        $changedAccountList = array_unique($changedAccountList);

        /* Add the execution team members to the project. */
        if($execution->project) $this->addProjectMembers($execution->project, $executionMember);
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
            $this->loadModel('user')->updateUserView($projectID, $projectType, $changedAccountList);
            $linkedProducts = $this->dao->select("t2.id")->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t1.project')->eq($projectID)
                ->andWhere('t2.vision')->eq($this->config->vision)
                ->fetchPairs();

            if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', $changedAccountList);
        }
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

        /* Remove team members from the sprint or stage, and determine whether to remove team members from the project. */
        if(strpos(',stage,sprint,kanban,', ",$execution->type,") !== false)
        {
            $teamMember = $this->dao->select('t1.id, t2.account')->from(TABLE_EXECUTION)->alias('t1')
                ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id = t2.root')
                ->where('t1.project')->eq($execution->project)
                ->andWhere('t1.type')->eq($execution->type)
                ->andWhere('t2.account')->eq($account)
                ->fetch();

            /* Remove the user from the project team members and update the user's product permission. */
            if(empty($teamMember))
            {
                $this->dao->delete()->from(TABLE_TEAM)->where('root')->eq($execution->project)->andWhere('type')->eq('project')->andWhere('account')->eq($account)->exec();
                $this->loadModel('user')->updateUserView($execution->project, 'project', array($account));

                $linkedProducts = $this->loadModel('product')->getProductPairsByProject($execution->project);
                if(!empty($linkedProducts)) $this->user->updateUserView(array_keys($linkedProducts), 'product', array($account));
            }
        }
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
     * @access public
     * @return array
     */
    public function getSearchTasks(string $condition, string $orderBy, object $pager = null): array
    {
        if(strpos($condition, '`assignedTo`') !== false)
        {
            preg_match("/`assignedTo`\s+(([^']*) ('([^']*)'))/", $condition, $matches);
            $condition = preg_replace('/`(\w+)`/', 't1.`$1`', $condition);
            $condition = str_replace("t1.$matches[0]", "(t1.$matches[0] or (t1.mode = 'multi' and t2.`account` $matches[1] and t1.status != 'closed' and t2.status != 'done') )", $condition);
        }

        $sql = $this->dao->select('t1.id')->from(TABLE_TASK)->alias('t1');
        if(strpos($condition, '`assignedTo`') !== false) $sql = $sql->leftJoin(TABLE_TASKTEAM)->alias('t2')->on("t2.task = t1.id and t2.account $matches[1]");

        $orderBy = array_map(function($value){return 't1.' . $value;}, explode(',', $orderBy));
        $orderBy = implode(',', $orderBy);

        $taskIdList = $sql->where($condition)
            ->andWhere('deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $orderBy = str_replace(array('t1.pri_', 't1.`pri'), array('priOrder_', '`priOrder_'), $orderBy);
        $tasks   = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.product, t2.branch, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName, IF(t1.`pri` = 0, 999, t1.`pri`) as priOrder')
             ->from(TABLE_TASK)->alias('t1')
             ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
             ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
             ->where('t1.deleted')->eq(0)
             ->andWhere('t1.id')->in(array_keys($taskIdList))
             ->orderBy($orderBy)
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

        $parents = array();
        foreach($tasks as $task)
        {
            if($task->parent > 0) $parents[$task->parent] = $task->parent;
        }
        $parents = $this->dao->select('*')->from(TABLE_TASK)->where('id')->in($parents)->fetchAll('id');

        if($this->config->vision == 'lite') $tasks = $this->loadModel('task')->appendLane($tasks);
        foreach($tasks as $task)
        {
            if($task->parent > 0)
            {
                if(isset($tasks[$task->parent]))
                {
                    $tasks[$task->parent]->children[$task->id] = $task;
                    unset($tasks[$task->id]);
                }
                else
                {
                    $parent = $parents[$task->parent];
                    $task->parentName = $parent->name;
                }
            }
        }
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
            if(!isset($tasks[$task->parent]) or $task->parent <= 0)
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
     * @param  object    $execution
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $execution, string $action): bool
    {
        if(!commonModel::hasPriv('execution', $action)) return false;

        $action = strtolower($action);
        if($action == 'start')    return $execution->status == 'wait';
        if($action == 'close')    return $execution->status != 'closed';
        if($action == 'suspend')  return $execution->status == 'wait' || $execution->status == 'doing';
        if($action == 'putoff')   return $execution->status == 'wait' || $execution->status == 'doing';
        if($action == 'activate') return $execution->status == 'suspended' || $execution->status == 'closed';

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

        $this->config->bug->search['module']    = $type == 'execution' ? 'executionBug' : 'projectBug';
        $this->config->bug->search['actionURL'] = $actionURL;
        $this->config->bug->search['queryID']   = $queryID;

        $this->config->bug->search['params']['plan']['values']          = $this->loadModel('productplan')->getForProducts(array_keys($products));
        $this->config->bug->search['params']['module']['values']        = $modules;
        $this->config->bug->search['params']['openedBuild']['values']   = $builds;
        $this->config->bug->search['params']['resolvedBuild']['values'] = $this->config->bug->search['params']['openedBuild']['values'];
        if(isset($this->config->bug->search['params']['product'])) $this->config->bug->search['params']['product']['values'] = $productPairs + array('all' => $this->lang->product->allProductsOfProject);

        unset($this->config->bug->search['fields']['execution']);
        unset($this->config->bug->search['params']['execution']);
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
     * @access public
     * @return void
     */
    public function buildCaseSearchForm(array $products, int $queryID, string $actionURL)
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

        $this->loadModel('search')->setSearchParams($this->config->testcase->search);
    }

    /**
     * 构建搜索任务的表单。
     * Build task search form.
     *
     * @param  int    $executionID
     * @param  array  $executions
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildTaskSearchForm(int $executionID, array $executions, int $queryID, string $actionURL)
    {
        $this->config->execution->search['actionURL'] = $actionURL;
        $this->config->execution->search['queryID']   = $queryID;
        $this->config->execution->search['params']['execution']['values'] = array(''=>'', $executionID => $executions[$executionID], 'all' => $this->lang->execution->allExecutions);

        $showAllModule = isset($this->config->execution->task->allModule) ? $this->config->execution->task->allModule : '';
        $this->config->execution->search['params']['module']['values'] = $this->loadModel('tree')->getTaskOptionMenu($executionID, 0, $showAllModule ? 'allModule' : '');

        $this->loadModel('search')->setSearchParams($this->config->execution->search);
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
    public function getKanbanTasks(int $executionID, string $orderBy = 'status_asc, id_desc', array $excludeTasks = array(), object|null $pager = null): array
    {
        $tasks = $this->dao->select('t1.*, t2.id AS storyID, t2.title AS storyTitle, t2.version AS latestStoryVersion, t2.status AS storyStatus, t3.realname AS assignedToRealName')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->leftJoin(TABLE_USER)->alias('t3')->on('t1.assignedTo = t3.account')
            ->where('t1.execution')->eq($executionID)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->ge(0)
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
        static $taskGroups;
        if(empty($taskGroups)) $taskGroups = $this->executionTao->getTaskGroups($executionID);
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
            static $users, $storyGroups;
            if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
            if(empty($storyGroups))
            {
                if($this->config->vision == 'lite') $execution = $this->getById($executionID);
                $stories = $this->loadModel('story')->getListByProject(isset($execution->project) ? $execution->project : $executionID);

                $storyGroups = array();
                foreach($stories as $story) $storyGroups[$story->product][$story->module][$story->id] = $story;
            }

            $node = $this->executionTao->processStoryNode($node, $storyGroups, $taskGroups, $users, $executionID);
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

        $branchQuery .= ')';

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
     * @access pubic
     * @return array
     */
    public function buildTree(array $trees, bool $hasProduct = true): array
    {
        $treeData = array();
        foreach($trees as $index => $tree)
        {
            $tree = (object)$tree;
            $treeData[$index] = array('className' => 'py-2 cursor-pointer ' . $tree->type);
            switch($tree->type)
            {
                case 'task':
                    $label = $tree->parent > 0 ? $this->lang->task->children : $this->lang->task->common;
                    $treeData[$index]['url']    = helper::createLink('execution', 'treeTask', "taskID={$tree->id}");
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'><span class='label gray-pale rounded-full'>{$label}</span><span class='ml-4'>{$tree->id}</span><span class='title ml-4 text-primary' title='{$tree->title}'>" . $tree->title . '</span> <span class="user"><i class="icon icon-person"></i> ' . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . '</span></div>',
                    );
                    break;
                case 'product':
                    $treeData[$index]['content'] = array(
                        'html' => "<span class='label rounded-full p-2' title='{$tree->name}'>{$tree->name}</span>"
                    );
                    break;
                case 'story':
                    $this->app->loadLang('story');
                    $treeData[$index]['url']    = helper::createLink('execution', 'treeStory', "taskID={$tree->storyId}");
                    $treeData[$index]['content'] = array(
                        'html' => "<div class='tree-link'><span class='label gray-pale rounded-full'>{$this->lang->story->common}</span><span class='ml-4'>{$tree->storyId}</span><span class='title text-primary ml-4' title='{$tree->title}'>{$tree->title}</span> <span class='user'><i class='icon icon-person'></i> " . (empty($tree->assignedTo) ? $tree->openedBy : $tree->assignedTo) . "</span></div>",
                    );
                    break;
                case 'branch':
                    $this->app->loadLang('branch');
                    $treeData[$index]['content'] = array(
                        'html' => "<span class='label gray-pale rounded-full'>{$this->lang->branch->common}</span><span class='title ml-4' title='{$tree->name}'>{$tree->name}</span>"
                    );
                    break;
                default:
                    $firstClass = $tree->id == 0 ? 'label rounded-full p-2' : '';
                    $treeData[$index]['content'] = array(
                        'html' => "<span class='{$firstClass} title' title='{$tree->name}'>" . $tree->name . '</span>'
                    );
                    break;
            }
            if(isset($tree->children))
            {
                if($tree->type == 'task') $treeData[$index]['content']['html'] = "<span class='title' title='{$tree->title}'>{$tree->title}</span>";
                $treeData[$index]['items'] = $this->buildTree($tree->children, $hasProduct);
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
        $this->loadModel('user')->updateUserView($executionID, $objectType, $users);

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
        foreach($executions as $execution)
        {
            $execution->rawID         = $execution->id;
            $execution->isExecution   = 1;
            $execution->id            = 'pid' . (string)$execution->id;
            $execution->projectID     = $execution->project;
            $execution->project       = $execution->projectName;
            $execution->parent        = ($execution->parent && $execution->grade > 1) ? 'pid' . (string)$execution->parent : '';
            $execution->isParent      = !empty($execution->isParent) or !empty($execution->tasks);
            $execution->actions       = array();
            foreach($this->config->projectExecution->dtable->fieldList['actions'][$execution->projectModel] as $actionKey)
            {
                $action  = array();
                $actions = explode('|', $actionKey);
                foreach($actions as $actionName)
                {
                    if(!commonModel::hasPriv('execution', $actionName)) continue;
                    $action = array('name' => $actionName, 'disabled' => $this->isClickable($execution, $actionName) ? false : true);
                    if(!$action['disabled']) break;
                }
                if(!empty($action)) $execution->actions[] = $action;
            }

            /* For user's avatar. */
            if($execution->PM)
            {
                $realname = zget($users, $execution->PM);
                if(empty($realname)) continue;

                $execution->PM        = $realname;
                $execution->PMAvatar  = zget($avatarList, $execution->PM);
                $execution->PMAccount = $execution->PM;
            }

            $rows[$execution->id] = $execution;

            /* Append tasks and child stages. */
            if(!empty($execution->tasks)) $rows = $this->appendTasks($execution->tasks, $rows);
        }

        return $rows;
    }

    /**
     * 追加任务列表到执行列表。
     * Append tasks to execution list.
     *
     * @param  array  $tasks
     * @param  array  $rows
     * @access public
     * @return array
     */
    public function appendTasks(array $tasks, array $rows): array
    {
        $this->loadModel('task');

        foreach($tasks as $task)
        {
            foreach($this->config->projectExecution->dtable->fieldList['actions']['task'] as $action)
            {
                $rawAction = str_replace('Task', '', $action);
                if(!commonModel::hasPriv('task', $rawAction)) continue;
                $clickable = $this->task->isClickable($task, $rawAction);
                $action    = array('name' => $action);
                if(!$clickable) $action['disabled'] = true;
                $task->actions[] = $action;
            }

            $task->name          = "<span class='label secondary-pale'>{$this->lang->task->common}</span> " . html::a(helper::createLink('task', 'view', "id={$task->id}"), $task->name);
            $task->rawID         = $task->id;
            $task->id            = 'tid' . (string)$task->id;
            $task->totalEstimate = $task->estimate;
            $task->totalConsumed = $task->consumed;
            $task->totalLeft     = $task->left;
            $task->isParent      = ($task->parent < 0);
            $task->parent        = $task->parent <= 0 ? 'pid' . (string)$task->execution : 'tid' . (string)$task->parent;
            $task->progress      = ($task->consumed + $task->left) == 0 ? 0 : round($task->consumed / ($task->consumed + $task->left), 2) * 100;

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
        $executionData->begin       = $project->begin;
        $executionData->end         = $project->end;
        $executionData->status      = 'wait';
        $executionData->type        = 'sprint';
        $executionData->days        = $project->days;
        $executionData->team        = $project->team;
        $executionData->desc        = $project->desc;
        $executionData->acl         = 'open';
        $executionData->PO          = $this->app->user->account;
        $executionData->QD          = $this->app->user->account;
        $executionData->PM          = $this->app->user->account;
        $executionData->RD          = $this->app->user->account;
        $executionData->multiple    = '0';
        $executionData->whitelist   = '';
        $executionData->plans       = array();
        $executionData->hasProduct  = $project->hasProduct;
        $executionData->openedBy    = $this->app->user->account;
        $executionData->openedDate  = helper::now();
        $executionData->parent      = $projectID;
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
        $postData->code      = '';
        $postData->uid       = '';

        /* Handle extend fields. */
        $extendFields = $this->loadModel('project')->getFlowExtendFields();
        foreach($extendFields as $field) $_POST[$field->field] = $project->field;
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
        $executionID = $this->dao->select('*')->from(TABLE_EXECUTION)->where('project')->eq($projectID)->andWhere('type')->in('sprint,kanban')->andWhere('multiple')->eq(0)->fetch('id');
        if($executionID)
        {
            $this->update($executionID, $postData);
            $this->updateProducts($executionID, (array)$updateProductsData);
        }

        return (int)$executionID;
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
            $execution->closedBy   = '';
            $execution->canceledBy = '';
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
        }
        elseif($status == 'closed')
        {
            $execution->closedBy = $this->app->user->account;
        }

        return $execution;
    }

    /**
     * 给执行列表重新排序。
     * Reset execution orders.
     *
     * @param  array  $executions
     * @param  array  $parentExecutions
     * @access public
     * @return array
     */
    public function resetExecutionSorts(array $executions, array $parentExecutions = array()): array
    {
        if(empty($executions)) return array();
        if(empty($parentExecutions))
        {
            $execution        = current($executions);
            $parentExecutions = $this->dao->select('*')->from(TABLE_EXECUTION)
                ->where('deleted')->eq(0)
                ->andWhere('type')->in('kanban,sprint,stage')
                ->andWhere('grade')->eq(1)
                ->andWhere('project')->eq($execution->project)
                ->orderBy('order_asc')
                ->fetchAll('id');
        }

        $sortedExecutions = array();
        foreach($parentExecutions as $executionID => $execution)
        {
            if(!isset($sortedExecutions[$executionID]) and isset($executions[$executionID])) $sortedExecutions[$executionID] = $executions[$executionID];

            $children = $this->getChildExecutions($executionID, 'order_asc');
            if(!empty($children)) $sortedExecutions += $this->resetExecutionSorts($executions, $children);
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
}
