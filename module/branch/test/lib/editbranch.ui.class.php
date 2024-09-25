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
        if (isset($branch->desc)) $form->dom->editDesc->setValue($branch->desc);
        $form->dom->editSave->click();
        $form->wait(2);
        if ($form->dom->zin_branch_edit_1_form)
        {
            if ($this->checkFormTips('branch')) return $this->success('分支名称必填提示信息正确');
            if (isset($branch->name) && ($form->dom->nameTip))
            {
                //分支已存在
                $nameTip = str_replace('@branch@', $this->lang->branch->common, $this->lang->branch->existName);
                return ($form->dom->nameTip->getText() == $nameTip)
                    ? $this->success('分支已存在提示信息正确')
                    : $this->failed('分支已存在提示信息不正确');
            }
            return $this->failed('分支名称必填提示信息不正确');
        }
        return ($form->dom->secName->getText() == $branch->name)
            ? $this->success('编辑分支成功')
            : $this->failed('编辑分支失败');
    }
}
