<?php
/**
 * The model file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: model.php 5148 2013-07-16 01:31:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class adminModel extends model
{
    /**
     * Post data form  API.
     *
     * @param  string $url
     * @param  string $formvars
     * @access public
     * @return void
     */
    public function postAPI($url, $formvars = '')
    {
        return common::http($url, $formvars);
    }

    /**
     * Get status of zentaopms.
     *
     * @access public
     * @return void
     */
    public function getStatOfPMS()
    {
        $sql    = "SHOW TABLE STATUS";
        $tables = $this->dbh->query($sql)->fetchALL();
    }

    /**
     * Get state of company.
     *
     * @param  int    $companyID
     * @access public
     * @return void
     */
    public function getStatOfCompany($companyID)
    {
    }

    /**
     * Get system info.
     *
     * @access public
     * @return void
     */
    public function getStatOfSys()
    {
    }

    /**
     * Register zentao by API.
     *
     * @access public
     * @return void
     */
    public function registerByAPI()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiRegister.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
        return $this->postAPI($apiURL, $_POST);
    }

    /**
     * Login zentao by API.
     *
     * @access public
     * @return void
     */
    public function bindByAPI()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-bindChanzhi.json?HTTP_X_REQUESTED_WITH=XMLHttpRequest&{$apiConfig->sessionVar}={$apiConfig->sessionID}";
        return $this->postAPI($apiURL, $_POST);
    }

    /**
     * Get secret key.
     *
     * @access public
     * @return object
     */
    public function getSecretKey()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-secretKey.json";

        $params['u']   = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $result = common::http($apiURL . '?' . http_build_query($params));
        $result = json_decode($result);
        return $result;
    }

    /**
     * Send code by API.
     *
     * @param  string    $type
     * @access public
     * @return string
     */
    public function sendCodeByAPI($type)
    {
        $apiConfig = $this->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiSendCode.json";

        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        if(isset($this->config->global->community) and $this->config->global->community != 'na') $this->post->set('account', $this->config->global->community);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Certify by API.
     *
     * @param  string    $type
     * @access public
     * @return string
     */
    public function certifyByAPI($type)
    {
        $apiConfig = $this->getApiConfig();
        $module    = $type == 'mobile' ? 'sms' : 'mail';
        $apiURL    = $this->config->admin->apiRoot . "/{$module}-apiCertify.json";

        $params['u'] = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Set company by API.
     *
     * @access public
     * @return string
     */
    public function setCompanyByAPI()
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-apiSetCompany.json";

        $params['u'] = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $param = http_build_query($params);
        return $this->postAPI($apiURL . '?' . $param, $_POST);
    }

    /**
     * Get signature.
     *
     * @param  array    $params
     * @access public
     * @return string
     */
    public function getSignature($params)
    {
        unset($params['u']);
        $privateKey = $this->config->global->ztPrivateKey;
        return md5(http_build_query($params) . md5($privateKey));
    }

    /**
     * Get api config.
     *
     * @access public
     * @return object
     */
    public function getApiConfig()
    {
        if(!$this->session->apiConfig or time() - $this->session->apiConfig->serverTime > $this->session->apiConfig->expiredTime)
        {
            $config = file_get_contents($this->config->admin->apiRoot . "?mode=getconfig");
            $config = json_decode($config);
            if(empty($config) or empty($config->sessionID)) return null;
            $this->session->set('apiConfig', $config);
        }
        return $this->session->apiConfig;
    }

    /**
     * Get register information.
     *
     * @access public
     * @return object
     */
    public function getRegisterInfo()
    {
        $register = new stdclass();
        $register->company = $this->app->company->name;
        $register->email   = $this->app->user->email;
        return $register;
    }

    /**
     * Check weak.
     *
     * @param  object    $user
     * @access public
     * @return bool
     */
    public function checkWeak($user)
    {
        $weaks = array();

        foreach(explode(',', $this->config->safe->weak) as $weak)
        {
            $weak = md5(trim($weak));
            $weaks[$weak] = $weak;
        }

        if(isset($weaks[$user->password])) return true;
        if($user->password == md5($user->account)) return true;
        if($user->phone    and $user->password == md5($user->phone))    return true;
        if($user->mobile   and $user->password == md5($user->mobile))   return true;
        if($user->birthday and $user->password == md5($user->birthday)) return true;
        return false;
    }

    /**
     * Set admin menu.
     *
     * @access public
     * @return void
     */
    public function setMenu()
    {
        $this->checkPrivMenu();

        $menuKey = $this->getMenuKey();
        if(empty($menuKey)) return;

        $this->setSwitcher($menuKey);
        if(isset($this->lang->admin->menuList->$menuKey))
        {
            if(isset($this->lang->admin->menuList->{$menuKey}['subMenu']))
            {
                $moduleName = $this->app->rawModule;
                $methodName = $this->app->rawMethod;
                $firstParam = $this->app->rawParams ? reset($this->app->rawParams) : '';

                foreach($this->lang->admin->menuList->{$menuKey}['subMenu'] as $subMenuKey => $subMenu)
                {
                    $subModule = '';
                    if($moduleName == 'custom' and strpos(',required,set,', $methodName) !== false)
                    {
                        if(isset($this->config->admin->navsGroup[$menuKey][$subMenuKey]) and strpos($this->config->admin->navsGroup[$menuKey][$subMenuKey], ",$firstParam,") !== false) $subModule = 'custom';
                        if($firstParam == $subMenuKey) $subModule = 'custom';
                    }

                    if(!empty($subModule)) $subMenu['subModule'] = $subModule;
                    if(isset($this->lang->admin->menuList->{$menuKey}['tabMenu'][$subMenuKey]))
                    {
                        if(!empty($subModule))
                        {
                            $this->lang->admin->menuList->{$menuKey}['tabMenu'][$subMenuKey][$firstParam]['subModule'] = $subModule;
                            unset($this->lang->admin->menuList->{$menuKey}['tabMenu'][$subMenuKey][$firstParam]['exclude']);
                        }
                        $subMenu['subMenu'] = $this->lang->admin->menuList->{$menuKey}['tabMenu'][$subMenuKey];
                    }
                    if(isset($this->lang->admin->menuList->{$menuKey}['tabMenu']['menuOrder'][$subMenuKey]))   $subMenu['menuOrder']   = $this->lang->admin->menuList->{$menuKey}['tabMenu']['menuOrder'][$subMenuKey];
                    if(isset($this->lang->admin->menuList->{$menuKey}['tabMenu']['dividerMenu'][$subMenuKey])) $subMenu['dividerMenu'] = $this->lang->admin->menuList->{$menuKey}['tabMenu']['dividerMenu'][$subMenuKey];

                    $this->lang->admin->menu->$subMenuKey = $subMenu;
                }
            }

            if(isset($this->lang->admin->menuList->{$menuKey}['menuOrder']))   $this->lang->admin->menuOrder   = $this->lang->admin->menuList->{$menuKey}['menuOrder'];
            if(isset($this->lang->admin->menuList->{$menuKey}['dividerMenu'])) $this->lang->admin->dividerMenu = $this->lang->admin->menuList->{$menuKey}['dividerMenu'];
            if(isset($this->lang->admin->menuList->{$menuKey}['tabMenu']))     $this->lang->admin->tabMenu     = $this->lang->admin->menuList->{$menuKey}['tabMenu'];
        }
    }

    /**
     * Check priv menu.
     *
     * @access public
     * @return void
     */
    public function checkPrivMenu()
    {
        $orders = array();
        foreach($this->lang->admin->menuList as $menuKey => $menu)
        {
            $menu['disabled'] = true;
            if(!isset($menu['link'])) $menu['link'] = '';
            if($menuKey == 'company')
            {
                $dept = $this->dao->select('id')->from(TABLE_DEPT)->fetch();
                if($dept and common::hasPriv('company', 'browse')) $menu['link'] = helper::createLink('company', 'browse');
            }

            /* Set links to authorized navigation. */
            if(isset($menu['subMenu']))
            {
                /* Reorder secondary navigation. */
                $subMenuList   = array();
                $subMenuOrders = $menu['menuOrder'];
                ksort($subMenuOrders);
                foreach($subMenuOrders as $value)
                {
                    if(!isset($menu['subMenu'][$value])) continue;
                    $subMenuList[$value] = $menu['subMenu'][$value];
                }

                /* Check sub menu priv. */
                foreach($subMenuList as $subMenuKey => $subMenu)
                {
                    if($menuKey == 'message' and $subMenuKey == 'mail')
                    {
                        $this->loadModel('mail');
                        if(!$this->config->mail->turnon and !$this->session->mailConfig) $subMenu['link'] = $this->lang->mail->common . '|mail|detect|';
                    }
                    if($menuKey == 'dev' and $subMenuKey == 'editor')
                    {
                        if(!empty($this->config->global->editor)) $subMenu['link'] = $this->lang->editor->common . '|editor|index|';
                        if(empty($this->config->global->editor) and !$this->app->user->admin)
                        {
                            unset($menu['subMenu']['editor']);
                            continue;
                        }
                    }

                    $link = array();
                    if(isset($menu['tabMenu'][$subMenuKey]))
                    {
                        /* Reorder tertiary navigation. */
                        $tabMenuList   = $menu['tabMenu'][$subMenuKey];
                        if(isset($menu['tabMenu']['menuOrder'][$subMenuKey]))
                        {
                            $tabMenuOrders = $menu['tabMenu']['menuOrder'][$subMenuKey];
                            ksort($tabMenuOrders);
                            foreach($tabMenuOrders as $value) $tabMenuList[$value] = $menu['tabMenu'][$subMenuKey][$value];
                        }

                        /* Check tab menu priv. */
                        foreach($tabMenuList as $tabMenuKey => $tabMenu)
                        {
                            $tabMenuLink = $this->getHasPrivLink($tabMenu);
                            if(!empty($tabMenuLink))
                            {
                                /* Updated tertiary navigation links. */
                                list($module, $method, $params) = $tabMenuLink;
                                $tabMenuLabel = $tabMenu['link'];
                                $menu['tabMenu'][$subMenuKey][$tabMenuKey]['link'] = substr($tabMenuLabel, 0, strpos($tabMenuLabel, '|') + 1) . $module . '|' . $method . '|' . $params;

                                if(empty($link)) $link = $tabMenuLink;
                            }
                        }
                    }
                    else
                    {
                        $link = $this->getHasPrivLink($subMenu);
                    }

                    if(!empty($link))
                    {
                        /* Updated secondary navigation link. */
                        list($module, $method, $params) = $link;
                        $subMenuLabel = $subMenu['link'];
                        $menu['subMenu'][$subMenuKey]['link'] = substr($subMenuLabel, 0, strpos($subMenuLabel, '|') + 1) . $module . '|' . $method . '|' . $params;

                        /* Update the level 1 navigation link. */
                        if(empty($menu['link'])) $menu['link'] = helper::createLink($module, $method, $params);
                        $menu['disabled'] = false;
                    }
                }
            }
            elseif(!empty($menu['link']) and strpos($menu['link'], '|') !== false)
            {
                list($module, $method) = explode('|', $menu['link']);
                $menu['link'] = helper::createLink($module, $method);
                if(common::hasPriv($module, $method)) $menu['disabled'] = false;
            }

            $order = $menu['order'];
            $orders[$order] = $menuKey;

            $this->lang->admin->menuList->$menuKey = $menu;
        }

        ksort($orders);
        $menuList = new stdclass();
        foreach($orders as $index => $menuKey)
        {
            $menuList->$menuKey = $this->lang->admin->menuList->$menuKey;
            $menuList->{$menuKey}['order'] = $index;
        }

        $this->lang->admin->menuList = $menuList;
    }

    /**
     * Get the authorized link.
     *
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function getHasPrivLink($menu)
    {
        $link = array();
        if(!empty($menu['link']))
        {
            list($label, $module, $method, $params) = explode('|', $menu['link']);
            if(common::hasPriv($module, $method))
            {
                $link = array($module, $method, $params);
            }
            elseif(!empty($menu['links']))
            {
                foreach($menu['links'] as $menuLink)
                {
                    list($module, $method, $params) = explode('|', $menuLink);
                    if(common::hasPriv($module, $method))
                    {
                        $link = array($module, $method, $params);
                        break;
                    }
                }
            }
        }

        return $link;
    }

    /**
     * Get menu key
     *
     * @access public
     * @return string
     */
    public function getMenuKey()
    {
        $moduleName  = $this->app->rawModule;
        $methodName  = $this->app->rawMethod;
        $firstParam  = $this->app->rawParams ? reset($this->app->rawParams) : '';
        $secondParam = $this->app->rawParams ? next($this->app->rawParams)  : '';

        foreach($this->config->admin->menuGroup as $menuKey => $menuGroup)
        {
            if(in_array($moduleName, $menuGroup))
            {
                return $menuKey;
            }
            elseif(in_array("$moduleName|$methodName", $menuGroup))
            {
                if($moduleName == 'custom' and ($methodName == 'required' or $methodName == 'set'))
                {
                    if(in_array($firstParam, $this->config->admin->menuModuleGroup[$menuKey]["custom|$methodName"])) return $menuKey;
                }
                else
                {
                    return $menuKey;
                }
            }
        }
        return null;
    }

    /**
     * Set switcher.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return string
     */
    public function setSwitcher($currentMenuKey = 'system')
    {
        if(empty($currentMenuKey)) return null;

        $currentMenu = $this->lang->admin->menuList->$currentMenuKey;
        $output      = "<div class='btn-group header-btn'>";
        $output     .= "<button class='btn pull-right btn-link' data-toggle='dropdown'>";
        $output     .= "<span class='text'>{$currentMenu['name']}</span> ";
        $output     .= "<span class='caret'></span></button>";
        $output     .= "<ul class='dropdown-menu menu-hover-primary menu-active-primary' id='adminMenu'>";
        foreach($this->lang->admin->menuList as $menuKey => $menuGroup)
        {
            if($this->config->vision == 'lite' and !in_array($menuKey, $this->config->admin->liteMenuList)) continue;
            $class = $menuKey == $currentMenuKey ? "active" : '';
            if($menuGroup['disabled']) $class .= ' disabled not-clear-menu';
            $output .= "<li class='$class'>" . html::a($menuGroup['disabled'] ? '###' : $menuGroup['link'], "<img src='{$this->config->webRoot}static/svg/admin-{$menuKey}.svg'/>" . $menuGroup['name']) . "</li>";
        }
        $output .= "</ul></div>";

        $this->lang->switcherMenu = $output;
    }

    /**
     * Check internet.
     *
     * @access public
     * @return bool
     */
    public function checkInternet()
    {
        $timeout = 1;
        $curl    = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->config->admin->apiSite);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $connected = curl_exec($curl);
        curl_close($curl);

        return (bool)$connected;
    }

    /**
     * Get date used object.
     *
     * @access public
     * @return object
     */
    public function genDateUsed()
    {
        $firstUseDate = $this->dao->select('date')->from(TABLE_ACTION)
            ->where('date')->gt('1970-01-01')
            ->andWhere('actor')->eq($this->app->user->account)
            ->orderBy('date_asc')
            ->limit('1')
            ->fetch('date');

        if($firstUseDate) $firstUseDate = substr($firstUseDate, 0, 10);
        return helper::getDateInterval($firstUseDate);
    }

    /**
     * Get zentao.net data.
     *
     * @access public
     * @return object
     */
    public function getZentaoData()
    {
        $data = new stdclass();
        $data->hasData  = true;
        $data->dynamics = array();
        $data->classes  = array();
        $data->plugins  = array();
        $data->patches  = array();

        $zentaoData = !empty($this->config->zentaoWebsite) ? $this->config->zentaoWebsite : null;
        if(empty($zentaoData))
        {
            $data->hasData = false;
            if($this->config->edition == 'open')
            {
                $data->plugins = array(
                    $this->config->admin->plugins[27],
                    $this->config->admin->plugins[26],
                    $this->config->admin->plugins[30]
                );
            }
            else
            {
                $data->plugins = array(
                    $this->config->admin->plugins[198],
                    $this->config->admin->plugins[194],
                    $this->config->admin->plugins[203]
                );
            }
        }
        else
        {
            $data->dynamics = json_decode($zentaoData->dynamic);
            $data->classes  = json_decode($zentaoData->publicClass);
            $data->plugins  = json_decode($zentaoData->plugin);
            $data->patches  = json_decode($zentaoData->patch);
            if(common::checkNotCN()) array_pop($data->plugins);
        }

        return $data;
    }
}
