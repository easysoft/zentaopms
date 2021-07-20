<?php
/**
 * 禅道API的story资源类
 * 版本V1
 *
 * The story entry point of zentaopms
 * Version 1
 */
class storyChangeEntry extends Entry
{
    public function post($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        $fields = 'reviewer,comment';
        $this->batchSetPost($fields);
        $fields = 'title,spec,verify';
        $this->batchSetPost($fields, $oldStory);

        /* If reviewer is not post, set needNotReview. */
        if(empty($this->request('reviewer')))
        {
            $this->setPost('reviewer', array());
            $this->setPost('needNotReview', 1);
        }

        $control = $this->loadController('story', 'change');
        $this->requireFields('title,spec');

        $control->change($storyID);
        
        $data = $this->getData();
        if($data->status == 'fail') return $this->sendError(400, $data->message);

        $story = $this->loadModel('story')->getByID($storyID);

        $this->send(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time'));
    }
}
