<?php
declare(strict_types=1);
/**
 * The model file of solution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @link        https://www.zentao.net
 */
class solutionModel extends model
{
    /**
     * 根据ID获取解决方案。
     * Get solution by id.
     *
     * @param  int    $solutionID
     * @access public
     * @return object
     */
    public function getByID(int $solutionID): ?object
    {
        $solution = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->eq($solutionID)->fetch();
        if(!$solution) return null;

        $solution->instances = array();
        $instanceIDList = $this->dao->select('id')->from(TABLE_INSTANCE)->where('solution')->eq($solutionID)->fetchAll('id');
        if($instanceIDList) $solution->instances = $this->loadModel('instance')->getByIDList(array_keys($instanceIDList));

        return $solution;
    }

    /**
     * 根据应用市场的解决方案创建本地解决方案。
     * Create by solution of cloud market.
     *
     * @param  object $cloudSolution
     * @param  object $components
     * @access public
     * @return object|null
     */
    public function create(object $cloudSolution, object $components): object|null
    {
        $postedCharts = $this->session->solutionCharts ? $this->session->solutionCharts : fixer::input('post')->get();

        /* Sort selected apps. */
        $orderedCategories = $components->order;
        $selectedApps = array();
        foreach($orderedCategories as $category)
        {
            $chart = zget($postedCharts, $category, '');
            if(empty($chart)) continue;

            $selectedApps[$category] = $this->pickAppFromSchema($components, $category, $chart, $cloudSolution);
            if(empty($selectedApps[$category])) unset($selectedApps[$category]);
        }
        if(empty($selectedApps)) return null;

        if(!isset($this->app->user->account))
        {
            $this->app->user = new stdclass();
            $this->app->user->account = $this->dao->select('*')->from(TABLE_USER)->where('deleted')->eq(0)->fetch('account');
        }

        /* Create solution. */
        $solution = new stdclass;
        $solution->name         = $cloudSolution->title;
        $solution->appID        = $cloudSolution->id;
        $solution->appName      = $cloudSolution->name;
        $solution->appVersion   = $cloudSolution->app_version;
        $solution->version      = $cloudSolution->version;
        $solution->chart        = $cloudSolution->chart;
        $solution->cover        = $cloudSolution->background_url;
        $solution->introduction = $cloudSolution->introduction;
        $solution->desc         = $cloudSolution->description;
        $solution->status       = 'waiting';
        $solution->source       = 'cloud';
        $solution->components   = json_encode($selectedApps);
        $solution->createdBy    = $this->app->user->account;
        $solution->createdAt    = helper::now();
        $solution->channel      = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;

        $this->dao->insert(TABLE_SOLUTION)->data($solution)->exec();
        if(dao::isError()) return null;

        return $this->getByID($this->dao->lastInsertID());
    }

    /**
     * 按类别和应用从配置信息中获取应用信息。
     * Pick App from schema info by category and app.
     *
     * @param  object $schema
     * @param  string $category
     * @param  string $app
     * @param  object $cloudSolution
     * @access public
     * @return object|null
     */
    public function pickAppFromSchema(object $schema, string $category, string $app, object $cloudSolution): object|null
    {
        $categoryList = helper::arrayColumn($schema->category, null, 'name');
        $appGroup     = zget($categoryList, $category, array());

        foreach($appGroup->choices as $appInSchema)
        {
            if($appInSchema->name != $app) continue;

            $appInfo = zget($cloudSolution->apps, $app);
            $appInfo->version     = $appInSchema->version;
            $appInfo->app_version = $appInSchema->app_version;
            $appInfo->status      = 'waiting';

            return $appInfo;
        }
        return null;
    }

    /**
     * 如果实例未安装，则安装实例。
     * Install instance if instance not installed.
     *
     * @param  object  $solution
     * @param  object  $componentApp
     * @param  object  $solutionSchema
     * @param  array   $allMappings
     * @access private
     * @return bool
     */
    private function installInstance(object $solution, object $componentApp, object $solutionSchema, array $allMappings): bool
    {
        static $apps, $components, $channel;
        if(empty($apps))       $apps       = helper::arrayColumn(json_decode($solution->components, true), 'chart');
        if(empty($channel))    $channel    = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        if(empty($components)) $components = json_decode($solution->components);

        $cloudApp = $this->loadModel('store')->getAppInfo($componentApp->id, false, '', $componentApp->version, $channel);
        if(!$cloudApp)
        {
            $this->saveStatus($solution->id, 'notFoundApp');
            dao::$errors[] = sprintf($this->lang->solution->errors->notFoundAppByVersion, $componentApp->version, $componentApp->alias);
            return false;
        }
        /* Must install the defineded version in solution schema. */
        $cloudApp->version     = $componentApp->version;
        $cloudApp->app_version = $componentApp->app_version;

        /* Check enough memory to install app, or not.*/
        if(!$this->loadModel('instance')->enoughMemory($cloudApp))
        {
            $this->saveStatus($solution->id, 'notEnoughResource');
            dao::$errors[] = $this->lang->solution->errors->notEnoughResource;
            return false;
        }

        if($solution->status != 'installing') return false;
        $settings = $this->mountSettings($solutionSchema, $componentApp->chart, $components, $allMappings, in_array('sonarqube', $apps));
        $instance = $this->installApp($cloudApp, $settings);

        if(!$instance)
        {
            $this->saveStatus($solution->id, 'cneError');
            dao::$errors[] = sprintf($this->lang->solution->errors->failToInstallApp, $cloudApp->name);
            return false;
        }
        $this->dao->update(TABLE_INSTANCE)->set('solution')->eq($solution->id)->where('id')->eq($instance->id)->exec();

        $componentApp->status = 'installing';
        $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();

        return !dao::isError();
    }

