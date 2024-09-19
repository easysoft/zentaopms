<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /**
     * 我收藏的文档。
     * Check my favorite docs.
     *
     * @param  string $docName
     * @access public
     * @return void
     */
    public function myFavorites($docName)
    {
        /*进入我的空间创建并收藏一个文档*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->createDocBtn->click();
        $form->wait(1);
        $form->dom->showTitle->setValue($docName->dcName);
        $form->dom->saveBtn->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();

        /*收藏文档*/
        $this->openUrl('doc', 'mySpace', array('type' => 'mine'));
        $form = $this->loadPage('doc', 'mySpace', array('type' => 'mine'));
        $form->dom->fstSaveBtn->click();
        $form->wait(1);
        $form->dom->myFavorites->click();
    }
}
