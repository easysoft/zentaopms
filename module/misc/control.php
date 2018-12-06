<?php
/**
 * The control file of misc of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        die("<html><head><meta http-equiv='refresh' content='600' /></head><body></body></html>");
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
     * Download zentao client.
     * 
     * @access public
     * @return void
     */
    public function downloadClient($os = '', $confirm = 'no', $send = 'no')
    {
        if($_POST) die(js::locate($this->createLink('misc', 'downloadClient', "os={$this->post->os}&confirm=yes"), 'parent'));

        if($send == 'yes')
        {
            $clientDir = $this->app->getBasePath() . 'tmp/cache/client/tmp/';
            if(!is_dir($clientDir)) mkdir($clientDir, 0755, true);

            /* write login info into config file. */
            $defaultUser = new stdclass();
            $defaultUser->server  = common::getSysURL();
            $defaultUser->account = $this->app->user->account;

            $loginInfo = new stdclass();
            $loginInfo->ui = $defaultUser;
            $loginInfo = json_encode($loginInfo);

            $loginFile = $clientDir . 'config.json';
            file_put_contents($loginFile, $loginInfo);

            define('PCLZIP_TEMPORARY_DIR', $clientDir);
            $this->app->loadClass('pclzip', true);
            $clientFile = $clientDir . 'zentaoClient.zip';
            $archive    = new pclzip($clientFile);

            if($os == 'mac')
            {
                $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'xuanxuan.app/Contents/Resouces');
            }
            else
            {
                $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'resources/build-in');
            }

            if($result == 0) die("Error : " . $archive->errorInfo(true));

            unlink($clientDir);
            
            $this->fetch('file', 'sendDownHeader', array('fileName' => 'client.zip', 'zip', $zipContent));
        }

        $this->view->os      = $os;
        $this->view->confirm = $confirm;
        $this->display();
    }

    public function ajaxGetClient($os)
    {
        set_time_limit (0);

        $clientDir = $this->app->getBasePath() . 'tmp/cache/client/';
        if(!is_dir($clientDir)) mkdir($clientDir, 0755, true);

        if($os == 'windows64') $clientName = "xuanxuan.win64.zip";
        if($os == 'windows32') $clientName = "xuanxuan.win32.zip";
        if($os == 'linux64')   $clientName = "xuanxuan.linux.x64.zip";
        if($os == 'linux32')   $clientName = "xuanxuan.linux.ia32.zip";
        if($os == 'mac')       $clientName = "xuanxuan.mac.zip";

        $tmpDir = $clientDir . '/tmp/';
        if(!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        $packageFile = $clientDir . $clientName;
        if(!file_exists($packageFile))
        {
            $handle = "http://dl.pts.com/xuanxuan/xuanxuan.win64.zip";
        }
        else
        {
            $handle = $packageFile;
        }

        $client = $tmpDir . 'zentaoClient.zip';

        $clientHd = fopen($handle, "rb");
        if($clientHd)
        {
            $newf = fopen($client, "wb");
            if($newf)
            {
                while(!feof($clientHd))
                {
                    fwrite($newf, fread($clientHd, 1024 * 8 ), 1024 * 8 );
                }
            }
        }

        if ($clientHd) fclose($clientHd);
        if ($newf)     fclose($newf);

        $response = array();
        $response['result'] = 'success';

        $this->send($response);
    }

    public function ajaxGetDownProgress($os = '')
    {
        $response = array();
        $response['result'] = 'unfinished';

        $this->send($response);
    }

    /**
     * Down notify.
     * 
     * @access public
     * @return void
     */
    public function downNotify()
    {
        $notifyDir = $this->app->getBasePath() . 'tmp/cache/notify/';
        if(!is_dir($notifyDir))mkdir($notifyDir, 0755, true);

        $account     = $this->app->user->account;
        $packageFile = $notifyDir . $account . 'notify.zip';
        $loginFile   = $notifyDir . 'config.json';

        /* write login info into tmp file. */
        $loginInfo = new stdclass();
        $userInfo  = new stdclass();
        $userInfo->Account        = $account;
        $userInfo->Url            = common::getSysURL() . $this->config->webRoot;
        $userInfo->PassMd5        = '';
        $userInfo->Role           = $this->app->user->role;
        $userInfo->AutoSignIn     = true;
        $userInfo->Lang           = $this->cookie->lang;
        $loginInfo->User          = $userInfo;
        $loginInfo->LastLoginTime = time() / 86400 + 25569;
        $loginInfo = json_encode($loginInfo);

        file_put_contents($packageFile, file_get_contents("http://dl.cnezsoft.com/notify/newest/zentaonotify.win_32.zip"));
        file_put_contents($loginFile, $loginInfo);

        define('PCLZIP_TEMPORARY_DIR', $notifyDir);
        $this->app->loadClass('pclzip', true);

        /* remove the old config.json, add a new one. */
        $archive = new pclzip($packageFile);
        $result = $archive->delete(PCLZIP_OPT_BY_NAME, 'config.json');
        if($result == 0) die("Error : " . $archive->errorInfo(true));

        $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'notify');
        if($result == 0) die("Error : " . $archive->errorInfo(true));
        
        $zipContent = file_get_contents($packageFile);
        unlink($loginFile);
        unlink($packageFile);
        $this->fetch('file', 'sendDownHeader', array('fileName' => 'notify.zip', 'zip', $zipContent));
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
        $session  = $this->loadModel('user')->isLogon() ? '?' . $this->config->sessionVar . '=' . session_id() : '';

        if(!extension_loaded('gd'))
        {
            $this->view->noGDLib = sprintf($this->lang->misc->noGDLib, $loginAPI);
            die($this->display());
        }

        $this->app->loadClass('qrcode');
        QRcode::png($loginAPI . $session, false, 4, 9);
    }

    /**
     * Ajax ignore browser.
     * 
     * @access public
     * @return void
     */
    public function ajaxIgnoreBrowser()
    {
        $this->loadModel('setting')->setItem($this->app->user->account . '.common.global.browserNotice', 'true');
    }

    /**
     * Show version changelog
     * @access public
     * @return viod
     */
    public function changeLog($version = '')
    {
        if(empty($version)) $version  = key($this->lang->misc->feature->all);
        $this->view->version  = $version;
        $this->view->features = zget($this->lang->misc->feature->all, $version, '');

        $detailed      = '';
        $changeLogFile = $this->app->getBasePath() . 'doc' . DS . 'CHANGELOG';
        if(file_exists($changeLogFile))
        {
            $handle = fopen($changeLogFile, 'r');
            $tag    = false;
            while($line = fgets($handle))
            {
                $line = trim($line);
                if($tag and empty($line)) break;
                if($tag) $detailed .= $line . '<br />';

                if(preg_match("/{$version}$/", $line) > 0) $tag = true;
            }
            fclose($handle);
        }
        $this->view->detailed = $detailed;
        $this->display();
    }
}