    /**
     * 检查启动结果。
     * Check start result.
     *
     * @param  object  $instance
     * @param  int     $solutionID
     * @param  object  $solutionSchema
     * @param  string  $category
     * @access private
     * @return bool
     */
    private function checkSarted(object $instance, int $solutionID, object $solutionSchema, string $category): bool
    {
        $instance = $this->waitInstanceStart($instance, $solutionID);
        if($instance)
        {
            $mappingKeys = zget($solutionSchema->mappings, $instance->chart, '');
            if($mappingKeys)
            {
                /* Load settings mapping of installed app for next app. */
                $tempMappings = $this->cne->getSettingsMapping($instance, $mappingKeys);
                if($tempMappings) $allMappings[$category] = $tempMappings;
            }

            $this->instance->saveAuthInfo($instance);
            return true;
        }

        $this->saveStatus($solutionID, 'timeout');
        dao::$errors[] = $this->lang->solution->errors->timeout;
        return false;
    }

    /**
     * 安装解决方案。
     * Install solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return bool
     */
    public function install(int $solutionID): bool
    {
        set_time_limit(0);
        session_write_close();

        $solution = $this->getByID($solutionID);
        if(!$solution)
        {
            dao::$errors[] = $this->lang->solution->errors->notFound;
            return false;
        }

        if(in_array($solution->status, array('installing', 'installed', 'uninstalled'))) return false;
        $this->saveStatus($solutionID, 'installing');

        $this->loadModel('cne');
        $this->loadModel('instance');
        $this->loadModel('common');
        $allMappings    = array();
        $solutionSchema = $this->loadModel('store')->solutionConfig('id', $solution->appID);
        $components     = json_decode($solution->components);
        foreach($components as $category => $componentApp)
        {
            $solutionStatus = $this->dao->select('status')->from(TABLE_SOLUTION)->where('id')->eq($solutionID)->fetch();
            if($solutionStatus->status !='installing')
            {
                /* If status is not installing, should abort installation.  Becaust installation was canceled or error happened. */
                dao::$errors[] = $this->lang->solution->errors->hasInstallationError;
                return false;
            }

            $instance = $this->instance->instanceOfSolution($solution, $componentApp->chart);
            if(!$instance && !$this->installInstance($solution, $componentApp, $solutionSchema, $allMappings)) return false;

            /* Wait instanlled app started. */
            if(!$this->checkSarted($instance, $solutionID, $solutionSchema, $category)) return false;

            $componentApp->status = 'installed';
            $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
        }

        $this->saveStatus($solutionID, 'installed');
        return true;
    }

    /**
     * 更新解决方案的状态。
     * Update status of solution.
     *
     * @param  int    $solutionID
     * @param  string $status      waiting|uninstalling|cneError|timeout|notFoundApp|notEnoughResource
     * @access public
     * @return bool
     */
    public function saveStatus(int $solutionID, string $status): bool
    {
        if(!isset($this->lang->solution->installationErrors[$status])) return false;

        $this->dao->update(TABLE_SOLUTION)
            ->set('status')->eq($status)
            ->set('updatedDate')->eq(helper::now())
            ->where('id')->eq($solutionID)
            ->exec();
        return !dao::isError();
    }

