<?php
declare(strict_types=1);
class caselibTao extends caselibModel
{
    /**
     * 初始化导入的用例。
     * Init imported case.
     *
     * @param  object $data
     * @access public
     * @return bool|arry
     */
    protected function initImportedCase(object $data): bool|array
    {
        $line  = 1;
        $cases = array();
        foreach($data->lib as $key => $lib)
        {
            $caseData = new stdclass();

            $caseData->lib          = $lib;
            $caseData->module       = $data->module[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->keywords     = $data->keywords[$key];
            $caseData->frequency    = 1;
            $caseData->precondition = $data->precondition[$key];

            if(isset($this->config->testcase->create->requiredFields))
            {
                $requiredFields = explode(',', $this->config->testcase->create->requiredFields);
                foreach($requiredFields as $requiredField)
                {
                    $requiredField = trim($requiredField);
                    if(!isset($caseData->$requiredField)) continue;
                    if(empty($caseData->$requiredField)) dao::$errors[$requiredField][] = sprintf($this->lang->testcase->noRequire, $line, $this->lang->testcase->$requiredField);
                }
            }

            $cases[$key] = $caseData;
            $line++;
        }

        if(dao::isError()) return false;

        return $cases;
    }

    /**
     * 插入导入的用例。
     * Insert imported case.
     *
     * @param  int       $key
     * @param  object    $caseData
     * @param  object    $data
     * @param  bool      $forceNotReview
     * @access protected
     * @return bool|int
     */
    protected function insertImportedCase(int $key, object $caseData, object $data, bool $forceNotReview): bool|int
    {
        $caseData->project    = (int)$this->session->project;
        $caseData->version    = 1;
        $caseData->openedBy   = $this->app->user->account;
        $caseData->openedDate = helper::now();
        $caseData->status     = $forceNotReview ? 'normal' : 'wait';

        $this->dao->insert(TABLE_CASE)->data($caseData)->autoCheck()->exec();

        if(dao::isError()) return false;

        $caseID = $this->dao->lastInsertID();
        if(isset($data->desc[$key]))
        {
            $parentStepID = 0;
            foreach($data->desc[$key] as $id => $desc)
            {
                $desc = trim($desc);
                if(empty($desc)) continue;

                $stepData = new stdclass();
                $stepData->type    = ($data->stepType[$key][$id] == 'item' || $parentStepID == 0) ? 'step' : $data->stepType[$key][$id];
                $stepData->parent  = ($stepData->type == 'item') ? $parentStepID : 0;
                $stepData->case    = $caseID;
                $stepData->version = 1;
                $stepData->desc    = htmlSpecialString($desc);
                $stepData->expect  = htmlSpecialString(trim($data->expect[$key][$id]));
                $this->dao->insert(TABLE_CASESTEP)->data($stepData)->autoCheck()->exec();

                if($stepData->type == 'group') $parentStepID = $this->dao->lastInsertID();
                if($stepData->type == 'step')  $parentStepID = 0;
            }
        }

        $this->loadModel('action')->create('case', $caseID, 'Opened');

        return $caseID;
    }

    /**
     * 检查步骤是否改变。
     * Check the step is changed or not.
     *
     * @param  array    $oldSteps
     * @param  array    $steps
     * @access private
     * @return bool
     */
    private function checkStepChanged(array $oldSteps, array $steps): bool
    {
        if(($oldSteps != $steps) || (count($oldSteps) != count($steps))) return true;

        /* Compare every step. */
        $stepChanged = false;
        foreach($oldSteps as $id => $oldStep)
        {
            if(trim($oldStep->desc) != trim($steps[$id]->desc) || trim($oldStep->expect) != $steps[$id]->expect)
            {
                $stepChanged = true;
                break;
            }
        }
        return $stepChanged;
    }

    /**
     * 根据描述获取用例步骤。
     * Get steps form descs.
     *
     * @param  array   $descs
     * @param  array   $stepTypes
     * @param  array   $expects
     * @access private
     * @return array
     */
    private function processSteps($descs, $stepTypes, $expects): array
    {
        $steps = array();
        foreach($descs as $id => $desc)
        {
            $desc = trim($desc);
            if(empty($desc)) continue;

            $step = new stdclass();
            $step->type   = $stepTypes[$id];
            $step->desc   = htmlSpecialString($desc);
            $step->expect = htmlSpecialString(trim($expects[$id]));

            $steps[] = $step;
        }
        return $steps;
    }

    /**
     * 更新导入的用例。
     * Update imported case.
     *
     * @param  int       $key
     * @param  object    $caseData
     * @param  object    $data
     * @param  bool      $forceNotReview
     * @access protected
     * @return bool
     */
    protected function updateImportedCase(int $key, object $caseData, object $data, bool $forceNotReview): bool
    {
        $caseID   = $data->id[$key];
        $oldCases = $this->loadModel('testcase')->getByList($data->id);
        $oldCase  = $oldCases[$caseID];

        /* 如果已经存在的用例和导入的用例的用例库不同，不导入。*/
        /* Ignore updating cases for different libs. */
        if($oldCase->lib != $caseData->lib) return false;

        $stepsGroupByCase = $this->testcase->getStepGroupByIdList($data->id, 'all');

        $steps       = $this->processSteps(zget($data->desc, $key, array()), zget($data->stepType, $key, array()), zget($data->expect, $key, array()));
        $oldSteps    = zget($stepsGroupByCase, $caseID, array());
        $stepChanged = $this->checkStepChanged($oldSteps, $steps);
        $changes     = common::createChanges($oldCase, $caseData);
        if(!$changes && !$stepChanged) return false;

        $caseData->lastEditedBy   = $this->app->user->account;
        $caseData->lastEditedDate = helper::now();
        $caseData->version        = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
        if($stepChanged && !$forceNotReview) $caseData->status = 'wait';

        $this->dao->update(TABLE_CASE)->data($caseData)->where('id')->eq($caseID)->autoCheck()->exec();
        if(dao::isError()) return false;

        if($stepChanged)
        {
            $parentStepID = 0;
            foreach($steps as $step)
            {
                $step->type    = ($step->type == 'item' && $parentStepID == 0) ? 'step' : $step->type;
                $step->case    = $caseID;
                $step->parent  = ($step->type == 'item') ? $parentStepID : 0;
                $step->version = $caseData->version;
                $this->dao->insert(TABLE_CASESTEP)->data($step)->autoCheck()->exec();

                if($step->type == 'group') $parentStepID = $this->dao->lastInsertID();
                if($step->type == 'step')  $parentStepID = 0;
            }
        }

        $oldCase->steps  = $this->testcase->joinStep($oldSteps);
        $caseData->steps = $this->testcase->joinStep($steps);
        $changes  = common::createChanges($oldCase, $caseData);
        $actionID = $this->loadModel('action')->create('case', (int)$caseID, 'Edited');
        $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }
}
