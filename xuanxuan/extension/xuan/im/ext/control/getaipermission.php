<?php
helper::import('../../control.php');
class myIm extends im {
    public function getAiPermission($userID = 0, $version = '', $device = 'desktop')
    {
        $user = $this->im->user->getByID($userID);
        $user->rights = $this->loadModel('user')->authorize($user->account);

        global $app;
        $app->user = $user;

        $aiChatPermission = commonModel::hasPriv('ai', 'chat');
        $hasModelsAvailable = $this->loadModel('ai')->hasModelsAvailable();

        $chats = array();
        if($hasModelsAvailable)
        {

            $models = $this->ai->getLanguageModels('', true);
            foreach($models as $model)
            {
                $chat = $this->im->chat->getByGid("$userID&ai-{$model->id}");
                if($chat) continue;

                $chat = new stdclass();
                $chat->gid = "$userID&ai-{$model->id}";
                $chat->name = $model->name;

                $chats[] = $chat;
            }
        }

        $output = new stdclass();
        $output->method = 'getaipermission';
        $output->result = 'success';

        $output->data = new stdclass();
        $output->data->hasAiChatPermission = $aiChatPermission;
        $output->data->hasModelsAvailable  = $hasModelsAvailable;
        $output->data->needToCreateChat    = $chats;

        return $this->im->sendOutput($output, 'getaipermissionResponse');
    }
}