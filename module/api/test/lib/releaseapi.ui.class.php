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
        $form->wait(1);
        $form->dom->version->setValue($apiVersion->fstVersion);
        $form->dom->btn($this->lang->save)->click();
        $form->wait(1);
        $form->dom->releaseBtn->click();
        $form->wait(1);
        if($form->dom->fstVersion->getText() != $apiVersion->fstVersion) return $this->failed('发布接口失败');
        return $this->success('发布接口成功');
    }
}
