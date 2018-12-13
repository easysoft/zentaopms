<?php
public function downloadXXD($setting)
{
    return $this->loadExtension('xuanxuan')->downloadXXD($setting);
}

public function getExtensionList($userID)
{
    return $this->loadExtension('xuanxuan')->getExtensionList($userID);
}
