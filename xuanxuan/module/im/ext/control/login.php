<?php
include '../../control.php';

class myIm extends im 
{
    public function login($account = '', $password = '', $status = '', $userID = 0, $version = '', $device = 'desktop')
    {
        if(!isset($this->config->xuanxuan->turnon) or $this->config->xuanxuan->turnon != 1) die;
        parent::login($account, $password, $status, $userID, $version);
    }
}
