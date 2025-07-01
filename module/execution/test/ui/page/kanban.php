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
            /* 开始看板弹窗中的提交按钮 */
            'startSubmitInLite'   => "//*[@data-name='realBegan']/..//button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
