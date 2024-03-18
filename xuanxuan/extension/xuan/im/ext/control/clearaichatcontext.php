<?php
helper::import('../../control.php');

class myIm extends im
{
    public function clearAiChatContext($modelId, $userID = 0, $version = '', $device = 'desktop')
    {
        $user = $this->im->user->getByID($userID);

        global $app;
        $app->user = $user;

        $aiChatPermission = commonModel::hasPriv('ai', 'chat');
        if(!$aiChatPermission) return false;

        $aiModel = $this->loadModel('ai')->getLanguageModel($modelId);
        if(!$aiModel || $aiModel->enabled == 0) return false;

        $chatGid = "$userID&ai-{$modelId}";

        $broadcast = new stdclass();
        $broadcast->gid         = imModel::createGID();
        $broadcast->cgid        = $chatGid;
        $broadcast->user        = "ai-{$modelId}";
        $broadcast->content     = $this->lang->ai->miniPrograms->clearContext;
        $broadcast->type        = 'broadcast';
        $broadcast->contentType = 'text';

        $broadcastMessage = $this->im->messageCreate(array($broadcast), $userID);

        $output = new stdclass();
        $output->result = 'success';
        $output->method = 'messagesend';
        $output->users  = array($userID);
        $output->data   = $broadcastMessage;

        return $this->im->sendOutput($output, 'messagesendResponse');
    }
}