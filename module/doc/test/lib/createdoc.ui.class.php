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
        $this->openUrl('doc', 'mySpace');
        return $this->success('创建草稿成功。');
    }
}
