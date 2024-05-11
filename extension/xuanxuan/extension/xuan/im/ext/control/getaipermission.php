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
        $models = $this->loadModel('ai')->getLanguageModels();

        $chats = array();
        if($models)
        {
            foreach($models as $model)
            {
                if($model->enabled == '0') continue;

                $chat = $this->im->chat->getByGid("$userID&ai-{$model->id}");
                if($chat) continue;

                $chat = new stdclass();
                $chat->gid = "$userID&ai-{$model->id}";
                $chat->name = empty($model->name) ? $this->lang->ai->models->typeList[$model->type] : $model->name;

                $chats[] = $chat;
            }
        }

        $output = new stdclass();
        $output->method = 'getaipermission';
        $output->result = 'success';

        $output->data = new stdclass();
        $output->data->hasAiChatPermission = $aiChatPermission;
        $output->data->hasModelsAvailable  = !empty($models);
        $output->data->needToCreateChat    = $chats;

        return $this->im->sendOutput($output, 'getaipermissionResponse');
    }
}