<?php
/**
 * The model file of automation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     automation
 * @version     $Id: model.php 5148 2013-07-16 01:31:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class automationModel extends model
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
    public function setMenu($products, $productID, $branch = 0)
    {
        $this->loadModel('product');
        foreach($this->lang->testtask->menu as $key => $value)
        {
            if($this->lang->navGroup->testtask != 'qa') $this->loadModel('qa')->setSubMenu('automation', $key, $productID);
            common::setMenuVars($this->lang->testtask->menu, $key, $productID);
        }

        if($this->lang->navGroup->automation == 'qa')
        {
            foreach($this->lang->qa->subMenu->automation as $key => $menu)
            {
                common::setMenuVars($this->lang->qa->subMenu->automation, $key, $productID);
            }
            $this->lang->qa->menu         = $this->lang->automation->menu;
            $this->lang->automation->menu = $this->lang->qa->subMenu->automation;
            $this->lang->qa->switcherMenu = $this->product->getSwitcher($productID, '', $branch);
        }
    }

}
