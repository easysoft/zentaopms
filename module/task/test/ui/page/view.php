<?php
include dirname(__FILE__, 4) . '/lang/zh-cn.php';
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'submitBtn'  => "//button[@type='submit']",
            /* 开始任务弹窗 */
            'assignedToDelBtn' => "//*[@name='assignedTo']/../button",    //指派给组件中的删除按钮
            'modalText'        => "//*[@class='modal-body']",             //总计消耗和预计剩余都为空或0时弹窗提示
            'confirmBtn'       => "//*[@class='modal-footer']//button[1]",
            /* 基本信息 */
            'taskStatus' => "//div[contains(@class, 'task-status')]//span",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
