<?php
helper::import('../../control.php');

class myIm extends im
{
    public function clearAiChatContext($modelId, $assistantId, $explicitSwitchAssistant, $userID = 0, $version = '', $device = 'desktop')
    {
        $user = $this->im->user->getByID($userID);
        $user->rights = $this->loadModel('user')->authorize($user->account);

        global $app;
        $app->user = $user;

        $aiChatPermission = commonModel::hasPriv('ai', 'chat');
        if(!$aiChatPermission) return false;

        $aiModel = $this->loadModel('ai')->getLanguageModel($modelId);
        if(!$aiModel || $aiModel->enabled == 0) return false;

        $chatGid = "$userID&ai-{$modelId}";
        $assistant = empty($assistantId)
            ? (object) array('name'=> $this->lang->ai->assistant->defaultAssistant)
            : $this->ai->getAssistantById($assistantId);

        if($explicitSwitchAssistant)
        {
            $broadcast          = new stdclass();
            $broadcast->gid     = imModel::createGID();
            $broadcast->cgid    = $chatGid;
            $broadcast->user    = "ai-{$modelId}";
            $broadcast->content = sprintf($this->lang->ai->assistant->switchAndClearContext, $assistant->name);
        }
        else
        {
            $broadcast          = new stdclass();
            $broadcast->gid     = imModel::createGID();
            $broadcast->cgid    = $chatGid;
            $broadcast->user    = "ai-{$modelId}";
            $broadcast->content = $this->lang->ai->miniPrograms->clearContext;
        }
        $broadcast->type        = 'broadcast';
        $broadcast->contentType = 'text';
        $broadcast->data        = json_encode(array('reminders' => array()));
        $broadcastMessage       = $this->im->messageCreate(array($broadcast), $userID);

        $output = new stdclass();
        $output->result = 'success';
        $output->method = 'messagesend';
        $output->users  = array($userID);
        $output->data   = $broadcastMessage;

        $outputs = array($output);

        if(!empty($assistant->greetings))
        {
            $replyMessage = new stdclass();
            $replyMessage->gid         = imModel::createGID();
            $replyMessage->cgid        = $chatGid;
            $replyMessage->user        = "ai-{$modelId}";
            $replyMessage->content     = $assistant->greetings;
            $replyMessage->type        = 'normal';
            $replyMessage->contentType = 'text';

            $sender = new stdclass();
            $sender->id          = 0;
            $sender->displayName = $aiModel->name;

            $replyMessage->data = new stdclass();
            $replyMessage->data->sender = $sender;
            $replyMessage->data         = json_encode($replyMessage->data);

            $chatMessage = $this->im->messageCreate(array($replyMessage), $userID);

            $output = new stdclass();
            $output->result = 'success';
            $output->method = 'messagesend';
            $output->users  = array($userID);
            $output->data   = $chatMessage;

            $outputs[] = $output;
        }
        return $this->im->sendOutputGroup($outputs);
    }
}