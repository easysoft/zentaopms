<?php
declare(strict_types=1);
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
     * @param  int         $productID
     * @param  int|string  $branch
     * @access public
     * @return void
     */
    public function setMenu(int $productID = 0, int|string $branch = '')
    {
        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$productID,") === false and $productID != 0 and !commonModel::isTutorialMode())
        {
            $this->app->loadLang('product');
            return print(js::error($this->lang->product->accessDenied) . js::locate('back'));
        }

        if($this->session->branch) $branch = $this->session->branch;
        if($this->cookie->preBranch !== '' and $branch === '') $branch = $this->cookie->preBranch;
        helper::setcookie('preBranch', $branch);

        $product = $this->loadModel('product')->getByID($productID);
        if($product and $product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

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
