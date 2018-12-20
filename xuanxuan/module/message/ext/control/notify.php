<?php
include '../../control.php';
class myMessage extends message
{
    public function notify()
    {
        $response = array();
        $response['result']  = true;
        $response['message'] = '';

        if(empty($_POST['userList']))
        {
            $response['result']  = false;
            $response['message'] = $this->lang->message->error->noUserList;
            die(json_encode($response));
        }

        if(empty($_POST['sender']))
        {
            $response['result']  = false;
            $response['message'] = $this->lang->message->error->noSender;
            die(json_encode($response));
        }

        $userList    = empty($_POST['userList'])    ? ''      : $this->post->userList;
        $title       = empty($_POST['title'])       ? ''      : $this->post->title;
        $subtitle    = empty($_POST['subtitle'])    ? ''      : $this->post->subtitle;
        $content     = empty($_POST['content'])     ? ''      : $this->post->content;
        $contentType = empty($_POST['contentType']) ? 'text'  : $this->post->contentType;
        $url         = empty($_POST['url'])         ? ''      : $this->post->url;
        $actions     = empty($_POST['actions'])     ? array() : $this->post->actions;
        $sender      = empty($_POST['sender'])      ? 0       : $this->post->sender;

        $result = $this->loadModel('chat')->createNotify($userList, $title, $subtitle, $content, $contentType, $url, $actions, $sender);
        if(!$result)
        {
            $response['result']  = false;
            $response['message'] = dao::getError();
        }

        die(json_encode($response));
    }
}
