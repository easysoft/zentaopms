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
     * @param  string $action
     * @param  string $os 
     * @return void
     */
    public function downloadClient($action = 'check', $os = '')
    {
        if($_POST)
        {
            $os = $this->post->os;

            die(js::locate($this->createLink('misc', 'downloadClient', "action=getPackage&os=$os"), 'parent'));
        }

        if($action == 'check')
        {
            $error     = false;
            $errorInfo = '';

            $cacheDir = $this->app->getBasePath() . 'tmp/cache/';
            if(!is_dir($cacheDir))
            {
                $result = mkdir($cacheDir, 0755, true);
                if($result == false)
                {
                    $error = true;
                    $errorInfo = sprintf($this->lang->misc->client->errorInfo->dirNotExist, $cacheDir, $cacheDir);
                }
            }

            if(!is_writable($cacheDir))
            {
                $error = true;
                $errorInfo = sprintf($this->lang->misc->client->errorInfo->dirNotWritable, $cacheDir, $cacheDir);
            }

            $this->view->error     = $error;
            $this->view->errorInfo = $errorInfo;

            if(!$error) die(js::locate($this->createLink('misc', 'downloadClient', "action=selectPackage")));
        }

        if($action == 'selectPackage')
        {
            $os = 'windows64';
            $agentOS = helper::getOS();
            if(strpos($agentOS, 'Windows') !== false) $os = 'windows64';
            if(strpos($agentOS, 'Linux') !== false)   $os = 'linux64';
            if(strpos($agentOS, 'Mac') !== false)     $os = 'mac';

            $this->view->os = $os;
        }

        if($action == 'getPackage')
        {
            $this->view->os      = $os;
            $this->view->account = $this->app->user->account; 
        }

        if($action == 'clearTmpPackage')
        {
            $account = $this->app->user->account;
            $tmpDir  = $this->app->getBasePath() . 'tmp/cache/client/' . "$account/";

            if(is_dir($tmpDir))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($tmpDir);
            }

            die(js::closeModal('parent.parent', 'this'));
        }

        if($action == 'downloadPackage')
        {
            $account   = $this->app->user->account;
            $clientDir = $this->app->getBasePath() . 'tmp/cache/client/' . "$account/";

            $clientFile = $clientDir . 'zentaoclient.zip';
            $zipContent = file_get_contents($clientFile);
            if(is_dir($clientDir))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($clientDir);
            }
            
            $this->fetch('file', 'sendDownHeader', array('fileName' => 'zentaoclient.zip', 'zip', $zipContent));
        }

        $this->view->action = $action;
        $this->display();
    }

    /**
     * Ajax get client package.
     * 
     * @param  string $os 
     * @access public
     * @return void
     */
    public function ajaxGetClientPackage($os = '')
    {
        set_time_limit (0);
        session_write_close();

        $response = array();
        $response['result']  = 'success';
        $response['message'] = '';

        $clientDir = $this->app->getBasePath() . 'tmp/cache/client/';
        if(!is_dir($clientDir)) mkdir($clientDir, 0755, true);

        $account = $this->app->user->account;
        $tmpDir = $clientDir . "/$account/";
        if(!is_dir($tmpDir)) mkdir($tmpDir, 0755, true);

        if($os == 'windows64') $clientName = "zentaoclient.win64.zip";
        if($os == 'windows32') $clientName = "zentaoclient.win32.zip";
        if($os == 'linux64')   $clientName = "zentaoclient.linux.x64.zip";
        if($os == 'linux32')   $clientName = "zentaoclient.linux.ia32.zip";
        if($os == 'mac')       $clientName = "zentaoclient.mac.zip";

        $needCache   = false;
        $version     = $this->config->xuanxuan->version;
        $packageFile = $clientDir . $clientName;
        if(!file_exists($packageFile))
        {
            $url       = "http://dl.cnezsoft.com/zentaoclient/$version/";
            $xxFile    = $url . $clientName;
            $needCache = true;
        }
        else
        {
            $xxFile = $packageFile;
        }

        $clientFile = $tmpDir . 'zentaoclient.zip';
        if($xxHd = fopen($xxFile, "rb"))
        {
            if($clientHd = fopen($clientFile, "wb"))
            {
                while(!feof($xxHd))
                {
                    $result = fwrite($clientHd, fread($xxHd, 1024 * 8 ), 1024 * 8 );
                    if($result == false)
                    {
                        $response['result']  = 'fail';
                        $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
                        $this->send($response);
                    }
                }
            }
            else
            {
                $response['result'] = 'fail';
                $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
                $this->send($response);
            }
            fclose($xxHd);
            fclose($clientHd);
        }
        else
        {
            $response['result'] = 'fail';
            $response['message'] = sprintf($this->lang->misc->client->errorInfo->manualOpt, $xxFile);
            $this->send($response);
        }

        if($needCache) file_put_contents($packageFile, file_get_contents($clientFile));

        $this->send($response);
    }

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
        $clientDir = $this->app->getBasePath() . 'tmp/cache/client/' . "$account/";
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
        $clientFile = $clientDir . 'zentaoclient.zip';
        $archive    = new pclzip($clientFile);

        if($os == 'mac')
        {
            $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'xuanxuan.app/Contents/Resouces');
        }
        else
        {
            $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'resources/build-in');
        }

        if($result == 0)
        {
            $response['result']  = 'fail';
            $response['message'] = $archive->errorInfo(true);
            $this->send($response);
        }

        $this->send($response);
    }

    /**
     * Ajax get client package size.
     * 
     * @access public
     * @return void
     */
    public function ajaxGetPackageSize()
    {
        $account     = $this->app->user->account;
        $packageFile = $this->app->getBasePath() . 'tmp/cache/client/' . $account . '/zentaoclient.zip';

        $size = 0;
        if(file_exists($packageFile))
        {
            $size = filesize($packageFile);
            $size = $size ? round($size / 1048576, 2) : 0;
        }

        $response = array();
        $response['result'] = 'success';
        $response['size'] = $size;

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
