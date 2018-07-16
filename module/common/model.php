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
            define('FIRST_RUN', true);
            $this->sendHeader();
            $this->setCompany();
            $this->setUser();
            $this->loadConfigFromDB();
            if((strpos($this->config->global->version, 'pro') !== false && version_compare($this->config->global->version, 'pro2.3.beta', '>'))
                || (strpos($this->config->global->version, 'biz') !== false)
                || version_compare($this->config->global->version, '4.3.beta', '>'))
            {
                $this->loadCustomFromDB();
            }

            if(!$this->checkIP()) die($this->lang->ipLimited);
            $this->app->loadLang('company');
        }
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
            $user->admin      = false;
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
        if(isset($this->config->system->common)) $this->app->mergeConfig($this->config->system->common, 'common');
        if(isset($this->config->personal->common)) $this->app->mergeConfig($this->config->personal->common, 'common');
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
        $records = $this->loadModel('custom')->getAllLang();
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
        if($module == 'misc' and $method == 'checktable') return true;
        if($module == 'misc' and $method == 'qrcode') return true;
        if($module == 'misc' and $method == 'about') return true;
        if($module == 'misc' and $method == 'checkupdate') return true;
        if($module == 'misc' and $method == 'ping')  return true;
        if($module == 'sso' and $method == 'login')  return true;
        if($module == 'sso' and $method == 'logout') return true;
        if($module == 'sso' and $method == 'bind') return true;
        if($module == 'sso' and $method == 'gettodolist') return true;
        if($module == 'block' and $method == 'main' and isset($_GET['hash'])) return true;
        if($module == 'file' and $method == 'read') return true;

        if($this->loadModel('user')->isLogon() or ($this->app->company->guest and $this->app->user->account == 'guest'))
        {
            if(stripos($method, 'ajax') !== false) return true;
            if(stripos($method, 'downnotify') !== false) return true;
            if($module == 'block' and $method == 'main') return true;
            if($module == 'misc' and $method == 'changelog') return true;
            if($module == 'tutorial') return true;
            if($module == 'block') return true;
            if($module == 'product' and $method == 'showerrornone') return true;
        }
        return false;
    }

    /**
     * Deny access.
     *
     * @access public
     * @return mixed
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
            echo "<script>document.getElementById('denylink').click();</script>";
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
    public static function printUserBar()
    {
        global $lang, $app;

        if(isset($app->user))
        {
            $isGuest = $app->user->account == 'guest';

            echo "<a class='dropdown-toggle' data-toggle='dropdown'>";
            echo "<div class='avatar avatar-sm bg-secondary avatar-circle'>" . strtoupper($app->user->account{0}) . "</div>\n";
            echo "<span class='user-name'>" . (empty($app->user->realname) ? $app->user->account : $app->user->realname) . '</span>';
            echo "<span class='caret'></span>";
            echo '</a>';
            echo "<ul class='dropdown-menu pull-right'>";
            if(!$isGuest)
            {
                echo '<li class="user-profile-item">';
                echo "<a href='". helper::createLink('my', 'profile', '', '', true) . "' class='iframe" . (!empty($app->user->role) && isset($lang->user->roleList[$app->user->role]) ? '' : ' no-role') . "' data-width='600'>";
                echo "<div class='avatar avatar bg-secondary avatar-circle'>" . strtoupper($app->user->account{0}) . "</div>\n";
                echo '<div class="user-profile-name">' . (empty($app->user->realname) ? $app->user->account : $app->user->realname) . '</div>';
                if(isset($lang->user->roleList[$app->user->role])) echo '<div class="user-profile-role">' . $lang->user->roleList[$app->user->role] . '</div>';
                echo '</a></li><li class="divider"></li>';
                echo '<li>' . html::a(helper::createLink('my', 'profile', '', '', true), $lang->profile, '', "class='iframe' data-width='600'") . '</li>';
                echo '<li>' . html::a(helper::createLink('my', 'changepassword', '', '', true), $lang->changePassword, '', "class='iframe' data-width='500'") . '</li>';

                echo "<li class='divider'></li>";
            }

            echo "<li class='dropdown-submenu'>";
            echo "<a href='javascript:;'>" . $lang->theme . "</a><ul class='dropdown-menu pull-left'>";
            foreach($app->lang->themes as $key => $value)
            {
                echo "<li " . ($app->cookie->theme == $key ? "class='selected'" : '') . "><a href='javascript:selectTheme(\"$key\");' data-value='" . $key . "'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            echo "<li class='dropdown-submenu'>";
            echo "<a href='javascript:;'>" . $lang->lang . "</a><ul class='dropdown-menu pull-left'>";
            foreach ($app->config->langs as $key => $value)
            {
                echo "<li " . ($app->cookie->lang == $key ? "class='selected'" : '') . "><a href='javascript:selectLang(\"$key\");'>" . $value . "</a></li>";
            }
            echo '</ul></li>';

            if(!$isGuest and !commonModel::isTutorialMode() and $app->viewType != 'mhtml')
            {
                $customLink = helper::createLink('custom', 'ajaxMenu', "module={$app->getModuleName()}&method={$app->getMethodName()}", '', true);
                echo "<li class='custom-item'><a href='$customLink' data-toggle='modal' data-type='iframe' data-icon='cog' data-width='80%'>$lang->customMenu</a></li>";
            }

            echo '<li class="divider"></li>';
            echo '<li>';
            if($isGuest)
            {
                echo html::a(helper::createLink('user', 'login'), $lang->login);
            }
            else
            {
                echo html::a(helper::createLink('user', 'logout'), $lang->logout);
            }
            echo '</li></ul>';
        }
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
        echo "<ul id='extraNav' class='nav nav-default'>";
        echo "<li class='dropdown'>";
        echo "<a data-toggle='dropdown'>" . $lang->help . " <span class='caret'></span></a>";
        echo "<ul class='dropdown-menu'>";
        if($config->global->flow == 'full' && !commonModel::isTutorialMode() and $app->user->account != 'guest') echo '<li>' . html::a(helper::createLink('tutorial', 'start'), $lang->tutorial, '', "class='iframe' data-class-name='modal-inverse' data-width='800' data-headerless='true' data-backdrop='true' data-keyboard='true'") . "</li>";
        echo '<li>' . html::a($lang->manualUrl, $lang->manual, '_blank', "class='open-help-tab'") . '</li>';
        echo '<li>' . html::a(helper::createLink('misc', 'changeLog'), $lang->changeLog, '', "class='iframe' data-width='800' data-headerless='true' data-backdrop='true' data-keyboard='true'") . '</li>';
        echo "</ul></li>\n";
        echo '<li>' . html::a(helper::createLink('misc', 'about'), $lang->aboutZenTao, '', "class='about iframe' data-width='900' data-headerless='true' data-backdrop='true' data-keyboard='true' data-class='modal-about'") . '</li>';
        echo '</ul>';
    }

    /**
     * Create menu item link
     *
     * @param object $menuItem
     *
     * @static
     * @access public
     * @return string
     */
    public static function createMenuLink($menuItem)
    {
        $link = $menuItem->link;
        if(is_array($menuItem->link))
        {
            $vars = isset($menuItem->link['vars']) ? $menuItem->link['vars'] : '';
            if(isset($menuItem->tutorial) && $menuItem->tutorial)
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
     * Create sub menu by settings in lang files.
     *
     * @param  array    $items
     * @param  mixed    $replace
     * @static
     * @access public
     * @return array
     */
    public static function createSubMenu($items, $replace)
    {
        $subMenu = array();
        foreach($items as $subMenuKey => $subMenuLink)
        {
            if(is_array($subMenuLink) and isset($subMenuLink['link'])) $subMenuLink = $subMenuLink['link'];
            $subMenuLink = vsprintf($subMenuLink, $replace);
            list($subMenuName, $subMenuModule, $subMenuMethod, $subMenuParams) = explode('|', $subMenuLink);

            $link = array();
            $link['module'] = $subMenuModule;
            $link['method'] = $subMenuMethod;
            $link['vars']   = $subMenuParams;

            $menu = new stdclass();
            $menu->name   = $subMenuKey;
            $menu->link   = $link;
            $menu->text   = $subMenuName;
            $menu->hidden = false;
            $subMenu[$subMenuKey] = $menu;
        }

        return $subMenu;
    }

    /**
     * Print the main menu.
     *
     * @param  string $moduleName
     * @param  string $methodName
     *
     * @static
     * @access public
     * @return void
     */
    public static function printMainmenu($moduleName, $methodName = '')
    {
        global $app, $lang;

        /* Set the main main menu. */
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;

        /* Print all main menus. */
        $menu       = customModel::getMainMenu();
        $activeName = 'active';
        $lastMenu   = end($menu);

        echo "<ul class='nav nav-default'>\n";
        foreach($menu as $menuItem)
        {
            if(empty($menuItem->hidden))
            {
                $active = $menuItem->name == $mainMenu ? "class='$activeName'" : '';
                $link   = commonModel::createMenuLink($menuItem);
                echo "<li $active data-id='$menuItem->name'><a href='$link' $active>$menuItem->text</a></li>\n";
            }

            if(($lastMenu->name != $menuItem->name) && strpos($lang->dividerMenu, ",{$menuItem->name},") !== false) echo "<li class='divider'></li>";

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
        global $app, $config, $lang;
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
        if(empty($lang->searchObjects[$searchObject]))
        {
            $searchObject = 'bug';
            if($config->global->flow == 'onlyStory') $searchObject = 'story';
            if($config->global->flow == 'onlyTask')  $searchObject = 'task';
        }

        echo "<div id='searchbox'>";
        echo "<div class='input-group'>";
        echo "<div class='input-group-btn'>";
        echo "<a data-toggle='dropdown' class='btn btn-link'><span id='searchTypeName'>" . $lang->searchObjects[$searchObject] . "</span> <span class='caret'></span></a>";
        echo html::hidden('searchType', $searchObject);
        echo "<ul id='searchTypeMenu' class='dropdown-menu'>";
        foreach ($lang->searchObjects as $key => $value)
        {
            $class = $key == $searchObject ? "class='selected'" : '';
            echo "<li $class><a href='javascript:$.setSearchType(\"$key\");' data-value='{$key}'>{$value}</a></li>";
        }
        echo '</ul></div>';
        echo "<input id='searchInput' class='form-control search-input' type='search' onclick='this.value=\"\"' onkeydown='if(event.keyCode==13) $.gotoObject();' placeholder='" . $lang->searchTips . "'/>";
        echo '</div>';
        echo "<a href='javascript:$.gotoObject();' class='btn btn-link' id='searchGo'>GO!</a>";
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
        global $config, $lang, $app;

        if(!isset($lang->$moduleName->menu))
        {
            echo "<ul></ul>";
            return;
        }

        /* get current module and method. */
        $isTutorialMode = commonModel::isTutorialMode();
        $currentModule  = ($isTutorialMode and defined('WIZARD_MODULE')) ? WIZARD_MODULE : $app->getModuleName();
        $currentMethod  = ($isTutorialMode and defined('WIZARD_METHOD')) ? WIZARD_METHOD : $app->getMethodName();
        $menu           = customModel::getModuleMenu($moduleName);
        $isMobile       = $app->viewType === 'mhtml';

        /* The beginning of the menu. */
        echo $isMobile ? '' : "<ul class='nav nav-default'>\n";

        if(isset($lang->menugroup->$moduleName)) $moduleName = $lang->menugroup->$moduleName;
        /* Cycling to print every sub menu. */
        foreach($menu as $menuItem)
        {
            if(isset($lang->$moduleName->dividerMenu) and strpos($lang->$moduleName->dividerMenu, ",{$menuItem->name},") !== false) echo "<li class='divider'></li>";
            if(isset($menuItem->hidden) && $menuItem->hidden) continue;
            if($isMobile and empty($menuItem->link)) continue;

            /* Init the these vars. */
            $alias     = isset($menuItem->alias) ? $menuItem->alias : '';
            $subModule = isset($menuItem->subModule) ? explode(',', $menuItem->subModule) : array();
            $class     = isset($menuItem->class) ? $menuItem->class : '';
            $active    = '';
            if($subModule and in_array($currentModule, $subModule)) $active = 'active';
            if($alias and $moduleName == $currentModule and strpos(",$alias,", ",$currentMethod,") !== false) $active = 'active';
            if($menuItem->link)
            {
                $target = '';
                $module = '';
                $method = '';
                $link   = commonModel::createMenuLink($menuItem);
                if(is_array($menuItem->link))
                {
                    if(isset($menuItem->link['target'])) $target = $menuItem->link['target'];
                    if(isset($menuItem->link['module'])) $module = $menuItem->link['module'];
                    if(isset($menuItem->link['method'])) $method = $menuItem->link['method'];
                }
                if($module == $currentModule and ($method == $currentMethod or strpos(",$alias,", ",$currentMethod,") !== false)) $active = 'active';

                /* Avoid user thinking the page is shaking when the menu toggle class 'active' */
                if($config->global->flow == 'onlyTest')
                {
                    if($currentModule == 'bug'       && $currentMethod == 'browse')  $active = '';
                    if($currentModule == 'testcase'  && $currentMethod == 'browse')  $active = '';
                    if($currentModule == 'testtask'  && $currentMethod == 'browse')  $active = '';
                    if($currentModule == 'testsuite' && $currentMethod == 'library') $active = '';
                }

                $label   = $menuItem->text;
                $subMenu = '';
                /* Print sub menus. */
                if(isset($menuItem->subMenu))
                {
                    $firstLink  = '';
                    $firstLabel = '';
                    foreach($menuItem->subMenu as $subMenuItem)
                    {
                        if($subMenuItem->hidden) continue;

                        $subActive = '';
                        $subModule = '';
                        $subMethod = '';
                        $subParams = '';
                        $subLabel  = $subMenuItem->text;
                        if(isset($subMenuItem->link['module'])) $subModule = $subMenuItem->link['module'];
                        if(isset($subMenuItem->link['method'])) $subMethod = $subMenuItem->link['method'];
                        if(isset($subMenuItem->link['vars']))   $subParams = $subMenuItem->link['vars'];

                        $subLink = helper::createLink($subModule, $subMethod, $subParams);

                        if($config->global->flow != 'onlyTest' && $currentModule == strtolower($subModule) && $currentMethod == strtolower($subMethod)) $subActive = 'active';

                        $subMenu .= "<li class='$subActive' data-id='$subMenuItem->name'>" . html::a($subLink, $subLabel) . '</li>';

                        if(!$firstLink)  $firstLink  = $subLink;
                        if(!$firstLabel) $firstLabel = $subLabel;
                    }

                    if(!$firstLink or !$firstLabel) continue;

                    if($config->global->flow == 'onlyTest')
                    {
                        $link = $firstLink;
                    }
                    else
                    {
                        $link  = $firstLink;
                        $label = $firstLabel;
                    }

                    if($subMenu)
                    {
                        $label   .= "<span class='caret'></span>";
                        $subMenu  = "<ul class='dropdown-menu'>{$subMenu}</ul>";
                    }
                }

                $menuItemHtml = "<li class='$class $active' data-id='$menuItem->name'>" . html::a($link, $label, $target) . $subMenu . "</li>\n";
                if($isMobile) $menuItemHtml = html::a($link, $menuItem->text, $target, "class='$class $active'") . "\n";
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
        echo "<ul class='breadcrumb'>";
        echo '<li>' . html::a(helper::createLink('my', 'index'), $lang->zentaoPMS) . '</li>';
        if($moduleName != 'index')
        {
            if(!isset($lang->menu->$mainMenu)) return;
            $menuLink = $mainMenu == 'admin' ? $lang->adminMenu : $lang->menu->$mainMenu;
            list($menuLabel, $module, $method) = explode('|', $menuLink);
            echo '<li>' . html::a(helper::createLink($module, $method), $menuLabel) . '</li>';
        }
        else
        {
            echo '<li>' . $lang->index->common . '</li>';
        }
        if(empty($position))
        {
            echo '</ul>';
            return;
        }
        foreach($position as $key => $link)
        {
            echo "<li class='active'>" . $link . '</li>';
        }
        echo '</ul>';
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
            echo html::a(helper::createLink('misc', 'downNotify'), "<i class='icon-bell'></i>", '', "title='$lang->downNotify' class='text-primary'") . ' &nbsp; ';
        }
    }

    /**
     * Print QR code Link.
     *
     * @param string $color
     *
     * @static
     * @access public
     * @return void
     */
    public static function printQRCodeLink($color = '')
    {
        global $lang;
        echo html::a('javascript:;', "<i class='icon-qrcode'></i>", '', "class='qrCode $color' id='qrcodeBtn' title='{$lang->user->mobileLogin}'");
        echo "<div class='popover top' id='qrcodePopover'><div class='arrow'></div><h3 class='popover-title'>{$lang->user->mobileLogin}</h3><div class='popover-content'><img src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'></div></div>";
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
     *
     * @access public
     * @return void
     */
    public static function printOrderLink($fieldName, $orderBy, $vars, $label, $module = '', $method = '')
    {
        global $lang, $app;
        if(empty($module)) $module = $app->getModuleName();
        if(empty($method)) $method = $app->getMethodName();
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
            $orderBy   = "" . trim($fieldName, '`') . "" . '_' . 'asc';
            $className = 'header';
        }
        $link = helper::createLink($module, $method, sprintf($vars, $orderBy));
        echo $isMobile ? html::a($link, $label, '', "class='$className'") : html::a($link, $label, '', "class='$className'");
    }

    /**
     *
     * Print link to an modules' methd.
     *
     * Before printing, check the privilege first. If no privilege, return fasle. Else, print the link, return true.
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
    public static function printLink($module, $method, $vars = '', $label, $target = '', $misc = '', $newline = true, $onlyBody = false, $object = null)
    {
        if(!commonModel::hasPriv($module, $method, $object)) return false;
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
     * @param string $commentFormLink
     * @param object $object
     *
     * @static
     * @access public
     * @return mixed
     */
    public static function printCommentIcon($commentFormLink, $object = null)
    {
        global $lang;

        if(!commonModel::hasPriv('action', 'comment', $object)) return false;
        echo html::commonButton('<i class="icon icon-chat-line"></i> ' . $lang->action->create, '', 'btn btn-link pull-right btn-comment');
        echo <<<EOD
<div class="modal fade modal-comment">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title">{$lang->action->create}</h4>
      </div>
      <div class="modal-body">
    <form action="$commentFormLink" target='hiddenwin' method='post'>
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
EOD;
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
     * @static
     * @access public
     * @return void
     */
    public static function buildIconButton($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
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
        if(!commonModel::hasPriv($module, $method, $object)) return false;
        $link = helper::createLink($module, $method, $vars, '', $onlyBody);

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
                if($method != 'edit' and $method != 'copy' and $method != 'delete')
                {
                    return html::a($link, "<i class='$class'></i> " . "<span class='text'>{$title}</span>", $target, "class='btn btn-link $extraClass' $misc", true);
                }
                else
                {
                    return html::a($link, "<i class='$class'></i>", $target, "class='btn btn-link $extraClass' title='$title' $misc", false);
                }
            }
            else
            {
                return html::a($link, "<i class='$class'></i>", $target, "class='btn $extraClass' title='$title' $misc", false) . "\n";
            }
        }
        else
        {
            if($type == 'list')
            {
                return "<button type='button' class='disabled btn $extraClass'><i class='$class' title='$title' $misc></i></button>\n";
            }
        }
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
    public static function printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '', $extraClass = '', $onlyBody = false, $misc = '', $title = '')
    {
        echo common::buildIconButton($module, $method, $vars, $object, $type, $icon, $target, $extraClass, $onlyBody, $misc, $title);
    }

    /**
     * Print backLink and preLink and nextLink.
     *
     * @param string $backLink
     * @param object $preAndNext
     * @param string $linkTemplate
     *
     * @static
     * @access public
     * @return void
     */
    static public function printRPN($backLink, $preAndNext = '', $linkTemplate = '')
    {
        global $lang, $app;
        if(isonlybody()) return false;

        $title = $lang->goback . $lang->backShortcutKey;
        echo html::a($backLink, '<i class="icon-goback icon-back icon-large"></i>', '', "id='back' class='btn' title={$title}");

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
     * Print back link
     *
     * @param  string $backLink
     * @static
     * @access public
     * @return void
     */
    static public function printBack($backLink, $class = '')
    {
        global $lang, $app;
        if(isonlybody()) return false;

        if(empty($class)) $class = 'btn';
        $title = $lang->goback . $lang->backShortcutKey;
        echo html::a($backLink, '<i class="icon-goback icon-back"></i> ' . $lang->goback, '', "id='back' class='{$class}' title={$title}");
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

        echo "<nav class='container'>";
        if(isset($preAndNext->pre) and $preAndNext->pre)
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->pre->case)) ? 'case' : 'id';
            $title = isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name;
            $title = '#' . $preAndNext->pre->$id . ' ' . $title . ' ' . $lang->preShortcutKey;
            $link  = $linkTemplate ? sprintf($linkTemplate, $preAndNext->pre->$id) : inLink('view', "ID={$preAndNext->pre->$id}");
            echo html::a($link, '<i class="icon-pre icon-chevron-left"></i>', '', "id='prevPage' class='btn btn-info' title='{$title}'");
        }
        if(isset($preAndNext->next) and $preAndNext->next)
        {
            $id = (isset($_SESSION['testcaseOnlyCondition']) and !$_SESSION['testcaseOnlyCondition'] and $app->getModuleName() == 'testcase' and isset($preAndNext->next->case)) ? 'case' : 'id';
            $title = isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name;
            $title = '#' . $preAndNext->next->$id . ' ' . $title . ' ' . $lang->nextShortcutKey;
            $link  = $linkTemplate ? sprintf($linkTemplate, $preAndNext->next->$id) : inLink('view', "ID={$preAndNext->next->$id}");
            echo html::a($link, '<i class="icon-pre icon-chevron-right"></i>', '', "id='nextPage' class='btn btn-info' title='$title'");
        }
        echo '</nav>';
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
            if(is_object($value) or is_array($value)) continue;
            if(strtolower($key) == 'lastediteddate')  continue;
            if(strtolower($key) == 'lasteditedby')    continue;
            if(strtolower($key) == 'assigneddate')    continue;
            if(strtolower($key) == 'editedby')        continue;
            if(strtolower($key) == 'editeddate')      continue;
            if(strtolower($key) == 'uid')             continue;
            if(strtolower($key) == 'finisheddate' && $value == '')  continue;
            if(strtolower($key) == 'canceleddate' && $value == '')  continue;
            if(strtolower($key) == 'closeddate'   && $value == '')  continue;

            if($magicQuote) $value = stripslashes($value);
            if(isset($old->$key) and $value != stripslashes($old->$key))
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
     * @param  int    $countInputVars
     * @static
     * @access public
     * @return bool
     */
    public static function judgeSuhosinSetting($countInputVars)
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
     * Get the previous and next object.
     *
     * @param  string $type story|task|bug|case
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

        /* Get objectIDList. */
        $table             = $this->config->objectTables[$type];
        $queryCondition    = $type . 'QueryCondition';
        $typeOnlyCondition = $type . 'OnlyCondition';
        $queryCondition = $this->session->$queryCondition;
        $orderBy = $type . 'OrderBy';
        $orderBy = $this->session->$orderBy;

        if(empty($queryCondition) or $this->session->$typeOnlyCondition)
        {
            $queryObjects = $this->dao->select('*')->from($table)->where('id')->eq($objectID)
                ->beginIF($queryCondition != false)->orWhere($queryCondition)->fi()
                ->beginIF($orderBy != false)->orderBy($orderBy)->fi()
                ->query();
        }
        else
        {
            $queryObjects = $this->dao->query($queryCondition . (empty($orderBy) ? '' : " ORDER BY $orderBy"));
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
            $queryCondition = isset($queryCondition[1]) ? $queryCondition[1] : '';
            if($queryCondition)
            {
                $queryCondition = explode('ORDER', $queryCondition);
                $queryCondition = str_replace('t1.', '', $queryCondition[0]);
            }
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
        $orderBy = isset($orderBy[1]) ? $orderBy[1] : '';
        if($orderBy)
        {
            $orderBy = explode('LIMIT', $orderBy);
            $orderBy = $orderBy[0];
            if($onlyCondition) $orderBy = str_replace('t1.', '', $orderBy);
        }
        $this->session->set($objectType . 'OrderBy', $orderBy);
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
            $cmd = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? $this->lang->upgrade->createFileWinCMD : $this->lang->upgrade->createFileLinuxCMD;
            $cmd = sprintf($cmd, $statusFile);

            echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>";
            echo "<table align='center' style='margin-top:100px; border:1px solid gray; font-size:14px;padding:8px;'><tr><td>";
            printf($this->lang->upgrade->setStatusFile, $cmd, $statusFile);
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
        if(isset($this->app->user->modifyPassword) and $this->app->user->modifyPassword and ($module != 'my' or $method != 'changepassword')) die(js::locate(helper::createLink('my', 'changepassword')));
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
    public static function hasPriv($module, $method, $object = null)
    {
        global $app, $lang;

        /* Check is the super admin or not. */
        if(!empty($app->user->admin) || strpos($app->company->admins, ",{$app->user->account},") !== false) return true;
        /* If not super admin, check the rights. */
        $rights  = $app->user->rights['rights'];
        $acls    = $app->user->rights['acls'];
        $module  = strtolower($module);
        $method  = strtolower($method);

        if(isset($rights[$module][$method]))
        {
            if(!commonModel::hasDBPriv($object, $module, $method)) return false;

            if(empty($acls['views'])) return true;
            $menu = isset($lang->menugroup->$module) ? $lang->menugroup->$module : $module;
            $menu = strtolower($menu);
            if($menu != 'qa' and !isset($lang->$menu->menu)) return true;
            if($menu == 'my' or $menu == 'index' or $module == 'tree') return true;
            if($module == 'company' and $method == 'dynamic') return true;
            if($module == 'action' and $method == 'editcomment') return true;
            if($module == 'action' and $method == 'comment') return true;
            if(!isset($acls['views'][$menu])) return false;

            return true;
        }

        return false;
    }

    /**
     * Check db priv.
     *
     * @param  object $object
     * @param  string $module
     * @param  string $method
     * @static
     * @access public
     * @return void
     */
    public static function hasDBPriv($object, $module = '', $method = '')
    {
        global $app;

        if(!empty($app->user->admin)) return true;
        if($module == 'todo' and ($method == 'create' or $method == 'batchcreate')) return true;
        if($module == 'effort' and ($method == 'batchcreate' or $method == 'createforobject')) return true;

        // limited project
        $limitedProject = false;
        if(!empty($module) && $module == 'task' && !empty($object->project) or
            !empty($module) && $module == 'project' && !empty($object->id)
        )
        {
            $objectID = '';
            if($module == 'project' and !empty($object->id))  $objectID = $object->id;
            if($module == 'task' and !empty($object->project))$objectID = $object->project;

            $limitedProjects = !empty($_SESSION['limitedProjects']) ? $_SESSION['limitedProjects'] : '';
            if(strpos(",{$limitedProjects},", ",$objectID,") !== false) $limitedProject = true;
        }
        if(empty($app->user->rights['rights']['my']['limited']) && !$limitedProject) return true;

        if(!is_null($method) && strpos($method, 'batch')  === 0) return false;
        if(!is_null($method) && strpos($method, 'link')   === 0) return false;
        if(!is_null($method) && strpos($method, 'create') === 0) return false;
        if(!is_null($method) && strpos($method, 'import') === 0) return false;

        if(is_null($object)) return true;

        if(!empty($object->openedBy)     && $object->openedBy     == $app->user->account or
            !empty($object->addedBy)      && $object->addedBy      == $app->user->account or
            !empty($object->assignedTo)   && $object->assignedTo   == $app->user->account or
            !empty($object->finishedBy)   && $object->finishedBy   == $app->user->account or
            !empty($object->canceledBy)   && $object->canceledBy   == $app->user->account or
            !empty($object->closedBy)     && $object->closedBy     == $app->user->account or
            !empty($object->lastEditedBy) && $object->lastEditedBy == $app->user->account)
        {
            return true;
        }

        return false;
    }

    /**
     * Check whether IP in white list.
     *
     * @param  string $ipWhiteList
     * @access public
     * @return bool
     */
    public function checkIP($ipWhiteList = '')
    {
        $ip = $this->server->remote_addr;

        if(!$ipWhiteList) $ipWhiteList = $this->config->ipWhiteList;

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
        if(!isset($menu->$key)) return false;

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

    /**
     * Check whether view type is tutorial
     * @access public
     * @return boolean
     */
    public static function isTutorialMode()
    {
        return (isset($_SESSION['tutorialMode']) and $_SESSION['tutorialMode']);
    }

    /**
     * Convert items to Pinyin.
     *
     * @param  array    $items
     * @static
     * @access public
     * @return array
     */
    public static function convert2Pinyin($items)
    {
        global $app;
        static $allConverted = array();
        static $pinyin;
        if(empty($pinyin)) $pinyin = $app->loadClass('pinyin');

        $sign = ' aNdAnD ';
        $notConvertedItems = array_diff($items, array_keys($allConverted));

        if($notConvertedItems)
        {
            $convertedPinYin = $pinyin->romanize(join($sign, $notConvertedItems));
            $itemsPinYin     = explode(trim($sign), $convertedPinYin);
            foreach($notConvertedItems as $item)
            {
                $itemPinYin  = array_shift($itemsPinYin);
                $wordsPinYin = explode("\t", trim($itemPinYin));

                $abbr = '';
                foreach($wordsPinYin as $i => $wordPinyin)
                {
                    if($wordPinyin) $abbr .= $wordPinyin[0];
                }

                $allConverted[$item] = strtolower(join($wordsPinYin) . ' ' . $abbr);
            }
        }

        $convertedItems = array();
        foreach($items as $item) $convertedItems[$item] = zget($allConverted, $item, null);

        return $convertedItems;
    }

    /**
     * Check an entry.
     *
     * @access public
     * @return void
     */
    public function checkEntry()
    {
        $this->loadModel('entry');

        if($this->session->valid_entry)
        {
            if(!$this->session->entry_code) $this->response('SESSION_CODE_MISSING');
            if($this->session->valid_entry != md5(md5($this->get->code) . $this->server->remote_addr)) $this->response('SESSION_VERIFY_FAILED');
            return true;
        }

        if(!$this->get->code)  $this->response('PARAM_CODE_MISSING');
        if(!$this->get->token) $this->response('PARAM_TOKEN_MISSING');

        $entry = $this->entry->getByCode($this->get->code);
        if(!$entry)                              $this->response('EMPTY_ENTRY');
        if(!$entry->key)                         $this->response('EMPTY_KEY');
        if(!$this->checkIP($entry->ip))          $this->response('IP_DENIED');
        if(!$this->checkEntryToken($entry->key)) $this->response('INVALID_TOKEN');

        $this->session->set('ENTRY_CODE', $this->get->code);
        $this->session->set('VALID_ENTRY', md5(md5($this->get->code) . $this->server->remote_addr));
        $this->loadModel('entry')->saveLog($entry->id, $this->server->request_uri);

        unset($_GET['code']);
        unset($_GET['token']);
    }

    /**
     * Check token of an entry.
     *
     * @param  string $key
     * @access public
     * @return void
     */
    public function checkEntryToken($key)
    {
        parse_str($this->server->query_String, $queryString);
        unset($queryString['token']);
        $queryString = http_build_query($queryString);
        return $this->get->token == md5(md5($queryString) . $key);
    }

    /**
     * Response.
     *
     * @param  string $code
     * @access public
     * @return void
     */
    public function response($code)
    {
        $response = new stdclass();
        $response->errcode = $this->config->entry->errcode[$code];
        $response->errmsg  = $this->lang->entry->errmsg[$code];

        die(helper::jsonEncode($response));
    }

    /**
     * Http.
     *
     * @param  string       $url
     * @param  string|array $data
     * @static
     * @access public
     * @return string
     */
    public static function http($url, $data = null)
    {
        global $lang, $app;
        if(!extension_loaded('curl')) return json_encode(array('result' => 'fail', 'message' => $lang->error->noCurlExt));

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Sae T OAuth2 v0.1');
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);

        $headers[] = "API-RemoteIP: " . $_SERVER['REMOTE_ADDR'];
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        if(!empty($data))
        {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        $response = curl_exec($curl);
        $errors   = curl_error($curl);
        curl_close($curl);

        $logFile = $app->getLogRoot() . 'saas.'. date('Ymd') . '.log.php';
        if(!file_exists($logFile)) file_put_contents($logFile, '<?php die(); ?' . '>');

        $fh = @fopen($logFile, 'a');
        if($fh)
        {
            fwrite($fh, date('Ymd H:i:s') . ": " . $app->getURI() . "\n");
            fwrite($fh, "url:    " . $url . "\n");
            if(!empty($data)) fwrite($fh, "data:   " . print_r($data, true) . "\n");
            fwrite($fh, "results:" . print_r($response, true) . "\n");
            if(!empty($errors)) fwrite($fh, "errors: " . $errors . "\n");
            fclose($fh);
        }

        return $response;
    }
}

class common extends commonModel
{
}
