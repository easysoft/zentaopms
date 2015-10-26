<?php
/**
 * The control file of branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branch extends control
{
    public function manage($productID)
    {
        if($_POST)
        {
            $this->branch->manage($productID);
            die(js::reload('parent'));
        }
        $this->view->title = $this->lang->branch->manage;
        $this->view->position[] = $this->lang->branch->manage;

        $this->loadModel('product')->setMenu($this->product->getPairs('nocode'), $productID);

        $this->view->product  = $this->product->getById($productID);
        $this->view->branches = $this->branch->getPairs($productID, 'noempty');
        $this->display();
    }

    public function ajaxGetDropMenu($productID, $module, $method, $extra)
    {
        $this->view->link      = $this->loadModel('product')->getProductLink($module, $method, $extra, true);
        $this->view->productID = $productID;
        $this->view->module    = $module;
        $this->view->method    = $method;
        $this->view->extra     = $extra;
        $this->view->branches  = $this->branch->getPairs($productID);
        $this->display();
    }

    public function ajaxGetMatchedItems($keywords, $module, $method, $extra, $objectID)
    {
        $this->view->link      = $this->loadModel('product')->getProductLink($module, $method, $extra, true);
        $this->view->branches  = $this->dao->select('*')->from(TABLE_BRANCH)->where('deleted')->eq(0)->andWhere('product')->eq($objectID)->andWhere('name')->like("%$keywords%")->orderBy('id desc')->fetchPairs('id', 'name');
        $this->view->productID = $objectID;
        $this->view->keywords  = $keywords;
        $this->display();
    }

    public function ajaxGetBranches($productID)
    {
        $product = $this->loadModel('product')->getById($productID);
        if(empty($product) or $product->type == 'normal') die();

        $branches = $this->branch->getPairs($productID);
        die(html::select('branch', $branches, '', "class='form-control'"));
    }
}

