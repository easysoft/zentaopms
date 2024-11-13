<?php
declare(strict_types=1);
/**
 * The control file of instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Sun Guangming <sunguangming@easycorp.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.zentao.net
 * @property  instanceModel $instance
 * @property  instanceZen $instanceZen
 * @property  cneModel $cne
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
            $this->instanceZen->storeView($id, $tab);
        }
        else
        {
            $instance = $this->loadModel('gitea')->fetchByID($id);
            $instance->status      = 'running';
            $instance->source      = 'user';
            $instance->externalID  = $instance->id;
            $instance->runDuration = 0;
            $instance->appName     = $instance->type;
            $instance->createdAt   = $instance->createdDate;

            $instanceMetric = new stdclass();
            $instanceMetric->cpu    = 0;
            $instanceMetric->memory = 0;
            $instanceMetric->disk   = 0;

            $this->view->title           = $instance->name;
            $this->view->instance        = $instance;
            $this->view->actions         = $this->loadModel('action')->getList($instance->type, $id);
            $this->view->defaultAccount  = '';
            $this->view->instanceMetric  = $instanceMetric;
            $this->view->dbList          = array();
        }

        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->tab   = $tab;
        $this->view->type  = $type;
        $this->display();
    }

    /**
     * 设置应用。
     * Setting instance.
     *
     * @param  int    $id
     * @param  string $component 0|mysql
     * @access public
     * @return void
     */
    public function setting(int $id, string $component = '0')
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);
        $instance        = $this->instance->getByID($id);
        $currentResource = $this->cne->getAppConfig($instance);
        if(empty($component)) $component = false;
        $diskSettings = $this->cne->getDiskSettings($instance, $component);
        $this->lang->instance->errors->invalidDiskSize = sprintf($this->lang->instance->errors->invalidDiskSize, $diskSettings->size, $diskSettings->limit);
        $this->lang->instance->tips->resizeDisk        = sprintf($this->lang->instance->tips->resizeDisk, $diskSettings->size, $diskSettings->limit);

        if(!empty($_POST))
        {
            $newInstance = fixer::input('post')->trim('name')->get();
            $memoryKb    = $this->post->memory_kb;

            if(intval($currentResource->max->memory / 1024) != $memoryKb)
            {
                /* Check free memory size is enough or not. */
                $clusterResource = $this->cne->cneMetrics();
                $freeMemory      = intval($clusterResource->metrics->memory->allocatable * 0.9); // Remain 10% memory for system.
                if($memoryKb * 1024 > $freeMemory) $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->notEnoughResource));

                /* Request CNE to adjust memory size. */
                $instance->oldValue = $currentResource->max->memory;
                if(!$this->instance->updateMemorySize($instance, $memoryKb * 1024))
                {
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
            }

            $cpu = $this->post->cpu;
            if($currentResource->max->cpu != $cpu)
            {
                $instance->oldValue = $currentResource->max->cpu;
                if(!$this->instance->updateCpuSize($instance, $cpu))
                {
                    return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                }
            }

            $disk = $this->post->disk_gb;
            if(is_numeric($disk) && $disk != $diskSettings->size && $disk != $diskSettings->requestSize)
            {
                if($disk < $diskSettings->size || $disk > $diskSettings->limit)  return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->invalidDiskSize));
                $instance->oldValue = $diskSettings->size;
                if(!$this->instance->updateVolSize($instance, $disk . 'Gi', $diskSettings->name)) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            if(empty($component))
            {
                $this->instance->updateByID($id, $newInstance);
                if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));
                if($newInstance->name != $instance->name)
                {
                    $this->action->create('instance', $instance->id, 'editName', '', json_encode(array('result' => array('result' => 'success'), 'data' => array('oldName' => $instance->name, 'newName' => $newInstance->name))));
                }
            }

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }

        $this->view->diskSettings    = $diskSettings;
        $this->view->currentResource = $currentResource;
        $this->view->instance        = $instance;

        $this->display();
    }

    /**
     * 升级一个应用。
     * Upgrade a instance.
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
     * @return void
     */
    public function createExternalApp(string $type)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $this->loadModel('sonarqube');
        $this->app->loadLang('pipeline');

        $externalApp = form::data($this->config->instance->form->create)
            ->add('createdBy', isset($this->app->user->account) ? $this->app->user->account : '')
            ->get();
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
     * @return void
     */
    public function editExternalApp(int $externalID)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $oldApp = $this->loadModel('pipeline')->getByID($externalID);

        if($_POST)
        {
            $instance = form::data($this->config->instance->form->edit)
                ->add('editedBy', isset($this->app->user->account) ? $this->app->user->account : '')
                ->get();
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
     * @return void
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

            $sharedDB = new stdclass();
            if(isset($cloudApp->dependencies->mysql) && $customData->dbType == 'sharedDB')
            {
                $sharedDB = zget($mysqlList, $customData->dbService, $sharedDB);
            }
            elseif(isset($cloudApp->dependencies->postgresql) && $customData->dbType == 'sharedDB')
            {
                $sharedDB = zget($pgList, $customData->dbService, $sharedDB);
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

        $instance = $this->instance->getByID((int)$post->instanceID);
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
     * 定时任务：同步GitFox数据。（方法用于DevOps解决方案）
     * Sync GitFox data (for DevOps solution).
     *
     * @access public
     * @return void
     */
    public function syncGitFoxData()
    {
        if(!file_exists($this->app->basePath . 'extension' . DS . 'custom' . DS . 'gitfox' . DS . 'control.php')) return false;

        $this->loadModel('instance')->syncGitFoxData();
        return true;
    }

    /**
     * Instance Backup List.
     * 应用备份列表。
     * @param int $id
     * @param int $recPerPage
     * @param int $pageID
     * @return void
     */
    public function backupList(int $id, int $recPerPage = 5, int $pageID = 1)
    {
        /* Initialize Backup List. */
        $instance = $this->instance->getByID($id);
        $backupList  = $this->instance->backupList($instance);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal   = count($backupList);
        $pager      = new pager($recTotal, $recPerPage, $pageID);
        $backupList = array_chunk($backupList, $pager->recPerPage);
        $backupList = empty($backupList) ? array() : $backupList[$pageID - 1];

        $this->view->instance   = $instance;
        $this->view->pager      = $pager;
        $this->view->backupList = $backupList;
        $this->display();
    }

    /**
     * Restore instance by ajax.
     * 还原应用。
     * @access public
     * @return void
     */
    public function ajaxRestore(int $instanceID, string $backupName)
    {
        if(empty($instanceID) || empty($backupName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->instance->errors->wrongRequestData));

        $instance = $this->instance->getByID($instanceID);
        if(empty($instance)) return $this->send(array('result' => 'fail', 'load' => array('alert' => $this->lang->instance->instanceNotExists)));

        $success = $this->instance->restore($instance, $this->app->user, $backupName);
        if(!$success)
        {
            $this->action->create('instance', $instance->id, 'restore', '', json_encode(array('result' => array('result' => 'fail'))));
            return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'restoreFail') ));
        }
        $this->action->create('instance', $instance->id, 'restore', '', json_encode(array('result' => array('result' => 'success'))));
        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'restoreSuccess'), 'locate' => $this->createLink('instance', 'view', 'id=' . $instanceID)));
    }

    /**
     * 手动备份。
     * Manual backup.
     *
     * @param  int    $id
     * @param  string $component 0|mysql
     * @access public
     * @return void
     */
    public function manualBackup(int $id)
    {
        $instance = $this->instance->getByID($id);
        $success  = $this->instance->backup($instance, $this->app->user);
        if(!$success)
        {
            $this->action->create('instance', $instance->id, 'manualbackup', '', json_encode(array('result' => 'fail')));
            return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'backupFail')));
        }
        $this->action->create('instance', $instance->id, 'manualbackup', '', json_encode(array('result' => 'success')));
        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'backupSuccess')));
    }

    /**
     * 备份设置。
     * backup settings.
     *
     * @param  int    $id
     * @param  string $component 0|mysql
     * @access public
     * @return void
     */
    public function backupSettings(int $instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if($_POST)
        {
            $this->instance->saveBackupSettings($instance);
            if(dao::isError())  return $this->send(array('result' => 'fail', 'load' => array('alert' => dao::getError())));

            return $this->send(array('result' => 'success', 'load' => true, 'closeModal' => true));
        }
        $this->view->instance        = $instance;
        $this->view->backupSettings = $this->instance->getAutoBackupSettings($instanceID);
        $this->display();
    }

    /**
     * Display or save auto backup.
     * 自动备份设置。
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function autoBackup(int $instanceID)
    {
        $instance = $this->instance->getByID($instanceID);
        if($_POST)
        {
            $this->instance->saveAutoBackupSettings($instance);
            if(dao::isError())  return $this->send(array('result' => 'fail', 'load' => array('alert' => dao::getError())));

            $backupSettings = $this->instance->getAutoBackupSettings($instanceID);
            $locate         = $this->createLink('instance', 'view', 'id=' . $instanceID);
            $startTime      = strtotime($backupSettings->backupTime);
            if($startTime < time()) $startTime = strtotime("+1 day $backupSettings->backupTime");
            if($backupSettings->autoBackup)
            {
                $startRestoreMessage = sprintf($this->lang->instance->restore->firstStartTime, $instance->name, date('Y-m-d H:i:s', $startTime));
                return $this->send(array('result' => 'success', 'load' => array('alert' => $startRestoreMessage, 'locate' => $locate)));
            }
            return $this->send(array('result' => 'success', 'load' => array('alert' => $this->lang->instance->backup->disableAutoBackup, 'locate' => $locate)));
        }
        $this->view->instance        = $instance;
        $this->view->backupSettings = $this->instance->getAutoBackupSettings($instanceID);
        $this->display();
    }


    /**
     * Cron task of auto backup.
     * 定时备份。
     *
     * @param $instanceID
     * @return int|null
     */
    public function cronBackup(string $instanceID)
    {
        $instance = $this->instance->getByID((int)$instanceID);
        if(empty($instance)) $this->send(array('result' => 'success', 'message' => $this->lang->instance->instanceNotExists));

        $sysUser = new stdclass;
        $sysUser->account = 'system';
        $success = $this->instance->autoBackup($instance, $sysUser);
        if(!$success)
        {
            return $this->send(array('result' => 'fail', 'message' => zget($this->lang->instance->notices, 'backupFail')));
        }
        return $this->send(array('result' => 'success', 'message' => zget($this->lang->instance->notices, 'backupSuccess')));
    }
}
