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
     * Project id.
     *
     * @var    int
     * @access public
     */
    public $projectID;

    /**
     * Construct.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        /* Set qa menu group. */
        $this->projectID = isset($_GET['PRJ']) ? $_GET['PRJ'] : 0;
        if(!$this->projectID)
        {
            foreach($this->config->qa->menuList as $module) $this->lang->navGroup->$module = 'qa';
            $this->lang->noMenuModule[] = $this->app->rawModule;
        }
        else 
        {    
            $this->lang->qa->menu    = $this->lang->projectQa->menu;
            $this->lang->qa->subMenu = $this->lang->projectQa->subMenu;
        }
    }

    /**
     * The index of qa, go to bug's browse page.
     *
     * @access public
     * @return void
     */
    public function index($locate = 'auto', $productID = 0)
    {
        $this->products = $this->loadModel('product')->getProductPairsByProject($this->projectID, 'noclosed');
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', 'fromModule=qa&moduleGroup=' . $this->lang->navGroup->qa . '&activeMenu=index')));
        if($locate == 'yes') $this->locate($this->createLink('bug', 'browse'));

        $productID = $this->product->saveState($productID, $this->products);
        $branch    = (int)$this->cookie->preBranch;
        $this->qa->setMenu($this->products, $productID, $branch);

        $this->view->title      = $this->lang->qa->index;
        $this->view->position[] = $this->lang->qa->index;
        $this->view->products   = $this->products;
        $this->display();
    }
}
