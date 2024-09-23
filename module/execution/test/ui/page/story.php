<?php
class storyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'       => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[1]/a",
            'unclosedTab'  => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[2]/a",
            'draftTab'     => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[3]/a",
            'reviewingTab' => "//*[@data-page='execution-story']//div[@id='featureBar']/menu/li[3]/a",
            'num'          => "//*[@id='table-execution-story']/div[3]/div[2]/strong[1]",
            /* 列表 */
            'firstCheckbox'  => "//*[@id='table-execution-story']/div[2]/div[1]/div/div[1]/div/div",
            'firstName'      => "//*[@id='table-execution-story']/div[2]/div[1]/div/div[2]/div/a",
            'firstPhase'     => "//*[@id='table-execution-story']/div[2]/div[2]/div/div[6]/div/span",
            'firstAssignTo'  => "//*[@id='table-execution-story']/div[2]/div[2]/div/div[7]/div/a/span",
            'firstUnlinkBtn' => "//*[@id='table-execution-story']/div[2]/div[3]/div/div/div/nav/a[5]",
            /* 批量操作按钮 */
            'batchAssignBtn' => "//*[@id='table-execution-story']/div[3]/nav[1]/button[1]",
            'assignToAdmin'  => "//li//div[text() = 'admin']",
            'phaseBtn'       => "//*[@id='table-execution-story']/div[3]/nav[1]/button[3]",
            'phases'         => "//*[@data-page='execution-story']/div[5]/menu/menu",
            'aaa' => "//li//div[text() = '未开始']/../../../a",
            /* 需求指派弹窗 */
            'submitBtn' => "//*[@data-name='assignedTo']/../div[3]/div/button",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
