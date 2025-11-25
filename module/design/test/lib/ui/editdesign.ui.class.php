<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
    public function editDesign($design)
    {
        $form = $this->initForm('design', 'edit', array('designID' => 1), 'appIframe-project');
        if(isset($design['product'])) $form->dom->type->setValue($design['product']);
        if(isset($design['type']))    $form->dom->type->setValue($design['type']);
        if(isset($design['name']))    $form->dom->name->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        return $this->checkResult($design, $form);
    }

    /**
     * Check the result after edit the design.
     * 编辑设计后检查结果。
     *
     * @param  array $design
     * @access public
     * @return object
     */
    public function checkResult($design, $form)
    {
        if($design['name'] == '')
        {
            $nameTip     = $form->dom->nameTip->getText();
            $nameTipText = sprintf($this->lang->error->notempty, $this->lang->design->name);
            return ($nameTip == $nameTipText) ? $this->success('设计名称必填提示信息正确') : $this->failed('设计名称必填提示信息不正确');
        }
        /* 跳转到设计详情页，检查设计字段信息。 */
        else
        {
            $viewPage = $this->loadPage('design', 'view');
            $viewPage->wait(2);
            if($viewPage->dom->designName->getText()    != $design['name']) return $this->failed('设计名称错误');
            if($viewPage->dom->linkedProduct->getText() != $design['product']) return $this->failed('所属产品错误');
        }
        return $this->success();
    }
}
