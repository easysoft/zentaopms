<?php
class allPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 列表字段 */
            'firstCheckbox' => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'     => "//*[@id='table-execution-all']/div[2]/div[1]/div/div[2]/div/div/div/a",
            'firstStatus'   => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[2]/div",
            'firstBegin'    => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[4]/div",
            'firstEnd'      => "//*[@id='table-execution-all']/div[2]/div[2]/div/div[5]/div",
            /* 标签 */
            'allTab'       => "//*[@id='featureBar']/menu/li[1]/a",
            'undoneTab'    => "//*[@id='featureBar']/menu/li[2]/a",
            'waitTab'      => "//*[@id='featureBar']/menu/li[3]/a",
            'doingTab'     => "//*[@id='featureBar']/menu/li[4]/a",
            'suspendedTab' => "//*[@id='featureBar']/menu/li[5]/a",
            'closedTab'    => "//*[@id='featureBar']/menu/li[6]/a",
            /* 批量编辑状态 */
            'statusBtn' => "//*[@id='table-execution-all']/div[3]/nav[1]/button",
            'wait'      => "//*[@data-page='execution-all']/div[2]/menu/menu/li[1]/a",
            'doing'     => "//*[@data-page='execution-all']/div[2]/menu/menu/li[2]/a",
            'suspended' => "//*[@data-page='execution-all']/div[2]/menu/menu/li[3]/a",
            'closed'    => "//*[@data-page='execution-all']/div[2]/menu/menu/li[4]/a",
            /* 列表底部统计数据 */
            'num' => "//*[@id='table-execution-all']/div[3]/div[2]/strong",

        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
