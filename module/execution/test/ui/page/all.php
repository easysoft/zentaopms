<?php
class allPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstCheckbox' => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'     => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[2]/div/div/div/a",
            'firstStatus'   => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[2]/div",
            'firstBegin'    => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[4]/div",
            'firstEnd'      => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[5]/div",
            /* 标签 */
            'allTab'       => "//*[@data-id='all']",
            'undoneTab'    => "//*[@data-id='undone']",
            'waitTab'      => "//*[@data-id='wait']",
            'doingTab'     => "//*[@data-id='doing']",
            'suspendedTab' => "//*[@data-id='suspended']",
            'closedTab'    => "//*[@data-id='closed']",
            /* 批量编辑状态 */
            'statusBtn' => "//*[@id='table-execution-all']/div[3]/nav[1]/button",
            'wait'      => "//*[@data-page='execution-all']/div[2]/menu/menu/li[1]/a",
            'doing'     => "//*[@data-page='execution-all']/div[2]/menu/menu/li[2]/a",
            'suspended' => "//*[@data-page='execution-all']/div[2]/menu/menu/li[3]/a",
            'closed'    => "//*[@data-page='execution-all']/div[2]/menu/menu/li[4]/a",

        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
