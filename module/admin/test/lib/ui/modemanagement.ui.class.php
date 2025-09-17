<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
class modeManagementTester extends tester
{
    /**
     * 切换管理模式。
     * Mode system management.
     *
     * @param  string $programName
     * @access public
     * @return void
     */
    public function modeManagement($programName)
    {
        $this->openUrl('admin', 'index');
        $form = $this->loadPage('admin', 'index');
        $form->dom->systemSetting->click();
        $form->wait(1);
        $form->dom->useLight->click();
        $form->wait(1);
        $form->dom->comfirmBtn->click();
        $form->wait(1);
        if($form->dom->secTop->getText() != '产品') return $this->failed('切换轻量级模式失败');
        return $this->success('切换轻量级模式成功');
    }
}
