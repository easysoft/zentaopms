<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 编辑文档。
     * Edit a draft.
     *
     * @param  string $editDocName
     * @access public
     * @return void
     */
    public function editDoc($docName, $editDocName)
    {
        /*进入我的空间下创建文档*/
        $this->openUrl('doc', 'myspace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'myspace', array('objectType' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();
    }
}
