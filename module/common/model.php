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
 * @property    router $app
 */
class commonModel extends model
{
    /**
     * 网络请求客户端。
     * HTTP client.
     *
     * @var object
     * @access public
     */
    public static $httpClient;

    /**
     * 网络请求错误。
     * Request errors.
     *
     * @var array
     * @access public
     */
    public static $requestErrors = array();

    /**
     * 缓存用户是否有某个模块、方法的访问权限。
     * Cache the user's access rights to a module or method.
     *
     * @var array
     * @access public
     */
    public static $userPrivs = array();

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

        if($rawModule == 'marketresearch' and strpos($rawMethod, 'stage') !== false) $rawModule = 'execution';

        if($rawModule == 'task' || $rawModule == 'effort' || $rawModule == 'researchtask')
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
            $realBegan = helper::isZeroDate($project->realBegan) ? $this->dao->select('realBegan')->from(TABLE_PROJECT)->where('id')->eq($execution->id)->fetch('realBegan') : $project->realBegan;
            $this->dao->update(TABLE_PROJECT)
                 ->set('status')->eq('doing')
                 ->set('realBegan')->eq($realBegan)
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
        if(strpos(',waterfall,waterfallplus,ipd,research,', ",$project->model,") !== false) $this->loadModel('programplan')->computeProgress($execution->id);

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

