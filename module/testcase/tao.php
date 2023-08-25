<?php
declare(strict_types=1);
class testcaseTao extends testcaseModel
{
    /**
     * Fetch scene name.
     *
     * @param  int       $sceneID
     * @access protected
     * @return void
     */
    protected function fetchSceneName(int $sceneID): string|null
    {
        return $this->dao->findByID((int)$sceneID - CHANGEVALUE)->from(TABLE_SCENE)->fetch('title');
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
    protected function insertSteps(int $caseID, array $steps, array $expects, array $stepTypes)
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
            $step->version = 1;
            $step->desc    = rtrim(htmlSpecialString($stepDesc));
            $step->expect  = $stepType == 'group' ? '' : rtrim(htmlSpecialString(zget($expects, $stepKey, '')));

            $this->dao->insert(TABLE_CASESTEP)->data($step)
                ->autoCheck()
                ->exec();

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

    protected function updateStep(object $case, object $oldCase): bool
    {
        if($oldCase->lib && empty($oldCase->product))
        {
            $fromcaseVersion = $this->dao->select('fromCaseVersion')->from(TABLE_CASE)->where('fromCaseID')->eq($caseID)->fetch('fromCaseVersion');
            $fromcaseVersion = (int)$fromcaseVersion + 1;
            $this->dao->update(TABLE_CASE)->set('`fromCaseVersion`')->eq($fromcaseVersion)->where('`fromCaseID`')->eq($caseID)->exec();
        }

        /* Ignore steps when post has no steps. */
        if($case->steps)
        {
            $this->insertSteps($case->id, $case->steps, $case->expects, (array)$case->stepType);
        }
        else
        {
            foreach($oldCase->steps as $step)
            {
                unset($step->id);
                $step->version = $version;
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();
            }
        }

        return !dao::isError();
    }

    protected function linkBugs($linkedBugs, $case): bool
    {
        $toLinkBugs = $case->linkBug;
        $newBugs    = array_diff($toLinkBugs, $linkedBugs);
        $removeBugs = array_diff($linkedBugs, $toLinkBugs);

        foreach($newBugs as $bugID)    $this->dao->update(TABLE_BUG)->set('`case`')->eq($caseID)->set('caseVersion')->eq($case->version)->set('`story`')->eq($case->story)->set('storyVersion')->eq($case->storyVersion)->where('id')->eq($bugID)->exec();
        foreach($removeBugs as $bugID) $this->dao->update(TABLE_BUG)->set('`case`')->eq(0)->set('caseVersion')->eq(0)->set('`story`')->eq(0)->set('storyVersion')->eq(0)->where('id')->eq($bugID)->exec();

        return !dao::isError();
    }

    protected function unlinkCaseFromTesttask($caseID, $testtasks): bool
    {
        $this->loadModel('action');
        foreach($testtasks as $taskID => $testtask)
        {
            if($testtask->branch != $case->branch && $taskID)
            {
                $this->dao->delete()->from(TABLE_TESTRUN)->where('task')->eq($taskID)->andWhere('`case`')->eq($caseID)->exec();
                $this->action->create('case' ,$caseID, 'unlinkedfromtesttask', '', $taskID);
            }
        }
    }
}
