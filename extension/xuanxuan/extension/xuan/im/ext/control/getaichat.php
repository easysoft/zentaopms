<?php
helper::import('../../control.php');

class myIm extends im
{
    public function getAiChat($userID = 0, $version = '', $device = 'desktop')
    {
        $chats = $this->im->chatGetListByUserID($userID);

        $chats = array_values(array_filter($chats, function($chat) {return $chat->type == 'ai';}));

        $output = new stdclass();
        $output->result = 'success';
        $output->users  = array($userID);
        $output->data   = $chats;

        return $this->im->sendOutput($output, "getaichatResponse");
    }
}