        if(!empty($this->config->xFrameOptions)) helper::header('X-Frame-Options', $this->config->xFrameOptions);
        if($this->loadModel('setting')->getItem('owner=system&module=sso&key=turnon'))
        {
            if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == 'on')
            {
                $session = $this->config->sessionVar . '=' . session_id();
                helper::header('Set-Cookie', "$session; SameSite=None; Secure=true", false);
            }
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
        $this->config->openedApproval = in_array($this->config->edition, array('biz', 'max', 'ipd')) && $this->config->vision != 'lite';
    }

    /**
     * 获取表单的配置项。
     * Obtain the config for the form.
     *
     * @param  string $module
     * @param  string $method
     * @param  int    $objectID
     * @static
     * @access public
     * @return array
     */
    public static function formConfig(string $module, string $method, int $objectID = 0): array
    {
        global $config, $app;
        if($config->edition == 'open') return array();

        $formConfig = array();
        $common     = new self();
        $required   = $common->dao->select('*')->from(TABLE_WORKFLOWRULE)->where('type')->eq('system')->andWhere('rule')->eq('notempty')->fetch();
        $fields     = $common->loadModel('flow')->getExtendFields($module, $method, $objectID);

        $type       = 'string';
        foreach($fields as $fieldObject)
        {
            if(strpos($fieldObject->type, 'int')  !== false)            $type = 'int';
            if(strpos($fieldObject->type, 'date') !== false)            $type = 'date';
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
        $this->config->systemDB   = isset($config['system']) ? $config['system'] : array();
        $this->config->personalDB = isset($config[$account]) ? $config[$account] : array();

        /* Web root cannot be changed by api request. */
        if(!$this->app->apiVersion)
        {
            $this->commonTao->updateDBWebRoot($this->config->systemDB);
        }

        /* Override the items defined in config/config.php and config/my.php. */
        if(isset($this->config->systemDB->common))   $this->app->mergeConfig($this->config->systemDB->common, 'common');
        if(isset($this->config->personalDB->common)) $this->app->mergeConfig($this->config->personalDB->common, 'common');

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

        if($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))
        {
            if(in_array("$module.$method", $this->config->logonMethods)) return true;

            if(stripos($method, 'ajax') !== false) return true;
            if($module == 'block' && stripos(',dashboard,printblock,create,edit,delete,close,reset,layout,', ",{$method},") !== false) return true;
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
        if($reload && $this->loadModel('user')->isLogon())
        {
            /* Get authorize again. */
            $user = $this->app->user;
            $user->rights = $this->user->authorize($user->account);
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

        if(helper::isAjaxRequest())
        {
            $isModal = helper::isAjaxRequest('modal');
            if($isModal) header("Location: $denyLink");
            if(!$isModal) echo json_encode(array('load' => $denyLink));
        }
        else
        {
            header("Location: $denyLink");
        }


        helper::end();
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
     * 检查字符串是否以给定的子字符串结尾。
     * Checks if a string ends with a given substring.
     *
     * @param string $haystack
     * @param string $needle
     * @return bool
     */
    public static function strEndsWith(string $haystack, string $needle): bool
    {
        $length = strlen($needle);
        if ($length === 0) return true;

        $position = strpos($haystack, $needle);
        return $position !== false && $position === strlen($haystack) - $length;
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
     * @param  bool   $useDefault 是否使用语言项中的默认值
     *
     * @static
     * @access public
     * @return array
     */
    public static function getMainNavList(string $moduleName, bool $useDefault = false): array
    {
        global $lang, $app, $config;

        $app->loadLang('my');
        if($config->edition != 'open' && $app->isServing()) $app->control->loadModel('common')->mergePrimaryFlows();

        /* Ensure user has latest rights set. */
        $app->user->rights = $app->control->loadModel('user')->authorize($app->user->account);

        $menuOrder     = $lang->mainNav->menuOrder;
        $hasCustomMenu = false;
        if(isset($config->customMenu->nav) && !$useDefault && !commonModel::isTutorialMode())
        {
            $customMenuOrder = array();
            $items           = json_decode($config->customMenu->nav);
            $hiddenItems     = array();
            foreach($items as $item)
            {
                if(!empty($item->hidden))
                {
                    $hiddenItems[] = $item->name;
                    continue;
                }

                $customMenuOrder[$item->order] = $item->name;
            }

            $customMenuItems = array_values($customMenuOrder);
            foreach($menuOrder as $order => $name)
            {
                if(in_array($name, $customMenuItems) || in_array($name, $hiddenItems)) continue;

                while(isset($customMenuOrder[$order])) $order ++;
                $customMenuOrder[$order] = $name;
            }

            $menuOrder     = $customMenuOrder;
            $hasCustomMenu = true;
        }

        ksort($menuOrder);

        $items        = array();
        $lastItem     = end($menuOrder);
        $printDivider = false;
        $prev         = '';

        foreach($menuOrder as $key => $group)
        {
            // 如果有自定义菜单，则直接用自定义后的divider分隔符
            if($hasCustomMenu && $group == 'divider' && $prev != 'divider')
            {
                $items[] = 'divider';
                $prev    = 'divider';
                continue;
            }

            $prev = $group;

            if($group != 'my' && !$app->user->admin && !empty($app->user->rights['acls']['views']) && !isset($app->user->rights['acls']['views'][$group])) continue; // 后台权限分组中没有给导航视图
            if(!isset($lang->mainNav->$group)) continue;

            $nav = $lang->mainNav->$group;
            list($title, $currentModule, $currentMethod, $vars) = explode('|', $nav);

            // 没有自定义过菜单，用默认语言项中的divider分隔符
            if(!$hasCustomMenu)
            {
                /* When last divider is not used in mainNav, use it next menu. */
                $printDivider = ($printDivider or ($lastItem != $key) and strpos($lang->dividerMenu, ",{$group},") !== false) ? true : false;
                if($printDivider and !empty($items))
                {
                    $items[]      = 'divider';
                    $printDivider = false;
                }
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

        // 如果最后一个是分割线，则删除
        while(!empty($items) && end($items) === 'divider') { array_pop($items); }
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
                            $subAlias   = zget($dropMenuItem, 'alias', '');
                            if($subModule and in_array($currentModule, $subModule) and strpos(",$subExclude,", ",$currentModule-$currentMethod,") === false) $activeMainMenu = true;
                            if(strpos(",$subAlias,", ",$currentModule-$currentMethod,") !== false) $activeMainMenu = true;
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
        global $app, $config, $dao;

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
                $field = $dao->select('options')->from(TABLE_WORKFLOWFIELD)->where('`module`')->eq($moduleName)->andWhere('`field`')->eq('subStatus')->fetch();
                if(!empty($field->options)) $field->options = json_decode($field->options, true);

                if(!empty($field->options[$newStatus]['default']))
                {
                    $flow    = $dao->select('`table`')->from(TABLE_WORKFLOW)->where('`module`')->eq($moduleName)->fetch();
                    $default = $field->options[$newStatus]['default'];

                    $common = new self();
                    $common->dao->update($flow->table)->set('subStatus')->eq($default)->where('id')->eq($oldID)->exec();

                    $new->subStatus = $default;
                }
            }
            $dateFields = array();
            $rows       = $dao->select('`field`')->from(TABLE_WORKFLOWFIELD)->where('`module`')->eq($moduleName)->andWhere('`control`')->in(array('data', 'datetime'))->fetchAll();
            foreach($rows as $row) $dateFields[$row->field] = $row->field;
        }

        $changes = array();
        foreach($new as $key => $value)
        {
            if($key == 'addedFiles')
            {
                foreach($value as $addedFile) $changes[] = array('field' => 'addDiff', 'old' => '', 'new' => '', 'diff' => $addedFile);
            }
            elseif($key == 'deleteFiles')
            {
                foreach($value as $deleteFile) $changes[] = array('field' => 'removeDiff', 'old' => '', 'new' => '', 'diff' => $deleteFile);
            }
            elseif($key == 'renameFiles')
            {
                foreach($value as $renameFile) $changes[] = array('field' => 'fileName', 'old' => $renameFile['old'], 'new' => $renameFile['new'], 'diff' => '');
            }

            if(is_object($value) || is_array($value)) continue;

            $check = strtolower($key);
            if(in_array($check, array('lastediteddate', 'lasteditedby', 'assigneddate', 'editedby', 'editeddate', 'editingdate', 'uid', 'prevstatus', 'prevassignedto'))) continue;
            if(in_array($check, array('finisheddate', 'canceleddate', 'hangupeddate', 'lastcheckeddate', 'activateddate', 'closeddate', 'actualcloseddate')) && $value == '') continue;

            if(isset($old->$key) && !is_object($old->$key) && !is_array($old->$key))
            {
                if($config->edition != 'open' && isset($dateFields[$key])) $old->$key = formatTime($old->$key);

                if($value != stripslashes((string)$old->$key))
                {
                    $diff       = '';
                    $showDiff   = (substr_count((string)$value, "\n") > 1 || substr_count((string)$old->$key, "\n") > 1 || strpos(',name,title,desc,spec,steps,content,digest,verify,report,definition,analysis,summary,prevention,resolution,outline,schedule,minutes,sql,interface,ui,langs,performance,privileges,search,actions,deploy,bi,safe,other,', ',' . strtolower($key) . ',') !== false);
                    $hiddenDiff = array('bug' => 'resolution');
                    if($showDiff && (!isset($hiddenDiff[$moduleName]) || (isset($hiddenDiff[$moduleName]) && strpos(",$hiddenDiff[$moduleName],", ",$key,") === false)))
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
            $queryCondition = substr($sql, strpos($sql, ' WHERE ') + 7);
            if($queryCondition)
            {
                if(strpos($queryCondition, ' ORDER BY') !== false) $queryCondition = substr($queryCondition, 0, strpos($queryCondition, ' ORDER BY '));
                $queryCondition = str_replace('t1.', '', $queryCondition);
            }
        }
        else
        {
            $queryCondition = strpos($sql, ' ORDER BY') !== false ? substr($sql, 0, strpos($sql, ' ORDER BY ')) : $sql;
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
        if($this->config->inContainer) return false;

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
     * 检查系统是否处于维护状态，如果处于维护状态则给非管理员用户输出提示。
     * Check whether system in maintenance status and show message for non-admin user.
     *
     * @return void
     */
    public function checkMaintenance()
    {
        $maintenance = $this->loadModel('setting')->getItem('owner=system&module=system&key=maintenance');
        if(empty($maintenance)) return true;

        $maintenance = json_decode($maintenance);
        if($this->app->moduleName == 'user' && $this->app->methodName == 'login') return true;
        if(!empty($this->app->user->admin)) return true;

        if(isset($maintenance->action) && in_array($maintenance->action, array('upgrade', 'downgrade', 'restore'))) helper::setStatus(503);

        $reason  = sprintf($this->lang->maintainReason, isset($maintenance->reason) ? $maintenance->reason : $this->lang->unknown);
        $message = <<<eof
<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>
<table align='center' style='margin-top:100px; border:1px solid gray; font-size:14px;padding:8px;'><tr><td>
<h4>{$this->lang->inMaintenance}</h4>
<p>{$reason}{$this->lang->systemMaintainer}</p>
</td></tr></table></body></html>'
eof;
        helper::end($message);
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

            if($module == 'product' and $method == 'browse' and !empty($this->app->params['storyType']) and $this->app->params['storyType'] != 'story') $method = $this->app->params['storyType'];
            if($module == 'productplan' && ($method == 'story' || $method == 'bug')) $method = 'view';
            if($module == 'doc' && $method == 'edittemplate') $method = 'createtemplate';

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
                    if($module === 'feedback' && stripos(',view,adminview,', ",$method,") !== false && ($method === 'view' && commonModel::hasPriv('feedback', 'adminview') || $method === 'adminview' && commonModel::hasPriv('feedback', 'view'))) return true; // Make both feedback view and adminview privs interchangeable.

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
     * @param  string $whitelist
     * @return void
     */
    public function checkIframe(?string $whitelist = null)
    {
        /*
         * 忽略如下情况：非 HTML 请求、Ajax 请求、特殊 GET 参数 _single。
         * Ignore the following situations: non-HTML request, Ajax request, special GET parameter _single.
         */
        if($this->app->getViewType() != 'html' || helper::isAjaxRequest() || isset($_GET['_single'])) return;

        /**
         * 忽略无请求头 HTTP_SEC_FETCH_DEST 或者 HTTP_SEC_FETCH_DEST 为 iframe 的请求，较新的浏览器在启用 https 的情况下才会正确发送该请求头。
         * Ignore the request without HTTP_SEC_FETCH_DEST or HTTP_SEC_FETCH_DEST is iframe, the latest browser will send this request header correctly when enable https.
         */
        if(!isset($_SERVER['HTTP_SEC_FETCH_DEST']) || $_SERVER['HTTP_SEC_FETCH_DEST'] == 'iframe') return;

        /**
         * 当有 HTTP_REFERER 请求头时，忽略 safari 浏览器，因为 safari 浏览器不会正确发送 HTTP_SEC_FETCH_DEST 请求头。
         * Ignore safari browser when there is HTTP_REFERER request header, because safari browser will not send HTTP_SEC_FETCH_DEST request header correctly.
         */
        if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']))
        {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
            if(strpos($userAgent, 'chrome') === false && strpos($userAgent, 'safari') !== false) return;
        }

        /**
         * 忽略所有方法名以 ajax 开头的请求。
         * Ignore all requests which it's method name starts with 'ajax'.
         */
        $method = $this->app->getMethodName();
        if(strpos($method, 'ajax') === 0) return;

        /**
         * 以下页面可以允许在非 iframe 中打开，所以要忽略这些页面。
         * The following pages can be allowed to open in non-iframe, so ignore these pages.
         */
        $module     = $this->app->getModuleName();
        $whitelist  = is_string($whitelist) ? $whitelist : '|index|tutorial|install|upgrade|sso|cron|misc|user-login|user-deny|user-logout|user-reset|user-forgetpassword|user-resetpassword|my-changepassword|my-preference|file-read|file-download|file-preview|file-uploadimages|file-ajaxwopifiles|report-annualdata|misc-captcha|execution-printkanban|traincourse-ajaxuploadlargefile|traincourse-playvideo|screen-view|zanode-create|screen-ajaxgetchart|ai-chat|integration-wopi|instance-terminal|conference-getconferencepermissions|instance-logs|';
        $iframeList = '|cron-index|zanode-create|';

        if(strpos($iframeList, "|{$module}-{$method}|") === false && (strpos($whitelist, "|{$module}|") !== false || strpos($whitelist, "|{$module}-{$method}|") !== false)) return;

        /**
         * 如果以上条件都不满足，则视为当前页面必须在 iframe 中打开，使用 302 跳转实现。
         * If none of the above conditions are missed, then the current page must be opened in iframe, using 302 jump to achieve.
         */
        $url = helper::safe64Encode($_SERVER['REQUEST_URI']);
        $redirectUrl  = helper::createLink('index', 'index');
        $redirectUrl .= strpos($redirectUrl, '?') === false ? "?open=$url" : "&open=$url";
        helper::header('location', $redirectUrl);
        helper::end();
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
    public static function hasPriv(string $module, string $method, $object = null, string $vars = '')
    {
        /* If the user is doing a tutorial, have all privileges. */
        if(commonModel::isTutorialMode()) return true;

        global $app;
        if(empty($app->user->account)) return false;

        $module = strtolower($module);
        $method = strtolower($method);

        if($module == 'product' and $method == 'browse' and !empty($app->params['storyType']) and $app->params['storyType'] != 'story') $method = $app->params['storyType'];

        global $config;
        if(isset($config->{$module}->groupPrivs[$method]))
        {
            $groupPriv = strtolower($config->{$module}->groupPrivs[$method]);
            if(strpos($groupPriv, '|') !== false) list($module, $groupPriv) = explode('|', $groupPriv);
            if($groupPriv && $groupPriv != $method)
            {
                if($object) return static::getUserPriv($module, $groupPriv, $object, $vars);
                return static::hasPriv($module, $groupPriv, $object, $vars);
            }
        }

        if($object) return static::getUserPriv($module, $method, $object, $vars);

        if(!isset(static::$userPrivs[$module][$method][$vars])) static::$userPrivs[$module][$method][$vars] = static::getUserPriv($module, $method, $object, $vars);

        return static::$userPrivs[$module][$method][$vars];
    }

    /**
     * 获取用户是否有某个模块、方法的访问权限。
     * Get the user has the access permission of one module and method.
     *
     * @param  string $module
     * @param  string $method
     * @param  mixed  $object
     * @param  string $vars
     * @static
     * @access public
     * @return bool
     */
    public static function getUserPriv(string $module, string $method, $object = null, string $vars = ''): bool
    {
        global $app,$config;
        $module = strtolower($module);
        $method = strtolower($method);
        parse_str($vars, $params);

        if(empty($app->user)) return false;
        //list($module, $method) = commonTao::getStoryModuleAndMethod($module, $method, $params);
        list($module, $method) = commonTao::getBoardModuleAndMethod($module, $method, $params);

        /* Compatible with old search. */
        if($module == 'search' && $method == 'buildoldform')  $method = 'buildform';
        if($module == 'search' && $method == 'saveoldquery')  $method = 'savequery';
        if($module == 'search' && $method == 'buildoldquery') $method = 'buildquery';

        /* If the user is doing a tutorial, have all tutorial privileges. */
        if(commonModel::isTutorialMode())
        {
            $app->loadLang('tutorial');
            $app->loadConfig('tutorial');
            foreach($app->config->tutorial->guides as $guide)
            {
                if(!isset($guide->modules)) continue;

                $guideModules = explode(',', strtolower($guide->modules));
                if(in_array($module, $guideModules)) return true;
            }
        }

        /* Check the parent object is closed. */
        if(!empty($method) and strpos('close|batchclose', $method) === false and !commonModel::canBeChanged($module, $object)) return false;

        /* Check is the super admin or not. */
        if(!empty($app->user->admin) or strpos($app->company->admins, ",{$app->user->account},") !== false) return true;

        /* Check the method is openMethod. */
        if($app->config->vision == 'or') $app->config->logonMethods[] = 'story.view';
        if($app->config->vision != 'or') $app->config->logonMethods[] = 'demand.view';
        if(in_array("$module.$method", $app->config->openMethods)) return true;
        if(in_array("$module.$method", $app->config->logonMethods)) return true;

        /* If is the program/project/product/execution admin, have all program privileges. */
        if($app->config->vision != 'lite' && commonTao::isProjectAdmin($module, $object)) return true;

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

            foreach($programRightGroup as $module => $methods)
            {
                foreach($methods as $method => $label)
                {
                    $module = strtolower($module);
                    $method = strtolower($method);
                    $rights[$module][$method] = $label;
                }
            }

            /* Set base priv for project. */
            $projectRights = zget($this->app->user->rights['rights'], 'project', array());
            if(isset($projectRights['browse']) and !isset($rights['project']['browse'])) $rights['project']['browse'] = 1;
            if(isset($projectRights['kanban']) and !isset($rights['project']['kanban'])) $rights['project']['kanban'] = 1;
            if(isset($projectRights['index'])  and !isset($rights['project']['index']))  $rights['project']['index']  = 1;

            if(isset($projectPrivs->execution->linkStory))      $rights['execution']['linkstory']      = 1;
            if(isset($projectPrivs->execution->batchLinkStory)) $rights['execution']['batchlinkstory'] = 1;
            if(isset($projectPrivs->execution->unLinkStory))    $rights['execution']['unlinkstory']    = 1;

            $this->app->user->rights['rights'] = $rights;
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
            if($module == 'execution' && in_array($method, array('editrelation', 'deleterelation')) && !empty($object->execution)) $executionID = $object->execution;
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
        if($module == 'task' && !empty($object->team))
        {
            $taskModel = $app->control->loadModel('task');
            if($object->mode == 'linear')
            {
                if($method == 'assignto' && !in_array($object->status, array('done', 'cancel', 'closed'))) return false;
                if($method == 'start' && in_array($object->status, array('wait', 'doing')))
                {
                    if($object->assignedTo != $account) return false;

                    $currentTeam = $taskModel->getTeamByAccount($object->team, $account);
                    if($currentTeam && $currentTeam->status == 'wait') return true;
                }
                if($method == 'finish' && $object->assignedTo != $account) return false;
            }
            elseif($object->mode == 'multi')
            {
                $currentTeam = $taskModel->getTeamByAccount($object->team, $account);
                if($method == 'start' && in_array($object->status, array('wait', 'doing')) && $currentTeam && $currentTeam->status == 'wait') return true;
                if($method == 'finish' && (empty($currentTeam) || $currentTeam->status == 'done')) return false;
            }
        }

        if(!empty($object->openedBy)     && $object->openedBy     == $account) return true;
        if(!empty($object->addedBy)      && $object->addedBy      == $account) return true;
        if(!empty($object->account)      && $object->account      == $account) return true;
        if(!empty($object->assignedTo)   && $object->assignedTo   == $account) return true;
        if(!empty($object->finishedBy)   && $object->finishedBy   == $account) return true;
        if(!empty($object->canceledBy)   && $object->canceledBy   == $account) return true;
        if(!empty($object->closedBy)     && $object->closedBy     == $account) return true;
        if(!empty($object->lastEditedBy) && $object->lastEditedBy == $account) return true;

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
        foreach($items as $item)
        {
            if(!is_string($item)) return array();
        }

        global $app;
        static $allConverted = array();
        static $pinyin;
        if(empty($pinyin)) $pinyin = $app->loadClass('pinyin');

        $sign = ' aNdAnD ';
        $notConvertedItems = array_diff($items, array_keys($allConverted));

        if($notConvertedItems)
        {
            $convertedPinYin = $pinyin->convert(implode($sign, $notConvertedItems), PINYIN_KEEP_NUMBER | PINYIN_KEEP_ENGLISH);
            $itemsPinYin     = explode(trim($sign), implode("\t", $convertedPinYin));
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
     * 检查旧版API调用是否合法。
     * Check an entry.
     *
     * @access public
     * @return void
     */
    public function checkEntry()
    {
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
        $user->last   = helper::now();
        $user->rights = $this->user->authorize($user->account);
        $user->groups = $this->user->getGroups($user->account);
        $user->view   = $this->user->grantUserView($user->account, $user->rights['acls']);
        $user->admin  = strpos($this->app->company->admins, ",{$user->account},") !== false;
        $this->session->set('user', $user);
        $this->app->user = $user;

        $this->dao->update(TABLE_USER)->set('last')->eq($user->last)->set('visits=visits+1')->where('account')->eq($user->account)->exec();
        $this->loadModel('action')->create('user', $user->id, 'login');
        $this->loadModel('score')->create('user', 'login');

        if($isFreepasswd)
        {
            header("Location: {$this->config->webRoot}");
            helper::end();
        }

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
                $this->loadModel('entry')->updateCalledTime($entry->code, (int)$timestamp);
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
    public static function canBeChanged(string $module, $object = null): bool
    {
        if(defined('RUN_MODE') && RUN_MODE == 'api') return true;

        if(empty($object)) return true;

        global $config;
        static $productsStatus   = array();
        static $projectsStatus   = array();
        static $executionsStatus = array();

        $commonModel = new commonModel();

        if($config->edition != 'open')
        {
            $workflow = $commonModel->loadModel('workflow')->getByModule($module);
            if($workflow && $workflow->buildin == '0') return true;
        }

        /* Check the product is closed. */
        if(!empty($object->product) and is_numeric($object->product) and empty($config->CRProduct))
        {
            if(!isset($productsStatus[$object->product]))
            {
                $product = $commonModel->loadModel('product')->getByID((int)$object->product);
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

        /* Check the project is closed. */
        $productModuleList = array('story', 'bug', 'testcase', 'case', 'testtask', 'release');
        if(!in_array($module, $productModuleList) and !empty($object->project) and is_numeric($object->project) and (empty($config->CRProject) || empty($config->CRExecution)))
        {
            if(!isset($projectsStatus[$object->project]))
            {
                $project = $commonModel->loadModel('project')->getByID((int)$object->project);
                if($project && $project->status == 'closed')
                {
                    /* 有的表项目和执行都存在project里。 */
                    return $project->type == 'project' ? !empty($config->CRProject) : !empty($config->CRExecution);
                }
                $projectsStatus[$object->project] = $project ? $project->status : '';
            }
            if($projectsStatus[$object->project] == 'closed') return false;
        }

        /* Check the execution is closed. */
        $productModuleList = array('story', 'bug', 'testcase', 'case', 'testtask');
        if(!in_array($module, $productModuleList) and !empty($object->execution) and is_numeric($object->execution) and empty($config->CRExecution))
        {
            if(!isset($executionsStatus[$object->execution]))
            {
                $execution = $commonModel->loadModel('execution')->getByID((int)$object->execution);
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

        /* Judge that if the closed object(product|project|execution) is readonly from config table. The default is can modify. */
        if($type == 'product'   and empty($config->CRProduct) and $object->status == 'closed') return false;
        if($type == 'project'   and empty($config->CRProject) and $object->status == 'closed') return false;
        if($type == 'execution' and empty($config->CRExecution))
        {
            if($object->status == 'closed') return false;
            if(!isset($object->project) || !empty($config->CRProject)) return true;

            /* Check the execution's project is closed. */
            static $projectsStatus = array();
            $commonModel = new commonModel();
            if(!isset($projectsStatus[$object->project]))
            {
                $project = $commonModel->loadModel('project')->getByID((int)$object->project);
                $projectsStatus[$object->project] = $project ? $project->status : '';
            }
            if($projectsStatus[$object->project] == 'closed') return false;
        }

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
     * @return string|array|bool
     */
    public static function http(string $url, mixed $data = null, array $options = array(), array $headers = array(), string $dataType = 'data', string $method = 'POST', int $timeout = 30, bool $httpCode = false, bool $log = true): string|array|bool
    {
        global $lang, $app;

        if(common::$httpClient)
        {
            return common::$httpClient->request($url, $data, $options, $headers, $dataType, $method, $timeout, $httpCode, $log);
        }
        elseif(!extension_loaded('curl'))
        {
             if($dataType == 'json') return print($lang->error->noCurlExt);
             return json_encode(array('result' => 'fail', 'message' => $lang->error->noCurlExt));
        }

        commonModel::$requestErrors = array();

        $requestType = 'GET';
        if(func_num_args() >= 6) $requestType = $method; /* Specify $method parameter explicitly. */

        if(!is_array($headers)) $headers = (array)$headers;

        $headers[] = 'API-RemoteIP: ' . helper::getRemoteIp(); /* Real IP of real user. */
        $headers[] = 'API-LocalIP: ' . zget($_SERVER, 'SERVER_ADDR', ''); /* Server IP of self. */

        if($dataType == 'json')
        {
            $headers[] = 'Content-Type: application/json;charset=utf-8';
            if(!empty($data)) $data = json_encode($data);
        }

        $curl = curl_init();
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

        if(!$response) $response = '';
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

            if($app->config->debug)
            {
                $saasLog .= 'request  header: ' . json_encode($headers) . PHP_EOL;
                if(isset($newHeader)) $saasLog .= 'response header: ' . json_encode($newHeader) . PHP_EOL;
            }

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

        return $httpCode ? array($response, $httpCode, 'body' => $body, 'header' => $newHeader, 'errno' => $errno, 'info' => $info, 'response' => $response) : $response;
    }

    /**
     * 设置要展示的顶部一级菜单。
     * Set main menu.
     *
     * @static
     * @access public
     * @return bool
     */
    public static function setMainMenu(): bool
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

        if(!isset($lang->$tab->homeMenu)) return false;

        if($currentModule == $tab and $currentMethod == 'create')
        {
            $lang->menu = $lang->$tab->homeMenu;
            return true;
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
                return true;
            }

            $alias   = isset($menu['alias'])   ? explode(',', strtolower($menu['alias']))   : array();
            $exclude = isset($menu['exclude']) ? explode(',', strtolower($menu['exclude'])) : array();
            if(in_array($currentMethod, $alias) && !in_array("{$currentModule}-{$currentMethod}", $exclude))
            {
                $lang->menu = $lang->$tab->homeMenu;
                return true;
            }

            if(isset($menu['subModule']) and strpos(",{$menu['subModule']},", ",$currentModule,") !== false)
            {
                $lang->menu = $lang->$tab->homeMenu;
                return true;
            }
        }

        return false;
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
        if(common::isTutorialMode()) return array();

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
            if(!$menu) continue;

            $lang->$moduleName->$menuKey->$label = static::setMenuVarsEx($menu, $objectID, $params);
            if(isset($menu['subMenu']))
            {
                foreach($menu['subMenu'] as $key1 => $subMenu)
                {
                    $lang->$moduleName->$menuKey->{$label}['subMenu']->$key1 = static::setMenuVarsEx($subMenu, $objectID, $params);
                    if(!isset($subMenu['dropMenu'])) continue;
                    foreach($subMenu['dropMenu'] as $key2 => $dropMenu)
                    {
                        $lang->$moduleName->$menuKey->{$label}['subMenu']->$key1['dropMenu']->$key2 = static::setMenuVarsEx($dropMenu, $objectID, $params);
                    }
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
    public static function processMarkdown(string $markdown): string|bool
    {
        if(empty($markdown)) return false;

        global $app;
        $parsedown = $app->loadClass('parsedown');

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
    public static function buildActionItem(string $module, string $method, string $params, ?object $object = null, array $attrs = array()): array
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
     * @param  string $moduleName
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $data, string $moduleName = ''): array
    {
        global $app, $config;

        /* build operate menu. */
        $moduleName  = $moduleName ? $moduleName : $app->moduleName;
        $methodName  = $app->methodName;
        $actionsMenu = array();
        $isInModal   = helper::isAjaxRequest('modal');

        $this->loadModel($moduleName);
        foreach($config->{$moduleName}->actions->{$methodName} as $menu => $actionList)
        {
            $actions = array();
            foreach($actionList as $action)
            {
                $actionData = !empty($config->{$moduleName}->actionList[$action]) ? $config->{$moduleName}->actionList[$action] : array();
                if($isInModal && !empty($actionData['notInModal'])) continue;

                if(isset($actionData['data-app']) && $actionData['data-app'] == 'my') $actionData['data-app'] = $this->app->tab;
                if($isInModal && !isset($actionData['data-target']) && isset($actionData['data-toggle']) && $actionData['data-toggle'] == 'modal')
                {
                    $actionData['data-load'] = 'modal';
                    unset($actionData['data-toggle']);
                }

                if(isset($actionData['items']) && is_array($actionData['items']))
                {
                    $itemList = array();
                    foreach($actionData['items'] as $itemAction)
                    {
                        $itemActionData = $config->{$moduleName}->actionList[$itemAction];
                        $itemActionData = $this->checkPrivForOperateAction($itemActionData, $itemAction, $moduleName, $data, $menu);
                        if(($isInModal && !empty($itemActionData['notInModal'])) || $itemActionData === false) continue;
                        $itemList[] = $itemActionData;
                    }
                    $actionData['items'] = $itemList;
                    if(!empty($actionData['items'])) $actions[] = $actionData;
                }
                else
                {
                    $actionData = $this->checkPrivForOperateAction($actionData, $action, $moduleName, $data, $menu);
                    if($actionData !== false) $actions[] = $actionData;
                }

            }
            $actionsMenu[$menu] = $actions;
        }
        return $actionsMenu;
    }

    /**
     * 检查详情页操作按钮的权限。
     * Check the privilege of the operate action.
     *
     * @param  array      $actionData
     * @param  string     $action
     * @param  string     $moduleName
     * @param  object     $data
     * @param  object     $menu
     * @access protected
     * @return array|bool
     */
    public function checkPrivForOperateAction(array $actionData, string $action, string $moduleName, object $data, string $menu): array|bool
    {
        $rawModule = $moduleName;
        if(!empty($actionData['url']) && is_array($actionData['url']))
        {
            $moduleName     = ($actionData['url']['module'] == 'story' && in_array($moduleName, array('epic', 'requirement', 'story'))) ? $data->type : $actionData['url']['module'];
            $methodName     = $actionData['url']['method'];
            $params         = $actionData['url']['params'];
            $storySubdivide = in_array($moduleName, array('epic', 'requirement', 'story')) && $action == 'subdivide';
            if(!$storySubdivide && !common::hasPriv($moduleName, $methodName, $data)) return false;
            $actionData['url'] = helper::createLink($moduleName, $methodName, $params, '', !empty($actionData['url']['onlybody']) ? true : false);
        }
        else if(!empty($actionData['data-url']) && is_array($actionData['data-url']))
        {
            $moduleName     = ($actionData['data-url']['module'] == 'story' && in_array($moduleName, array('epic', 'requirement', 'story'))) ? $data->type : $actionData['data-url']['module'];
            $methodName     = $actionData['data-url']['method'];
            $params         = $actionData['data-url']['params'];
            $storySubdivide = in_array($moduleName, array('epic', 'requirement', 'story')) && $action == 'subdivide';
            if(!$storySubdivide && !common::hasPriv($moduleName, $methodName, $data)) return false;
            $actionData['data-url'] = helper::createLink($moduleName, $methodName, $params, '', !empty($actionData['data-url']['onlybody']) ? true : false);
        }
        elseif(empty($actionData['url']))
        {
            return $actionData;
        }
        else
        {
            if($action == 'importToLib' && in_array($moduleName, array('epic', 'requirement'))) $moduleName = 'story';
            if(!common::hasPriv($moduleName, $action, $data)) return false;
        }

        if(!empty($actionData['notLoadModel']) && $moduleName != $rawModule) $moduleName = $rawModule;
        if(!isset($this->$moduleName)) $this->loadModel($moduleName);
        if(empty($actionData['isClickable']) && isset($this->$moduleName) && method_exists($this->{$moduleName}, 'isClickable') && false === $this->{$moduleName}->isClickable($data, $action)) return false;
        if(!empty($actionData['hint']) && !isset($actionData['text'])) $actionData['text'] = $actionData['hint'];

        if($menu == 'suffixActions' && !empty($actionData['text']) && empty($actionData['showText'])) $actionData['text'] = '';
        if(isset($actionData['data-app']) && $actionData['data-app'] == 'my') $actionData['data-app'] = $this->app->tab;
        return $actionData;
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
        if(common::isTutorialMode()) return true;

        $objectType = strtolower($objectType);
        if(in_array($objectType, array('program', 'project', 'product', 'execution'))) return $this->loadModel($objectType)->checkPriv($objectID);

        return false;
    }

    /**
     * Replace menu lang.
     *
     * @static
     * @access public
     * @return void
     */
    public static function replaceMenuLang()
    {
        global $lang;
        if(empty($lang->db->custom)) return;
        foreach($lang->db->custom as $moduleName => $sectionMenus)
        {
            if(strpos($moduleName, 'Menu') === false) continue;

            $isSecondMenu = strpos($moduleName, 'SubMenu') === false;
            $moduleName   = str_replace($isSecondMenu ? 'Menu' : 'SubMenu', '', $moduleName);

            foreach($sectionMenus as $section => $menus)
            {
                foreach($menus as $key => $value)
                {
                    /* Get second menu. */
                    if($isSecondMenu)
                    {
                        $isDropMenu = strpos($section, 'DropMenu') !== false;
                        if(!$isDropMenu)
                        {
                            if(!isset($lang->{$moduleName}->{$section})) break;
                            if(is_object($lang->{$moduleName}->{$section}) and isset($lang->{$moduleName}->{$section}->{$key})) $settingMenu = &$lang->{$moduleName}->{$section}->{$key};
                            if(is_array($lang->{$moduleName}->{$section})  and isset($lang->{$moduleName}->{$section}[$key]))   $settingMenu = &$lang->{$moduleName}->{$section}[$key];
                        }
                        else
                        {
                            /* Get drop menu in second menu. */
                            $dropMenuKey = str_replace('DropMenu', '', $section);
                            if(!isset($lang->{$moduleName}->menu->{$dropMenuKey}['dropMenu']->{$key})) break;
                            $settingMenu = &$lang->{$moduleName}->menu->{$dropMenuKey}['dropMenu']->{$key};
                        }
                    }
                    /* Get third menu. */
                    elseif(isset($lang->{$moduleName}->menu->{$section}['subMenu']))
                    {
                        $subMenu = $lang->{$moduleName}->menu->{$section}['subMenu'];
                        if(is_object($subMenu) and isset($subMenu->{$key})) $settingMenu = &$lang->{$moduleName}->menu->{$section}['subMenu']->{$key};
                        if(is_array($subMenu)  and isset($subMenu[$key]))   $settingMenu = &$lang->{$moduleName}->menu->{$section}['subMenu'][$key];
                    }

                    /* Set custom menu lang. */
                    if(!empty($settingMenu))
                    {
                        if(is_string($settingMenu)) $settingMenu = $value . substr($settingMenu, strpos($settingMenu, '|'));
                        if(is_array($settingMenu) and isset($settingMenu['link'])) $settingMenu['link'] = $value . substr($settingMenu['link'], strpos($settingMenu['link'], '|'));
                        unset($settingMenu);
                    }
                }
            }
        }
    }

    /**
     * Check operate effort.
     *
     * @param  object  $effort
     * @access public
     * @return bool
     */
    public function canOperateEffort(object $effort)
    {
        if($this->app->user->admin) return true;

        if(empty((array)$effort)) return true;

        $actor = $effort->account;

        /* 如果是本人，可以直接修改。 */
        if($actor == $this->app->user->account) return true;

        /* 如果当前账户是项目负责人，则可以修改团队成员日志。*/
        if(!empty($effort->project))
        {
            $PM = $this->dao->select('PM')->from(TABLE_PROJECT)->where('id')->eq($effort->project)->fetch('PM');
            if($PM == $this->app->user->account) return true;
        }

        /* 如果当前账户是执行负责人，则可以修改团队成员日志。*/
        if(!empty($effort->execution))
        {
            $PM = $this->dao->select('PM')->from(TABLE_PROJECT)->where('id')->eq($effort->execution)->fetch('PM');
            if($PM == $this->app->user->account) return true;
        }

        /* 如果当前账户是上级部门(包括当前部门)负责人，则可以修改下属员工的日志。*/
        $actorDeptPath = $this->dao->select('path')->from(TABLE_DEPT)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t2.dept = t1.id')
            ->where('t2.account')->eq($actor)
            ->fetch('path');
        $deptManagers = $this->dao->select('manager')->from(TABLE_DEPT)->where('id')->in(explode(',', trim($actorDeptPath, ',')))->fetchPairs();
        if(in_array($this->app->user->account, $deptManagers)) return true;

        return false;
    }

    /**
     * Build icon button.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $vars
     * @param  object $object
     * @param  string $type button|list
     * @param  string $icon
     * @param  string $target
     * @param  string $extraClass
     * @param  bool   $onlyBody
     * @param  string $misc
     * @param  bool   $extraEnabled
     * @static
     * @access public
     * @return void
     */
    public static function buildIconButton($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '', $programID = 0, $extraEnabled = '')
    {
        if(isonlybody() and strpos($extraClass, 'showinonlybody') === false) return false;

        /* Remove iframe for operation button in modal. Prevent pop up in modal. */
        if(isonlybody() and strpos($extraClass, 'showinonlybody') !== false) $extraClass = str_replace('iframe', '', $extraClass);

        global $app, $lang, $config;

        /* Judge the $method of $module clickable or not, default is clickable. */
        $clickable = true;
        if(is_bool($extraEnabled))
        {
            $clickable = $extraEnabled;
        }
        else if(is_object($object))
        {
            if($app->getModuleName() != $module) $app->control->loadModel($module);
            $modelClass = class_exists("ext{$module}Model") ? "ext{$module}Model" : $module . "Model";
            if(class_exists($modelClass) and method_exists($modelClass, 'isClickable'))
            {
                //$clickable = call_user_func_array(array($modelClass, 'isClickable'), array('object' => $object, 'method' => $method));
                // fix bug on php  8.0 link: https://www.php.net/manual/zh/function.call-user-func-array.php#125953
                $clickable = call_user_func_array(array($modelClass, 'isClickable'), array($object, $method));
            }
        }

        /* Add data-app attribute. */
        if(strpos($misc, 'data-app') === false) $misc .= ' data-app="' . $app->tab . '"';
        if($onlyBody && strpos($misc, 'data-toggle') === false && $clickable && !isonlybody()) $misc .= ' data-toggle="modal"';

        /* Set module and method, then create link to it. */
        if(strtolower($module) == 'story'    and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'tostory')    ($module = 'story') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        $currentModule = strtolower($module);
        $currentMethod = strtolower($method);
        if(!commonModel::hasPriv($module, $method, $object, $vars) && !in_array("$currentModule.$currentMethod", $config->openMethods) && !in_array("$currentModule.$currentMethod", $config->logonMethods)) return false;

        $link = helper::createLink($module, $method, $vars, '', $onlyBody ? true : false);

        /* Set the icon title, try search the $method defination in $module's lang or $common's lang. */
        if(empty($title))
        {
            $title = $method;
            if($method == 'create' and $icon == 'copy') $method = 'copy';
            if(isset($lang->$method) and is_string($lang->$method)) $title = $lang->$method;
            if((isset($lang->$module->$method) or $app->loadLang($module)) and isset($lang->$module->$method))
            {
                $title = $method == 'report' ? $lang->$module->$method->common : $lang->$module->$method;
            }
            if($icon == 'toStory')   $title  = $lang->bug->toStory;
            if($icon == 'createBug') $title  = $lang->testtask->createBug;
        }

        /* set the class. */
        if(!$icon)
        {
            $icon = isset($lang->icons[$method]) ? $lang->icons[$method] : $method;
        }
        if(strpos(',edit,copy,report,export,delete,', ",$method,") !== false) $module = 'common';
        $class = "icon-$module-$method";

        if(!$clickable) $class .= ' disabled';
        if($icon)       $class .= ' icon-' . $icon;

        /* Create the icon link. */
        if($clickable)
        {
            if($app->getViewType() == 'mhtml')
            {
                return "<a data-remote='$link' class='$extraClass' $misc>$title</a>";
            }
            if($type == 'button')
            {
                if($method == 'edit' or $method == 'copy' or $method == 'delete' or ($method == 'review' and $module == 'charter'))
                {
                    return html::a($link, "<i class='$class'></i>", $target, "class='btn btn-link $extraClass' title=\"$title\" $misc", false);
                }
                else
                {
                    return html::a($link, "<i class='$class'></i> " . "<span class='text'>{$title}</span>", $target, "class='btn btn-link $extraClass' $misc", true);
                }
            }
            else
            {
                return html::a($link, "<i class='$class'></i>", $target, "class='btn $extraClass' title=\"$title\" $misc", false) . "\n";
            }
        }
        else
        {
            if($type == 'list')
            {
                return "<button type='button' class='disabled btn $extraClass'><i class='$class' title=\"$title\" $misc></i></button>\n";
            }
        }
    }

    /**
     * Build more executions button.
     *
     * @param  int    $executionID
     * @param  bool   $printHtml
     * @static
     * @access public
     * @return bool
     */
    public static function buildMoreButton(int $executionID, bool $printHtml = true): string
    {
        global $lang, $app, $dao;

        if(commonModel::isTutorialMode()) return '';

        $object = $dao->select('project,`type`')->from(TABLE_EXECUTION)->where('id')->eq($executionID)->fetch();
        if(empty($object)) return '';

        $executionPairs = array();
        $executionList  = $dao->select('id,name,parent,project,grade')->from(TABLE_EXECUTION)
            ->where('project')->eq($object->project)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$app->user->admin)->andWhere('id')->in($app->user->view->sprints)->fi()
            ->orderBy($object->type == 'stage' ? 'id_asc' : 'id_desc')->fi()
            ->fetchAll();

        $executions = array();
        foreach($executionList as $execution) $executions[$execution->id] = $execution;

        $executionList = $app->control->loadModel('execution')->resetExecutionSorts($executions);
        foreach($executionList as $execution)
        {
            if(isset($executionPairs[$execution->parent])) unset($executionPairs[$execution->parent]);
            if($execution->id == $executionID) continue;
            $executionPairs[$execution->id] = $execution->name;
        }

        if(empty($executionPairs)) return '';

        $html  = "<li class='divider'></li><li class='dropdown dropdown-hover'><a href='javascript:;' data-toggle='dropdown'>{$lang->more}<span class='caret'></span></a>";
        $html .= "<ul class='dropdown-menu'>";

        $showCount = 0;
        foreach($executionPairs as $executionID => $executionName)
        {
            $html .= "<li style='max-width: 300px;'>" . html::a(helper::createLink('execution', 'task', "executionID=$executionID"), $executionName, '', "title='{$executionName}' class='text-ellipsis' style='padding: 2px 10px'") . '</li>';

            $showCount ++;
            if($showCount == 10) break;
        }

        if(count($executionPairs) > 10) $html .= '<li>' . html::a(helper::createLink('project', 'execution', "status=all&projectID={$object->project}"), $lang->preview . $lang->more, '', "data-app='project' style='padding: 2px 10px'") . '</li>';

        $html .= "</ul></li>\n";

        if($printHtml) echo $html;
        return $html;
    }

    /**
     * Print about bar.
     *
     * @static
     * @access public
     * @return void
     */
    public static function printAboutBar()
    {
        global $app, $config, $lang;
        echo "<li class='dropdown-submenu zentao-help'>";
        echo "<a data-toggle='dropdown'>" . "<i class='icon icon-help'></i> " . $lang->help . "</a>";
        echo "<ul class='dropdown-menu pull-left'>";

        $manualUrl = ((!empty($config->isINT)) ? $config->manualUrl['int'] : $config->manualUrl['home']) . '&theme=' . $_COOKIE['theme'];
        echo '<li>' . html::a($manualUrl, $lang->manual, '', "class='show-in-app' id='helpLink' data-app='help'") . '</li>';

        echo '<li>' . html::a(helper::createLink('misc', 'changeLog'), $lang->changeLog, '', "class='iframe' data-width='800' data-headerless='true' data-backdrop='true' data-keyboard='true'") . '</li>';
        echo "</ul></li>\n";

        static::printClientLink();

        echo '<li class="zentao-about">' . html::a(helper::createLink('misc', 'about'), "<i class='icon icon-about'></i> " . $lang->aboutZenTao, '', "class='about iframe' data-width='1050' data-headerless='true' data-backdrop='true' data-keyboard='true' data-class='modal-about'") . '</li>';
        echo '<li class="AIUX">' . $lang->designedByAIUX . '</li>';
    }

    /**
     * Print the link for zentao client.
     *
     * @static
     * @access public
     * @return void
     */
    public static function printClientLink()
    {
        global $config, $lang;
        if(isset($config->xxserver->installed) and $config->xuanxuan->turnon)
        {
            echo "<li class='dropdown-submenu zentao-client'>";
            echo "<a href='javascript:;'>" . "<i class='icon icon-download'></i> " . $lang->clientName . "</a><ul class='dropdown-menu pull-left'>";
            echo '<li>' . html::a(helper::createLink('misc', 'downloadClient', '', '', true), $lang->downloadClient, '', "title='$lang->downloadClient' class='iframe text-ellipsis' data-width='600'") . '</li>';
            echo "<li class='dropdown-submenu' id='downloadMobile'><a href='javascript:;'>" . $lang->downloadMobile . "</a><ul class='dropdown-menu pull-left''>";
            echo "<li><div class='mobile-qrcode local-img'><img src='{$config->webRoot}static/images/app-qrcode.png' /></li>";
            echo "</ul></li>";
            echo '<li>' . html::a($lang->clientHelpLink, $lang->clientHelp, '', "title='$lang->clientHelp' target='_blank'") . '</li>';
            echo '</ul></li>';
        }
    }

    /**
     * Print create button list.
     *
     * @static
     * @access public
     * @return void
     */
    public static function printCreateList()
    {
        global $app, $config, $lang, $dao;

        $html = "<ul class='dropdown-menu pull-right create-list'>";

        /* Initialize the default values. */
        $showCreateList = $needPrintDivider = false;

        /* Get default product id. */
        $productID = isset($_SESSION['product']) ? $_SESSION['product'] : 0;
        if($productID)
        {
            $product = $dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('vision')->eq($config->vision)->andWhere('id')->eq($productID)->fetch();
            if(empty($product)) $productID = 0;
        }
        if(!$productID and $app->user->view->products)
        {
            $product = $dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->andWhere('vision')->eq($config->vision)->andWhere('id')->in($app->user->view->products)->orderBy('order desc')->limit(1)->fetch();
            if($product) $productID = $product->id;
        }

        if($config->vision == 'lite')
        {
            $object = $dao->select('id')->from(TABLE_PROJECT)
                ->where('deleted')->eq('0')
                ->andWhere('vision')->eq('lite')
                ->andWhere('model')->eq('kanban')
                ->beginIF(!$app->user->admin)->andWhere('id')->in($app->user->view->projects)->fi()
                ->limit(1)
                ->fetch();
            if(empty($object)) unset($lang->createIcons['story'], $lang->createIcons['task'], $lang->createIcons['execution']);
        }

        if($config->edition == 'open')     unset($lang->createIcons['effort']);
        if($config->systemMode == 'light') unset($lang->createIcons['program']);
        if(empty($config->board))          unset($lang->createIcons['board']);

        /* Check whether the creation permission is available, and print create buttons. */
        foreach($lang->createIcons as $objectType => $objectIcon)
        {
            $createMethod = 'create';
            $module       = $objectType == 'kanbanspace' ? 'kanban' : $objectType;
            if($objectType == 'effort') $createMethod = 'batchCreate';
            if($objectType == 'kanbanspace') $createMethod = 'createSpace';
            if($objectType == 'board') $createMethod = 'createBoard';
            if(strpos('|bug|execution|kanbanspace|', "|$objectType|") !== false) $needPrintDivider = true;

            $hasPriv = common::hasPriv($module, $createMethod);
            if(!$hasPriv and $objectType == 'doc' and  common::hasPriv('api', 'create'))        $hasPriv = true;
            if(!$hasPriv) continue;

            /* Determines whether to print a divider. */
            if($needPrintDivider and $showCreateList)
            {
                $html .= '<li class="divider"></li>';
                $needPrintDivider = false;
            }

            $showCreateList = true;
            $isOnlyBody     = false;
            $attr           = '';

            $params = '';
            switch($objectType)
            {
                case 'doc':
                    $params       = "objectType=&objectID=0&libID=0";
                    $createMethod = 'selectLibType';
                    $isOnlyBody   = true;
                    $attr         = "class='iframe' data-width='750px'";
                    break;
                case 'project':
                    $params = "model=scrum&programID=0&copyProjectID=0&extra=from=global";
                    if($config->vision == 'lite')
                    {
                        $params = "model=kanban";
                    }
                    elseif(!defined('TUTORIAL'))
                    {
                        $params       = "programID=0&from=global";
                        $createMethod = 'createGuide';
                        $attr         = 'data-toggle="modal" data-type="iframe"';
                    }
                    break;
                case 'bug':
                    $params = "productID=$productID&branch=&extras=from=global";
                    break;
                case 'story':
                    if(!$productID and $config->vision == 'lite')
                    {
                        $module = 'project';
                        $params = "model=kanban";
                    }
                    else
                    {
                        $params = "productID=$productID&branch=0&moduleID=0&storyID=0&objectID=0&bugID=0&planID=0&todoID=0&extra=from=global";
                        if($config->vision == 'lite')
                        {
                            $projectID = isset($_SESSION['project']) ? $_SESSION['project'] : 0;
                            $projects  = $dao->select('t2.id')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                                ->where('t1.product')->eq($productID)
                                ->andWhere('t2.type')->eq('project')
                                ->andWhere('t2.id')->in($app->user->view->projects)
                                ->orderBy('order desc')
                                ->fetchAll();

                            $projectIdList = array();
                            foreach($projects as $project) $projectIdList[$project->id] = $project->id;
                            if($projectID and !isset($projectIdList[$projectID])) $projectID = 0;
                            if(empty($projectID)) $projectID = key($projectIdList);

                            $params = "productID={$productID}&branch=0&moduleID=0&storyID=0&objectID={$projectID}&bugID=0&planID=0&todoID=0&extra=from=global";
                        }
                    }

                    break;
                case 'task':
                    $params = "executionID=0&storyID=0&moduleID=0&taskID=0&todoID=0&extra=from=global";
                    break;
                case 'testcase':
                    $params = "productID=$productID&branch=&moduleID=0&from=&param=0&storyID=0&extras=from=global";
                    break;
                case 'execution':
                    $projectID = isset($_SESSION['project']) ? $_SESSION['project'] : 0;
                    $params = "projectID={$projectID}&executionID=0&copyExecutionID=0&planID=0&confirm=no&productID=0&extra=from=global";
                    break;
                case 'product':
                    $params = "programID=&extra=from=global";
                    break;
                case 'program':
                    $params = "parentProgramID=0&extra=from=global";
                    break;
                case 'kanbanspace':
                    $isOnlyBody = true;
                    $attr       = "class='iframe' data-width='75%'";
                    break;
                case 'kanban':
                    $isOnlyBody = true;
                    $attr       = "class='iframe' data-width='75%'";
                    break;
                case 'board':
                    $createMethod = 'createByTemplate';
                    $params       = 'templateID=0';
                    $isOnlyBody   = true;
                    $attr         = 'data-toggle="modal"';
                    break;
            }

            $html .= '<li>' . html::a(helper::createLink($module, $createMethod, $params, '', $isOnlyBody), "<i class='icon icon-$objectIcon'></i> " . $lang->createObjects[$objectType], '', "$attr data-app=''") . '</li>';
        }

        if(!$showCreateList) return '';

        $html .= "</ul>";
        $html .= "<a class='dropdown-toggle' data-toggle='dropdown'>";
        $html .= "<i class='icon icon-plus'></i>";
        $html .= "</a>";

        echo $html;
    }

    /**
     * Print upper left corner home button.
     *
     * @param  string $tab
     * @static
     * @access public
     * @return void
     */
    public static function printHomeButton($tab)
    {
        global $lang, $config, $app;

        if(!$tab) return;
        if($tab == 'admin' and $app->control and method_exists($app->control, 'loadModel')) $app->control->loadModel('admin')->setMenu();
        if($config->edition != 'open' && $app->isServing()) $app->control->loadModel('common')->mergeFlowMenuLang();
        $icon = zget($lang->navIcons, $tab, '');

        if(!in_array($tab, array('program', 'product', 'project')))
        {
            if(!isset($lang->mainNav->$tab)) return;
            $nav = $lang->mainNav->$tab;
            list($title, $currentModule, $currentMethod, $vars) = explode('|', $nav);
            if($tab == 'execution') $currentMethod = 'all';
        }
        else
        {
            $currentModule = $tab;
            if($tab == 'program' or $tab == 'project') $currentMethod = 'browse';
            if($tab == 'product') $currentMethod = 'all';
        }

        $btnTitle  = isset($lang->db->custom['common']['mainNav'][$tab]) ? $lang->db->custom['common']['mainNav'][$tab] : $lang->$tab->common;
        $commonKey = $tab . 'Common';
        if(isset($lang->$commonKey) and $tab != 'execution') $btnTitle = $lang->$commonKey;
        if($btnTitle == $lang->project->template) $currentMethod = 'template'; //项目模板appName点击后跳转到项目模板列表页面

        $link      = helper::createLink($currentModule, $currentMethod);
        $className = $tab == 'devops' ? 'btn num' : 'btn';
        $html      = $link ? html::a($link, "$icon $btnTitle", '', "class='$className' style='padding-top: 2px'") : "$icon $btnTitle";

        echo "<div class='btn-group header-btn'>" . $html . '</div>';
    }

    /**
     * Print link icon.
     *
     * @param  string $module
     * @param  string $method
     * @param  string $vars
     * @param  object $object
     * @param  string $type button|list
     * @param  string $icon
     * @param  string $target
     * @param  string $extraClass
     * @param  bool   $onlyBody
     * @param  string $misc
     * @param  string $extraEnabled
     * @static
     * @access public
     * @return void
     */
    public static function printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '', $programID = 0, $extraEnabled = '')
    {
        echo common::buildIconButton($module, $method, $vars, $object, $type, $icon, $target, $extraClass, $onlyBody, $misc, $title, $programID, $extraEnabled);
    }

    /**
     * Print the main menu.
     *
     * @param  bool   $printHtml
     * @static
     * @access public
     * @return string
     */
    public static function printMainMenu(bool $printHtml = true): string
    {
        global $app, $lang, $config;

        /* Set main menu by app tab and module. */
        static::replaceMenuLang();
        static::setMainMenu();
        static::checkMenuVarsReplaced();

        $activeMenu = '';
        $tab = $app->tab;

        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if($isTutorialMode && isset($_SESSION['wizardModule'])) $currentModule = $_SESSION['wizardModule'];
        if($isTutorialMode && isset($_SESSION['wizardMethod'])) $currentMethod = $_SESSION['wizardMethod'];

        /* Print all main menus. */
        $menu = customModel::getMainMenu();

        $menuHtml = "<ul class='nav nav-default'>\n";
        foreach($menu as $menuItem)
        {
            if(isset($menuItem->hidden) and $menuItem->hidden and (!isset($menuItem->tutorial) or !$menuItem->tutorial)) continue;
            if(empty($menuItem->link)) continue;
            if($menuItem->divider && $menuItem->link['method'] != 'more') $menuHtml .= "<li class='divider'></li>";

            /* Init the these vars. */
            $alias     = isset($menuItem->alias) ? $menuItem->alias : '';
            $subModule = isset($menuItem->subModule) ? explode(',', $menuItem->subModule) : array();
            $class     = isset($menuItem->class) ? $menuItem->class : '';
            $exclude   = isset($menuItem->exclude) ? $menuItem->exclude : '';

            $active = '';
            if($menuItem->name == $currentModule and strpos(",$exclude,", ",$currentModule-$currentMethod,") === false)
            {
                $activeMenu = $menuItem->name;
                $active = 'active';
            }
            if($subModule and in_array($currentModule, $subModule) and strpos(",$exclude,", ",$currentModule-$currentMethod,") === false)
            {
                $activeMenu = $menuItem->name;
                $active = 'active';
            }

            if($menuItem->link['module'] == 'execution' and $menuItem->link['method'] == 'more')
            {
                $executionID = $menuItem->link['vars'];
                $menuHtml .= commonModel::buildMoreButton((int)$executionID, false);
            }
            elseif($menuItem->link['module'] == 'app' and $menuItem->link['method'] == 'serverlink')
            {
                $menuHtml .= commonModel::buildAppButton(false);
            }
            else
            {
                if($menuItem->link)
                {
                    $target = '';
                    $module = '';
                    $method = '';
                    $link   = commonModel::createMenuLink($menuItem);

                    if($menuItem->link['module'] == 'project' and $menuItem->link['method'] == 'other') $link = 'javascript:void(0);';

                    if(is_array($menuItem->link))
                    {
                        if(isset($menuItem->link['target'])) $target = $menuItem->link['target'];
                        if(isset($menuItem->link['module'])) $module = $menuItem->link['module'];
                        if(isset($menuItem->link['method'])) $method = $menuItem->link['method'];
                    }
                    if($module == $currentModule and ($method == $currentMethod or strpos(",$alias,", ",$currentMethod,") !== false) and strpos(",$exclude,", ",$currentMethod,") === false)
                    {
                        $activeMenu = $menuItem->name;
                        $active = 'active';
                    }

                    $label    = $menuItem->text;
                    $dropMenu = '';
                    $misc     = (isset($lang->navGroup->$module) and $tab != $lang->navGroup->$module) ? "data-app='$tab'" : '';

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

                            $subLink = helper::createLink($subModule, $subMethod, $subParams);

                            $subActive = '';
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

                            if($activeMainMenu)
                            {
                                $activeMenu = $dropMenuName;
                                $active     = 'active';
                                $subActive  = 'active';
                                $label      = $subLabel;
                            }
                            $dropMenu .= "<li class='$subActive' data-id='$dropMenuName'>" . html::a($subLink, $subLabel, '', "data-app='$tab'") . '</li>';
                        }

                        if(empty($dropMenu)) continue;

                        $label    .= "<span class='caret'></span>";
                        $dropMenu  = "<ul class='dropdown-menu'>{$dropMenu}</ul>";

                        $menuHtml .= "<li class='$class $active' data-id='$menuItem->name'>" . html::a($link, $label, $target, $misc) . $dropMenu . "</li>\n";
                    }
                    else
                    {
                        $menuHtml .= "<li class='$class $active' data-id='$menuItem->name'>" . html::a($link, $label, $target, $misc) . "</li>\n";
                    }
                }
                else
                {
                    $menuHtml .= "<li class='$class $active' data-id='$menuItem->name'>$menuItem->text</li>\n";
                }
            }
        }

        $menuHtml .= "</ul>\n";

        if($printHtml) echo $menuHtml;
        return $activeMenu;
    }

    /**
     * Print the module menu.
     *
     * @param  string $activeMenu
     * @static
     * @access public
     * @return void
     */
    public static function printModuleMenu($activeMenu)
    {
        global $app, $lang;
        $moduleName = $app->rawModule;
        $methodName = $app->rawMethod;

        $tab = $app->tab;

        if(!isset($lang->$tab->menu))
        {
            echo "<ul></ul>";
            return;
        }

        /* get current module and method. */
        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule  = $app->getModuleName();
        $currentMethod  = $app->getMethodName();
        $isMobile       = $app->viewType === 'mhtml';

        /* When use workflow then set rawModule to moduleName. */
        if($moduleName == 'flow') $activeMenu = $app->rawModule;
        $menu = customModel::getModuleMenu($activeMenu);

        /* If this is not workflow then use rawModule and rawMethod to judge highlight. */
        if($app->isFlow)
        {
            $currentModule = $app->rawModule;
            $currentMethod = $app->rawMethod;
        }

        if($isTutorialMode and defined('WIZARD_MODULE')) $currentModule = WIZARD_MODULE;
        if($isTutorialMode and defined('WIZARD_METHOD')) $currentMethod = WIZARD_METHOD;

        /* The beginning of the menu. */
        echo $isMobile ? '' : "<ul class='nav nav-default'>\n";

        /* Cycling to print every sub menu. */
        foreach($menu as $menuItem)
        {
            if(isset($menuItem->hidden) and $menuItem->hidden) continue;
            if($isMobile and empty($menuItem->link)) continue;
            if($menuItem->divider) echo "<li class='divider'></li>";

            /* Init the these vars. */
            $alias     = isset($menuItem->alias) ? $menuItem->alias : '';
            $subModule = isset($menuItem->subModule) ? explode(',', $menuItem->subModule) : array();
            $class     = isset($menuItem->class) ? $menuItem->class : '';
            $exclude   = isset($menuItem->exclude) ? $menuItem->exclude : '';
            $active    = '';

            if($subModule and in_array($currentModule, $subModule)) $active = 'active';
            if($menuItem->link)
            {
                $target = '';
                $module = '';
                $method = '';
                $link   = commonModel::createMenuLink($menuItem, $tab);
                if(is_array($menuItem->link))
                {
                    if(isset($menuItem->link['target'])) $target = $menuItem->link['target'];
                    if(isset($menuItem->link['module'])) $module = $menuItem->link['module'];
                    if(isset($menuItem->link['method'])) $method = $menuItem->link['method'];
                }

                if($module == $currentModule and $method == $currentMethod) $active = 'active';
                if($module == $currentModule and strpos(",$alias,", ",$currentMethod,") !== false) $active = 'active';
                if(strpos(",$exclude,", ",$currentModule-$currentMethod,") !== false or strpos(",$exclude,", ",$currentModule,") !== false) $active = '';

                $label    = $menuItem->text;
                $dropMenu = '';

                /* Print sub menus. */
                if(isset($menuItem->dropMenu))
                {
                    foreach($menuItem->dropMenu as $dropMenuKey => $dropMenuItem)
                    {
                        if(isset($dropMenuItem->hidden) and $dropMenuItem->hidden) continue;

                        $subActive = '';
                        $subModule = '';
                        $subMethod = '';
                        $subParams = '';
                        $subLabel  = '';
                        list($dropMenuName, $dropMenuModule, $dropMenuMethod, $dropMenuParams) = explode('|', $dropMenuItem['link']);
                        if(isset($dropMenuModule)) $subModule = $dropMenuModule;
                        if(isset($dropMenuMethod)) $subMethod = $dropMenuMethod;
                        if(isset($dropMenuParams)) $subParams = $dropMenuParams;
                        if(isset($dropMenuName))   $subLabel  = $dropMenuName;

                        $subLink = helper::createLink($subModule, $subMethod, $subParams);

                        if($currentModule == strtolower($subModule) and $currentMethod == strtolower($subMethod)) $subActive = 'active';

                        $misc = (isset($lang->navGroup->$subModule) and $tab != $lang->navGroup->$subModule) ? "data-app='$tab'" : '';
                        $dropMenu .= "<li class='$subActive' data-id='$dropMenuKey'>" . html::a($subLink, $subLabel, '', $misc) . '</li>';
                    }

                    if(empty($dropMenu)) continue;

                    $label   .= "<span class='caret'></span>";
                    $dropMenu  = "<ul class='dropdown-menu'>{$dropMenu}</ul>";
                }

                $misc = "data-app='$tab'";
                $menuItemHtml = "<li class='$class $active' data-id='$menuItem->name'>" . html::a($link, $label, $target, $misc) . $dropMenu . "</li>\n";

                if($isMobile) $menuItemHtml = html::a($link, $menuItem->text, $target, $misc . " class='$class $active'") . "\n";
                echo $menuItemHtml;
            }
            else
            {
                echo $isMobile ? $menuItem->text : "<li class='$class $active' data-id='$menuItem->name'>$menuItem->text</li>\n";
            }
        }
        echo $isMobile ? '' : "</ul>\n";
    }

    /**
     * Print the link contains orderBy field.
     *
     * This method will auto set the orderby param according the params. Fox example, if the order by is desc,
     * will be changed to asc.
     *
     * @param  string $fieldName    the field name to sort by
     * @param  string $orderBy      the order by string
     * @param  string $vars         the vars to be passed
     * @param  string $label        the label of the link
     * @param  string $module       the module name
     * @param  string $method       the method name
     *
     * @access public
     * @return void
     */
    public static function printOrderLink($fieldName, $orderBy, $vars, $label, $module = '', $method = '')
    {
        global $lang, $app;
        if(empty($module)) $module = isset($app->rawModule) ? $app->rawModule : $app->getModuleName();
        if(empty($method)) $method = isset($app->rawMethod) ? $app->rawMethod : $app->getMethodName();
        $className = 'header';
        $isMobile  = $app->viewType === 'mhtml';

        $order = explode('_', $orderBy);
        $order[0] = trim($order[0], '`');
        if($order[0] == $fieldName)
        {
            if(isset($order[1]) and $order[1] == 'asc')
            {
                $orderBy   = "{$order[0]}_desc";
                $className = $isMobile ? 'SortUp' : 'sort-up';
            }
            else
            {
                $orderBy = "{$order[0]}_asc";
                $className = $isMobile ? 'SortDown' : 'sort-down';
            }
        }
        else
        {
            $orderBy   = trim($fieldName, '`') . '_' . 'asc';
            $className = 'header';
        }

        $params = sprintf($vars, $orderBy);
        if($app->getModuleName() == 'my' and $app->rawMethod == 'work') $params = "mode={$app->getMethodName()}&" . $params;

        $link = helper::createLink($module, $method, $params);
        echo $isMobile ? html::a($link, $label, '', "class='$className' data-app={$app->tab}") : html::a($link, $label, '', "class='$className' data-app={$app->tab}");
    }

    /**
     * Print messageBar.
     *
     * @static
     * @access public
     * @return void
     */
    public static function printMessageBar()
    {
        global $app, $config;
        $app->loadConfig('message');
        if(!$config->message->browser->turnon) return;

        $showCount   = $config->message->browser->count;
        $unreadCount = $app->dbh->query("SELECT COUNT(1) AS `count` FROM " . TABLE_NOTIFY . " WHERE `objectType` = 'message' AND status != 'read' AND `toList` = ',{$app->user->account},'")->fetch()->count;
        $dotStyle    = static::getDotStyle($showCount != '0', $unreadCount);
        if($unreadCount > 99) $unreadCount = '99+';

        $fetcher = helper::createLink('message', 'ajaxGetDropMenuForOld');
        foreach($dotStyle as $cssKey => $cssValue) $dotStyle[$cssKey] = $cssKey . ':' . $cssValue;

        $html  = "<li id='messageDropdown' class='relative'>\n";
        $html .= "<a class='dropdown-toggle' id='messageBar' data-fetcher='{$fetcher}' onclick='fetchMessage()'>";
        $html .= "<i class='icon icon-bell'></i>";
        if($unreadCount)
        {
            $html .= "<span class='label label-dot danger absolute";
            if($showCount) $html .= ' rounded-sm';
            $html .= "' style='" . implode('; ', $dotStyle) . "'>";
            $html .= $showCount ? $unreadCount : '';
            $html .= '</span>';
        }
        $html .= "</a>";

        $html .= "<div class='dropdown-menu messageDropdownBox absolute' style='padding:0;left:-320px;'><div id='dropdownMessageMenu' class='not-clear-menu'></div></div>";
        $html .= "</li>";

        echo $html;
    }

    /**
     * Print top bar.
     *
     * @static
     * @access public
     * @return void
     */
    public static function printUserBar()
    {
        global $lang, $app;

        if(isset($app->user))
        {
            $isGuest = $app->user->account == 'guest';

            echo "<ul class='dropdown-menu pull-right'>";
            if(!$isGuest)
            {
                $noRole = (!empty($app->user->role) and isset($lang->user->roleList[$app->user->role])) ? '' : ' no-role';
                echo '<li class="user-profile-item">';
                echo "<a href='" . helper::createLink('my', 'profile', '', '', true) . "' data-width='700' class='iframe $noRole'" . '>';
                echo html::avatar($app->user, '', 'avatar-circle', 'id="menu-avatar"');
                echo '<div class="user-profile-name">' . (empty($app->user->realname) ? $app->user->account : $app->user->realname) . '</div>';
                if(isset($lang->user->roleList[$app->user->role])) echo '<div class="user-profile-role">' . $lang->user->roleList[$app->user->role] . '</div>';
                echo '</a></li><li class="divider"></li>';

                $vision = $app->config->vision == 'lite' ? 'rnd' : 'lite';

                echo '<li>' . html::a(helper::createLink('my', 'profile', '', '', true), "<i class='icon icon-account'></i> " . $lang->profile, '', "class='iframe' data-width='700'") . '</li>';

                if($app->config->vision === 'rnd')
                {
                    if(!commonModel::isTutorialMode())
                    {
                        echo '<li class="user-tutorial">' . html::a(helper::createLink('tutorial', 'start', '', '', true), "<i class='icon icon-guide'></i> " . $lang->tutorialAB, '', "class='iframe' data-class-name='modal-inverse' data-width='800' data-headerless='true' data-backdrop='true' data-keyboard='true'") . '</li>';
                    }

                    echo '<li class="preference-setting">' . html::a(helper::createLink('my', 'preference', 'showTip=false', '', true), "<i class='icon icon-controls'></i> " . $lang->preference, '', "class='iframe' data-width='700'") . '</li>';
                }

                if(common::hasPriv('my', 'changePassword')) echo '<li class="change-password">' . html::a(helper::createLink('my', 'changepassword', '', '', true), "<i class='icon icon-cog-outline'></i> " . $lang->changePassword, '', "class='iframe' data-width='600'") . '</li>';

                echo "<li class='divider'></li>";
            }

            echo "<li class='dropdown-submenu top'>";
            echo "<a href='javascript:;'>" . "<i class='icon icon-theme'></i> " . $lang->theme . "</a><ul class='dropdown-menu pull-left'>";
            foreach($app->lang->themes as $key => $value)
            {
                echo "<li " . ($app->cookie->theme == $key ? "class='selected'" : '') . "><a href='javascript:selectTheme(\"$key\");' data-value='" . $key . "'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            echo "<li class='dropdown-submenu top switch-language'>";
            echo "<a href='javascript:;'>" . "<i class='icon icon-lang'></i> " . $lang->lang . "</a><ul class='dropdown-menu pull-left'>";
            foreach ($app->config->langs as $key => $value)
            {
                echo "<li " . ($app->cookie->lang == $key ? "class='selected'" : '') . "><a href='javascript:selectLang(\"$key\");'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            //if(!$isGuest and !commonModel::isTutorialMode() and $app->viewType != 'mhtml')
            //{
            //    $customLink = helper::createLink('custom', 'ajaxMenu', "module={$app->getModuleName()}&method={$app->getMethodName()}", '', true);
            //    echo "<li class='custom-item'><a href='$customLink' data-toggle='modal' data-type='iframe' data-icon='cog' data-width='80%'>$lang->customMenu</a></li>";
            //}

            commonModel::printAboutBar();
            echo '<li class="divider"></li>';
            echo '<li>';
            if($isGuest)
            {
                echo html::a(helper::createLink('user', 'login'), $lang->login, '_top');
            }
            else
            {
                echo html::a('javascript:$.apps.logout()', "<i class='icon icon-exit'></i> " . $lang->logout, '_top');
            }
            echo '</li></ul>';

            echo "<a class='dropdown-toggle' data-toggle='dropdown'>";
            echo html::avatar($app->user);
            echo '</a>';
            echo '<script>$("#userDropDownMenu").on("click", function(){$(this).removeClass("dropdown-hover");});$("#userDropDownMenu").on("hover", function(){$(this).next().removeClass("open");$(this).addClass("dropdown-hover");});</script>';
        }
    }

    /**
     * 打印返回链接。
     * Print back link.
     *
     * @param  string $backLink
     * @param  string $class
     * @param  string $misc
     * @static
     * @access public
     * @return void
     */
    static public function printBack($backLink, $class = '', $misc = '')
    {
        global $lang;
        if(isonlybody()) return false;

        if(empty($class)) $class = 'btn';
        $title = $lang->goback . $lang->backShortcutKey;
        echo html::a($backLink, '<i class="icon-goback icon-back"></i> ' . $lang->goback, '', "id='back' class='{$class}' title={$title} $misc");
    }

    /**
     * Print link to a module's method.
     *
     * Before printing, check the privilege first. If no privilege, return false. Else, print the link, return true.
     *
     * @param string $module    the module name
     * @param string $method    the method
     * @param string $vars      vars to be passed
     * @param string $label     the label of the link
     * @param string $target    the target of the link
     * @param string $misc      others
     * @param bool   $newline
     * @param bool   $onlyBody
     * @param        $object
     *
     * @static
     * @access public
     * @return bool
     */
    public static function printLink($module, $method, $vars = '', $label = '', $target = '', $misc = '', $newline = true, $onlyBody = false, $object = null)
    {
        /* Add data-app attribute. */
        global $app, $config;
        $currentModule = strtolower($module);
        $currentMethod = strtolower($method);
        if(strpos($misc, 'data-app') === false) $misc .= ' data-app="' . $app->tab . '"';

        if(!commonModel::hasPriv($module, $method, $object, $vars) && !in_array("$currentModule.$currentMethod", $config->openMethods) && !in_array("$currentModule.$currentMethod", $config->logonMethods)) return false;
        echo html::a(helper::createLink($module, $method, $vars, '', $onlyBody), $label, $target, $misc, $newline);
        return true;
    }

     /**
     * Print pre and next link
     *
     * @param  string $preAndNext
     * @param  string $linkTemplate
     * @static
     * @access public
     * @return void
     */
    public static function printPreAndNext($preAndNext = '', $linkTemplate = '')
    {
        global $lang, $app;
        if(isonlybody()) return false;

        $moduleName = ($app->getModuleName() == 'story' and $app->tab == 'project') ? 'projectstory' : $app->getModuleName();
        $methodName = $app->getMethodName();
        echo "<nav class='container'>";
        if(isset($preAndNext->pre) and $preAndNext->pre)
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->pre->case)) ? 'case' : 'id';
            $title = isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name;
            $title = '#' . $preAndNext->pre->$id . ' ' . $title . ' ' . $lang->preShortcutKey;

            $params = $moduleName == 'story' ? "&version=0&param=0&storyType={$preAndNext->pre->type}" : '';
            $link   = $linkTemplate ? sprintf($linkTemplate, $preAndNext->pre->$id) : helper::createLink($moduleName, $methodName, "ID={$preAndNext->pre->$id}" . $params);
            $link  .= '#app=' . $app->tab;
            if(isset($preAndNext->pre->objectType) and $preAndNext->pre->objectType == 'doc')
            {
                echo html::a('javascript:void(0)', '<i class="icon-pre icon-chevron-left"></i>', '', "id='prevPage' class='btn' title='{$title}' data-url='{$link}'");
            }
            else
            {
                echo html::a($link, '<i class="icon-pre icon-chevron-left"></i>', '', "id='prevPage' class='btn' title='{$title}'");
            }
        }
        if(isset($preAndNext->next) and $preAndNext->next)
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->next->case)) ? 'case' : 'id';
            $title = isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name;
            $title = '#' . $preAndNext->next->$id . ' ' . $title . ' ' . $lang->nextShortcutKey;
            $params = $moduleName == 'story' ? "&version=0&param=0&storyType={$preAndNext->next->type}" : '';
            $link  = $linkTemplate ? sprintf($linkTemplate, $preAndNext->next->$id) : helper::createLink($moduleName, $methodName, "ID={$preAndNext->next->$id}" . $params);
            $link .= '#app=' . $app->tab;
            if(isset($preAndNext->next->objectType) and $preAndNext->next->objectType == 'doc')
            {
                echo html::a('javascript:void(0)', '<i class="icon-pre icon-chevron-right"></i>', '', "id='nextPage' class='btn' title='$title' data-url='{$link}'");
            }
            else
            {
                echo html::a($link, '<i class="icon-pre icon-chevron-right"></i>', '', "id='nextPage' class='btn' title='$title'");
            }
        }
        echo '</nav>';
    }

    /**
     * Print icon of comment.
     *
     * @param string $commentFormLink
     * @param object $object
     *
     * @static
     * @access public
     * @return mixed
     */
    public static function printCommentIcon(string $commentFormLink, ?object $object = null)
    {
        global $lang;

        if(!commonModel::hasPriv('action', 'comment', $object)) return false;
        echo html::commonButton('<i class="icon icon-chat-line"></i> ' . $lang->action->create, '', 'btn btn-link pull-right btn-comment');
        echo <<<EOF
<div class="modal fade modal-comment">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title">{$lang->action->create}</h4>
      </div>
      <div class="modal-body">
        <form class="load-indicator not-watch" action="{$commentFormLink}" target='hiddenwin' method='post'>
          <div class="form-group">
            <textarea id='comment' name='comment' class="form-control" rows="8" autofocus="autofocus"></textarea>
          </div>
          <div class="form-group form-actions text-center">
            <button type="submit" class="btn btn-primary btn-wide">{$lang->save}</button>
            <button type="button" class="btn btn-wide" data-dismiss="modal">{$lang->close}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    \$body = $('body', window.parent.document);
    if(\$body.hasClass('hide-modal-close')) \$body.removeClass('hide-modal-close');
});
</script>
EOF;
    }

    /**
     * Get messageBar dot style.
     *
     * @param bool $showCount
     * @param int  $unreadCount
     *
     * @static
     * @access public
     * @return mixed
     */
    public static function getDotStyle(bool $showCount, int $unreadCount): array
    {
        $dotStyle    = array('top' => '-3px', 'right' => '-10px', 'aspect-ratio' => '0', 'padding' => '2px');
        if($unreadCount < 10) $dotStyle['right'] = '-5px';
        if(!$showCount)
        {
            $dotStyle['aspect-ratio'] = '1 / 1';
            $dotStyle['width']        = '5px';
            $dotStyle['height']       = '5px';
            $dotStyle['right']        = '-2px';
            $dotStyle['top']          = '-2px';
        }

        return $dotStyle;
    }
}

class common extends commonModel
{
}
