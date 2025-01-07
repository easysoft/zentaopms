<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
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
