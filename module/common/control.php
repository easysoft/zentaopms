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
     * @static
     * @access public
     * @return bool
     */
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
     * Save the id of the last browse product to session.
     *
     * @access public
     * @return string 
     */
    public static function saveProductState($productID, $defaultProductID)
    {
        global $app;
        if($productID > 0) $app->session->set('product', (int)$productID);
        if($productID == 0 and $app->cookie->lastProduct)    $app->session->se('product', (int)$app->cookie->lastProduct);
        if($productID == 0 and $app->session->product == '') $app->session->set('product', $defaultProductID);
        return $app->session->product;
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
