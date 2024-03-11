<?php
helper::import('../../control.php');

class myIm extends im
{
    public function chatWithAi($modelId, $text, $userID = 0, $version = '', $device = 'desktop')
    {
        $user = $this->im->user->getByID($userID);

        global $app;
        $app->user = $user;

        $chatGid = "$userID&ai-{$modelId}";

        $aiChatPermission = commonModel::hasPriv('ai', 'chat');
        if(!$aiChatPermission) return false;

        $chat = $this->im->chat->getByGid($chatGid, false, false);
        if(!$chat) return false;

        $messages = $this->loadModel('ai')->converse($modelId, array(
            (object)array('role' => 'user', 'content' => $text),
        ));

        $replyMessage = new stdclass();
        $replyMessage->gid         = imModel::createGID();
        $replyMessage->cgid        = $chatGid;
        $replyMessage->user        = "ai-$modelId";
        $replyMessage->content     = current($messages);
        $replyMessage->type        = 'normal';
        $replyMessage->contentType = 'text';

        $chatMessages = $this->im->messageCreate(array($replyMessage), $userID);

        $output = new stdclass();
        $output->result = 'success';
        $output->method = 'messagesend';
        $output->users  = array($userID);;
        $output->data   = $chatMessages;

        return $this->im->sendOutput($output, 'messagesendResponse');
    }
}