<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createBranchTester extends tester
{
    /**
     * 创建分支
     * Create  branch
     *
     * @param $branch    分支数据
     * @param $branchurl 产品ID
     *
     * @return mixed
     */
    public function createBranch($branch, $branchurl)
    {
        $form = $this->initForm('branch', 'manage', $branchurl, 'appIframe-product');
        $form->dom->btn($this->lang->branch->createAction)->click();
        $form->waitElement($form->dom->branchName);
        //设置表单字段值
        if (isset($branch->name)) $form->dom->branchName->setValue($branch->name);
        if (isset($branch->desc)) $form->dom->branchDesc->setValue($branch->desc);
        $form->dom->save->click();
        $form->wait(2);
        if ($form->dom->createBranchForm)
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
        else
        {
            return $this->success('创建分支成功');
        }
    }
}
