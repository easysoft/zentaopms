<?php
declare(strict_types=1);
/**
 * The model file of qa module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     qa
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class qaModel extends model
{
    /**
     * 设置测试应用下导航权限和链接。
     * Set qa menu.
     *
     * @param  int         $productID
     * @param  int|string  $branch
     * @access public
     * @return void
     */
    public function setMenu(int $productID = 0, int|string $branch = '')
    {
        if(!$this->app->user->admin && strpos(",{$this->app->user->view->products},", ",$productID,") === false && $productID != 0 && !commonModel::isTutorialMode())
        {
            $this->app->loadLang('product');
            return $this->app->control->sendError($this->lang->product->accessDenied, helper::createLink('qa', 'index'));
        }

        if($this->session->branch) $branch = $this->session->branch;
        if($this->cookie->preBranch !== '' and $branch === '') $branch = $this->cookie->preBranch;
        helper::setcookie('preBranch', (string)$branch);

        $product = $this->loadModel('product')->getByID($productID);
        if($product and $product->type != 'normal') $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);

        if(!common::hasPriv('zahost', 'browse') and !common::hasPriv('zanode', 'browse')) unset($this->lang->qa->menu->automation);
        common::setMenuVars('qa', $productID);

        return true;
    }
}
