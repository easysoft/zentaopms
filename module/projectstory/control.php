<?php
/**
 * The control file of projectStory module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectStory
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class projectStory extends control
{
    public $products = array();

    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->products = $this->loadModel('product')->getProductPairsByProject($this->session->PRJ);
        if(empty($this->products)) die($this->locate($this->createLink('product', 'showErrorNone', "fromModule=projectStory")));
    }

    public function requirement($projectID = 0, $productID = 0, $branch = 0)
    {
        $this->projectstory->setMenu($this->products, $productID, $branch);
        $this->display();
    }

    public function story()
    {
        $this->display();
    }
}

