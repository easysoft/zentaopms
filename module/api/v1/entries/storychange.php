<?php
/**
 * The bug change point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class storyChangeEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function post($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        $fields = 'reviewer,comment,executions,bugs,cases,tasks,reviewedBy,uid';
        $this->batchSetPost($fields);
        $fields = 'title,spec,verify';
        $this->batchSetPost($fields, $oldStory);

        /* If reviewer is not post, set needNotReview. */
        if(!$this->request('reviewer'))
        {
            $this->setPost('reviewer', array());
            $this->setPost('needNotReview', 1);
        }

        $control = $this->loadController('story', 'change');
        $this->requireFields('title');

        $control->change($storyID);

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $story = $this->loadModel('story')->getByID($storyID);

        return $this->send(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time'));
    }
}
