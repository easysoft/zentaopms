<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDesignTester extends tester
{
    /**
     * Create a design.
     * 创建设计输入表单。
     *
     * @param  array  $design
     * @access public
     * @return object
     */
    public function createDesign(array $design)
    {
        $form = $this->initForm('design', 'create', array('projecID' => 60, 'productID' => 0), 'appIframe-project');
        if(isset($design['product'])) $form->dom->type->setValue($design['product']);
        if(isset($design['type']))    $form->dom->type->setValue($design['type']);
        if(isset($design['name']))    $form->dom->name->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkResult($design);
    }

    /**
     * Check the result after create the design.
     * 创建设计后检查结果。
     *
     * @param  array $design
     * @access public
     * @return object
     */
    public function checkResult(array $design)
    {
        if($this->response('method') != 'browse')
        {
            if($this->checkFormTips('design')) return $this->success('创建设计表单页提示信息正确');
            return $this->failed('创建设计表单页提示信息不正确');

        }

        /* 跳转到设计列表，检查设计字段信息。 */
        $browsePage = $this->loadPage('design', 'browse');
        if($browsePage->dom->designName->getText()    != $design['name']) return $this->failed('设计名称错误');
        if($browsePage->dom->linkedProduct->getText() != $design['product']) return $this->failed('所属产品错误');
        if($browsePage->dom->designType->getText()    != $design['type']) return $this->failed('设计类型错误');

        return $this->success();
    }
}
