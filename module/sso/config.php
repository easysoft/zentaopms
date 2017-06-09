<?php
$config->filterParam->get['sso']['login']['hold']        = 'referer,token,status,data,md5';
$config->filterParam->get['sso']['logout']['hold']       = 'token,status';
$config->filterParam->get['sso']['getuserpairs']['hold'] = 'hash';
$config->filterParam->get['sso']['getbindusers']['hold'] = 'hash';
$config->filterParam->get['sso']['gettodolist']['hold']  = 'hash';
$config->filterParam->get['sso']['login']['params']['referer']['reg']     = '/^[a-zA-Z0-9\+\/\=]+$/';
$config->filterParam->get['sso']['login']['params']['token']['reg']       = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['login']['params']['status']['code']     = '';
$config->filterParam->get['sso']['login']['params']['data']['reg']        = '/^[a-zA-Z0-9\+\/\=]+$/';
$config->filterParam->get['sso']['login']['params']['md5']['reg']         = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['logout']['params']['token']['reg']      = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['logout']['params']['status']['code']    = '';
$config->filterParam->get['sso']['getuserpairs']['params']['hash']['reg'] = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['getbindusers']['params']['hash']['reg'] = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['gettodolist']['params']['hash']['reg']  = '/^[a-z0-9]{32}$/';
