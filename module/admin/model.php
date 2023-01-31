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
        $this->sortMenu();

        $menuKey = $this->getMenuKey();
        if(empty($menuKey)) return;

        $this->lang->switcherMenu = $this->getSwitcher($menuKey);
        if(isset($this->lang->admin->menuList->$menuKey))
        {
            if(isset($this->lang->admin->menuList->$menuKey['subMenu']))     $this->lang->admin->menu        = $this->lang->admin->menuList->$menuKey['subMenu'];
            if(isset($this->lang->admin->menuList->$menuKey['menuOrder']))   $this->lang->admin->menuOrder   = $this->lang->admin->menuList->$menuKey['menuOrder'];
            if(isset($this->lang->admin->menuList->$menuKey['dividerMenu'])) $this->lang->admin->dividerMenu = $this->lang->admin->menuList->$menuKey['dividerMenu'];
        }
    }

    /**
     * sort menu.
     *
     * @access public
     * @return void
     */
    public function sortMenu()
    {
        $orders   = array();
        $disabled = array();
        foreach($this->lang->admin->menuList as $menuKey => $menu)
        {
            $menu['disabled'] = false;
            if(isset($menu['link']))
            {
                list($module, $method) = explode('|', $menu['link']);
                $menu['link'] = helper::createLink($module, $method);
                if(!common::hasPriv($module, $method)) $menu['disabled'] = true;
            }

            if(isset($menu['menuOrder']))
            {
                $menuOrder = $menu['menuOrder'];
                ksort($menuOrder);
                /* Check sub menu priv. */
                foreach($menuOrder as $subOrder => $subMenuKey)
                {
                    list($label, $module, $method, $params) = explode('|', $menu['subMenu'][$subMenuKey]['link']);
                    if(!common::hasPriv($module, $method)) unset($menuOrder[$subOrder]);
                }
                /* Set link. */
                if(!empty($menuOrder))
                {
                    $subMenuKey = reset($menuOrder);
                    list($label, $module, $method, $params) = explode('|', $menu['subMenu'][$subMenuKey]['link']);
                    $menu['link'] = helper::createLink($module, $method, $params);
                }
                if(empty($menuOrder)) $menu['disabled'] = true;
            }

            $order = $menu['order'];
            $orders[$order] = $menuKey;
            if($menu['disabled'])
            {
                unset($orders[$order]);
                $disabled[$menuKey] = $menu;
            }

            $this->lang->admin->menuList->$menuKey = $menu;
        }

        ksort($orders);
        $menuList = new stdclass();
        $index    = 1;
        foreach($orders as $menuKey)
        {
            $menuList->$menuKey = $this->lang->admin->menuList->$menuKey;
            $menuList->$menuKey['order'] = $index;
            $index++;
        }
        foreach($disabled as $menuKey => $menu)
        {
            $menuList->$menuKey = $menu;
            $menuList->$menuKey['order'] = $index;
            $index++;
        }

        $this->lang->admin->menuList = $menuList;
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
     * Get switcher.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return string
     */
    public function getSwitcher($currentMenuKey = 'setting')
    {
        if(empty($currentMenuKey)) return null;

        $currentMenu = $this->lang->admin->menuList->$currentMenuKey;
        $output      = "<div class='btn-group header-btn'><button class='btn pull-right btn-link' data-toggle='dropdown'> <span class='text'>{$currentMenu['name']}</span> <span class='caret'></span></button><ul class='dropdown-menu' id='adminMenu'>";
        foreach($this->lang->admin->menuList as $menuKey => $menuGroup)
        {
            $class = $menuKey == $currentMenuKey ? "class='active'" : '';
            if($menuGroup['disabled']) $class .= ' disabled';
            $output .= "<li $class>" . html::a($menuGroup['link'], $menuGroup['name']) . "</li>";
        }
        $output .= "</ul></div>";

        return $output;
    }

    /**
     * Get extensions from zentao.net.
     *
     * @param  string $type extension|patch
     * @param  int    $limit
     * @param  bool   $hasInternet
     * @access public
     * @return array
     */
    public function getExtensionsByAPI($type = 'extension', $limit = 6, $hasInternet = true)
    {
        if($hasInternet)
        {
            $searchType = $type == 'extension' ? 'byUpdatedTime' : 'byModule';
            $param      = $type == 'extension' ? '' : 'MTIxOA==';
            $extensions = $this->loadModel('extension')->getExtensionsByAPI($searchType, $param, 0, $limit);
            $extensions = isset($extensions->extensions) ? (array)$extensions->extensions : array();
        }
        else
        {
            if($this->config->edition == 'open')
            {
                $extensions = array(
                    $this->config->admin->plugIns[27],
                    $this->config->admin->plugIns[26],
                    $this->config->admin->plugIns[30]
                );
            }
            else
            {
                $extensions = array(
                    $this->config->admin->plugIns[198],
                    $this->config->admin->plugIns[203]
                );
            }
        }

        return $extensions;
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
            $publicClass[$index]->name = $course->title;
            $publicClass[$index]->image = $this->config->admin->apiRoot . $course->image->list[0]->smallURL;
            $publicClass[$index]->viewLink = $this->config->admin->apiRoot . "{$course->alias}-{$course->id}.html";
            $index++;
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
