<?php
/**
 * The bugs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class bugsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @access public
     * @return void
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
                $status = array('code' => $bug->status, 'name' => $this->lang->bug->statusList[$bug->status]);
                if($bug->status == 'active' and $bug->confirmed) $status = array('code' => 'confirmed', 'name' => $this->lang->bug->labelConfirmed);
                if($bug->resolution == 'postponed') $status = array('code' => 'postponed', 'name' => $this->lang->bug->labelPostponed);
                if(!empty($bug->delay)) $status = array('code' => 'delay', 'name' => $this->lang->bug->overdueBugs);
                $bug->status     = $status['code'];
                $bug->statusName = $status['name'];

                $product            = $this->product->getById($bug->product);
                $bug->productStatus = $product->status;

                $result[$bug->id] = $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool');
            }

            $storyChangeds = $this->dao->select('t1.id')->from(TABLE_BUG)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                ->where('t1.id')->in(array_keys($result))
                ->andWhere('t1.story')->ne('0')
                ->andWhere('t1.storyVersion != t2.version')
                ->fetchPairs('id', 'id');
            foreach($storyChangeds as $bugID)
            {
                $status = array('code' => 'storyChanged', 'name' => $this->lang->bug->changed);
                $result[$bugID]->status     = $status['code'];
                $result[$bugID]->statusName = $status['name'];
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
     * @return void
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'title,project,execution,openedBuild,assignedTo,pri,module,severity,type,story,task,mailto,keywords,steps,uid';
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

        $this->send(200, $this->format($bug, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }
}
