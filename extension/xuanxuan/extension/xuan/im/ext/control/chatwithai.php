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

        $aiModel = $this->loadModel('ai')->getLanguageModel($modelId);
        if(!$aiModel || $aiModel->enabled == 0) return false;

        $messages = $this->ai->converse($modelId, array(
            (object)array('role' => 'user', 'content' => $text),
        ));

        if(!$messages)
        {
            $statusOutput = new stdclass();
            $statusOutput->result = 'success';
            $statusOutput->method = 'chatwithai';
            $statusOutput->users  = array($userID);
            $statusOutput->data   = (object)array('status' => 'error', 'modelId' => $modelId);

            return $this->im->sendOutputGroup(array($statusOutput));
        }

        $replyMessage = new stdclass();
        $replyMessage->gid         = imModel::createGID();
        $replyMessage->cgid        = $chatGid;
        $replyMessage->user        = "ai-{$modelId}";
        $replyMessage->content     = current($messages);
        $replyMessage->type        = 'normal';
        $replyMessage->contentType = 'text';

        $sender = new stdclass();
        $sender->id          = 0;
        $sender->displayName = $aiModel->name;

        $replyMessage->data = new stdclass();
        $replyMessage->data->sender = $sender;
        $replyMessage->data         = json_encode($replyMessage->data);

        $chatMessages = $this->im->messageCreate(array($replyMessage), $userID);

        $chatOutput = new stdclass();
        $chatOutput->result = 'success';
        $chatOutput->method = 'messagesend';
        $chatOutput->users  = array($userID);
        $chatOutput->data   = $chatMessages;

        $statusOutput = new stdclass();
        $statusOutput->result = 'success';
        $statusOutput->method = 'chatwithai';
        $statusOutput->users  = array($userID);
        $statusOutput->data   = (object)array('status' => 'success', 'modelId' => $modelId);

        return $this->im->sendOutputGroup(array($statusOutput, $chatOutput));
    }
}