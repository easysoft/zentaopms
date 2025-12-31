<?php
/**
 * The story submit review entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class storySubmitReviewEntry extends entry
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
        $control = $this->loadController('story', 'submitReview');

        $fields = 'reviewer,needNotReview';
        $this->batchSetPost($fields);

        $control->submitReview($storyID, $this->param('storyType', 'story'));

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->result) && $data->result == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(isset($data->status) && $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $story = $this->loadModel('story')->getByID($storyID);

        return $this->send(200, $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }
}
