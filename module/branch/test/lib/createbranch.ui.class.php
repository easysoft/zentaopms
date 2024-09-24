<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createBranchTester extends tester
{
    /**
     * 创建分支
     * Create  branch
     *
     * @param $branch
     * @param  array  $branchurl
     *
     * @return mixed
     */
    public function createBranch($branch, $branchurl)
    {
        $form = $this->initForm('branch', 'manage', $branchurl, 'appIframe-product');
        $form->dom->btn($this->lang->branch->createAction)->click();
        $form->wait(2);
        //设置表单字段
        /*if ($form->dom->branchName === false)
        {
            var_dump('没找到分支名称输入框');
        }
        else
        {
            var_dump('找到了');
        }*/
        $form->dom->branchName->click();
        if (isset($branch->name)) $form->dom->branchName->setValue($branch->name);
        if (isset($branch->desc)) $form->dom->desc->setValue($branch->desc);
        $form->wait(2);
        $form->dom->save->click();
        $form->wait(2);
        if ($form->dom->createBranchForm)
        {
            var_dump($this->page->dom->getFormTips());
            if ($this->checkFormTips('branch'))
            {
                return $this->success('创建分支表单页提示信息正确');
            }
            return $this->failed('创建分支表单页提示信息不正确');
        }
        else
        {
            return $this->success('创建分支成功');
        }
    }
}
