<?php
declare(strict_types=1);
/**
 * The control file of system module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   system
 * @version   $Id$
 * @link      https://www.zentao.net
 * @property  systemModel $system
 * @property  cneModel    $cne
 */
class system extends control
{

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->loadModel('action');
        $this->loadModel('setting');
        $this->loadModel('cne');
        $this->loadModel('instance');
    }

    /**
     * 服务仪表盘。
     * Dashboard page.
     *
     * @param  int    $total
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function dashboard(int $total = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('cne');
        $this->app->loadClass('pager', true);
        $pager = new pager($total, $recPerPage, $pageID);

        $instances        = $this->loadModel('instance')->getList($pager, '', '', 'running');
        $instancesMetrics = $this->cne->instancesMetrics($instances);

        foreach($instances as $instance)
        {
            $metrics       = zget($instancesMetrics, $instance->id);
            $instance->cpu = $this->instance->printCpuUsage($instance, $metrics->cpu);
            $instance->mem = $this->instance->printStorageUsage($instance, $metrics->memory);
        }

        $actions = $this->loadModel('action')->getDynamic('all', 'today');
        $cneMetrics = $this->cne->cneMetrics();

        $this->view->title      = $this->lang->my->common;
        $this->view->instances  = $instances;
        $this->view->actions    = $actions;
        $this->view->cneMetrics = $cneMetrics;
        $this->view->cpuInfo    = $this->systemZen->getCpuUsage($cneMetrics->metrics->cpu);
        $this->view->memoryInfo = $this->systemZen->getMemUsage($cneMetrics->metrics->memory);
        $this->view->pager      = $pager;

        $this->display();
    }

    /**
     * 数据库列表。
     * Show database list.
     *
     * @access public
     * @return void
     */
    public function dbList()
    {
        $this->app->loadLang('instance');

        $this->view->title  = $this->lang->system->dbManagement;
        $this->view->dbList = $this->loadModel('cne')->allDBList();

        $this->display();
    }

    /**
     * 对象存储视图。
     * OSS view.
     *
     * @access public
     * @return void
     */
    public function ossView()
    {
        $this->loadModel('cne');

        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $ossAccount = $this->cne->getDefaultAccount($minioInstance, 'minio');
        $ossDomain  = $this->cne->getDomain($minioInstance, 'minio');

        $this->view->title      = $this->lang->system->oss->common;
        $this->view->ossAccount = $ossAccount ? $ossAccount : new stdclass();
        $this->view->ossDomain  = $ossDomain;

        $this->display();
    }

    /**
     * 自定义域名配置。
     * Config customer's domain.
     *
     * @access public
     * @return void
     */
    public function configDomain()
    {
        $domainSettings = $this->system->getDomainSettings();
        if($domainSettings->customDomain) $this->locate($this->inLink('domainView'));
        $this->locate($this->inLink('editDomain'));
    }

    /**
     * 编辑自定义域名。
     * Edit customer's domain.
     *
     * @access public
     * @return void
     */
    public function editDomain()
    {
        if(!commonModel::hasPriv('system', 'configDomain')) $this->loadModel('common')->deny('system', 'configDomain', false);
        $this->loadModel('instance');

        if($_POST)
        {
            session_write_close();
            $settings = form::data($this->config->system->form->editDomain)
                ->setDefault('https', 'false')
                ->setIf(is_array($this->post->https) && in_array('true', $this->post->https), 'https', 'true')
                ->get();

            $this->system->saveDomainSettings($settings);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError(true)));

            return $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->updateDomainSuccess, 'locate' => $this->inlink('domainView')));
        }

        $this->view->title          = $this->lang->system->domain->common;
        $this->view->domainSettings = $this->system->getDomainSettings();

        $this->display();
    }

    /**
     * 校验证书。
     * Ajax valid cert.
     *
     * @access public
     * @return void
     */
    public function ajaxValidateCert()
    {
        $certData = fixer::input('post')->get();

        if(!validater::checkREG($certData->customDomain, '/^((?!-)[a-z0-9-]{1,63}(?<!-)\\.)+[a-z]{2,6}$/'))
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->invalidDomain));
        }

        $certName = 'tls-' . str_replace('.', '-',$certData->customDomain);
        $result = $this->loadModel('cne')->validateCert($certName, $certData->certPem, $certData->certKey, $certData->customDomain);
        if($result->code == 200) return $this->send(array('result' => 'success', 'message' => $this->lang->system->notices->validCert));

        return $this->send(array('result' => 'fail', 'message' => $result->message));
    }

    /**
     * 更新域名的进度。
     * Show progress of updating domains.
     *
     * @access public
     * @return void
     */
    public function ajaxUpdatingDomainProgress()
    {
        session_write_close();

        $oldDomainQty  = $this->loadModel('instance')->countOldDomain();
        return print(sprintf($this->lang->system->domain->updatingProgress, $oldDomainQty));
    }

    /**
     * 域名设置视图。
     * Domain settings view.
     *
     * @access public
     * @return void
     */
    public function domainView()
    {
        $domainSettings = $this->system->getDomainSettings();
        $certName       = 'tls-' . str_replace('.', '-', $domainSettings->customDomain);
        $cert           = $this->loadModel('cne')->certInfo($certName);

        $notAfter = zget($cert, 'not_after', '');
        if($notAfter) $cert->expiredDate = date('Y-m-d H:i:s', $notAfter);

        $this->view->title          = $this->lang->system->domain->common;
        $this->view->domainSettings = $domainSettings;
        $this->view->cert           = $cert;

        $this->display();
    }

    /**
     * 创建一个禅道DevOps平台版的备份。
     * Backup the system.
     *
     * @param  string $reload yes|no
     * @param  string $mode   |manual|system|upgrade|downgrade
     * @access public
     * @return void
     */
    public function backup(string $reload = 'no', string $mode = 'manual')
    {
        if($reload == 'yes') session_write_close();

        set_time_limit(0);

        if(!$this->config->inQuickon) $this->sendError($this->lang->system->cneStatus);

        $this->loadModel('instance');
        $instance = $this->config->instance->zentaopaas;

        $result = $this->system->backup($instance, $mode);
        $this->loadModel('action')->create('system', 0, 'createBackup');

        if($result['result'] == 'success')
        {
            $backupName = $result['data']->backup_name;
            $this->send($result + array('callback' => "backupInProgress('$backupName')"));
        }
        else
        {
            $this->send($result);
        }
    }

    /**
     * 恢复一个备份。
     * Restore the backup.
     *
     * @param  string $backupName
     * @return void
     */
    public function restoreBackup($backupName)
    {
        session_write_close();
        set_time_limit(0);

        if(empty($this->config->system->noBackupBeforeRestore))
        {
            $instance     = $this->config->instance->zentaopaas;
            $backupResult = $this->cne->backup($instance, null, 'restore');
            if($backupResult->code != 200) $this->sendError($backupResult->message);

            while(true)
            {
                $backupStatus = $this->cne->getBackupStatus($instance, $backupResult->data->backup_name);
                if($backupStatus->code != 200) $this->sendError($backupStatus->message);
                if(strtolower($backupStatus->data->status) == 'completed') break;
                sleep(1);
            }
        }

        $backupName   = str_replace('_', '-', $backupName);
        $result = $this->system->restore($instance, $backupName);

        $this->loadModel('action')->create('system', 0, 'restoreBackup', '', $backupName);

        $this->send($result + array('load' => true, 'callback' => "restoreInProgress('$backupName')"));
    }

    /**
     * 删除一个备份。
     * Delete the backup.
     *
     * @param  string $backupName
     * @return void
     */
    public function deleteBackup($backupName)
    {
        $backupName = str_replace('_', '-', $backupName);
        $instance = $this->config->instance->zentaopaas;

        $result = $this->system->deleteBackup($instance, $backupName);

        $this->loadModel('action')->create('system', 0, 'deleteBackup', '', $backupName);

        $this->send($result + array('load' => true, 'callback' => "deleteInProgress('$backupName')"));
    }

    /**
     * 执行系统升级。
     * Upgrade ths quickon system.
     *
     * @param  string $edition
     * @return void
     */
    public function upgrade($backup = 'yes', $edition = 'open')
    {
        session_write_close();
        set_time_limit(0);

        if(!$this->system->isUpgradeable()) $this->sendError($this->lang->system->backup->error->beenLatestVersion);

        $this->loadModel('action')->create('system', 0, 'upgradeSystem');

        if($backup == 'yes' && empty($this->config->system->noBackupBeforeUpgrade))
        {
            $instance     = $this->config->instance->zentaopaas;
            $backupResult = $this->cne->backup($instance, null, 'upgrade');
            if($backupResult->code != 200) $this->sendError($backupResult->message);

            while(true)
            {
                $backupStatus = $this->cne->getBackupStatus($instance, $backupResult->data->backup_name);
                if($backupStatus->code != 200) $this->sendError($backupStatus->message);
                if(strtolower($backupStatus->data->status) == 'completed') break;
                sleep(1);
            }
        }

        $rawResult = $this->cne->upgrade($edition);
        if($rawResult)
        {
            if($rawResult->code == 200)
                $this->sendSuccess(array('message' => $this->lang->system->backup->success->upgrade, 'callback' => 'upgradeInProgress'));
            else
                $this->sendError($rawResult->message);
        }

        $this->sendError($this->lang->CNE->serverError);
    }

    /**
     * AJAX: 获取备份列表。
     * AJAX: Get the backup list.
     *
     * @return void
     */
    public function ajaxGetBackups()
    {
        $result = $this->system->getBackupList($this->config->instance->zentaopaas);
        $this->send($result);
    }

    /**
     * 获取备份的进度。
     * AJAX: Get the progress of the backup.
     *
     * @access public
     * @param  string $backupName
     * @return void
     */
    public function ajaxGetBackupProgress($backupName)
    {
        session_write_close();

        if(strpos($backupName, '_') !== false) $backupName = str_replace('_', '-', $backupName);
        $result = $this->cne->getBackupStatus($this->config->instance->zentaopaas, $backupName);
        if($result && $result->code == 200)
        {
            $status = strtolower($result->data->status);
            if($status == 'completed') return $this->send(array('result' => 'success', 'message' => $this->lang->system->backup->backupSucceed, 'status' => 'completed', 'closeModal' => true, 'load' => helper::createLink('backup', 'index')));
            if($status == 'pending' || $status == 'inprogress') return $this->send(array('result' => 'progress', 'status' => 'inprogress', 'text' => sprintf($this->lang->system->backup->progress, $result->data->completed, $result->data->total)));
            if($status == 'failed') return $this->send(array('result' => 'failed', 'message' => $this->lang->system->backup->error->backupFail, 'closeModal' => true));
        }
        else
        {
            return $this->send(array('result' => 'failed', 'message' => $result->message, 'closeModal' => true));
        }
    }

    /**
     * 获取还原的进度。
     * AJAX: Get the progress of the restore.
     *
     * @access public
     * @param  string $backupName
     * @return void
     */
    public function ajaxGetRestoreProgress($backupName)
    {
        session_write_close();

        $backupName = str_replace('_', '-', $backupName);
        $result = $this->cne->getRestoreStatus($this->config->instance->zentaopaas, $backupName);
        if($result && $result->code == 200)
        {
            $status = $result->data->status;
            if($status == 'completed') return $this->send(array('result' => 'success', 'message' => $this->lang->system->backup->restoreSucceed, 'status' => 'completed', 'load' => helper::createLink('backup', 'index')));
            if($status == 'pending' || $status == 'inprogress') return $this->send(array('result' => 'progress', 'status' => 'inprogress', 'text' => sprintf($this->lang->system->backup->progressStore, $result->data->completed, $result->data->total)));
            if($status == 'failed') return $this->send(array('result' => 'failed', 'message' => $this->lang->system->backup->error->backupFail, 'load' => helper::createLink('backup', 'index')));
        }
        else
        {
            return $this->send(array('result' => 'failed', 'message' => $result->message, 'load' => helper::createLink('backup', 'index')));
        }
    }

    /**
     * 获取升级的进度。（当前没有进度，但可以通过获取是否可以升级来检查）
     * AJAX: Get upgrade progress.
     *
     * @return void
     */
    public function ajaxGetUpgradeProgress()
    {
        $isUpgradeable = $this->system->isUpgradeable();
        if($isUpgradeable) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->backup->upgrading));
        return $this->send(array('result' => 'success', 'message' => $this->lang->system->backup->success->upgrade, 'load' => true));
    }

    /**
     * 获取删除的进度。
     *  AJAX: Get delete progress.
     *
     * @param  string $backupName
     * @return void
     */
    public function ajaxGetDeleteProgress($backupName)
    {
        session_write_close();

        if(strpos($backupName, '_') !== false) $backupName = str_replace('_', '-', $backupName);
        $rawResult = $this->cne->getBackupList($this->config->instance->zentaopaas);
        if($rawResult && $rawResult->code == 200)
        {
            foreach($rawResult->data as $backup)
            {
                if($backup->name == $backupName) return $this->sendSuccess(array('status' => 'inprogress', 'message' => $rawResult->message));
            }
            $this->sendSuccess(array('status' => 'completed', 'message' => $rawResult->message));
        }
        $this->sendError(isset($rawResult->message) ? $rawResult->message : 'fail');
    }

    /**
     * 生成数据库授权链接。
     * Generate database auth parameters and jump to login page.
     *
     * @access public
     * @return void
     */
    public function ajaxDBAuthUrl()
    {
        $post = fixer::input('post')
            ->setDefault('namespace', 'default')
            ->get();
        if(empty($post->dbName)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->dbNameIsEmpty));

        $detail = $this->loadModel('cne')->dbDetail($post->dbName, $post->namespace);
        if(empty($detail)) return $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->notFoundDB));

        $this->app->loadConfig('instance');
        $dbAuth = array();
        $dbAuth['driver']   = zget($this->config->instance->adminer->dbTypes, $post->dbType, '');
        $dbAuth['server']   = $detail->host . ':' . $detail->port;
        $dbAuth['username'] = $detail->username;
        $dbAuth['db']       = $detail->database;
        $dbAuth['password'] = $detail->password;

        $url = '/adminer?'. http_build_query($dbAuth);
        $this->send(array('result' => 'success', 'message' => '', 'data' => array('url' => $url)));
    }

    /**
     * 获取对象存储信息。
     * Get oss account and domain by ajax.
     *
     * @access public
     * @return void
     */
    public function ajaxOssInfo()
    {
        $minioInstance = new stdclass;
        $minioInstance->k8name    = 'cne-operator';
        $minioInstance->spaceData = new stdclass;
        $minioInstance->spaceData->k8space = $this->config->k8space;

        $ossAccount = $this->loadModel('cne')->getDefaultAccount($minioInstance, 'minio');

        $ossDomain  = $this->cne->getDomain($minioInstance, 'minio');
        $ossDomain->domain = $ossDomain->access_host;

        $url = $this->loadModel('instance')->url($ossDomain);

        if($ossAccount and $ossDomain) return  $this->send(array('result' => 'success', 'message' => '', 'data' => array('account' => $ossAccount, 'url' => $url)));

        $this->send(array('result' => 'fail', 'message' => $this->lang->system->errors->failGetOssAccount));
    }
}
