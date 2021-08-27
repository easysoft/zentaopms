<?php
/**
 * 禅道API的executionStories资源类
 * 版本V1
 *
 * The executionStories entry point of zentaopms
 * Version 1
 */
class executionStoriesEntry extends entry 
{
    public function get($executionID)
    {
        $control = $this->loadController('execution', 'story');
        $control->story($executionID, $this->param('order', ''), $this->param('type', 'all'), 0, $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
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
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'executionStories' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }
}
