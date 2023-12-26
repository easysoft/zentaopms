<?php
declare(strict_types=1);
/**
 * The model file of app instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Sun Guangming <sunguangming@easycrop.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.zentao.net
 */
class instanceModel extends model
{
    /**
     * Construct method: load CNE model, and set primaryDomain.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('cne');
        $this->loadModel('action');
    }

    /**
     * 获取应用实例。
     * Get by id.
     *
     * @param  int         $id
     * @access public
     * @return object|null
     */
    public function getByID(int $id): object|null
    {
        $instance = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->eq($id)
            ->andWhere('deleted')->eq(0)
            ->fetch();

        if(!$instance) return null;

        $instance->spaceData = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($instance->space)->fetch();
        if($instance->solution) $instance->solutionData = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->eq($instance->solution)->fetch();

        return $instance;
    }

    /**
     * 根据应用url获取应用信息。
     * Get a application by url.
     *
     * @param  string $url
     * @access public
     * @return object
     */
    public function getByUrl(string $url)
    {
        $url = str_replace(array('https://', 'http://'), '', trim($url));
        return $this->dao->select('id')->from(TABLE_INSTANCE)->where('domain')->eq($url)->fetch();
    }

    /**
     * Get by id list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByIdList($idList)
    {
        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->in($idList)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');

        $spaces = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->in(helper::arrayColumn($instances, 'space'))->fetchAll('id');
        foreach($instances as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        $solutionIDList = helper::arrayColumn($instances, 'solution');
        $solutions      = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->in($solutionIDList)->fetchAll('id');
        foreach($instances as $instance) $instance->solutionData = zget($solutions, $instance->solution, new stdclass);

        return $instances;
    }

    /**
     * Get instance list by solution.
     *
     * @param  object $solution
     * @param  string $chart
     * @access public
     * @return array
     */
    public function instanceOfSolution($solution, $chart)
    {
        $instance = $this->dao->select('id')->from(TABLE_INSTANCE)->where('deleted')->eq(0)
            ->andWhere('solution')->eq($solution->id)
            ->andWhere('chart')->eq($chart)
            ->fetch();

        if($instance) return $this->getByID($instance->id);

        return array();
    }

