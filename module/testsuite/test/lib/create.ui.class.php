<?php
declare(strict_types=1);
include dirname(__FILE__, 5).'/test/lib/ui.php';
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
            $form->wait(1);
