<?php
/**
 * The bugs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class bugsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function get($productID = 0)
    {
        if(empty($productID)) $productID = $this->param('product', 0);
        if(empty($productID)) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('bug', 'browse');
        $control->browse($productID, $this->param('branch', 'all'), $this->param('status', ''), 0, $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $bugs   = $data->data->bugs;
            $pager  = $data->data->pager;
            $result = array();
            $this->loadModel('product');
            foreach($bugs as $bug)
            {
                $product            = $this->product->getById($bug->product);
                $bug->statusName    = $this->lang->bug->statusList[$bug->status];
                $bug->productStatus = $product->status;

                $result[$bug->id] = $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool');
            }

            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'bugs' => array_values($result)));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'title,project,execution,openedBuild,assignedTo,pri,module,severity,type,story,task,mailto,keywords,steps,uid,deadline';
        $this->batchSetPost($fields);

        $caseID = $this->request('case', 0);
        if($caseID)
        {
            $case = $this->loadModel('testcase')->getById($caseID);
            if($case)
            {
                $this->setPost('case', $case->id);
                $this->setPost('caseVersion', $case->version);
            }
        }

        $this->setPost('product', $productID);
        $this->setPost('notifyEmail', implode(',', $this->request('notifyEmail', array())));

        $control = $this->loadController('bug', 'create');
        $this->requireFields('title,pri,severity,type,openedBuild');

        $control->create($productID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $bug = $this->loadModel('bug')->getByID($data->id);

        return $this->send(201, $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }
}
