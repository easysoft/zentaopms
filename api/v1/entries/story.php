<?php
/**
 * 禅道API的story资源类
 * 版本V1
 *
 * The story entry point of zentaopms
 * Version 1
 */
class storyEntry extends Entry
{
    public function get($storyID)
    {
        $control = $this->loadController('story', 'view');
        $control->view($storyID);

        $data  = $this->getData();
        $story = $data->data->story;
        $this->send(200, $story);
    }

    public function put($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        /* Set $_POST variables. */
        $fields = 'type';
        $this->batchSetPost($fields, $oldStory);
        $this->setPost('parent', 0);

        $control = $this->loadController('story', 'edit');
        $control->edit($storyID);

        $this->getData();
        $this->sendSuccess(200, 'success');
    }

    public function delete($storyID)
    {
        $control = $this->loadController('story', 'delete');
        $control->delete($storyID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
