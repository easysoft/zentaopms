<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class editDesignTester extends tester
{
    /**
     * Edit a design.
     * 编辑设计输入表单。
     *
     * @param  array  $design
     * @access public
     * @return object
     */
    public function editDesign(array $design)
    {
        $form = $this->initForm('design', 'edit', array('designID' => 1), 'appIframe-project');
        if(isset($design['product'])) $form->dom->type->setValue($design['product']);
        if(isset($design['type']))    $form->dom->type->setValue($design['type']);
        if(isset($design['name']))    $form->dom->name->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkResult($design);
    }

    public function checkResult(array $design)
    {
        if($this->response('method') != 'view')
        {
            if($this->checkFormTips('design')) return $this->success('编辑设计表单页提示信息正确');
            return $this->failed('编辑设计表单页提示信息不正确');

        }

        /* 跳转到设计详情页，检查设计字段信息。 */
        $viewPage = $this->loadPage('design', 'view');
        $viewPage->wait(2);
        if($viewPage->dom->designName->getText()    != $design['name']) return $this->failed('设计名称错误');
        if($viewPage->dom->linkedProduct->getText() != $design['product']) return $this->failed('所属产品错误');

        return $this->success();
    }
}
