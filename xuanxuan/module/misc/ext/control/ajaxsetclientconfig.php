<?php
include '../../control.php';
class myMisc extends misc
{
    /**
     * Ajax set client config to client package. 
     * 
     * @param  string $os 
     * @access public
     * @return void
     */
    public function ajaxSetClientConfig($os = '')
    {
        $response['result'] = 'success';

        $account   = $this->app->user->account;
        $clientDir = $this->app->wwwRoot . 'data/client/' . "$account/";
        if(!is_dir($clientDir)) mkdir($clientDir, 0755, true);

        /* write login info into config file. */
        $loginInfo = new stdclass();
        $loginInfo->ui = array();
        $loginInfo->ui['defaultUser']['server']  = common::getSysURL();;
        $loginInfo->ui['defaultUser']['account'] = $this->app->user->account;
        $loginInfo->ui['defaultUser']['lock']    = false;
        $loginInfo->ui['defaultUser']['ldap']    = false;
        $loginInfo->ui['login.ldap']             = true;

        $ldapPath = $this->app->getModulePath('', 'ldap');
        if(is_dir($ldapPath))
        {
            $ldapTurnon = $this->dao->select('*')->from(TABLE_CONFIG)->where('owner')->eq('system')->andWhere('module')->eq('ldap')->andWhere('`key`')->eq('turnon')->fetch('value');
            $loginInfo->ui['defaultUser']['ldap'] = !empty($ldapTurnon);
        }

        $loginInfo = json_encode($loginInfo);

        $loginFile = $clientDir . 'config.json';
        file_put_contents($loginFile, $loginInfo);

        define('PCLZIP_TEMPORARY_DIR', $clientDir);
        $this->app->loadClass('pclzip', true);
        $clientFile = $clientDir . 'zentaoclient.zip';
        $archive    = new pclzip($clientFile);

        if($os == 'mac')
        {
            $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'zentaoclient/xuanxuan.app/Contents/Resouces');
        }
        else
        {
            $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'zentaoclient/resources/build-in');
        }

        if($result == 0)
        {
            $response['result']  = 'fail';
            $response['message'] = $archive->errorInfo(true);
            $this->send($response);
        }

        $this->send($response);
    }
}
