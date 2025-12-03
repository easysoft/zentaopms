<?php
declare(strict_types=1);
/**
 * The control file of backup of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     backup
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class backup extends control
{
    protected $backupPath;

    /**
     * __construct
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $this->backupPath = $this->backup->getBackupPath();

        if($this->app->methodName != 'setting')
        {
            if(!is_dir($this->backupPath))
            {
                if(!mkdir($this->backupPath, 0777, true)) $this->view->error = sprintf($this->lang->backup->error->noWritable, dirname($this->backupPath));
            }
            else
            {
                if(!is_writable($this->backupPath)) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->backupPath);
            }
            if(!is_writable($this->app->getTmpRoot())) $this->view->error = sprintf($this->lang->backup->error->noWritable, $this->app->getTmpRoot());
        }
    }

    /**
     * Index
     *
     * @access public
     * @return void
     */
    public function index(int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('action');

        $backups = array();
        if($this->config->inQuickon)
        {
            $this->loadModel('instance');
            $instance = $this->config->instance->zentaopaas;

            $backupResult = $this->loadModel('system')->getBackupList($instance);
            if($backupResult['result'] == 'success')
            {
                $operating = false;
                $backups   = !empty($backupResult['data']) ? $backupResult['data'] : array();
                foreach($backups as $backup)
                {
                    $backup->time    = isset($backup->create_time) ? $backup->create_time : '';
                    $backup->creator = isset($backup->creator) ? $backup->creator : '';
                    $backup->type    = isset($backup->mode) ? $backup->mode : 'manual';
                    $backup->id      = str_replace('-', '_', $backup->name);
                    if(in_array(strtolower($backup->status), array('pending', 'inprogress', 'processing'))) $operating = true;
                }

                function cmp($left, $right){return $left->create_time < $right->create_time ? 1 : -1;}
                usort($backups, 'cmp');

                if(empty($operating)) $this->system->unsetMaintenance();

                $this->view->operating = $operating;
            }

            $this->app->loadClass('pager', true);
            $pager = pager::init($recTotal, $recPerPage, $pageID);
            $this->view->pager = $pager;
        }
        else
        {
            if(empty($this->view->error)) $backups = $this->backupZen->getBackupList();
        }

        $this->view->title   = $this->lang->backup->common;
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->view->backups = $backups;

        $this->view->users['SYSTEM'] = $this->lang->admin->system;
        $this->view->users['system'] = $this->lang->admin->system;

        if(trim($this->config->visions, ',') == 'lite')
        {
            $version     = $this->config->liteVersion;
            $versionName = $this->lang->liteName . $this->config->liteVersion;
        }
        else
        {
            $version = $this->config->version;
            if($this->config->edition == 'open') $versionName = $this->lang->pmsName . $this->config->version;
            if($this->config->edition != 'open') $versionName = $this->lang->{$this->config->edition . 'Name'} . str_replace($this->config->edition, '', $this->config->version);
        }

        $latestVersionList = array();
        if(isset($this->config->global->latestVersionList)) $latestVersionList = json_decode($this->config->global->latestVersionList, true);
        $latestVersion = $latestVersionList && version_compare(array_reverse(array_keys($latestVersionList))[0], $version, 'gt') ? array_reverse(array_keys($latestVersionList))[0] : $version;

        $this->app->loadLang('install');
        $this->app->loadLang('instance');
        $this->app->loadLang('system');

        $systemInfo = new stdclass();
        $systemInfo->name           = $versionName;
        $systemInfo->status         = $this->lang->instance->statusList['running'];
        $systemInfo->currentVersion = $version;
        $systemInfo->versionHint    = $version;
        $systemInfo->latestVersion  = $latestVersion;
        $systemInfo->upgradeable    = $version != $latestVersion || $this->loadModel('system')->isUpgradeable();
        $systemInfo->upgradeHint    = $systemInfo->upgradeable ? $this->lang->system->backup->versionInfo: null;
        $systemInfo->latestURL      = !empty($latestVersionList[$version]->link) ? $latestVersionList[$version]->link : $this->lang->install->officeDomain;

        if($this->config->inQuickon)
        {
            $latestRelease = $this->loadModel('system')->getLatestRelease();
            $systemInfo->currentVersionTitle = getenv('CHART_VERSION') ?: '';
            $systemInfo->latestVersionTitle  = !empty($latestRelease->version) ? $latestRelease->version : '';
        }

        $this->view->systemInfo = $systemInfo;

        if(!is_writable($this->backupPath))        $this->view->backupError = sprintf($this->lang->backup->error->plainNoWritable, $this->backupPath);
        if(!is_writable($this->app->getTmpRoot())) $this->view->backupError = sprintf($this->lang->backup->error->plainNoWritable, $this->app->getTmpRoot());
        $this->display();
    }

    /**
     * Ajax get disk space.
     *
     * @access public
     * @return void
     */
    public function ajaxGetDiskSpace()
    {
        set_time_limit(0);
        session_write_close();
        $diskSpace = $this->backup->getDiskSpace($this->backupPath);
        $diskSpace = explode(',', $diskSpace);

        $space = new stdclass();
        $space->freeSpace = intval($diskSpace[0]);
        $space->needSpace = intval($diskSpace[1]);

        echo json_encode($space);
    }

    /**
     * Backup.
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

        if($this->config->inQuickon)
        {
            $this->loadModel('instance');
            $instance = $this->config->instance->zentaopaas;

            $result = $this->loadModel('system')->backup($instance, $mode);
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

        $fileName = date('YmdHis') . mt_rand(0, 9) . str_replace('.', '_', $this->config->version);
        $result   = $this->backupZen->backupSQL($fileName, $reload);
        if($result['result'] == 'fail')
        {
            if($reload == 'yes') return print($result['message']);
            printf($result['message']);
        }

        $nofile = str_contains($this->config->backup->setting, 'nofile');
        if(!$nofile)
        {
            $result = $this->backupZen->backupFile($fileName, $reload);
            if($result['result'] == 'fail')
            {
                if($reload == 'yes') return print($result['message']);
                printf($result['message']);
            }

            if(!$this->config->inContainer)
            {
                $result = $this->backupZen->backupCode($fileName, $reload);
                if($result['result'] == 'fail')
                {
                    if($reload == 'yes') return print($result['message']);
                    printf($result['message']);
                }
            }
        }


        /* Delete expired backup. */
        $this->backupZen->removeExpiredFiles();

        if($reload == 'yes') return print($this->lang->backup->success->backup);
        echo $this->lang->backup->success->backup . "\n";
    }

    /**
     * Restore.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function restore(string $fileName)
    {
        set_time_limit(0);

        /* Restore database. */
        $result = $this->backupZen->restoreSQL($fileName);
        if($result['result'] == 'fail') return $this->send($result);

        /* Restore attachments. */
        $result = $this->backupZen->restoreFile($fileName);
        if($result['result'] == 'fail') return $this->send($result);

        if(!empty($_SESSION['gotoUpgrade'])) $this->send(array('result' => 'success', 'message' => $this->lang->backup->notice->gotoUpgrade, 'load' => true));

        return $this->send(array('result' => 'success', 'closeModal' => true, 'callback' => "zui.Modal.alert('{$this->lang->backup->success->restore}').then(() => {loadCurrentPage()})"));
    }

    /**
     * remove PHP header.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function rmPHPHeader(string $fileName)
    {
        if(file_exists($this->backupPath . $fileName . '.sql.php'))
        {
            $this->backup->removeFileHeader($this->backupPath . $fileName . '.sql.php');
            rename($this->backupPath . $fileName . '.sql.php', $this->backupPath . $fileName . '.sql');
        }

        return $this->send(array('result' => 'success', 'load' => true));
    }

    /**
     * Delete.
     *
     * @param  string $fileName
     * @access public
     * @return void
     */
    public function delete(string $fileName)
    {
        foreach(glob($this->backupPath . "{$fileName}*") as $backupFile)
        {
            if(is_dir($backupFile))
            {
                $zfile = $this->app->loadClass('zfile');
                $zfile->removeDir($backupFile);
                $this->backup->processSummary($backupFile, 0, 0, array(), 0, 'delete');
            }
            elseif(!unlink($backupFile))
            {
                return $this->send(array('result' => 'fail', 'message' => sprintf($this->lang->backup->error->noDelete, $backupFile)));
            }
        }

        return $this->sendSuccess(array('load' => true));
    }

    /**
     * Change hold days.
     *
     * @access public
     * @return void
     */
    public function change()
    {
        if($_POST)
        {
            $data = fixer::input('post')->get();
            $this->loadModel('setting')->setItem('system.backup.holdDays', $data->holdDays);
            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->display();
    }

    /**
     * Setting backup
     *
     * @access public
     * @return void
     */
    public function setting()
    {
        /* Check safe file. */
        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $okFile = str_replace($this->app->getBasePath(), '', $statusFile);

            $this->view->error = sprintf($this->lang->noticeOkFile, $okFile, $statusFile);
            return print($this->display());
        }

        /* Get Zentao Info on the quickon platform. */
        $this->loadModel('instance');
        $instance = $this->config->inQuickon ? $this->instance->getByName('ZenTao') : new stdClass();
        if(strtolower($this->server->request_method) == "post")
        {
            $data = fixer::input('post')->join('setting', ',')->get();

            /* 1. Setting holdDays. */
            if(isset($data->holdDays))
            {
                $this->backupZen->setHoldDays($data);
                if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            }

            $setting = '';
            if(isset($data->setting)) $setting = $data->setting;
            $this->loadModel('setting')->setItem('system.backup.setting', $setting);

            /* 2. Setting dir. */
            $settingDir = zget($data, 'settingDir', '');
            if($settingDir)
            {
                $settingDir = rtrim($settingDir, DS) . DS;
                if(!is_dir($settingDir) and mkdir($settingDir, 0777, true)) return $this->send(array('result' => 'fail', 'message' => $this->lang->backup->error->noCreateDir));
                if(!is_writable($settingDir)) return $this->send(array('result' => 'fail', 'message' => strip_tags(sprintf($this->lang->backup->error->noWritable, $settingDir))));
                if($data->settingDir == $this->app->getTmpRoot() . 'backup' . DS) $settingDir = '';
            }
            $this->setting->setItem('system.backup.settingDir', $settingDir);

            /* 3. Setting instance backup settings. */
            $_POST['backupKeepDays'] =  $data->holdDays;
            if(!empty($instance->id)) $this->loadModel('instance')->saveBackupSettings($instance);
            if(dao::isError())  return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            return $this->sendSuccess(array('load' => true, 'closeModal' => true));
        }

        $this->view->instance       = $instance;
        $this->view->backupSettings = !empty($instance->id) ? $this->instance->getBackupSettings($instance->id) : new stdClass();
        $this->display();
    }

    /**
     * Ajax get progress.
     *
     * @access public
     * @return void
     */
    public function ajaxGetProgress()
    {
        session_write_close();

        if($this->config->inQuickon) return print('');

        $files = glob($this->backupPath . '/*.*');
        rsort($files);

        $fileName = basename($files[0]);
        $fileName = substr($fileName, 0, strpos($fileName, '.'));

        $sqlFileName = $this->backupPath . $fileName . '.sql';
        if(!file_exists($sqlFileName)) $sqlFileName .= '.php';
        $sqlFileName = $this->backup->getBackupFile($fileName, 'sql');
        if($sqlFileName)
        {
            $summary = $this->backup->getBackupSummary($sqlFileName);
            $message = sprintf($this->lang->backup->progressSQL, $this->backup->processFileSize($summary['size']));
        }

        $attachFileName = $this->backup->getBackupFile($fileName, 'file');
        if($attachFileName)
        {
            $log = $this->backup->getBackupDirProgress($attachFileName);
            if(empty($log)) return print('');
            $message = sprintf($this->lang->backup->progressAttach, zget($log, 'allCount', 0), zget($log, 'count', 0));
            if(empty($log)) $message = '';
        }

        $codeFileName = $this->backup->getBackupFile($fileName, 'code');
        if($codeFileName)
        {
            $log = $this->backup->getBackupDirProgress($codeFileName);
            if(empty($log)) return print('');
            $message = sprintf($this->lang->backup->progressCode, zget($log, 'allCount', 0), zget($log, 'count', 0));
            if(empty($log)) $message = '';
        }

        return print($message);
    }

    /**
     * AJAX: Check the version of the backup.
     *
     * @param  string $name
     * @return void
     */
    public function ajaxCheckBackupVersion($name)
    {
        if(!$this->config->inContainer) $this->send(array('result' => 'success', 'message' => $this->lang->backup->confirmRestore, 'canRestore' => true));

        $matched = preg_match('/\d{15}(.*)$/', $name, $matches);
        if ($matched == 1 && !empty($matches[1]))
        {
            $backupVersion = str_replace('_', '.', explode('.', $matches[1])[0]);
            $compareResult = version_compare($backupVersion, $this->config->version);
            switch($compareResult)
            {
                case -1:
                    $_SESSION['gotoUpgrade'] = common::getSysURL() . '/upgrade.php';
                    $message = $this->lang->backup->notice->lowerVersion;
                    break;
                case  1:
                    $message = sprintf($this->lang->backup->notice->higherVersion, $this->app->getVersionName($backupVersion));
                    break;
                default:
                    $message = $this->lang->backup->confirmRestore;
            }

            $canRestore = $compareResult == 1 ? false : true;
            $this->send(array('result' => 'success', 'message' => $message, 'canRestore' => $canRestore));
        }
        else
            $this->send(array('result' => 'fail', 'message' => $this->lang->backup->notice->unknownVersion));
    }
}
