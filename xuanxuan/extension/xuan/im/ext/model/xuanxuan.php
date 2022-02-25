<?php
public function getExtensionList($userID)
{
    return $this->loadExtension('xuanxuan')->getExtensionList($userID);
}

public function editUser($user = null)
{
    return $this->loadExtension('xuanxuan')->editUser($user);
}

public function getServer($backend = 'xxb')
{
    return $this->loadExtension('xuanxuan')->getServer($backend);
}

public function uploadFile($fileName, $path, $size, $time, $userID, $users, $chat)
{
    return $this->loadExtension('xuanxuan')->uploadFile($fileName, $path, $size, $time, $userID, $users, $chat);
}

public function chatAddAction($chatId = '', $action = '', $actorId = '', $result = '', $comment = '')
{
    return $this->loadExtension('xuanxuan')->chatAddAction($chatId, $action, $actorId, $result, $comment);
}

public function userAddAction($user, $actionType, $result, $comment = '', $common = false)
{
    return $this->loadExtension('xuanxuan')->userAddAction($user, $actionType, $result, $comment, $common);
}

public function messageGetNotifyList()
{
    return $this->loadExtension('xuanxuan')->messageGetNotifyList();
}
