<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchEditBranchTester extends tester
{
    /**
     * 批量编辑分支
     * batch edit branch
     *
     * @param $editBranch 分支数据
     * @param $productID  产品ID
     * @return mixed
     */
    public function batchEditBranch($editBranch, $productID)
    {
        $form = $this->initForm('branch', 'manage', $productID, 'appIframe-product');
        $form->dom->allTab->click();
        $form->wait(1);
        $form->dom->selectAllBtn->click();//全选分支
        $form->dom->batchEditBtn->click();//点击编辑按钮
        $form->wait(2);
