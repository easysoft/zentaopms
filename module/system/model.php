<?php
declare(strict_types=1);
/**
 * The model file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 * @property  cneModel $cne
 */
class systemModel extends model
{
    /**
     * 获取应用列表。
     * Get app list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $orderBy = 'id_desc', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取应用键值对。
     * Get app pairs.
     *
     * @param  string $integrated
     * @access public
     * @return array
     */
    public function getPairs(string $integrated = ''): array
    {
        return $this->dao->select('id, name')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->beginIF($integrated)->andWhere('integrated')->eq($integrated)->fi()
            ->fetchPairs('id', 'name');
    }

    /**
     * 创建应用。
     * Create an app.
     *
     * @param  object $formData
     * @access public
     * @return bool|int
     */
    public function create(object $formData): bool|int
    {
        $this->dao->insert(TABLE_SYSTEM)->data($formData)
            ->check('name', 'unique')
            ->batchCheck($this->config->system->create->requiredFields, 'notempty')
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 获取自定义的域名设置。
     * Get customized domain settings.
     *
     * @access public
     * @return object
     */
    public function getDomainSettings()
    {
        $this->loadModel('setting');

        $settings = new stdclass;
        $settings->customDomain = $this->setting->getItem('owner=system&module=common&section=domain&key=customDomain');
        $settings->https        = $this->setting->getItem('owner=system&module=common&section=domain&key=https');
        $settings->certPem      = '';
        $settings->certKey      = '';

        return $settings;
    }

    /**
     * 保存自定义的域名设置。
     * Save customized domain settings.
     *
     * @param  object $setting
     * @access public
     * @return void
     */
    public function saveDomainSettings(object $settings)
    {
        $this->loadModel('setting');

        $this->dao->from('system')->data($settings)
            ->check('customDomain', 'notempty')
            ->checkIf($settings->https == 'true', 'certPem', 'notempty')
            ->checkIf($settings->https == 'true', 'certKey', 'notempty');
        if(dao::isError()) return;

        if(!validater::checkREG($settings->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            dao::$errors[] = $this->lang->system->errors->invalidDomain;
            return;
        }

        /* Upload Certificate to CNE. */
        if($settings->https == 'true')
        {
            $cert = new stdclass;
            $cert->name            = 'tls-' . str_replace('.', '-', $settings->customDomain);
            $cert->certificate_pem = $settings->certPem;
            $cert->private_key_pem = $settings->certKey;
            $certResult = $this->loadModel('cne')->uploadCert($cert);
            if($certResult->code != 200)
            {
                dao::$errors[] = $certResult->message;
                return;
            }
        }

        if(stripos($settings->customDomain, 'haogs.cn') !== false) dao::$errors[] = $this->lang->system->errors->forbiddenOriginalDomain;
        if(dao::isError()) return false;

        $expiredDomain   = $this->setting->getItem('owner=system&module=common&section=domain&key=expiredDomain');
        $expiredDomain   = empty($expiredDomain ) ? array(getenv('APP_DOMAIN')) : json_decode($expiredDomain, true);
        $expiredDomain[] = zget($settings, 'customDomain', '');
        $this->setting->setItem('system.common.domain.expiredDomain', json_encode($expiredDomain));
        $this->setting->setItem('system.common.domain.customDomain', zget($settings, 'customDomain', ''));
        $this->setting->setItem('system.common.domain.https', zget($settings, 'https', 'false'));

        $this->loadModel('instance')->updateInstancesDomain();

        $this->updateMinioDomain();
    }

    /**
     * 更新域名。
     * Update minio domain.
     *
     * @access public
     * @return void
     */
    public function updateMinioDomain()
    {
        $this->loadModel('cne');
        $sysDomain = $this->cne->sysDomain();

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->chart     = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $settings = new stdclass;
        $settings->settings_map = new stdclass;
        $settings->settings_map->minio = new stdclass;
        $settings->settings_map->minio->ingress = new stdclass;
        $settings->settings_map->minio->ingress->enabled = true;
        $settings->settings_map->minio->ingress->host    = 's3.' . $sysDomain;

        $this->cne->updateConfig($minioInstance, $settings);
    }

    /**
     * 创建备份。
     * Backup the instance.
     *
     * @param  object $instance
     * @param  string $mode     |manual|system|upgrade|downgrade
     * @return array
     */
    public function backup(?object $instance, string $mode = ''): array
    {
        $this->loadModel('cne');

        if(empty($instance)) $instance = $this->config->instance->zentaopaas;

        if(empty($_SESSION['fromCron'])) $this->setMaintenance('backup');

        $rawResult = $this->cne->backup($instance, $this->app->user->account, $mode);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 获取备份状态。
     * Get backup status.
     *
     * @param  object $instance
     * @param  string $backup
     * @return array
     */
    public function getBackupStatus(object $instance, string $backupName): array
    {
        $this->loadModel('cne');

        $rawResult = $this->cne->getBackupStatus($instance, $backupName);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 获取备份列表。
     * Get backup list.
     *
     * @param  object $instance
     * @return array
     */
    public function getBackupList(object $instance): array
    {
        $this->loadModel('cne');
        $rawResult = $this->cne->getBackupList($instance);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 恢复一个备份。
     * Restore the backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @param  string $account
     * @return array
     */
    public function restore(object $instance, string $backupName, string $account = ''): array
    {
        $this->loadModel('cne');
        $rawResult = $this->cne->restore($instance, $backupName, $account);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 删除一个备份。
     * Delete the backup.
     *
     * @param  object $instance
     * @param  string $backupName
     * @return array
     */
    public function deleteBackup(object $instance, string $backupName): array
    {
        $this->loadModel('cne');
        $rawResult = $this->cne->deleteBackup($instance, $backupName);

        if(!empty($rawResult->code) && $rawResult->code == 200)
        {
            return array('result' => 'success', 'message' => $rawResult->message, 'data' => $rawResult->data);
        }
        else
        {
            return array('result' => 'fail', 'message' => $rawResult->message);
        }
    }

    /**
     * 设置系统维护信息。
     * Set maintenance message.
     *
     * @param  string $action backup|restore|upgrade
     * @return bool
     */
    public function setMaintenance(string $action): bool
    {
        if(empty($action) || !in_array($action, array_keys($this->lang->system->maintenance->reason))) return false;

        $maintenance = new stdclass();
        $maintenance->action = $action;
        $maintenance->reason = zget($this->lang->system->maintenance->reason, $action, $this->lang->unknown);

        return $this->loadModel('setting')->setItem('system.system.maintenance', json_encode($maintenance));
    }

    /**
     * 复原系统维护信息。
     * Unset Maintenance message.
     *
     * @return void
     */
    public function unsetMaintenance(): void
    {
        $maintenance = $this->loadModel('setting')->getItem('owner=system&module=system&key=maintenance');
        if(empty($maintenance)) return;

        $this->setting->deleteItems('owner=system&module=system&key=maintenance');
    }

    /**
     * 从云API服务器获取最新的发布版本。
     * Get the latest release from cloud API server.
     *
     * @return object
     */
    public function getLatestRelease(): object|false
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
            if($response && $response->code == 200) return $response->data;
        }
        return false;
    }

    /**
     * 检查当前版本是否可升级。
     * Check if the current release is upgradeable.
     *
     * @return bool
     */
    public function isUpgradeable(): bool
    {
        $latestRelease = $this->getLatestRelease();
        if(!$latestRelease) return false;

        $latestVersion = false;
        if(isset($latestRelease->products->{$this->config->edition}))
            $latestVersion = $latestRelease->products->{$this->config->edition};
        else
            $latestVersion = $latestRelease->products->oss;

        if(version_compare($latestVersion, $this->config->version, 'lt')) return false;

        $chartVersion = getenv('CHART_VERSION');
        if(empty($chartVersion)) return false;

        if(version_compare($latestRelease->version, $chartVersion, 'gt')) return true;

        return false;
    }
}
