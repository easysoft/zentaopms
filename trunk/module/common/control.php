<?php
/**
 * The control file of common module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
class common extends control
{
    /**
     * 构造函数：启动会话，加载公司模块，并设置公司信息。
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        session_start();
        $this->sendHeader();
        $this->loadModel('company');
        $this->setCompany();
        $this->setUser();
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
        echo html::a(helper::createLink('misc', 'about'), $lang->aboutZenTao, '', "class='about'");
    }

    /* 打印主菜单。*/
    public static function printMainmenu($moduleName)
    {
        global $app, $lang;
        $logo = $app->getWebRoot() . 'theme/default/images/main/logo.png';
        echo "<ul>\n";
        echo "<li style='padding:0; height:30px'><a href='http://www.zentaoms.com' target='_blank'><img src='$logo' /></a></li>\n";

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
        echo html::a(helper::createLink($module, $method), $lang->zentaoMS) . $lang->arrow;
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
     * 设置当前访问的公司信息。
     * 
     * 首先尝试按照当前访问的域名查找对应的公司信息，
     * 如果无法查到，再按照默认的域名进行查找。
     * 如果还无法查到，则取第一个公司作为默认的公司。
     * 获取公司信息之后，将其写入到$_SESSION中。
     *
     * @access public
     * @return void
     */
    private function setCompany()
    {
        if(isset($_SESSION['company']) and $_SESSION['company']->pms == $_SERVER['HTTP_HOST'])
        {
            $this->app->company = $_SESSION['company'];
        }
        else
        {
            $company = $this->company->getByDomain();
            if(!$company and isset($this->config->default->domain)) $company = $this->company->getByDomain($this->config->default->domain);
            if(!$company) $company = $this->company->getFirst();
            if(!$company) $this->app->error(sprintf($this->lang->error->companyNotFound, $_SERVER['HTTP_HOST']), __FILE__, __LINE__, $exit = true);
            $_SESSION['company'] = $company;
            $this->app->company  = $company;
        }
    }

    /**
     * 设置当前访问的用户信息。
     * 
     * @access public
     * @return void
     */
    private function setUser()
    {
        if(isset($_SESSION['user']))
        {
            $this->app->user = $_SESSION['user'];
        }
        elseif($this->app->company->guest)
        {
            $user             = new stdClass();
            $user->id         = 0;
            $user->account    = 'guest';
            $user->realname   = 'guest';
            $user->rights     = $this->loadModel('user')->authorize('guest');
            $_SESSION['user'] = $user;
            $this->app->user = $_SESSION['user'];
        }
    }

    /* 保存最后浏览的产品id到session会话中。*/
    public static function saveProductState($productID, $defaultProductID)
    {
        global $app;
        if($productID > 0) $app->session->set('product', (int)$productID);
        if($productID == 0 and $app->session->product == '') $app->session->set('product', $defaultProductID);
        return $app->session->product;
    }

    /* 保存最后浏览的项目id到session会话中。*/
    public static function saveProjectState($projectID, $projects)
    {
        global $app;
        if($projectID > 0) $app->session->set('project', (int)$projectID);
        if($projectID == 0 and $app->session->project == '') $app->session->set('project', $projects[0]);
        if(!in_array($app->session->project, $projects)) $app->session->set('project', $projects[0]);
        return $app->session->project;
    }

    /**
     * 发送header信息到浏览器。
     * 
     * @access public
     * @return void
     */
    public function sendHeader()
    {
        header("Content-Type: text/html; Language={$this->config->encoding}");
        header("Cache-control: private");
    }

    /* 比较两个数组元素的不同，产生修改记录。*/
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

            if($magicQuote) $value = stripslashes($value);
            if($value != $old->$key)
            { 
                $diff = '';
                if(substr_count($value, "\n") > 1 or substr_count($old->$key, "\n") > 1 or strpos('name,title,desc,spec,steps', strtolower($key)) !== false) $diff = self::diff($old->$key, $value);
                $changes[] = array('field' => $key, 'old' => $old->$key, 'new' => $value, 'diff' => $diff);
            }
        }
        return $changes;
    }

    /* 比较两个字符串的不同。摘自PHPQAT自动化测试框架。*/
    public static function diff($text1, $text2)
    {
        $w  = explode("\n", trim($text1));
        $o  = explode("\n", trim($text2));
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

    /* 获得系统URL地址。*/
    public function getSysURL()
    {
        global $config;
        $httpType = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on' ? 'https' : 'http';
        $httpHost = $_SERVER['HTTP_HOST'];
        return "$httpType://$httpHost";
    }

    /* 获得系统默认的样式表。*/
    public function getDefaultCss()
    {
        global $app;
        $pathFix = $app->getPathFix();
        $cssFile = $app->getAppRoot() . "www{$pathFix}theme{$pathFix}default{$pathFix}style.css";
        $cssContent = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!','', file_get_contents($cssFile));
        $cssContent = str_replace(array(" {", "} ", '  ', "\r\n", "\r", "\n", "\t"), array("{", '}', ' ', ''), $cssContent);
        return $cssContent;
    }
}
