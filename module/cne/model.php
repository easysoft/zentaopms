<?php
declare(strict_types=1);
/**
 * The model file of CNE(Cloud Native Engine) module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   CNE
 * @link      https://www.zentao.net
 */
class cneModel extends model
{
    public $error;

    /**
     * Construct function: set api headers.
     *
     * @param  string $appName
     * @access public
     * @return void
     */
    public function __construct($appName = '')
    {
        parent::__construct($appName);

        $this->error = new stdclass();

        global $config, $app;
        $config->CNE->api->headers[]   = "{$config->CNE->api->auth}: {$config->CNE->api->token}";
        $config->cloud->api->headers[] = "{$config->cloud->api->auth}: {$config->cloud->api->token}";

        if($config->cloud->api->switchChannel && $app->session->cloudChannel)
        {
            $config->cloud->api->channel = $app->session->cloudChannel;
            $config->CNE->api->channel   = $app->session->cloudChannel;
        }
    }

    /**
     * 更新实例配置。例如：cpu、内存大小、LDAP设置...
     * Update instance config. For example: cpu, memory size, LDAP settings...
     *
     * @link   https://yapi.qc.oop.cc/project/21/interface/api/725
     * @param  object $instance
     * @param  object $settings
     * @access public
     * @return bool
     */
    public function updateConfig(object $instance, object $settings = null): bool
    {
        $apiParams = array();
        $apiParams['cluster']   = '';
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['name']      = $instance->k8name;
        $apiParams['channel']   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;
        $apiParams['chart']     = $instance->chart;

        if(isset($instance->version))           $apiParams['version']           = $instance->version;
        if(isset($settings->force_restart))     $apiParams['force_restart']     = $settings->force_restart;
        if(isset($settings->settings))          $apiParams['settings']          = $settings->settings;
        if(isset($settings->settings_map))      $apiParams['settings_map']      = $settings->settings_map;
        if(isset($settings->settings_snippets)) $apiParams['settings_snippets'] = $settings->settings_snippets;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 校验证书。
     * Valid Certificate.
     *
     * @param  string $certName
     * @param  string $pem
     * @param  string $key
     * @param  string $domain
     * @access public
     * @return object
     */
    public function validateCert(string $certName, string $pem, string $key, string $domain)
    {
        $apiParams = array();
        $apiParams['name'] = $certName;
        $apiParams['certificate_pem'] = $pem;
        $apiParams['private_key_pem'] = $key;

        $apiUrl = "/api/cne/system/tls/validator";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return $result;

        $match = false;
        foreach($result->data->sans as $domainPattern)
        {
            if(stripos($domainPattern, $domain) !== false)
            {
                $match = true;
                break;
            }
        }
        if(!$match)
        {
            $result->code = 40004;
            $result->message = zget($this->lang->CNE->errorList, 40004);
        }

        return $result;
    }

    /**
     * 更新证书。
     * Upload cert.
     *
     * @param  object $cert
     * @param  string $channel
     * @access public
     * @return object
     */
    public function uploadCert(object $cert, string $channel = ''): ?object
    {
        $apiParams = array();
        $apiParams['cluster']         = '';
        $apiParams['channel']         = empty($channel) ? $this->config->CNE->api->channel : $channel;
        $apiParams['name']            = $cert->name;
        $apiParams['certificate_pem'] = $cert->certificate_pem;
        $apiParams['private_key_pem'] = $cert->private_key_pem;

        return $this->apiPost('/api/cne/system/tls/upload', $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取证书信息。
     * Get cert info.
     *
     * @param  string $certName
     * @param  string $channel
     * @access public
     * @return object|null success: return cert info, fail: return null.
     */
    public function certInfo(string $certName, string $channel = ''): ?object
    {
        $apiParams = array();
        $apiParams['cluster'] = '';
        $apiParams['channel'] = empty($channel) ? $this->config->CNE->api->channel : $channel;
        $apiParams['name']    = $certName;

        $apiUrl = "/api/cne/system/tls/info";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result->data;

        return null;
    }

    /**
     * 获取实例的默认账号密码。
     * Get default username and password of app.
     *
     * @param  object $instance
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDefaultAccount(object $instance, string $component = ''): ?object
    {
        $apiUrl = '/api/cne/app/account?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = '';
        if($component) $apiParams['component'] = $component;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers, $this->config->CNE->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        $account = $result->data;
        if(isset($account->username) && $account->username && isset($account->password) && $account->password) return $account;

        return null;
    }

    /**
     * 获取域名后缀。
     * Get suffix domain.
     *
     * @access public
     * @return string
     */
    public function sysDomain(): string
    {
        $customDomain = $this->loadModel('setting')->getItem('owner=system&module=common&section=domain&key=customDomain');
        if($customDomain) return $customDomain;

        if(getenv('APP_DOMAIN'))                    return getenv('APP_DOMAIN');
        if(!empty($this->config->CNE->app->domain)) return $this->config->CNE->app->domain;
        return '';
    }

    /**
     * 获取实例的域名。
     * Get domain.
     *
     * @param  object $instance
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDomain(object $instance, string $component = ''): ?object
    {
        $apiUrl = '/api/cne/app/domain?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = '';
        if($component) $apiParams['component'] = $component;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers, $this->config->CNE->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        return empty($result->data->internal_host) ? null : $result->data;
    }

    /**
     * 获取CNE平台的集群度量。
     * Get cluster metrics of CNE platform.
     *
     * @access public
     * @return object
     */
    public function cneMetrics(): object
    {
        $metrics = new stdclass();
        $metrics->cpu = new stdclass();
        $metrics->cpu->usage       = 0;
        $metrics->cpu->capacity    = 0;
        $metrics->cpu->allocatable = 0;
        $metrics->cpu->rate        = 0;

        $metrics->memory = new stdclass();
        $metrics->memory->usage       = 0;
        $metrics->memory->capacity    = 0;
        $metrics->memory->allocatable = 0;
        $metrics->memory->rate        = 0;

        $statistics = new stdclass();
        $statistics->status     = 'unknown';
        $statistics->node_count = 0;
        $statistics->metrics    = $metrics;

        $result = $this->apiGet('/api/cne/statistics/cluster', array('cluster' => ''), $this->config->CNE->api->headers);
        if($result->code != 200) return $statistics;

        $statistics = $result->data;
        $statistics->metrics->cpu->usage       = max(round($statistics->metrics->cpu->usage, 4), 0);
        $statistics->metrics->cpu->capacity    = max(round($statistics->metrics->cpu->capacity, 4), $statistics->metrics->cpu->usage);
        $statistics->metrics->cpu->allocatable = round($statistics->metrics->cpu->allocatable, 4);
        $statistics->metrics->cpu->rate        = $statistics->metrics->cpu->capacity > 0 ? round( $statistics->metrics->cpu->usage / $statistics->metrics->cpu->capacity * 100, 2) : 0;
        $statistics->metrics->cpu->rate        = min($statistics->metrics->cpu->rate, 100);

        $statistics->metrics->memory->usage    = max(round($statistics->metrics->memory->usage, 4), 0);
        $statistics->metrics->memory->capacity = max($statistics->metrics->memory->capacity, $statistics->metrics->memory->usage);
        $statistics->metrics->memory->rate     = $statistics->metrics->memory->capacity > 0 ? round($statistics->metrics->memory->usage / $statistics->metrics->memory->capacity * 100, 2) : 0;
        $statistics->metrics->memory->rate     = min($statistics->metrics->memory->rate, 100);
        return $statistics;
    }

    /**
     * Get the volumes metrics of the instance.
     *
     * @param  object $instance
     * @return object
     */
    public function getVolumesMetrics(object $instance): object
    {
        $metric = new stdclass;
        $metric->limit = 0;
        $metric->usage = 0;
        $metric->rate  = 0;

        $volumes = $this->getAppVolumes($instance);
        if($volumes)
        {
            foreach($volumes as $volume)
            {
                if(!$volume->is_block_device) return $metric;
                $metric->limit = $volume->size;
                $metric->usage = $volume->actual_size;
                break;
            }
        }

        $metric->rate  = $metric->limit != 0 ? round($metric->usage / $metric->limit * 100, 2) : 0.01;

        return $metric;
    }

    /**
     * 获取磁盘配置信息。
     * Get the disk settings.
     *
     * @param  object $instance
     * @return object
     */
    public function getDiskSettings(object $instance, bool|string $component = false): object
    {
        $diskSetting = new stdclass;
        $diskSetting->resizable   = false;
        $diskSetting->size        = 0;
        $diskSetting->used        = 0;
        $diskSetting->limit       = 0;
        $diskSetting->name        = '';
        $diskSetting->requestSize = 0;

        $volumes = $this->getAppVolumes($instance, $component);
        if($volumes)
        {
            foreach($volumes as $volume)
            {
                if(!$volume->is_block_device) return $diskSetting;

                $diskSetting->resizable   = true;
                $diskSetting->size        = ceil($volume->size / 1073741824);
                $diskSetting->used        = ceil($volume->actual_size / 1073741824);
                $diskSetting->limit       = floor($volume->max_increase_size / 1073741824);
                $diskSetting->name        = $volume->setting_keys->size->path;
                $diskSetting->requestSize = ceil($volume->request_size / 1073741824);
                break;
            }
        }

        return $diskSetting;
    }

    /**
     * 获取平台实例的度量。
     * Get instance metrics.
     *
     * @param  array  $instances
     * @access public
     * @return array
     */
    public function instancesMetrics(array $instances): array
    {
        $instancesMetrics = array();

        $apiData = array('cluster' => '', 'apps' => array());
        foreach($instances as $instance)
        {
            if($instance->source == 'external') continue;

            $appData = new stdclass();
            $appData->name      = $instance->k8name;
            $appData->namespace = $instance->spaceData->k8space;
            $apiData['apps'][]  = $appData;

            $instanceMetric = new stdclass();
            $instanceMetric->id        = $instance->id;
            $instanceMetric->name      = $instance->k8name;
            $instanceMetric->namespace = $instance->spaceData->k8space;

            $instanceMetric->cpu = new stdclass();
            $instanceMetric->cpu->limit = 0;
            $instanceMetric->cpu->usage = 0;
            $instanceMetric->cpu->rate  = 0;

            $instanceMetric->memory = new stdclass();
            $instanceMetric->memory->limit = 0;
            $instanceMetric->memory->usage = 0;
            $instanceMetric->memory->rate  = 0;

            $instanceMetric->disk = $this->getVolumesMetrics($instance);

            $instancesMetrics[$instance->k8name] = $instanceMetric;
        }

        $result = $this->apiPost('/api/cne/statistics/app', $apiData, $this->config->CNE->api->headers);
        if(!isset($result->code) || $result->code != 200)return array_combine(helper::arrayColumn($instancesMetrics, 'id'), $instancesMetrics);

        foreach($result->data as $k8sMetric)
        {
            if(!isset($k8sMetric->metrics)) continue;

            $instancesMetrics[$k8sMetric->name]->cpu->usage = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->usage) ? round($k8sMetric->metrics->cpu->usage, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->usage = max($instancesMetrics[$k8sMetric->name]->cpu->usage, 0);
            $instancesMetrics[$k8sMetric->name]->cpu->limit = isset($k8sMetric->metrics->cpu) && isset($k8sMetric->metrics->cpu->limit) ? round($k8sMetric->metrics->cpu->limit, 4) : 0;
            $instancesMetrics[$k8sMetric->name]->cpu->limit = max($instancesMetrics[$k8sMetric->name]->cpu->limit, $instancesMetrics[$k8sMetric->name]->cpu->usage);
            $instancesMetrics[$k8sMetric->name]->cpu->rate  = $instancesMetrics[$k8sMetric->name]->cpu->limit > 0 ? round($instancesMetrics[$k8sMetric->name]->cpu->usage / $instancesMetrics[$k8sMetric->name]->cpu->limit * 100, 2) : 0;

            $instancesMetrics[$k8sMetric->name]->memory->usage = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->usage) ? $k8sMetric->metrics->memory->usage : 0;
            $instancesMetrics[$k8sMetric->name]->memory->usage = max($instancesMetrics[$k8sMetric->name]->memory->usage, 0);
            $instancesMetrics[$k8sMetric->name]->memory->limit = isset($k8sMetric->metrics->memory) && isset($k8sMetric->metrics->memory->limit) ? $k8sMetric->metrics->memory->limit : 0;
            $instancesMetrics[$k8sMetric->name]->memory->limit = max( $instancesMetrics[$k8sMetric->name]->memory->limit, $instancesMetrics[$k8sMetric->name]->memory->usage);
            $instancesMetrics[$k8sMetric->name]->memory->rate  = $instancesMetrics[$k8sMetric->name]->memory->limit > 0 ? round($instancesMetrics[$k8sMetric->name]->memory->usage / $instancesMetrics[$k8sMetric->name]->memory->limit * 100, 2) : 0;
        }

        return array_combine(helper::arrayColumn($instancesMetrics, 'id'), $instancesMetrics);
    }

    /**
     * 备份一个应用实例。
     * Backup a instance with account.
     *
     * @param  object $instance
     * @param  string $account
     * @param  string $mode     |manual|system|upgrade|downgrade
     * @access public
     * @return object
     */
    public function backup(object $instance, string|null $account = '', string $mode = ''): object
    {
        $apiParams = new stdclass;
        $apiParams->username  = $account ?: $this->app->user->account;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        if(!empty($mode)) $apiParams->mode = $mode;

        $apiUrl = "/api/cne/app/backup";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份的进度。
     * Get the status of backup progress.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return object
     */
    public function getBackupStatus(object $instance, string $backupName): object
    {
        $apiParams = new stdclass;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份列表。
     * Get the backup list.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function getBackupList(object $instance): object
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backups";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 删除一个备份.
     * Delete backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return object
     */
    public function deleteBackup(object $instance, string $backupName): object
    {
        $apiParams = new stdclass;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup/remove";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 恢复一个备份。
     * Restore from the backup.
     *
     * @param  object $instance
     * @param  object $backupName
     * @param  string $account
     * @access public
     * @return object
     */
    public function restore(object $instance, string $backupName, string $account = ''): object
    {
        $apiParams = new stdclass;
        $apiParams->username    = $account ?: $this->app->user->account;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/restore";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份恢复的状态。
     * Get the status of restore progress.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return object
     */
    public function getRestoreStatus(object $instance, string $backupName): object
    {
        $apiParams = new stdclass;
        $apiParams->cluster      = '';
        $apiParams->namespace    = $instance->spaceData->k8space;
        $apiParams->name         = $instance->k8name;
        $apiParams->restore_name = $backupName;
        $apiParams->channel      = empty($instance->channel) ? $this->config->cne->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/restore/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 启动一个应用实例。
     * Start an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function startApp(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/start";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 停止一个应用实例。
     * Stop an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function stopApp(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/stop";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 安装应用。
     * Install app.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function installApp(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/install";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 卸载应用。
     * Uninstall an app instance.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function uninstallApp(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/uninstall";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取应用的安装日志。
     * Get app install logs.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function getAppLogs(object $instance): ?object
    {
        if(!isset($this->app->user->account))
        {
            $this->app->user = new stdclass();
            $this->app->user->account = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetch('account');
        }
        $defaultSpace = $this->loadModel('space')->defaultSpace($this->app->user->account);

        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->namespace = !empty($instance->spaceData->k8space) ? $instance->spaceData->k8space : $defaultSpace->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->tail      = 500;

        $apiUrl = "/api/cne/app/logs";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 查询应用的数据卷。
     * Get app volumes.
     *
     * @link   https://yapi.qc.oop.cc/project/21/interface/api/168
     * @param  object     $instance
     * @param bool|string $component true|'mysql'
     * @return object|array|false
     */
    public function getAppVolumes(object $instance, bool|string $component = false): object|array|false
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = $this->config->CNE->api->channel;

        if($component === true)   $apiParams->component = 'mysql';
        if(!empty($component) && is_string($component)) $apiParams->component = $component;

        $apiUrl = "/api/cne/app/volumes";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(!$result || $result->code != 200) return false;

        return $result->data;
    }

    /**
     * 按实例获取设置映射。
     * Get settings mapping by instance.
     *
     * @param  object      $instance
     * @param  array       $mappings
     * @access public
     * @return object|null
     */
    public function getSettingsMapping(object $instance, array $mappings = array()): ?object
    {
        if(empty($mappings)) $mappings = array(
            array(
                "key"  => "admin_username",
                "type" => "helm",
                "path" => "auth.username"
            ),
            array(
                "key"  => "z_username",
                "path" => "z_username",
                "type" => "secret"
            ),
            array(
                "key"  => "z_password",
                "path" => "z_password",
                "type" => "secret"
            ),
            array(
                "key"  => "api_token",
                "path" => "api_token",
                "type" => "secret"
            )
        );

        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->mappings  = $mappings;

        $apiUrl = "/api/cne/app/settings/mapping";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return null;

        return $result->data;
    }

    /**
     * 获取应用配置和资源。
     * Get app config and resource.
     *
     * @param  object       $instance
     * @access public
     * @return object|false
     */
    public function getAppConfig(object $instance): object|false
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/settings/common";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return false;

        $resources = new stdclass();
        $resources->cpu    = 0;
        $resources->memory = 0;
        $result->data->min = zget($result->data, 'oversold', $resources);
        $result->data->max = zget($result->data, 'resources', $resources);
        return $result->data;
    }

    /**
     * 获取应用实例状态。
     * Query status of an app instance.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function queryStatus(object $instance): ?object
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->name      = $instance->k8name;
        $apiParams->chart     = $instance->chart;
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/status";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result;

        return $result;
    }

    /**
     * 批量获取应用实例状态。
     * Query status of instance list.
     *
     * @param  array  $instanceList
     * @access public
     * @return array
     */
    public function batchQueryStatus(array $instanceList): array
    {
        $apiParams = new stdclass();
        $apiParams->cluster   = '';
        $apiParams->apps = array();

        foreach($instanceList as $instance)
        {
            $app = new stdclass();
            $app->name      = $instance->k8name;
            $app->chart     = $instance->chart;
            $app->namespace = $instance->spaceData->k8space;
            $app->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

            $apiParams->apps[] = $app;
        }

        $apiUrl = "/api/cne/app/status/multi";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200) return array();

        $statusList = array();
        foreach($result->data as $status) $statusList[$status->name] = $status;

        return $statusList;
    }

    /**
     * 获取应用的数据库列表。
     * Get all database list of app.
     *
     * @param  object  $instance
     * @access public
     * @return array
     */
    public function appDBList(object $instance): array
    {
        $apiUrl    = "/api/cne/app/dbs";
        $apiParams =  array();
        $apiParams['cluster']   = '';
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['channel']   = $this->config->CNE->api->channel;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(helper::arrayColumn($dbList, 'name'), $dbList);
    }

    /**
     * 获取应用数据库的详细信息。
     * Get detail of app database.
     *
     * @param  object       $instance
     * @param  string       $dbName
     * @access public
     * @return object|false
     */
    public function appDBDetail(object $instance, string $dbName): object|false
    {
        $apiParams =  array();
        $apiParams['cluster']   = '';
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['db']        = $dbName;
        $apiParams['channel']   = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/dbs/detail";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return false;

        return $result->data;
    }

    /**
     * 获取数据库配置。
     * Get database detail.
     *
     * @param  string       $dbService
     * @param  string       $namespace
     * @access public
     * @return object|false
     */
    public function dbDetail(string $dbService, string $namespace): object|false
    {
        $apiUrl    = "/api/cne/component/dbservice/detail";
        $apiParams =  array('name' => $dbService, 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return false;

        return $result->data;
    }

    /**
     * 获取数据库列表。
     * Get all database list.
     *
     * @access public
     * @return array
     */
    public function allDBList(): array
    {
        $apiUrl    = "/api/cne/component/dbservice";
        $apiParams = array('global' => 'true', 'namespace' => 'quickon-system', 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(helper::arrayColumn($dbList, 'name'), $dbList);
    }

    /**
     * 获取共享数据库列表。
     * Get shared database list.
     *
     * @param  string $dbType
     * @access public
     * @return array
     */
    public function sharedDBList(string $dbType = 'mysql'): array
    {
        $apiUrl    = "/api/cne/component/gdb";
        $apiParams =  array('kind' => $dbType, 'namespace' => 'default', 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(helper::arrayColumn($dbList, 'name'), $dbList);
    }

    /**
     * 校验数据库名称和用户是否可用。
     * Validate database name and user.
     *
     * @param  string $dbService
     * @param  string $dbUser
     * @param  string $dbName
     * @param  string $namespace
     * @access public
     * @return object
     */
    public function validateDB(string $dbService, string $dbUser, string $dbName, string $namespace): object
    {
        $apiParams = array();
        $apiParams['name']      = $dbService;
        $apiParams['user']      = $dbUser;
        $apiParams['database']  = $dbName;
        $apiParams['namespace'] = $namespace;

        $apiUrl = "/api/cne/component/gdb/validation";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result->data->validation;

        $validation = new stdclass();
        $validation->user     = true;
        $validation->database = true;

        return $validation;
    }

    /**
     * 通过Get方式调用API。
     * Get method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header example: array("key1:value1", "key2:value2")
     * @param  string       $host
     * @access public
     * @return object
     */
    public function apiGet(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $requestUri  = ($host ? $host : $this->config->CNE->api->host) . $url;
        $requestUri .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data, '', '&', PHP_QUERY_RFC3986);
        $result      = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'GET'), $header, 'json', 'GET', 20));

        if($result && $result->code == 200) return $result;
        if($result) return $this->translateError($result);

        return $this->cneServerError();
    }

    /**
     * 通过Post方式调用API。
     * Post method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $header example: array("key1:value1", "key2:value2")
     * @param  string       $host
     * @access public
     * @return object
     */
    public function apiPost(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'POST'), $header, 'json', 'POST', 20));
        if($result && in_array($result->code, array(200, 201)))
        {
            $result->code = 200;
            return $result;
        }

        if($result) return $this->translateError($result);
        return $this->cneServerError();
    }

    /**
     * 返回API服务器错误对象。
     * Return error object of api server.
     *
     * @access protected
     * @return object
     */
    protected function cneServerError(): object
    {
        $error = new stdclass();
        $error->code    = 600;
        $error->message = $this->lang->CNE->serverError;
        return $error;
    }

    /**
     * 翻译错误信息。
     * Translate error message for multi language.
     *
     * @param  object    $apiResult
     * @access protected
     * @return object
     */
    protected function translateError(object &$apiResult): object
    {
        $this->error->code    = $apiResult->code;
        $this->error->message = zget($this->lang->CNE->errorList, $apiResult->code, $this->lang->CNE->serverError); // Translate CNE api error message to multi language.

        if($this->config->debug)
        {
            if(isset($apiResult->code))    $this->error->message .= " [{$apiResult->code}]:";
            if(isset($apiResult->message)) $this->error->message .= " [{$apiResult->message}]";
        }

        $apiResult->message = $this->error->message;

        return $this->error;
    }

    /**
     * app资源调度尝试。
     * Try allocate for apps.
     *
     * @param  array $apps
     * @access public
     * @return object
     */
    public function tryAllocate(array $resources): object
    {
        $apiParams = new stdclass();
        $apiParams->requests = $resources;

        $apiUrl = "/api/cne/system/resource/try-allocate";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 从云API服务器获取最新的发布版本号。
     * Get the latest release version from cloud API server.
     *
     * @return string|false
     */
    public function getLatestVersion(): string|false
    {
        $cloudApiHost = getenv('CLOUD_API_HOST');
        if(empty($cloudApiHost)) $cloudApiHost = $this->config->cloud->api->host;

        $currentRelease = array(
            'name' => 'zentaopaas',
            'channel' => getenv('CLOUD_DEFAULT_CHANNEL') ?: 'stable',
            'version' => getenv('APP_VERSION')
        );
        $query = http_build_query($currentRelease);

        $response = common::http($cloudApiHost . '/api/market/app/version/latest?' . $query);
        if($response)
        {
            $response =json_decode($response);
            if($response && $response->code == 200) return $response->data->version;
        }
        return false;
    }

    /**
     * 升级禅道DevOps平台版。
     * Upgrade the quickon system.
     *
     * @param  string $edition oss|open|biz|max|ipd
     * @return object|false
     */
    public function upgrade($edition = 'oss'): object|false
    {
        $numArgs = func_num_args();
        if($numArgs == 0) $edition = $this->config->edition;
        if($edition == 'open') $edition = 'oss';

        $version = $this->getLatestVersion();
        if(!$version) return false;

        $apiParams = array(
            'channel' => getenv('CLOUD_DEFAULT_CHANNEL') ?: 'stable',
            'version' => $version,
            'product' => $edition
        );

        $apiUrl = "/api/cne/system/update";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }
}
