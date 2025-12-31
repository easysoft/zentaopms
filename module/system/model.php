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
     * @param  int    $productID
     * @param  string $status
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(int $productID, string $status = 'active', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSystemList();

        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->beginIF($status && $status != 'all')->andWhere('status')->eq($status)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);
    }

    /**
     * 获取应用键值对。
     * Get app pairs.
     *
     * @param  int    $productID
     * @param  string $integrated
     * @param  string $status
     * @access public
     * @return array
     */
    public function getPairs(int $productID = 0, string $integrated = '', string $status = ''): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getSystemPairs();

        return $this->dao->select('id, name')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($status)->andWhere('status')->eq($status)->fi()
            ->beginIF($integrated !== '')->andWhere('integrated')->eq($integrated)->fi()
            ->orderBy('id DESC')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据产品ids列表获取状态正常的非集成应用键值对。
     * @param int[] $products
     * @return array
     */
    public function getPairsByProducts(array $products): array
    {
        $products = array_values(array_filter($products));
        return $this->dao->select('id, name')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->andWhere('status')->eq('active')
            ->andWhere('integrated')->eq(0)
            ->orderBy('id DESC')
            ->fetchPairs('id', 'name');
    }

    /**
     * 根据ID列表获取应用。
     * Get apps by id list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByIdList(array $idList): array
    {
        return $this->dao->select('*')->from(TABLE_SYSTEM)
            ->where('deleted')->eq('0')
            ->andWhere('id')->in($idList)
            ->fetchAll('id');
    }

    /**
     * 根据应用ID列表获取产品ID列表。
     * Get product id list by system id list.
     *
     * @param  array $systemIDs
     * @return array
     */
    public function getProductListBySystemIds(array $systemIDs)
    {
        return $this->dao->select('t1.id,t1.name as appName,t1.product,t2.name as productName')
            ->from(TABLE_SYSTEM)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.id')->in(implode(',', $systemIDs))
            ->fetchAll('id');
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
        if(dao::isError())
        {
            if($this->app->rawModule != 'system' && !empty(dao::$errors['name']))
            {
                dao::$errors['systemName'] = dao::$errors['name'];
                unset(dao::$errors['name']);
            }
            return false;
        }

        $systemID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('system', $systemID, 'created', '', '', zget($formData, 'createdBy', $this->app->user->account));
        return $systemID;
    }

    /**
     * 编辑应用。
     * Edit an app.
     *
     * @param  int    $id
     * @param  object $formData
     * @param  string $type
     * @access public
     * @return bool
     */
    public function update(int $id, object $formData, string $type = 'edit'): bool
    {
        $oldSystem = $this->fetchByID($id);
        $change    = common::createChanges($oldSystem, $formData);
        if(empty($change)) return true;

        $this->dao->update(TABLE_SYSTEM)->data($formData)
            ->check('name', 'unique', '`id` != ' . $id)
            ->autoCheck()
            ->beginIF($type == 'edit')->batchCheck($this->config->system->edit->requiredFields, 'notempty')->fi()
            ->where('id')->eq($id)
            ->exec();
        if(dao::isError()) return false;

        $actionType = $type == 'edit' ? 'edited' : $type;

        $actionID = $this->loadModel('action')->create('system', $id, $actionType);
        if($actionID) $this->action->logHistory($actionID, $change);
        return !dao::isError();
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
            $this->unsetMaintenance('backup');
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
        $ztVersion = $this->loadModel('upgrade')->getOpenVersion(str_replace('.', '_', $this->config->version));
        $currentRelease['zentao_version'] = str_replace('_', '.', $ztVersion);
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

    /**
     * 检查按钮是否可用。
     * Check if the button is clickable.
     *
     * @param  object $system
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $system, string $action): bool
    {
        if($action == 'active') return $system->status == 'inactive';
        if($action == 'inactive') return $system->status == 'active';
        return true;
    }

    /**
     * 更新应用的最新发布信息。
     * Update the latest release of the app.
     *
     * @param  int    $systemID
     * @param  int    $releaseID
     * @param  string $releasedDate
     * @access public
     * @return bool
     */
    public function setSystemRelease(int $systemID, int $releaseID, string $releasedDate = ''): bool
    {
        $system = $this->fetchByID($systemID);
        if(!$system) return false;

        if(empty($releasedDate))
        {
            if($releaseID != $system->latestRelease) return false;

            $release      = $this->dao->select('id,createdDate')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('system')->eq($systemID)->orderBy('id DESC')->fetch();
            $releaseID    = $release ? $release->id : 0;
            $releasedDate = $release ? $release->createdDate : null;
        }

        $this->dao->update(TABLE_SYSTEM)->set('latestDate')->eq($releasedDate)->set('latestRelease')->eq($releaseID)->where('id')->eq($systemID)->exec();
        return !dao::isError();
    }

    /**
     * 根据ID获取发布信息。
     * Get release information by ID.
     *
     * @param  int $systemID
     * @access public
     * @return array
     */
    public function getReleasesByID(int $systemID): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('system')->eq($systemID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 根据ID获取构建信息。
     * Get build information by ID.
     *
     * @param  int $systemID
     * @access public
     * @return array
     */
    public function getBuildsByID(int $systemID): array
    {
        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('system')->eq($systemID)
            ->andWhere('deleted')->eq(0)
            ->fetchAll('id');
    }

    /**
     * 初始化应用。
     * Initialize application.
     *
     * @access public
     * @return bool
     */
    public function initSystem(): bool
    {
        $productPairs = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
        $releasePairs = $this->dao->select('id,product,date,createdDate')->from(TABLE_RELEASE)->where('deleted')->eq('0')->fetchAll('product');

        $systemPairs = array();
        $systemNames = array();
        $systemList  = $this->dao->select('id,product,name')->from(TABLE_SYSTEM)->where('deleted')->eq('0')->fetchAll();
        foreach($systemList as $system)
        {
            $system->name = strtolower($system->name);
            $systemNames[$system->name]    = $system->id;
            $systemPairs[$system->product] = $system->id;
        }

        $system = new stdclass();
        $system->createdDate = helper::now();
        $system->createdBy   = 'system';
        foreach($productPairs as $productID => $productName)
        {
            $systemID = zget($systemPairs, $productID, 0);
            if(!$systemID)
            {
                if(isset($systemNames[strtolower($productName)])) $productName .= '-1';

                $system->name          = $productName;
                $system->product       = $productID;
                $system->latestDate    = null;
                $system->latestRelease = 0;
                if(isset($releasePairs[$productID]))
                {
                    $system->latestDate    = $releasePairs[$productID]->createdDate ? $releasePairs[$productID]->createdDate : "{$releasePairs[$productID]->date} 00:00:00";
                    $system->latestRelease = $releasePairs[$productID]->id;
                }
                $systemID = $this->create($system);

                if(dao::isError()) continue;
            }

            $this->dao->update(TABLE_BUILD)->set('system')->eq($systemID)->where('product')->eq($productID)->andWhere('system')->eq(0)->exec();
            $this->dao->update(TABLE_RELEASE)->set('system')->eq($systemID)->where('product')->eq($productID)->andWhere('system')->eq(0)->exec();
        }

        if(!dao::isError()) $this->dao->delete()->from(TABLE_CRON)->where('command')->eq('moduleName=system&methodName=initSystem')->exec();

        return dao::isError();
    }
}
