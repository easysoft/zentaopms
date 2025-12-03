<?php
declare(strict_types=1);
include dirname(__FILE__, 6).'/test/lib/ui.php';
class createTestSuiteTester extends tester
{
    /*
     *检查创建套件后套件类型和名称
     * @param string $control
     * @access public
     * @return object
     */
    public function CreateTestSuite($control)
    {
        $form = $this->initForm('testsuite', 'create', array('productID' => 1), 'appIframe-qa');
        if($control == 'private')
        {
            $form->dom->name->setValue('测试套件私有');
            $form->dom->typeprivate->click();
            $form->dom->saveBtn->click();
            $form->wait(3);

            $browsePage = $this->loadPage('testsuite', 'browse');
            if($browsePage->dom->type->getText() != '私有')         return $this->failed('创建测试套件类型误');
            if($browsePage->dom->name->getText() != '测试套件私有') return $this->failed('创建测试套件名称错误');
        }
        else
        {
            $form->dom->name->setValue('测试套件公开');
            $form->dom->typepublic->click();
            $form->dom->saveBtn->click();

            $browsePage = $this->loadPage('testsuite', 'browse');
            if($browsePage->dom->type->getText() != '公开')         return $this->failed('创建测试套件类型错误');
            if($browsePage->dom->name->getText() != '测试套件公开') return $this->failed('创建测试套件名称错误');
        }

        return $this->success('创建套件测试成功');
    }
}
