<?php
/**
 * The control file of misc of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: control.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class misc extends control
{
    /**
     * Ping the server every 5 minutes to keep the session.
     * 
     * @access public
     * @return void
     */
    public function ping()
    {
        if(mt_rand(0, 1) == 1) $this->loadModel('setting')->setSN();
        die("<html><head><meta http-equiv='refresh' content='300' /></head><body></body></html>");
    }

    /**
     * Show php info.
     * 
     * @access public
     * @return void
     */
    public function phpinfo()
    {
        die(phpinfo());
    }

    /**
     * Show about info of zentao.
     * 
     * @access public
     * @return void
     */
    public function about()
    {
        die($this->display());
    }

    /**
     * Update nl.
     * 
     * @access public
     * @return void
     */
    public function updateNL()
    {
        $this->loadModel('upgrade')->updateNL();
    }

    /**
     * Check current version is latest or not.
     * 
     * @access public
     * @return void
     */
    public function checkUpdate()
    {
        $note    = isset($_GET['note'])    ? $_GET['note'] : '';
        $browser = isset($_GET['browser']) ? $_GET['browser'] : '';

        $this->view->note    = urldecode(helper::safe64Decode($note));
        $this->view->browser = $browser;
        $this->display();
    }

    /**
     * Check model extension logic
     * 
     * @access public
     * @return void
     */
    public function checkExtension()
    {
        echo $this->misc->hello();
        echo $this->misc->hello2();
    }

    /**
     * Down notify.
     * 
     * @access public
     * @return void
     */
    public function downNotify()
    {
        $notifyDir   = $this->app->getBasePath() . 'www/data/notify/';
        $packageFile = $notifyDir . 'notify.zip';
        $loginFile   = $notifyDir . 'config.json';

        /* write login info into tmp file. */
        $loginInfo = new stdclass();
        $userInfo  = new stdclass();
        $userInfo->Account        = $this->app->user->account;
        $userInfo->Url            = common::getSysURL() . $this->config->webRoot;
        $userInfo->PassMd5        = '';
        $userInfo->Role           = $this->app->user->role;
        $userInfo->AutoSignIn     = true;
        $userInfo->Lang           = $this->cookie->lang;
        $loginInfo->User          = $userInfo;
        $loginInfo->LastLoginTime = time() / 86400 + 25569;
        $loginInfo = json_encode($loginInfo);

        file_put_contents($loginFile, $loginInfo);

        define('PCLZIP_TEMPORARY_DIR', $notifyDir);
        $this->app->loadClass('pclzip', true);

        /* remove the old config.json, add a new one. */
        $archive = new pclzip($packageFile);
        $result = $archive->delete(PCLZIP_OPT_BY_NAME, 'config.json');
        if($result == 0) die("Error : " . $archive->errorInfo(true));

        $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH);
        if($result == 0) die("Error : " . $archive->errorInfo(true));
        
        unlink($loginFile);

        $this->fetch('file', 'sendDownHeader', array('fileName' => 'notify.zip', 'zip', file_get_contents($packageFile)));
        $result = $archive->delete(PCLZIP_OPT_BY_NAME, 'config.json');
        if($result == 0) die("Error : " . $archive->errorInfo(true));
    }

    /**
     * Create qrcode for mobile login.
     * 
     * @access public
     * @return void
     */
    public function qrCode()
    {
        $loginAPI = common::getSysURL() . $this->config->webRoot;
        $session  = $this->loadModel('user')->isLogon() ? '?sid=' . session_id() : '';

        if(!extension_loaded('gd'))
        {
            $this->view->noGDLib = sprintf($this->lang->misc->noGDLib, $loginAPI);
            die($this->display());
        }

        $this->app->loadClass('qrcode');
        QRcode::png($loginAPI . $session, false, 4, 9);
    }
}
