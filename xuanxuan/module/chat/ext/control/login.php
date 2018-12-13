<?php
include '../../control.php';

class myChat extends chat 
{
    public function login($account = '', $password = '', $status = '', $userID = 0, $version = '')
    {
        if(!isset($this->config->xuanxuan->turnon) or $this->config->xuanxuan->turnon != 1) die;
        parent::login($account, $password, $status, $userID, $version);
    }
}
