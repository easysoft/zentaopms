<?php
/**
 * The control file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: control.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class upgrade extends control
{
    /**
     * The index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        /* 如果没有升级入口文件，跳转到应用的首页。*/
        /* Locate to index page of my module, if upgrade.php does not exist. */
        $upgradeFile = $this->app->wwwRoot . 'upgrade.php';
        if(!file_exists($upgradeFile)) $this->locate($this->createLink('my', 'index'));

        if(version_compare($this->config->installedVersion, '6.4', '<=')) $this->locate(inlink('license'));
        $this->locate(inlink('backup'));
    }

    public function importBIData()
    {
        $this->loadModel('install')->importBIData();
    }

    /**
     * 授权协议页面。
     * Check agree license.
     *
     * @access public
     * @return void
     */
    public function license()
    {
        if($this->get->agree == true) $this->locate(inlink('backup'));

        $clientLang = $this->app->getClientLang();
        $licenseCN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.CN');
        $licenseEN  = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.EN');

        $license = $licenseEN . $licenseCN;
        if($clientLang == 'zh-cn' || $clientLang == 'zh-tw') $license = $licenseCN . $licenseEN;

        $this->view->title   = $this->lang->upgrade->common;
        $this->view->license = $license;
        $this->display();
    }

    /**
     * 提示备份数据库。
     * Prompt to backup database.
     *
     * @access public
     * @return void
     */
    public function backup()
    {
        $this->session->set('upgrading', true);

        $this->view->title = $this->lang->upgrade->common;
        $this->display();
    }

    /**
     * 选择升级前的禅道版本。
     * Select the version of old zentao.
     *
     * @access public
     * @return void
     */
    public function selectVersion()
    {
        $version = str_replace(array(' ', '.'), array('', '_'), $this->config->installedVersion);
        $version = strtolower($version);

        /* 处理迅捷版的版本。*/
        /* Process the lite version. */
        if($this->config->visions == ',lite,')
        {
            $installedVersion = str_replace('.', '_', $this->config->installedVersion);
            $version = array_search($installedVersion, $this->config->upgrade->liteVersion);

            foreach($this->lang->upgrade->fromVersions as $key => $value)
            {
                if(strpos($key, 'lite') === false) unset($this->lang->upgrade->fromVersions[$key]);
            }

            $this->config->version = ($this->config->edition == 'biz' ? 'LiteVIP' : 'Lite') . $this->config->liteVersion;
        }

        if($_POST) $this->locate(inlink('confirm', "fromVersion={$this->post->fromVersion}"));

        $this->view->title   = $this->lang->upgrade->common . $this->lang->hyphen . $this->lang->upgrade->selectVersion;
        $this->view->version = $version;
        $this->display();
    }

    /**
     * 确认要执行的SQL语句。
     * Confirm the upgrade sql.
     *
     * @param  string  $fromVersion
     * @access public
     * @return void
     */
    public function confirm(string $fromVersion = '')
    {
        if(file_exists($this->app->getTmpRoot() . 'upgradeSqlLines')) @unlink($this->app->getTmpRoot() . 'upgradeSqlLines');

        $this->view->fromVersion = $fromVersion;

        if(strpos($fromVersion, 'lite') !== false) $fromVersion = $this->config->upgrade->liteVersion[$fromVersion];

        if($_POST) $this->locate(inlink('execute', "fromVersion={$fromVersion}"));

        $confirmSql = $this->upgrade->getConfirm($fromVersion);

        /* When sql is empty then skip it. */
        if(empty($confirmSql)) $this->locate(inlink('execute', "fromVersion={$fromVersion}"));

        $this->session->set('step', '');
        $this->view->title    = $this->lang->upgrade->confirm;
        $this->view->confirm  = $confirmSql;
        $this->view->writable = is_writable($this->app->getTmpRoot()) ? true : false;

        $this->display();
    }

    /**
     * 执行升级的 SQL。
     * Execute the upgrading sql.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function execute(string $fromVersion = '')
    {
        session_write_close();
        $this->session->set('step', '');

        $this->view->title       = $this->lang->upgrade->result;
        $this->view->fromVersion = $fromVersion;

        /* 手动删除无法自动删除的文件。*/
        /* Remove files that can not be deleted automatically. */
        $result = $this->upgrade->deleteFiles();
        if($result)
        {
            $this->view->result = 'fail';
            $this->view->errors = $result;

            return $this->display();
        }

        /* 检查 Duckdb 引擎是否正确安装。*/
        /* Check Duckdb is installed. */
        $checkDuckdb = $this->loadModel('bi')->checkDuckdbInstall();
        if($checkDuckdb['fail'])
        {
            $this->app->loadLang('install');
            $this->view->result    = 'duckdbFail';
            $this->view->duckdb    = 'loading';
            $this->view->ext_dm    = 'loading';
            $this->view->ext_mysql = 'loading';

            return $this->display();
        }


        $rawFromVersion = isset($_POST['fromVersion']) ? $this->post->fromVersion : $fromVersion;
        if(strpos($fromVersion, 'lite') !== false) $rawFromVersion = $this->config->upgrade->liteVersion[$fromVersion];

        $installedVersion = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=version');

        if($this->config->version != $installedVersion) $this->upgrade->execute($rawFromVersion);

        if($this->upgrade->isError())
        {
            $this->view->result = 'sqlFail';
            $this->view->errors = $this->upgrade->getError();
            $this->display();
        }

        $this->upgradeZen->afterExecuteSql($fromVersion, $rawFromVersion);
    }

    /**
     * 引导升级到 18 版本。
     * Guide to 18 version.
     *
     * @param  string $fromVersion
     * @param  string $mode
     * @access public
     * @return void
     */
    public function to18Guide(string $fromVersion, string $mode = '')
    {
        if($_POST || $mode)
        {
            if($this->post->mode) $mode = $this->post->mode;

            if($this->config->edition == 'ipd') $mode = 'PLM';
            $this->loadModel('setting')->setItem('system.common.global.mode', $mode);
            $this->loadModel('custom')->disableFeaturesByMode($mode);

            /* 更新迭代的概念。*/
            /* Update sprint concept. */
            $this->upgradeZen->setSprintConcept();

            if($mode == 'light') $this->upgradeZen->setDefaultProgram();

            $this->locate(inlink('selectMergeMode', "fromVersion={$fromVersion}&mode={$mode}"));
        }

        $this->app->loadLang('install');

        list($disabledFeatures, $enabledScrumFeatures, $disabledScrumFeatures) = $this->loadModel('custom')->computeFeatures();

        $this->view->title                 = $this->lang->custom->selectUsage;
        $this->view->edition               = $this->config->edition;
        $this->view->disabledFeatures      = $disabledFeatures;
        $this->view->enabledScrumFeatures  = $enabledScrumFeatures;
        $this->view->disabledScrumFeatures = $disabledScrumFeatures;
        $this->display();
    }

    /**
     * 归并项目集。
     * Merge program.
     *
     * @param  string $type
     * @param  int    $programID
     * @param  string $projectType project|execution
     * @access public
     * @return void
     */
    public function mergeProgram(string $type = 'productline', int $programID = 0, string $projectType = 'project')
    {
        set_time_limit(0);
        $this->app->loadLang('program');
        $this->app->loadLang('project');
        $this->session->set('upgrading', true);

        if($_POST)
        {
            $projectType = isset($_POST['projectType']) ? $_POST['projectType'] : 'project';
            if($type == 'productline') $this->upgradeZen->mergeByProductline($projectType);
            if($type == 'product')     $this->upgradeZen->mergeByProduct($projectType);
            if($type == 'sprint')      $this->upgradeZen->mergeBySprint($projectType);
            if($type == 'moreLink')    $this->upgradeZen->mergeByMoreLink($projectType);

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => $this->createLink('upgrade', 'mergeProgram', "type={$type}&programID={$programID}&projectType={$projectType}")));
        }

        $noMergedProductCount = $this->upgrade->getNoMergedProductCount();
        $noMergedSprintCount  = $this->upgrade->getNoMergedSprintCount();

        /* 当产品和项目都归并完成后，完成后续操作。*/
        /* When all products and projects merged then finish and locate afterExec page. */
        if(empty($noMergedProductCount) && empty($noMergedSprintCount)) $this->upgradeZen->upgradeAfterMerged();

        $this->view->noMergedProductCount = $noMergedProductCount;
        $this->view->noMergedSprintCount  = $noMergedSprintCount;

        /* 获取产品线下的产品和项目。*/
        /* Get products and projects group by product line. */
        if($type == 'productline') $this->upgradeZen->assignProductsAndProjectsGroupByProductline($projectType);

        /* 获取产品下的项目。*/
        /* Get projects group by product. */
        if($type == 'product') $this->upgradeZen->assignProjectsGroupByProduct($projectType);

        $systemMode = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=mode');

        /* Get no merged projects that is not linked product. */
        if($type == 'sprint')
        {
            $this->upgradeZen->assignSprintsWithoutProduct();
            if(!$programID && $systemMode == 'light') $programID = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=defaultProgram');
        }

        /* Get no merged projects that link more than two products. */
        if($type == 'moreLink') $this->upgradeZen->assignSprintsWithMoreProducts();

        $programs = $this->dao->select('id, name')->from(TABLE_PROGRAM)->where('type')->eq('program')->andWhere('deleted')->eq('0')->orderBy('id_desc')->fetchPairs();
        $currentProgramID = $programID ? $programID : key($programs);

        $this->view->title       = $this->lang->upgrade->mergeProgram;
        $this->view->type        = $type;
        $this->view->programs    = $programs;
        $this->view->programID   = $programID;
        $this->view->projects    = $currentProgramID ? $this->upgrade->getProjectPairsByProgram($currentProgramID) : array();
        $this->view->lines       = $currentProgramID ? $this->loadModel('product')->getLinePairs($currentProgramID) : array();
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed|noempty');
        $this->view->groups      = $this->loadModel('group')->getPairs();
        $this->view->systemMode  = $systemMode;
        $this->view->projectType = $projectType;
        $this->display();
    }

    /**
     * 选择数据归并的方式。
     * Select the merge mode when upgrading to zentaopms 18.0.
     *
     * @param  string  $fromVersion
     * @param  string  $mode        light | ALM | PLM
     * @access public
     * @return void
     */
    public function selectMergeMode(string $fromVersion, string $mode = 'light')
    {
        if($_POST)
        {
            $mergeMode = $this->post->projectType;
            if($mergeMode == 'manually') $this->locate(inlink('mergeProgram'));

            if($mode == 'light') $programID = $this->loadModel('setting')->getItem('owner=system&module=common&section=global&key=defaultProgram');
            if($mode == 'ALM' || $mode == 'PLM') $programID = $this->loadModel('program')->createDefaultProgram();

            if($mergeMode == 'project')   $this->upgrade->upgradeInProjectMode($programID);
            if($mergeMode == 'execution') $this->upgrade->upgradeInExecutionMode($programID);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->upgrade->computeObjectMembers();
            $this->upgrade->initUserView();
            $this->upgrade->setDefaultPriv();
            $this->dao->update(TABLE_CONFIG)->set('value')->eq('0_0')->where('`key`')->eq('productProject')->exec();

            $hourPoint = $this->loadModel('setting')->getItem('owner=system&module=custom&key=hourPoint');
            if(empty($hourPoint)) $this->setting->setItem('system.custom.hourPoint', 0);

            $sprints = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll('id');
            $this->dao->update(TABLE_ACTION)->set('objectType')->eq('execution')->where('objectID')->in(array_keys($sprints))->andWhere('objectType')->eq('project')->exec();

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->locate(inlink('afterExec', "fromVersion={$fromVersion}"));
        }
        $this->view->title       = $this->lang->upgrade->selectMergeMode;
        $this->view->fromVersion = $fromVersion;
        $this->view->systemMode  = $mode;
        $this->display();
    }

    /**
     * 同一个项目集内项目名称不能重复，调整重名的项目名称。
     * Rename the projects that have the same name in the same program.
     *
     * @param  string $type          project|product|execution
     * @param  string $duplicateList
     * @access public
     * @return void
     */
    public function renameObject(string $type = 'project', string $duplicateList = '')
    {
        $this->app->loadLang($type);
        if($_POST)
        {
            foreach($this->post->project as $projectID => $projectName)
            {
                if(!$projectName) continue;
                $this->dao->update(TABLE_PROJECT)->set('name')->eq($projectName)->where('id')->eq($projectID)->exec();
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true));
        }

        $objectGroup = array();
        if($type == 'project' || $type == 'execution') $objectGroup = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($duplicateList)->orderBy('name')->fetchAll();

        $this->view->type        = $type;
        $this->view->objectGroup = $objectGroup;
        $this->display();
    }

    /**
     * 合并代码库。
     * Merge Repos.
     *
     * @access public
     * @return void
     */
    public function mergeRepo()
    {
        if($_POST)
        {
            $postData = form::data($this->config->upgrade->form->mergetRepo)->get();
            $this->upgrade->mergeRepo(array_keys($postData->repoes), $postData->products);
            return $this->send(array('result' => 'success', 'load' => inlink('mergeRepo')));
        }

        $repoes   = $this->dao->select('id, name')->from(TABLE_REPO)->where('deleted')->eq(0)->andWhere('product')->eq('')->fetchPairs();
        $products = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs();
        if(empty($repoes) || empty($products))
        {
            $this->dao->delete()->from(TABLE_BLOCK)->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('`key`')->eq('blockInited')->exec();
            $this->loadModel('setting')->deleteItems('owner=system&module=common&section=global&key=upgradeStep');
            $this->locate(inlink('afterExec', 'fromVersion=&processed=no'));
        }

        $this->view->title    = $this->lang->upgrade->mergeRepo;
        $this->view->repoes   = $repoes;
        $this->view->products = $products;
        $this->view->programs = $this->dao->select('id, name')->from(TABLE_PROGRAM)->where('deleted')->eq('0')->andWhere('type')->eq('program')->fetchPairs();

        $this->display();
    }

    /**
     * 获取执行sql的进度。
     * Ajax get progress.
     *
     * @param  int    $offset
     * @access public
     * @return 1
     */
    public function ajaxGetProgress(int $offset = 0)
    {
        $tmpProgressFile = $this->app->getTmpRoot() . 'upgradeSqlLines';
        $upgradeLogFile  = $this->upgrade->getLogFile();

        /* 计算执行的进度。*/
        /* Compute progress for executiong sql. */
        $progress = 1;
        if(file_exists($tmpProgressFile) && $offset != 0)
        {
            $sqlLines = file_get_contents($tmpProgressFile);
            if(empty($sqlLines)) $progress = $this->session->upgradeProgress ? $this->session->upgradeProgress : 1;
            if($sqlLines == 'completed') $progress = 100;

            $sqlLines = explode('-', $sqlLines);
            $progress = round((int)$sqlLines[1] / (int)$sqlLines[0] * 100);
            if($progress > 95) $progress = 100;

            /* Fix progress 1 to 99. */
            $progress = empty($progress) ? 1 : $progress;
            if($progress >= 100) $progress = 99;

            $this->session->set('upgradeProgress', $progress);
        }

        /* 显示执行 sql 的日志。*/
        /* Display the log of execution sql. */
        $log  = !file_exists($upgradeLogFile) ? '' : file_get_contents($upgradeLogFile, false, null, $offset);
        $size = 10 * 1024;
        if(!empty($log) && mb_strlen($log) > $size)
        {
            $left     = mb_substr($log, $size);
            $log      = mb_substr($log, 0, $size);
            $position = strpos($left, "\n");
            if($position !== false) $log .= substr($left, 0, $position + 1);
        }
        $log = trim($log);
        return print(json_encode(array('log' => str_replace("\n", "<br />", htmlspecialchars($log)) . ($log ? '<br />' : ''), 'progress' => $progress, 'offset' => $offset + strlen($log))));
    }

    /**
     * 获取修复冲突的记录。
     * Ajax get fix consistency logs.
     *
     * @param  int    $offset
     * @access public
     * @return void
     */
    public function ajaxGetFixLogs(int $offset = 0)
    {
        $logFile  = $this->upgrade->getConsistencyLogFile();
        $lines    = !file_exists($logFile) ? array() : file($logFile);
        $total    = (int)array_shift($lines);

        $progress = 0;
        if($total) $progress = round((count($lines) / $total) * 100);
        if($progress >= 100) $progress = 99;

        $log      = array_slice($lines, $offset);
        $finished = ($log && end($log) == 'Finished') ? true : false;
        if($finished) $progress = 100;

        return print(json_encode(array('log' => implode("<br />", $log) . (empty($log) ? '' : '<br />'), 'finished' => $finished, 'progress' => $progress, 'offset' => count($lines))));
    }

    /**
     * 为保持数据库一致，执行修复sql。
     * Ajax fix for consistency.
     *
     * @param  string $version
     * @access public
     * @return void
     */
    public function ajaxFixConsistency(string $version)
    {
        set_time_limit(0);
        session_write_close();

        $this->upgrade->fixConsistency($version);
    }

    /**
     * 获取某个项目集下的项目。
     * Get the project of the program it belongs to.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetProjectPairsByProgram(int $programID = 0)
    {
        $projects = $this->upgrade->getProjectPairsByProgram($programID);

        $result = array();
        foreach($projects as $projectID => $projectName) $result[] = array('text' => $projectName, 'value' => $projectID);

        return $this->send(array('result' => 'success', 'projects' => $result));
    }

    /**
     * 获取项目集下的产品线。
     * Get the lines of the program it belongs to.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetLinesPairsByProgram(int $programID = 0)
    {
        $lines = $this->loadModel('product')->getLinePairs((int)$programID);

        $result = array();
        foreach($lines as $lineID => $lineName) $result[] = array('text' => $lineName, 'value' => $lineID);

        return $this->send(array('result' => 'success', 'lines' => $result));
    }

    /**
     * After execute.
     *
     * @param  string $fromVersion
     * @param  string $processed
     * @param  string $skipMoveFile
     * @access public
     * @return void
     */
    public function afterExec($fromVersion, $processed = 'no', $skipMoveFile = 'no')
    {
        /* 如果数据库有冲突，显示更改的 sql。*/
        /* If there is a conflict with the standard database, display the changed sql. */
        $alterSQL = $this->upgrade->checkConsistency($this->config->version);
        if(!empty($alterSQL)) return $this->displayConsistency($alterSQL);

        /**
         * 升级完成后清除缓存。
         * 通过 dao 的 exec 方法更新数据库会自动更新缓存。
         * 通过 dbh 执行 sql 语句的方式更新数据库不会自动更新缓存，应该在清除缓存之前执行，否则可能导致缓存命中但数据已过期。
         * Clear the cache after the upgrade is complete.
         * Update the database through the exec method of dao will automatically update the cache.
         * Update the database by executing sql statements through dbh will not automatically update the cache, should be executed before clearing the cache, otherwise it may cause cache hits but the data has expired.
         */
        $this->dao->clearCache();

        /* 如果有扩展文件并且需要移除文件，显示需要移除的文件。*/
        /* If there are extendtion files and need to move them, display them. */
        $extFiles = $this->upgrade->getExtFiles();
        if(!empty($extFiles) && $skipMoveFile == 'no') return $this->locate(inlink('moveExtFiles', "fromVersion={$fromVersion}"));

        /* 移除收费版本目录，如果有错误，显示移除命令。*/
        /* Remove encrypted directories. */
        $response = $this->upgrade->removeEncryptedDir();
        if($response['result'] == 'fail') return $this->displayExecuteError($response['command']);

        unset($_SESSION['user']);

        /* 检查是否还有需要处理的。*/
        /* Check if there is anything else that needs to be processed. */
        $needProcess = $this->upgrade->checkProcess();
        if($processed == 'no') return $this->displayExecuteProcess($fromVersion, $needProcess);

        if(empty($needProcess) || $processed == 'yes') $this->processAfterExecSuccessfully();
    }

    /**
     * 数据库一致性检查。
     * Check database consistency.
     *
     * @param  bool   $netConnect
     * @access public
     * @return void
     */
    public function consistency(bool $netConnect = true)
    {
        $logFile  = $this->upgrade->getConsistencyLogFile();
        $hasError = $this->upgrade->hasConsistencyError();
        if(file_exists($logFile)) unlink($logFile);

        $alterSQL = $this->upgrade->checkConsistency();
        if(empty($alterSQL))
        {
            /* 能访问禅道官网插件接口跳转到检查插件页面，否则跳转到选择版本页面。*/
            /* If you can access the ZenTao official website extension interface, locate to the check extension page, otherwise locate to the version selection page. */
            if(!$netConnect) $this->locate(inlink('selectVersion'));
            $this->locate(inlink('checkExtension'));
        }

        $this->view->title    = $this->lang->upgrade->consistency;
        $this->view->hasError = $hasError;
        $this->view->alterSQL = $alterSQL;
        $this->view->version  = $this->config->installedVersion;
        $this->display();
    }

    /**
     * 检查扩展。
     * Check extension.
     *
     * @access public
     * @return void
     */
    public function checkExtension()
    {
        /* 如果没有已安装的扩展，跳转到选择版本页面。*/
        /* If there is no installed extensions, locate to the version selection page. */
        $extensions = $this->loadModel('extension')->getLocalExtensions('installed');
        if(empty($extensions)) $this->locate(inlink('selectVersion'));

        $versions = array();
        foreach($extensions as $code => $extension) $versions[$code] = $extension->version;

        /* 如果没有不兼容的扩展，跳转到选择版本页面。*/
        /* If there is no incompatible extensions, locate to the version selection page. */
        $incompatibleExts = $this->extension->checkIncompatible($versions);
        if(empty($incompatibleExts)) $this->locate(inlink('selectVersion'));

        $removeCommands = array();
        $extensionsName = array();
        foreach($incompatibleExts as $extension)
        {
            $this->extension->updateExtension(array('code' => $extension, 'status' => 'deactivated'));
            $removeCommands[$extension] = $this->extension->removePackage($extension);
            $extensionsName[$extension] = $extensions[$extension]->name;
        }

        $this->view->title          = $this->lang->upgrade->checkExtension;
        $this->view->extensionsName = $extensionsName;
        $this->view->removeCommands = $removeCommands;
        $this->display();
    }

    /**
     * 更新文件。
     * Ajax update file.
     *
     * @param  string $type
     * @param  int    $lastID
     * @access public
     * @return void
     */
    public function ajaxUpdateFile(string $type = '', int $lastID = 0)
    {
        set_time_limit(0);

        $this->app->loadLang('search');

        $result = $this->upgrade->updateFileObjectID($type, $lastID);

        $response = array();
        $response['type']  = $type;
        $response['count'] = $result['count'];

        if($result['type'] == 'finish')
        {
            $response['result']  = 'finished';
            $response['message'] = $this->lang->search->buildSuccessfully;
        }
        else
        {
            $response['result']   = 'continue';
            $response['next']     = inlink('ajaxUpdateFile', "type={$result['type']}&lastID={$result['lastID']}");
            $response['nextType'] = $result['type'];
            $response['message']  = zget($this->lang->searchObjects, $result['type']) . " <span class='{$result['type']}-num'>0</span>";
        }
        echo json_encode($response);
    }

    /**
     * 获取项目集的状态。
     * Ajax get program status.
     *
     * @param  int    $programID
     * @access public
     * @return void
     */
    public function ajaxGetProgramStatus(int $programID)
    {
        echo $this->dao->select('status')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch('status');
    }

    /**
     * 迁移扩展文件。
     * Move Extent files.
     *
     * @param  string $fromVersion
     * @access public
     * @return void
     */
    public function moveExtFiles(string $fromVersion)
    {
        $command = '';
        $result  = 'success';
        if(strtolower($this->server->request_method) == 'post')
        {
            if(!empty($_POST['files']))
            {
                $response = $this->upgrade->moveExtFiles();
                $result   = $response['result'];
                if($result == 'fail') $command = $response['command'];
            }

            if($result == 'success') return $this->locate(inlink('afterExec', "fromVersion={$fromVersion}&processed=no&skipMoveFile=yes"));
        }

        $this->view->title       = $this->lang->upgrade->common;
        $this->view->files       = $this->upgrade->getExtFiles();
        $this->view->result      = $result;
        $this->view->command     = $command;
        $this->view->fromVersion = $fromVersion;

        $this->display();
    }

    /**
     * 处理历史指标。
     * Process old metrics in order to easy of test.
     *
     * @param  bool   $isDelete
     * @access public
     * @return void
     */
    public function processOldMetrics(bool $isDelete = false)
    {
        if($isDelete)
        {
            $this->upgrade->deleteMetrics();
        }
        else
        {
            $this->upgrade->processOldMetrics();
        }

        if(dao::isError()) echo 'fail';

        echo 'ok';
    }

    /**
     * 处理历史指标数据。
     * Process history metric data.
     *
     * @access public
     * @return void
     */
    public function processHistoryDataForMetric()
    {
        $this->upgrade->processHistoryDataForMetric();
        if(dao::isError()) echo 'fail';
        echo 'ok';
    }

    /**
     * 升级BI内置数据。
     * Upgrade BI built-in data.
     *
     * @access public
     * @return void
     */
    public function ajaxUpgradeBIData()
    {
        $this->upgrade->upgradeBIData();
        echo 'ok';
    }

    /**
     * 安装DuckDB引擎。
     * AJAX: Install duckdb.
     *
     * @access public
     * @return void
     */
    public function ajaxInstallDuckdb()
    {
        $this->loadModel('bi');
        ignore_user_abort(true);
        set_time_limit(0);
        session_write_close();
        $this->bi->downloadDuckdb();
        echo 'success';
    }

    /**
     * 检查duckdb文件是否下载完成。
     * AJAX: Check duckdb.
     *
     * @access public
     * @return void
     */
    public function ajaxCheckDuckdb()
    {
        $check = $this->loadModel('bi')->checkDuckdbInstall();
        echo(json_encode($check));
    }
}
