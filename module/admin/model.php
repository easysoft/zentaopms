<?php
/**
 * The model file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
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
     * The extension manager version. Don't change it.
     */
    const EXT_MANAGER_VERSION = '1.3';

    /**
     * The api root.
     *
     * @var string
     * @access public
     */
    public $apiRoot;

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
            if(isset($this->lang->admin->menuList->$menuKey['subMenu']))     $this->lang->admin->menu        = $this->lang->admin->menuList->$menuKey['subMenu'];
            if(isset($this->lang->admin->menuList->$menuKey['menuOrder']))   $this->lang->admin->menuOrder   = $this->lang->admin->menuList->$menuKey['menuOrder'];
            if(isset($this->lang->admin->menuList->$menuKey['dividerMenu'])) $this->lang->admin->dividerMenu = $this->lang->admin->menuList->$menuKey['dividerMenu'];
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

            /* Check sub menu priv. */
            if(isset($menu['subMenu']))
            {
                foreach($menu['subMenu'] as $subMenuKey => $subMenu)
                {
                    /* Check tab menu priv. */
                    $link      = '';
                    $linkLabel = '';
                    if(isset($menu['tabMenu'][$subMenuKey]))
                    {
                        foreach($menu['tabMenu'][$subMenuKey] as $tabMenuKey => $tabMenu)
                        {
                            list($linkLabel, $link) = $this->getMenuLink($tabMenu);
                            if(!empty($link)) break;
                        }
                    }
                    else
                    {
                        list($linkLabel, $link) = $this->getMenuLink($subMenu);
                    }

                    if(!empty($link))
                    {
                        $menu['subMenu'][$subMenuKey]['link'] = $linkLabel;
                        if(empty($menu['link']))
                        {
                            $menu['link']     = $link;
                            $menu['disabled'] = false;
                        }
                    }
                    else
                    {
                        unset($this->lang->admin->menuList->feature['subMenu'][$subMenuKey]);
                    }
                }
            }
            else
            {
                if(!empty($menu['link']) and strpos($menu['link'], '|') !== false)
                {
                    list($module, $method) = explode('|', $menu['link']);
                    $menu['link'] = helper::createLink($module, $method);
                    if(common::hasPriv($module, $method)) $menu['disabled'] = false;
                }
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
            $menuList->$menuKey['order'] = $index;
        }

        $this->lang->admin->menuList = $menuList;
    }

    /**
     * Get menu link.
     *
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function getMenuLink($menu)
    {
        $link      = '';
        $linkLabel = '';
        if(!empty($menu['link']))
        {
            list($label, $module, $method, $params) = explode('|', $menu['link']);
            if(common::hasPriv($module, $method))
            {
                $linkLabel = $menu['link'];
                $link      = helper::createLink($module, $method, $params);
            }
            elseif(!empty($menu['links']))
            {
                foreach($menu['links'] as $menuLink)
                {
                    list($module, $method, $params) = explode('|', $menuLink);
                    if(common::hasPriv($module, $method))
                    {
                        $linkLabel = $label . '|' . $menuLink;
                        $link      = helper::createLink($module, $method);
                        break;
                    }
                }
            }
        }

        return array($linkLabel, $link);
    }

    /**
     * Get menu key
     *
     * @access public
     * @return string
     */
    public function getMenuKey()
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $paramName  = $this->app->rawParams ? reset($this->app->rawParams) : '';

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
                    if(in_array($paramName, $this->config->admin->menuModuleGroup[$menuKey]["custom|$methodName"])) return $menuKey;
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
     * Get extensions from zentao.net.
     *
     * @param  string $type plugin|patch
     * @param  int    $limit
     * @param  bool   $hasInternet
     * @access public
     * @return array
     */
    public function getExtensionsByAPI($type = 'plugin', $limit = 6, $hasInternet = true)
    {
        if($hasInternet)
        {
            $searchType = $type == 'plugin' ? 'byUpdatedTime' : 'byModule';
            $param      = $type == 'plugin' ? '' : 'MTIxOA==';
            $extensions = $this->loadModel('extension')->getExtensionsByAPI($searchType, $param, 0, $limit);
            $plugins    = isset($extensions->extensions) ? (array)$extensions->extensions : array();
            foreach($plugins as $plugin) $plugin->viewLink = str_replace(array('info', 'client'), '', $plugin->viewLink);
        }
        else
        {
            if($this->config->edition == 'open')
            {
                $plugins = array(
                    $this->config->admin->plugins[27],
                    $this->config->admin->plugins[26],
                    $this->config->admin->plugins[30]
                );
            }
            else
            {
                $plugins = array(
                    $this->config->admin->plugins[198],
                    $this->config->admin->plugins[194],
                    $this->config->admin->plugins[203]
                );
            }
        }

        return $plugins;
    }

    /**
     * Fetch data from an api.
     *
     * @param  string    $url
     * @access public
     * @return mixed
     */
    public function fetchAPI($url)
    {
        $version = $this->loadModel('upgrade')->getOpenVersion(str_replace('.', '_', $this->config->version));
        $version = str_replace('_', '.', $version);

        $url   .= (strpos($url, '?') === false ? '?' : '&') . 'lang=' . str_replace('-', '_', $this->app->getClientLang()) . '&managerVersion=' . self::EXT_MANAGER_VERSION . '&zentaoVersion=' . $version;
        $result = json_decode(preg_replace('/[[:cntrl:]]/mu', '', common::http($url)));

        if(!isset($result->status)) return false;
        if($result->status != 'success') return false;
        if(isset($result->data)) return json_decode($result->data);
    }

    /**
     * Get public class from zentao.net.
     *
     * @param  int    $limit
     * @access public
     * @return array
     */
    public function getPublicClassByAPI($limit = 2)
    {
        $apiURL  = $this->config->admin->apiRoot . "publicclass.json";
        $data    = $this->fetchAPI($apiURL);
        $courses = $data->videos;

        $index       = 1;
        $publicClass = array();
        foreach($courses as $course)
        {
            if($index > $limit) return $publicClass;

            $publicClass[$index] = new stdClass();
            $publicClass[$index]->name     = $course->title;
            $publicClass[$index]->image    = $this->config->admin->apiRoot . $course->image->list[0]->largeURL;
            $publicClass[$index]->viewLink = $this->config->admin->apiRoot . 'publicclass/' . ($course->alias ? "{$course->alias}-" : '') . "{$course->id}.html";
            $index ++;
        }
        return $publicClass;
    }

    /**
     * Check internet.
     *
     * @access public
     * @return bool
     */
    public function checkInternet()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.zentao.net/extension-apiGetExtensions.json');
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 1000);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 1000);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $connected = curl_exec($curl);
        curl_close($curl);

        return (bool)$connected;
    }
}
