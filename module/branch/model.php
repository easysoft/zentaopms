<?php
/**
 * The model file of branch module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branchModel extends model
{
    /**
     * Get name by id.
     * 
     * @param  int    $branchID 
     * @access public
     * @return string
     */
    public function getById($branchID, $productID = 0)
    {
        if(empty($branchID))
        {
            if(empty($productID)) $productID = $this->session->product;
            $product = $this->loadModel('product')->getById($productID);
            return $this->lang->branch->all . $this->lang->product->branchName[$product->type];
        }
        return $this->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch('name');
    }

    /**
     * Get pairs.
     * 
     * @param  int    $productID 
     * @param  string $params 
     * @access public
     * @return array
     */
    public function getPairs($productID, $params = '')
    {
        $branches = $this->dao->select('*')->from(TABLE_BRANCH)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->orderBy('id_asc')->fetchPairs('id', 'name');
        if(strpos($params, 'noempty') === false)
        {
            $product = $this->loadModel('product')->getById($productID);
            if(!$product or $product->type == 'normal') return array();

            $branches = array('0' => $this->lang->branch->all . $this->lang->product->branchName[$product->type]) + $branches;
        }
        return $branches;
    }

    /**
     * Manage branch 
     * 
     * @param  int    $productID 
     * @access public
     * @return bool
     */
    public function manage($productID)
    {
        $oldBranches = $this->getPairs($productID, 'noempty');
        $data = fixer::input('post')->get();
        if(isset($data->branch))
        {
            foreach($data->branch as $branchID => $branch)
            {
                if($oldBranches[$branchID] != $branch) $this->dao->update(TABLE_BRANCH)->set('name')->eq($branch)->where('id')->eq($branchID)->exec();
            }
        }
        foreach($data->newbranch as $branch)
        {
            if(empty($branch)) continue;
            $this->dao->insert(TABLE_BRANCH)->set('name')->eq($branch)->set('product')->eq($productID)->exec();
        }

        return dao::isError();
    }

    /**
     * Get branch group by products 
     * 
     * @param  array  $products 
     * @param  string $params 
     * @access public
     * @return array
     */
    public function getByProducts($products, $params = '')
    {
        $branches = $this->dao->select('*')->from(TABLE_BRANCH)->where('product')->in($products)->andWhere('deleted')->eq(0)->fetchAll();
        $products = $this->loadModel('product')->getByIdList($products);

        $branchGroups = array();
        foreach($branches as $branch)
        {
            if(!isset($branchGroups[$branch->product]) and strpos($params, 'noempty') === false) $branchGroups[$branch->product][0] = $this->lang->branch->all . $this->lang->product->branchName[$products[$branch->product]->type];
            if($products[$branch->product]->type == 'normal')
            {
                $branchGroups[$branch->product][0] = '';
            }
            else
            {
                $branchGroups[$branch->product][$branch->id] = $branch->name;
            }
        }

        return $branchGroups;
    }

    /**
     * Get product bype by branch.
     * 
     * @param  int    $branchID 
     * @access public
     * @return void
     */
    public function getProductType($branchID)
    {
        return $this->dao->select('t2.type')->from(TABLE_BRANCH)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.id')->eq($branchID)
            ->fetch('type');
    }
}
