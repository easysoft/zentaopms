<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class batchCreateDesignTester extends tester
{
    /**
     * Batch create a design.
     * 批量创建设计。
     *
     * @param  array  $design
     * @access public
     * @return object
     */
    public function batchCreateDesign(array $design)
    {
        $form = $this->initForm('design', 'batchCreate', array('projecID' => 60, 'productID' => 0), 'appIframe-project');
        if(isset($design['type'])) $form->dom->type->picker($design['type']);
        if(isset($design['name'])) $form->dom->name_0->setValue($design['name']);

        $form->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /* 跳转到设计列表，检查设计字段信息。*/
        $browsePage = $this->loadPage('design', 'browse');
        if($browsePage->dom->designName->getText() != $design['name']) return $this->failed('设计名称错误');
        if($browsePage->dom->designType->getText() != $design['type']) return $this->failed('设计类型错误');

        return $this->success();
    }
}
