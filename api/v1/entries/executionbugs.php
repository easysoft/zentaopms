<?php
/**
 * The execution bugs entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class executionBugsEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function get($executionID = 0)
    {
        if(!$executionID) $executionID   = $this->param('execution', 0);
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');

        $control = $this->loadController('execution', 'bug');
        $control->bug($executionID, $this->param('product', 0), $this->param('branch', 0), $this->param('order', 'status,id_desc'), $this->param('build', 0), $this->param('status', 'all'), 0, 0, $this->param('limit', 20), $this->param('page', 1));

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

                $result[$bug->id] = $this->format($bug, 'activatedDate:time,openedDate:time,assignedDate:time,resolvedDate:time,closedDate:time,lastEditedDate:time,deadline:date,deleted:bool');
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
}
