<?php
/**
 * The model file of qa module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @param  array  $products
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $branch = 0)
    {
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $selectHtml = $this->product->select($products, $productID, 'qa', 'index', '', $branch);

        $productIndex  = '';
        $isMobile      = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $productIndex  = html::a(helper::createLink('qa', 'index'), $this->lang->qa->index) . $this->lang->colon;
            $productIndex .= $selectHtml;
        }
        else
        {
            $productIndex  = '<div class="btn-group angle-btn"><div class="btn-group">' . html::a(helper::createLink('qa', 'index', 'locate=no'), $this->lang->qa->index, '', "class='btn'") . '</div></div>';
            $productIndex .= $selectHtml;
        }

        $this->lang->modulePageNav = $productIndex;
        foreach($this->lang->qa->menu as $key => $menu)
        {
            $replace = $productID;
            common::setMenuVars($this->lang->qa->menu, $key, $replace);
        }
    }
}
