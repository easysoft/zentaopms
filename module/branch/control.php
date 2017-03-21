<?php
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branch extends control
{
    /**
     * Manage branch 
     * 
     * @param  int    $productID 
     * @access public
     * @return void
     */
    public function manage($productID)
    {
        if($_POST)
        {
            $this->branch->manage($productID);
            die(js::reload('parent'));
        }

        $products = $this->loadModel('product')->getPairs('nocode');
        $this->product->setMenu($products, $productID);

        $position[] = html::a($this->createLink('product', 'browse', "productID=$productID"), $products[$productID]);
        $position[] = $this->lang->branch->manage;

        $this->view->title    = $this->lang->branch->manage;
        $this->view->position = $position;
        $this->view->branches = $this->branch->getPairs($productID, 'noempty');
        $this->display();
    }

    /**
     * Sort branch.
     * 
     * @access public
     * @return void
     */
    public function sort()
    {
        $this->branch->sort();
    }

    /**
     * Ajax get drop menu.
     * 
     * @param  int    $productID 
     * @param  string $module 
     * @param  string $method 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function ajaxGetDropMenu($productID, $module, $method, $extra)
    {
        $this->view->link      = $this->loadModel('product')->getProductLink($module, $method, $extra, true);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;

        $branches = $this->branch->getPairs($productID);
        $this->view->branches       = $branches;
        $this->view->branchesPinyin = common::convert2Pinyin($branches);
        $this->display();
    }

    /**
     * Delete branch 
     * 
     * @param  int    $branchID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function delete($branchID, $confirm = 'no')
    {
        $this->app->loadLang('product');
        $productType = $this->branch->getProductType($branchID);
        if(!$this->branch->checkBranchData($branchID)) die(js::alert(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->canNotDelete)));
        if($confirm == 'no')
        {
            die(js::confirm(str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmDelete), inlink('delete', "branchID=$branchID&confirm=yes")));
        }

        $this->branch->delete(TABLE_BRANCH, $branchID);
        die(js::reload('parent'));
    }

    /**
     * Ajax get branches.
     * 
     * @param  int    $productID 
     * @param  int    $oldBranch 
     * @access public
     * @return void
     */
    public function ajaxGetBranches($productID, $oldBranch = 0)
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product) or $product->type == 'normal') die();

        $branches = $this->branch->getPairs($productID);
        if($oldBranch) $branches = array($oldBranch => $branches[$oldBranch]);
        die(html::select('branch', $branches, '', "class='form-control' onchange='loadBranch(this)'"));
    }
}
