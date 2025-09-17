<?php
include dirname(__FILE__, 6) . '/test/lib/ui.php';
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
        $form->wait(1);
        $form->dom->btn($this->lang->history)->click();
        $form->wait();
        $content = explode(" ", $form->dom->{$param}->getText());

        if($action == 'edited')
        {
            $history = $this->lang->action->desc->{$action};
        }
        else
        {
            $history = $this->lang->module->action->{$action}->main;
        }
        $history = explode(" ", $history);

        if(trim($history[3]) == $content[4]) return $this->success('历史记录正确');
        return $this->failed('历史记录不正确');
    }
}
