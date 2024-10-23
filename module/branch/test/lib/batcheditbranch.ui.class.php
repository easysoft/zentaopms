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
        $secID   = $form->dom->id_static_1->getText();//获取第二个ID
        $nameDom = "name[{$secID}]";
        $descDom = "desc[{$secID}]";
        $tipDom  = "name[{$secID}]Tip";
        //设置表单字段
        if (isset($editBranch->name)) $form->dom->$nameDom->setValue($editBranch->name);
        if (isset($editBranch->desc)) $form->dom->$descDom->setValue($editBranch->desc);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(2);
        //判断批量编辑结果
        if ($form->dom->allTab === false)
        {
            $nameTip      = $form->dom->$tipDom->getText();
            $branchName   = sprintf($this->lang->branch->name, $this->lang->branch->common);
            $nameEmptyTip = sprintf($this->lang->error->notempty, $branchName);
            if ($nameTip === $nameEmptyTip) return $this->success('分支名称必填提示信息正确');
            if ($editBranch->name != '' && $form->dom->$tipDom)
            {
                $nameExistTip = str_replace('@branch@', $this->lang->branch->common, $this->lang->branch->existName);
                return ($nameTip === $nameExistTip)
                    ? $this->success('分支已存在提示信息正确')
                    : $this->failed('分支已存在提示信息不正确');
            }
            return $this->failed('分支名称必填提示信息不正确');
        }
        else
        {
            $branchName = $form->dom->secName->getText();
            $branchDesc = $form->dom->secDesc->getText();
            return ($branchName === $editBranch->name && $branchDesc === $editBranch->desc)
                ? $this->success('批量编辑分支成功')
                : $this->failed('批量编辑分支失败');
        }
    }
}
