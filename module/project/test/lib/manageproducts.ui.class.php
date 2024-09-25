<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class manageProductsTester extends tester
{
    /**
     * 检查选择产品不能为空。
     * Check selected products cannot be empty.
     *
     * @param  array  $project
     * @access public
     * @return void
     */
    public function linkNoProducts($project)
    {
        $form = $this->initForm('project', 'manageproducts', array('project' => 11), 'appIframe-project');
        $form->dom->linkBtn->click(); //点击关联其他产品
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        if($form->dom->alertModal('text') === '『产品』不能为空。') return $this->success('关联其他产品必填提示信息正确');
        return $this->failed('关联其他产品必填提示信息不正确');
    }
}
