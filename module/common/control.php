<?php
/**
 * The control file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class common extends control
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
        $this->common->startSession();
        $this->common->sendHeader();
        $this->common->setCompany();
        $this->common->setUser();
    }

    /**
     * 检查用户对当前的请求有没有权限。如果没有权限，则跳转到登陆界面。
     * 
     * @access public
     * @return void
     */
    public function checkPriv()
    {
        $module = $this->app->getModuleName();
        $method = $this->app->getMethodName();
        if($module == 'user')
        {
            if($method == 'login' or $method == 'logout' or $method == 'deny') return true;
        }
        elseif($module == 'api' and $method == 'getsessionid') 
        {
            return true;
        }
        elseif($module == 'misc' and $method == 'about') 
        {
            return true;
        }

        if(isset($this->app->user))
        {
            if(!common::hasPriv($module, $method))
            {
                $vars = "module=$module&method=$method";
                if(isset($_SERVER['HTTP_REFERER']))
                {
                    $referer  = helper::safe64Encode($_SERVER['HTTP_REFERER']);
                    $vars .= "&referer=$referer";
                }
                $denyLink = $this->createLink('user', 'deny', $vars);

                /* Fix the bug of IE: use js locate, can't get the referer. */
                if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
                {
                echo <<<EOT
<a href='$denyLink' id='denylink' style='display:none'>deny</a>
<script language='javascript'>document.getElementById('denylink').click();</script>
EOT;
                }
                else
                {
                    echo js::locate($denyLink);
                }
                exit;
            }
        }
        elseif(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))
        {
            $account  = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $this->loadModel('user');
            $user = $this->user->identify($account, $password);
            if($user)
            {
                /* 对用户进行授权，并登记session。*/
                $user->rights = $this->user->authorize($account);
                $_SESSION['user'] = $user;
                $this->app->user = $_SESSION['user'];

                /* 记录登录记录。*/
                $this->loadModel('action')->create('user', $user->id, 'login');
            }
            else
            {
                die(js::error($this->lang->user->loginFailed));
            }
        }
        else
        {
            $referer  = helper::safe64Encode($this->app->getURI(true));
            $this->locate($this->createLink('user', 'login', "referer=$referer&from=zentao"));
        }
    }

    /* 检查当前用户对某一个模块的某一个访问是否有权限访问。*/
    public static function hasPriv($module, $method)
    {
        global $app;

        /* 检查是否是管理员。*/
        $account = ',' . $app->user->account . ',';
        if(strpos($app->company->admins, $account) !== false) return true; 

        /* 非管理员，则检查权限列表中是否存在。*/
        $rights  = $app->user->rights;
        if(isset($rights[strtolower($module)][strtolower($method)])) return true;
        return false;
    }

    /* 打印顶部的条形区域。*/
    public static function printTopBar()
    {
        global $lang, $app;

        printf($lang->todayIs, date(DT_DATE3));
        if(isset($app->user)) echo $app->user->realname . ' ';
        if(isset($app->user) and $app->user->account != 'guest')
        {
            echo html::a(helper::createLink('my', 'index'), $lang->myControl);
            echo html::a(helper::createLink('user', 'logout'), $lang->logout);
        }
        else
        {
            echo html::a(helper::createLink('user', 'login'), $lang->login);
        }
        echo html::a('#', $lang->switchHelp, '', "onclick='toggleHelpLink();'");
        echo html::a(helper::createLink('misc', 'about'), $lang->aboutZenTao, '', "class='about'");
        echo html::select('', $app->config->langs, $app->getClientLang(), 'class=switcher onchange="selectLang(this.value)"');
    }

    /* 打印主菜单。*/
    public static function printMainmenu($moduleName)
    {
        global $app, $lang;
        $logo = $app->getWebRoot() . 'theme/default/images/main/logo.png';
        echo "<ul>\n";
        echo "<li style='padding:0; height:30px'><a href='http://www.zentao.net' target='_blank'><img src='$logo' /></a></li>\n";

        /* 设定当前的主菜单项。默认先取当前的模块名，如果有该模块所对应的菜单分组，则取分组名作为主菜单项。*/
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;

        /* 循环打印主菜单。*/
        foreach($lang->menu as $menuKey => $menu)
        {
            $active = $menuKey == $mainMenu ? 'class=active' : '';
            list($menuLabel, $module, $method) = explode('|', $menu);

            if(common::hasPriv($module, $method))
            {
                $link  = helper::createLink($module, $method);
                echo "<li $active><nobr><a href='$link'>$menuLabel</a></nobr></li>\n";
            }
        }

        /* 打印搜索框。*/
        $moduleName = $app->getModuleName();
        $methodName = $app->getMethodName();
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

        echo "<li id='searchbox'>"; 
        echo html::select('searchType', $lang->searchObjects, $searchObject);
        echo html::input('searchQuery', $lang->searchTips, "onclick=this.value='' onkeydown='if(event.keyCode==13) shortcut()' class='w-60px'");
        echo html::submitButton($lang->go, 'onclick="shortcut()"');
        echo "</li>";
        echo "</ul>\n";
    }

    /* 打印模块的菜单。*/
    public static function printModuleMenu($moduleName)
    {
        global $lang, $app;

        /* 没有设置菜单，直接退出。*/
        if(!isset($lang->$moduleName->menu)) {echo "<ul></ul>"; return;}

        /* 获得菜单设置，并记录当前的模块名和方法名。*/
        $submenus      = $lang->$moduleName->menu;  
        $currentModule = $app->getModuleName();
        $currentMethod = $app->getMethodName();

        /* 菜单开始。*/
        echo "<ul>\n";

        /* 循环处理每一个菜单项。*/
        foreach($submenus as $submenu)
        {
            /* 初始化设置。*/
            $link      = $submenu;
            $subModule = '';
            $alias     = '';
            $float     = '';
            $active    = '';
            $target    = '';

            /* 如果该菜单是以数组的形式配置的，则覆盖上面的默认设置。*/
            if(is_array($submenu)) extract($submenu);

            /* 打印菜单。*/
            if(strpos($link, '|') === false)
            {
                echo "<li>$link</li>\n";
            }
            else
            {
                $link = explode('|', $link);
                list($label, $module, $method) = $link;
                $vars = isset($link[3]) ? $link[3] : '';
                if(common::hasPriv($module, $method))
                {
                    global $app;

                    /* 判断是否应该设置激活。*/
                    if($currentModule == $subModule) $active = 'active';
                    if($module == $currentModule and ($method == $currentMethod or strpos($alias, $currentMethod) !== false)) $active = 'active';

                    echo "<li class='$float $active'>" . html::a(helper::createLink($module, $method, $vars), $label, $target) . "</li>\n";
                }
            }
        }
        echo "</ul>\n";
    }

    /* 打印面包屑导航。*/
    public static function printBreadMenu($moduleName, $position)
    {
        global $lang;
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;
        list($menuLabel, $module, $method) = explode('|', $lang->menu->index);
        echo html::a(helper::createLink($module, $method), $lang->ZenTaoPMS) . $lang->arrow;
        if($moduleName != 'index')
        {
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

    /* 设置菜单的参数。*/
    public function setMenuVars($menu, $key, $params)
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

    /* 打印带有orderby的链接。 */
    public static function printOrderLink($fieldName, $orderBy, $vars, $label, $module = '', $method = '')
    {
        global $lang, $app;
        if(empty($module)) $module= $app->getModuleName();
        if(empty($method)) $method= $app->getMethodName();
        if(strpos($orderBy, $fieldName) !== false)
        {
            if(stripos($orderBy, 'desc') !== false)
            {
                $orderBy   = str_ireplace('desc', 'asc', $orderBy);
                $className = 'headerSortUp';
            }
            elseif(stripos($orderBy, 'asc')  !== false)
            {
                $orderBy = str_ireplace('asc', 'desc', $orderBy);
                $className = 'headerSortDown';
            }
        }
        else
        {
            $orderBy   = $fieldName . '_' . 'asc';
            $className = 'header';
        }
        $link = helper::createLink($module, $method, sprintf($vars, $orderBy));
        echo "<div class='$className'>" . html::a($link, $label) . '</div>';
    }

    /* 打印链接，会检查权限*/
    public static function printLink($module, $method, $vars = '', $label, $target = '', $misc = '')
    {
        if(!common::hasPriv($module, $method)) return false;
        echo html::a(helper::createLink($module, $method, $vars), $label, $target, $misc);
        return true;
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
            if($value != $old->$key)
            { 
                $diff = '';
                if(substr_count($value, "\n") > 1 or substr_count($old->$key, "\n") > 1 or strpos('name,title,desc,spec,steps,content,digest', strtolower($key)) !== false) $diff = commonModel::diff($old->$key, $value);
                $changes[] = array('field' => $key, 'old' => $old->$key, 'new' => $value, 'diff' => $diff);
            }
        }
        return $changes;
    }

    /**
     * Get the full url of the system.
     * 
     * @access public
     * @return string
     */
    public function getSysURL()
    {
        global $config;
        $httpType = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ? 'https' : 'http';
        $httpHost = $_SERVER['HTTP_HOST'];
        return "$httpType://$httpHost";
    }

    /**
     * Print the run info.
     * 
     * @param mixed $startTime  the start time.
     * @access public
     * @return void
     */
    public function printRunInfo($startTime)
    {
        vprintf($this->lang->runInfo, $this->common->getRunInfo($startTime));
    }
}
