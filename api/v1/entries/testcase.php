<?php
/**
 * The testcase entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testcaseEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return void
     */
    public function get($testcaseID)
    {
        $control = $this->loadController('testcase', 'view');
        $control->view($testcaseID, $this->param('version', 0));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(!isset($data->case)) $this->sendError(400, 'error');

        $case = $data->case;
        $case->steps = (isset($case->steps) and !empty($case->steps)) ? array_values(get_object_vars($case->steps)) : array();

        $this->send(200, $this->format($case, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedBy:user,reviewedDate:date,steps:array,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $caseID
     * @access public
     * @return void
     */
    public function put($caseID)
    {
        $oldCase = $this->loadModel('testcase')->getByID($caseID);

        /* Set $_POST variables. */
        $fields = 'title,pri,story,type,stage,product,module,branch,precondition';
        $this->batchSetPost($fields, $oldCase);

        /* Set steps and expects. */
        if(isset($this->requestBody->steps))
        {
            $steps    = array();
            $expects  = array();
            $stepType = array();
            foreach($this->requestBody->steps as $step)
            {
                $steps[]    = $step->desc;
                $expects[]  = $step->expect;
                $stepType[] = 'item';
            }
            $this->setPost('steps',    $steps);
            $this->setPost('expects',  $expects);
            $this->setPost('stepType', $stepType);
        }

        $control = $this->loadController('testcase', 'edit');
        $control->edit($caseID);

        $this->getData();
        $case = $this->testcase->getByID($caseID);
        $this->send(200, $this->format($case, 'openedBy:user,openedDate:time,lastEditedBy:user,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedBy:user,reviewedDate:date,steps:array,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $testcaseID
     * @access public
     * @return void
     */
    public function delete($testcaseID)
    {
        $control = $this->loadController('testcase', 'delete');
        $control->delete($testcaseID, 'yes');

        $this->getData();

        $this->sendSuccess(200, 'success');
    }
}
