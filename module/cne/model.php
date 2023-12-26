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
    protected $error;

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

        $this->error = new stdclass;

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
     * 获取集群中应用程序的所有实例。
     * Get all instance of app in cluster.
     *
     * @access public
     * @return array
     */
    public function instanceList(): array
    {
        $apiUrl = "/api/cne/system/app-full-list";
        $result = $this->apiGet($apiUrl, array(), $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $instanceList = $result->data;
        return array_combine(helper::arrayColumn($instanceList, 'name'), $instanceList);
    }

    /**
     * 更新平台域名。
     * Update platform domain.
     *
     * @param  string $domain
     * @access public
     * @return bool
     */
    public function updatePlatformDomain(string $domain): bool
    {
        $instance = new stdclass;
        $instance->k8name = 'qucheng';
        $instance->chart  = 'qucheng';

        $instance->spaceData = new stdclass;
        $instance->spaceData->k8space = $this->config->k8space;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->env = new stdclass;
        $settings->settings_map->env->APP_DOMAIN = $domain;

        return $this->updateConfig($instance, $settings);
    }

    /**
     * 平台系统升级。
     * Upgrade platform.
     *
     * @param  string $toVersion
     * @access public
     * @return bool
     */
    public function upgradePlatform(string $toVersion): bool
    {
        $apiUrl = "/api/cne/system/update";
        $result = $this->apiPost($apiUrl, array('version' => $toVersion, 'channel' => $this->config->CNE->api->channel), $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 升级或降级平台版本。
     * Upgrade or degrade platform version.
     *
     * @param  string $version
     * @access public
     * @return bool
     */
    public function setPlatformVersion(string $version): bool
    {
        $instance = new stdclass;
        $instance->k8name = 'qucheng';
        $instance->chart  = 'qucheng';

        $instance->spaceData = new stdclass;
        $instance->spaceData->k8space = $this->config->k8space;

        return $this->upgradeToVersion($instance, $version);
    }

    /**
     * 升级平台实例倒指定版本。
     * Upgrade app instance to version.
     *
     * @param  object $instance
     * @param  string $toVersion
     * @access public
     * @return bool
     */
    public function upgradeToVersion(object $instance, string $toVersion = ''): bool
    {
        $setting = array();
        $setting['cluster']   = '';
        $setting['namespace'] = $instance->spaceData->k8space;
        $setting['name']      = $instance->k8name;
        $setting['channel']   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;
        $setting['chart']     = $instance->chart;
        $setting['version']   = $toVersion;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $setting, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 更新实例配置。例如：cpu、内存大小、LDAP设置...
     * Update instance config. For example: cpu, memory size, LDAP settings...
     *
     * @param  object $instance
     * @param  object $settings
     * @access public
     * @return bool
     */
    public function updateConfig(object $instance, object $settings): bool
    {
        $apiParams = array();
        $apiParams['cluster']   = '';
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['name']      = $instance->k8name;
        $apiParams['channel']   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;
        $apiParams['chart']     = $instance->chart;

        if(isset($instance->version))           $apiParams['version']           = $instance->version;
        if(isset($settings->force_restart))     $apiParams['force_restart']     = $settings->force_restart;
        if(isset($settings->settings_map))      $apiParams['settings_map']      = $settings->settings_map;
        if(isset($settings->settings_snippets)) $apiParams['settings_snippets'] = $settings->settings_snippets;

        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 设置负载均衡。
     * Config QLB (Server Load Balancing).
     *
     * @param  object $settings
     * @param  string $channel
     * @access public
     * @return bool
     */
    public function configQLB(object $settings, string $channel = ''): bool
    {
        $apiParams = array();
        $apiParams['cluster']   = '';
        $apiParams['channel']   = empty($channel) ? $this->config->CNE->api->channel : $channel;
        $apiParams['namespace'] = $settings->namespace;
        $apiParams['name']      = $settings->name;
        $apiParams['ippool']    = $settings->ippool;

        $apiUrl = "/api/cne/system/qlb/config";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 获取负载均衡信息。
     * Get Qucheng Load Balancer Info
     *
     * @param  string $name
     * @param  string $namespace
     * @param  string $channel
     * @access public
     * @return object
     */
    public function getQLBInfo(string $name, string $namespace, string $channel = ''): ?object
    {
        $apiParams = array();
        $apiParams['cluster']   = '';
        $apiParams['channel']   = empty($channel) ? $this->config->CNE->api->channel : $channel;
        $apiParams['namespace'] = $namespace;
        $apiParams['name']      = $name;

        $apiUrl = "/api/cne/system/qlb/config";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return $result->data;

        return null;
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
     * @return bool
     */
    public function validateCert(string $certName, string $pem, string $key, string $domain): bool
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
     * @param  string $cluster
     * @access public
     * @return object|null
     */
    public function getDefaultAccount(object $instance, string $cluster = '', string $component = ''): ?object
    {
        $apiUrl = '/api/cne/app/account?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = $cluster;
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
     * @param  string $cluster
     * @param  string $component
     * @access public
     * @return object|null
     */
    public function getDomain(object $instance, string $cluster = '', string $component = ''): ?object
    {
        $apiUrl = '/api/cne/app/domain?channel='. (empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel);
        $apiParams = array();
        $apiParams['name']      = $instance->k8name;
        $apiParams['namespace'] = $instance->spaceData->k8space;
        $apiParams['cluster']   = $cluster;
        if($component) $apiParams['component'] = $component;

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers, $this->config->CNE->api->host);
        if(!isset($result->code) || $result->code != 200) return null;

        return $result->data;
    }

    /**
     * 获取CNE平台的集群度量。
     * Get cluster metrics of CNE platform.
     *
     * @param  string $cluster
     * @access public
     * @return object
     */
    public function cneMetrics(string $cluster = ''): object
    {
        $metrics = new stdclass;
        $metrics->cpu = new stdclass;
        $metrics->cpu->usage       = 0;
        $metrics->cpu->capacity    = 0;
        $metrics->cpu->allocatable = 0;
        $metrics->cpu->rate        = 0;

        $metrics->memory = new stdclass;
        $metrics->memory->usage       = 0;
        $metrics->memory->capacity    = 0;
        $metrics->memory->allocatable = 0;
        $metrics->memory->rate        = 0;

        $statistics = new stdclass;
        $statistics->status     = 'unknown';
        $statistics->node_count = 0;
        $statistics->metrics    = $metrics;

        $result = $this->apiGet('/api/cne/statistics/cluster', array('cluster' => $cluster), $this->config->CNE->api->headers);
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

            $appData = new stdclass;
            $appData->name      = $instance->k8name;
            $appData->namespace = $instance->spaceData->k8space;
            $apiData['apps'][]  = $appData;

            $instanceMetric = new stdclass;
            $instanceMetric->id        = $instance->id;
            $instanceMetric->name      = $instance->k8name;
            $instanceMetric->namespace = $instance->spaceData->k8space;

            $instanceMetric->cpu = new stdclass;
            $instanceMetric->cpu->limit = 0;
            $instanceMetric->cpu->usage = 0;
            $instanceMetric->cpu->rate  = 0;

            $instanceMetric->memory = new stdclass;
            $instanceMetric->memory->limit = 0;
            $instanceMetric->memory->usage = 0;
            $instanceMetric->memory->rate  = 0;

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
     * 获取CPU使用率。
     * Get CPU usage.
     *
     * @param  object  $metrics
     * @static
     * @access public
     * @return array
     */
    public static function getCpuUsage(object $metrics): array
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = {$metrics->usage} / {$metrics->capacity}";

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 80)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }

    /**
     * 获取内存使用率。
     * Get memory usage.
     *
     * @param  object  $metrics
     * @static
     * @access public
     * @return array
     */
    public static function getMemUsage(object $metrics): array
    {
        $rate = $metrics->rate;
        $tip  = "{$rate}% = " . helper::formatKB($metrics->usage) . ' / ' . helper::formatKB($metrics->capacity);

        if(empty($color) && $rate == 0)               $color = 'gray';
        if(empty($color) && $rate > 0 && $rate < 50)  $color = 'var(--color-secondary-500)';
        if(empty($color) && $rate >= 0 && $rate < 70) $color = 'var(--color-warning-500)';
        if(empty($color) && $rate >= 0 && $rate < 90) $color = 'var(--color-important-500)';
        if(empty($color) && $rate >= 80)              $color = 'var(--color-danger-500)';

        return array('color' => $color, 'tip' => $tip, 'rate' => $rate);
    }

    /**
     * 备份k8s集群中的服务。
     * Backup service in k8s cluster.
     *
     * @param  object $instance
     * @param  string $account
     * @access public
     * @return object
     */
    public function backup(object $instance, string $account): object
    {
        $apiParams = new stdclass;
        $apiParams->username  = $account;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;
        $apiParams->channel   = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份进度。
     * Stauts of backup progress.
     *
     * @param  object $instance
     * @param  object $backup
     * @access public
     * @return object
     */
    public function backupStatus(object $instance, object $backup): ?object
    {
        $apiParams = new stdclass;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backup->backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份列表。
     * Backup list.
     *
     * @param  object $instance
     * @access public
     * @return object
     */
    public function backupList(object $instance): ?object
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
     * 删除备份。
     * (not Finish: waiting cne api) Delete backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @access public
     * @return bool
     */
    public function deleteBackup(object $instance, string $backupName): bool
    {
        $apiParams = new stdclass;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/backup/remove";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 恢复k8s集群中的服务。
     * Restroe service in k8s cluster.
     *
     * @param  object $instance
     * @param  string $backupName
     * @param  string $account
     * @access public
     * @return object
     */
    public function restore(object $instance, string $backupName, string $account): ?object
    {
        $apiParams = new stdclass;
        $apiParams->username    = $account;
        $apiParams->cluster     = '';
        $apiParams->namespace   = $instance->spaceData->k8space;
        $apiParams->name        = $instance->k8name;
        $apiParams->backup_name = $backupName;
        $apiParams->channel     = empty($instance->channel) ? $this->config->CNE->api->channel : $instance->channel;

        $apiUrl = "/api/cne/app/restore";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取恢复进度。
     * Stauts of restore progress.
     *
     * @param  object $instance
     * @param  object $restore
     * @access public
     * @return object
     */
    public function restoreStatus(object $instance, object $restore): ?object
    {
        $apiparams = new stdclass;
        $apiparams->cluster      = '';
        $apiparams->namespace    = $instance->spacedata->k8space;
        $apiparams->name         = $instance->k8name;
        $apiparams->restore_name = $restore->restorename;
        $apiparams->channel      = empty($instance->channel) ? $this->config->cne->api->channel : $instance->channel;

        $apiurl = "/api/cne/app/restore/status";
        return $this->apiget($apiurl, $apiparams, $this->config->cne->api->headers);
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
     * 创建快照。
     * Create snippet.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function addSnippet(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/add";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 更新快照。
     * Update snippet.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function updateSnippet(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/update";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 删除快照。
     * Remove snippet.
     *
     * @param  object $apiParams
     * @access public
     * @return object
     */
    public function removeSnippet(object $apiParams): ?object
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/snippet/remove";
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
        $defaultSpace = $this->loadModel('space')->defaultSpace($this->app->user->account);

        $apiparams = new stdclass;
        $apiparams->cluster   = '';
        $apiparams->namespace = !empty($instance->spacedata->k8space) ? $instance->spacedata->k8space : $defaultSpace->k8space;
        $apiparams->name      = $instance->k8name;
        $apiparams->tail      = 500;

        $apiurl = "/api/cne/app/logs";
        return $this->apiget($apiurl, $apiparams, $this->config->CNE->api->headers);
    }

    /**
     * 校验SMTP配置。
     * Validate SMTP settings.
     *
     * @param  object $apiParams
     * @access public
     * @return bool
     */
    public function validateSMTP(object $apiParams): bool
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/system/smtp/validator";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 配置应用实例。
     * Config app instance.
     *
     * @param  object $apiParams
     * @param  array  $settings
     * @access public
     * @return bool
     */
    public function configApp(object $apiParams, array $settings): bool
    {
        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiParams->settings = $this->transformSettings($settings);
        $apiUrl = "/api/cne/app/settings";
        $result = $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code == 200) return true;

        return false;
    }

    /**
     * 格式化配置。
     * Transform setting format.
     *
     * @param  array  $settings
     * @access private
     * @return aray
     */
    private function transformSettings(array $settings): array
    {
        $transformedSettings = array();
        foreach($settings as $key => $value)
        {
            if(strpos($key, 'replicas') !== false && intval($value) < 1) $value = 1; // Replicas must be greater 0.
            $transformedSettings[] = array('key' => str_replace('_', '.', $key), 'value' => $value);
        }
        return $transformedSettings;
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

        $apiParams = new stdclass;
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
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;

        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/settings/common";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return false;

        $result->data->min = $result->data->oversold;
        $result->data->max = $result->data->resources;
        return $result->data;
    }

    /**
     * 获取应用自定义配置。
     * Get custom items of app.
     *
     * @param  object       $instance
     * @access public
     * @return array|false
     */
    public function getCustomItems(object $instance): array|false
    {
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->namespace = $instance->spaceData->k8space;
        $apiParams->name      = $instance->k8name;

        if(empty($apiParams->channel)) $apiParams->channel = $this->config->CNE->api->channel;

        $apiUrl = "/api/cne/app/settings/custom";
        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if($result && $result->code != 200) return false;

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
        $apiParams = new stdclass;
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
        $apiParams = new stdclass;
        $apiParams->cluster   = '';
        $apiParams->apps = array();

        foreach($instanceList as $instance)
        {
            $app = new stdclass;
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
     * @param  boolean $global true: global database
     * @param  string  $namespace
     * @access public
     * @return array
     */
    public function allDBList(bool $global = true, string $namespace = 'quickon-system'): array
    {
        $apiUrl    = "/api/cne/component/dbservice";
        $apiParams =  array( 'global' => ($global ? 'true' : 'false'), 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

        $result = $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
        if(empty($result) || $result->code != 200 || empty($result->data)) return array();

        $dbList = $result->data;
        return array_combine(helper::arrayColumn($dbList, 'name'), $dbList);
    }

    /**
     * 获取共享数据库列表。
     * Get shared database list.
     *
     * @param  string $dbType    database type.
     * @param  string $namespace
     * @access public
     * @return array
     */
    public function sharedDBList(string $dbType = 'mysql', string $namespace = 'default'): array
    {
        $apiUrl    = "/api/cne/component/gdb";
        $apiParams =  array('kind' => $dbType, 'namespace' => $namespace, 'channel' => $this->config->CNE->api->channel);

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

        $validation = new stdclass;
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
     * 通过Put方式调用API。
     * Put method of API.
     *
     * @param  string        $url
     * @param  array|object  $data
     * @param  array         $header example: array("key1:value1", "key2:value2")
     * @param  string        $host
     * @access public
     * @return object
     */
    public function apiPut(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $header, 'json', 'PUT', 20));
        if($result && $result->code == 200) return $result;
        if($result) return $this->translateError($result);

        return $this->cneServerError();
    }

    /**
     * 通过Delete方式调用API。
     * Delete method of API.
     *
     * @param  string        $url
     * @param  array|object  $data
     * @param  array         $header example: array("key1:value1", "key2:value2")
     * @param  string        $host
     * @access public
     * @return object
     */
    public function apiDelete(string $url, array|object $data, array $header = array(), string $host = ''): object
    {
        $requestUri = ($host ? $host : $this->config->CNE->api->host) . $url;
        $result     = json_decode(commonModel::http($requestUri, $data, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $header, 'json', 'DELETE', 20));
        if($result && $result->code == 200) return $result;
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
        $error = new stdclass;
        $error->code    = 600;
        $error->message = $this->lang->CNE->serverError;
        return $error;
    }

    /**
     * 返回错误信息。
     * Get error message.
     *
     * @access public
     * @return object
     */
    public function getError(): object
    {
        return $this->error;
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

        $apiResult->message = $this->error->message;

        return $this->error;
    }

    /**
     * 获取备份平台列表。
     * Get backup platform list.
     *
     * @access public
     * @return object
     */
    public function getBackupPlatformList(): object
    {
        $apiUrl = "/api/cne/platform/backups";
        return $this->apiGet($apiUrl, array(), $this->config->CNE->api->headers);
    }

    /**
     * 备份平台和应用。
     * Backup platform and apps.
     *
     * @param  array $apps
     * @access public
     * @return object
     */
    public function backupPlatform(array $apps = array()): object
    {
        $apiParams = new stdclass();
        $apiParams->apps = $apps;

        $apiUrl = "/api/cne/platform/backup";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份平台状态。
     * Get backup platfrom status.
     *
     * @param  string $name
     * @access public
     * @return object
     */
    public function backupPlatformStatus(string $name): object
    {
        $apiParams = new stdclass();
        $apiParams->name = $name;

        $apiUrl = "/api/cne/platform/backup/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 还原平台和应用。
     * Restroe platform and apps.
     *
     * @param  array $apps
     * @access public
     * @return object
     */
    public function restorePlatform(string $name): object
    {
        $apiParams = new stdclass();
        $apiParams->backup_set_name = $name;

        $apiUrl = "/api/cne/platform/restore";
        return $this->apiPost($apiUrl, $apiParams, $this->config->CNE->api->headers);
    }

    /**
     * 获取备份平台状态。
     * Get backup platfrom status.
     *
     * @param  string $name
     * @access public
     * @return object
     */
    public function restorePlatformStatus(string $name): object
    {
        $apiParams = new stdclass();
        $apiParams->name = $name;

        $apiUrl = "/api/cne/platform/restore/status";
        return $this->apiGet($apiUrl, $apiParams, $this->config->CNE->api->headers);
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
}
