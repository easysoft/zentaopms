<?php
/**
 * The project entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     1
 * @link        http://www.zentao.net
 */
class projectStoriesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get($projectID)
    {
        if(!$projectID) $projectID = $this->param('project');
        if(!$projectID) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('projectstory', 'story');
        $control->story($projectID, $this->param('product', 0), $this->param('branch', ''), $this->param('status', 'unclosed'), 0, 'story', $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $stories = $data->data->stories;
            $pager   = $data->data->pager;
            $result  = array();
            foreach($stories as $story)
            {
                $result[] = $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }
}
