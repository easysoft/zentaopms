<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 编辑我的文档库。
     * Edit a doclib.
     *
     * @param  string $docLibName
     * @access public
     * @return void
     */
    public function editDocLib($libName, $editLibName)
    {
        /*创建文档库*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->createLibBtn->click();
        $form->wait(1);
        $form->dom->name->setValue($libName->libName);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);

        /*编辑文档库*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->fstMoreBtn->click();
        $form->dom->editLib->click();
        $form->wait(1);
        $form->dom->name->setValue($editLibName->editName);
        $form->dom->btn($this->lang->save)->click();
        if($form->dom->fstDocLib->getText() != $editLibName->editName) return $this->failed('编辑文档库失败。');
        return $this->success('编辑文档库成功。');
    }

    /**
     * 删除一个文档库。
     * Delete a docLib.
     *
     * @param  string $docLibName
     * @access public
     * @return void
     */
    public function deleteDocLib($editLibName)
    {
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->fstDocLib->click();
        $form->dom->fstMoreBtn->click();
        $form->dom->deleteLib->click();
        $form->wait(1);
        $form->dom->deleteAccept->click();

        if($form->dom->listHeader->getText() != $editLibName->editName) return $this->success('删除文档库成功。');
    }
}
