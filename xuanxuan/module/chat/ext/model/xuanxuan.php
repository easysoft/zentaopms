<?php
public function getExtensionList($userID)
{
    return $this->loadExtension('xuanxuan')->getExtensionList($userID);
}

public function getUserListOutput($idList = array(), $userID)
{
    return $this->loadExtension('xuanxuan')->getUserListOutput($idList, $userID);
}

