<?php
class kanbanPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'settings'            => "//*[@id='navbar']//a[@data-id='settings']/span",
            'groupPickerInLite'   => "//*[@id='actionBar']/button[1]",
            'kanbanSettingInLite' => "//*[@id='actionBar']/button[2]/span",
            /* 编辑看板弹窗中的提交按钮 */
            'editSubmitInLite'    => "//form[contains(@id,'zin_execution_edit')]/div[last()]/button",
            /* 开始看板弹窗中的提交按钮 */
            'startSubmitInLite'   => "//*[@data-name='realBegan']/..//button",
            /* 延期看板弹窗中的提交按钮 */
            'putoffSubmitInLite'  => "//form[contains(@id,'zin_execution_putoff')]//button",
            /* 挂起看板弹窗中的提交按钮 */
            'suspendSubmitInLite' => "//form[contains(@id,'zin_execution_suspend')]//button",
            /* 关闭看板弹窗中的元素 */
            'realEndField'      => "//div[@data-name='realEnd']/label/span",
            'closeSubmitInLite' => "//form[contains(@id,'zin_execution_close')]//button",
            /* 激活看板弹窗中的提交按钮 */
            'activateSubmitInLite' => "//form[contains(@id,'zin_execution_activate')]//button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
