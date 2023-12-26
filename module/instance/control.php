<?php
declare(strict_types=1);
/**
 * The control file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.zentao.net
 */
class instance extends control
{
    /**
     * Construct function.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('action');
        $this->loadModel('cne');
        $this->loadModel('store');
    }

    /**
     * 查看应用详情。
     * Show instance view.
     *
     * @param  int    $id
     * @param  string $type
     * @param  string $tab
     * @access public
     * @return void
     */
    public function view(int $id, string $type = 'store', string $tab = 'baseinfo')
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        if($type === 'store')
        {
            $this->storeView($id, $tab);
        }
        else
        {
            $instance = $this->loadModel('gitea')->fetchByID($id);
            $instance->status      = '';
            $instance->source      = 'user';
            $instance->externalID  = $instance->id;
            $instance->runDuration = 0;
            $instance->appName     = $instance->type;
            $instance->createdAt   = $instance->createdDate;

            $instanceMetric = new stdclass();
            $instanceMetric->cpu    = 0;
            $instanceMetric->memory = 0;

            $this->view->title           = $instance->name;
            $this->view->instance        = $instance;
            $this->view->cloudApp        = array();
            $this->view->seniorAppList   = array();
            $this->view->actions         = $this->loadModel('action')->getList($instance->type, $id);
            $this->view->defaultAccount  = '';
            $this->view->instanceMetric  = $instanceMetric;
            $this->view->currentResource = '';
            $this->view->customItems     = array();
            $this->view->backupList      = array();
            $this->view->hasRestoreLog   =  false;
            $this->view->latestBackup    = array();
            $this->view->dbList          = array();
            $this->view->domain          = '';
        }

        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->tab   = $tab;
        $this->view->type  = $type;
        $this->display();
    }

    /**
     * 查看商店应用详情。
     * Show instance view.
     *
     * @param  int    $id
     * @param  string $tab
     * @access public
     * @return void
     */
    protected function storeView(int $id, string $tab = 'baseinfo')
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        $instance = $this->instance->getByID($id);
        if(empty($instance)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->instance->instanceNotExists, 'locate' => $this->createLink('space', 'browse'))));

        $instance->latestVersion = $this->store->appLatestVersion($instance->appID, $instance->version);

        $instance = $this->instance->freshStatus($instance);

        $instanceMetric = $this->cne->instancesMetrics(array($instance));
        $instanceMetric = $instanceMetric[$instance->id];

        $backupList   = array();
        $latestBackup = new stdclass();
        if($tab == 'backup') $backupList = $this->instance->backupList($instance);
        if(count($backupList)) $latestBackup = reset($backupList);

        $hasRestoreLog = false;
        foreach($backupList as $backup)
        {
            $backup->latest_restore_time   = 0;
            $backup->latest_restore_status = '';
            foreach($backup->restores as $restore)
            {
                $hasRestoreLog = true;
                if($restore->create_time > $backup->latest_restore_time)
                {
                    $backup->latest_restore_time   = $restore->create_time;
                    $backup->latest_restore_status = $restore->status;
                }
            }
        }

        $dbList          = $this->cne->appDBList($instance);
        $currentResource = $this->cne->getAppConfig($instance);
        $customItems     = $this->cne->getCustomItems($instance);

        if($instance->status == 'running') $this->instance->saveAuthInfo($instance);
        if(in_array($instance->chart, $this->config->instance->devopsApps))
        {
            $url      = strstr(getWebRoot(true), ':', true) . '://' . $instance->domain;
            $pipeline = $this->loadModel('pipeline')->getByUrl($url);
            $instance->externalID = !empty($pipeline) ? $pipeline->id : 0;
        }

        $this->view->title           = $instance->appName;
        $this->view->instance        = $instance;
        $this->view->cloudApp        = $this->loadModel('store')->getAppInfoByChart($instance->chart, $instance->channel, false);
        $this->view->seniorAppList   = $tab == 'baseinfo' ? $this->instance->seniorAppList($instance, $instance->channel) :  array();
        $this->view->actions         = $this->loadModel('action')->getList('instance', $id);
        $this->view->defaultAccount  = $this->cne->getDefaultAccount($instance);
        $this->view->instanceMetric  = $instanceMetric;
        $this->view->currentResource = $currentResource;
        $this->view->customItems     = $customItems;
        $this->view->backupList      = $backupList;
        $this->view->hasRestoreLog   = $hasRestoreLog;
        $this->view->latestBackup    = $latestBackup;
        $this->view->dbList          = $dbList;
        $this->view->domain          = $this->cne->getDomain($instance);
    }

    /**
     * 展示、保存应用配置。
     * Display or save auto backup settings.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function backupSettings(int $instanceID)
    {
        $instance = $this->instance->getByID($instanceID);

        if($_POST)
        {
            $this->instance->saveAutoBackupSettings($instance);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $backupSettings = $this->instance->getAutoBackupSettings($instanceID);

            $startTime = strtotime($backupSettings->backupTime);
            if($startTime < time()) $startTime = strtotime("+1 day $backupSettings->backupTime");

            if($backupSettings->autoBackup)
            {
                $startBackupMessage = sprintf($this->lang->instance->backup->firstStartTime, $instance->name, date('Y-m-d H:i:s', $startTime));
                return $this->send(array('result' => 'success', 'message' => $startBackupMessage));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->instance->backup->disableAutoBackup));
        }

        $backupSettings = $this->instance->getAutoBackupSettings($instanceID);

        $this->view->title          = $this->lang->instance->backup->autoBackup;
        $this->view->instance       = $instance;
        $this->view->backupSettings = $backupSettings;

        $this->display();
    }

    /**
     * 还原应用配置。
     * Display or save auto restore settings.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function restoreSettings(int $instanceID)
    {
        $instance = $this->instance->getByID($instanceID);

        if($_POST)
        {
            $this->instance->saveAutoRestoreSettings($instance);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $restoreSettings = $this->instance->getAutoRestoreSettings($instanceID);

            $startTime = strtotime($restoreSettings->restoreTime);
            if($startTime < time()) $startTime = strtotime("+1 day $restoreSettings->restoreTime");

            if($restoreSettings->autoRestore)
            {
                $startRestoreMessage = sprintf($this->lang->instance->restore->firstStartTime, $instance->name, date('Y-m-d H:i:s', $startTime));
                return $this->send(array('result' => 'success', 'message' => $startRestoreMessage));
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->instance->restore->disableAutoRestore));
        }

        $this->view->title           = $this->lang->instance->restore->autoRestore;
        $this->view->instance        = $instance;
        $this->view->restoreSettings = $this->instance->getAutoRestoreSettings($instanceID);

        $this->display();
    }

    /**
     * 自动备份。
     * Cron task of auto backup.
     *
     * @param  string $key
     * @access public
     * @return void
     */
    public function autoBackup(string $key)
    {
        if($this->config->instance->enableAutoRestore) return; // Only one of auto backup and auto restore can be enabled.

        $this->app->saveLog('Run auto backup at: ' . date('Y-md-d H:i:s'));

        if($key != helper::readKey()) return;

        $this->instance->autoBackup();
    }

    /**
     * 自动还原。
     * Cron task of auto restore.
     *
     * @param  string $key
     * @access public
     * @return void
     */
    public function autoRestore(string $key)
    {
        if(!$this->config->instance->enableAutoRestore) return; // Only one of auto backup and auto restore can be enabled.

        $this->app->saveLog('Run auto restore at: ' . date('Y-md-d H:i:s'));

        if($key != helper::readKey()) return;

        $this->instance->autoRestore();
    }

    /**
     * 设置应用。
     * Setting instance.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function setting(int $id)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $currentResource = new stdclass;
        $instance        = $this->instance->getByID($id);
        $currentResource = $this->cne->getAppConfig($instance);
        if(!empty($_POST))
        {
            $newInstance = fixer::input('post')->trim('name')->get();
            $memoryKb    = $newInstance->memory_kb;
            unset($newInstance->memory_kb);

            if(intval($currentResource->max->memory / 1024) != $memoryKb)
            {
                /* Check free memory size is enough or not. */
                $clusterResource = $this->cne->cneMetrics();
                $freeMemory      = intval($clusterResource->metrics->memory->allocatable * 0.9); // Remain 10% memory for system.
                if($memoryKb * 1024 > $freeMemory) $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->notEnoughResource));

                /* Request CNE to adjust memory size. */
                if(!$this->instance->updateMemorySize($instance, $memoryKb * 1024))
                {
                    $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
            }

            $this->instance->updateByID($id, $newInstance);
            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($newInstance->name != $instance->name)
            {
                $this->action->create('instance', $instance->id, 'editname', '', json_encode(array('result' => array('result' => 'success'), 'data' => array('oldName' => $instance->name, 'newName' => $newInstance->name))));
            }
            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->currentResource = $currentResource;
        $this->view->instance        = $instance;

        $this->display();
    }

    /**
     * Upgrade to senior serial.
     *
     * @param  int    $instanceID
     * @param  int    $seniorAppID
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function toSenior(int $instanceID, int $seniorAppID, string $confirm = 'no')
    {
        $instance = $this->instance->getByID($instanceID);
        $cloudApp = $this->store->getAppInfo($seniorAppID, $instance->channel, false);
        if(empty($cloudApp)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->noAppInfo));

        if($confirm == 'yes')
        {
            $success = $this->instance->upgradeToSenior($instance, $cloudApp);
            if($success) $this->send(array('result' => 'success', 'message' => '', 'locate' => $this->inLink('view', "id=$instance->id")));

            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->view->title    = $this->lang->instance->upgradeToSenior;
        $this->view->instance = $instance;
        $this->view->cloudApp = $cloudApp;

        $this->display();
    }

    /**
     * Upgrade instnace
     *
     * @param  int    $id
     * @access public
     * @return mixed
     */
    public function upgrade(int $id)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $instance = $this->instance->getByID($id);
        $instance->latestVersion = $this->store->appLatestVersion($instance->appID, $instance->version);

        if($_POST)
        {
            if(empty($instance->latestVersion)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->noHigherVersion, 'closeModal' => true));

            $postData = fixer::input('post')->get();

            if($postData->confirm == 'yes') $success = $this->instance->upgrade($instance, $instance->latestVersion->version, $instance->latestVersion->app_version);

            $logExtra = array('result' => 'success', 'data' => array('oldVersion' => $instance->appVersion, 'newVersion' => $instance->latestVersion->app_version));
            if(!$success)
            {
                $logExtra['result'] = 'fail';
                $this->action->create('instance', $instance->id, 'upgrade', '', json_encode($logExtra));
                return $this->send(array('result' => 'fail', 'message' => !empty($logExtra['message']) ? $logExtra['message'] : $this->lang->instance->notices['upgradeFail'], 'closeModal' => true));
            }

            $this->action->create('instance', $instance->id, 'upgrade', '', json_encode($logExtra));
            return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['upgradeSuccess'], 'load' => $this->createLink('instance', 'view', "id=$id"), 'closeModal' => true));
        }

        $this->view->title    = $this->lang->instance->upgrade . $instance->name;
        $this->view->instance = $instance;

        $this->display();
    }

    /**
     * 访问一个应用。
     * Visit a app.
     *
     * @param  int    $id
     * @param  int    $externalID
     * @access public
     * @return void
     */
    public function visit(int $id, int $externalID = 0)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        if(!$externalID)
        {
            $instance = $this->instance->getByID($id);
            $url      = $this->instance->url($instance);
        }
        else
        {
            $pipeline = $this->loadModel('pipeline')->getByID($externalID);
            $url      = $pipeline->url;
        }

        return $this->send(array('result' => 'success', 'callback' => "window.open('{$url}')"));
    }

    /**
     * 创建手工配置外部应用。
     * Create a external app.
     *
     * @param  string $type
     * @access public
     * @return viod
     */
    public function createExternalApp(string $type)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $this->loadModel('sonarqube');
        $this->app->loadLang('pipeline');

        $externalApp = form::data($this->config->instance->form->create)->get();
        $externalApp->type = $type;
        $externalApp->url  = rtrim($externalApp->url, '/');
        if(!$this->instance->checkAppNameUnique($externalApp->name)) return $this->send(array('result' => false, 'message' => array('name' => sprintf($this->lang->error->repeat, $this->lang->pipeline->name, $externalApp->name))));

        $appID = $this->loadModel('pipeline')->create($externalApp);
        if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->loadModel('action')->create($type, $appID, 'created');
        return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $this->createLink('space', 'browse')));
    }

    /**
     * 编辑手工配置外部应用。
     * Edit a external app.
     *
     * @param  int    $externalID
     * @access public
     * @return viod
     */
    public function editExternalApp(int $externalID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $oldApp = $this->loadModel('pipeline')->getByID($externalID);

        if($_POST)
        {
            $instance = form::data($this->config->instance->form->edit)->get();
            $this->pipeline->update($externalID, $instance);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $app      = $this->pipeline->getByID($externalID);
            $actionID = $this->loadModel('action')->create($app->type, $externalID, 'edited');
            $changes  = common::createChanges($oldApp, $app);
            $this->action->logHistory($actionID, $changes);
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true, 'closeModal' => true));
        }

        $this->app->loadLang('space');
        $this->app->loadLang('sonarqube');

        $this->view->app = $oldApp;
        $this->display();
    }

    /**
     * 删除一个外部应用。
     * Delete a external app.
     *
     * @param  int    $externalID
     * @access public
     * @return viod
     */
    public function deleteExternalApp(int $externalID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $oldApp = $this->loadModel('pipeline')->getByID($externalID);
        $actionID = $this->pipeline->deleteByObject($externalID, $oldApp->type);
        if(!$actionID)
        {
            $response['result']   = 'fail';
            $response['callback'] = sprintf('zui.Modal.alert("%s");', $this->lang->pipeline->delError);
            return $this->send($response);
        }

        $app     = $this->pipeline->getByID($externalID);
        $changes = common::createChanges($oldApp, $app);
        $this->loadModel('action')->logHistory($actionID, $changes);

        $response['load']    = true;
        $response['message'] = zget($this->lang->instance->notices, 'uninstallSuccess');
        $response['result']  = 'success';

        return $this->send($response);
    }

    /**
     * 自定义安装应用。
     * Install app by custom settings.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function customInstall(int $id)
    {
        // Disable custom installation in version 1.0.
        $storeUrl = $this->createLink('store', 'appview', "id=$id");
        return js::execute("window.parent.location.href='{$storeUrl}';");
    }

    /**
     * 安装应用。
     * Install app.
     *
     * @param  int    $appID
     * @param  string $checkResource
     * @access public
     * @return void
     */
    public function install(int $appID, string $checkResource = 'true')
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $cloudApp = $this->store->getAppInfo($appID);
        if(empty($cloudApp)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->instance->errors->noAppInfo)));

        $versionList = $this->store->appVersionList($cloudApp->id);
        $mysqlList   = $this->cne->sharedDBList('mysql');
        $pgList      = $this->cne->sharedDBList('postgresql');
        if(!empty($_POST))
        {
            $customData = form::data($this->config->instance->form->install)->get();
            if($customData->version && isset($versionList[$customData->version])) $customData->app_version = $versionList[$customData->version]->app_version;

            $this->instanceZen->checkForInstall($customData);

            if($checkResource == 'true')
            {
                $resource = new stdclass();
                $resource->cpu    = $cloudApp->cpu;
                $resource->memory = $cloudApp->memory;

                $result = $this->cne->tryAllocate(array($resource));
                if(!isset($result->code) || $result->code != 200) return $this->send(array('callback' => 'alertResource()'));
            }

            /* If select the version, replace the latest version of App by selected version. */
            if($customData->version)
            {
                $cloudApp->version     = $customData->version;
                $cloudApp->app_version = $customData->app_version;
            }

            $sharedDB = new stdclass;
            if(isset($cloudApp->dependencies->mysql) && $customData->dbType == 'sharedDB')
            {
                $sharedDB = zget($mysqlList, $customData->dbService);
            }
            elseif(isset($cloudApp->dependencies->postgresql) && $customData->dbType == 'sharedDB')
            {
                $sharedDB = zget($pgList, $customData->dbService);
            }
            $instance = $this->instance->install($cloudApp, $sharedDB, $customData);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if(!$instance) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->notices['installFail']));

            return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['installSuccess'], 'load' => $this->createLink('instance', 'view', "id=$instance->id"), 'closeModal' => true));
        }

        $this->view->versionList = array();
        foreach($versionList as $version) $this->view->versionList[$version->version] = $version->app_version . " ({$version->version})";

        $this->view->title       = $this->lang->instance->install . $cloudApp->alias;
        $this->view->cloudApp    = $cloudApp;
        $this->view->thirdDomain = $this->instance->randThirdDomain();
        $this->view->mysqlList   = $this->instance->dbListToOptions($mysqlList);
        $this->view->pgList      = $this->instance->dbListToOptions($pgList);

        $this->display();
    }

    /**
     * 卸载应用。
     * Uninstall app instance.
     *
     * @param  int    $instanceID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxUninstall(int $instanceID, string $type = '')
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        if($type !== 'store')
        {
            $instance = $this->loadModel('pipeline')->getByID($instanceID);
            if(!$instance) return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['success'], 'load' => $this->createLink('space', 'browse')));

            if($instance->type == 'nexus') return $this->deleteExternalApp($instance->id);
            return $this->fetch($instance->type, 'delete', array('id' => $instance->id));
        }
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'success', 'message' => $this->lang->instance->notices['success'], 'load' => $this->createLink('space', 'browse')));

        $externalApp = $this->loadModel('space')->getExternalAppByApp($instance);
        if($externalApp)
        {
            $actionID = $this->loadModel('pipeline')->deleteByObject($externalApp->id, strtolower($instance->appName));
            if(!$actionID) return $this->send(array('result' => 'fail', 'message' => $this->lang->pipeline->delError));
        }

        $success = $this->instance->uninstall($instance);
        $this->action->create('instance', $instance->id, 'uninstall', '', json_encode(array('result' => $success, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
        if($success) return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'uninstallSuccess'), 'load' => $this->createLink('space', 'browse')));

        return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'uninstallFail')));
    }

    /**
     * 启动应用实例。
     * Start app instance.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxStart(int $instanceID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $result = $this->instance->start($instance);
        $this->action->create('instance', $instance->id, 'start', '', json_encode(array('result' => $result, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));

        if($result->code == 200) return $this->send(array('result' => 'success', 'load' => true, 'message' => zget($this->lang->instance->notices, 'startSuccess')));

        return $this->send(array('result' => 'fail', 'message' => !empty($result->message) ? $result->message : zget($this->lang->instance->notices, 'startFail')));
    }

    /**
     * 停止应用实例。
     * Stop app instance.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxStop(int $instanceID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $result = $this->instance->stop($instance);
        $this->action->create('instance', $instance->id, 'stop', '', json_encode(array('result' => $result, 'app' => array('alias' => $instance->appName, 'app_version' => $instance->version))));
        if($result->code == 200) return $this->send(array('result' => 'success', 'load' => true, 'message' => zget($this->lang->instance->notices, 'stopSuccess')));

        return $this->send(array('result' => 'fail', 'message' => !empty($result->message) ? $result->message : zget($this->lang->instance->notices, 'stopFail')));
    }

    /**
     * 查看应用的运行状态。
     * Query status of app instance.
     *
     * @access public
     * @return void
     */
    public function ajaxStatus()
    {
        $postData = fixer::input('post')->setDefault('idList', array())->get();

        $instances  = $this->instance->getByIdList($postData->idList);
        $statusList = $this->instance->batchFresh($instances);

        return $this->send(array('result' => 'success', 'data' => $statusList));
    }

    /**
     * 备份应用。
     * Backup instnacd by ajax.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxBackup($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        $success = $this->instance->backup($instance, $this->app->user);
        if(!$success)
        {
            $this->action->create('instance', $instance->id, 'backup', '', json_encode(array('result' => array('result' => 'fail'))));
            return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'backupFail')));
        }

        $this->action->create('instance', $instance->id, 'backup', '', json_encode(array('result' => array('result' => 'success'))));
        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'backupSuccess')));
    }

    /**
     * 还原应用。
     * Restore instance by ajax
     *
     * @access public
     * @return void
     */
    public function ajaxRestore()
    {
        $postData = fixer::input('post')
            ->trim('instanceID')
            ->trim('backupName')->get();

        if(empty($postData->instanceID) || empty($postData->backupName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->wrongRequestData));

        $instance = $this->instance->getByID($postData->instanceID);
        if(empty($instance))return print(js::alert($this->lang->instance->instanceNotExists) . js::locate($this->createLink('space', 'browse')));

        $success = $this->instance->restore($instance, $this->app->user, $postData->backupName);
        if(!$success)
        {
            $this->action->create('instance', $instance->id, 'restore', '', json_encode(array('result' => array('result' => 'fail'))));
            return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'restoreFail')));
        }

        $this->action->create('instance', $instance->id, 'restore', '', json_encode(array('result' => array('result' => 'success'))));
        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'restoreSuccess')));
    }

    /**
     * 删除备份。
     * Delete backup by ajax.
     *
     * @param  int    $backupID
     * @access public
     * @return void
     */
    public function ajaxDeleteBackup(int $backupID)
    {
        $success = $this->instance->deleteBackup($backupID, $this->app->user);
        if(!$success) return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'deleteFail')));

        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'deleteSuccess')));
    }

    /**
     * 授权数据库。
     * Generate database auth parameters and jump to login page.
     *
     * @access public
     * @return void
     */
    public function ajaxDBAuthUrl()
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        $post = fixer::input('post')
            ->setDefault('namespace', 'default')
            ->setDefault('instanceID', 0)
            ->setDefault('dbType', '')
            ->get();
        if(empty($post->dbName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->dbNameIsEmpty));

        $instance = $this->instance->getByID($post->instanceID);
        if(empty($instance)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $detail = $this->loadModel('cne')->appDBDetail($instance, $post->dbName);
        if(empty($detail)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->notFoundDB));

        $dbAuth = array();
        $dbAuth['driver']   = zget($this->config->instance->adminer->dbTypes, $post->dbType, '');
        $dbAuth['server']   = $detail->host . ':' . $detail->port;
        $dbAuth['username'] = $detail->username;
        $dbAuth['db']       = $detail->database;
        $dbAuth['password'] = $detail->password;

        $url = '/adminer?' . http_build_query($dbAuth);
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('url' => $url)));
    }

    /**
     * 调整实例内存大小。
     * Adjust instance memory size by ajax.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxAdjustMemory($instanceID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $postData = fixer::input('post')->get();

        /* Check free memory size is enough or not. */
        $clusterResource = $this->cne->cneMetrics();
        $freeMemory      = intval($clusterResource->metrics->memory->allocatable * 0.9); // Remain 10% memory for system.
        if($postData->memory_kb * 1024 > $freeMemory) $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->notEnoughResource));

        /* Request CNE to adjust memory size. */
        $instance = $this->instance->getByID($instanceID);
        if(!$this->instance->updateMemorySize($instance, $postData->memory_kb * 1024))
        {
            $this->send(array('result' => 'fail', 'message' => dao::getError()));
        }

        $this->send(array('result' => 'success', 'message' => ''));
    }

    /**
     * 启用/禁用LDAP。
     * Switch LDAP between enable and disable by ajax.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxSwitchLDAP(int $instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        $postData = fixer::input('post')->get();

        if($this->instance->switchLDAP($instance, $postData->enableLDAP == 'true'))
        {
            $this->send(array('result' => 'success', 'message' => ''));
        }

        $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->switchLDAPFailed));
    }

    /**
     * 启用/禁用SMTP。
     * Switch SMTP between enable and disable by ajax.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxSwitchSMTP($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        $postData = fixer::input('post')->get();

        if($this->instance->switchSMTP($instance, $postData->enableSMTP == 'true'))
        {
            $this->send(array('result' => 'success', 'message' => ''));
        }

        $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->switchSMTPFailed));
    }

    /**
     * 更新自定义配置。
     * Update custom settings by ajax. For example: env variables.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function ajaxUpdateCustom($instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if(!$instance) $this->send(array('result' => 'fail', 'message' => $this->lang->instance->instanceNotExists));

        $postData = fixer::input('post')->get();

        $settings = new stdclass;
        $settings->force_restart = true;
        $settings->settings_map  = new stdclass;
        $settings->settings_map->custom = $postData;

        if($this->cne->updateConfig($instance, $settings))
        {
            $this->action->create('instance', $instanceID, 'updatecustom', '', json_encode($settings));
            $this->send(array('result' => 'success', 'message' => ''));
        }

        $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->setEnvFailed));
    }

    /**
     * 删除过期的demo实例。
     * Delete expired demo instance by cron.
     *
     * @access public
     * @return void
     */
    public function deleteExpiredDemoInstance()
    {
        if(empty($this->config->demoAccounts)) return $this->send(array('result' => 'fail', 'message' => 'This api is only for demo enviroment.'));

        $this->instance->deleteExpiredDemoInstance();

        $this->send(array('result' => 'success', 'message' => ''));
    }

    /**
     * 获取实例信息。
     * Get instance info for q tool in console.
     *
     * @param  int    $id
     * @access public
     * @return mixed
     */
    public function apiDetail(int $id)
    {
        if(!$this->checkCneToken())
        {
            helper::setStatus(401);
            return print(json_encode(array('code' => 401, 'message' => 'Invalid token.')));
        }

        if(empty($id)) return print(json_encode(array('code' => 401, 'message' => 'Invalid id.')));

        $instance = $this->instance->getByID($id);
        if(empty($instance)) return print(json_encode(array('code' => 404, 'message' => 'Not found.', 'data' => array())));

        $instance->space = $instance->spaceData && isset($instance->spaceData->k8space) ? $instance->spaceData->k8space : '';
        unset($instance->desc);
        unset($instance->spaceData);

        return print(json_encode(array('code' => 200, 'message' => 'success', 'data' => $instance)));
    }

    /**
     * 获取实例列表。
     * Get instances list by account through api for q tool.
     *
     * @access public
     * @return void
     */
    public function apiBrowse()
    {
        if(!$this->checkCneToken())
        {
            helper::setStatus(401);
            return print(json_encode(array('code' => 401, 'message' => 'Invalid token.')));
        }

        $requestBody = json_decode(file_get_contents("php://input"));
        $account = zget($requestBody, 'account', '');
        if(empty($account)) return print(json_encode(array('code' => 700, 'message' => 'Account is required.', 'data' => new stdclass)));

        $recPerPage = zget($requestBody, 'perPage', 20);
        $pageID     = zget($requestBody, 'page', 1);

        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $recPerPage, $pageID);

        $instanceList = $this->instance->getByAccount($account, $pager);

        $result = new stdclass;
        $result->list      = $instanceList;
        $result->page      = $pageID;
        $result->perPage   = $recPerPage;
        $result->pageTotal = $pager->pageTotal;
        $result->total     = $pager->recTotal;

        return print(json_encode(array('code' => 200, 'message' => 'success', 'data' => $result)));
    }

    /**
     * 通过API安装应用。
     * Install app by api for q tool.
     *
     * @access public
     * @return void
     */
    public function apiInstall()
    {
        if(!$this->checkCneToken())
        {
            helper::setStatus(401);
            return print(json_encode(array('code' => 401, 'message' => 'Invalid token.')));
        }

        $requestBody = json_decode(file_get_contents("php://input"));
        $chart = zget($requestBody , 'chart', '');
        if(empty($chart)) return print(json_encode(array('code' => 701, 'message' => 'Param chart is required.')));

        $user = null;
        $account = zget($requestBody, 'account', '');
        if($account) $user = $this->loadModel('user')->getById($account);
        if(empty($user)) $user = $this->loadModel('company')->getAdmin();
        if(empty($user)) return print(json_encode(array('code' => 703, 'message' => 'No user.')));

        $this->app->user = $user;

        $name    = zget($requestBody , 'name', '');
        $channel = zget($requestBody , 'channel', 'stable');
        $k8name  = zget($requestBody , 'k8name', '');
        if($k8name && $this->instance->k8nameExists($k8name))  return print(json_encode(array('code' => 706, 'message' => $k8name . ' has been used, please change it and try again.')));

        $thirdDomain = zget($requestBody , 'domain', '');
        if($this->instance->domainExists($thirdDomain))  return print(json_encode(array('code' => 705, 'message' => $thirdDomain . ' has been used, please change it and try again.')));

        $cloudApp = $this->store->getAppInfoByChart($chart, $channel, false);
        if(empty($cloudApp)) return print(json_encode(array('code' => 702, 'message' => 'App not found.')));

        $result = $this->instance->apiInstall($cloudApp, $thirdDomain, $name, $k8name, $channel);

        if($result) return print(json_encode(array('code' => 200, 'message' => 'success', 'data' => new stdclass)));

        return print(json_encode(array('code' => 704, 'message' => 'Fail to install app.', 'data' => new stdclass)));
    }

    /**
     * 检查CNE token。
     * Check CNE token.
     *
     * @access private
     * @return bool
     */
    private function checkCneToken()
    {
        $token = zget($_SERVER, 'HTTP_TOKEN');
        return $token == $this->config->CNE->api->token;
    }
}
