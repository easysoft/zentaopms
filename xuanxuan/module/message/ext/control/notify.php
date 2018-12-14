<?php
include '../../control.php';
class myMessage extends message
{
    public function notify()
    {
        $response = array();
        $response['result']  = true;
        $response['message'] = '';

        $userList    = empty($this->post->userList)    ? ''      : $this->post->userList;
        $title       = empty($this->post->title)       ? ''      : $this->post->title;
        $subtitle    = empty($this->post->subtitle)    ? ''      : $this->post->subtitle;
        $content     = empty($this->post->content)     ? ''      : $this->post->content;
        $contentType = empty($this->post->contentType) ? 'text'  : $this->post->contentType;
        $url         = empty($this->post->url)         ? ''      : $this->post->url;
        $actions     = empty($this->post->actions)     ? array() : $this->post->actions;
        $sender      = empty($this->post->sender)      ? 0       : $this->post->sender;

        $result = $this->loadModel('chat')->createNotify($userList, $title, $subtitle, $content, $contentType, $url, $actions, $sender);

        if(!$result)
        {
            $response['result']  = false;
            $response['message'] = dao::getError();
        }

        die(json_encode($response));
    }
}
