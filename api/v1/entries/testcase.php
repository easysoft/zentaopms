<?php
/**
 * The testcase entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class testcaseEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return string
     */
    public function get($testcaseID)
    {
        $control = $this->loadController('testcase', 'view');
        $control->view($testcaseID, $this->param('version', 0));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(!isset($data->case)) $this->sendError(400, 'error');

        $case = $data->data->case;
        $case->steps = (isset($case->steps) and !empty($case->steps)) ? array_values(get_object_vars((object)$case->steps)) : array();
        if(!empty($case->steps))
        {
            foreach($case->steps as &$step)
            {
                foreach(array('step', 'desc', 'expect') as $field)
                {
                    if(isset($step->$field) && $step->$field != '')
                    {
                        $step->$field = str_replace(array("&amp;quot;", "&amp;#039;", "&amp;lt;", "&amp;gt;"), array('"', "'", "<", ">"), $step->$field);
                    }
                }
            }
        }

        return $this->send(200, $this->format($case, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedBy:user,reviewedDate:date,steps:array,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $caseID
     * @access public
     * @return string
     */
    public function put($caseID)
    {
        $control = $this->loadController('testcase', 'edit');
        $oldCase = $this->loadModel('testcase')->getByID($caseID);

        /* Set $_POST variables. */
        $fields = 'title,pri,story,type,stage,product,module,branch,precondition,script';
        $this->batchSetPost($fields, $oldCase);
        $this->setPost('uid', $this->request('uid', ''));
        if(isset($this->requestBody->script)) $this->setPost('auto', 'auto');

        /* Set steps and expects. */
        $steps    = array();
        $expects  = array();
        $stepType = array();
        if(isset($this->requestBody->steps) && !isset($this->requestBody->stepType))
        {
            foreach($this->requestBody->steps as $key => $step)
            {
                if(empty($step->type)) $step->type = 'step';
                if(!in_array($step->type, array('step', 'item', 'group'))) $step->type = 'step';

                if($step->type == 'group' && (empty($this->requestBody->steps[$key + 1]->type) || $this->requestBody->steps[$key + 1]->type != 'step')) $step->type = 'step';

                $stepID = zget($step, 'id', $key);
                $steps[$stepID]    = $step->desc;
                $expects[$stepID]  = $step->expect;
                $stepType[$stepID] = $step->type;
            }
        }

        $this->setPost('steps',    isset($this->requestBody->stepType) ? $this->requestBody->steps : $steps);
        $this->setPost('expects',  isset($this->requestBody->expects) ? $this->requestBody->expects : $expects);
        $this->setPost('stepType', isset($this->requestBody->stepType) ? $this->requestBody->stepType : $stepType);

        $control->edit($caseID);

        $this->getData();
        $case = $this->testcase->getByID($caseID);
        return $this->send(200, $this->format($case, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedBy:user,reviewedDate:date,steps:array,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return string
     */
    public function delete($testcaseID)
    {
        $control = $this->loadController('testcase', 'delete');
        $control->delete($testcaseID, 'yes');

        $this->getData();

        return $this->sendSuccess(200, 'success');
    }
}
