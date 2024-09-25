<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editBranchTester extends tester
{
    /**
     * 编辑分支
     * edit  branch
     *
     * @param $branch    分支数据
     * @param $productID 产品ID
     *
     * @return mixed
     */
    public function editBranch($branch, $productID)
    {
        $form = $this->initForm('branch', 'manage', $productID, 'appIframe-product');
        $form->dom->allTab->click();
        $form->dom->editBtn->click();
        //设置表单字段值
        if (isset($branch->name)) $form->dom->editName->setValue($branch->name);
