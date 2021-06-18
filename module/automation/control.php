<?php
/**
 * The control file of automation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     automation
 * @version     $Id: control.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class automation extends control
{
    /** 
     * Products.
     *
     * @var    array
     * @access public
     */
    public $products = array();

    /**
     * Construct function, load product module, assign products to view auto.
     *  
     * @access public 
     * @return void
     */ 
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->view->products = $this->products = $products = $this->loadModel('product')->getPairs();
    }

    /**
     * Automation details.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function browse($productID = 0, $branch = 0)
    {
        /* Set menu. */
        $productID = $this->product->saveState($productID, $this->products);
        if(empty($branch)) $branch = (int)$this->cookie->preBranch;
        $this->loadModel('qa')->setMenu($this->products, $productID, $branch);

        $this->view->title      = $this->lang->automation->common;
        $this->view->position[] = $this->lang->automation->browse;
        $this->display();
    }
}
