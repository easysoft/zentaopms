<?php
/**
 * The model file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class commonModel extends model
{
    /**
     * The construc method, to do some auto things.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        if(!defined('FIRST_RUN'))
        {
            $this->startSession();
            $this->sendHeader();
            $this->setCompany();
            $this->setUser();
            $this->loadConfigFromDB();
            $this->loadCustomFromDB();
            if(!$this->checkIP()) die($this->lang->ipLimited);
            if($this->app->getViewType() == 'mhtml') $this->setMobileMenu();
            $this->app->loadLang('company');
            define('FIRST_RUN', true);
        }
    }

    /**
     * Start the session.
     * 
     * @access public
     * @return void
     */
    public function startSession()
    {
        session_name($this->config->sessionVar);
        if(isset($_GET[$this->config->sessionVar])) session_id($_GET[$this->config->sessionVar]);
        session_start();
    }

    /**
     * Set the header info.
     * 
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        header("Content-Type: text/html; Language={$this->config->charset}");
        header("Cache-control: private");
    }

    /**
     * Set the commpany.
     *
     * First, search company by the http host. If not found, search by the default domain. Last, use the first as the default. 
     * After get the company, save it to session.
     * @access public
     * @return void
     */
    public function setCompany()
    {        
        $httpHost = $this->server->http_host;

        if($this->session->company)
        {
            $this->app->company = $this->session->company;
        }
        else
        {
            $company = $this->loadModel('company')->getFirst();
            if(!$company) $this->app->triggerError(sprintf($this->lang->error->companyNotFound, $httpHost), __FILE__, __LINE__, $exit = true);
            $this->session->set('company', $company);
            $this->app->company  = $company;
        }
    }

    /**
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
        elseif($this->app->company->guest or PHP_SAPI == 'cli')
        {
            $user             = new stdClass();
            $user->id         = 0;
            $user->account    = 'guest';
            $user->realname   = 'guest';
            $user->role       = 'guest';
            $user->rights     = $this->loadModel('user')->authorize('guest');
            $user->groups     = array('group');
            $this->session->set('user', $user);
            $this->app->user = $this->session->user;
        }
    }

    /**
     * Load configs from database and save it to config->system and config->personal.
     * 
     * @access public
     * @return void
     */
    public function loadConfigFromDB()
    {
        /* Get configs of system and current user. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        if($this->config->db->name) $config  = $this->loadModel('setting')->getSysAndPersonalConfig($account);
        $this->config->system   = isset($config['system']) ? $config['system'] : array();
        $this->config->personal = isset($config[$account]) ? $config[$account] : array();

        /* Overide the items defined in config/config.php and config/my.php. */
        if(isset($this->config->system->common)) helper::mergeConfig($this->config->system->common, 'common');
        if(isset($this->config->personal->common)) helper::mergeConfig($this->config->personal->common, 'common');
    }

    /**
     * Load custom lang from db.
     * 
     * @access public
     * @return void
     */
    public function loadCustomFromDB()
    {
        if(defined('IN_UPGRADE')) return;
        if(!$this->config->db->name) return;
        $records = $this->loadModel('custom')->getAll();
        if(!$records) return;
        $this->lang->db = new stdclass();
        $this->lang->db->custom = $records;
    }

    /**
     * Juage a method of one module is open or not?
     * 
     * @param  string $module 
     * @param  string $method 
     * @access public
     * @return bool
     */
    public function isOpenMethod($module, $method)
    {
        if($module == 'user' and strpos('login|logout|deny|reset', $method) !== false) return true;
        if($module == 'api'  and $method == 'getsessionid') return true;
        if($module == 'misc' and $method == 'ping')  return true;
        if($module == 'misc' and $method == 'checktable') return true;
        if($module == 'block' and $method == 'main') return true;
        if($module == 'sso' and $method == 'login')  return true;
        if($module == 'sso' and $method == 'logout') return true;
        if($module == 'sso' and $method == 'bind') return true;

        if($this->loadModel('user')->isLogon())
        {
            if(stripos($method, 'ajax') !== false) return true;
            if(stripos($method, 'downnotify') !== false) return true;
        }

        if(stripos($method, 'ajaxgetdropmenu') !== false) return true;
        if(stripos($method, 'ajaxgetmatcheditems') !== false) return true;
        if($module == 'misc' and $method == 'qrcode') return true;
        if($module == 'misc' and $method == 'about') return true;
        if($module == 'misc' and $method == 'checkupdate') return true;
        return false;
    }

    /**
     * Deny access.
     * 
     * @access public
     * @return void
     */
    public function deny($module, $method)
    {
        /* Get authorize again. */
        $user = $this->app->user;
        $user->rights = $this->loadModel('user')->authorize($user->account);
        $user->groups = $this->user->getGroups($user->account);
        $this->session->set('user', $user);
        $this->app->user = $this->session->user;
        if(commonModel::hasPriv($module, $method)) return true;

        $vars = "module=$module&method=$method";
        if(isset($this->server->http_referer))
        {
            $referer = helper::safe64Encode($this->server->http_referer);
            $vars   .= "&referer=$referer";
        }
        $denyLink = helper::createLink('user', 'deny', $vars);

        /* Fix the bug of IE: use js locate, can't get the referer. */
        if(strpos($this->server->http_user_agent, 'Trident') !== false)
        {
            echo "<a href='$denyLink' id='denylink' style='display:none'>deny</a>";
            echo "<script language='javascript'>document.getElementById('denylink').click();</script>";
        }
        else
        {
            echo js::locate($denyLink);
        }
        exit;
    }

    /**
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
     * Print top bar.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function printTopBar()
    {
        global $lang, $app;

        if(isset($app->user))
        {
            $isGuest = $app->user->account == 'guest';


            echo "<div class='dropdown' id='userMenu'>";
            echo "<a href='javascript:;' data-toggle='dropdown'><i class='icon-user'></i> " . $app->user->realname . " <span class='caret'></span></a>";

            echo "<ul class='dropdown-menu'>";

            if(!$isGuest)
            {
                echo '<li>' . html::a(helper::createLink('my', 'profile', '', '', true), $lang->profile, '', "class='iframe' data-width='600'") . '</li>';
                echo '<li>' . html::a(helper::createLink('my', 'changepassword', '', '', true), $lang->changePassword, '', "class='iframe' data-width='500'") . '</li>';
                echo "<li class='divider'></li>";
            }

            $isLeft = ($app->company->website and $app->company->backyard) ? '' : ' left';

            echo "<li class='dropdown-submenu{$isLeft}'>";
            echo "<a href='javascript:;'>" . $lang->theme . "</a><ul class='dropdown-menu'>";
            foreach ($app->lang->themes as $key => $value)
            {
                echo "<li class='theme-option" . ($app->cookie->theme == $key ? " active" : '') . "'><a href='javascript:selectTheme(\"$key\");' data-value='" . $key . "'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            echo "<li class='dropdown-submenu{$isLeft}'>";
            echo "<a href='javascript:;'>" . $lang->lang . "</a><ul class='dropdown-menu'>";
            foreach ($app->config->langs as $key => $value)
            {
                echo "<li class='lang-option" . ($app->cookie->lang == $key ? " active" : '') . "'><a href='javascript:selectLang(\"$key\");' data-value='" . $key . "'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            echo '</ul></div>';

            if($isGuest)
            {
                echo html::a(helper::createLink('user', 'login'), $lang->login);
            }
            else
            {
                echo html::a(helper::createLink('user', 'logout'), $lang->logout);
            }
        }

        if($app->company->website)  echo html::a($app->company->website,  $lang->company->website,  '_blank');
        if($app->company->backyard) echo html::a($app->company->backyard, $lang->company->backyard, '_blank');      

        echo html::a(helper::createLink('misc', 'about'), $lang->aboutZenTao, '', "class='about iframe' data-width='900' data-headerless='true' data-class='modal-about'");
    }

    /**
     * Set mobile menu.
     * 
     * @access public
     * @return void
     */
    public function setMobileMenu()
    {
        $menu = new stdclass();

        $role = isset($this->app->user->role) ? $this->app->user->role : '';

        $this->config->locate = new stdclass();
        $this->config->locate->module = 'my';
        $this->config->locate->method = 'todo';
        $this->config->locate->params = '';

        $todo    = $this->lang->my->menu->todo['link'];
        $task    = $this->lang->my->menu->task['link'];
        $story   = $this->lang->my->menu->story['link'];
        $bug     = $this->lang->my->menu->bug['link'];
        $project = $this->lang->menu->project . '|locate=no&&status=isdoing';
        $product = $this->lang->menu->product . '|locate=no';
        $menu    = array('todo' => $todo, 'task' => $task, 'bug' => $bug, 'project' => $project, 'product' => $product);

        if($role and strpos('dev,td,pm', $role) !== false) $menu = array('todo' => $todo, 'task' => $task, 'bug' => $bug, 'product' => $product, 'project' => $project);
        if($role and strpos('pd,po',     $role) !== false) $menu = array('todo' => $todo, 'story' => $story, 'bug' => $bug, 'product' => $product, 'project' => $project);
        if($role and strpos('qa,qd',     $role) !== false) $menu = array('todo' => $todo, 'bug' => $bug, 'project' => $project, 'product' => $product);
        if($role and strpos('top',       $role) !== false) $menu = array('project' => $project, 'product' => $product, 'todo' => $todo);

        if($role == 'top')
        {
            $this->config->locate->module = 'project';
            $this->config->locate->method = 'index';
            $this->config->locate->params = 'locate=no&status=doing';
        }

        unset($this->lang->menuOrder);
        unset($this->lang->menugroup);
        $this->lang->menu = new stdclass();
        $this->lang->menu = $menu;
    }

    /**
     * Print the main menu.
     * 
     * @param  string $moduleName 
     * @static
     * @access public
     * @return void
     */
    public static function printMainmenu($moduleName, $methodName = '')
    {
        global $app, $lang;
        echo "<ul class='nav'>\n";

        /* Set the main main menu. */
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;
        if($app->getViewType() == 'mhtml')
        {
            if($moduleName == 'my')   $mainMenu = $methodName;
            if($moduleName == 'todo') $mainMenu = $moduleName;
            if($moduleName == 'story' and !isset($lang->menu->story)) $mainMenu = 'product';
            if($moduleName == 'bug'   and !isset($lang->menu->bug))   $mainMenu = 'product';
            if($moduleName == 'task'  and !isset($lang->menu->task))  $mainMenu = 'project';
        }

        /* Sort menu according to menuOrder. */
        if(isset($lang->menuOrder))
        {
            $menus = $lang->menu;
            $lang->menu = new stdclass();

            ksort($lang->menuOrder, SORT_ASC);
            foreach($lang->menuOrder as $key)  
            {
                $menu = $menus->$key; 
                unset($menus->$key);
                $lang->menu->$key = $menu;
            }
            foreach($menus as $key => $menu)
            {
                $lang->menu->$key = $menu; 
            }
        }

        $activeName = $app->getViewType() == 'mhtml' ? 'ui-btn-active' : 'active';
        /* Print all main menus. */
        foreach($lang->menu as $menuKey => $menu)
        {
            $active = $menuKey == $mainMenu ? "class='$activeName'" : '';
            $link = explode('|', $menu);
            list($menuLabel, $module, $method) = $link;
            $vars = isset($link[3]) ? $link[3] : '';

            if(commonModel::hasPriv($module, $method))
            {
                $link  = helper::createLink($module, $method, $vars);
                echo "<li $active><a href='$link' $active id='menu$menuKey'>$menuLabel</a></li>\n";
            }
        }
        echo "</ul>\n";
    }

    /**
     * Print the search box.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function printSearchBox()
    {
        global $app, $lang;
        $moduleName  = $app->getModuleName();
        $methodName  = $app->getMethodName();
        $searchObject = $moduleName;

        if($moduleName == 'product')
        {
            if($methodName == 'browse') $searchObject = 'story';
        }
        elseif($moduleName == 'project')
        {
            if(strpos('task|story|bug|build', $methodName) !== false) $searchObject = $methodName;
        }
        elseif($moduleName == 'my' or $moduleName == 'user')
        {
            $searchObject = $methodName;
        }
        if(empty($lang->searchObjects[$searchObject])) $searchObject = 'bug';

        echo "<div class='input-group input-group-sm' id='searchbox'>"; 
        echo "<div class='input-group-btn' id='typeSelector'>";
        echo "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span id='searchTypeName'>" . $lang->searchObjects[$searchObject] . "</span> <span class='caret'></span></button>";
        echo html::hidden('searchType', $searchObject);
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->searchObjects as $key => $value)
        {
            echo "<li><a href='javascript:;' data-value='{$key}'>{$value}</a></li>"; 
        }
        echo '</ul></div>';
        echo html::input('searchQuery', '', "onclick='this.value=\"\"' onkeydown='if(event.keyCode==13) shortcut()' class='form-control' placeholder='" . $lang->searchTips . "'");
        echo "<div id='objectSwitcher' class='input-group-btn'><a href='javascript:shortcut();' class='btn'>GO! </a></div>";
        echo "</div>\n";
    }

    /**
     * Print the module menu.
     * 
     * @param  string $moduleName 
     * @static
     * @access public
     * @return void
     */
    public static function printModuleMenu($moduleName)
    {
        global $lang, $app;

        if(!isset($lang->$moduleName->menu)) {echo "<ul></ul>"; return;}

        /* Get the sub menus of the module, and get current module and method. */
        $submenus      = $lang->$moduleName->menu;  
        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();

        /* Sort the subMenu according to menuOrder. */
        if(isset($lang->$moduleName->menuOrder))
        {
            $menus = json_decode(json_encode($submenus), true);
            $submenus = new stdclass();

            ksort($lang->$moduleName->menuOrder, SORT_ASC);
            if(isset($menus['list'])) 
            {
                $submenus->list = $menus['list']; 
                unset($menus['list']);
            }
            foreach($lang->$moduleName->menuOrder as $order)  
            {
                if(($order != 'list') && isset($menus[$order]))
                {
                    $subOrder = $menus[$order];
                    unset($menus[$order]);
                    $submenus->$order = $subOrder;
                }
            }

            foreach($menus as $key => $menu) $submenus->$key = $menu; 
        }

        /* The beginning of the menu. */
        echo "<ul class='nav'>\n";

        /* Cycling to print every sub menus. */
        foreach($submenus as $subMenuKey => $submenu)
        {
            /* Init the these vars. */
            $link      = $submenu;
            $subModule = '';
            $alias     = '';
            $float     = '';
            $active    = '';
            $target    = '';

            if(is_array($submenu)) extract($submenu);   // If the sub menu is an array, extract it.

            /* Print the menu. */
            if(strpos($link, '|') === false)
            {
                echo "<li>$link</li>\n";
            }
            else
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
                $vars = isset($link[3]) ? $link[3] : '';
                if(commonModel::hasPriv($module, $method))
                {
                    /* Is the currentModule active? */
                    $subModules = explode(',', $subModule);
                    if(in_array($currentModule,$subModules) and $float != 'right') $active = 'active';
                    if($module == $currentModule and ($method == $currentMethod or strpos(",$alias,", ",$currentMethod,") !== false) and $float != 'right') $active = 'active';
                    echo "<li class='$float $active'>" . html::a(helper::createLink($module, $method, $vars), $label, $target, "id=submenu$subMenuKey") . "</li>\n";
                }
            }
        }
        echo "</ul>\n";
    }

    /**
     * Print the bread menu.
     * 
     * @param  string $moduleName 
     * @param  string $position 
     * @static
     * @access public
     * @return void
     */
    public static function printBreadMenu($moduleName, $position)
    {
        global $lang;
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;
        echo html::a(helper::createLink('my', 'index'), $lang->zentaoPMS) . $lang->arrow;
        if($moduleName != 'index')
        {
            if(!isset($lang->menu->$mainMenu)) return;
            list($menuLabel, $module, $method) = explode('|', $lang->menu->$mainMenu);
            echo html::a(helper::createLink($module, $method), $menuLabel);
        }
        else
        {
            echo $lang->index->common;
        }
        if(empty($position)) return;
        echo $lang->arrow;
        foreach($position as $key => $link)
        {
            echo $link;
            if(isset($position[$key + 1])) echo $lang->arrow;
        }
    }

    /**
     * Print the link for notify file.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function printNotifyLink()
    {
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)
        {
            global $lang;
            echo html::a(helper::createLink('misc', 'downNotify'), "<i class='icon-bell'></i>", '', "title='$lang->downNotify'") . ' &nbsp; ';
        }
    }

    /**
     * Print QR code Link. 
     * 
     * @static
     * @access public
     * @return void
     */
    public static function printQRCodeLink($color = '')
    {
        global $lang;
        echo html::a('javascript:;', "<i class='icon-qrcode'></i>", '', "class='qrCode $color' id='qrcodeBtn' title='{$lang->user->mobileLogin}'");
        echo "<div class='popover top' id='qrcodePopover'><div class='arrow'></div><h3 class='popover-title'>{$lang->user->mobileLogin}</h3><div class='popover-content'><img src=\"javascript:;\"></div></div>";
        echo '<script>$(function(){$("#qrcodeBtn").click(function(){$("#qrcodePopover").toggleClass("show");}); $("#wrap").click(function(){$("#qrcodePopover").removeClass("show");});});</script>';
        echo '<script>$(function(){$("#qrcodeBtn").hover(function(){$(".popover-content img").attr("src", "' . helper::createLink('misc', 'qrCode') . '");});});</script>';
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
     * @static
     * @access public
     * @return void
     */
    public static function printOrderLink($fieldName, $orderBy, $vars, $label, $module = '', $method = '')
    {
        global $lang, $app;
        if(empty($module)) $module = $app->getModuleName();
        if(empty($method)) $method = $app->getMethodName();
        $className = 'header';

        $order = explode('_', $orderBy);
        $order[0] = trim($order[0], '`');
        if($order[0] == $fieldName)
        {
            if(isset($order[1]) and $order[1] == 'asc')
            {
                $orderBy   = "{$order[0]}_desc";
                $className = 'headerSortDown';
            }
            else
            {
                $orderBy = "{$order[0]}_asc";
                $className = 'headerSortUp';
            }
        }
        else
        {

            $orderBy   = "" . trim($fieldName, '`') . "" . '_' . 'asc';
            $className = 'header';
        }
        $link = helper::createLink($module, $method, sprintf($vars, $orderBy));
        echo "<div class='$className'>" . html::a($link, $label) . '</div>';
    }

    /**
     * Print link to an modules' methd.
     *
     * Before printing, check the privilege first. If no privilege, return fasle. Else, print the link, return true.
     * 
     * @param  string $module   the module name
     * @param  string $method   the method
     * @param  string $vars     vars to be passed
     * @param  string $label    the label of the link
     * @param  string $target   the target of the link
     * @param  string $misc     others
     * @param  bool   $newline 
     * @static
     * @access public
     * @return bool
     */
    public static function printLink($module, $method, $vars = '', $label, $target = '', $misc = '', $newline = true, $onlyBody = false)
    {
        if(!commonModel::hasPriv($module, $method)) return false;
        echo html::a(helper::createLink($module, $method, $vars, '', $onlyBody), $label, $target, $misc, $newline);
        return true;
    }

    /**
     * Print icon of split line.
     * 
     * @static
     * @access public
     * @return void
     */
    public static function printDivider()
    {
        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
    }

    /**
     * Print icon of comment.
     * 
     * @param  string $module 
     * @static
     * @access public
     * @return void
     */
    public static function printCommentIcon($module)
    {
        if(isonlybody()) return false;

        global $lang;

        if(!commonModel::hasPriv($module, 'edit')) return false;
        echo html::a('#commentBox', '<i class="icon-comment-alt"></i>', '', "title='$lang->comment' onclick='setComment()' class='btn'");
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
     * @static
     * @access public
     * @return void
     */
    public static function printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '')
    {
        if(isonlybody() and strpos($extraClass, 'showinonlybody') === false) return false;

        global $app, $lang;

        /* Judge the $method of $module clickable or not, default is clickable. */
        $clickable = true;
        if(is_object($object))
        {
            if($app->getModuleName() != $module) $app->control->loadModel($module);
            $modelClass = class_exists("ext{$module}Model") ? "ext{$module}Model" : $module . "Model";
            if(class_exists($modelClass) and is_callable(array($modelClass, 'isClickable')))
            {
                $clickable = call_user_func_array(array($modelClass, 'isClickable'), array('object' => $object, 'method' => $method));
            }
        }

        /* Set module and method, then create link to it. */
        if(strtolower($module) == 'story'    and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'tostory')    ($module = 'story') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(!commonModel::hasPriv($module, $method)) return false;
        $link = helper::createLink($module, $method, $vars, '', $onlyBody);

        /* Set the icon title, try search the $method defination in $module's lang or $common's lang. */
        $title = $method;
        if($method == 'create' and $icon == 'copy') $method = 'copy';
        if(isset($lang->$method) and is_string($lang->$method)) $title = $lang->$method;
        if((isset($lang->$module->$method) or $app->loadLang($module)) and isset($lang->$module->$method)) 
        {
            $title = $method == 'report' ? $lang->$module->$method->common : $lang->$module->$method;
        }
        if($icon == 'toStory')   $title  = $lang->bug->toStory;
        if($icon == 'createBug') $title  = $lang->testtask->createBug;

        /* set the class. */
        if(!$icon)
        {
            $icon = $lang->icons[$method] ? $lang->icons[$method] : $method;
        }
        if(strpos(',edit,copy,report,export,delete,', ",$method,") !== false) $module = 'common';
        $class = "icon-$module-$method";
        if(!$clickable) $class .= ' disabled';
        if($icon) $class       .= ' icon-' . $icon;


        /* Create the icon link. */
        if($clickable)
        {
            if($app->getViewType() == 'mhtml')
            {
                echo html::a($link, $title, $target, "class='$extraClass' data-role='button' data-mini='true' data-inline='true' data-theme='b'", true);
                return;
            }
            if($type == 'button')
            {
                if($method != 'edit' and $method != 'copy' and $method != 'delete')
                {
                    echo html::a($link, "<i class='$class'></i> " . $title, $target, "class='btn $extraClass' $misc", true);
                }
                else
                {
                    echo html::a($link, "<i class='$class'></i>", $target, "class='btn $extraClass' title='$title' $misc", false);
                }
            }
            else
            {
                echo html::a($link, "<i class='$class'></i>", $target, "class='btn-icon $extraClass' title='$title' $misc", false);
            }
        }
        else
        {
            if($type == 'list')
            {
                echo "<button type='button' class='disabled btn-icon $extraClass'><i class='$class' title='$title' $misc></i></button>";
            }
        }
    }

    /**
     * Print backLink and preLink and nextLink.
     * 
     * @param  string $backLink 
     * @param  object $preAndNext 
     * @access public
     * @return void
     */
    static public function printRPN($backLink, $preAndNext = '', $linkTemplate = '')
    {
        global $lang, $app;
        if(isonlybody()) return false;

        echo html::a($backLink, '<i class="icon-goback icon-level-up icon-large icon-rotate-270"></i>', '', "class='btn' title={$lang->goback}");

        if(isset($preAndNext->pre) and $preAndNext->pre) 
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->pre->case)) ? 'case' : 'id';
            $title = isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name;
            $title = '#' . $preAndNext->pre->$id . ' ' . $title . ' ' . $lang->preShortcutKey;
            $link  = $linkTemplate ? sprintf($linkTemplate, $preAndNext->pre->$id) : inLink('view', "ID={$preAndNext->pre->$id}");
            echo html::a($link, '<i class="icon-pre icon-chevron-left"></i>', '', "id='pre' class='btn' title='{$title}'");
        }
        if(isset($preAndNext->next) and $preAndNext->next) 
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->next->case)) ? 'case' : 'id';
            $title = isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name;
            $title = '#' . $preAndNext->next->$id . ' ' . $title . ' ' . $lang->nextShortcutKey;
            $link  = $linkTemplate ? sprintf($linkTemplate, $preAndNext->next->$id) : inLink('view', "ID={$preAndNext->next->$id}");
            echo html::a($link, '<i class="icon-pre icon-chevron-right"></i>', '', "id='next' class='btn' title='$title'");
        }
    }

    /**
     * Create changes of one object.
     * 
     * @param mixed $old    the old object
     * @param mixed $new    the new object
     * @static
     * @access public
     * @return array
     */
    public static function createChanges($old, $new)
    {
        global $config;
        $changes    = array();
        $magicQuote = get_magic_quotes_gpc();
        foreach($new as $key => $value)
        {
            if(strtolower($key) == 'lastediteddate') continue;
            if(strtolower($key) == 'lasteditedby')   continue;
            if(strtolower($key) == 'assigneddate')   continue;
            if(strtolower($key) == 'editedby')       continue;
            if(strtolower($key) == 'editeddate')     continue;

            if($magicQuote) $value = stripslashes($value);
            if($value != stripslashes($old->$key))
            { 
                $diff = '';
                if(substr_count($value, "\n") > 1     or 
                    substr_count($old->$key, "\n") > 1 or 
                    strpos('name,title,desc,spec,steps,content,digest,verify,report', strtolower($key)) !== false)
                {
                    $diff = commonModel::diff($old->$key, $value);
                }
                $changes[] = array('field' => $key, 'old' => $old->$key, 'new' => $value, 'diff' => $diff);
            }
        }
        return $changes;
    }

    /**
     * Diff two string. (see phpt)
     * 
     * @param string $text1 
     * @param string $text2 
     * @static
     * @access public
     * @return string
     */
    public static function diff($text1, $text2)
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
     * Judge Suhosin Setting whether the actual size of post data is large than the setting size.
     * 
     * @param  int    $numberOfItems 
     * @param  int    $columns 
     * @access public
     * @return void
     */
    public function judgeSuhosinSetting($numberOfItems, $columns)
    {
        if(extension_loaded('suhosin'))
        {
            $maxPostVars    = ini_get('suhosin.post.max_vars');
            $maxRequestVars = ini_get('suhosin.request.max_vars');
            if($numberOfItems * $columns > $maxPostVars or $numberOfItems * $columns > $maxRequestVars) return true;
        }

        return false;
    }

    /**
     * Get the previous and next object.
     * 
     * @param  string $type story|task|bug|case
     * @param  string $objectIDs 
     * @param  string $objectID 
     * @access public
     * @return void
     */
    public function getPreAndNextObject($type, $objectID)
    {
        $preAndNextObject = new stdClass();

        if(strpos('story, task, bug, testcase, doc', $type) === false) return $preAndNextObject;

        /* Use existObject when the preAndNextObject of this objectID has exist in session. */
        $existObject = $type . 'PreAndNext';
        if(isset($_SESSION[$existObject]) and $_SESSION[$existObject]['objectID'] == $objectID) return $_SESSION[$existObject]['preAndNextObject'];

        /* Get objectIDs. */
        $table             = $this->config->objectTables[$type];
        $queryCondition    = $type . 'QueryCondition';
        $typeOnlyCondition = $type . 'OnlyCondition';
        $queryCondition = $this->session->$queryCondition;
        $orderBy = $type . 'OrderBy';
        $orderBy = $this->session->$orderBy;
        $orderBy = str_replace('`left`', 'left', $orderBy); // process the `left` to left.

        if(empty($queryCondition) or $this->session->$typeOnlyCondition)
        {
            $queryObjects = $this->dao->select('*')->from($table)->where('id')->eq($objectID)
                ->beginIF($queryCondition != false)->orWhere($queryCondition)->fi()
                ->beginIF($orderBy != false)->orderBy($orderBy)->fi()
                ->query();
        }
        else
        {
            $queryObjects = $this->dao->query($queryCondition . " ORDER BY $orderBy");
        }

        $preObj  = false;
        $preAndNextObject->pre  = '';
        $preAndNextObject->next = '';
        while($object = $queryObjects->fetch())
        {
            $key = (!$this->session->$typeOnlyCondition and $type == 'testcase' and isset($object->case)) ? 'case' : 'id';
            $id  = $object->$key;

            /* Get next object. */
            if($preObj === true)
            {
                $preAndNextObject->next = $object;
                break;
            }

            /* Get pre object. */
            if($id == $objectID)
            {
                if($preObj) $preAndNextObject->pre = $preObj;
                $preObj = true;
            }
            if($preObj !== true) $preObj = $object;
        }

        $this->session->set($existObject, array('objectID' => $objectID, 'preAndNextObject' => $preAndNextObject));
        return $preAndNextObject;
    }

    /**
     * Save one executed query.
     * 
     * @param  string    $sql 
     * @param  string    $objectType story|task|bug|testcase 
     * @access public
     * @return void
     */
    public function saveQueryCondition($sql, $objectType, $onlyCondition = true)
    {
        /* Set the query condition session. */
        if($onlyCondition)
        {
            $queryCondition = explode('WHERE', $sql);
            $queryCondition = explode('ORDER', $queryCondition[1]);
            $queryCondition = str_replace('t1.', '', $queryCondition[0]);
        }
        else
        {
            $queryCondition = explode('ORDER', $sql);
            $queryCondition = $queryCondition[0];
        }
        $queryCondition = trim($queryCondition);
        if(empty($queryCondition)) $queryCondition = "1=1";

        $this->session->set($objectType . 'QueryCondition', $queryCondition);
        $this->session->set($objectType . 'OnlyCondition', $onlyCondition);

        /* Set the query condition session. */
        $orderBy = explode('ORDER BY', $sql);
        if(isset($orderBy[1]))
        {
            $orderBy = explode('limit', $orderBy[1]);
            $orderBy = str_replace('t1.', '', $orderBy[0]);

            $this->session->set($objectType . 'OrderBy', $orderBy);
        }
        else
        {
            $this->session->set($objectType . 'OrderBy', '');
        }
    }

    /**
     * Remove duplicate for story, task, bug, case, doc.
     * 
     * @param  string       $type  e.g. story task bug case doc.
     * @param  array|object $data 
     * @param  string       $condition 
     * @access public
     * @return array
     */
    public function removeDuplicate($type, $data = '', $condition = '')
    {
        $table      = $this->config->objectTables[$type];
        $titleField = $type == 'task' ? 'name' : 'title';
        $date       = date(DT_DATETIME1, time() - $this->config->duplicateTime);
        $dateField  = $type == 'doc' ? 'addedDate' : 'openedDate';
        $titles     = $data->$titleField;

        if(empty($titles)) return false;
        $duplicate = $this->dao->select("id,$titleField")->from($table)
            ->where('deleted')->eq(0)
            ->andWhere($titleField)->in($titles)
            ->andWhere($dateField)->ge($date)->fi()
            ->beginIF($condition)->andWhere($condition)->fi()
            ->fetchPairs();

        if($duplicate and is_string($titles)) return array('stop' => true, 'duplicate' => key($duplicate));
        if($duplicate and is_array($titles))
        {
            foreach($titles as $i => $title)
            {
                if(in_array($title, $duplicate)) unset($titles[$i]);
            }
            $data->$titleField = $titles;
        }
        return array('stop' => false, 'data' => $data);
    }

    /**
     * Append order by.
     * 
     * @param  string $orderBy 
     * @param  string $append 
     * @access public
     * @return string
     */
    public function appendOrder($orderBy, $append = 'id')
    {
        list($firstOrder) = explode(',', $orderBy);
        $sort = strpos($firstOrder, '_') === false ? '_asc' : strstr($firstOrder, '_');
        return strpos($orderBy, $append) === false ? $orderBy . ',' . $append . $sort : $orderBy;
    }

    /**
     * Check field exists
     * 
     * @param  string    $table 
     * @param  string    $field 
     * @access public
     * @return bool
     */
    public function checkField($table, $field)
    {
        $fields   = $this->dao->query("DESC $table")->fetchAll();
        $hasField = false;
        foreach($fields as $fieldObj)
        {
            if($field == $fieldObj->Field)
            {
                $hasField = true;
                break;
            }
        }
        return $hasField;
    }

    /**
     * Check upgrade's status file is ok or not.
     * 
     * @access public
     * @return void
     */
    public function checkUpgradeStatus()
    {
        $statusFile = $this->loadModel('upgrade')->checkSafeFile();
        if($statusFile)
        {
            echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>";
            echo "<table align='center' style='margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td>";
            printf($this->lang->upgrade->setStatusFile, $statusFile, $statusFile, $statusFile);
            die('</td></tr></table></body></html>');
        }
    }

    /**
     * Check the user has permission to access this method, if not, locate to the login page or deny page.
     * 
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($this->isOpenMethod($module, $method)) return true;
        if(!$this->loadModel('user')->isLogon() and $this->server->php_auth_user) $this->user->identifyByPhpAuth();
        if(!$this->loadModel('user')->isLogon() and $this->cookie->za) $this->user->identifyByCookie();

        if(isset($this->app->user))
        {
            if(!commonModel::hasPriv($module, $method)) $this->deny($module, $method);
        }
        else
        {
            $referer  = helper::safe64Encode($this->app->getURI(true));
            die(js::locate(helper::createLink('user', 'login', "referer=$referer")));
        }
    }

    /**
     * Check the user has permisson of one method of one module.
     * 
     * @param  string $module 
     * @param  string $method 
     * @static
     * @access public
     * @return bool
     */
    public static function hasPriv($module, $method)
    {
        global $app, $lang;

        /* Check is the super admin or not. */
        $account = ',' . $app->user->account . ',';
        if(strpos($app->company->admins, $account) !== false) return true; 

        /* If not super admin, check the rights. */
        $rights  = $app->user->rights['rights'];
        $acls    = $app->user->rights['acls'];
        $module  = strtolower($module);
        $method  = strtolower($method);
        if(isset($rights[$module][$method]))
        {
            if(empty($acls['views'])) return true;
            $menu = isset($lang->menugroup->$module) ? $lang->menugroup->$module : $module;
            $menu = strtolower($menu);
            if($menu != 'qa' and !isset($lang->$menu->menu)) return true;
            if($menu == 'my' or $menu == 'index' or $module == 'tree') return true;
            if($module == 'company' and $method == 'dynamic') return true;
            if($module == 'action' and $method == 'editcomment') return true;
            if(!isset($acls['views'][$menu])) return false;
            return true;
        }
        return false;
    }

    /**
     * Check whether IP in white list.
     *
     * @access public
     * @return bool
     */
    public function checkIP()
    {
        $ip = $this->server->remote_addr;

        $ipWhiteList = $this->config->ipWhiteList;

        /* If the ip white list is '*'. */
        if($ipWhiteList == '*') return true;

        /* The ip is same as ip in white list. */
        if($ip == $ipWhiteList) return true;

        /* If the ip in white list is like 192.168.1.1-192.168.1.10. */
        if(strpos($ipWhiteList, '-') !== false)
        {
            list($min, $max) = explode('-', $ipWhiteList);
            $min = ip2long(trim($min));
            $max = ip2long(trim($max));
            $ip  = ip2long(trim($ip));

            return $ip >= $min and $ip <= $max;
        }

        /* If the ip in white list is in IP/CIDR format eg 127.0.0.1/24. Thanks to zcat. */
        if(strpos($ipWhiteList, '/') == false) $ipWhiteList .= '/32';
        list($ipWhiteList, $netmask) = explode('/', $ipWhiteList, 2);

        $ip          = ip2long($ip);
        $ipWhiteList = ip2long($ipWhiteList);
        $wildcard    = pow(2, (32 - $netmask)) - 1;
        $netmask     = ~ $wildcard;

        return (($ip & $netmask) == ($ipWhiteList & $netmask));
    }

    /**
     * Replace the %s of one key of a menu by $params.
     *
     * All the menus are defined in the common's language file. But there're many dynamic params, so in the defination,
     * we used %s as placeholder. These %s should be setted in one module.
     *
     * The items of one module's menu may be an string or array. For example, please see module/common/lang.
     * 
     * @param  string $object     the menus of one module
     * @param  string $key        the menu item to be replaced
     * @param  string $params     the params passed to the menu item
     * @access public
     * @return void
     */
    public static function setMenuVars($menu, $key, $params)
    {
        if(is_array($params))
        {
            if(is_array($menu->$key))
            {
                $menu->$key = (object)$menu->$key;
                $menu->$key->link = vsprintf($menu->$key->link, $params);
                $menu->$key = (array)$menu->$key;
            }
            else 
            {
                $menu->$key = vsprintf($menu->$key, $params);
            }
        }
        else
        {
            if(is_array($menu->$key))
            {
                $menu->$key = (object)$menu->$key;
                $menu->$key->link = sprintf($menu->$key->link, $params);
                $menu->$key = (array)$menu->$key;
            }
            else
            {
                $menu->$key = sprintf($menu->$key, $params);
            }
        }
    }

    /**
     * Get the full url of the system.
     * 
     * @access public
     * @return string
     */
    public static function getSysURL()
    {
        $httpType = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
        $httpHost = $_SERVER['HTTP_HOST'];
        return "$httpType://$httpHost";
    }
}

class common extends commonModel
{
}
