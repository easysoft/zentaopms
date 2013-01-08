<?php
/**
 * The model file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     common
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class commonModel extends model
{
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
        header("Content-Type: text/html; Language={$this->config->encoding}");
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
        if(strpos($httpHost, ":"))
        {
            $httpHost = explode(":", $httpHost);
            $httpHost = $httpHost[0];
        }

        if($this->session->company and $this->session->company->pms == $httpHost)
        {
            $this->app->company = $this->session->company;
        }
        else
        {
            $company = $this->loadModel('company')->getByDomain();
            if(!$company and isset($this->config->default->domain)) $company = $this->company->getByDomain($this->config->default->domain);
            if(!$company) $company = $this->company->getFirst();
            if(!$company) $this->app->error(sprintf($this->lang->error->companyNotFound, $httpHost), __FILE__, __LINE__, $exit = true);
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
        elseif($this->app->company->guest)
        {
            $user             = new stdClass();
            $user->id         = 0;
            $user->account    = 'guest';
            $user->realname   = 'guest';
            $user->role       = 'guest';
            $user->rights     = $this->loadModel('user')->authorize('guest');
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
        if(!isset($this->app->user->account)) return;

        $account = $this->app->user->account;
        $config  = $this->loadModel('setting')->getSysAndPersonalConfig($account);

        $this->config->system   = isset($config['system']) ? $config['system'] : array();
        $this->config->personal = isset($config[$account]) ? $config[$account] : array();

        /* Overide the items defined in config/config.php and config/my.php. */
        if(isset($this->config->system->common))
        {
            foreach($this->config->system->common as $record)
            {
                if($record->section)
                {
                    if(!isset($this->config->{$record->section})) $this->config->{$record->section} = new stdclass();
                    $this->config->{$record->section}->{$record->key} = $record->value;
                }
                else
                {
                    if(!$record->section) $this->config->{$record->key} = $record->value;
                }
            }
        }
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
        if($module == 'user' and strpos('login|logout|deny', $method) !== false) return true;
        if($module == 'api'  and $method == 'getsessionid') return true;

        if($this->loadModel('user')->isLogon())
        {
            if(stripos($method, 'ajax') !== false) return true;
            if(stripos($method, 'downnotify') !== false) return true;
        }

        if($module == 'misc' and $method == 'about') return true;
        if($module == 'misc' and $method == 'checkupdate') return true;
        if($module == 'help' and $method == 'field') return true;
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
        $vars = "module=$module&method=$method";
        if(isset($this->server->http_referer))
        {
            $referer = helper::safe64Encode($this->server->http_referer);
            $vars   .= "&referer=$referer";
        }
        $denyLink = helper::createLink('user', 'deny', $vars);

        /* Fix the bug of IE: use js locate, can't get the referer. */
        if(strpos($this->server->http_user_agent, 'MSIE') !== false)
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
     * Get the run info.
     * 
     * @param mixed $startTime  the start time of this execution
     * @access public
     * @return array    the run info array.
     */
    public function getRunInfo($startTime)
    {
        $info['timeUsed'] = round(getTime() - $startTime, 4) * 1000;
        $info['memory']   = round(memory_get_peak_usage() / 1024, 1);
        $info['querys']   = count(dao::$querys);
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

        printf($lang->todayIs, date(DT_DATE4));
        if(isset($app->user)) echo $app->user->realname . ' ';
        if(isset($app->user) and $app->user->account != 'guest')
        {
            echo html::a(helper::createLink('user', 'logout'), $lang->logout);
        }
        else
        {
            echo html::a(helper::createLink('user', 'login'), $lang->login);
        }

        echo '&nbsp;|&nbsp; ';
        echo html::a(helper::createLink('misc', 'about'), $lang->aboutZenTao, '', "class='about'");
        echo $lang->agileTraining;
        echo $lang->donate;

        echo '&nbsp;|&nbsp;';
        echo html::select('', $app->config->langs, $app->cookie->lang,  'onchange="selectLang(this.value)"');
        echo html::select('', $app->lang->themes,  $app->cookie->theme, 'onchange="selectTheme(this.value)"');
    }

    /**
     * Print the main menu.
     * 
     * @param  string $moduleName 
     * @static
     * @access public
     * @return void
     */
    public static function printMainmenu($moduleName)
    {
        global $app, $lang;
        echo "<ul>\n";

        /* Set the main main menu. */
        $mainMenu = $moduleName;
        if(isset($lang->menugroup->$moduleName)) $mainMenu = $lang->menugroup->$moduleName;

        /* Sort menu according to menuOrder. */
        if(isset($lang->menuOrder))
        {
            $menus = $lang->menu;
            $lang->menu = new stdclass();

            ksort($lang->menuOrder, SORT_ASC);
            foreach($lang->menuOrder as $order)  
            {
                $menu = $menus->$order; 
                unset($menus->$order);
                $lang->menu->$order = $menu;
            }
            foreach($menus as $key => $menu)
            {
                $lang->menu->$key = $menu; 
            }
        }

        /* Print all main menus. */
        foreach($lang->menu as $menuKey => $menu)
        {
            $active = $menuKey == $mainMenu ? "class='active'" : '';
            list($menuLabel, $module, $method) = explode('|', $menu);

            if(common::hasPriv($module, $method))
            {
                $link  = helper::createLink($module, $method);
                echo "<li $active><nobr><a href='$link' id='menu$menuKey'>$menuLabel</a></nobr></li>\n";
            }
        }

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

        echo "<li id='searchbox'>"; 
        echo html::select('searchType', $lang->searchObjects, $searchObject);
        echo html::input('searchQuery', $lang->searchTips, "onclick=this.value='' onkeydown='if(event.keyCode==13) shortcut()' class='w-80px'");
		echo "<input type='button' id='objectSwitcher' onclick='shortcut()' />";
        echo "</li>";
        echo "</ul>\n";
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
            $menus = $submenus;
            $submenus = new stdclass();

            ksort($lang->$moduleName->menuOrder, SORT_ASC);
            if(isset($menus->list)) 
            {
                $submenus->list = $menus->list; 
                unset($menus->list);
            }
            foreach($lang->$moduleName->menuOrder as $order)  
            {
                if(($order != 'list') && isset($menus->$order))
                {
                    $subOrder = $menus->$order;
                    unset($menus->$order);
                    $submenus->$order = $subOrder;
                }
            }
            foreach($menus as $key => $menu)
            {
                $submenus->$key = $menu; 
            }
        }

        /* The beginning of the menu. */
        echo "<ul>\n";

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
                if(common::hasPriv($module, $method))
                {
                    /* Is the currentModule active? */
                    if($currentModule == $subModule and $float != 'right') $active = 'active';
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
        echo html::a(helper::createLink('my', 'index'), $lang->ZenTaoPMS) . $lang->arrow;
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

        switch($type)
        {
            case 'story'    : $table = TABLE_STORY; break;
            case 'task'     : $table = TABLE_TASK;  break;
            case 'bug'      : $table = TABLE_BUG;   break;
            case 'testcase' : $table = TABLE_CASE;  break;
            case 'doc'      : $table = TABLE_DOC;   break;
            default         : return $preAndNextObject;
        }

        $typeIDs = $type . 'IDs';
        if($this->session->$typeIDs and strpos($this->session->$typeIDs, ',' . $objectID . ',') !== false)
        {
            $objectIDs = $this->session->$typeIDs;
            $this->session->set($typeIDs, '');
        }
        else
        {
            /* Get objectIDs. */
            $queryCondition    = $type . 'QueryCondition';
            $typeOnlyCondition = $type . 'OnlyCondition';
            $queryCondition = $this->session->$queryCondition;
            $orderBy = $type . 'OrderBy';
            $orderBy = $this->session->$orderBy;
            $orderBy = str_replace('`left`', 'left', $orderBy); // process the `left` to left.

            if(empty($queryCondition) or $this->session->$typeOnlyCondition)
            {
                $objects = $this->dao->select('*')->from($table)
                    ->beginIF($queryCondition != false)->where($queryCondition)->fi()
                    ->beginIF($orderBy != false)->orderBy($orderBy)->fi()
                    ->fetchAll();
            }
            else
            {
                $objects = $this->dbh->query($queryCondition . " ORDER BY $orderBy")->fetchAll();
            }

            $tmpObjectIDs = array();
            foreach($objects as $object) $tmpObjectIDs[$object->id] = (!$this->session->$typeOnlyCondition and $type == 'testcase') ? $object->case : $object->id;
            $objectIDs    = ',' . implode(',', $tmpObjectIDs) . ',';
            $this->session->set($type . 'IDs', $objectIDs);
        }

        /* Current object. */
        $currentStart = strpos($objectIDs, ',' . $objectID . ',') + 1;
        $currentEnd   = $currentStart + strlen($objectID) - 1;

        /* Get the previous object. */
        $tmp      = substr($objectIDs, 0, $currentStart - 1);
        $preStart = strrpos($tmp, ',', 0) +  1;
        $preEnd   = $currentStart - 2;
        if($preEnd - $preStart < 0) 
        {
            $preAndNextObject->pre = '';
        }
        else
        {
            $preID = substr($objectIDs, $preStart, $preEnd - $preStart + 1);
            $preAndNextObject->pre  = $this->dao->select('*')->from($table)->where('id')->eq($preID)->fetch();
        }
        
        /* Get the next object. */
        $nextStart = $currentEnd + 2;            
        $nextEnd   = strlen($objectIDs) > $nextStart ? strpos($objectIDs, ',', $nextStart) - 1 : 0;
        if($nextEnd - $nextStart < 0) 
        {
            $preAndNextObject->next = '';
        }
        else
        {
            $nextID = substr($objectIDs, $nextStart, $nextEnd - $nextStart + 1);
            $preAndNextObject->next = $this->dao->select('*')->from($table)->where('id')->eq($nextID)->fetch();
        }

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
}
