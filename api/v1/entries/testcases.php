<?php
/**
 * The testcases entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class testcasesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function get($productID = 0, $projectID = 0, $executionID = 0)
    {
        if(!$productID)   $productID   = $this->param('product', 0);
        if(!$projectID)   $projectID   = $this->param('project', 0);
        if(!$executionID) $executionID = $this->param('execution', 0);
        if(!$productID and !$projectID and !$executionID) return $this->sendError(400, 'Need product or project or execution id.');

        if($executionID)
        {
            $control = $this->loadController('execution', 'testcase');
            $control->testcase($executionID, $this->param('status', 'all'), $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
        }
        else
        {
            $control = $this->loadController('testcase', 'browse');
            $control->browse($productID, $this->param('branch', ''), $this->param('status', 'all'), 0, $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1), $projectID);
        }

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $cases  = $data->data->cases;
            $pager  = $data->data->pager;
            $result = array();
            foreach($cases as $case)
            {
                $result[] = $this->format($case, 'openedDate:time,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedDate:date,deleted:bool');
            }

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'testcases' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'module,type,stage,story,title,precondition,pri';
        $this->batchSetPost($fields);
        $this->setPost('product', $productID);

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

        $control = $this->loadController('testcase', 'create');
        $this->requireFields('title,type,pri,steps');

        $control->create(0);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $case = $this->loadModel('testcase')->getByID($data->id);
        $case->steps = (isset($case->steps) and !empty($case->steps)) ? array_values($case->steps) : array();

        $this->send(200, $this->format($case, 'openedDate:time,lastEditedDate:time,lastRunDate:time,scriptedDate:date,reviewedDate:date,deleted:bool'));
    }
}
