<?php
$config->filterParam->get['sso']['login']['referer']['reg']     = '/^[a-zA-Z0-9\+\/\=]+$/';
$config->filterParam->get['sso']['login']['token']['reg']       = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['login']['status']['code']     = '';
$config->filterParam->get['sso']['login']['data']['reg']        = '/^[a-zA-Z0-9\+\/\=]+$/';
$config->filterParam->get['sso']['login']['md5']['reg']         = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['logout']['token']['reg']      = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['logout']['status']['code']    = '';
$config->filterParam->get['sso']['getuserpairs']['hash']['reg'] = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['getbindusers']['hash']['reg'] = '/^[a-z0-9]{32}$/';
$config->filterParam->get['sso']['gettodolist']['hash']['reg']  = '/^[a-z0-9]{32}$/';
