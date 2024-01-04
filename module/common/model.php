<?php
declare(strict_types=1);
/**
 * The model file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class commonModel extends model
{
    public static $requestErrors = array();

    /**
     * 设置用户配置信息。
     * Set config of user.
     *
     * @access public
     * @return void
     */
    public function setUserConfig()
    {
        $this->sendHeader();
        $this->setCompany();
        $this->setUser();
        $this->setApproval();
        $this->loadConfigFromDB();
        $this->loadCustomFromDB();
        $this->initAuthorize();

        if(!$this->checkIP()) return print($this->lang->ipLimited);
    }

    /**
     * 同步执行、项目、项目集的状态。
     * Set the status of execution, project, and program to doing.
     *
     * @param  int    $objectID
     * @access public
     * @return void
     */
    public function syncPPEStatus(int $objectID)
    {
        global $app;
        $rawModule = $app->rawModule;
        $rawMethod = strtolower($app->rawMethod);

        if($rawModule == 'marketresearch' and strpos($rawMethod, 'task') !== false)  $rawModule = 'task';
        if($rawModule == 'marketresearch' and strpos($rawMethod, 'stage') !== false) $rawModule = 'execution';

        if($rawModule == 'task' or $rawModule == 'effort')
        {
            $taskID    = $objectID;
            $execution = $this->syncExecutionStatus($taskID);
            $project   = $this->syncProjectStatus($execution);
            $this->syncProgramStatus($project);
        }
        if($rawModule == 'execution')
        {
            $executionID = $objectID;
            $execution   = $this->dao->select('id, project, grade, parent, status, deleted')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
            $this->syncExecutionByChild($execution);
            $project     = $this->syncProjectStatus($execution);
            $this->syncProgramStatus($project);
        }
        if($rawModule == 'project')
        {
            $projectID = $objectID;
            $project   = $this->dao->select('id, parent, path')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();
            $this->syncProgramStatus($project);
        }
        if($rawModule == 'program' and $this->config->systemMode == 'ALM')
        {
            $programID = $objectID;
            $program   = $this->dao->select('id, parent, path')->from(TABLE_PROGRAM)->where('id')->eq($programID)->fetch();
            $this->syncProgramStatus($program);
        }
    }

   /**
     * 项目开始时，设置项目所属的项目集的状态为进行中。
     * Set the status of the program to which theproject is linked as Ongoing.
     *
     * @param  object   $project
     * @access public
     * @return void
     */
    public function syncProgramStatus(object $project)
    {
        if($project->parent == 0) return;

        $parentPath = str_replace(",{$project->id},", '', $project->path);
        $parentPath = explode(',', trim($parentPath, ','));
        $waitList   = $this->dao->select('id')->from(TABLE_PROGRAM)
            ->where('id')->in($parentPath)
            ->andWhere('status')->eq('wait')
            ->orderBy('id_desc')
            ->fetchPairs();

        $this->dao->update(TABLE_PROGRAM)->set('status')->eq('doing')->set('realBegan')->eq(helper::now())->where('id')->in($waitList)->exec();

        $this->loadModel('action');
        foreach($waitList as $programID)
        {
            $this->action->create('program', $programID, 'syncprogram');
        }
    }

    /**
     * 执行开始时，设置执行所属的项目和项目所属的项目集的状态为进行中。
     * Set the status of the project to which the execution is linked as Ongoing.
     *
     * @param  object  $execution
     * @access public
     * @return object  $project
     */
    public function syncProjectStatus(object $execution): object
    {
        $projectID = $execution->project;
        $project   = $this->dao->select('*')->from(TABLE_PROJECT)->where('id')->eq($projectID)->fetch();

        $today = helper::today();
        if($project->status == 'wait')
        {
            $this->dao->update(TABLE_PROJECT)
                 ->set('status')->eq('doing')
                 ->beginIf(helper::isZeroDate($project->realBegan))->set('realBegan')->eq($today)->fi()
                 ->where('id')->eq($projectID)
                 ->exec();

            $this->loadModel('project')->recordFirstEnd($projectID);

            $actionType = $project->multiple ? 'syncproject' : 'syncmultipleproject';
            $this->loadModel('action')->create('project', $projectID, $actionType);
        }

        return $project;
    }

    /**
     * 子阶段开始时，设置子阶段所属的父阶段和项目的状态为进行中。
     * Set the status of the execution to which the sub execution is linked as Ongoing.
     *
     * @param  object $execution
     * @access public
     * @return object|false $parentExecution
     */
    public function syncExecutionByChild(object $execution): object|false
    {
        if($execution->grade == 1) return false;

        $today = helper::today();
        $parentExecution = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($execution->parent)->fetch();

        if($execution->deleted == '0' and $execution->status == 'doing' and in_array($parentExecution->status, array('wait', 'closed')))
        {
            $this->dao->update(TABLE_EXECUTION)
                 ->set('status')->eq('doing')
                 ->beginIf(helper::isZeroDate($parentExecution->realBegan))->set('realBegan')->eq($today)->fi()
                 ->where('id')->eq($execution->parent)
                 ->exec();
            $this->loadModel('action')->create('execution', $execution->parent, 'syncexecutionbychild');
        }

        $project = $this->loadModel('project')->getByID($execution->project);
        if($project->model == 'waterfall' or $project->model == 'waterfallplus') $this->loadModel('programplan')->computeProgress($execution->id);

        return $parentExecution;
    }

    /**
     * 任务开始时，设置任务所属的执行、项目和项目所属的项目集的状态为进行中。
     * Set the status of the execution to which the task is linked as Ongoing.
     *
     * @param  int    $taskID
     * @access public
     * @return object $execution
     */
    public function syncExecutionStatus(int $taskID): object
    {
        $execution = $this->dao->select('t1.*')->from(TABLE_EXECUTION)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.id=t2.execution')
            ->where('t2.id')->eq($taskID)
            ->fetch();

        $today = helper::today();
        if($execution->status == 'wait')
        {
            $this->dao->update(TABLE_EXECUTION)->set('status')->eq('doing')->set('realBegan')->eq($today)->where('id')->eq($execution->id)->exec();
            $this->loadModel('project')->recordFirstEnd($execution->id);

            $this->loadModel('action')->create('execution', $execution->id, 'syncexecution');
            if($execution->parent)
            {
                $execution = $this->dao->select('*')->from(TABLE_EXECUTION)->where('id')->eq($execution->id)->fetch(); // Get updated execution.
                $this->syncExecutionByChild($execution);
            }
        }
        return $execution;
    }

    /**
     * 设置HTTP标头。
     * Set the header info.
     *
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        helper::header('Content-Type', "text/html; Language={$this->config->charset}");
        helper::header('Cache-Control', 'private');

        /* Send HTTP header. */
        if($this->config->framework->sendXCTO)  helper::header('X-Content-Type-Options', 'nosniff');
        if($this->config->framework->sendXXP)   helper::header('X-XSS-Protection', '1; mode=block');
        if($this->config->framework->sendHSTS)  helper::header('Strict-Transport-Security', 'max-age=3600; includeSubDomains');
        if($this->config->framework->sendRP)    helper::header('Referrer-Policy', 'no-referrer-when-downgrade');
        if($this->config->framework->sendXPCDP) helper::header('X-Permitted-Cross-Domain-Policies', 'master-only');
        if($this->config->framework->sendXDO)   helper::header('X-Download-Options', 'noopen');

        /* Set Content-Security-Policy header. */
        if($this->config->CSPs)
        {
            foreach($this->config->CSPs as $CSP) helper::header('Content-Security-Policy', "$CSP;");
        }

        if($this->loadModel('setting')->getItem('owner=system&module=sso&key=turnon'))
        {
            if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == 'on')
            {
                $session = $this->config->sessionVar . '=' . session_id();
                helper::header('Set-Cookie', "$session; SameSite=None; Secure=true", false);
            }
        }
        else
        {
            if(!empty($this->config->xFrameOptions)) helper::header('X-Frame-Options', $this->config->xFrameOptions);
        }
    }

    /**
     * 设置公司信息。
     * Set the company.
     *
     * First, search company by the http host. If not found, search by the default domain. Last, use the first as the default.
     * After get the company, save it to session.
     * @access public
     * @return void
     */
    public function setCompany()
    {
        if($this->session->company)
        {
            $this->app->company = $this->session->company;
        }
        else
        {
            $httpHost = $this->server->http_host;
            $company  = $this->loadModel('company')->getFirst();
            if(!$company) $this->app->triggerError(sprintf($this->lang->error->companyNotFound, $httpHost), __FILE__, __LINE__, true);
            $this->session->set('company', $company);
            $this->app->company = $company;
        }
    }

    /**
     * 设置用户信息。
     * Set the user info.
     *
     * @access public
     * @return void
     */
    public function setUser()
    {
        if($this->session->user)
        {
            $this->app->user = $this->session->user;
        }
        elseif($this->app->company->guest || (PHP_SAPI == 'cli' && (!isset($_SERVER['RR_MODE']) || $_SERVER['RR_MODE'] == 'jobs')))
        {
            $user           = new stdClass();
            $user->id       = 0;
            $user->account  = 'guest';
            $user->realname = 'guest';
            $user->dept     = 0;
            $user->avatar   = '';
            $user->role     = 'guest';
            $user->admin    = false;
            $user->groups   = array('group');
            $user->visions  = $this->config->vision;
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }
    }

    /**
     * 初始化用户权限和视图。
     * Init user authorize.
     *
     * @access private
     * @return void
     */
    private function initAuthorize()
    {
        if(isset($this->app->user))
        {
            $user = $this->app->user;
            if(!$this->app->upgrading) $user->rights = $this->loadModel('user')->authorize($user->account);
            if(!$this->app->upgrading) $user->view = $this->user->grantUserView($user->account, $user->rights['acls']);
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }
    }

    /**
     * 设置审批配置。
     * Set approval config.
     *
     * @access public
     * @return void
     */
    public function setApproval()
    {
        $this->config->openedApproval = (in_array($this->config->edition, array('max', 'ipd'))) && ($this->config->vision == 'rnd');
    }

    /**
     * 获取表单的配置项。
     * Obtain the config for the form.
     *
     * @param  string $module
     * @param  string $method
     * @static
     * @access public
     * @return array
     */
    public static function formConfig(string $module, string $method): array
    {
        global $config, $app;
        if($config->edition == 'open') return array();

        $required   = $app->dbQuery("SELECT * FROM " . TABLE_WORKFLOWRULE . " WHERE `type` = 'system' and `rule` = 'notempty'")->fetch();
        $fields     = $app->control->loadModel('flow')->getExtendFields($module, $method);
        $fields     = (new self())->loadModel('flow')->getExtendFields($module, $method);
        $formConfig = array();
        $type       = 'string';
        foreach($fields as $fieldObject)
        {
            if(strpos($fieldObject->type, 'int') !== false) $type = 'int';
            if(strpos($fieldObject->type, 'date') !== false) $type = 'date';
            if(in_array($fieldObject->type, array('float', 'decimal'))) $type = 'float';

            $formConfig[$fieldObject->field] = array('type' => $type, 'default' => $fieldObject->default, 'control' => $fieldObject->control, 'rules' => $fieldObject->rules);
            $formConfig[$fieldObject->field]['required'] = strpos(",{$fieldObject->rules},", ",{$required->id},") !== false;
            if(in_array($fieldObject->control, array('multi-select', 'checkbox'))) $formConfig[$fieldObject->field]['filter'] = 'join';
        }
        return $formConfig;
    }

    /**
     * 从数据库加载配置信息。
     * Load configs from database and save it to config->system and config->personal.
     *
     * @access public
     * @return void
     */
    public function loadConfigFromDB()
    {
        /* Get configs of system and current user. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        if($this->config->db->name) $config = $this->loadModel('setting')->getSysAndPersonalConfig($account);
        $this->config->system   = isset($config['system']) ? $config['system'] : array();
        $this->config->personal = isset($config[$account]) ? $config[$account] : array();

        /* Override the items defined in config/config.php and config/my.php. */
        if(isset($this->config->system->common))   $this->app->mergeConfig($this->config->system->common, 'common');
        if(isset($this->config->personal->common)) $this->app->mergeConfig($this->config->personal->common, 'common');

        $this->config->disabledFeatures = $this->config->disabledFeatures . ',' . $this->config->closedFeatures;
    }

    /**
     * 从数据库加载自定义信息。
     * Load custom lang from db.
     *
     * @access public
     * @return void
     */
    public function loadCustomFromDB()
    {
        $this->loadModel('custom');

        if($this->app->upgrading) return;
        if(!$this->config->db->name) return;

        $records = $this->custom->getAllLang();
        if(!$records) return;

        $this->lang->db = new stdclass();
        $this->lang->db->custom = $records;
    }

    /**
     * 判断哪些方法不需要鉴权。
     * Judge a method of one module is open or not.
     *
     * @param  string $module
     * @param  string $method
     * @access public
     * @return bool
     */
    public function isOpenMethod(string $module, string $method): bool
    {
        if(in_array("$module.$method", $this->config->openMethods)) return true;

        if($module == 'block' and $method == 'main' and isset($_GET['hash'])) return true;

        if($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))
        {
            if(stripos($method, 'ajax') !== false) return true;
            if($module == 'block') return true;
            if($module == 'index'    and $method == 'app') return true;
            if($module == 'my'       and $method == 'guidechangetheme') return true;
            if($module == 'product'  and $method == 'showerrornone') return true;
            if($module == 'misc'     and in_array($method, array('downloadclient', 'changelog'))) return true;
            if($module == 'tutorial' and in_array($method, array('start', 'index', 'quit', 'wizard'))) return true;
        }
        return false;
    }

    /**
     * 拒绝访问的页面。
     * Deny access.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  bool    $reload
     * @access public
     * @return mixed
     */
    public function deny(string $module, string $method, bool $reload = true)
    {
        if($reload)
        {
            /* Get authorize again. */
            $user = $this->app->user;
            $user->rights = $this->loadModel('user')->authorize($user->account);
            $user->groups = $this->user->getGroups($user->account);
            $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
            if(commonModel::hasPriv($module, $method)) return true;
        }

        $vars = "module=$module&method=$method";
        if(isset($this->server->http_referer))
        {
            $referer = helper::safe64Encode($this->server->http_referer);
            $vars   .= "&referer=$referer";
        }
        $denyLink = helper::createLink('user', 'deny', $vars);

        echo json_encode(array('load' => $denyLink));
        helper::end();
    }

    /**
     * 输出运行信息。
     * Print the run info.
     *
     * @param mixed $startTime  the start time.
     * @access public
     * @return array    the run info array.
     */
    public function printRunInfo($startTime)
    {
        $info['timeUsed'] = round(getTime() - $startTime, 4) * 1000;
        $info['memory']   = round(memory_get_peak_usage() / 1024, 1);
        $info['querys']   = count(dao::$querys);
        vprintf($this->lang->runInfo, $info);
        return $info;
    }

    /**
     * 格式化日期，将日期格式化为YYYY-mm-dd，将日期时间格式化为YYYY-mm-dd HH:ii:ss。
     * Format the date to YYYY-mm-dd, format the datetime to YYYY-mm-dd HH:ii:ss.
     *
     * @param  string $date
     * @param  string $type date|datetime|''
     * @access public
     * @return string
     */
    public function formatDate(string $date, string $type = '')
    {
        if(helper::isZeroDate($date))
        {
            if($type == 'date')     return '0000-00-00';
            if($type == 'datetime') return '0000-00-00 00:00:00';
        }

        $datePattern     = '\w{4}(\/|-)\w{1,2}(\/|-)\w{1,2}';
        $datetimePattern = $datePattern . ' \w{1,2}\:\w{1,2}\:\w{1,2}';

        if(empty($type))
        {
            if(!preg_match("/$datePattern/", $date) and !preg_match("/$datetimePattern/", $date)) return $date;
            if(preg_match("/$datePattern/", $date) === 1)     $type = 'date';
            if(preg_match("/$datetimePattern/", $date) === 1) $type = 'datetime';
        }

        if($type == 'date')     $format = 'Y-m-d';
        if($type == 'datetime') $format = 'Y-m-d H:i:s';

        return date($format, strtotime($date));
    }

    /**
     * 创建菜单项链接。
     * Create menu item link
     *
     * @param object $menuItem
     *
     * @static
     * @access public
     * @return string
     */
    public static function createMenuLink(object $menuItem): string
    {
        $link = $menuItem->link;
        if(is_array($menuItem->link))
        {
            $vars = isset($menuItem->link['vars']) ? $menuItem->link['vars'] : '';
            if(isset($menuItem->tutorial) and $menuItem->tutorial)
            {
                if(!empty($vars)) $vars = helper::safe64Encode($vars);
                $link = helper::createLink('tutorial', 'wizard', "module={$menuItem->link['module']}&method={$menuItem->link['method']}&params=$vars");
            }
            else
            {
                $link = helper::createLink($menuItem->link['module'], $menuItem->link['method'], $vars);
            }
        }
        return $link;
    }

    /**
     * 获取左侧一级导航。
     * Get main nav items list
     *
     * @param  string $moduleName
     *
     * @static
     * @access public
     * @return array
     */
    public static function getMainNavList(string $moduleName): array
    {
        global $lang, $app, $config;

        $app->loadLang('my');

        /* Ensure user has latest rights set. */
        $app->user->rights = $app->control->loadModel('user')->authorize($app->user->account);

        $menuOrder = $lang->mainNav->menuOrder;
        ksort($menuOrder);

        $items        = array();
        $lastItem     = end($menuOrder);
        $printDivider = false;

        foreach($menuOrder as $key => $group)
        {
            if($group != 'my' && !empty($app->user->rights['acls']['views']) && !isset($app->user->rights['acls']['views'][$group])) continue; // 后台权限分组中没有给导航视图

            $nav = $lang->mainNav->$group;
            list($title, $currentModule, $currentMethod, $vars) = explode('|', $nav);

            /* When last divider is not used in mainNav, use it next menu. */
            $printDivider = ($printDivider or ($lastItem != $key) and strpos($lang->dividerMenu, ",{$group},") !== false) ? true : false;
            if($printDivider and !empty($items))
            {
                $items[]      = 'divider';
                $printDivider = false;
            }

            $display = false;

            /* 1. 有权限则展示导航. */
            if(common::hasPriv($currentModule, $currentMethod)) $display = true;

            /* 2. 如果没有资产库落地页的权限，则查看是否有资产库其他方法的权限. */
            if($currentModule == 'assetlib' && !$display) list($display, $currentMethod) = commonTao::setAssetLibMenu($display, $currentModule, $currentMethod);

            /* 3. 可以个性化设置的导航，如果没有落地页的权限，则查看是否有其他落地页的权限。 */
            $moduleLinkList = $currentModule . 'LinkList';
            if(!$display and isset($lang->my->$moduleLinkList) and $config->vision != 'or') list($display, $currentMethod) = commonTao::setPreferenceMenu($display, $currentModule, $currentMethod);

            /* 4. 不可以个性化设置的导航，如果没有落地页的权限，则查看是否有对应app下其他方法的权限. */
            if(!$display and isset($lang->$currentModule->menu) and !in_array($currentModule, array('program', 'product', 'project', 'execution', 'demandpool'))) list($display, $currentMethod) = commonTao::setOtherMenu($display, $currentModule, $currentMethod);

            /* 5. 如果以上权限都没有，则最后查看是否有该应用下任意一个顶部一级导航的权限。 */
            if(!$display and isset($lang->$group->menu)) list($display, $currentModule, $currentMethod) = commonTao::setMenuByGroup($group, $display, $currentModule, $currentMethod);

            /* Check whether the homeMenu of this group have permissions. If yes, point to them. */
            if($display == false and isset($lang->$group->homeMenu))
            {
                foreach($lang->$group->homeMenu as $menu)
                {
                    if(!isset($menu['link'])) continue;

                    $linkPart = explode('|', $menu['link']);
                    if(count($linkPart) < 3) continue;
                    list($label, $module, $method) = $linkPart;

                    if(common::hasPriv($module, $method))
                    {
                        $display       = true;
                        $currentModule = $module;
                        $currentMethod = $method;
                        if(!isset($menu['target'])) break;
                    }
                }
            }

            if(!$display) continue;

            /* Assign vars. */
            $item = new stdClass();
            $item->group      = $group;
            $item->code       = $group;
            $item->active     = zget($lang->navGroup, $moduleName, '') == $group or $moduleName != 'program' and $moduleName == $group;
            $item->title      = $title;
            $item->moduleName = $currentModule;
            $item->methodName = $currentMethod;
            $item->vars       = $vars;

            $isTutorialMode = commonModel::isTutorialMode();
            if($isTutorialMode and $currentModule == 'project')
            {
                if(!empty($vars)) $vars = helper::safe64Encode($vars);
                $item->url = helper::createLink('tutorial', 'wizard', "module={$currentModule}&method={$currentMethod}&params={$vars}", '', false, 0, 1);
            }
            else
            {
                $item->url = helper::createLink($currentModule, $currentMethod, $vars, '', false, 0, 1);
            }

            $items[] = $item;
        }

        /* Fix bug 14574. */
        if(end($items) == 'divider') array_pop($items);
        return $items;
    }

    /**
     * 获取高亮的顶部一级导航。
     * Get active main menu.
     *
     * @static
     * @access public
     * @return string
     */
    public static function getActiveMainMenu(): string
    {
        global $app;

        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if($isTutorialMode && defined('WIZARD_MODULE')) $currentModule = WIZARD_MODULE;
        if($isTutorialMode && defined('WIZARD_METHOD')) $currentMethod = WIZARD_METHOD;

        /* Print all main menus. */
        $menu = customModel::getMainMenu();

        $activeMenu = '';
        foreach($menu as $menuItem)
        {
            if(isset($menuItem->hidden) and $menuItem->hidden and (!isset($menuItem->tutorial) or !$menuItem->tutorial)) continue;
            if(empty($menuItem->link)) continue;

            /* Init the these vars. */
            $alias     = isset($menuItem->alias) ? $menuItem->alias : '';
            $subModule = isset($menuItem->subModule) ? explode(',', $menuItem->subModule) : array();
            $exclude   = isset($menuItem->exclude) ? $menuItem->exclude : '';

            if($menuItem->name == $currentModule and strpos(",$exclude,", ",$currentModule-$currentMethod,") === false) $activeMenu = $menuItem->name;

            if($subModule and in_array($currentModule, $subModule) and strpos(",$exclude,", ",$currentModule-$currentMethod,") === false) $activeMenu = $menuItem->name;

            if($menuItem->link)
            {
                $module = '';
                $method = '';

                if(is_array($menuItem->link))
                {
                    if(isset($menuItem->link['module'])) $module = $menuItem->link['module'];
                    if(isset($menuItem->link['method'])) $method = $menuItem->link['method'];
                }

                if($module == $currentModule and ($method == $currentMethod or strpos(",$alias,", ",$currentMethod,") !== false) and strpos(",$exclude,", ",$currentMethod,") === false) $activeMenu = $menuItem->name;

                /* Print drop menus. */
                if(isset($menuItem->dropMenu))
                {
                    foreach($menuItem->dropMenu as $dropMenuName => $dropMenuItem)
                    {
                        if(empty($dropMenuItem)) continue;
                        if(isset($dropMenuItem->hidden) and $dropMenuItem->hidden) continue;

                        /* Parse drop menu link. */
                        $dropMenuLink = zget($dropMenuItem, 'link', $dropMenuItem);

                        list($subLabel, $subModule, $subMethod, $subParams) = explode('|', $dropMenuLink);
                        if(!common::hasPriv($subModule, $subMethod)) continue;

                        $activeMainMenu = false;
                        if($currentModule == strtolower($subModule) and $currentMethod == strtolower($subMethod))
                        {
                            $activeMainMenu = true;
                        }
                        else
                        {
                            $subModule  = isset($dropMenuItem['subModule']) ? explode(',', $dropMenuItem['subModule']) : array();
                            $subExclude = isset($dropMenuItem['exclude']) ? $dropMenuItem['exclude'] : $exclude;
                            if($subModule and in_array($currentModule, $subModule) and strpos(",$subExclude,", ",$currentModule-$currentMethod,") === false) $activeMainMenu = true;
                        }

                        if($activeMainMenu) $activeMenu = $dropMenuName;
                    }
                }
            }
        }

        return $activeMenu;
    }

    /**
     * 当对象编辑后，比较前后差异。
     * Create changes of one object.
     *
     * @param  mixed  $old        the old object
     * @param  mixed  $new        the new object
     * @param  string $moduleName
     * @static
     * @access public
     * @return array
     */
    public static function createChanges(mixed $old, mixed $new, string $moduleName = ''): array
    {
        global $app, $config;

        /**
         * 当主状态改变并且未设置子状态的值时把子状态的值设置为默认值并记录日志。
         * Change sub status when status is changed and sub status is not set, and record the changes.
         */
        if($config->edition != 'open')
        {
            $oldID        = zget($old, 'id', '');
            $oldStatus    = zget($old, 'status', '');
            $newStatus    = zget($new, 'status', '');
            $newSubStatus = zget($new, 'subStatus', '');
            if(empty($moduleName)) $moduleName = $app->getModuleName();

            if($oldID and $oldStatus and $newStatus and !$newSubStatus and $oldStatus != $newStatus)
            {
                $field = $app->dbQuery('SELECT options FROM ' . TABLE_WORKFLOWFIELD . " WHERE `module` = '$moduleName' AND `field` = 'subStatus'")->fetch();
                if(!empty($field->options)) $field->options = json_decode($field->options, true);

                if(!empty($field->options[$newStatus]['default']))
                {
                    $flow    = $app->dbQuery('SELECT `table` FROM ' . TABLE_WORKFLOW . " WHERE `module`='$moduleName'")->fetch();
                    $default = $field->options[$newStatus]['default'];

                    $app->dbh->exec("UPDATE `$flow->table` SET `subStatus` = '$default' WHERE `id` = '$oldID'");

                    $new->subStatus = $default;
                }
            }

            $dateFields = array();
            $sql        = "SELECT `field` FROM " . TABLE_WORKFLOWFIELD . " WHERE `module` = '{$moduleName}' and `control` in ('date', 'datetime')";
            $stmt       = $app->dbQuery($sql);
            while($row = $stmt->fetch()) $dateFields[$row->field] = $row->field;
        }

        $changes = array();
        foreach($new as $key => $value)
        {
            if(is_object($value) || is_array($value)) continue;

            $check = strtolower($key);
            if(in_array($check, array('lastediteddate', 'lasteditedby', 'assigneddate', 'editedby', 'editeddate', 'editingdate', 'uid'))) continue;
            if(in_array($check, array('finisheddate', 'canceleddate', 'hangupeddate', 'lastcheckeddate', 'activateddate', 'closeddate', 'actualcloseddate')) && $value == '') continue;

            if(isset($old->$key) && !is_object($old->$key) && !is_array($old->$key))
            {
                if($config->edition != 'open' && isset($dateFields[$key])) $old->$key = formatTime($old->$key);

                if($value != stripslashes((string)$old->$key))
                {
                    $diff = '';
                    if(substr_count((string)$value, "\n") > 1     or
                        substr_count((string)$old->$key, "\n") > 1 or
                        strpos('name,title,desc,spec,steps,content,digest,verify,report,definition,analysis,summary,prevention,resolution,outline,schedule,minutes', strtolower($key)) !== false)
                    {
                        $diff = commonModel::diff((string)$old->$key, (string)$value);
                    }
                    $changes[] = array('field' => $key, 'old' => $old->$key, 'new' => $value, 'diff' => $diff);
                }
            }
        }
        return $changes;
    }

    /**
     * 比较两个字符串的差异。
     * Diff two string. (see phpt)
     *
     * @param string $text1
     * @param string $text2
     * @static
     * @access public
     * @return string
     */
    public static function diff(string $text1, string $text2): string
    {
        $text1 = str_replace('&nbsp;', '', trim($text1));
        $text2 = str_replace('&nbsp;', '', trim($text2));
        $w  = explode("\n", $text1);
        $o  = explode("\n", $text2);
        $w1 = array_diff_assoc($w,$o);
        $o1 = array_diff_assoc($o,$w);
        $w2 = array();
        $o2 = array();
        foreach($w1 as $idx => $val) $w2[sprintf("%03d<",$idx)] = sprintf("%03d- ", $idx+1) . "<del>" . trim($val) . "</del>";
        foreach($o1 as $idx => $val) $o2[sprintf("%03d>",$idx)] = sprintf("%03d+ ", $idx+1) . "<ins>" . trim($val) . "</ins>";
        $diff = array_merge($w2, $o2);
        ksort($diff);
        return implode("\n", $diff);
    }

    /**
     * 判断post数据大小是否超过Suhosin设置大小。
     * Judge Suhosin Setting whether the actual size of post data is large than the setting size.
     *
     * @param  int    $countInputVars
     * @static
     * @access public
     * @return bool
     */
    public static function judgeSuhosinSetting(int $countInputVars): bool
    {
        if(extension_loaded('suhosin'))
        {
            $maxPostVars    = ini_get('suhosin.post.max_vars');
            $maxRequestVars = ini_get('suhosin.request.max_vars');
            if($countInputVars > $maxPostVars or $countInputVars > $maxRequestVars) return true;
        }
        else
        {
            $maxInputVars = ini_get('max_input_vars');
            if($maxInputVars and $countInputVars > (int)$maxInputVars) return true;
        }

        return false;
    }

    /**
     * 获取详情页面上一页和下一页的对象。
     * Get the previous and next object.
     *
     * @param  string $type     story|task|bug|case
     * @param  int    $objectID
     * @access public
     * @return object
     */
    public function getPreAndNextObject(string $type, int $objectID): object
    {
        $queryCondition = $type . 'QueryCondition';
        $queryCondition = $this->session->$queryCondition;

        $preAndNextObject       = new stdClass();
        $preAndNextObject->pre  = '';
        $preAndNextObject->next = '';
        if(empty($queryCondition)) return $preAndNextObject;

        $sql              = $this->commonTao->getPreAndNextSQL($type);
        $objectList       = $this->commonTao->queryListForPreAndNext($type, $sql);
        $preAndNextObject = $this->commonTao->searchPreAndNextFromList($objectID, $objectList);
        $preAndNextObject = $this->commonTao->fetchPreAndNextObject($type, $objectID, $preAndNextObject);

        return $preAndNextObject;
    }

    /**
     * 保存列表页的查询条件到session中，用于其他页面返回、导出等操作。
     * Save one executed query.
     *
     * @param  string    $sql
     * @param  string    $objectType story|task|bug|testcase
     * @param  bool      $onlyCondition
     * @access public
     * @return void
     */
    public function saveQueryCondition(string $sql, string $objectType, bool $onlyCondition = true)
    {
        /* Set the query condition session. */
        if($onlyCondition)
        {
            $queryCondition = explode(' WHERE ', $sql);
            $queryCondition = isset($queryCondition[1]) ? $queryCondition[1] : '';
            if($queryCondition)
            {
                $queryCondition = explode(' ORDER BY ', $queryCondition);
                $queryCondition = str_replace('t1.', '', $queryCondition[0]);
            }
        }
        else
        {
            $queryCondition = explode(' ORDER BY ', $sql);
            $queryCondition = $queryCondition[0];
        }
        $queryCondition = trim($queryCondition);
        if(empty($queryCondition)) $queryCondition = "1=1";

        $this->session->set($objectType . 'QueryCondition', $queryCondition, $this->app->tab);
        $this->session->set($objectType . 'OnlyCondition', $onlyCondition, $this->app->tab);

        /* Set the query condition session. */
        $orderBy = explode(' ORDER BY ', $sql);
        $orderBy = isset($orderBy[1]) ? $orderBy[1] : '';
        if($orderBy)
        {
            $orderBy = explode(' LIMIT ', $orderBy);
            $orderBy = $orderBy[0];
            if($onlyCondition) $orderBy = str_replace('t1.', '', $orderBy);
        }
        $this->session->set($objectType . 'OrderBy', $orderBy, $this->app->tab);
        $this->session->set($objectType . 'BrowseList', array(), $this->app->tab);
    }

    /**
     * 批量创建时，移除名称重复的对象。
     * Remove duplicate for story, task, bug, case, doc.
     *
     * @param  string       $type  e.g. story task bug case doc.
     * @param  array|object $data
     * @param  string       $condition
     * @access public
     * @return array
     */
    public function removeDuplicate(string $type, object|array $data, string $condition = ''): array
    {
        $table = zget($this->config->objectTables, $type, '');
        if(empty($table)) return array('stop' => false, 'data' => $data);

        $titleField = $type == 'task' ? 'name' : 'title';
        $date       = date(DT_DATETIME1, time() - $this->config->duplicateTime);
        $dateField  = $type == 'doc' ? 'addedDate' : 'openedDate';
        $titles     = zget($data, $titleField, array());
        $storyType  = zget($data, 'type', '');

        if(empty($titles)) return false;
        $duplicate = $this->dao->select("id,$titleField")->from($table)
            ->where('deleted')->eq(0)
            ->andWhere($titleField)->in($titles)
            ->andWhere($dateField)->ge($date)->fi()
            ->beginIF($condition)->andWhere($condition)->fi()
            ->beginIF($type == 'story')->andWhere('type')->eq($storyType)
            ->fetchPairs();

        if($duplicate and is_string($titles)) return array('stop' => true, 'duplicate' => key($duplicate));
        if($duplicate and is_array($titles))
        {
            foreach($titles as $i => $title)
            {
                if(in_array($title, $duplicate)) unset($titles[$i]);
            }

            if(is_object($data)) $data->$titleField = $titles;
            if(is_array($data))  $data[$titleField] = $titles;
        }
        return array('stop' => false, 'data' => $data);
    }

    /**
     * 追加排序字段。
     * Append order by.
     *
     * @param  string $orderBy
     * @param  string $append
     * @access public
     * @return string
     */
    public static function appendOrder(string $orderBy, string $append = 'id'): string
    {
        if(empty($orderBy)) return $append;

        list($firstOrder) = explode(',', $orderBy);
        $sort = strpos($firstOrder, '_') === false ? '_asc' : strstr($firstOrder, '_');
        return strpos($orderBy, $append) === false ? $orderBy . ',' . $append . $sort : $orderBy;
    }

    /**
     * 检查字段是否存在。
     * Check field exists
     *
     * @param  string $table
     * @param  string $field
     * @access public
     * @return bool
     */
    public function checkField(string $table, string $field): bool
    {
        $fields   = $this->dao->descTable($table);
        $hasField = false;
        foreach($fields as $fieldObj)
        {
            if($field == $fieldObj->field)
            {
                $hasField = true;
                break;
            }
        }
        return $hasField;
    }

    /**
     * 检查验证文件是否正确创建。
     * Check safe file.
     *
     * @access public
     * @return string|false
     */
    public function checkSafeFile()
    {
        if($this->app->isContainer()) return false;

        if($this->app->hasValidSafeFile()) return false;

        if($this->app->getModuleName() == 'upgrade' and $this->session->upgrading) return false;

        $statusFile = $this->app->getAppRoot() . 'www' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'ok.txt';
        return (!is_file($statusFile) or (time() - filemtime($statusFile)) > $this->config->safeFileTimeout) ? $statusFile : false;
    }

    /**
     * 检查升级验证文件是否创建，给出升级的提示。
     * Check upgrade's status file is ok or not.
     *
     * @access public
     * @return bool
     */
    public function checkUpgradeStatus()
    {
        $statusFile = $this->checkSafeFile();
        if($statusFile)
        {
            $this->app->loadLang('upgrade');
            $cmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $this->lang->upgrade->createFileWinCMD : $this->lang->upgrade->createFileLinuxCMD;
            $cmd = sprintf($cmd, $statusFile);

            echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>";
            echo "<table align='center' style='margin-top:100px; border:1px solid gray; font-size:14px;padding:8px;'><tr><td>";
            printf($this->lang->upgrade->setStatusFile, $cmd, $statusFile);
            echo '</td></tr></table></body></html>';

            return false;
        }

        return true;
    }

    /**
     * 禅道鉴权核心方法，如果用户没有当前模块、方法的权限，则跳转到登录页面或者拒绝页面。
     * Check the user has permission to access this method, if not, locate to the login page or deny page.
     *
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        try
        {
            $module = $this->app->getModuleName();
            $method = $this->app->getMethodName();
            if($this->app->isFlow)
            {
                $module = $this->app->rawModule;
                $method = $this->app->rawMethod;
            }

            $openMethods = array(
                'user'    => array('deny', 'logout'),
                'my'      => array('changepassword'),
                'message' => array('ajaxgetmessage'),
            );

            if(!empty($this->app->user->modifyPassword) and (!isset($openMethods[$module]) or !in_array($method, $openMethods[$module]))) return helper::header('location', helper::createLink('my', 'changePassword'));
            if(!$this->loadModel('user')->isLogon() and $this->server->php_auth_user) $this->user->identifyByPhpAuth();
            if(!$this->loadModel('user')->isLogon() and $this->cookie->za) $this->user->identifyByCookie();
            if($this->isOpenMethod($module, $method)) return true;

            if(isset($this->app->user))
            {
                if($this->app->tab == 'project')
                {
                    $this->resetProjectPriv(); // 项目有继承和重新定义两种权限，在此处需要重置权限。
                    if(commonModel::hasPriv($module, $method)) return true;
                }

                $this->app->user = $this->session->user;
                if(!commonModel::hasPriv($module, $method))
                {
                    if($module == 'story' and !empty($this->app->params['storyType']) and strpos(",story,requirement,", ",{$this->app->params['storyType']},") !== false) $module = $this->app->params['storyType'];
                    $this->deny($module, $method);
                }
            }
            else
            {
                $uri = $this->app->getURI(true);
                if($module == 'message' and $method == 'ajaxgetmessage')
                {
                    $uri = helper::createLink('my');
                }
                elseif(helper::isAjaxRequest())
                {
                    helper::end(json_encode(array('result' => false, 'message' => $this->lang->error->loginTimeout))); // Fix bug #14478.
                }

                $referer = helper::safe64Encode($uri);
                helper::end(js::locate(helper::createLink('user', 'login', "referer=$referer")));
            }
        }
        catch(EndResponseException $endResponseException)
        {
            echo $endResponseException->getContent();
            helper::end();
        }
    }

    /**
     * 检查当前页面是否在 iframe 中打开，如果不是 iframe 并且不允许独立打开，则跳转到首页以 iframe 方式打开。
     * Check current page whether is in iframe. If it is not iframe and not allowed to open independently, then redirect to index to open it in iframe.
     *
     * @access public
     * @return void
     */
    public function checkIframe()
    {
        /*
         * 忽略如下情况：非 HTML 请求、Ajax 请求、特殊 GET 参数 _single。
         * Ignore the following situations: non-HTML request, Ajax request, special GET parameter _single.
         */
        if($this->app->getViewType() != 'html' || helper::isAjaxRequest() || isset($_GET['_single'])) return true;

        /**
         * 忽略无请求头 HTTP_SEC_FETCH_DEST 或者 HTTP_SEC_FETCH_DEST 为 iframe 的请求，较新的浏览器在启用 https 的情况下才会正确发送该请求头。
         * Ignore the request without HTTP_SEC_FETCH_DEST or HTTP_SEC_FETCH_DEST is iframe, the latest browser will send this request header correctly when enable https.
         */
        if(!isset($_SERVER['HTTP_SEC_FETCH_DEST']) || $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe') return true;

        /**
         * 当有 HTTP_REFERER 请求头时，忽略 safari 浏览器，因为 safari 浏览器不会正确发送 HTTP_SEC_FETCH_DEST 请求头。
         * Ignore safari browser when there is HTTP_REFERER request header, because safari browser will not send HTTP_SEC_FETCH_DEST request header correctly.
         */
        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
        {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
            if(strpos($userAgent, 'chrome') === false && strpos($userAgent, 'safari') !== false) return true;
        }

        /**
         * 忽略所有方法名以 ajax 开头的请求。
         * Ignore all requests which it's method name starts with 'ajax'.
         */
        $method = $this->app->getMethodName();
        if(strpos($method, 'ajax') === 0) return true;

        /**
         * 以下页面可以允许在非 iframe 中打开，所以要忽略这些页面。
         * The following pages can be allowed to open in non-iframe, so ignore these pages.
         */
        $module    = $this->app->getModuleName();
        $whitelist = '|index|tutorial|install|upgrade|sso|cron|misc|user-login|user-deny|user-logout|user-reset|user-forgetpassword|user-resetpassword|my-changepassword|my-preference|file-read|file-download|file-preview|file-uploadimages|file-ajaxwopifiles|report-annualdata|misc-captcha|execution-printkanban|traincourse-ajaxuploadlargefile|traincourse-playvideo|screen-view|zanode-create|screen-ajaxgetchart|';

        if(strpos($whitelist, "|{$module}|") !== false || strpos($whitelist, "|{$module}-{$method}|") !== false) return true;

        /**
         * 如果以上条件都不满足，则视为当前页面必须在 iframe 中打开，使用 302 跳转实现。
         * If none of the above conditions are missed, then the current page must be opened in iframe, using 302 jump to achieve.
         */
        $url = helper::safe64Encode($_SERVER['REQUEST_URI']);
        $redirectUrl  = helper::createLink('index', 'index');
        $redirectUrl .= strpos($redirectUrl, '?') === false ? "?open=$url" : "&open=$url";
        helper::header('location', $redirectUrl);
        return false;
    }

    /**
     * 检查用户是否有当前模块、方法的权限。
     * Check the user has permission of one method of one module.
     *
     * @param  string $module
     * @param  string $method
     * @param  mixed  $object
     * @param  string $vars
     * @static
     * @access public
     * @return bool
     */
    public static function hasPriv(string $module, string $method, mixed $object = null, string $vars = '')
    {
        global $app,$config;
        $module = strtolower($module);
        $method = strtolower($method);
        parse_str($vars, $params);

        if($config->vision == 'or' and $module == 'story') $module = 'requirement';
        if(empty($app->user)) return false;
        list($module, $method) = commonTao::getStoryModuleAndMethod($module, $method, $params);

        /* If the user is doing a tutorial, have all tutorial privileges. */
        if(commonModel::isTutorialMode())
        {
            $app->loadLang('tutorial');
            $app->loadConfig('tutorial');
            foreach($app->config->tutorial->tasksConfig as $task)
            {
                if($task['nav']['module'] == $module and $task['nav']['method'] = $method) return true;
            }
        }

        /* Check the parent object is closed. */
        if(!empty($method) and strpos('close|batchclose', $method) === false and !commonModel::canBeChanged($module, $object)) return false;

        /* Check is the super admin or not. */
        if(!empty($app->user->admin) or strpos($app->company->admins, ",{$app->user->account},") !== false) return true;

        /* Check the method is openMethod. */
        if(in_array("$module.$method", $app->config->openMethods)) return true;

        /* If is the program/project/product/execution admin, have all program privileges. */
        if($app->config->vision != 'lite' && commonTao::isProjectAdmin($module)) return true;

        /* If not super admin, check the rights. */
        $rights = $app->user->rights['rights'];
        $acls   = $app->user->rights['acls'];

        /* White list of import method. */
        $canImport = isset($rights[$module]['import']) && commonModel::hasDBPriv($object, $module, 'import');
        if(in_array($module, $app->config->importWhiteList) && $method == 'showimport' && $canImport) return true;

        if(isset($rights[$module][$method])) return commonTao::checkPrivByRights($module, $method, $acls, $object);

        return false;
    }

    /**
     * 项目有继承和重新定义两种权限，在此处需要重置权限。
     * Reset project priv.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function resetProjectPriv(int $projectID = 0)
    {
        /* Get user program priv. */
        if(empty($projectID) and $this->session->project) $projectID = $this->session->project;
        if(empty($projectID)) return;

        $program = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch();
        if(empty($program)) return;

        $programRights = $this->dao->select('t3.module, t3.method')->from(TABLE_GROUP)->alias('t1')
            ->leftJoin(TABLE_USERGROUP)->alias('t2')->on('t1.id = t2.`group`')
            ->leftJoin(TABLE_GROUPPRIV)->alias('t3')->on('t2.`group`=t3.`group`')
            ->where('t1.project')->eq($program->id)
            ->andWhere('t2.account')->eq($this->app->user->account)
            ->fetchAll();

        /* Group priv by module the same as rights. */
        $programRightGroup = array();
        foreach($programRights as $programRight) $programRightGroup[strtolower($programRight->module)][strtolower($programRight->method)] = 1;

        /* Reset priv by program privway. */
        $this->app->user = clone $_SESSION['user'];
        $rights = $this->app->user->rights['rights'];

        if($this->app->user->account == $program->openedBy or $this->app->user->account == $program->PM) $program->auth = 'extend';

        if($program->auth == 'extend') $this->app->user->rights['rights'] = array_merge_recursive($programRightGroup, $rights);
        if($program->auth == 'reset')
        {
            /* If priv way is reset, unset common program priv, and cover by program priv. */
            $projectPrivs = $this->loadModel('project')->getPrivsByModel($program->multiple ? $program->model : 'noSprint');
            foreach($projectPrivs as $module => $methods)
            {
                foreach($methods as $method => $label)
                {
                    $module = strtolower($module);
                    $method = strtolower($method);
                    if(isset($rights[$module][$method])) unset($rights[$module][$method]);
                }
            }

            $recomputedRights = array_merge($rights, $programRightGroup);

            /* Set base priv for project. */
            $projectRights = zget($this->app->user->rights['rights'], 'project', array());
            if(isset($projectRights['browse']) and !isset($recomputedRights['project']['browse'])) $recomputedRights['project']['browse'] = 1;
            if(isset($projectRights['kanban']) and !isset($recomputedRights['project']['kanban'])) $recomputedRights['project']['kanban'] = 1;
            if(isset($projectRights['index'])  and !isset($recomputedRights['project']['index']))  $recomputedRights['project']['index']  = 1;

            $this->app->user->rights['rights'] = $recomputedRights;
        }
    }

    /**
     * 受限用户只能编辑自己相关的数据。
     * Check db priv.
     *
     * @param  mixed  $object
     * @param  string $module
     * @param  string $method
     * @static
     * @access public
     * @return bool
     */
    public static function hasDBPriv(mixed $object, string $module = '', string $method = ''): bool
    {
        global $app;

        if(!empty($app->user->admin)) return true;
        if($module == 'todo' && ($method == 'create' || $method == 'batchcreate')) return true;
        if($module == 'effort' && ($method == 'batchcreate' || $method == 'createforobject')) return true;

        /* Limited execution. */
        $limitedExecution = false;
        $executionID = 0;
        if(!empty($module))
        {
            if(in_array($module, array('task', 'story')) && !empty($object->execution)) $executionID = $object->execution;
            if($module == 'execution' && !empty($object->id)) $executionID = $object->id;
        }
        if($executionID)
        {
            $limitedExecutions = !empty($_SESSION['limitedExecutions']) ? $_SESSION['limitedExecutions'] : '';
            if(strpos(",{$limitedExecutions},", ",$executionID,") !== false) $limitedExecution = true;
        }
        if(empty($app->user->rights['rights']['my']['limited']) && !$limitedExecution) return true;

        if(strpos($method, 'batch')  === 0) return false;
        if(strpos($method, 'link')   === 0) return false;
        if(strpos($method, 'create') === 0) return false;
        if(strpos($method, 'import') === 0) return false;

        if(empty($object)) return true;

        $account = $app->user->account;
        if(!empty($object->openedBy))     return $object->openedBy     == $account;
        if(!empty($object->addedBy))      return $object->addedBy      == $account;
        if(!empty($object->account))      return $object->account      == $account;
        if(!empty($object->assignedTo))   return $object->assignedTo   == $account;
        if(!empty($object->finishedBy))   return $object->finishedBy   == $account;
        if(!empty($object->canceledBy))   return $object->canceledBy   == $account;
        if(!empty($object->closedBy))     return $object->closedBy     == $account;
        if(!empty($object->lastEditedBy)) return $object->lastEditedBy == $account;

        return false;
    }

    /**
     * 检查IP是否在白名单中。
     * Check whether IP in white list.
     *
     * @param  string $ipWhiteList
     * @access public
     * @return bool
     */
    public function checkIP(string $ipWhiteList = ''): bool
    {
        $ip = helper::getRemoteIp();

        if(!$ipWhiteList) $ipWhiteList = $this->config->ipWhiteList;

        /* If the ip white list is '*'. */
        if($ipWhiteList == '*') return true;

        /* The ip is same as ip in white list. */
        if($ip == $ipWhiteList) return true;

        /* If the ip in white list is like 192.168.1.1,192.168.1.10. */
        if(strpos($ipWhiteList, ',') !== false)
        {
            $ipArr = explode(',', $ipWhiteList);
            foreach($ipArr as $ipRule)
            {
                if($this->checkIP($ipRule)) return true;
            }
            return false;
        }

        /* If the ip in white list is like 192.168.1.1-192.168.1.10. */
        if(strpos($ipWhiteList, '-') !== false)
        {
            list($min, $max) = explode('-', $ipWhiteList);
            $min = ip2long(trim($min));
            $max = ip2long(trim($max));
            $ip  = ip2long(trim($ip));

            return $ip >= $min and $ip <= $max;
        }

        /* If the ip in white list is like 192.168.1.*. */
        if(strpos($ipWhiteList, '*') !== false)
        {
            $regCount = substr_count($ipWhiteList, '.');
            if($regCount == 3)
            {
                $min = str_replace('*', '0', $ipWhiteList);
                $max = str_replace('*', '255', $ipWhiteList);
            }
            elseif($regCount == 2)
            {
                $min = str_replace('*', '0.0', $ipWhiteList);
                $max = str_replace('*', '255.255', $ipWhiteList);
            }
            elseif($regCount == 1)
            {
                $min = str_replace('*', '0.0.0', $ipWhiteList);
                $max = str_replace('*', '255.255.255', $ipWhiteList);
            }
            $min = ip2long(trim($min));
            $max = ip2long(trim($max));
            $ip  = ip2long(trim($ip));

            return ($ip >= $min and $ip <= $max);
        }

        /* If the ip in white list is in IP/CIDR format eg 127.0.0.1/24. Thanks to zcat. */
        if(strpos($ipWhiteList, '/') === false) $ipWhiteList .= '/32';
        list($ipWhiteList, $netmask) = explode('/', $ipWhiteList, 2);

        $ip          = ip2long($ip);
        $ipWhiteList = ip2long($ipWhiteList);
        $wildcard    = pow(2, (32 - $netmask)) - 1;
        $netmask     = ~ $wildcard;

        return (($ip & $netmask) == ($ipWhiteList & $netmask));
    }

    /**
     * 获取禅道的完整URL。
     * Get the full url of the system.
     *
     * @access public
     * @return string
     */
    public static function getSysURL(): string
    {
        if(defined('RUN_MODE') && RUN_MODE == 'test') return '';

        $httpType = (isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
        if(isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') $httpType = 'https';
        if(isset($_SERVER['REQUEST_SCHEME']) and strtolower($_SERVER['REQUEST_SCHEME']) == 'https') $httpType = 'https';
        $httpHost = $_SERVER['HTTP_HOST'];
        return "$httpType://$httpHost";
    }

    /**
     * 检查当前是否为新手教程模式。
     * Check whether view type is tutorial
     *
     * @access public
     * @return bool
     */
    public static function isTutorialMode(): bool
    {
        return !empty($_SESSION['tutorialMode']);
    }

    /**
     * 将数组中的值转为拼音, 以便搜索。
     * Convert items to Pinyin.
     *
     * @param  array    $items
     * @static
     * @access public
     * @return array
     */
    public static function convert2Pinyin($items): array
    {
        global $app;
        static $allConverted = array();
        static $pinyin;
        if(empty($pinyin)) $pinyin = $app->loadClass('pinyin');

        $sign = ' aNdAnD ';
        $notConvertedItems = array_diff($items, array_keys($allConverted));

        if($notConvertedItems)
        {
            $convertedPinYin = $pinyin->romanize(implode($sign, $notConvertedItems));
            $itemsPinYin     = explode(trim($sign), $convertedPinYin);
            foreach($notConvertedItems as $item)
            {
                $key        = key($itemsPinYin);
                $itemPinYin = $itemsPinYin[$key];
                unset($itemsPinYin[$key]);

                $wordsPinYin = explode("\t", trim($itemPinYin));

                $abbr = '';
                foreach($wordsPinYin as $wordPinyin)
                {
                    if($wordPinyin)
                    {
                        $letter = $wordPinyin[0];
                        if(preg_match('/\w/', $letter)) $abbr .= $letter;
                    }
                }

                $allConverted[$item] = mb_strtolower(implode('', $wordsPinYin) . ' ' . $abbr);
            }
        }

        $convertedItems = array();
        foreach($items as $item) $convertedItems[$item] = zget($allConverted, $item, null);

        return $convertedItems;
    }

    /**
     * 检查RESTful API调用是否合法。
     * Check an entry of new API.
     *
     * @access public
     * @return void
     */
    private function checkNewEntry()
    {
        $entry = $this->loadModel('entry')->getByKey(session_id());
        if(!$entry or !$entry->account or !$this->checkIP($entry->ip)) return false;

        $user = $this->dao->findByAccount($entry->account)->from(TABLE_USER)->andWhere('deleted')->eq(0)->fetch();
        if(!$user) return false;

        $user->last   = time();
        $user->rights = $this->loadModel('user')->authorize($user->account);
        $user->groups = $this->user->getGroups($user->account);
        $user->view   = $this->user->grantUserView($user->account, $user->rights['acls']);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $this->session->set('user', $user);
        $this->app->user = $user;
    }

    /**
     * 检查旧版API调用是否合法。
     * Check an entry.
     *
     * @access public
     * @return void
     */
    public function checkEntry()
    {
        /* if the API is new version, goto checkNewEntry. */
        if($this->app->version) return $this->checkNewEntry();

        /* Old version. */
        if(!isset($_GET[$this->config->moduleVar]) or !isset($_GET[$this->config->methodVar])) $this->response('EMPTY_ENTRY');
        if($this->isOpenMethod($_GET[$this->config->moduleVar], $_GET[$this->config->methodVar])) return true;

        if(!$this->get->code)  $this->response('PARAM_CODE_MISSING');
        if(!$this->get->token) $this->response('PARAM_TOKEN_MISSING');

        $entry = $this->loadModel('entry')->getByCode($this->get->code);

        if(!$entry)                         $this->response('EMPTY_ENTRY');
        if(!$entry->key)                    $this->response('EMPTY_KEY');
        if(!$this->checkIP($entry->ip))     $this->response('IP_DENIED');
        if(!$this->checkEntryToken($entry)) $this->response('INVALID_TOKEN');
        if($entry->freePasswd == 0 and empty($entry->account)) $this->response('ACCOUNT_UNBOUND');

        $isFreepasswd = ($_GET['m'] == 'user' and strtolower($_GET['f']) == 'apilogin' and $_GET['account'] and $entry->freePasswd);
        if($isFreepasswd) $entry->account = $_GET['account'];

        $user = $this->dao->findByAccount($entry->account)->from(TABLE_USER)->andWhere('deleted')->eq(0)->fetch();
        if(!$user) $this->response('INVALID_ACCOUNT');

        $this->loadModel('user');
        $user->last   = time();
        $user->rights = $this->user->authorize($user->account);
        $user->groups = $this->user->getGroups($user->account);
        $user->view   = $this->user->grantUserView($user->account, $user->rights['acls']);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $this->session->set('user', $user);
        $this->app->user = $user;

        $this->dao->update(TABLE_USER)->set('last')->eq($user->last)->set('visits=visits+1')->where('account')->eq($user->account)->exec();
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');

        if($isFreepasswd) helper::end(js::locate($this->config->webRoot));

        $this->session->set('ENTRY_CODE', $this->get->code);
        $this->session->set('VALID_ENTRY', md5(md5($this->get->code) . helper::getRemoteIp()));
        $this->loadModel('entry')->saveLog($entry->id, $this->server->request_uri);

        /* Add for task #5384. */
        if($_SERVER['REQUEST_METHOD'] == 'POST' and empty($_POST))
        {
            $post = file_get_contents("php://input");
            if(!empty($post)) $post  = json_decode($post, true);
            if(!empty($post)) $_POST = $post;
        }

        unset($_GET['code']);
        unset($_GET['token']);
    }

    /**
     * 检查Token是否合法。
     * Check token of an entry.
     *
     * @param  object $entry
     * @access public
     * @return bool
     */
    public function checkEntryToken(object $entry): bool
    {
        parse_str($this->server->query_String, $queryString);
        unset($queryString['token']);

        /* Change for task #5384. */
        if(isset($queryString['time']))
        {
            $timestamp = $queryString['time'];
            if(strlen($timestamp) > 10) $timestamp = substr($timestamp, 0, 10);
            if(strlen($timestamp) != 10 or $timestamp[0] >= '4') $this->response('ERROR_TIMESTAMP');

            $result = $this->get->token == md5($entry->code . $entry->key . $queryString['time']);
            if($result)
            {
                if($timestamp <= $entry->calledTime) $this->response('CALLED_TIME');
                $this->loadModel('entry')->updateCalledTime($entry->code, $timestamp);
                unset($_GET['time']);
                return $result;
            }
        }

        $queryString = http_build_query($queryString);
        return $this->get->token == md5(md5($queryString) . $entry->key);
    }

    /**
     * 检查当前语言项是否为中文。
     * Check Not CN Lang.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function checkNotCN(): bool
    {
        global $app;
        return strpos('|zh-cn|zh-tw|', '|' . $app->getClientLang() . '|') === false;
    }

    /**
     * 检查对象是否可被修改。
     * Check the object can be changed.
     *
     * @param  string $module
     * @param  mixed  $object
     * @static
     * @access public
     * @return bool
     */
    public static function canBeChanged(string $module, mixed $object = null): bool
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return true;

        global $app, $config;
        static $productsStatus   = array();
        static $executionsStatus = array();

        $commonModel = new commonModel();

        /* Check the product is closed. */
        if(!empty($object->product) and is_numeric($object->product) and empty($config->CRProduct))
        {
            if(!isset($productsStatus[$object->product]))
            {
                $product = $commonModel->loadModel('product')->getByID($object->product);
                $productsStatus[$object->product] = $product ? $product->status : '';
            }
            if($productsStatus[$object->product] == 'closed') return false;
        }
        elseif(!empty($object->product) and is_string($object->product) and empty($config->CRProduct))
        {
            $products      = array(0) + explode(',', $object->product);
            $products      = $commonModel->loadModel('product')->getByIdList($products);
            $productStatus = array();
            foreach($products as $product) $productStatus[$product->status] = 1;
            if(!empty($productStatus['closed']) and count($productStatus) == 1) return false;
        }

        /* Check the execution is closed. */
        $productModuleList = array('story', 'bug', 'testtask');
        if(!in_array($module, $productModuleList) and !empty($object->execution) and is_numeric($object->execution) and empty($config->CRExecution))
        {
            if(!isset($executionsStatus[$object->execution]))
            {
                $execution = $commonModel->loadModel('execution')->getByID($object->execution);
                $executionsStatus[$object->execution] = $execution ? $execution->status : '';
            }
            if($executionsStatus[$object->execution] == 'closed') return false;
        }

        return true;
    }

    /**
     * Check object can modify.
     *
     * @param  string      $type    product|Execution
     * @param  object|null $object
     * @static
     * @access public
     * @return bool
     */
    public static function canModify(string $type, object|null $object): bool
    {
        global $config;

        if(empty($object)) return true;

        /* Judge that if the closed object(product|execution) is readonly from config table. The default is can modify. */
        if($type == 'product'   and empty($config->CRProduct)   and $object->status == 'closed') return false;
        if($type == 'execution' and empty($config->CRExecution) and $object->status == 'closed') return false;

        return true;
    }

    /**
     * Response.
     *
     * @param  string $reasonPhrase
     * @access public
     * @return void
     */
    public function response($reasonPhrase)
    {
        $response = new stdclass();
        if(isset($this->config->entry->errcode))
        {
            $response->errcode = $this->config->entry->errcode[$reasonPhrase];
            $response->errmsg  = urlencode($this->lang->entry->errmsg[$reasonPhrase]);

            helper::end(urldecode(json_encode($response)));
        }
        else
        {
            $response->error = $reasonPhrase;
            helper::end(urldecode(json_encode($response)));
        }
    }

    /**
     * Http.
     *
     * @param  string              $url
     * @param  string|array|object $data
     * @param  array               $options   This is option and value pair, like CURLOPT_HEADER => true. Use curl_setopt function to set options.
     * @param  array               $headers   Set request headers.
     * @param  string              $dataType
     * @param  string              $method    POST|PATCH|PUT
     * @param  int                 $timeout
     * @param  bool                $httpCode  Return a array contains response, http code, body, header. such as [response, http_code, 'body' => body, 'header' => header].
     * @param  bool                $log       Save to log or not
     * @static
     * @access public
     * @return string|array
     */
    public static function http(string $url, string|array|object|null $data = null, array $options = array(), array $headers = array(), string $dataType = 'data', string $method = 'POST', int $timeout = 30, bool $httpCode = false, bool $log = true): string|array
    {
        global $lang, $app;
        if(!extension_loaded('curl'))
        {
             if($dataType == 'json') return print($lang->error->noCurlExt);
             return json_encode(array('result' => 'fail', 'message' => $lang->error->noCurlExt));
        }

        commonModel::$requestErrors = array();

        $requestType = 'GET';
        if(!is_array($headers)) $headers = (array)$headers;

        $headers[] = 'API-RemoteIP: ' . helper::getRemoteIp(); /* Real IP of real user. */
        $headers[] = 'API-LocalIP: ' . zget($_SERVER, 'SERVER_ADDR', ''); /* Server IP of self. */

        if($dataType == 'json')
        {
            $headers[] = 'Content-Type: application/json;charset=utf-8';
            if(!empty($data)) $data = json_encode($data);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_USERAGENT, 'ZenTao PMS ' . $app->config->version);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING,'');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($curl, CURLOPT_HEADER, $httpCode);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 2);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);

        if(!empty($data))
        {
            if(is_object($data)) $data = (array) $data;
            if($method == 'POST') curl_setopt($curl, CURLOPT_POST, true);
            if(in_array($method, array('PATCH', 'PUT'))) curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            $requestType = $method;
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        if($options) curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        $errno  = curl_errno($curl);
        $errors = empty($errno)  ? 0 : curl_error($curl);
        $info   = curl_getinfo($curl);

        if($httpCode)
        {
            $httpCode     = $info['http_code'] ?? curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $headerSize   = $info['header_size'] ?? curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $headerString = substr($response, 0, $headerSize);
            $body         = substr($response, $headerSize);

            /* Parse header. */
            $header    = explode("\n", $headerString);
            $newHeader = array();
            foreach($header as $item)
            {
                $field = explode(':', $item);
                if(count($field) < 2) continue;
                $headerKey = array_shift($field);
                $newHeader[$headerKey] = implode('', $field);
            }
        }

        curl_close($curl);

        if($log or $app->config->debug)
        {
            $runMode = PHP_SAPI == 'cli' ? '_cli' : '';
            $logFile = $app->getLogRoot() . 'saas' . $runMode . '.' . date('Ymd') . '.log.php';
            if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');

            $saasLog  = date('Ymd H:i:s') . ': ' . $app->getURI() . "\n";
            $saasLog .= "{$requestType} url:    {$url}\n";
            if(!empty($data)) $saasLog .= 'data:   ' . print_r($data, true) . "\n";
            $saasLog .= 'results:' . print_r($response, true) . "\n";

            if(!empty($errors))
            {
                $saasLog .= "errno: {$errno}\n";
                $saasLog .= "errors: {$errors}\n";
                $saasLog .= 'info: ' . print_r($info, true) . "\n";
            }

            file_put_contents($logFile, $saasLog, FILE_APPEND);
        }

        if($errors) commonModel::$requestErrors[] = $errors;

        if(!$response) $response = '';
        return $httpCode ? array($response, $httpCode, 'body' => $body, 'header' => $newHeader, 'errno' => $errno, 'info' => $info, 'response' => $response) : $response;
    }

    /**
     * 设置要展示的顶部一级菜单。
     * Set main menu.
     *
     * @static
     * @access public
     * @return void
     */
    public static function setMainMenu()
    {
        global $app, $lang;
        $tab = $app->tab;

        $isTutorialMode = common::isTutorialMode();
        $currentModule  = $isTutorialMode ? $app->moduleName : $app->rawModule;
        $currentMethod  = $isTutorialMode ? $app->methodName : $app->rawMethod;
        $currentMethod  = strtolower($currentMethod);

        /* If homeMenu is not exists or unset, display menu. */
        $lang->menu      = isset($lang->$tab->menu) ? $lang->$tab->menu : array();
        $lang->menuOrder = isset($lang->$tab->menuOrder) ? $lang->$tab->menuOrder : array();

        if(!isset($lang->$tab->homeMenu)) return;

        if($currentModule == $tab and $currentMethod == 'create')
        {
            $lang->menu = $lang->$tab->homeMenu;
            return;
        }

        /* If the method is in homeMenu, display homeMenu. */
        foreach($lang->$tab->homeMenu as $menu)
        {
            $link   = is_array($menu) ? $menu['link'] : $menu;
            $params = explode('|', $link);
            $method = strtolower($params[2]);

            if($method == $currentMethod)
            {
                $lang->menu = $lang->$tab->homeMenu;
                return;
            }

            $alias   = isset($menu['alias'])   ? explode(',', strtolower($menu['alias']))   : array();
            $exclude = isset($menu['exclude']) ? explode(',', strtolower($menu['exclude'])) : array();
            if(in_array($currentMethod, $alias) && !in_array("{$currentModule}-{$currentMethod}", $exclude))
            {
                $lang->menu = $lang->$tab->homeMenu;
                return;
            }

            if(isset($menu['subModule']) and strpos(",{$menu['subModule']},", ",$currentModule,") !== false)
            {
                $lang->menu = $lang->$tab->homeMenu;
                return;
            }
        }
    }

    /**
     * 获取两种对象的关联关系。
     * Get relations for two object.
     *
     * @param  string  $AType
     * @param  int     $AID
     * @param  string  $BType
     * @param  int     $BID
     *
     * @access public
     * @return array
     */
    public function getRelations(string $AType = '', int $AID = 0, string $BType = '', int $BID = 0): array
    {
        return $this->dao->select('*')->from(TABLE_RELATION)
            ->where('AType')->eq($AType)
            ->andWhere('AID')->eq($AID)
            ->andwhere('BType')->eq($BType)
            ->beginIF($BID)->andwhere('BID')->eq($BID)->fi()
            ->fetchAll();
    }

    /**
     * 将导航中的占位符替换为实际的值。
     * Replace the %s of one key of a menu by objectID or $params.
     *
     * All the menus are defined in the common's language file. But there're many dynamic params, so in the defination,
     * we used %s as placeholder. These %s should be setted in one module.
     *
     * @param  string  $moduleName
     * @param  int     $objectID
     * @param  array   $params
     *
     * @access public
     * @return void
     */
    public static function setMenuVars(string $moduleName, int $objectID, array $params = array())
    {
        global $app, $lang;

        $menuKey = 'menu';
        if($app->viewType == 'mhtml') $menuKey = 'webMenu';

        foreach($lang->$moduleName->$menuKey as $label => $menu)
        {
            $lang->$moduleName->$menuKey->$label = static::setMenuVarsEx($menu, $objectID, $params);
            if(isset($menu['subMenu']))
            {
                foreach($menu['subMenu'] as $key1 => $subMenu)
                {
                    $lang->$moduleName->$menuKey->{$label}['subMenu']->$key1 = static::setMenuVarsEx($subMenu, $objectID, $params);
                }
            }

            if(!isset($menu['dropMenu'])) continue;

            foreach($menu['dropMenu'] as $key2 => $dropMenu)
            {
                $lang->$moduleName->$menuKey->{$label}['dropMenu']->$key2 = static::setMenuVarsEx($dropMenu, $objectID, $params);

                if(!isset($dropMenu['subMenu'])) continue;

                foreach($dropMenu['subMenu'] as $key3 => $subMenu)
                {
                    $lang->$moduleName->$menuKey->{$label}['dropMenu']->$key3 = static::setMenuVarsEx($subMenu, $objectID, $params);
                }
            }
        }

        /* If objectID is set, cannot use homeMenu. */
        unset($lang->$moduleName->homeMenu);
    }

    /**
     * 检查菜单中的占位符是否已经替换，如果没有，则替换。
     * Check if the menu vars replaced, if not, replace them.
     *
     * @static
     * @access public
     * @return void
     */
    public static function checkMenuVarsReplaced()
    {
        global $app, $lang;

        $tab          = $app->tab;
        $varsReplaced = true;
        foreach($lang->menu as $menu)
        {
            if(isset($menu['link']) and strpos($menu['link'], '%s') !== false) $varsReplaced = false;
            if(!isset($menu['link']) and is_string($menu) and strpos($menu, '%s') !== false) $varsReplaced = false;
            if(!$varsReplaced) break;
        }

        if(!$varsReplaced and strpos("|program|product|project|execution|qa|safe|", "|{$tab}|") !== false)
        {
            $isTutorialMode = common::isTutorialMode();
            $currentModule  = $isTutorialMode ? $app->moduleName : $app->rawModule;

            if(isset($lang->$currentModule->menu))
            {
                $lang->menu      = isset($lang->$currentModule->menu) ? $lang->$currentModule->menu : array();
                $lang->menuOrder = isset($lang->$currentModule->menuOrder) ? $lang->$currentModule->menuOrder : array();
                $app->tab        = zget($lang->navGroup, $currentModule);
            }
            else
            {
                static::setMenuVars($tab, (int)$app->session->$tab);
            }
        }
    }

    /**
     * 将导航中的占位符替换为实际的值。
     * Replace the %s of one key of a menu by objectID or $params.
     *
     * @param  array|string $menu
     * @param  int          $objectID
     * @param  array|string $params
     */
    public static function setMenuVarsEx(array|string $menu, int $objectID, array $params = array()): array|string
    {
        if(is_array($menu))
        {
            if(!isset($menu['link'])) return $menu;

            $link = sprintf($menu['link'], $objectID);
            $menu['link'] = vsprintf($link, $params);
        }
        else
        {
            $menu = sprintf($menu, $objectID);
            $menu = vsprintf($menu, $params);
        }

        return $menu;
    }

    /**
     * Process markdown.
     *
     * @param  string  $markdown
     * @static
     * @access public
     * @return string
     */
    public static function processMarkdown(string $markdown): string
    {
        if(empty($markdown)) return false;

        global $app;
        $app->loadClass('parsedown');

        $parsedown = new parsedown;

        $parsedown->voidElementSuffix = '>'; // HTML5

        return $parsedown->text($markdown);
    }

    /**
     * 排序FeatureBar。
     * Sort featureBar.
     *
     * @param  string $module
     * @param  string $method
     * @static
     * @access public
     * @return bool
     */
    public static function sortFeatureMenu(string $module = '', string $method = ''): bool
    {
        global $lang, $config, $app;

        $module = $module ? $module : $app->rawModule;
        $method = $method ? $method : $app->rawMethod;

        /* It will be sorted according to the workflow in the future */
        if(!empty($config->featureBarSort[$module][$method]))
        {
            $featureBar = array();
            if(empty($lang->$module->featureBar[$method])) return false;
            foreach($lang->$module->featureBar[$method] as $key => $label)
            {
                foreach($config->featureBarSort[$module][$method] as $currentKey => $afterKey)
                {
                    if($key == $currentKey) continue;
                    $featureBar[$method][$key] = $label;
                    if($key == $afterKey && !empty($lang->$module->featureBar[$method][$currentKey]))
                    {
                        $featureBar[$method][$currentKey] = $lang->$module->featureBar[$method][$currentKey];
                    }
                }
            }
            $lang->$module->featureBar = $featureBar;
        }

        return true;
    }

    /**
     * Get method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $headers example: array("key1:value1", "key2:value2")
     * @access public
     * @return object
     */
    public static function apiGet(string $url, array|object $data = array(), array $headers = array())
    {
        $url .= (strpos($url, '?') !== false ? '&' : '?') . http_build_query($data, '', '&', PHP_QUERY_RFC3986);

        $result = json_decode(commonModel::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'GET'), $headers, 'json'));

        if($result && $result->code == 200) return $result;

        return static::apiError($result);
    }

    /**
     * Post method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $headers example: array("key1:value1", "key2:value2")
     * @access public
     * @return object
     */
    public static function apiPost(string $url, array|object $data, array $headers = array()): object
    {
        $result     = json_decode(commonModel::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'POST'), $headers, 'json'));
        if($result && $result->code == 200) return $result;

        return static::apiError($result);
    }

    /**
     * Put method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $headers example: array("key1:value1", "key2:value2")
     * @access public
     * @return object
     */
    public static function apiPut(string $url, array|object $data, array $headers = array()): object
    {
        $result     = json_decode(commonModel::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'PUT'), $headers, 'json'));
        if($result && $result->code == 200) return $result;

        return static::apiError($result);
    }

    /**
     * Delete method of API.
     *
     * @param  string       $url
     * @param  array|object $data
     * @param  array        $headers example: array("key1:value1", "key2:value2")
     * @access public
     * @return object
     */
    public static function apiDelete(string $url, array|object $data, array $headers = array()): object
    {
        $result     = json_decode(commonModel::http($url, $data, array(CURLOPT_CUSTOMREQUEST => 'DELETE'), $headers, 'json'));
        if($result && $result->code == 200) return $result;

        return static::apiError($result);
    }

    /**
     * Return error object of api server.
     *
     * @param  object|null $result
     * @access protected
     * @return object
     */
    protected static function apiError(object|null $result): object
    {
        global $lang;

        if($result && $result->code) return $result;

        $error = new stdclass;
        $error->code    = 600;
        $error->message = $lang->error->httpServerError;
        return $error;
    }

    /**
     * 构建详情操作链接配置。
     * Build action item.
     *
     * @param  string    $module
     * @param  string    $method
     * @param  string    $params
     * @param  object    $object
     * @param  array  $attrs
     * @static
     * @access public
     * @return void
     */
    public static function buildActionItem(string $module, string $method, string $params, object|null $object = null, array $attrs = array()): array
    {
        if(!commonModel::hasPriv($module, $method, $object)) return array();

        global $app;
        $clickable = true;
        if(is_object($object))
        {
            if($app->getModuleName() != $module) $app->control->loadModel($module);
            $modelClass = class_exists("ext{$module}Model") ? "ext{$module}Model" : $module . "Model";
            if(class_exists($modelClass) and method_exists($modelClass, 'isClickable')) $clickable = call_user_func_array(array($modelClass, 'isClickable'), array($object, $method));
        }
        if(!$clickable) return array();

        $item['url'] = helper::createLink($module, $method, $params);
        foreach($attrs as $attr => $value) $item[$attr] = $value;
        return $item;
    }

    /**
     * 按照模块生成详情页的操作按钮。
     * Build operate actions menu.
     *
     * @param  object $data
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $data): array
    {
        global $app, $config;

        /* build operate menu. */
        $moduleName  = $app->moduleName;
        $methodName  = $app->methodName;
        $actionsMenu = array();

        $this->loadModel($moduleName);
        foreach($config->{$moduleName}->actions->{$methodName} as $menu => $actionList)
        {
            $actions = array();
            foreach($actionList as $action)
            {
                $actionData = $config->{$moduleName}->actionList[$action];

                if(!empty($actionData['url']) && is_array($actionData['url']))
                {
                    $module = $actionData['url']['module'];
                    $method = $actionData['url']['method'];
                    $params = $actionData['url']['params'];
                    if(!common::hasPriv($module, $method)) continue;
                    $actionData['url'] = helper::createLink($module, $method, $params);
                }
                else if(!empty($actionData['data-url']) && is_array($actionData['data-url']))
                {
                    $module = $actionData['data-url']['module'];
                    $method = $actionData['data-url']['method'];
                    $params = $actionData['data-url']['params'];
                    if(!common::hasPriv($module, $method)) continue;
                    $actionData['data-url'] = helper::createLink($module, $method, $params);
                }
                else
                {
                    if(!common::hasPriv($moduleName, $action)) continue;
                }

                if(method_exists($this->{$moduleName}, 'isClickable') && false === $this->{$moduleName}->isClickable($data, $action)) continue;

                if($menu == 'suffixActions' && !empty($actionData['text']) && empty($actionData['showText'])) $actionData['text'] = '';

                $actions[] = $actionData;
            }
            $actionsMenu[$menu] = $actions;
        }
        return $actionsMenu;
    }

    /**
     * 计算应用运行时间。
     * Print duration of a instance.
     *
     * @param  int    $seconds
     * @param  string $format  y-m-d-h-i-s, case insensitive
     * @static
     * @access public
     * @return string
     */
    public static function printDuration(int $seconds, string $format = 'y-m-d-h-i-s'): string
    {
        global $lang;

        $duration = '';
        $format = strtolower($format);

        if(strpos($format, 'y') !== false)
        {
            $years       = intval($seconds / (3600 * 24 * 365));
            $leftSeconds = intval($seconds % (3600 * 24 * 365));
            if($years) $duration .= $years . $lang->year;
        }

        if(strpos($format, 'm') !== false)
        {
            $months      = intval($leftSeconds / (3600 * 24 * 30));
            $leftSeconds = intval($leftSeconds % (3600 * 24 * 30));
            if($months) $duration .= $months . $lang->month;
        }

        if(strpos($format, 'd') !== false)
        {
            $days        = intval($leftSeconds / (3600 * 24));
            $leftSeconds = intval($leftSeconds % (3600 * 24));
            if($days) $duration .= $days . trim($lang->day);
        }

        if(strpos($format, 'h') !== false)
        {
            $hours = intval($leftSeconds / 3600);
            $leftSeconds = intval($leftSeconds % 3600);
            if($hours) $duration .= $hours . $lang->hour;
        }

        if(strpos($format, 'i') !== false)
        {
            $minutes = intval($leftSeconds / 60);
            $leftSeconds = intval($leftSeconds % 60);
            if($minutes) $duration .= $minutes . $lang->minute;
        }

        if(strpos($format, 's') !== false)
        {
            $seconds = intval($leftSeconds % 3600);
            if($seconds) $duration .= $seconds . $lang->second;
        }

        return $duration;
    }

    /**
     * Check object priv.
     *
     * @param  string   $objectType program|project|product|execution
     * @param  int      $objectID
     * @access public
     * @return bool
     */
    public function checkPrivByObject(string $objectType, int $objectID): bool
    {
        $objectType = strtolower($objectType);
        if(in_array($objectType, array('program', 'project', 'product', 'execution'))) return $this->loadModel($objectType)->checkPriv($objectID);

        return false;
    }

}

class common extends commonModel
{
}
