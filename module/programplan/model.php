<?php
/**
 * The model file of programplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     programplan
 * @version     $Id: model.php 5079 2013-07-10 00:44:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class programplanModel extends model
{
    /**
     * Set menu.
     *
     * @param  int  $projectID
     * @param  int  $productID
     * @access public
     * @return bool
     */
    public function setMenu($projectID, $productID)
    {
        return true;
    }

    /**
     * Get plan by id.
     *
     * @param  int    $planID
     * @access public
     * @return object
     */
    public function getByID($planID)
    {
        $plan = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();

        return $this->processPlan($plan);
    }

    /**
     * Get plans list.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  string  $browseType all|parent
     * @param  string  $orderBy
     * @access public
     * @return array
     */
    public function getStage($projectID = 0, $productID = 0, $browseType = 'all', $orderBy = 'id_asc')
    {
        if(empty($projectID) || empty($productID)) return array();

        $plans = $this->dao->select('t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('stage')
            ->beginIF($browseType == 'all')->andWhere('t2.project')->eq($projectID)->fi()
            ->beginIF($browseType == 'parent')->andWhere('t2.parent')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');

        return $this->processPlans($plans);
    }

    /**
     * Get plans by idList.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByList($idList = array())
    {
        $plans = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in($idList)
            ->andWhere('type')->eq('project')
            ->fetchAll('id');

        return $this->processPlans($plans);
    }

    /**
     * Get plan pairs for budget.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getPlanPairsForBudget($programID = 0)
    {
        $pairs   = array();
        $plans   = $this->getPlans($programID);
        $program = $this->getByID($programID);
        foreach($plans as $planID => $plan)
        {
            $name = $plan->name;
            if($program->product == 'multiple') $name = $plan->productName . '/' . $name;
            $pairs[$planID] = '/'.$name;

            foreach($plan->children as $childID => $child)
            {
                $childName = $name . '/' . $child->name;
                $pairs[$childID] = $childName;
            }
        }

        return $pairs;
    }

    /**
     * Get plans.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getPlans($projectID = 0, $productID = 0, $orderBy = 'id_asc')
    {
        $plans = $this->getStage($projectID, $productID, 'all', $orderBy);

        $parents  = array();
        $children = array();
        foreach($plans as $planID => $plan)
        {
            $plan->grade == 1 ? $parents[$planID] = $plan : $children[$plan->parent][] = $plan;
        }

        foreach($parents as $planID => $plan) $parents[$planID]->children = isset($children[$planID]) ? $children[$planID] : array();

        return $parents;
    }

    /**
     * Get pairs.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairs($projectID, $productID = 0, $type = 'all')
    {
        $plans = $this->getPlans($projectID, $productID);

        $pairs = array(0 => '');
        foreach($plans as $plan)
        {
            $pairs[$plan->id] = $plan->name;
            if(!empty($plan->children))
            {
                foreach($plan->children as $child) $pairs[$child->id] = $plan->name . '/' . $child->name;
            }
        }

        return $pairs;
    }

    /**
     * Get gantt data.
     *
     * @param  int     $projectID
     * @param  int     $productID
     * @param  int     $baselineID
     * @param  string  $selectCustom
     * @param  bool    $returnJson
     * @access public
     * @return string
     */
    public function getDataForGantt($projectID, $productID, $baselineID = 0, $selectCustom = '', $returnJson = true)
    {
        $this->loadModel('stage');

        $plans = $this->getStage($projectID, $productID);
        if($baselineID)
        {
            $baseline = $this->loadModel('cm')->getByID($baselineID);
            $oldData  = json_decode($baseline->data);
            $oldPlans = $oldData->stage;
            foreach($oldPlans as $id => $oldPlan)
            {
                if(!isset($plans[$id])) continue;
                $plans[$id]->version   = $oldPlan->version;
                $plans[$id]->name      = $oldPlan->name;
                $plans[$id]->milestone = $oldPlan->milestone;
                $plans[$id]->begin     = $oldPlan->begin;
                $plans[$id]->end       = $oldPlan->end;
            }
        }

        $datas       = array();
        $planIDList  = array();
        $isMilestone = "<icon class='icon icon-flag icon-sm red'></icon> ";
        $stageIndex  = array();
        foreach($plans as $plan)
        {
            $planIDList[$plan->id] = $plan->id;

            $start = $plan->begin == '0000-00-00' ? '' : date('d-m-Y', strtotime($plan->begin));
            $end   = $plan->end   == '0000-00-00' ? '' : $plan->end;

            $data = new stdclass();
            $data->id         = $plan->id;
            $data->type       = 'plan';
            $data->text       = empty($plan->milestone) ? $plan->name : $isMilestone . $plan->name;
            $data->percent    = $plan->percent;
            $data->attribute  = zget($this->lang->stage->typeList, $plan->attribute);
            $data->milestone  = zget($this->lang->programplan->milestoneList, $plan->milestone);
            $data->start_date = $start;
            $data->deadline   = $end;
            $data->realBegan  = $plan->realBegan == '0000-00-00' ? '' : $plan->realBegan;
            $data->realEnd    = $plan->realEnd == '0000-00-00' ? '' : $plan->realEnd;
            $data->duration   = helper::diffDate($plan->end, $plan->begin) + 1;;
            $data->parent     = $plan->grade == 1 ? 0 :$plan->parent;
            $data->open       = true;

            if($data->start_date == '' or $data->deadline == '') $data->duration = 0;

            $datas['data'][] = $data;
            $stageIndex[]    = array('planID' => $plan->id, 'progress' => array('totalConsumed' => 0, 'totalReal' => 0));
        }

        $taskSign = "<span>[ T ] </span>";
        $taskPri  = "<span class='label-pri label-pri-%s' title='%s'>%s</span> ";

        /* Judge whether to display tasks under the stage. */
        $owner   = $this->app->user->account;
        $module  = 'programplan';
        $section = 'browse';
        $object  = 'stageCustom';

        if(empty($selectCustom)) $selectCustom = $this->loadModel('setting')->getItem("owner={$owner}&module={$module}&section={$section}&key={$object}");

        $tasks = array();
        if(strpos($selectCustom, 'task') !== false)
        {
            $tasks = $this->dao->select('*')->from(TABLE_TASK)->where('deleted')->eq(0)->andWhere('project')->in($planIDList)->fetchAll('id');
        }

        if($baselineID)
        {
            $oldTasks = $oldData->task;
            foreach($oldTasks as $id => $oldTask)
            {
                if(!isset($tasks->$id)) continue;
                $tasks->$id->version    = $oldTask->version;
                $tasks->$id->name       = $oldTask->name;
                $tasks->$id->estStarted = $oldTask->estStarted;
                $tasks->$id->deadline   = $oldTask->deadline;
            }
        }

        foreach($tasks as $task)
        {
            $start = $task->estStarted == '0000-00-00' ? '' : date('d-m-Y', strtotime($task->estStarted));
            $end   = $task->deadline   == '0000-00-00' ? '' : $task->deadline;

            $realBegan = $task->realStarted == '0000-00-00' ? '' : $task->realStarted;
            $realEnd   = $task->finishedDate == '0000-00-00 00:00:00' ? '' : substr($task->finishedDate, 5, 11);
            $priIcon   = sprintf($taskPri, $task->pri, $task->pri, $task->pri);

            $data = new stdclass();
            $data->id           = $task->project . '-' . $task->id;
            $data->type         = 'task';
            $data->text         = $taskSign . $priIcon . $task->name;
            $data->percent      = '';
            $data->attribute    = '';
            $data->milestone    = '';
            $data->start_date   = $start;
            $data->deadline     = $end;
            $data->realBegan    = $realBegan;
            $data->realEnd      = $realEnd;
            $data->duration     = helper::diffDate($task->deadline, $task->estStarted) + 1;
            $data->parent       = $task->parent > 0 ? $task->project . '-' . $task->parent : $task->project;
            $data->open         = true;
            $progress           = $task->consumed ? round($task->consumed / ($task->left + $task->consumed), 3) * 100 : 0;
            $data->taskProgress = $progress . '%';

            if($data->start_date == '' or $data->deadline == '') $data->duration = 0;

            $datas['data'][] = $data;
            foreach($stageIndex as $index => $stage)
            {
                if($stage['planID'] == $task->project)
                {
                    $stageIndex[$index]['progress']['totalConsumed'] += $task->consumed;
                    $stageIndex[$index]['progress']['totalReal']     += ($task->left + $task->consumed);
                }
            }
        }

        /* Calculate the progress of the phase. */
        foreach($stageIndex as $index => $stage)
        {
            $progress  = empty($stage['progress']['totalConsumed']) ? 0 : round($stage['progress']['totalConsumed'] / $stage['progress']['totalReal'], 3) * 100;
            $progress .= '%';
            $datas['data'][$index]->taskProgress = $progress;
        }

        return $returnJson ? json_encode($datas) : $datas;
    }

    /**
     * Get total percent.
     *
     * @param  object  $plan
     * @access public
     * @return int
     */
    public function getTotalPercent($plan, $parent = false)
    {
        /* When parent is equal to true, query the total workload of the subphase. */
        $projectID = $parent ? $plan->id : $plan->project;
        $plans = $this->getStage($projectID, $plan->product, 'parent');

        $totalPercent = 0;
        foreach($plans as $id => $stage)
        {
            if($id == $plan->id) continue;
            $totalPercent += $stage->percent;
        }

        return $totalPercent;
    }

    /**
     * Process plans.
     *
     * @param  int    $plans
     * @access public
     * @return object
     */
    public function processPlans($plans)
    {
        foreach($plans as $planID => $plan) $plans[$planID] = $this->processPlan($plan);
        return $plans;
    }

    /**
     * Process plan.
     *
     * @param  int    $plan
     * @access public
     * @return object
     */
    public function processPlan($plan)
    {
        $plan->setMilestone = true;

        if($plan->parent)
        {
            $attribute = $this->dao->select('attribute')->from(TABLE_PROJECT)->where('id')->eq($plan->parent)->fetch('attribute');
            $plan->attribute = $attribute == 'develop' ? $attribute : $plan->attribute;
        }
        else
        {
            $milestones = $this->dao->select('count(*) AS count')->from(TABLE_PROJECT)
                ->where('parent')->eq($plan->id)
                ->andWhere('milestone')->eq(1)
                ->andWhere('deleted')->eq(0)
                ->fetch('count');
            if($milestones > 0)
            {
                $plan->milestone    = 0;
                $plan->setMilestone = false;
            }
        }

        $plan->begin     = $plan->begin == '0000-00-00' ? '' : $plan->begin;
        $plan->end       = $plan->end  == '0000-00-00' ? '' : $plan->end;
        $plan->realBegan = $plan->realBegan == '0000-00-00' ? '' : $plan->realBegan;
        $plan->realEnd   = $plan->realEnd == '0000-00-00' ? '' : $plan->realEnd;

        $plan->product     = $this->loadModel('product')->getProductIDByProject($plan->id);
        $plan->productName = $this->dao->findByID($plan->product)->from(TABLE_PRODUCT)->fetch('name');

        return $plan;
    }

    /**
     * Get duration.
     *
     * @param  int    $begin
     * @param  int    $end
     * @access public
     * @return int
     */
    public function getDuration($begin, $end)
    {
        $duration = $this->loadModel('holiday')->getActualWorkingDays($begin, $end);
        return count($duration);
    }

    /**
     * Create a plan.
     *
     * @param  int  $projectID
     * @param  int  $productID
     * @param  int  $parentID
     * @access public
     * @return bool
     */
    public function create($projectID = 0, $productID = 0, $parentID = 0)
    {
        $data = (array)fixer::input('post')->get();
        extract($data);

        /* Determine if a task has been created under the parent phase. */
        if(!$this->isCreateTask($parentID)) return dao::$errors['message'][] = $this->lang->programplan->error->createdTask;

        /* The child phase type setting is the same as the parent phase. */
        $parentAttribute = '';
        $parentPercent   = 0;
        if($parentID)
        {
            $parentStage     = $this->getByID($parentID);
            $parentAttribute = $parentStage->attribute;
            $parentPercent   = $parentStage->percent;
            $parentACL       = $parentStage->acl;
        }

        $attributes = array_values($attributes);
        $milestone  = array_values($milestone);
        $datas      = array();
        foreach($names as $key => $name)
        {
            if(empty($name)) continue;

            $plan = new stdclass();
            $plan->id        = isset($planIDList[$key]) ? $planIDList[$key] : '';
            $plan->type      = 'stage';
            $plan->project   = $projectID;
            $plan->parent    = $parentID ? $parentID : $projectID;
            $plan->name      = $names[$key];
            $plan->percent   = $percents[$key];
            $plan->attribute = empty($parentID) ? $attributes[$key] : $parentAttribute;
            $plan->milestone = $milestone[$key];
            $plan->begin     = empty($begin[$key]) ? '0000-00-00' : $begin[$key];
            $plan->end       = empty($end[$key]) ? '0000-00-00' : $end[$key];
            $plan->realBegan = empty($realBegan[$key]) ? '0000-00-00' : $realBegan[$key];
            $plan->realEnd   = empty($realEnd[$key]) ? '0000-00-00' : $realEnd[$key];
            $plan->output    = empty($output[$key]) ? '' : implode(',', $output[$key]);
            $plan->acl       = empty($parentID) ? $acl[$key] : $parentACL;

            $datas[] = $plan;
        }

        $totalPercent = 0;
        $totalDevType = 0;
        $milestone    = 0;
        foreach($datas as $plan)
        {
            if($plan->percent and !preg_match("/^[0-9]+(.[0-9]{1,3})?$/", $plan->percent))
            {
                dao::$errors['message'][] = $this->lang->programplan->error->percentNumber;
                return false;
            }
            if($plan->end != '0000-00-00' and $plan->end < $plan->begin)
            {
                dao::$errors['message'][] = $this->lang->programplan->error->planFinishSmall;
                return false;
            }

            if($plan->begin == '0000-00-00') $plan->begin = '';
            if($plan->end   == '0000-00-00') $plan->end   = '';
            foreach(explode(',', $this->config->programplan->create->requiredFields) as $field)
            {
                $field = trim($field);
                if($field and empty($plan->$field))
                {
                    dao::$errors['message'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->$field);
                    return false;
                }
            }

            $plan->percent = (float)$plan->percent;
            $totalPercent += $plan->percent;

            if($plan->milestone) $milestone = 1;
        }

        /* The sum of the workload of the child phases cannot be greater than that of the parent phase. */
        if($parentID && $totalPercent > $parentPercent)
        {
            dao::$errors['message'][] = sprintf($this->lang->programplan->error->parentWorkload, $parentPercent . '%');
            return false;
        }

        if($totalPercent > 100) return dao::$errors['message'][] = $this->lang->programplan->error->percentOver;

        $this->loadModel('user');
        $this->loadModel('project');
        $this->app->loadLang('doc');
        $account = $this->app->user->account;
        $now     = helper::now();
        foreach($datas as $data)
        {
            /* Set planDuration and realDuration. */
            $data->planDuration = $this->getDuration($data->begin, $data->end);
            $data->realDuration = $this->getDuration($data->realBegan, $data->realEnd);

            $projectChanged = false;
            $data->days     = helper::diffDate($data->end, $data->begin) + 1;
            if($data->id)
            {
                $planID = $data->id;
                unset($data->id);

                $oldPlan     = $this->getByID($planID);
                $planChanged = ($oldPlan->name != $data->name || $oldPlan->milestone != $data->milestone || $oldPlan->begin != $data->begin || $oldPlan->end != $data->end);

                if($planChanged) $data->version = $oldPlan->version + 1;
                $this->dao->update(TABLE_PROJECT)->data($data)
                    ->autoCheck()
                    ->batchCheck($this->config->programplan->edit->requiredFields, 'notempty')
                    ->checkIF($plan->percent != '', 'percent', 'float')
                    ->where('id')->eq($planID)
                    ->exec();

                if($data->acl != 'open') $this->user->updateUserView($planID, 'sprint');

                /* Record version change information. */
                if($planChanged)
                {
                    $spec = new stdclass();
                    $spec->project   = $planID;
                    $spec->version   = $data->version;
                    $spec->name      = $data->name;
                    $spec->milestone = $data->milestone;
                    $spec->begin     = $data->begin;
                    $spec->end       = $data->end;
                    $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();
                }
            }
            else
            {
                unset($data->id);
                $data->status        = 'wait';
                $data->acl           = $data->parent == 0 ? 'open' : $this->dao->findByID($data->parent)->from(TABLE_PROJECT)->fetch('acl');
                $data->version       = 1;
                $data->parentVersion = $data->parent == 0 ? 0 : $this->dao->findByID($data->parent)->from(TABLE_PROJECT)->fetch('version');
                $data->team          = substr($data->name,0, 30);
                $data->openedBy      = $account;
                $data->openedDate    = $now;
                $data->openedVersion = $this->config->version;
                $this->dao->insert(TABLE_PROJECT)->data($data)
                    ->autoCheck()
                    ->batchCheck($this->config->programplan->create->requiredFields, 'notempty')
                    ->checkIF($plan->percent != '', 'percent', 'float')
                    ->exec();

                if(!dao::isError())
                {
                    $stageID = $this->dao->lastInsertID();

                    if($data->acl != 'open') $this->user->updateUserView($stageID, 'sprint');
                    $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($stageID * 5)->where('id')->eq($stageID)->exec();

                    /* Create doc lib. */
                    $lib = new stdclass();
                    $lib->project = $stageID;
                    $lib->name    = $this->lang->doclib->main['project'];
                    $lib->type    = 'project';
                    $lib->main    = '1';
                    $lib->acl     = 'default';
                    $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

                    /* Add creators to stage teams and project teams. */
                    $member = new stdclass();
                    $member->root    = $stageID;
                    $member->account = $account;
                    $member->role    = $this->lang->user->roleList[$this->app->user->role];
                    $member->join    = $now;
                    $member->type    = $data->type;
                    $member->days    = $data->days;
                    $member->hours   = $this->config->project->defaultWorkhours;
                    $this->dao->insert(TABLE_TEAM)->data($member)->exec();
                    $this->project->addExecutionMembers($data->project, array($member));

                    $this->setTreePath($stageID);
                    if($data->acl != 'open') $this->user->updateUserView($stageID, 'sprint');

                    $this->post->set('products', array(0 => $productID));
                    $this->project->updateProducts($stageID);

                    /* Record version change information. */
                    $spec = new stdclass();
                    $spec->project   = $stageID;
                    $spec->version   = $data->version;
                    $spec->name      = $data->name;
                    $spec->milestone = $data->milestone;
                    $spec->begin     = $data->begin;
                    $spec->end       = $data->end;
                    $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();
                }
            }

            /* If child plans has milestone, update parent plan set milestone eq 0 . */
            if($parentID and $milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($parentID)->exec();

            if(dao::isError()) die(js::error(dao::getError()));
        }
    }

    /**
     * Set stage tree path.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function setTreePath($planID)
    {
        $stage  = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($planID)->fetch();
        $parent = $this->dao->select('id,type,parent,path,grade')->from(TABLE_PROJECT)->where('id')->eq($stage->parent)->fetch();

        if($parent->type == 'project')
        {
            $path['path']  =  ",{$parent->id},{$stage->id},";
            $path['grade'] = 1;
        }
        elseif($parent->type == 'stage')
        {
            $path['path']  = $parent->path . "{$stage->id},";
            $path['grade'] = $parent->grade + 1;
        }
        $this->dao->update(TABLE_PROJECT)->set('path')->eq($path['path'])->set('grade')->eq($path['grade'])->where('id')->eq($stage->id)->exec();
    }

    /**
     * Update a plan.
     *
     * @param  int    $planID
     * @param  int    $projectID
     * @access public
     * @return bool|array
     */
    public function update($planID = 0, $projectID = 0)
    {
        $oldPlan = $this->getByID($planID);
        $plan    = fixer::input('post')
            ->setDefault('begin', '0000-00-00')
            ->setDefault('end', '0000-00-00')
            ->setDefault('realBegan', '0000-00-00')
            ->setDefault('realEnd', '0000-00-00')
            ->join('output', ',')
            ->get();

        if($plan->begin == '0000-00-00') dao::$errors['begin'][] = sprintf($this->lang->error->notempty, $this->lang->programplan->begin);
        if($plan->end   == '0000-00-00') dao::$errors['end'][]   = sprintf($this->lang->error->notempty, $this->lang->programplan->end);

        $planChanged = ($oldPlan->name != $plan->name || $oldPlan->milestone != $plan->milestone || $oldPlan->begin != $plan->begin || $oldPlan->end != $plan->end);

        if($plan->parent > 0)
        {
            $parentPlan = $this->getByID($plan->parent);
            $plan->attribute = $parentPlan->attribute;
            $plan->acl       = $parentPlan->acl;
            $parentPercent   = $parentPlan->percent;

            $childrenTotalPercent = $this->getTotalPercent($parentPlan, true);
            $childrenTotalPercent = $plan->parent == $oldPlan->parent ? ($childrenTotalPercent - $oldPlan->percent + $plan->percent) : ($childrenTotalPercent + $plan->percent);

            /* The sum of the workload of the child phases cannot be greater than that of the parent phase. */
            if($childrenTotalPercent > $parentPercent)
            {
                dao::$errors['message'][] = sprintf($this->lang->programplan->error->parentWorkload, $parentPercent . '%');
                return false;
            }

            /* If child plan has milestone, update parent plan set milestone eq 0 . */
            if($plan->milestone and $parentPlan->milestone) $this->dao->update(TABLE_PROJECT)->set('milestone')->eq(0)->where('id')->eq($oldPlan->parent)->exec();
        }
        else
        {
            /* The workload of the parent plan cannot exceed 100%. */
            $oldPlan->parent = $plan->parent;
            $totalPercent    = $this->getTotalPercent($oldPlan);
            $totalPercent    = $totalPercent + $plan->percent;
            if($totalPercent > 100) return dao::$errors['percent'][] = $this->lang->programplan->error->percentOver;
        }


        /* Set planDuration and realDuration. */
        $plan->planDuration = $this->getDuration($plan->begin, $plan->end);
        $plan->realDuration = $this->getDuration($plan->realBegan, $plan->realEnd);

        if($planChanged)  $plan->version = $oldPlan->version + 1;
        if(empty($plan->parent)) $plan->parent = $projectID;

        $this->dao->update(TABLE_PROJECT)->data($plan)
            ->autoCheck()
            ->batchCheck($this->config->programplan->edit->requiredFields, 'notempty')
            ->checkIF($plan->end != '0000-00-00', 'end', 'ge', $plan->begin)
            ->checkIF($plan->percent != false, 'percent', 'float')
            ->where('id')->eq($planID)
            ->exec();

        if(dao::isError()) return false;
        $this->setTreePath($planID);
        if($plan->acl != 'open') $this->loadModel('user')->updateUserView($planID, 'sprint');

        if($planChanged)
        {
            $spec = new stdclass();
            $spec->project   = $planID;
            $spec->version   = $plan->version;
            $spec->name      = $plan->name;
            $spec->milestone = $plan->milestone;
            $spec->begin     = $plan->begin;
            $spec->end       = $plan->end;

            $this->dao->insert(TABLE_PROJECTSPEC)->data($spec)->exec();
        }

        return common::createChanges($oldPlan, $plan);
    }

    /**
     * Print cell.
     *
     * @param  int    $col
     * @param  int    $plan
     * @param  int    $users
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function printCell($col, $plan, $users, $projectID)
    {
        $id = $col->id;
        if($col->show)
        {
            $class  = 'c-' . $id;
            $title  = '';
            $idList = array('id','name','output','percent','attribute','milestone','version','openedBy','openedDate','begin','end','realBegan','realEnd');
            if(in_array($id,$idList))
            {
                $class .= ' text-left';
                $title  = "title='{$plan->$id}'";
                if($id == 'output') $class .= ' text-ellipsis';
                if(!empty($plan->children)) $class .= ' has-child';
            }
            else
            {
                $class .= ' text-center';
            }
            if($id == 'actions') $class .= ' c-actions';

            echo "<td class='{$class}' {$title}>";
            if(isset($this->config->bizVersion)) $this->loadModel('flow')->printFlowCell('programplan', $plan, $id);
            switch($id)
            {
            case 'id':
                echo sprintf('%03d', $plan->id);
                break;
            case 'name':
                if($plan->grade > 1) echo '<span class="label label-badge label-light" title="' . $this->lang->programplan->children . '">' . $this->lang->programplan->childrenAB . '</span> ';
                echo $plan->name;
                if(!empty($plan->children)) echo '<a class="plan-toggle" data-id="' . $plan->id . '"><i class="icon icon-angle-double-right"></i></a>';
                break;
            case 'percent':
                echo $plan->percent . '%';
                break;
            case 'attribute':
                echo zget($this->lang->stage->typeList, $plan->attribute, '');
                break;
            case 'milestone':
                echo zget($this->lang->programplan->milestoneList, $plan->milestone, 0);
                break;
            case 'begin':
                echo $plan->begin;
                break;
            case 'end':
                echo $plan->end;
                break;
            case 'realBegan':
                echo $plan->realBegan;
                break;
            case 'realEnd':
                echo $plan->realEnd;
                break;
            case 'output':
                echo $plan->output;
                break;
            case 'version':
                echo $plan->version;
                break;
            case 'openedBy':
                echo zget($users, $plan->openedBy);
                break;
            case 'openedDate':
                echo substr($plan->openedDate, 5, 11);
                break;
            case 'editedBy':
                echo zget($users, $plan->editedBy);
                break;
            case 'editedDate':
                echo substr($plan->editedDate, 5, 11);
                break;
            case 'actions':
                common::printIcon('project', 'start', "projectID={$plan->id}", $plan, 'list', '', '', 'iframe', true);
                $class = !empty($plan->children) ? 'disabled' : '';
                common::printIcon('task', 'create', "projectID={$plan->id}", $plan, 'list', '', '', $class);

                if($plan->grade == 1 && $this->isCreateTask($plan->id))
                {
                    common::printIcon('programplan', 'create', "program={$plan->parent}&productID=$plan->product&planID=$plan->id", $plan, 'list', 'treemap', '', '', '', '', $this->lang->programplan->createSubPlan);
                }
                else
                {
                    $disabled = ($plan->grade == 2) ? ' disabled' : '';
                    echo html::a('javascript:alert("' . $this->lang->programplan->error->createdTask . '");', '<i class="icon-programplan-create icon-treemap"></i>', '', 'class="btn ' . $disabled . '"');
                }

                common::printIcon('programplan', 'edit', "planID=$plan->id&projectID=$projectID", $plan, 'list', '', '', 'iframe', true);
                $disabled = !empty($plan->children) ? ' disabled' : '';
                if(common::hasPriv('programplan', 'delete', $plan))
                {
                    $deleteURL = helper::createLink('programplan', 'delete', "planID=$plan->id&confirm=yes");
                    echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"programplanForm\", confirmDelete)", '<i class="icon icon-close"></i>', '', "title='{$this->lang->programplan->delete}' class='btn $disabled'");
                }
                break;
            }
            echo '</td>';
        }
    }

    /**
     * Is create task.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function isCreateTask($planID)
    {
        $task = $this->dao->select('*')->from(TABLE_TASK)->where('project')->eq($planID)->limit(1)->fetch();
        return empty($task) ? true : false;
    }

    /**
     * Is clickable.
     *
     * @param  int    $plan
     * @param  int    $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($plan, $action)
    {
        $action = strtolower($action);

        if($action == 'create' and $plan->grade > 1) return false;

        return true;
    }

    /**
     * Get the stage set to milestone.
     *
     * @param  int    $projectID
     * @access public
     * @return object
     */
    public function getMilestones($projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_PROJECT)
            ->where('project')->eq($projectID)
            ->andWhere('type')->eq('stage')
            ->andWhere('milestone')->eq(1)
            ->andWhere('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetchPairs();
    }

    /**
     * Get milestone by product.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function getMilestoneByProduct($productID)
    {
        return $this->dao->select('t1.id, t1.name')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.product')->eq($productID)
            ->andWhere('t1.type')->eq('stage')
            ->andWhere('t1.milestone')->eq(1)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.begin asc')
            ->fetchPairs();
    }

    /**
     * Is parent.
     *
     * @param  int    $planID
     * @access public
     * @return bool
     */
    public function isParent($planID)
    {
        $childrenStage = $this->dao->select('grade')->from(TABLE_PROJECT)->where('parent')->eq($planID)->andWhere('deleted')->eq('0')->fetch();
        return empty($childrenStage) ? false : true;
    }

    /**
     * Get parent stage list.
     *
     * @param  int    $projectID
     * @param  int    $planID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getParentStageList($projectID, $planID, $productID)
    {
        $parentStage = $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)
            ->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.project')->eq($projectID)
            ->andWhere('t2.grade')->eq(1)
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->orderBy('t2.id desc')
            ->fetchPairs();

        /* Remove the currently edited stage. */
        if(isset($parentStage[$planID])) unset($parentStage[$planID]);

        foreach($parentStage as $key => $stage)
        {
            $isCreate = $this->isCreateTask($key);
            if($isCreate === false) unset($parentStage[$key]);
        }
        $parentStage[0] = $this->lang->programplan->emptyParent;
        ksort($parentStage);

        return $parentStage;
    }
}
