<?php
/**
 * The model file of app instance module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycrop.ltd>
 * @package   instance
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class InstanceModel extends model
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
     * Get by id.
     *
     * @param  int $id
     * @access public
     * @return object|null
     */
    public function getByID($id)
    {
        $instance = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('id')->eq($id)
            ->andWhere('deleted')->eq(0)
            ->fetch();
        if(!$instance) return null;

        $instance->spaceData = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($instance->space)->fetch();
        if($instance->solution)
        {
            $solution = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->eq($instance->solution)->fetch();
            $instance->solutionData = $solution;
        }

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
     * @param  string $account
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getByAccount($account = '', $pager = null, $pinned = '', $searchParam = '', $status = 'all')
    {
        // $defaultSpace = $this->loadModel('space')->defaultSpace($account ? $account : $this->app->user->account);

        $instances = $this->dao->select('instance.*')->from(TABLE_INSTANCE)->alias('instance')
            ->leftJoin(TABLE_SPACE)->alias('space')->on('space.id=instance.space')
            ->where('instance.deleted')->eq(0)
            // ->andWhere('space.id')->eq($defaultSpace->id)
            // ->beginIF($account)->andWhere('space.owner')->eq($account)->fi()
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
     * Get instance list that has been enabled LDAP.
     *
     * @access public
     * @return array
     */
    public function getListEnabledLDAP()
    {
        $instances = $this->dao->select('*')->from(TABLE_INSTANCE)->where('deleted')->eq(0)->andWhere('length(ldapSnippetName) > 0')->fetchAll('id');

        $spaces = $this->dao->select('*')->from(TABLE_SPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in(helper::arrayColumn($instances, 'space'))
            ->fetchAll('id');

        foreach($instances as $instance) $instance->spaceData = zget($spaces, $instance->space, new stdclass);

        return $instances;
    }

    /**
     * Count instance which is enabled LDAP.
     *
     * @access public
     * @return int
     */
    public function countLDAP()
    {
        $count = $this->dao->select('count(*) as ldapQty')->from(TABLE_INSTANCE)->where('deleted')->eq(0)->andWhere('length(ldapSnippetName) > 0')->fetch();
        return $count->ldapQty;
    }

    /**
     * Count instance which is enabled SMTP.
     *
     * @access public
     * @return int
     */
    public function countSMTP()
    {
        $count = $this->dao->select('count(*) as Qty')->from(TABLE_INSTANCE)->where('deleted')->eq(0)->andWhere('length(smtpSnippetName) > 0')->fetch();
        return $count->Qty;
    }

    /**
     * Get quantity of total installed services.
     *
     * @access public
     * @return int
     */
    public function totalServices()
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
     * Pin instance to navigation page or Unpin instance.
     *
     * @param  int    $instanceID
     * @access public
     * @return void
     */
    public function pinToggle($instanceID)
    {
        $instance = $this->getByID($instanceID);
        $pinned = $instance->pinned == '0' ? '1' : '0';
        $this->dao->update(TABLE_INSTANCE)->set('pinned')->eq($pinned)->where('id')->eq($instanceID)->exec();
    }

    /**
     * Switch LDAP between enable and disable.
     *
     * @param  object $instance
     * @param  bool   $enableSMTP
     * @access public
     * @return bool
     */
    public function switchSMTP($instance, $enableSMTP)
    {
        $this->loadModel('system');
        $snippetName = $this->system->smtpSnippetName();

        $settings = new stdclass;
        if($enableSMTP)
        {
            $settings->settings_snippets = [$snippetName];
        }
        else
        {
            $settings->settings_snippets = [$snippetName . '-'];
        }

        $success = $this->cne->updateConfig($instance, $settings);
        if(!$success)
        {
            dao::$errors[] = $this->lang->instance->errors->failToAdjustMemory;
            return false;
        }

        if($enableSMTP)
        {
            $this->dao->update(TABLE_INSTANCE)->set('smtpSnippetName')->eq($snippetName)->where('id')->eq($instance->id)->exec();
            $this->action->create('instance', $instance->id, 'enableSMTP');
        }
        else
        {
            $this->dao->update(TABLE_INSTANCE)->set('smtpSnippetName')->eq('')->where('id')->eq($instance->id)->exec();
            $this->action->create('instance', $instance->id, 'disableSMTP');
        }

        return true;
    }

    /**
     * Switch LDAP between enable and disable.
     *
     * @param  object $instance
     * @param  bool   $enableLDAP
     * @access public
     * @return bool
     */
    public function switchLDAP($instance, $enableLDAP)
    {
        $this->loadModel('system');
        $snippetName = $this->system->ldapSnippetName();

        $settings = new stdclass;
        $settings->force_restart = true;
        if($enableLDAP)
        {
            $settings->settings_snippets = [$snippetName];
        }
        else
        {
            $settings->settings_snippets = [$snippetName . '-'];
        }

        $success = $this->cne->updateConfig($instance, $settings);
        if(!$success)
        {
            dao::$errors[] = $this->lang->instance->errors->failToAdjustMemory;
            return false;
        }

        if($enableLDAP)
        {
            $this->dao->update(TABLE_INSTANCE)->set('ldapSnippetName')->eq($snippetName)->where('id')->eq($instance->id)->exec();
            $this->action->create('instance', $instance->id, 'enableLDAP');
        }
        else
        {
            $this->dao->update(TABLE_INSTANCE)->set('ldapSnippetName')->eq('')->where('id')->eq($instance->id)->exec();
            $this->action->create('instance', $instance->id, 'disableLDAP');
        }

        return true;
    }

    /**
     * Count old domain.
     *
     * @access public
     * @return int
     */
    public function countOldDomain()
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
      install global SMTP proxy service.
     *
     * @param  object $app
     * @param  object $smtpSettings
     * @param  string $instanceName
     * @param  string $k8name
     * @param  string $channel
     * @access public
     * @return object
     */
    public function installSysSMTP($app, $smtpSettings, $instanceName = '', $k8name = '', $channel = 'stable')
    {
        $this->app->loadLang('system');

        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $customData = new stdclass;
        $customData->dbType       = null;
        $customData->customDomain = $this->randThirdDomain();

        $instance = $this->createInstance($app, $space, $customData->customDomain, $instanceName, $k8name, $channel);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallSMTP;
            return false;
        }

        $settingsMap = $this->installationSettingsMap($customData, array(), $instance);

        $settingsMap->env = new stdclass;
        $settingsMap->env->SMTP_HOST         = $smtpSettings->host;
        $settingsMap->env->SMTP_PORT         = strval($smtpSettings->port);
        $settingsMap->env->SMTP_USER         = $smtpSettings->user;
        $settingsMap->env->SMTP_PASS         = $smtpSettings->pass;
        $settingsMap->env->AUTHENTICATE_CODE = helper::randStr(24);

        $instance = $this->doCneInstall($instance, $space, $settingsMap);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallSMTP;
            return false;
        }

        /* Post snippet to CNE. */
        $snippetSettings = new stdclass;
        $snippetSettings->name        = 'snippet-smtp-proxy';
        $snippetSettings->namespace   = $space->k8space;
        $snippetSettings->auto_import = false;

        $snippetSettings->values = new stdclass;
        $snippetSettings->values->mail = new stdclass;
        $snippetSettings->values->mail->enabled = true;
        $snippetSettings->values->mail->smtp    = new stdclass;
        $snippetSettings->values->mail->smtp->host = "{$instance->k8name}.{$snippetSettings->namespace}.svc";
        $snippetSettings->values->mail->smtp->port = '1025';
        $snippetSettings->values->mail->smtp->user = 'smtp-bot@quickon.local'; // This is fake value.
        $snippetSettings->values->mail->smtp->pass = $settingsMap->env->AUTHENTICATE_CODE; // This is fake value.

        $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
        if($snippetResult->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallSMTP;
            $this->uninstall($instance);
            return false;
        }

        /* Save SMTP settings. */
        $secretKey = helper::readKey();
        $settingsMap->env->SMTP_PASS         = openssl_encrypt($settingsMap->env->SMTP_PASS, 'DES-ECB', $secretKey);
        $settingsMap->env->AUTHENTICATE_CODE = openssl_encrypt($settingsMap->env->AUTHENTICATE_CODE, 'DES-ECB', $secretKey);

        $snippetSettings->values->mail->smtp->pass = $settingsMap->env->AUTHENTICATE_CODE;

        $this->loadModel('setting');
        $this->setting->setItem('system.common.smtp.enabled', true);
        $this->setting->setItem('system.common.smtp.instanceID', $instance->id);
        $this->setting->setItem('system.common.smtp.snippetName', $snippetSettings->name);
        $this->setting->setItem('system.common.smtp.settingsMap', json_encode($settingsMap));
        $this->setting->setItem('system.common.smtp.snippetSettings', json_encode($snippetSettings));

        return $instance;
    }

    /**
     * Install LDAP.
     *
     * @param  object $app
     * @param  string $thirdDomain
     * @param  string $instanceName
     * @param  string $channel
     * @access public
     * @return bool|object
     */
    public function installLDAP($app, $thirdDomain = '', $instanceName = '', $k8name = '', $channel = 'stable')
    {
        $this->app->loadLang('system');

        $space = $this->loadModel('space')->getSystemSpace($this->app->user->account);

        $customData = new stdclass;
        $customData->dbType       = null;
        $customData->customDomain = $thirdDomain ? $thirdDomain : $this->randThirdDomain();

        $instance = $this->createInstance($app, $space, $customData->customDomain, $instanceName, $k8name, $channel);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        $settingMap = $this->installationSettingsMap($customData, array(), $instance);

        $settingMap->auth = new stdclass;
        $settingMap->auth->username = $app->account ? $app->account->username : 'admin';
        $settingMap->auth->password = helper::randStr(16);
        $settingMap->auth->root     = 'dc=quickon,dc=org';

        $instance = $this->doCneInstall($instance, $space, $settingMap);
        if(!$instance)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            return false;
        }

        /* Post snippet to CNE. */
        $snippetSettings = new stdclass;
        $snippetSettings->name        = 'snippet-qucheng-ldap';
        $snippetSettings->namespace   = $space->k8space;
        $snippetSettings->auto_import = false;

        $snippetSettings->values = new stdclass;
        $snippetSettings->values->auth = new stdclass;
        $snippetSettings->values->auth->ldap = new stdclass;
        $snippetSettings->values->auth->ldap->enabled   = true;
        $snippetSettings->values->auth->ldap->type      = 'ldap';
        $snippetSettings->values->auth->ldap->host      = "{$instance->k8name}.{$snippetSettings->namespace}.svc";
        $snippetSettings->values->auth->ldap->port      = '1389';
        $snippetSettings->values->auth->ldap->bindDN    = "cn={$settingMap->auth->username},dc=quickon,dc=org";
        $snippetSettings->values->auth->ldap->bindPass  = $settingMap->auth->password;
        $snippetSettings->values->auth->ldap->baseDN    = $settingMap->auth->root;
        $snippetSettings->values->auth->ldap->filter    = "&(objectClass=posixAccount)(cn=%s)";
        $snippetSettings->values->auth->ldap->attrUser  = 'uid';
        $snippetSettings->values->auth->ldap->attrEmail = 'mail';

        $snippetResult = $this->loadModel('cne')->addSnippet($snippetSettings);
        if($snippetResult->code != 200)
        {
            dao::$errors[] = $this->lang->system->errors->failToInstallLDAP;
            $this->uninstall($instance);
            return false;
        }

        /* Save LDAP account. */
        $secretKey = helper::readKey();
        $settingMap->auth->password = openssl_encrypt($settingMap->auth->password, 'DES-ECB', $secretKey);
        $this->dao->update(TABLE_INSTANCE)->set('ldapSettings')->eq(json_encode($settingMap))->where('id')->eq($instance->id)->exec();
        $this->loadModel('setting')->setItem('system.common.ldap.active', 'qucheng');
        $this->loadModel('setting')->setItem('system.common.ldap.instanceID', $instance->id);
        $this->loadModel('setting')->setItem('system.common.ldap.snippetName', $snippetSettings->name); // Parameter for App installation API.

        return $instance;
    }

    /**
     * Install app through API.
     *
     * @param  object $cloudApp
     * @param  string $thirdDomain
     * @param  string $name
     * @param  string $channel
     * @access public
     * @return bool|object
     */
    public function apiInstall($cloudApp, $thirdDomain = '', $name = '', $k8name = '', $channel = 'stable')
    {
        $this->loadModel('space');
        $space = $this->space->defaultSpace($this->app->user->account);

        $customData = new stdclass;
        $customData->dbType       = null;
        $customData->customDomain = $thirdDomain ? $thirdDomain : $this->randThirdDomain();

        $dbInfo = new stdclass;
        $dbList = $this->cne->sharedDBList();
        if(count($dbList) > 0)
        {
            $dbInfo = reset($dbList);

            $customData->dbType    = 'sharedDB';
            $customData->dbService = $dbInfo->name; // Use first shared database.
        }

        $instance = $this->createInstance($cloudApp, $space, $customData->customDomain, $name, $k8name, $channel);
        if(!$instance) return false;

        $settingMap = $this->installationSettingsMap($customData, $dbInfo, $instance);
        return $this->doCneInstall($instance, $space, $settingMap);
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

    /**
     * Upgrade to senior App.
     *
     * @param  object $instance
     * @param  object $seniorApp
     * @access public
     * @return bool
     */
    public function upgradeToSenior($instance, $seniorApp)
    {
        $instance->chart   = $seniorApp->chart;
        $instance->version = $seniorApp->version;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->nameOverride = $this->parseK8Name($instance->k8name)->chart;

        $settings->settings_map->global = new stdclass;
        $settings->settings_map->global->stopped = false;

        if(!$this->cne->updateConfig($instance, $settings))
        {
            dao::$errors[] = $this->lang->instance->errors->failToSenior;
            return false;
        }

        $seniorData = new stdclass;
        $seniorData->chart      = $seniorApp->chart;
        $seniorData->version    = $seniorApp->version;
        $seniorData->appVersion = $seniorApp->app_version;
        $seniorData->appID      = $seniorApp->id;
        $seniorData->appName    = $seniorApp->alias;
        $seniorData->name       = $instance->name . "($seniorApp->alias)";

        $this->dao->update(TABLE_INSTANCE)->data($seniorData)->where('id')->eq($instance->id)->exec();

        $logExtra = new stdclass;
        $logExtra->result = 'success';
        $logExtra->data = new stdclass;
        $logExtra->data->oldAppName = $instance->appName;
        $logExtra->data->newAppName = $seniorApp->alias;

        $this->action->create('instance', $instance->id, 'tosenior', '', json_encode($logExtra));

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
     * Save auto backup settings.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function saveAutoBackupSettings($instance)
    {
        /* Cycle days is always is 1 at present. */
        $settings = fixer::input('post')
            ->setDefault('backupTime', '1:00')
            ->setDefault('cycleDays', '1')
            ->setDefault('keepDays', '10')
            ->setDefault('autoBackup', array())
            ->get();
        $settings->autoBackup = in_array('true', $settings->autoBackup);

        if(!preg_match("/^([0-2][0-9]):([0-5][0-9])$/", $settings->backupTime, $parts))
        {
            dao::$errors[] = $this->lang->instance->backup->invalidTime;
            return false;
        }

        /* Save cron task. */
        list($hour, $minute) = explode(':', $settings->backupTime);
        $cronData = new stdclass;
        $cronData->m   = intval($minute);
        $cronData->h   = intval($hour);
        //$cronData['dom'] = intval($settings->cycleDays);
        $cronData->dom = '*';
        $cronData->mon = '*';
        $cronData->dow = '*';

        $cron = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->eq($instance->id)->fetch();
        if($cron)
        {
            $this->dao->update(TABLE_CRON)->autoCheck()->data($cronData)->where('id')->eq($cron->id)->exec();
        }
        else
        {
            $cronData->objectID = $instance->id;
            $this->dao->insert(TABLE_CRON)->data($cronData)->exec();
        }
        if($this->dao->isError()) return false;

        /* Save instance backup settings. */
        $this->dao->update(TABLE_INSTANCE)->set('autoBackup')->eq($settings->autoBackup)->set('backupKeepDays')->eq($settings->keepDays)->where('id')->eq($instance->id)->exec();
        if($this->dao->isError()) return false;

        $this->action->create('instance', $instance->id, 'saveAutoBackupSettings', '', json_encode(array('data' => $settings)));
        return true;
    }

    /**
     * Get auto backup settings.
     *
     * @param  int    $instnaceID
     * @access public
     * @return object
     */
    public function getAutoBackupSettings($instanceID)
    {
        $instance = $this->getByID($instanceID);

        $cron = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->eq($instanceID)->limit(1)->fetch();
        if(!$cron) $cron = new stdclass;

        $hour   = substr('0' . zget($cron, 'h', '1'), -2, 2);
        $minute = substr('0' . zget($cron, 'm', '00'), -2, 2);

        $settings = new stdclass;
        $settings->backupTime = "{$hour}:{$minute}";
        $settings->autoBackup = boolval(zget($instance, 'autoBackup', false));
        $settings->keepDays   = $settings->autoBackup ? intval(zget($instance, 'backupKeepDays', 7)) : 7; // Set default value to 7 days.
        $settings->cycleDays  = 1; // Cycle days is always is 1 at present.

        return $settings;
    }

    /**
     * Save auto backup settings.
     *
     * @param  object $instance
     * @access public
     * @return bool
     */
    public function saveAutoRestoreSettings($instance)
    {
        /* Cycle days is always is 1 at present. */
        $settings = fixer::input('post')
            ->setDefault('restoreTime', '1:00')
            ->setDefault('cycleDays', '1')
            ->setDefault('autoRestore', array())
            ->get();
        $settings->autoRestore = in_array('true', $settings->autoRestore);

        if(!preg_match("/^([0-2][0-9]):([0-5][0-9])$/", $settings->restoreTime, $parts))
        {
            dao::$errors[] = $this->lang->instance->restore->invalidTime;
            return false;
        }

        /* Save cron task. */
        list($hour, $minute) = explode(':', $settings->restoreTime);
        $cronData = new stdclass;
        $cronData->m   = intval($minute);
        $cronData->h   = intval($hour);
        //$cronData['dom'] = intval($settings->cycleDays);
        $cronData->dom = '*';
        $cronData->mon = '*';
        $cronData->dow = '*';

        $cron = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->eq($instance->id)->fetch();
        if($cron)
        {
            $this->dao->update(TABLE_CRON)->autoCheck()->data($cronData)->where('id')->eq($cron->id)->exec();
        }
        else
        {
            $cronData->objectID = $instance->id;
            $this->dao->insert(TABLE_CRON)->data($cronData)->exec();
        }
        if($this->dao->isError()) return false;

        /* Save instance restore settings. */
        $this->dao->update(TABLE_INSTANCE)->set('autoRestore')->eq($settings->autoRestore)->where('id')->eq($instance->id)->exec();
        if($this->dao->isError()) return false;

        $this->action->create('instance', $instance->id, 'saveAutoRestoreSettings', '', json_encode(array('data' => $settings)));
        return true;
    }

    /**
     * Get auto restore settings.
     *
     * @param  int    $instnaceID
     * @access public
     * @return object
     */
    public function getAutoRestoreSettings($instanceID)
    {
        $instance = $this->getByID($instanceID);

        $cron = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->eq($instanceID)->limit(1)->fetch();
        if(!$cron) $cron = new stdclass;

        $hour   = substr('0' . zget($cron, 'h', '1'), -2, 2);
        $minute = substr('0' . zget($cron, 'm', '00'), -2, 2);

        $settings = new stdclass;
        $settings->restoreTime = "{$hour}:{$minute}";
        $settings->autoRestore = boolval(zget($instance, 'autoRestore', false));
        $settings->cycleDays   = 1; // Cycle days is always is 1 at present.

        return $settings;
    }

    /**
     * Do auto backup cron task.
     *
     * @access public
     * @return void
     */
    public function autoBackup()
    {
        $instanceList = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('autoBackup')->eq(true)
            ->andWhere('status')->eq('running')
            ->fetchAll('id');

        /* Load all crons that instance is enable auto backup. */
        $crons = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->in(array_keys($instanceList))->fetchAll();

        $nowHour  = intval(date('H'));
        $nowMiute = intval(date('i'));
        foreach($crons as $cron)
        {
            $hour   = intval($cron->h);
            $minute = intval($cron->m);
            if(!($nowHour == $hour and $nowMiute == $minute)) continue;

            /* 1. create new backup of instance. */
            $system = new stdclass;
            $system->account = 'auto';  // The string 'auto' should be kept for System.

            $instance = zget($instanceList, $cron->objectID);
            $instance->spaceData = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->eq($instance->space)->fetch();
            $this->backup($instance, $system);

            $this->action->create('instance', $instance->id, 'autoBackup');

            /* 2. Pick latest successful backup recorder. */
            $latestBackup = null;
            $backupList = $this->backupList($instance);
            foreach($backupList as $backup)
            {
                if(empty($latestBackup) or $backup->status == 'completed' && $backup->create_time > $latestBackup->create_time)
                {
                    $latestBackup = $backup;
                }
            }

            /* 3. delete expired backup. Get backup list of instance, then check every backup is expired or not.*/
            // foreach($backupList as $backup)
            // {
            //     if($backup->creator != 'auto') continue; // Only delete data madde by auto backup.
            //     if($latestBackup && $latestBackup->name == $backup->name) continue; // Keep latest successful backup.
            // }
        }
    }

    /**
     * Delete backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return bool
     */
    public function deleteBackup($instance, $backupName)
    {
        return $this->cne->deleteBackup($instance, $backupName);
    }

    /**
     * Backup instance.
     *
     * @param  object $instance
     * @param  object $user
     * @access public
     * @return bool
     */
    public function backup($instance, $user)
    {
        $result = $this->cne->backup($instance, $user->account);
        if($result->code != 200) return false;

        return true;
    }

    /**
     * Restore instance.
     *
     * @param  object $instance
     * @param  object $user
     * @param  string $backupName
     * @access public
     * @return bool
     */
    public function restore($instance, $user, $backupName)
    {
        $result = $this->cne->restore($instance, $backupName, $user->account);
        if($result->code != 200) return false;

        return true;
    }

    /* Run restore latest successful manual backup recorder automatically.
     *
     * @access public
     * @return void
     */
    public function autoRestore()
    {
        $instanceList = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('autoRestore')->eq(true)
            ->andWhere('status')->eq('running')
            ->fetchAll('id');

        /* Load all crons that instance is enable auto restore. */
        $crons = $this->dao->select('*')->from(TABLE_CRON)->where('objectID')->in(array_keys($instanceList))->fetchAll();

        $nowHour  = intval(date('H'));
        $nowMiute = intval(date('i'));
        foreach($crons as $cron)
        {
            $hour   = intval($cron->h);
            $minute = intval($cron->m);
            if(!($nowHour == $hour and $nowMiute == $minute)) continue;

            /* Pick latest successful manual backup recorder. */
            $latestBackup = null;
            $instance     = $this->getByID($cron->objectID);
            $backupList   = $this->backupList($instance);
            foreach($backupList as $backup)
            {
                if($backup->username == 'auto') continue; // Only restore manual backup.
                if(empty($latestBackup) or $backup->status == 'completed' && $backup->create_time > $latestBackup->create_time)
                {
                    $latestBackup = $backup;
                }
            }

            if($latestBackup)
            {
                $sysUser = new stdclass;
                $sysUser->account = 'auto';
                $this->restore($instance, $sysUser, $latestBackup->name);

                $this->action->create('instance', $instance->id, 'autoRestore');
            }
        }
    }

    /**
     * Get backup list of instance.
     *
     * @param  object $instance
     * @access public
     * @return array
     */
    public function backupList($instance)
    {
        $result = $this->cne->backupList($instance);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $backupList = $result->data;
        usort($backupList, function($backup1, $backup2){ return $backup1->create_time < $backup2->create_time; });

        $accounts = helper::arrayColumn($backupList, 'creator');
        foreach($backupList as $backup) $accounts = array_merge($accounts, helper::arrayColumn($backup->restores, 'creator'));

        $accounts = array_unique($accounts);

        $users = $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($accounts)->fetchPairs('account', 'realname');

        foreach($backupList as &$backup)
        {
            $backup->latest_restore_time   = 0;
            $backup->latest_restore_status = '';
            $backup->username = zget($users, $backup->creator);
            /* Mount backup operator info and latest restore info. */
            foreach($backup->restores as &$restore)
            {
                $restore->username = zget($users, $restore->creator);

                if($restore->create_time > $backup->latest_restore_time)
                {
                    $backup->latest_restore_time   = $restore->create_time;
                    $backup->latest_restore_status = $restore->status;
                }
            }
        }

        return $backupList;
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
     * Restore instance by data from k8s cluster.
     *
     * @access public
     * @return void
     */
    public function restoreInstanceList()
    {
        $k8AppList  = $this->cne->instancelist();
        $k8NameList = array_keys($k8AppList);

        //软删除不存在的数据
        $this->dao->update(TABLE_INSTANCE)->set('deleted')->eq(1)->where('k8name')->notIn($k8NameList)->exec();
        foreach($k8AppList as $k8App)
        {
            $existInstance = $this->dao->select('id')->from(TABLE_INSTANCE)->where('k8name')->eq($k8App->name)->fetch();
            if($existInstance) continue;

            $this->loadModel('store');
            $marketApp = $this->store->getAppInfo(0, false, $k8App->chart, $k8App->version,  $k8App->channel);
            if(empty($marketApp)) continue;

            $instanceData = new stdclass;
            $instanceData->appId        = $marketApp->id;
            $instanceData->appName      = $marketApp->alias;
            $instanceData->name         = $marketApp->alias;
            $instanceData->logo         = $marketApp->logo;
            $instanceData->desc         = $marketApp->desc;
            $instanceData->introduction = isset($marketApp->introduction) ? $marketApp->introduction : $marketApp->desc;
            $instanceData->source       = 'cloud';
            $instanceData->channel      = $k8App->channel;
            $instanceData->chart        = $k8App->chart;
            $instanceData->appVersion   = $marketApp->app_version;
            $instanceData->version      = $k8App->version;
            $instanceData->k8name       = $k8App->name;
            $instanceData->status       = 'stopped';

            $parsedK8Name = $this->parseK8Name($k8App->name);

            $instanceData->createdBy = $k8App->username ? $k8App->username : $parsedK8Name->createdBy;
            $instanceData->createdAt =  $parsedK8Name->createdAt;

            $space = $this->dao->select('id,k8space')->from(TABLE_SPACE)->where('k8space')->eq($k8App->namespace)->fetch();
            if(empty($space)) $space = $this->loadModel('space')->defaultSpace($instanceData->createdBy);

            $instanceData->space = $space->id;

            $this->dao->insert(TABLE_INSTANCE)->data($instanceData)->exec();
        }
    }

    /**
     * Parse K8Name to get more data: chart, created time, user name.
     *
     * @param  string $k8Name
     * @access public
     * @return object
     */
    public function parseK8Name($k8Name)
    {
        $datePosition = strripos($k8Name, '-');
        $createdAt    = trim(substr($k8Name, $datePosition), '-');

        $createdBy       = trim(substr($k8Name, 0, $datePosition), '-');
        $accountPosition = strripos($createdBy, '-');
        $createdBy       = trim(substr($createdBy, $accountPosition), '-');

        $parsedData = new stdclass;
        $parsedData->chart     = trim(substr($k8Name, 0, $accountPosition));
        $parsedData->createdBy = trim($createdBy, '-');
        $parsedData->createdAt = date('Y-m-d H:i:s', strtotime($createdAt));

        return $parsedData;
    }

    /**
     * Delete expired instances if run in demo mode.
     *
     * @access public
     * @return void
     */
    public function deleteExpiredDemoInstance()
    {
        $demoAccounts = array_filter(explode(',', $this->config->demoAccounts));

        $instanceList = $this->dao->select('*')->from(TABLE_INSTANCE)
            ->where('deleted')->eq(0)
            ->andWhere('createdBy')->in($demoAccounts)
            ->fetchAll();
        if(empty($instanceList)) return;

        $spaceList = $this->dao->select('*')->from(TABLE_SPACE)->where('id')->in(helper::arrayColumn($instanceList, 'space'))->fetchAll('id');

        foreach($instanceList as $instance)
        {
            $instance->spaceData = zget($spaceList, $instance->space);
            if(empty($instance->spaceData)) continue;

            $this->uninstall($instance);
        }
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
     * Print suggested memory size by current memory usage. Show suggested messge when memory usage more than 90%.
     *
     * @param  object $memUsage
     * @param  array  $memoryOptions
     * @access public
     * @return void
     */
    public function printSuggestedMemory($memUsage, $memoryOptions)
    {
        if($memUsage->rate < 90) return;

        foreach($memoryOptions as  $size => $memText)
        {
            if($size > ($memUsage->limit / 1024))
            {
                printf($this->lang->instance->adjustMemorySize, $memText);
                return ;
            }
        }
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

    /*
     * Print action buttons with icon.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printIconActions($instance)
    {
        $actionHtml = '';

        $disableStart = !$this->canDo('start', $instance);
        $actionHtml  .= html::commonButton("<i class='icon-play'></i>", "instance-id='{$instance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn btn-lg btn-action");

        $disableStop = !$this->canDo('stop', $instance);
        $actionHtml .= html::commonButton('<i class="icon-off"></i>', "instance-id='{$instance->id}' title='{$this->lang->instance->stop}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn btn-lg btn-action');

        if(empty($instance->solution))
        {
            $disableUninstall = !$this->canDo('uninstall', $instance);
            $actionHtml      .= html::commonButton('<i class="icon-trash"></i>', "instance-id='{$instance->id}' title='{$this->lang->instance->uninstall}'" . ($disableUninstall ? ' disabled ' : ''), 'btn-uninstall btn btn-lg btn-action');
        }

        if($instance->domain)
        {
            $disableVisit = !$this->canDo('visit', $instance);
            $actionHtml  .= html::a($this->url($instance), '<i class="icon icon-menu-my"></i>', '_blank', "title='{$this->lang->instance->visit}' class='btn btn-lg btn-action btn-link'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
        }

        echo $actionHtml;
    }

    /*
     * Print action buttons with text.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printTextActions($instance)
    {
        $actionHtml = '';

        $disableStart = !$this->canDo('start', $instance);
        $actionHtml  .= html::commonButton($this->lang->instance->start, "instance-id='{$instance->id}' title='{$this->lang->instance->start}'" . ($disableStart ? ' disabled ' : ''), "btn-start btn label label-outline label-primary label-lg");

        $disableStop = !$this->canDo('stop', $instance);
        $actionHtml .= html::commonButton($this->lang->instance->stop, "instance-id='{$instance->id}' title='{$this->lang->instance->stop}'" . ($disableStop ? ' disabled ' : ''), 'btn-stop btn label label-outline label-warning label-lg');

        if(empty($instance->solution))
        {
            $disableUninstall = !$this->canDo('uninstall', $instance);
            $actionHtml      .= html::commonButton($this->lang->instance->uninstall, "instance-id='{$instance->id}' title='{$this->lang->instance->uninstall}'" . ($disableUninstall ? ' disabled ' : ''), 'btn-uninstall btn label  label-outline label-danger label-lg');
        }

        if($instance->domain)
        {
            $disableVisit = !$this->canDo('visit', $instance);
            $actionHtml  .= html::a($this->url($instance), $this->lang->instance->visit, '_blank', "title='{$this->lang->instance->visit}' class='btn btn-primary label-lg'" . ($disableVisit ? ' disabled style="pointer-events: none;"' : ''));
        }

        echo $actionHtml;
    }

    /**
     * Print backup button of instance.
     *
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printBackupBtn($instance)
    {
        $disabled = $instance->status == 'running' ? '' : 'disabled';
        $title    = empty($disabled) ? $this->lang->instance->backup->create : $this->lang->instance->backupOnlyRunning;
        $btn      = html::commonButton($this->lang->instance->backup->create, "instance-id='{$instance->id}' title='{$title}' {$disabled}", "btn-backup btn btn-primary");

        echo $btn;
    }

    /**
     * Print restore button of instance.
     *
     * @param  object $instance
     * @param  object $backup
     * @access public
     * @return void
     */
    public function printRestoreBtn($instance, $backup)
    {
        $disabled = $instance->status == 'running' && strtolower($backup->status) == 'completed' ? '' : 'disabled';
        $title    = empty($disabled) ? $this->lang->instance->backup->restore : $this->lang->instance->restoreOnlyRunning;
        $btn      = html::commonButton($this->lang->instance->backup->restore, "instance-id='{$instance->id}' title='{$title}' {$disabled} backup-name='{$backup->name}'", "btn-restore btn btn-info");

        echo $btn;
    }

    /**
     * Print button for managing database.
     *
     * @param  object $db
     * @param  object $instance
     * @access public
     * @return void
     */
    public function printDBAction($db, $instance)
    {
        $disabled = $db->ready ? '' : 'disabled';
        $btnHtml  = html::commonButton($this->lang->instance->management, "{$disabled} data-db-name='{$db->name}' data-db-type='{$db->db_type}' data-id='{$instance->id}'", 'db-login btn btn-primary');

        echo $btnHtml;
    }

    /**
     * Print message of action log of instance.
     *
     * @param  object $instance
     * @param  object $log
     * @access public
     * @return string
     */
    public function printLog($instance, $log)
    {
        if(empty($this->userPairs)) $this->userPairs = $this->loadModel('user')->getPairs('noclosed|noletter');
        $action  = zget($this->lang->instance->actionList, $log->action, $this->lang->actions);
        $logText = zget($this->userPairs, $log->actor) . ' ' . sprintf($action, $instance->name, $log->comment);

        $extra = json_decode($log->extra);
        if(empty($extra) or !isset($extra->data))
        {
            return $logText;
        }

        switch($log->action)
        {
            case 'editname':
                $oldName  = zget($extra->data, 'oldName', '');
                $newName  = zget($extra->data, 'newName', '');
                $logText .= ', ' . sprintf($this->lang->instance->nameChangeTo, $oldName, $newName);
                break;
            case 'upgrade':
                $oldVersion = zget($extra->data, 'oldVersion', '');
                $newVersion = zget($extra->data, 'newVersion', '');
                $logText   .= ', ' . sprintf($this->lang->instance->versionChangeTo, $oldVersion, $newVersion);
                break;
            case 'tosenior':
                $oldAppName = zget($extra->data, 'oldAppName', '');
                $newAppName = zget($extra->data, 'newAppName', '');
                $logText   .= ', ' . sprintf($this->lang->instance->toSeniorSerial, $oldAppName, $newAppName);
                break;
            case 'saveautobackupsettings':
                $enableAutoBackup = zget($extra->data, 'autoBackup', 0);
                $logText         .= ': ' . ($enableAutoBackup ? $this->lang->instance->enableAutoBackup : $this->lang->instance->disableAutoBackup);
                break;
            default:
        }

        return $logText;
    }

    /*
     * Convert CPU digital to readable format.
     *
     * @param  array  $cpuList
     * @access public
     * @return array
     */
    public function getCpuOptions($cpuList)
    {
        $newList = array();
        foreach($cpuList as $cpuValue) $newList[$cpuValue] = $cpuValue . $this->lang->instance->cpuCore;
        return $newList;
    }

    /*
     * Convert memory digital to readable format.
     *
     * @param  array  $memList
     * @access public
     * @return array
     */
    public function getMemOptions($memList)
    {
        $newList = array();
        foreach($memList as $memValue) $newList[$memValue] = helper::formatKB(intval($memValue));
        return $newList;
    }

    /*
     * Get instance switcher.
     *
     * @param  object  $instance
     * @access public
     * @return string
     */
    public function getSwitcher($instance)
    {
        $instanceLink = helper::createLink('instance', 'view', "id=$instance->id");

        $output  = "<div class='btn-group header-btn'>";
        $output .= html::a($instanceLink, $instance->appName, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of custom installation page of store.
     *
     * @param  object $app
     * @access public
     * @return array
     */
    public function getInstallSwitcher($app)
    {
        $output  = $this->loadModel('store')->getAppViewSwitcher($app);
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('instance', 'install', "id=$app->id"), $this->lang->instance->installApp, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get switcher of custom installation page of store.
     *
     * @param  object $app
     * @access public
     * @return array
     */
    public function getCustomInstallSwitcher($app)
    {
        $output  = $this->loadModel('store')->getAppViewSwitcher($app);
        $output .= "<div class='btn-group header-btn'>";
        $output .= html::a(helper::createLink('instance', 'custominstall', "id=$app->id"), $this->lang->instance->customInstall, '', 'class="btn"');
        $output .= "</div>";

        return $output;
    }

    /**
     * Get senior app list. The instance can be switched to senior App.
     *
     * @param  object $instance
     * @param  string $channel
     * @access public
     * @return array
     */
    public function seniorAppList($instance, $channel)
    {
        $appList = array();
        foreach(zget($this->config->instance->seniorChartList, $instance->chart, array()) as $chart)
        {
            $cloudApp = $this->loadModel('store')->getAppInfoByChart($chart, $channel, false);
            if($cloudApp) $appList[] = $cloudApp;
        }

        return $appList;
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
     * 判断按钮是否显示。
     * Adjust the action display.
     *
     * @param  object $instance
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isDisplay(object $instance, string $action): bool
    {
        if($action !== 'visit' && !commonModel::hasPriv('instance', 'manage')) return false;
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