    /**
     * Get instances list by account.
     *
     * @param  object $pager
     * @param  string $pinned
     * @param  string $searchParam
     * @param  string $status
     * @access public
     * @return array
     */
    public function getList($pager = null, string $pinned = '', string $searchParam = '', string $status = 'all')
    {
        $instances = $this->dao->select('instance.*')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            ->beginIF($pinned)->andWhere('instance.pinned')->eq((int)$pinned)->fi()
            ->beginIF($searchParam)->andWhere('instance.name')->like("%{$searchParam}%")->fi()
            ->beginIF($status != 'all')->andWhere('instance.status')->eq($status)->fi()
            ->orderBy('instance.id desc')
            ->beginIF($pager)->page($pager)->fi()
            ->fetchAll('id');

        $spaces = $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in(helper::arrayColumn($instances, 'space'))
            ->fetchAll('id');

        foreach($instances as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        $solutionIDList = helper::arrayColumn($instances, 'solution');
        $solutions      = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->in($solutionIDList)->fetchAll('id');
        foreach($instances as $instance) $instance->solutionData = zget($solutions, $instance->solution, new stdclass);

        return $instances;
    }

    /**
     * Get quantity of total installed services.
     *
     * @access public
     * @return int
     */
    public function getServiceCount(): int
    {
        $defaultSpace = $this->loadModel('space')->defaultSpace($this->app->user->account);

        $count = $this->dao->select('count(*) as qty')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            ->andWhere('space.id')->eq($defaultSpace->id)
            ->fetch();

        return $count->qty;
    }

    /**
     * Count old domain.
     *
     * @access public
     * @return int
     */
    public function countOldDomain(): int
    {
        /* REPLACE(domain, .$sysDomain, '') used for special case: new domain is a.com and old domain is b.a.com, domain of instance is c.b.a.com, */
        $sysDomain = $this->loadModel('cne')->sysDomain();
        $result = $this->dao->select('count(*) as qty')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere("REPLACE(domain, '.$sysDomain', '')")->like("%.%")
            ->fetch();

        return $result->qty;
    }

    /**
     * Update all instances domain by customized domain.
     *
     * @access public
     * @return void
     */
    public function updateInstancesDomain()
    {
        /* REPLACE(domain, .$sysDomain, '') used for special case: new domain is a.com and old domain is b.a.com, domain of instance is c.b.a.com,  new domain of instance should be c.a.com */
        $sysDomain = $this->loadModel('cne')->sysDomain();
        $instanceList = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere("REPLACE(domain, '.$sysDomain', '')")->like("%.%")
            ->fetchAll('id');

        $spaces = $this->dao->select('*')->from(TABLE_SPACE)->where('deleted')->eq(0)->andWhere('id')->in(helper::arrayColumn($instanceList, 'space'))->fetchAll('id');

        foreach($instanceList as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        foreach($instanceList as $instance) $this->updateDomain($instance);
    }

    /**
     * Update domain.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function updateDomain($instance)
    {
        /* If domain of instance is same with system domain, not need to update. */
        $sysDomain = $this->cne->sysDomain();
        if(stripos(str_replace($sysDomain, '', $instance->domain), '.') === false) return true;

        $expiredDomains = $this->loadModel('setting')->getItem('owner=system&module=common&section=domain&key=expiredDomain');
        $expiredDomains = json_decode($expiredDomains, true);
        if(empty($expiredDomains)) return true;

        $leftDomain = '';
        foreach($expiredDomains as $expiredDomain)
        {
            if(stripos($instance->domain, $expiredDomain) === false) continue;

            $leftDomain = trim(str_replace($expiredDomain, '', $instance->domain), '.'); // Pick left domain.
            /* If left domain like a.b, skip it, because left domain must be letters without dot(.) . */
            if(stripos($leftDomain, '.') !== false) continue; // Does not pick left domain.

            $newDomain = $leftDomain . '.' . $sysDomain;

            $settings = new stdclass;
            $settings->settings_map = new stdclass;
            $settings->settings_map->ingress = new stdclass;
            $settings->settings_map->ingress->enabled = true;
            $settings->settings_map->ingress->host    = $newDomain; //$this->fullDomain($leftDomain);

            $settings->settings_map->global = new stdclass;
            $settings->settings_map->global->ingress = $settings->settings_map->ingress;

            if($this->cne->updateConfig($instance, $settings))
            {
                $this->dao->update(TABLE_INSTANCE)->set('domain')->eq($newDomain)->where('id')->eq($instance->id)->exec();
            }
        }

        return true;
    }

    /**
     * Update instance memory size.
     *
     * @param  object $instnace
     * @param  int    $size
     * @access public
     * @return bool
     */
    public function updateMemorySize($instnace, $size = '')
    {
        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->resources = new stdclass;
        $settings->settings_map->resources->memory = $size;

        $success = $this->cne->updateConfig($instnace, $settings);
        if($success)
        {
            $this->action->create('instance', $instnace->id, 'adjustMemory', helper::formatKB(intval($size)));
            return true;
        }

        dao::$errors[] = $this->lang->instance->errors->failToAdjustMemory;
        return false;
    }

    /**
     * Update instance status.
     *
     * @param  int    $int
     * @param  string $status
     * @access public
     * @return int
     */
    public function updateStatus($id, $status)
    {
        $instanceData = new stdclass;
        $instanceData->status = trim($status);
        return $this->updateByID($id, $instanceData);
    }

    /**
     * Update instance by id.
     *
     * @param  int    $id
     * @param  object $newInstance
     * @access public
     * @return void
     */
    public function updateByID($id, $newInstance)
    {
        return $this->dao->update(TABLE_INSTANCE)->data($newInstance)
            ->autoCheck()
            ->checkIF(isset($newInstance->name), 'name', 'notempty')
            ->checkIF(isset($newInstance->status), 'status', 'in', array_keys($this->lang->instance->statusList))
            ->where('id')->eq($id)->exec();
    }


    /**
     * Soft delete instance by id.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function softDeleteByID($id)
    {
        return $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
    }

    /**
     * If actions are allowed to do.
     *
     * @param  string $action
     * @param  object $instance
     * @access public
     * @return boolean
     */
    public function canDo($action, $instance)
    {
        // $busy = in_array($instance->status, array('creating', 'initializing', 'starting', 'stopping', 'suspending', 'pulling', 'destroying'));
        switch($action)
        {
            case 'start':
                return $instance->status === 'stopped';
            case 'stop':
                return in_array($instance->status, array('abnormal', 'running'));
            case 'uninstall':
                return !in_array($instance->status, array('destroying'));
            case 'visit':
                return $instance->status == 'running';
            default:
                return false;
        }
    }

    /**
     * Get URL for visiting instance service.
     *
     * @param  object $instance
     * @access public
     * @return string
     */
    public function url($instance)
    {
        $url  = "//" . $instance->domain;
        $port = getenv('APP_HTTPS_PORT');
        if($port and $port != '443') $url .= ":$port/";

        return $url;
    }

    /**
     * Create third domain.
     *
     * @param  int    $length
     * @param  int    $triedTimes
     * @access public
     * @return string
     */
    public function randThirdDomain($length = 4, $triedTimes = 0)
    {
        if($triedTimes > 16) $length++;

        $thirdDomain = strtolower(helper::randStr($length));
        if(!$this->domainExists($thirdDomain)) return $thirdDomain;

        return $this->randThirdDomain($length, $triedTimes + 1);
    }

    /**
     * Return full domain.
     *
     * @param  string $thirdDomain
     * @access public
     * @return string
     */
    public function fullDomain($thirdDomain)
    {
        return $thirdDomain . '.' . $this->cne->sysDomain();
    }

    /**
     * Check if the domain exists.
     *
     * @param  string $thirdDomain
     * @access public
     * @return bool   true: exists, false: not exist.
     */
    public function domainExists($thirdDomain)
    {
        $domain = $this->fullDomain($thirdDomain);
        return boolval($this->dao->select('id')->from(TABLE_INSTANCE)->where('domain')->eq($domain)->andWhere('deleted')->eq(0)->fetch());
    }

    /**
     * Check if the k8name exists.
     *
     * @param  string $k8name
     * @access public
     * @return bool   true: exists, false: not exist.
     */
    public function k8nameExists($k8name)
    {
        return boolval($this->dao->select('id')->from(TABLE_INSTANCE)->where('k8name')->eq($k8name)->andWhere('deleted')->eq(0)->fetch());
    }

    /**
     * Mount installation settings by custom data.
     *
     * @param  object  $customData
     * @param  object  $dbInfo
     * @param  object  $instance
     * @access private
     * @return object
     */
    private function installationSettingsMap($customData, $dbInfo, $instance)
    {
        $settingsMap = new stdclass;
        if($customData->customDomain)
        {
            $settingsMap->ingress = new stdclass;
            $settingsMap->ingress->enabled = true;
            $settingsMap->ingress->host    = $this->fullDomain($customData->customDomain);

            $settingsMap->global = new stdclass;
            $settingsMap->global->ingress = new stdclass;
            $settingsMap->global->ingress->enabled = true;
            $settingsMap->global->ingress->host    = $settingsMap->ingress->host;
        }

        if(in_array($instance->chart, $this->config->instance->devopsApps))
        {
            $settingsMap->ci = new stdclass();
            $settingsMap->ci->enabled = true;
        }

        if(empty($customData->dbType) || $customData->dbType == 'unsharedDB' || empty($customData->dbService)) return $settingsMap;

        /* Set DB settings. */
        $dbSettings = new stdclass;
        $dbSettings->service   = $dbInfo->name;
        $dbSettings->namespace = $dbInfo->namespace;
        $dbSettings->host      = $dbInfo->host;
        $dbSettings->port      = $dbInfo->port;
        $dbSettings->name      = 'db_' . $instance->id;
        $dbSettings->user      = 'user_' . $instance->id;

        $dbSettings = $this->getValidDBSettings($dbSettings, $dbSettings->user, $dbSettings->name);

        $settingsMap->mysql = new stdclass;
        $settingsMap->mysql->enabled = false;

        $settingsMap->mysql->auth = new stdclass;
        $settingsMap->mysql->auth->user     = $dbSettings->user;
        $settingsMap->mysql->auth->password = helper::randStr(12);
        $settingsMap->mysql->auth->host     = $dbSettings->host;
        $settingsMap->mysql->auth->port     = $dbSettings->port;
        $settingsMap->mysql->auth->database = $dbSettings->name;

        $settingsMap->mysql->auth->dbservice = new stdclass;
        $settingsMap->mysql->auth->dbservice->name      = $dbSettings->service;
        $settingsMap->mysql->auth->dbservice->namespace = $dbSettings->namespace;

        return $settingsMap;
    }

    /**
     * Return valid DBSettings.
     *
     * @param  object  $dbSettings
     * @param  string  $defaultUser
     * @param  string  $defaultDBName
     * @param  int     $times
     * @access private
     * @return null|object
     */
    private function getValidDBSettings($dbSettings, $defaultUser, $defaultDBName, $times = 1)
    {
        if($times >10) return;

        $validatedResult = $this->cne->validateDB($dbSettings->service, $dbSettings->name, $dbSettings->user, $dbSettings->namespace);
        if($validatedResult->user && $validatedResult->database) return $dbSettings;

        if(!$validatedResult->user)     $dbSettings->user = $defaultUser . '_' . help::randStr(4);
        if(!$validatedResult->database) $dbSettings->database = $defaultDBName  . '_' . help::randStr(4);

        return $this->getValidDBSettings($dbSettings, $defaultUser, $defaultDBName, $times + 1);
    }

    /**
     * Install app by request from Web page.
     *
     * @param  object $app
     * @param  object $dbInfo
     * @param  object $customData
     * @param  int    $spaceID
     * @access public
     * @return false|object Failure: return false, Success: return instance
     */
    public function install($app, $dbInfo, $customData, $spaceID = null, $settings = array())
    {
        $this->loadModel('space');
        if($spaceID)
        {
            $space = $this->space->getByID($spaceID);
        }
        else
        {
            $space = $this->space->defaultSpace($this->app->user->account);
        }

        $snippets = array();
        if(isset($customData->ldapSnippet[0])) $snippets['ldapSnippetName'] = $customData->ldapSnippet[0];
        if(isset($customData->smtpSnippet[0])) $snippets['smtpSnippetName'] =  $customData->smtpSnippet[0];

        $channel  = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $instance = $this->createInstance($app, $space, $customData->customDomain, $customData->customName, '', $channel, $snippets);
        if(!$instance) return false;

        $settingMap = $this->installationSettingsMap($customData, $dbInfo, $instance);
        return $this->doCneInstall($instance, $space, $settingMap, $snippets, $settings);
    }

    /**
     * Install System SLB component.
     *
     * @param  object $app
     * @param  string $k8name
     * @param  string $channel
     * @access public
     * @return object
     */
    public function installSysSLB($app, $k8name = 'cne-lb', $channel = 'stable')
    {
        $this->app->loadLang('system');

        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $instance = $this->createInstance($app, $space, '', '', $k8name, $channel);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        $instance = $this->doCneInstall($instance, $space, (new stdclass));
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        return $instance;
    }

    /**
     * Create instance recorder for installation.
     *
     * @param  object $app
     * @param  object $space
     * @param  object $thirdDomain
     * @param  string $name
     * @param  string $channel
     * @param  array  $snippets
     * @access public
     * @return bool|object
     */
    public function createInstance($app, $space, $thirdDomain, $name = '', $k8name = '', $channel = 'stable', $snippets = array())
    {
        $createdBy = $this->app->user->account;
        if(empty($k8name)) $k8name = "{$app->chart}-" . date('YmdHis'); //name rule: chartName-userAccount-YmdHis;
        if(defined('IN_INSTALL') && IN_INSTALL && empty($this->app->user->account)) $createdBy = trim($this->app->company->admins, ','); //Set createdBy if in login progress;

        if(!$this->app->user->account)
        {
            $this->app->user = new stdclass();
            $this->app->user->account = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetch('account');
        }

        $instanceData = new stdclass;
        $instanceData->appId           = $app->id;
        $instanceData->appName         = $app->alias;
        $instanceData->name            = !empty($name) ? $name : $app->alias;
        $instanceData->domain          = !empty($thirdDomain) ? $this->fullDomain($thirdDomain) : '';
        $instanceData->logo            = $app->logo;
        $instanceData->desc            = $app->desc;
        $instanceData->introduction    = isset($app->introduction) ? $app->introduction : $app->desc;
        $instanceData->source          = 'cloud';
        $instanceData->channel         = $channel;
        $instanceData->chart           = $app->chart;
        $instanceData->appVersion      = $app->app_version;
        $instanceData->version         = $app->version;
        $instanceData->space           = $space->id;
        $instanceData->k8name          = $k8name;
        $instanceData->status          = 'creating';
        $instanceData->createdBy       = $this->app->user->account;
        $instanceData->createdAt       = date('Y-m-d H:i:s');

        foreach($snippets as $fieldName => $snippetName) $instanceData->$fieldName = $snippetName;

        $this->dao->insert(TABLE_INSTANCE)->data($instanceData)->autoCheck()->exec();
        if(dao::isError()) return false;

        return $this->getByID($this->dao->lastInsertID());
    }

    /**
     * Create app instance on CNE platform.
     *
     * @param  object $instance
     * @param  object $space
     * @param  object $settingsMap
     * @param  array  $snippets
     * @param  object $app
     * @access private
     * @return object|bool
     */
    private function doCneInstall($instance, $space, $settingsMap, $snippets = array(), $settings = array())
    {
        $apiParams = new stdclass;
        $apiParams->userame           = $instance->createdBy;
        $apiParams->cluser            = '';
        $apiParams->namespace         = $space->k8space;
        $apiParams->name              = $instance->k8name;
        $apiParams->chart             = $instance->chart;
        $apiParams->version           = $instance->version;
        $apiParams->channel           = $instance->channel;
        $apiParams->settings_map      = $settingsMap;
        $apiParams->settings          = $settings;
        $apiParams->settings_snippets = array_values($snippets);

        if(strtolower($this->config->CNE->app->domain) == 'demo.haogs.cn') $apiParams->settings_snippets = array('quickon_saas'); // Only for demo enviroment.

        $result = $this->cne->installApp($apiParams);
        if($result->code != 200)
        {
            $this->dao->delete()->from(TABLE_INSTANCE)->where('id')->eq($instance->id)->exec();
            dao::$errors['server'][] = $result->message;
            return false;
        }

        $this->action->create('instance', $instance->id, 'install', '', json_encode(array('result' => $result, 'apiParams' => $apiParams)));

        $instance->status     = 'initializing';
        $instance->dbSettings = json_encode($apiParams->settings_map);

        $this->updateByID($instance->id, array('status' => $instance->status, 'dbSettings' => $instance->dbSettings));

        return  $instance;
    }

    /*
     * Uninstall app instance.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function uninstall($instance)
    {
        if($instance->source == 'external')
        {
            $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('id')->eq($instance->id)->exec();
            return true;
        }

        $apiParams = new stdclass;
        $apiParams->cluster   = '';// Multiple cluster should set this field.
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = $instance->channel;
        $apiParams->namespace = $instance->spaceData->k8space;

        $result  = $this->cne->uninstallApp($apiParams);
        $success = $result->code == 200 || $result->code == 404;
        if($success) $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('id')->eq($instance->id)->exec();

        $url = strstr(getWebRoot(true), ':', true) . '://' . $instance->domain;
        $this->dao->delete()->from(TABLE_PIPELINE)->where('url')->eq($url)->exec();
        return $success;
    }

    /*
     * Start app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function start($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;

        $result = $this->cne->startApp($apiParams);
        if($result->code == 200) $this->dao->update(TABLE_INSTANCE)->set('status')->eq('starting')->where('id')->eq($instance->id)->exec();

        return $result;
    }

    /*
     * Stop app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function stop($instance)
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';// Mulit cluster should set this field.
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = $instance->channel;

        $result = $this->cne->stopApp($apiParams);
        if($result->code == 200) $this->dao->update(TABLE_INSTANCE)->set('status')->eq('stopping')->where('id')->eq($instance->id)->exec();

        return $result;
    }

    /**
     * Upgrade app instance to higher version.
     *
     * @param  object $instance
     * @param  string $toVersion
     * @param  string $appVersion
     * @access public
     * @return bool
     */
    public function upgrade($instance, $toVersion, $appVersion)
    {
        $success = $this->cne->upgradeToVersion($instance, $toVersion);
        if(!$success) return false;

        $instanceData = new stdclass;
        $instanceData->version    = $toVersion;
        $instanceData->appVersion = $appVersion;
        $this->updateByID($instance->id, $instanceData);

        return true;
    }

    /*
     * Query and update instances status.
     *
     * @param  array $instances
     * @access public
     * @return array  new status list [{id:xx, status: xx, changed: true/false}]
     */
    public function batchFresh(&$instances)
    {
        $statusList = $this->cne->batchQueryStatus($instances);

        $newStatusList = array();

        foreach($instances as $instance)
        {
            $statusData = zget($statusList, $instance->k8name, '');
            if($statusData && ($instance->status != $statusData->status || $instance->version != $statusData->version))
            {
                $this->dao->update(TABLE_INSTANCE)
                    ->set('status')->eq($statusData->status)
                    ->beginIF($statusData->version)->set('version')->eq($statusData->version)->fi()
                    ->where('id')->eq($instance->id)
                    ->autoCheck()
                    ->exec();
                $instance->status = $statusData->status;

                if($instance->status == 'running') $this->saveAuthInfo($instance);
            }

            $status = new stdclass;
            $status->id     = $instance->id;
            $status->status = $instance->status;

            $newStatusList[] = $status;
        }

        return $newStatusList;
    }

    /*
     * Query and update instance status.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function freshStatus($instance)
    {
        $instance->runDuration = 0;
        $statusResponse = $this->cne->queryStatus($instance);
        if($statusResponse->code != 200) return $instance;

        $statusData = $statusResponse->data;
        $instance->runDuration = intval($statusData->age); // Run duration used in view page.

        if($instance->status != $statusData->status || $instance->version != $statusData->version)
        {
            $instance->status = $statusData->status;

            $this->dao->update(TABLE_INSTANCE)
                ->set('status')->eq($statusData->status)
                ->beginIF($statusData->version)->set('version')->eq($statusData->version)->fi()
                ->where('id')->eq($instance->id)
                ->autoCheck()
                ->exec();
        }

        return $instance;
    }

    /**
     * Mount DB name and alias to array for select options.
     *
     * @param  array  $databases
     * @access public
     * @return array
     */
    public function dbListToOptions($databases)
    {
        $dbList = array();
        foreach($databases as $database) $dbList[$database->name] = zget($database, 'alias', $database->name);

        return $dbList;
    }

    /**
     * Filter memory options that smaller then current memory size.
     *
     * @param  object $resources
     * @access public
     * @return array
     */
    public function filterMemOptions($resources)
    {
        $currentMemory = intval($resources->min->memory / 1024);

        $options = [];
        foreach($this->lang->instance->memOptions as $size => $text)
        {
            if($size >= $currentMemory) $options[$size] = $text;
        }

        return $options;
    }

    /**
     * Print CPU usage.
     *
     * @param  object $instance
     * @param  object $metrics
     * @static
     * @access public
     * @return mixed
     */
    public static function printCpuUsage($instance, $metrics)
    {
        if($instance->source === 'user') return array('color' => '', 'tip' => '', 'rate' => '', 'usage' => '', 'limit' => '');
        $rate = $instance->status == 'stopped' ? 0 : $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->limit}";

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'secondary';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'warning';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'important';
        if(empty($color) && $rate >= 80)              $color = 'danger';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate . '%', 'usage' => $metrics->usage, 'limit' => $metrics->limit);
    }

    /**
     * Print memory usage.
     *
     * @param  object $instance
     * @param  object $metrics
     * @static
     * @access public
     * @return mixed
     */
    public static function printMemUsage($instance, $metrics)
    {
        if($instance->source === 'user') return array('color' => '', 'tip' => '', 'rate' => '', 'usage' => '', 'limit' => '');
        $rate = $instance->status == 'stopped' ? 0 : $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage) . ' / ' . helper::formatKB($metrics->limit);

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'secondary';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'warning';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'important';
        if(empty($color) && $rate >= 80)              $color = 'danger';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate . '%', 'usage' => helper::formatKB($metrics->usage), 'limit' => helper::formatKB($metrics->limit));
    }

    /**
     * Check the free memory of cluster is enough to install app.
     *
     * @param  object $cloudApp
     * @access public
     * @return bool
     */
    public function enoughMemory($cloudApp)
    {
        $clusterResource = $this->cne->cneMetrics();
        $freeMemory      = intval($clusterResource->metrics->memory->allocatable * 0.9); // Remain 10% memory for system.

        return $freeMemory >= $cloudApp->memory;
    }

    /**
     * 判断按钮是否可点击。
     * Adjust the action clickable.
     *
     * @param  object $instance
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $instance, string $action): bool
    {
        if(!isset($instance->type)) $instance->type = 'store';

        if($instance->type !== 'store')
        {
            if($action === 'edit' || $action === 'visit') return true;
            if($action == 'bindUser')  return in_array($instance->appName, array('GitLab', 'Gitea', 'Gogs'));
            if($action == 'ajaxUninstall') return true;
            return false;
        }

        if($action == 'ajaxStart')     return $this->canDo('start', $instance);
        if($action == 'ajaxStop')      return $this->canDo('stop', $instance);
        if($action == 'ajaxUninstall') return $this->canDo('uninstall', $instance);
        if($action == 'visit')         return !empty($instance->domain) && $this->canDo('visit', $instance);
        if($action == 'upgrade')       return !empty($instance->latestVersion) && in_array($instance->status, array('stopped', 'running'));
        if($action == 'edit')          return false;
        if($action == 'bindUser')      return $instance->status == 'running' && in_array($instance->appName, array('GitLab', 'Gitea', 'Gogs'));

        return true;
    }

    /**
     * Check app name unique.
     *
     * @param  string    $name
     * @access protected
     * @return bool
     */
    public function checkAppNameUnique(string $name): bool
    {
        $existInstance = $this->dao->select('*')->from(TABLE_INSTANCE)->where('name')->eq($name)->andWhere('deleted')->eq(0)->fetch();
        $existExternal = $this->dao->select('*')->from(TABLE_PIPELINE)->where('name')->eq($name)->andWhere('deleted')->eq('0')->fetch();

        return ($existInstance || $existExternal) ? false : true;
    }

    /**
     * 自动保存devops应用授权信息。
     * Auto save auth info of devops.
     *
     * @param  object     $instance
     * @access protected
     * @return void
     */
    public function saveAuthInfo(object $instance): void
    {
        if(!in_array($instance->chart, $this->config->instance->devopsApps)) return;

        $url      = strstr(getWebRoot(true), ':', true) . '://' . $instance->domain;
        $pipeline = $this->loadModel('pipeline')->getByUrl($url);
        if(!empty($pipeline)) return;

        $tempMappings = $this->loadModel('cne')->getSettingsMapping($instance);
        if(empty($tempMappings)) return;

        $pipeline = new stdclass();
        $instance->type        = $instance->chart;
        $pipeline->type        = $instance->type == 'nexus3' ? 'nexus' : $instance->type;
        $pipeline->private     = md5(strval(rand(10,113450)));
        $pipeline->createdBy   = 'system';
        $pipeline->createdDate = helper::now();
        $pipeline->url         = $url;
        $pipeline->name        = $this->generatePipelineName($instance);
        $pipeline->token       = zget($tempMappings, 'api_token', '');
        $pipeline->account     = zget($tempMappings, 'z_username', '');
        $pipeline->password    = zget($tempMappings, 'z_password', '');
        if($instance->chart == 'sonarqube') $pipeline->token = base64_encode($pipeline->token . ':');
        if(empty($pipeline->account)) $pipeline->account = zget($tempMappings, 'admin_username', '');

        $this->pipeline->create($pipeline);
        if(dao::isError()) dao::getError();
    }

    /**
     * 自动生成pipline表name字段。
     * Auto generate name of pipline table.
     *
     * @param  object     $instance
     * @access protected
     * @return string
     */
    public function generatePipelineName(object $instance): string
    {
        $name = $instance->name;
        $type = $instance->type;
        if(empty($this->loadModel('pipeline')->getByNameAndType($name, $type))) return $name;
        if(empty($this->loadModel('pipeline')->getByNameAndType($name . '-' . $instance->appVersion, $type))) return $name . '-' . $instance->appVersion;

        for($times = 1; $times < 5; $times ++)
        {
            if(empty($this->loadModel('pipeline')->getByNameAndType($name . '-' . $times, $name))) return $name . '-' . $times;
        }
    }
}
