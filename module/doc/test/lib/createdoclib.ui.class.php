<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 创建我的文档库。
     * Create a doclib.
     *
     * @param  string $docLibName
     * @access public
     * @return void
     */
    public function createDocLib($libName)
    {
        /*进入我的空间下创建文档库*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->createLibBtn->click();
        $form->wait(1);

        $form->dom->libName->setValue($libName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /*判断名称必填项以及是否保存成功*/
        if($form->dom->leftListHeader->getText() != $libName)
        {
            if($form->dom->nameTip->getText() != '『库名称』不能为空。') return $this->failed('库名称为空校验失败。');
            return $this->success('库名称非空校验成功。');
        }
        return $this->success('创建我的文档库成功');
    }
}
