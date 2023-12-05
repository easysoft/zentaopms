<?php
declare(strict_types=1);
/**
 * The control file of qa module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     qa
 * @version     $Id: control.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
class qa extends control
{
    /**
     * 测试应用仪表盘页面。
     * The index of qa, go to bug's browse page.
     *
     * @param  string $locate
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function index(string $locate = 'auto', int $productID = 0, int $projectID = 0)
    {
        $this->loadModel('product');
        $products = $projectID ? $this->product->getProductPairsByProject($projectID, 'noclosed') : $this->product->getPairs('noclosed', 0, '', 'all');
        if(empty($products)) $this->locate($this->createLink('product', 'showErrorNone', "moduleName=qa&activeMenu=index"));

        $productID = $this->product->checkAccess($productID, $products);
        $branch    = (int)$this->cookie->preBranch;
        $this->qa->setMenu($productID, $branch);

        if($locate == 'yes') $this->locate($this->createLink('bug', 'browse', "product=$productID"));

        $this->view->title    = $this->lang->qa->index;
        $this->view->products = $products;
        echo $this->fetch('block', 'dashboard', 'dashboard=qa');
    }
}
