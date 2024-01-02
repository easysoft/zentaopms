<?php
/**
 * The model file of qa module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     qa
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class qaModel extends model
{
    /**
     * Set menu.
     *
     * @param  array       $products
     * @param  int         $productID
     * @param  int|string  $branch
     * @param  string      $extra
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = '', $extra = '')
    {
        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$productID,") === false and $productID != 0 and !defined('TUTORIAL'))
        {
            $this->app->loadLang('product');
            $productID = key($products);
            $locate    = $productID ? helper::createLink('bug', 'browse', "productID=$productID") : helper::createLink('qa', 'index');
            return print(js::error($this->lang->product->accessDenied) . js::locate($locate));
        }

        $branch = ($this->cookie->preBranch !== '' and $branch === '') ? $this->cookie->preBranch : $branch;
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        $product = $this->loadModel('product')->getById($productID);
        if($product and $product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        if(!in_array($this->app->rawModule, $this->config->qa->noDropMenuModule)) $this->lang->switcherMenu = $this->product->getSwitcher($productID, $extra, $branch);
        if($this->app->rawModule == 'product' and $this->app->rawMethod == 'showerrornone') $this->lang->switcherMenu = '';
        if(!common::hasPriv('zahost', 'browse') and !common::hasPriv('zanode', 'browse')) unset($this->lang->qa->menu->automation);
        common::setMenuVars('qa', $productID);
    }

    /**
     * Set qa subMenu.
     *
     * @param  string $module
     * @param  string $key
     * @param  int    $id
     * @access public
     * @return void
     */
    public function setSubMenu($module, $key, $id)
    {
        if(!isset($this->lang->$module->subMenu->$key)) return true;

        $moduleSubMenu = $this->lang->$module->subMenu->$key;
        $subMenu       = common::createSubMenu($this->lang->$module->subMenu->$key, $id);
        $moduleName    = $this->app->getModuleName();
        $methodName    = $this->app->getMethodName();

        if(!empty($subMenu))
        {
            foreach($subMenu as $menuKey => $menu)
            {
                $itemMenu = zget($moduleSubMenu, $menuKey, '');
                $isActive['method']    = ($moduleName == strtolower($menu->link['module']) and $methodName == strtolower($menu->link['method']));
                $isActive['alias']     = ($moduleName == strtolower($menu->link['module']) and (is_array($itemMenu) and isset($itemMenu['alias']) and strpos(',' . $itemMenu['alias'] . ',', ",$methodName,") !== false));
                $isActive['subModule'] = (is_array($itemMenu) and isset($itemMenu['subModule']) and strpos($itemMenu['subModule'], $moduleName) !== false);
                if($isActive['method'] or $isActive['alias'] or $isActive['subModule'])
                {
                    $this->lang->$module->menu->{$key}['link'] = $menu->text . "|" . join('|', $menu->link);
                    break;
                }
            }
            $this->lang->$module->menu->{$key}['subMenu'] = $subMenu;
        }
    }
}
