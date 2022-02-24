<?php
include '../../control.php';

class myIm extends im 
{
    public function userLogin($account = '', $password = '', $options = 0, $userID = 0, $version = '', $device = 'desktop')
    {
        if(!isset($this->config->xuanxuan->turnon) or $this->config->xuanxuan->turnon != 1) die;
        parent::userLogin($account, $password, $options, $userID, $version, $device);
    }
}
