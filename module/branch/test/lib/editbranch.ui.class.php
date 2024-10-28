<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editBranchTester extends tester
{
    /**
     * 编辑分支
     * edit branch
     *
     * @param $editBranch 分支数据
     * @param $productID  产品ID
     *
     * @return mixed
     */
    public function editBranch($editBranch, $productID)
    {
        $form = $this->initForm('branch', 'manage', $productID, 'appIframe-product');
        $form->dom->allTab->click();
        $form->wait(1);
        $form->dom->editBtn->click();
        //设置表单字段值
        if (isset($editBranch->name)) $form->dom->editName->setValue($editBranch->name);
        if (isset($editBranch->desc)) $form->dom->editDesc->setValue($editBranch->desc);
        $form->wait(1);
        $form->dom->editSave->click();
        $form->wait(1);
        if ($form->dom->zin_branch_edit_1_form)
        {
            $nameTip      = $form->dom->nameTip->getText();
            $branchName   = sprintf($this->lang->branch->name, $this->lang->branch->common);
            $nameEmptyTip = sprintf($this->lang->error->notempty, $branchName);
            if ($nameTip == $nameEmptyTip) return $this->success('分支名称必填提示信息正确');
            if ($editBranch->name != '' && $form->dom->nameTip)
            {
                //分支已存在
                $nameTip = str_replace('@branch@', $this->lang->branch->common, $this->lang->branch->existName);
                return ($form->dom->nameTip->getText() == $nameTip)
                    ? $this->success('分支已存在提示信息正确')
                    : $this->failed('分支已存在提示信息不正确');
            }
            return $this->failed('分支名称必填提示信息不正确');
        }
        return ($form->dom->secName->getText() == $editBranch->name)
            ? $this->success('编辑分支成功')
            : $this->failed('编辑分支失败');
    }
}