    /**
     * 获取安装应用的配置信息。
     * Mount settings for installing app.
     *
     * @param  object  $solutionSchema
     * @param  string  $chart
     * @param  object  $components
     * @param  array   $mappings  example: ['git' => ['env.GIT_USERNAME' => 'admin', ...], ...]
     * @param  boolean $isInstallSonar
     * @access private
     * @return array
     */
    private function mountSettings(object $solutionSchema, string $chart, object $components, array $mappings, bool $isInstallSonar = true): array
    {
        $settings = array();

        $appSettings = zget($solutionSchema->settings, $chart, array());
        $apps        = helper::arrayColumn((array)$components, 'chart');
        foreach($appSettings as $item)
        {
            if(!empty($item->when) && !in_array($item->when, $apps)) continue;
            switch($item->type)
            {
                case 'static':
                    if(!$isInstallSonar && $item->key === 'solution.sonarqube.enabled')
                    break;
                    $settings[] = array('key' => $item->key, 'value' => $item->value);
                    break;
                case 'choose':
                    $appInfo = zget($components, $item->target, '');
                    if($appInfo) $settings[] = array('key' => $item->key, 'value' => $appInfo->chart);
                    break;
                case 'mappings':
                    $mappingInfo = zget($mappings, $item->target, '');
                    if($mappingInfo) $settings[] = array('key' => $item->key, 'value' => zget($mappingInfo, $item->key, ''));
                    break;
                case 'auto':
                    if($item->value === 'protocol') $settings[] = array('key' => $item->key, 'value' => strstr(getWebRoot(true), ':', true));
                    break;
            }
        }

        return $settings;
    }

    /**
     * 安装应用。
     * Install app.
     *
     * @param  object  $cloudApp
     * @param  array   $settings
     * @access private
     * @return object|false
     */
    private function installApp(object $cloudApp, array $settings): object|false
    {
        /* Fake parameters for installation. */

        $customData = new stdclass;
        $customData->customName   = $cloudApp->alias;
        $customData->dbType       = null;
        $customData->customDomain = $this->loadModel('instance')->randThirdDomain();

        $dbInfo = new stdclass;
        $dbList = $this->loadModel('cne')->sharedDBList();
        if(count($dbList) > 0)
        {
            $dbInfo = reset($dbList);

            $customData->dbType    = 'sharedDB';
            $customData->dbService = $dbInfo->name; // Use first shared database.
        }

        return $this->instance->install($cloudApp, $dbInfo, $customData, null, $settings);
    }

    /**
     * 等待应用启动完成。
     * Wait instance started.
     *
     * @param  object       $instance
     * @param  int          $solutionID
     * @access private
     * @return object|false
     */
    private function waitInstanceStart(object $instance, int $solutionID): object|false
    {
        /* Query status of the installed instance. */
        $times = 0;
        $this->loadModel('instance');
        for($times = 0; $times < 50; $times++)
        {
            $solution = $this->getByID($solutionID);
            if(!$solution || $solution->status != 'installing') return false;

            $this->dao->update(TABLE_SOLUTION)->set('updatedDate')->eq(date("Y-m-d H:i:s"))->where('id')->eq($solutionID)->exec();

            sleep(12);
            $instance = $this->instance->freshStatus($instance);
            $this->saveLog(date('Y-m-d H:i:s').' installing ' . $instance->name . ':' . $instance->status . '#' . $instance->solution); // Code for debug.

            if($instance->status == 'running') return $instance;
        }

        return false;
    }

    /**
     * 移除解决方案环境。
     * Uninstall solution and all included instances .
     *
     * @param  int    $solutionID
     * @access public
     * @return bool
     */
    public function uninstall(int $solutionID): bool
    {
        /* Firstly change the status to 'unintalling' for abort installing process. */
        $this->dao->update(TABLE_SOLUTION)->set('status')->eq('uninstalling')->where('id')->eq($solutionID)->exec();

        $solution = $this->getByID($solutionID);
        if(empty($solution))
        {
            dao::$errors[] = $this->lang->solution->notFound;
            return false;
        }

        $this->loadModel('instance');
        foreach($solution->instances as $instance)
        {
            $success = $this->instance->uninstall($instance);
            if(!$success)
            {
                dao::$errors[] = sprintf($this->lang->solution->errors->failToUninstallApp, $instance->name);
                return false;
            }
        }

        $this->dao->update(TABLE_SOLUTION)->set('status')->eq('uninstalled')->set('deleted')->eq(1)->where('id')->eq($solutionID)->exec();
        return !dao::isError();
    }

    /**
     * 保存安装日志。
     * Save message to error log file.
     *
     * @param  string $message
     * @access public
     * @return string
     */
    public function saveLog(string $message): string
    {
        $errorFile = $this->app->logRoot . 'php.' . date('Ymd') . '.log.php';
        if(!is_file($errorFile)) @file_put_contents($errorFile, "<\x3fphp\n die();\n\x3f>\n");

        @file_put_contents($errorFile, $message . "\n", FILE_APPEND);
        return $errorFile;
    }

    /**
     * 获取最后一次安装的方案。
     * Get last solution.
     *
     * @access public
     * @return object
     */
    public function getLastSolution(): object|false
    {
        return $this->dao->select('*')->from(TABLE_SOLUTION)
            ->where('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetch();
    }
}
