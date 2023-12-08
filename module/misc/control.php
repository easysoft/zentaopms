<?php
declare(strict_types=1);
/**
 * The control file of misc of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     misc
 * @version     $Id: control.php 5128 2013-07-13 08:59:49Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
class misc extends control
{
    /**
     * 保持心跳防止session过期。
     * Ping the server every 5 minutes to keep the session.
     *
     * @access public
     * @return void
     */
    public function ping()
    {
        if(empty($this->config->global->sn) && mt_rand(0, 1) == 1) $this->loadModel('setting')->setSN();
    }

    /**
     * 展示php服务器的配置信息。
     * Show php info.
     *
     * @access public
     * @return void
     */
    public function phpinfo()
    {
        phpinfo();
    }

    /**
     * 展示关于禅道页面。
     * Show about info of zentao.
     *
     * @access public
     * @return void
     */
    public function about()
    {
        $this->display();
    }

    /**
     * 检查是否存在更新的禅道版本。
     * Check current version is latest or not.
     *
     * @param  string $sn
     * @param  string $force
     * @access public
     * @return void
     */
    public function checkUpdate(string $sn = '', string $force = '')
    {
        /* 检查服务端是否联网。 */
        $hasInternet = $this->session->hasInternet;
        if(empty($hasInternet))
        {
            $hasInternet = $this->loadModel('admin')->checkInternet();
            $this->session->set('hasInternet', $hasInternet);
            if(!$hasInternet) return;
        }
        if($this->session->isSlowNetwork) return;

        /* 检查距离上一次调用函数是否已超过1小时。 */
        $startTime = microtime(true);
        if(empty($force) && !empty($this->config->checkUpdate->lastTime) && $startTime - (float)$this->config->checkUpdate->lastTime < 3600) return;
        $this->loadModel('setting')->setItem('system.common.checkUpdate.lastTime',  $startTime);

        if(empty($sn)) $sn = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=sn');

        $website = $this->config->misc->api;

        if(isset($this->config->qcVersion)) $website = $this->config->misc->qucheng;
        if(isset($this->config->isINT))     $website = $this->config->misc->enApi;

        /* 获取禅道或者渠成的最新版本。 */
        $source = isset($this->config->qcVersion) ? 'qucheng' : 'zentao';
        $lang   = str_replace('-', '_', $this->app->getClientLang());
        $link   = $website . "/updater-getLatest-{$this->config->version}-$source-$lang-$sn.html";

        $latestVersionList = common::http($link);

        if(!isset($this->config->global->latestVersionList) || $this->config->global->latestVersionList != $latestVersionList)
        {
            $this->loadModel('setting')->setItem('system.common.global.latestVersionList', $latestVersionList);
        }

        /* 请求超过一定时间后判断为网络请求缓慢。 */
        if(microtime(true) - $startTime > $this->config->timeout / 1000) $this->session->set('isSlowNetwork', true);
    }

    /**
     * 打印 hello world。
     * Check model extension logic.
     *
     * @access public
     * @return string
     */
    public function checkExtension(): string
    {
        echo $this->miscZen->hello();
    }

    /**
     * 下载桌面提醒。
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
        $userInfo  = new stdclass();
        $userInfo->Account        = $account;
        $userInfo->Url            = common::getSysURL() . $this->config->webRoot;
        $userInfo->PassMd5        = '';
        $userInfo->Role           = $this->app->user->role;
        $userInfo->AutoSignIn     = true;
        $userInfo->Lang           = $this->cookie->lang;

        $loginInfo = new stdclass();
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
        if($result == 0) return print("Error : " . $archive->errorInfo(true));

        $result = $archive->add($loginFile, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_ADD_PATH, 'notify');
        if($result == 0) return print("Error : " . $archive->errorInfo(true));

        $zipContent = file_get_contents($packageFile);
        unlink($loginFile);
        unlink($packageFile);

        $this->fetch('file', 'sendDownHeader', array('fileName' => 'notify.zip', 'zip', $zipContent));
    }

    /**
     * 忽略浏览器通知。
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
     * 显示当前版本的变更日志。
     * Show version changelog.
     *
     * @access public
     * @return void
     */
    public function changeLog(string $version = '')
    {
        if(empty($version)) $version  = key($this->lang->misc->feature->all);
        $this->view->version  = $version;
        $this->view->features = zget($this->lang->misc->feature->all, $version, '');

        $detailed = '';
        $changeLogFile = $this->app->getBasePath() . 'doc' . DS . 'CHANGELOG';
        if(file_exists($changeLogFile))
        {
            $handle = fopen($changeLogFile, 'r');
            $tag    = false;
            while($line = fgets($handle))
            {
                $line = trim($line);
                if($tag && empty($line)) break;
                if($tag) $detailed .= $line . '<br />';

                if(preg_match("/{$version}$/", $line) > 0) $tag = true;
            }
            fclose($handle);
        }
        $this->view->detailed = $detailed;
        $this->display();
    }

    /**
     * 检查是否能访问禅道官网插件接口。
     * Check net connect.
     *
     * @access public
     * @return string
     */
    public function checkNetConnect(): string
    {
        $this->app->loadConfig('extension');
        $check = @fopen(dirname($this->config->extension->apiRoot), "r");
        print($check ? 'success' : 'fail');
    }

    /**
     * 展示验证码图片。
     * Show captcha and save to session.
     *
     * @param  string $sessionVar
     * @access public
     * @return void
     */
    public function captcha(string $sessionVar = 'captcha')
    {
        if(in_array(strtolower($sessionVar), $this->config->misc->disabledSessionVar)) die("The string {$sessionVar} is not allowed to be defined as a session field.");

        $obLevel = ob_get_level();
        for($i = 0; $i < $obLevel; $i++) ob_end_clean();

        helper::header('Content-Type', 'image/jpeg');
        $captcha = $this->app->loadClass('captcha');
        $this->session->set($sessionVar, $captcha->getPhrase());
        $captcha->build()->output();
    }

    /**
     * 记录被展开的对象ID。
     * Ajax set unfoldID.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $action       add|delete
     * @access public
     * @return string
     */
    public function ajaxSetUnfoldID(int $objectID, string $objectType, string $action = 'add'): string
    {
        $newUnfoldID = $this->post->newUnfoldID;
        if(empty($newUnfoldID)) return '';

        $account = $this->app->user->account;
        if($objectType == 'execution')
        {
            $condition   = "owner={$account}&module={$objectType}&section=task&key=unfoldTasks";
            $settingPath = $account . ".{$objectType}.task.unfoldTasks";
        }
        elseif($objectType == 'productplan')
        {
            $condition   = "owner={$account}&module={$objectType}&section=browse&key=unfoldPlans";
            $settingPath = $account . ".{$objectType}.browse.unfoldPlans";
        }
        else
        {
            $condition   = "owner={$account}&module=product&section=browse&key=unfoldStories";
            $settingPath = $account . ".{$objectType}.browse.unfoldStories";
        }

        $this->loadModel('setting');
        $setting      = $this->setting->createDAO($this->setting->parseItemParam($condition), 'select')->fetch();
        $unfoldIdList = $setting ? json_decode($setting->value, true) : array();
        $newUnfoldID  = json_decode($newUnfoldID);
        foreach($newUnfoldID as $unfoldID)
        {
            unset($unfoldIdList[$objectID][$unfoldID]);
            if($action == 'add') $unfoldIdList[$objectID][$unfoldID] = $unfoldID;
        }

        if(!empty($setting))
        {
            $this->dao->update(TABLE_CONFIG)->set('value')->eq(json_encode($unfoldIdList))->where('id')->eq($setting->id)->exec();
        }
        else
        {
            $this->setting->setItem($settingPath, json_encode($unfoldIdList));
        }

        echo 'success';
    }

    /**
     * 获取15版本之后的最新特性。
     * Features dialog.
     *
     * @access public
     * @return void
     */
    public function features()
    {
        $features = array();
        foreach($this->config->newFeatures as $feature)
        {
            $accounts = zget($this->config->global, 'skip' . ucfirst($feature), '');
            if(strpos(",$accounts,", $this->app->user->account) === false) $features[] = $feature;
        }

        $this->app->loadLang('install');

        $this->view->features = $features;
        $this->display();
    }

    /**
     * 保存已看过最新特性的记录。
     * Save viewed feature.
     *
     * @param  string $feature
     * @access public
     * @return void
     */
    public function ajaxSaveViewed(string $feature)
    {
        $accounts = zget($this->config->global, 'skip' . ucfirst($feature), '');
        if(strpos(",$accounts,", $this->app->user->account) === false) $accounts .= ',' . $this->app->user->account;
        $this->loadModel('setting')->setItem('system.common.global.skip' . ucfirst($feature), $accounts);
    }
}
