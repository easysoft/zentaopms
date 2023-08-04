<?php
declare(strict_types=1);
/**
 * The zen file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
class testcaseZen extends testcase
{
    /**
     * Assign testcase related variables.
     *
     * @param  object    $case
     * @param  string    $from
     * @param  int       $taskID
     * @access protected
     * @return void
     */
    protected function assignCaseForView(object $case, string $from, int $taskID)
    {
        $case = $this->loadModel('story')->checkNeedConfirm($case);
        if($from == 'testtask')
        {
            $run = $this->loadModel('testtask')->getRunByCase($taskID, $case->id);
            $case->assignedTo    = $run->assignedTo;
            $case->lastRunner    = $run->lastRunner;
            $case->lastRunDate   = $run->lastRunDate;
            $case->lastRunResult = $run->lastRunResult;
            $case->caseStatus    = $case->status;
            $case->status        = $run->status;

            $results = $this->testtask->getResults($run->id);
            $result  = array_shift($results);
            if($result)
            {
                $case->xml      = $result->xml;
                $case->duration = $result->duration;
            }
        }
        $case = $this->testcase->appendCaseFails($case, $from, $taskID);
        $case = $this->processStepsForMindMap($case);

        $this->view->runID      = $from == 'testcase' ? 0 : $run->id;
        $this->view->case       = $case;
        $this->view->caseFails  = $case->caseFails;
        $this->view->modulePath = $this->tree->getParents($case->module);
        $this->view->caseModule = empty($case->module) ? '' : $this->tree->getById($case->module);
    }

    /**
     * 创建测试用例前检验表单数据是否正确。
     * check from data for create case.
     *
     * @param  object    $case
     * @access protected
     * @return bool
     */
    protected function checkCreateFormData(object $case): bool
    {
        $steps   = $case->steps;
        $expects = $case->expects;
        foreach($expects as $key => $value)
        {
            if(!empty($value) and empty($steps[$key])) dao::$errors['message']["steps$key"] = sprintf($this->lang->testcase->stepsEmpty, $key);
        }
        if(dao::isError()) return false;

        $param = '';
        if(!empty($case->lib))     $param = "lib={$case->lib}";
        if(!empty($case->product)) $param = "product={$case->product}";

        $result = $this->loadModel('common')->removeDuplicate('case', $case, $param);
        if($result and $result['stop'])
        {
            return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->duplicate, $this->lang->testcase->common), 'locate' => $this->createLink('testcase', 'view', "caseID={$result['duplicate']}")));
        }
        return true;
    }

    /**
     * 处理xmind数据
     * Process scene data.
     *
     * @param  array     $result
     * @access protected
     * @return array
     */
    protected function processScene(array $result): array
    {
        $scenes['id']   = $result['id'];
        $scenes['text'] = $result['title'];
        $scenes['type'] = 'root';
        if(!empty($result['children'])) $scenes['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'sub');
        return $scenes;
    }

    /**
     * 处理xmind的节点数据
     * process scene child data.
     *
     * @param  array     $results
     * @param  string    $parent
     * @param  string    $type
     * @access protected
     * @return void
     */
    protected function processChildScene(array $results, string $parent, string $type)
    {
        $scenes = array();
        foreach($results as $result)
        {
            $scene['id']     = $result['id'];
            $scene['text']   = $result['title'];
            $scene['parent'] = $parent;
            $scene['type']   = $type;
            if(!empty($result['children'])) $scene['children'] = $this->processChildScene($result['children']['attached'], $result['id'], 'node');
            $scenes[] = $scene;
        }
        return $scenes;
    }

    /**
     * 为展示脑图计算步骤数据。
     * Process steps for mindmap.
     *
     * @param  object    $case
     * @access protected
     * @return object
     */
    protected function processStepsForMindMap(object $case): object
    {
        $mindMapSteps = array();
        $mindMapSteps['id']   = $case->id;
        $mindMapSteps['text'] = $case->title;
        $mindMapSteps['type'] = 'root';

        $reverseSteps = array_reverse($case->steps);

        $parentSteps = array();
        foreach($reverseSteps as $step)
        {
            $stepItem = array();
            $stepItem['id']   = $step->id;
            $stepItem['text'] = $step->step;
            $stepItem['type'] = $step->grade == 1 ? 'sub' : 'node';
            $stepItem['parent'] = $step->parent > 0 ? $step->parent : $case->id;
            if(isset($parentSteps[$step->id])) $stepItem['children'] = array_reverse($parentSteps[$step->id]);

            if($step->parent > 0)
            {
                $parentSteps[$step->parent][] = $stepItem;
            }
            else
            {
                $stepList[] = $stepItem;
            }
        }
        $mindMapSteps['children'] = array_reverse($stepList);
        $case->mindMapSteps = $mindMapSteps;
        return $case;
    }
}

