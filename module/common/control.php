<?php
/**
 * The control file of common module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
        $this->common->loadConfigFromDB();
        $this->app->loadLang('company');
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
        if($this->common->isOpenMethod($module, $method)) return true;
        if(!$this->loadModel('user')->isLogon() and $this->server->php_auth_user) $this->user->identifyByPhpAuth();
        if(!$this->loadModel('user')->isLogon() and $this->cookie->za) $this->user->identifyByCookie();

        if(isset($this->app->user))
        {
            if(!common::hasPriv($module, $method)) $this->common->deny($module, $method);
        }
        else
        {
            $referer  = helper::safe64Encode($this->app->getURI(true));
            $this->locate($this->createLink('user', 'login', "referer=$referer"));
        }
    }

    /**
     * Check upgrade's status file is ok or not.
     * 
     * @access public
     * @return void
     */
    public function checkUpgradeStatus()
    {
        $statusFile = $this->app->getAppRoot() . 'www' . $this->pathFix . 'ok';
        if(!file_exists($statusFile) or time() - filemtime($statusFile) > 3600)
        {
            $this->app->loadLang('upgrade');
            echo "<html><head><meta http-equiv='Content-Type' content='text/html; charset=utf-8' /></head><body>";
            echo "<table align='center' style='margin-top:100px; border:1px solid gray; font-size:14px;'><tr><td>";
            printf($this->lang->upgrade->setStatusFile, $statusFile, $statusFile, $statusFile);
            die('</td></tr></table></body></html>');
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
        global $app;

        /* Check is the super admin or not. */
        $account = ',' . $app->user->account . ',';
        if(strpos($app->company->admins, $account) !== false) return true; 

        /* If not super admin, check the rights. */
        $rights  = $app->user->rights;
        if(isset($rights[strtolower($module)][strtolower($method)])) return true;
        return false;
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
        if(empty($module)) $module= $app->getModuleName();
        if(empty($method)) $method= $app->getMethodName();
        $className = 'header';

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
    public static function printLink($module, $method, $vars = '', $label, $target = '', $misc = '', $newline = true)
    {
        if(!common::hasPriv($module, $method)) return false;
        echo html::a(helper::createLink($module, $method, $vars), $label, $target, $misc, $newline);
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
        global $lang;

        if(!common::hasPriv($module, 'edit')) return false;
        echo "<span class='link-button'>";
        echo html::a('#comment', '&nbsp;', '', "class='icon-black-common-comment' title='$lang->comment' onclick='setComment()'");
        echo "</span>";
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
     * @static
     * @access public
     * @return void
     */
    public static function printIcon($module, $method, $vars = '', $object = '', $type = 'button', $icon = '', $target = '')
    {
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
        if(strtolower($module) == 'testcase' and strtolower($method) == 'createbug')  ($module = 'bug') and ($method = 'create');
        if(strtolower($module) == 'story'    and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'tostory')    ($module = 'story') and ($method = 'create');
        if(strtolower($module) == 'bug'      and strtolower($method) == 'createcase') ($module = 'testcase') and ($method = 'create');
        if(!common::hasPriv($module, $method)) return false;
        $link = helper::createLink($module, $method, $vars);

        /* Set the icon title, try search the $method defination in $module's lang or $common's lang. */
        $title = $method;
        if($method == 'create' and $icon == 'copy') $method = 'copy';
        if(isset($lang->$method) and is_string($lang->$method)) $title = $lang->$method;
        if((isset($lang->$module->$method) or $app->loadLang($module)) and isset($lang->$module->$method)) 
        {
            $title = $method == 'report' ? $lang->$module->$method->common : $lang->$module->$method;
        }
        if($icon == 'toStory')   $title = $lang->bug->toStory;
        if($icon == 'createBug') $title = $lang->testtask->createBug;

        /* set the class. */
        if(!$icon) $icon = $method;
        if(strpos(',edit,copy,report,export,delete,', ",$icon,") !== false) $module = 'common';
        $color      = $type == 'button' ? 'black' : ($clickable ? 'green' : 'gray');
        $extraClass = strpos(',export,customFields,runCase,results,', ",$method,") !== false ? $method : '';
        $class      = $extraClass ? "icon-$color-$module-$icon $extraClass" : "icon-$color-$module-$icon";
 
        /* Create the icon link. */
        if($clickable)
        {
            if($type == 'button')
            {
                echo "<span class='link-button'>";
                echo html::a($link, '&nbsp;', $target, "class='$class' title='$title'", true);
                if($method != 'edit' and $method != 'delete' and $method != 'copy')
                {
                    echo html::a($link, $title, $target, "class='$extraClass'", true);
                }
                echo "</span>";
            }
            else
            {
                echo html::a($link, '&nbsp;', $target, "class='$class' title='$title'", false);
            }
        }
        else
        {
            if($type == 'list')
            {
                echo "<span class='$class' title='$title'>&nbsp;</span>";
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
    public function printRPN($backLink, $preAndNext = '')
    {
        global $lang;

        echo "<span class='link-button'>";
        echo html::a($backLink, '&nbsp;', '', "class='icon-goback' title={$lang->goback}");
        echo "</span>";

        if(isset($preAndNext->pre) and $preAndNext->pre) 
        {
            $title = isset($preAndNext->pre->title) ? $preAndNext->pre->title : $preAndNext->pre->name;
            $title = '#' . $preAndNext->pre->id . ' ' . $title;
            echo "<span class='link-button'>";
            echo html::a($this->inLink('view', "ID={$preAndNext->pre->id}"), '&nbsp', '', "id='pre' class='icon-pre' title='{$title}'");
            echo "</span>";
        }
        if(isset($preAndNext->next) and $preAndNext->next) 
        {
            $title = isset($preAndNext->next->title) ? $preAndNext->next->title : $preAndNext->next->name;
            $title = '#' . $preAndNext->next->id . ' ' . $title;
            echo "<span class='link-button'>";
            echo html::a($this->inLink('view', "ID={$preAndNext->next->id}"), '&nbsp;', '', "id='next' class='icon-next' title='$title'");
            echo "</span>";
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
            if($value != $old->$key)
            { 
                $diff = '';
                if(substr_count($value, "\n") > 1 or substr_count($old->$key, "\n") > 1 or strpos('name,title,desc,spec,steps,content,digest,verify', strtolower($key)) !== false) $diff = commonModel::diff($old->$key, $value);
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
