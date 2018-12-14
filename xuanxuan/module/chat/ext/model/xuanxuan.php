<?php
public function downloadXXD($setting, $type)
{
    return $this->loadExtension('xuanxuan')->downloadXXD($setting, $type);
}

public function getExtensionList($userID)
{
    return $this->loadExtension('xuanxuan')->getExtensionList($userID);
}

public function sendDownHeader($fileName, $fileType, $content, $fileSize = 0)
{
    return $this->loadExtension('xuanxuan')->sendDownHeader($fileName, $fileType, $content, $fileSize);
}
