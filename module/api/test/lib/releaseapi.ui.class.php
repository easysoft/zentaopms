<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class createDocTester extends tester
{
    /*
     * 发布接口。
     * Release api.
     *
     * @param  string $apiVersion
     * @access public
     * @return void
     */
    public function releaseApi($apiVersion)
    {
        $form = $this->initForm('api', 'index', array(), 'appIframe-doc');
        $form->dom->publishBtn->click();
    }
}
