<?php
declare(strict_types=1);
include dirname(__FILE__, 6).'/test/lib/ui.php';
class editTestSuiteTester extends tester
{
    /*
     * Check information after edit testsuite.
     * @access public
     * @return object
     */
    public function editTestSuite()
    {
        $form = $this->initForm('testsuite', 'edit', array('id' => 1), 'appIframe-qa');
        $form->dom->name->setValue('测试套件编辑');
        $form->dom->saveBtn->click();
        $form->wait(1);

        $viewPage = $this->loadPage('testsuite', 'view');
        if($viewPage->dom->name->getText() != '测试套件编辑') return $this->failed('编辑测试套件名称错误');

        return $this->success('编辑套件测试成功');
    }
}
