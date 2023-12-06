<?php
/**
 * The model file of solution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jianhua Wang<wangjianhua@easycorp.ltd>
 * @package     solution
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class solutionModel extends model
{
    /**
     * Get solution by id.
     *
     * @param  int         $id
     * @access public
     * @return object|null
     */
    public function getByID($id)
    {
        $solution  = $this->dao->select('*')->from(TABLE_SOLUTION)->where('id')->eq($id)->fetch();
        if(!$solution) return null;

        $instanceIDList = $this->dao->select('id')->from(TABLE_INSTANCE)->where('solution')->eq($id)->fetchAll('id');

        $solution->instances = array();
        if($instanceIDList) $solution->instances = $this->loadModel('instance')->getByIDList(array_keys($instanceIDList));

        return $solution;
    }

    /**
     * Search
     *
     * @param  string $keyword
     * @access public
     * @return array
     */
    public function search($keyword = '')
    {
        return $this->dao->select('*')->from(TABLE_SOLUTION)
            ->where('deleted')->eq(0)
            ->beginIF($keyword)->andWhere('name')->like($keyword)->fi()
            ->orderBy('createdAt desc')->fetchAll();
    }

    /**
     * Update solution name.
     *
     * @param  int    $solutionID
     * @access public
     * @return int
     */
    public function updateName($solutionID)
    {
        $newSolution = fixer::input('post')->trim('name')->get();

        return $this->dao->update(TABLE_SOLUTION)->data($newSolution)->autoCheck()->where('id')->eq($solutionID)->exec();
    }

    /**
     * Create by solution of cloud market.
     *
     * @param  object $cloudSolution
     * @access public
     * @return object
     */
    public function create($cloudSolution, $components)
    {
        $postedCharts = $this->session->solutionCharts == '' ? fixer::input('post')->get() : $this->session->solutionCharts;

        /* Sort selected apps. */
        $orderedCategories = $components->order;
        $selectedApps = array();
        foreach($orderedCategories as $category)
        {
            $chart = zget($postedCharts, $category);

            $selectedApps[$category] = $this->pickAppFromSchema($components, $category, $chart, $cloudSolution);
            if(empty($selectedApps[$category])) unset($selectedApps[$category]);
        }

        if(!$this->app->user->account)
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
        $solution->createdAt    = date('Y-m-d H:i:s');

        $channel = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;

        $solution->channel = $channel;

        $this->dao->insert(TABLE_SOLUTION)->data($solution)->exec();

        if(dao::isError()) return null;

        return $this->getByID($this->dao->lastInsertID());
    }

    /**
     * Pick App from schema info by category and chart.
     *
     * @param  object $schema
     * @param  string $category
     * @param  string $chart
     * @param  object $cloudSolution
     * @access public
     * @return object|null
     */
    public function pickAppFromSchema($schema, $category, $chart, $cloudSolution)
    {
        $categoryList = helper::arrayColumn($schema->category, null, 'name');
        $appGroup = zget($categoryList, $category, array());

        foreach($appGroup->choices as $appInSchema)
        {

            if($appInSchema->name != $chart) continue;

            $appInfo = zget($cloudSolution->apps, $chart);

            $appInfo->version     = $appInSchema->version;
            $appInfo->app_version = $appInSchema->app_version;
            $appInfo->status      = 'waiting';

            return $appInfo;
        }

        return;
    }

    /**
     * Install solution.
     *
     * @param  int    $solutionID
     * @access public
     * @return bool
     */
    public function install($solutionID)
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
        $this->loadModel('store');
        $this->loadModel('common');
        $allMappings    = array();
        $solutionSchema = $this->loadModel('store')->solutionConfig('id', $solution->appID);
        $channel        = $this->app->session->cloudChannel ? $this->app->session->cloudChannel : $this->config->cloud->api->channel;
        $components     = json_decode($solution->components);
        $apps           = helper::arrayColumn(json_decode($solution->components, true), 'chart');
        foreach($components as $categorty => $componentApp)
        {
            $solutionStatus = $this->dao->select('status')->from(TABLE_SOLUTION)->where('id')->eq($solutionID)->fetch();
            if($solutionStatus->status !='installing')
            {
                /* If status is not installing, should abort installation.  Becaust installation was canceled or error happened. */
                dao::$errors[] = $this->lang->solution->errors->hasInstallationError;
                return false;
            }

            $instance = $this->instance->instanceOfSolution($solution, $componentApp->chart);
            /* If not install. */
            if(!$instance)
            {
                $cloudApp = $this->store->getAppInfo($componentApp->id, false, '', $componentApp->version, $channel);
                if(!$cloudApp)
                {
                    $this->saveStatus($solutionID, 'notFoundApp');
                    dao::$errors[] = sprintf($this->lang->solution->errors->notFoundAppByVersion, $componentApp->version, $componentApp->alias);
                    return false;
                }
                /* Must install the defineded version in solution schema. */
                $cloudApp->version     = $componentApp->version;
                $cloudApp->app_version = $componentApp->app_version;

                if($componentApp->external)
                {
                    $instance = $this->installExternalApp($cloudApp, $componentApp->external);
                }
                else
                {
                    /* Check enough memory to install app, or not.*/
                    if(!$this->instance->enoughMemory($cloudApp))
                    {
                        $this->saveStatus($solutionID, 'notEnoughResource');
                        dao::$errors[] = $this->lang->solution->errors->notEnoughResource;
                        return false;
                    }

                    if(!$this->checkInstallStatus($solutionID)) return false;
                    $settings = $this->mountSettings($solutionSchema, $componentApp->chart, $components, $allMappings, in_array('sonarqube', $apps));
                    $instance = $this->installApp($cloudApp, $settings);
                }

                if(!$instance)
                {
                    $this->saveStatus($solutionID, 'cneError');
                    dao::$errors[] = sprintf($this->lang->solution->errors->failToInstallApp, $cloudApp->name);
                    return false;
                }
                $this->dao->update(TABLE_INSTANCE)->set('solution')->eq($solutionID)->where('id')->eq($instance->id)->exec();

                $componentApp->status = 'installing';
                $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
            }

            if($componentApp->external)
            {
                $tempMappings = $this->getExternalMapping($solutionSchema, $componentApp);
                if($tempMappings) $allMappings[$categorty] = $tempMappings;

                $componentApp->status = 'configured';
                $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
                continue;
            }

            /* Wait instanlled app started. */
            $instance = $this->waitInstanceStart($instance, $solutionID);
            if($instance)
            {
                $mappingKeys = zget($solutionSchema->mappings, $instance->chart, '');
                if($mappingKeys)
                {
                    /* Load settings mapping of installed app for next app. */
                    $tempMappings = $this->cne->getSettingsMapping($instance, $mappingKeys);
                    if($tempMappings) $allMappings[$categorty] = $tempMappings;
                }
                $componentApp->status = 'installed';
                $this->dao->update(TABLE_SOLUTION)->set('components')->eq(json_encode($components))->where('id')->eq($solution->id)->exec();
                $this->instance->saveAuthInfo($instance);
            }
            else
            {
                $this->saveStatus($solutionID, 'timeout');
                dao::$errors[] = $this->lang->solution->errors->timeout;
                return false;
            }
        }

        $this->saveStatus($solutionID, 'installed');
        return true;
    }

    /**
     * Save status.
     *
     * @param  int    $solutionID
     * @param  string $status
     * @access public
     * @return int
     */
    public function saveStatus($solutionID, $status)
    {
        return $this->dao->update(TABLE_SOLUTION)->set('status')->eq($status)->set('updatedDate')->eq(date("Y-m-d H:i:s"))->where('id')->eq($solutionID)->exec();
    }

    /**
     * Mount settings for installing app.
     *
     * @param  object  $solutionSchema
     * @param  string  $chart
     * @param  object  $components
     * @param  array   $mappings  example: ['git' => ['env.GIT_USERNAME' => 'admin', ...], ...]
     * @access private
     * @return array
     */
    private function mountSettings($solutionSchema, $chart, $components, $mappings, $isInstallSonar = true)
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
     * installApp
     *
     * @param  object  $cloudApp
     * @param  int     $settings
     * @access private
     * @return mixed
     */
    private function installApp($cloudApp, $settings)
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
     * Wait instance started.
     *
     * @param  object      $instance
     * @access private
     * @return object|bool
     */
    private function waitInstanceStart($instance, $solutionID)
    {
        /* Query status of the installed instance. */
        $times = 0;
        for($times = 0; $times < 50; $times++)
        {
            if(!$this->checkInstallStatus($solutionID)) return false;
            $this->dao->update(TABLE_SOLUTION)->set('updatedDate')->eq(date("Y-m-d H:i:s"))->where('id')->eq($solutionID)->exec();

            sleep(12);
            $instance = $this->instance->freshStatus($instance);
            $this->saveLog(date('Y-m-d H:i:s').' installing ' . $instance->name . ':' . $instance->status . '#' . $instance->solution); // Code for debug.
            if($instance->status == 'running') return $instance;
        }

        return false;
    }

    /**
     * Check solution status.
     *
     * @param  int $solutionID
     * @access public
     * @return void
     */
    public function checkInstallStatus($solutionID)
    {
        $solution = $this->getByID($solutionID);
        if($solution->status != 'installing') return false;
        return true;
    }

    /**
     * Uninstall solution and all included instances .
     *
     * @param  int    $solutionID
     * @access public
     * @return void
     */
    public function uninstall($solutionID)
    {
        $this->loadModel('instance');
        /* Firstly change the status to 'unintalling' for abort installing process. */
        $this->dao->update(TABLE_SOLUTION)->set('status')->eq('uninstalling')->where('id')->eq($solutionID)->exec();

        $solution = $this->getByID($solutionID);
        if(empty($solution))
        {
            dao::$errors[] = $this->lang->solution->notFound;
            return;
        }

        foreach($solution->instances as $instance)
        {
            $success = $this->instance->uninstall($instance);
            if(!$success)
            {
                dao::$errors[] = sprintf($this->lang->solution->errors->failToUninstallApp, $instance->name);
                return;
            }
        }

        $this->dao->update(TABLE_SOLUTION)->set('status')->eq('uninstalled')->set('deleted')->eq(1)->where('id')->eq($solutionID)->exec();
    }

    /**
     * Convert schema choices to select options.
     *
     * @param  object $schemaChoices
     * @param  object $cloudSolution
     * @access public
     * @return array
     */
    public function createSelectOptions($schemaChoices, $cloudSolution)
    {
        $options = array();
        foreach($schemaChoices as $cloudApp)
        {
            $appInfo = zget($cloudSolution->apps, $cloudApp->name, array());
            $options[$cloudApp->name] = zget($appInfo, 'alias', $cloudApp->name);
        }

        return $options;
    }

    /**
     * Save message to error log file.
     *
     * @param  string $message
     * @access public
     * @return void
     */
    public function saveLog($message)
    {
        $errorFile = $this->app->logRoot . 'php.' . date('Ymd') . '.log.php';
        if(!is_file($errorFile)) file_put_contents($errorFile, "<?php\n die();\n?>\n");

        file_put_contents($errorFile, $message . "\n", FILE_APPEND);
    }

    /**
     * Get last solution.
     *
     * @access public
     * @return object
     */
    public function getLastSolution()
    {
        return $this->dao->select('*')->from(TABLE_SOLUTION)
            ->where('deleted')->eq(0)
            ->orderBy('id_desc')
            ->fetch();
    }
}
