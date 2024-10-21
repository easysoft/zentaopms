<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /*
     * 创建数据结构。
     * Create a struct.
     *
     * @param  string $dataStruct
     * @access public
     * @return void
     */
    public function createStruct($dataStruct)
    {
        $form = $this->initForm('api', 'struct', array('libID' => '1'), 'appIframe-doc');
        $form->dom->createStructBtn->click();
        $form->wait(1);
        $form->dom->name->setValue($dataStruct->name);
        $form->dom->btn($this->lang->save)->click();
        if($form->dom->fstStructName->getText() != $dataStruct->name) return $this->failed('创建数据结构失败');
        return $this->success('创建数据结构成功');
    }
}
