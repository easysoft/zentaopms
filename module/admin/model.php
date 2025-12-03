<?php
declare(strict_types=1);
/**
 * The model file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: model.php 5148 2013-07-16 01:31:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class adminModel extends model
{
    /**
     * 获取密钥。
     * Get secret key.
     *
     * @access public
     * @return object
     */
    public function getSecretKey(): object
    {
        $apiConfig = $this->getApiConfig();
        $apiURL    = $this->config->admin->apiRoot . "/user-secretKey.json";

        $params['u'] = $this->config->global->community;
        $params['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $params[$apiConfig->sessionVar]  = $apiConfig->sessionID;
        $params['k'] = $this->getSignature($params);

        $result = common::http($apiURL . '?' . http_build_query($params));
        $result = json_decode($result);
        return $result;
    }

    /**
     * 获取签名。
     * Get signature.
     *
     * @param  array    $params
     * @access public
     * @return string
     */
    public function getSignature(array $params): string
    {
        unset($params['u']);
        $privateKey = $this->config->global->ztPrivateKey;
        return md5(http_build_query($params) . md5($privateKey));
    }

    /**
     * 获取禅道官网配置信息。
     * Get api config.
     *
     * @access public
     * @return object
     */
    public function getApiConfig(): ?object
    {
        if(!$this->session->apiConfig || time() - $this->session->apiConfig->serverTime > $this->session->apiConfig->expiredTime)
        {
            $config = file_get_contents($this->config->admin->apiRoot . "?mode=getconfig");
            if(empty($config)) return null;

            $config = json_decode($config);
            if(empty($config->sessionID)) return null;
            $this->session->set('apiConfig', $config);
        }
        return $this->session->apiConfig;
    }

    /**
     * 弱口令扫描。
     * Check weak.
     *
     * @param  object    $user
     * @access public
     * @return bool
     */
    public function checkWeak(object $user): bool
    {
        $weaks = array();
        foreach(explode(',', $this->config->safe->weak) as $weak)
        {
            $weaks[$weak] = md5(trim($weak));
        }

        if(isset($weaks[$user->password])) return true;
        if(in_array($user->password, $weaks)) return true;
        if($user->password == md5($user->account)) return true;
        if($user->phone    && $user->password == md5($user->phone))    return true;
        if($user->mobile   && $user->password == md5($user->mobile))   return true;
        if($user->birthday && $user->password == md5($user->birthday)) return true;
        return false;
    }

    /**
     * 设置后台二级导航。
     * Set admin menu.
     *
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function setMenu(int $groupID = 0): void
    {
        $this->checkPrivMenu();

        $menuKey = $this->getMenuKey();
        if(empty($menuKey)) return;

        $customKey  = "admin-$menuKey";
        $customMenu = isset($this->config->customMenu->{$customKey}) ? json_decode($this->config->customMenu->{$customKey}) : array();

        $this->setSwitcher($menuKey);
        if(isset($this->lang->admin->menuList->$menuKey))
        {
            if(in_array($menuKey, array('projectflow', 'productflow')) && $groupID) $this->lang->admin->menuList->{$menuKey}['subMenu'] = $this->lang->admin->menuList->{$menuKey}['childMenu'];
            if(isset($this->lang->admin->menuList->{$menuKey}['subMenu']))
            {
                $moduleName = $this->app->rawModule;
                $methodName = $this->app->rawMethod;
                $firstParam = $this->app->rawParams ? reset($this->app->rawParams) : '';

                foreach($this->lang->admin->menuList->{$menuKey}['subMenu'] as $subMenuKey => $subMenu)
                {
                    $subModule = '';
                    if($moduleName == 'custom' && strpos(',required,set,', $methodName) !== false)
                    {
                        if(isset($this->config->admin->navsGroup[$menuKey][$subMenuKey]) && strpos($this->config->admin->navsGroup[$menuKey][$subMenuKey], ",$firstParam,") !== false) $subModule = 'custom';
                        if($firstParam == $subMenuKey) $subModule = 'custom';
                    }

                    /* 针对项目流程和产品流程菜单做特殊处理。 */
                    if(in_array($menuKey, array('projectflow', 'productflow')) && $groupID)
                    {
                        $workflowGroup = $this->fetchByID($groupID, 'workflowgroup');
                        if($subMenuKey == 'review' && $workflowGroup->projectModel == 'kanban') continue;
                        if($subMenuKey == 'stage' && in_array($workflowGroup->projectModel, array('kanban', 'scrum', 'agileplus'))) continue;

                        $subMenu['link'] = sprintf($subMenu['link'], $groupID);
                        if(isset($subMenu['subMenu']))
                        {
                            foreach($subMenu['subMenu'] as $subSubMenuKey => $subSubMenu)
                            {
                                $subMenu['subMenu'][$subSubMenuKey]['link'] = sprintf($subSubMenu['link'], $groupID);
                            }
                        }
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

            if($customMenu)
            {
                $this->lang->admin->menuList->{$menuKey}['menuOrder']   = array();
                $this->lang->admin->menuList->{$menuKey}['dividerMenu'] = '';
                $prev = '';
                foreach($customMenu as $item)
                {
                    $this->lang->admin->menuList->{$menuKey}['menuOrder'][$item->order] = $item->name;
                    if($prev == 'divider') $this->lang->admin->menuList->{$menuKey}['dividerMenu'] .= ',' . $item->name;
                    $prev = $item->name;
                }

                if($this->lang->admin->menuList->{$menuKey}['dividerMenu']) $this->lang->admin->menuList->{$menuKey}['dividerMenu'] = ',' . trim($this->lang->admin->menuList->{$menuKey}['dividerMenu']) . ',';
                ksort($this->lang->admin->menuList->{$menuKey}['menuOrder']);
            }

            if(isset($this->lang->admin->menuList->{$menuKey}['menuOrder']))   $this->lang->admin->menuOrder   = $this->lang->admin->menuList->{$menuKey}['menuOrder'];
            if(isset($this->lang->admin->menuList->{$menuKey}['dividerMenu'])) $this->lang->admin->dividerMenu = $this->lang->admin->menuList->{$menuKey}['dividerMenu'];
            if(isset($this->lang->admin->menuList->{$menuKey}['tabMenu']))     $this->lang->admin->tabMenu     = $this->lang->admin->menuList->{$menuKey}['tabMenu'];
        }
    }

    /**
     * 检查导航权限并设置导航链接。
     * Check priv menu.
     *
     * @access public
     * @return void
     */
    public function checkPrivMenu(): void
    {
        $orders = array();
        foreach($this->lang->admin->menuList as $menuKey => $menu)
        {
            $menu['disabled'] = true;
            if(!isset($menu['link'])) $menu['link'] = '';

            if($menuKey == 'company' && $this->app->rawModule != 'convert')
            {
                $dept = $this->dao->select('id')->from(TABLE_DEPT)->limit(1)->fetch();
                if($dept && common::hasPriv('company', 'browse')) $menu['link'] = helper::createLink('company', 'browse');
            }

            if(!empty($menu['link']) && strpos($menu['link'], '|') !== false)
            {
                list($module, $method, $params) = explode('|', $menu['link'] . '|');
                $menu['link'] = helper::createLink($module, $method, $params);
                if(($this->app->user->admin || $module . $method != 'adminregister') && common::hasPriv($module, $method)) $menu['disabled'] = false;
            }

            /* Set links to authorized navigation. */
            if(!in_array($menuKey, array('productflow', 'projectflow')) && isset($menu['subMenu']))
            {
                $menu = $this->setSubMenu($menuKey, $menu);
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
     * 设置二级导航。
     * Set sub menu.
     *
     * @param  string $menuKey
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function setSubMenu(string $menuKey, array $menu): array
    {
        /* Reorder secondary navigation. */
        $subMenuList   = array();
        $subMenuOrders = !empty($menu['menuOrder']) ? $menu['menuOrder'] : array();
        if(empty($subMenuOrders)) return array();
        ksort($subMenuOrders);
        foreach($subMenuOrders as $value)
        {
            if(!isset($menu['subMenu'][$value])) continue;
            $subMenuList[$value] = $menu['subMenu'][$value];
        }

        foreach($subMenuList as $subMenuKey => $subMenu)
        {
            /* Set links by special config. */
            if($menuKey == 'message' && $subMenuKey == 'mail')
            {
                $this->loadModel('mail');
                if(!$this->config->mail->turnon && !$this->session->mailConfig) $subMenu['link'] = $this->lang->mail->common . '|mail|detect|';
            }
            if($menuKey == 'dev' && $subMenuKey == 'editor')
            {
                if(!empty($this->config->global->editor)) $subMenu['link'] = $this->lang->editor->common . '|editor|index|';
                if(empty($this->config->global->editor) && !$this->app->user->admin)
                {
                    unset($menu['subMenu']['editor']);
                    continue;
                }
            }

            /* Get authorized links and change parent navigation links. */
            $link = array();
            if(isset($menu['tabMenu'][$subMenuKey]))
            {
                list($menu, $link) = $this->setTabMenu($subMenuKey, $menu);
            }
            else
            {
                $link = $this->getHasPrivLink($subMenu);
            }

            if(!empty($link))
            {
                /* Updated secondary navigation link. */
                list($module, $method, $params) = $link;
                $menu['subMenu'][$subMenuKey]['link'] = substr($subMenu['link'], 0, strpos($subMenu['link'], '|') + 1) . $module . '|' . $method . '|' . $params;

                /* Update the level 1 navigation link. */
                if(!empty($menu['disabled']))
                {
                    $menu['link']     = helper::createLink($module, $method, $params);
                    $menu['disabled'] = false;
                }
            }
        }

        return $menu;
    }

    /**
     * 设置三级导航。
     * Set tab menu.
     *
     * @param  string $subMenuKey
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function setTabMenu(string $subMenuKey, array $menu): array
    {
        /* Reorder tertiary navigation. */
        $tabMenuList = $menu['tabMenu'][$subMenuKey];
        if(isset($menu['tabMenu']['menuOrder'][$subMenuKey]))
        {
            $tabMenuOrders = $menu['tabMenu']['menuOrder'][$subMenuKey];
            ksort($tabMenuOrders);
            foreach($tabMenuOrders as $value) $tabMenuList[$value] = $menu['tabMenu'][$subMenuKey][$value];
        }

        /* Check tab menu priv. */
        $link = array();
        foreach($tabMenuList as $tabMenuKey => $tabMenu)
        {
            $tabMenuLink = $this->getHasPrivLink($tabMenu);
            if(!empty($tabMenuLink))
            {
                /* Updated tertiary navigation links. */
                list($module, $method, $params) = $tabMenuLink;
                $menu['tabMenu'][$subMenuKey][$tabMenuKey]['link'] = substr($tabMenu['link'], 0, strpos($tabMenu['link'], '|') + 1) . $module . '|' . $method . '|' . $params;
            }
            if(empty($link)) $link = $tabMenuLink;
        }
        return array($menu, $link);
    }

    /**
     * 获取有权限的链接。
     * Get the authorized link.
     *
     * @param  array  $menu
     * @access public
     * @return array
     */
    public function getHasPrivLink(array $menu): array
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
     * 获取页面所在的导航索引。
     * Get menu key
     *
     * @access public
     * @return string
     */
    public function getMenuKey(): string
    {
        $moduleName = $this->app->rawModule;
        $methodName = $this->app->rawMethod;
        $firstParam = $this->app->rawParams ? reset($this->app->rawParams) : '';
        if($this->config->edition == 'open')
        {
            $this->config->admin->menuGroup['feature'][] = 'stage|browse';
            $this->config->admin->menuGroup['feature'][] = 'stage|batchcreate';
        }

        foreach($this->config->admin->menuGroup as $menuKey => $menuGroup)
        {
            if(in_array($moduleName, $menuGroup))
            {
                return $menuKey;
            }
            elseif(in_array("$moduleName|$methodName", $menuGroup))
            {
                if($moduleName == 'custom' && ($methodName == 'required' || $methodName == 'set'))
                {
                    if(in_array($firstParam, $this->config->admin->menuModuleGroup[$menuKey]["custom|$methodName"])) return $menuKey;
                }
                elseif($moduleName == 'workflowgroup' && $firstParam && !in_array($methodName, array('project', 'product')))
                {
                    $workflowgroup = $this->loadModel('workflowgroup')->fetchByID($firstParam);
                    return $workflowgroup->type == 'project' ? 'projectflow' : 'productflow';
                }
                else
                {
                    return $menuKey;
                }
            }
        }
        return '';
    }

    /**
     * 检查网络。
     * Check internet.
     *
     * @param  string $url
     * @param  int    $timeout
     * @access public
     * @return bool
     */
    public function checkInternet(string $url = '', int $timeout = 1): bool
    {
        if(empty($url)) $url = $this->config->admin->apiSite;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
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
     * 获取禅道使用时长。
     * Get date used object.
     *
     * @param  string $end
     * @access public
     * @return object
     */
    public function genDateUsed($end = ''): object
    {
        $firstUseDate = $this->dao->select('min(`date`) as `date`')->from(TABLE_ACTION)
            ->where('`date`')->ge(FIRST_RELEASE_DATE)
            ->andWhere('actor')->eq($this->app->user->account)
            ->fetch('date');

        if($firstUseDate) $firstUseDate = substr($firstUseDate, 0, 10);
        return helper::getDateInterval($firstUseDate, $end);
    }

    /**
     * 获取1.5级下拉菜单。
     * Set switcher.
     *
     * @param  string $currentMenuKey
     * @access public
     * @return void
     */
    public function setSwitcher(string $currentMenuKey = 'system')
    {
        if(empty($currentMenuKey)) return null;

        $currentMenu = $this->lang->admin->menuList->$currentMenuKey;
        $output      = "<div class='btn-group header-btn'>";
        $output     .= "<button class='btn pull-right btn-link' data-toggle='dropdown'>";
        $output     .= "<span class='text'>{$currentMenu['name']}</span> ";
        $output     .= "<span class='caret'></span></button>";
        $output     .= "<ul class='dropdown-menu' id='adminMenu'>";
        foreach($this->lang->admin->menuList as $menuKey => $menuGroup)
        {
            if($this->config->vision == 'lite' and !in_array($menuKey, $this->config->admin->liteMenuList)) continue;
            $class = $menuKey == $currentMenuKey ? "active" : '';
            if(!empty($menuGroup['disabled'])) $class .= ' disabled not-clear-menu';
            $output .= "<li class='$class'>" . html::a(!empty($menuGroup['disabled']) ? '###' : (!empty($menuGroup['link']) ? $menuGroup['link'] : ''), (!empty($menuGroup['icon']) ? "<i class='icon icon-{$menuGroup['icon']} svg-icon bg-{$menuGroup['bg']}' style='height:24px;width:24px;text-align:center;border-radius:0.375rem;color:#fff;align-content:center;margin-left:6px;opacity:1;transform: scale(1);'></i>" : "<img src='{$this->config->webRoot}static/svg/admin-{$menuKey}.svg'/>") . $menuGroup['name']) . "</li>";
        }
        $output .= "</ul></div>";

        $this->lang->switcherMenu = $output;
    }
}
