<?php
declare(strict_types=1);

include dirname(__FILE__, 6).'/test/lib/ui.php';
class viewTester extends tester
{
    /**
     * Check the view page.
     * @access public
     * @return void
     */
     public function view()
     {
         $form          = $this->initForm('testsuite', 'browse', array('productID' => 1), 'appIframe-qa');
         $testsuiteName = $form->dom->name->getText();
         $form->dom->name->click();

         $viewPage = $this->loadPage('testsuite', 'view');
         if($this->response('method') != 'view' && $viewPage->dom->name->getText() != $testsuiteName) return $this->failed('测试套件详情页不正确');

         return $this->success('套件详情页测试成功');
     }
}
