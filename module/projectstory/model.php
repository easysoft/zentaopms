<?php
/**
 * The model file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id
 * @link        http://www.zentao.net
 */
class projectstoryModel extends model
{
    /**
     * Set the menu.
     *
     * @param  array $products
     * @param  int   $productID
     * @param  int   $branch
     * @access public
     * @return void
     */
    public function setMenu($products = array(), $productID = 0, $branch = 0)
    {
        /* Determine if the product is accessible. */
        if($products and (!isset($products[$productID]) or !$this->loadModel('product')->checkPriv($productID))) $this->loadModel('product')->accessDenied();

        if(empty($productID)) $productID = key($products);
        $this->loadModel('product')->setMenu($products, $productID, $branch);
        $this->lang->modulePageNav = $this->product->select($products, $productID, 'projectstory', $this->app->rawMethod, '', $branch);
    }
}
