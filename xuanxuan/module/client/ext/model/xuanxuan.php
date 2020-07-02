<?php
public function getCurrentVersion()
{
    $currentVersion = $this->dao->select('*')->from(TABLE_IM_CLIENT)->where('status')->eq('released')->orderBy('id_desc')->limit(1)->fetch();

    if(dao::isError()) return false;
    return $currentVersion ?: json_decode('{"version": "' . $this->config->xuanxuan->version . '"}');
}

public function downloadZipPackage($version, $link)
{
    $decodeLink = helper::safe64Decode($link);
    if(preg_match('/^https?\:\/\//', $decodeLink)) return false;

    return parent::downloadZipPackage($version, $link);
}
