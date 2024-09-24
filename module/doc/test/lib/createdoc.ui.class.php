<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 创建草稿文档。
     * Create a draft.
     *
     * @param  string $draftName
     * @access public
     * @return void
     */
    public function createDraft($draftName)
    {
        $this->openUrl('doc', 'myspace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'myspace', array('objectType' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->dom->showTitle->setValue($draftName->dftName);
        $form->dom->saveDraftBtn->click();
        $this->openUrl('doc', 'myspace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'myspace', array('objectType' => 'mine'));
        $form->dom->search(array("文档标题,=,{$draftName->dftName}"));
        $form->wait(1);

        if($form->dom->fstDocLabel->getText() != '草稿') return $this->failed('创建草稿失败。');
        return $this->success('创建草稿成功。');
    }

    /**
     * 创建文档。
     * Create a doc.
     *
     * @param  string $docName
     * @access public
     * @return void
     */
    public function createDoc($docName)
    {
        $this->openUrl('doc', 'mySpace', array('objectType' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('objectType' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();

        $this->openUrl('doc', 'mySpace', array('objectType' => 'createdby'));
        $form = $this->loadPage('doc', 'mySpace', array('objectType' => 'createdby'));
        $form->dom->search(array("文档标题,=,{$docName->dcName}"));
        $form->wait(1);

        if($form->dom->fstDocName->getText() != $docName->dcName) return $this->failed('文档创建失败');
        return $this->success('文档创建成功');
    }

    /*
     * 创建产品文档。
     * Create a product doc.
     *
     * @param  string $docName
     * @access public
     * @return void
     */
    public function createProductDoc($productName, $docName)
    {
        /*创建两个产品*/
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($productName->fstProduct);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        $form = $this->initForm('product', 'create');
        $form->dom->name->setValue($productName->secProduct);
        $form->dom->btn($this->lang->save)->click();
    }
}
