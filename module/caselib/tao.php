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
        if($this->config->edition != 'open') $fieldList = $this->loadModel('workflowaction')->getFields('caselib', 'showimport');

        $this->loadModel('testcase');
        foreach($data->lib as $key => $lib)
        {
            $key = (int)$key;
            $caseData = new stdclass();

            $caseData->lib          = $lib;
            $caseData->module       = (int)$data->module[$key];
            $caseData->title        = $data->title[$key];
            $caseData->pri          = (int)$data->pri[$key];
            $caseData->type         = $data->type[$key];
            $caseData->stage        = join(',', $data->stage[$key]);
            $caseData->keywords     = $data->keywords[$key];
            $caseData->frequency    = 1;
            $caseData->precondition = nl2br($data->precondition[$key]);

            list($caseData->steps, $caseData->stepType) = $this->testcase->processStepsOrExpects($data->steps[$key]);
            list($caseData->expects)                    = $this->testcase->processStepsOrExpects($data->expects[$key]);

            /* 追加工作流字段，保存到数据库。 */
            if($this->config->edition != 'open')
            {
                foreach($fieldList as $field)
                {
                    if(empty($field->show)) continue;
                    if(!isset($data->{$field->field}[$key])) continue;

                    $fieldValue = $data->{$field->field}[$key];
                    $caseData->{$field->field} = $fieldValue;
                }
            }

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
     * @param  object    $oldCase
     * @access protected
     * @return bool
     */
    protected function updateImportedCase(int $key, object $caseData, object $data, bool $forceNotReview, object $oldCase): bool
    {
        $caseID = $data->id[$key];

        /* 如果已经存在的用例和导入的用例的用例库不同，不导入。*/
        /* Ignore updating cases for different libs. */
        if($oldCase->lib != $caseData->lib) return false;

        $stepChanged = $this->loadModel('testcase')->processStepsChanged($caseData, $oldCase->steps);
        $changes     = common::createChanges($oldCase, $caseData);
        if(!$changes && !$stepChanged) return false;

        $caseData->id             = $caseID;
        $caseData->product        = 0;
        $caseData->branch         = 0;
        $caseData->story          = 0;
        $caseData->lastEditedBy   = $this->app->user->account;
        $caseData->lastEditedDate = helper::now();
        $caseData->stepChanged    = $stepChanged;
        $caseData->version        = $stepChanged ? $oldCase->version + 1 : $oldCase->version;
        if($stepChanged && !$forceNotReview) $caseData->status = 'wait';

        $changes = $this->testcase->update($caseData, $oldCase);

        if(dao::isError()) return false;

        $actionID = $this->loadModel('action')->create('case', (int)$caseID, 'Edited');
        $this->action->logHistory($actionID, $changes);

        return !dao::isError();
    }
}
