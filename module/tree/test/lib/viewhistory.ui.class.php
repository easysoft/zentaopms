<?php
include dirname(__FILE__, 5) . '/test/lib/ui.php';
class viewhistoryTester extends tester
{
    /**
     * 检查模块维护的历史记录。
     * Check the history of the module.
     *
     * @param  string $param
     * @param  string $action
     * @access public
     * @return void
     */
    public function checkHistory($param, $action)
    {
        $form = $this->initForm('tree', 'browse', array('product' => '1', 'view' => 'story'), 'appIframe-product');
        $form->dom->btn($this->lang->history)->click();
        $form->wait();
        $history = sprintf($this->ang->module->action->{$action}, '1', '2', '3');
        var_dump($history);die;
        var_dump($form->dom->{$param}->getText());die;
        if(strpos($form->dom->{$param}->getText(), $this->lang->action->label->{$action}) !== false) return $this->success('历史记录正确');
        return $this->failed('历史记录不正确');
    }
}
