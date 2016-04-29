<?php
/**
 * The control file of qa module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     qa
 * @version     $Id: control.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
class qa extends control
{
    /**
     * The index of qa, go to bug's browse page.
     * 
     * @access public
     * @return void
     */
    public function index($locate = 'auto', $productID = 0)
    {
        if(!isset($this->config->qa->homepage)) die($this->fetch('custom', 'ajaxSetHomepage', "module=qa"));

        $homepage = $this->config->qa->homepage;
        if($homepage == 'browse' and $locate == 'auto') $locate = 'yes';

        if($locate == 'yes') $this->locate($this->createLink('bug', 'browse'));

        unset($this->lang->qa->menu->index);
        $this->loadModel('product');
        $this->products = $this->product->getPairs('nocode');
        $productID      = $this->product->saveState($productID, $this->products);
        $branch         = $this->session->branch;
        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title      = $this->lang->qa->index;
        $this->view->position[] = $this->lang->qa->index;
        $this->view->products   = $this->products;
        $this->display();
    }
}
