<?php
class grouptaskPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'taskLinkedStoryBtn' => "//*[@id='featureBar']/menu/li[4]/a/span", //已关联研发需求的任务按钮
            'collapseBtn'        => "//*[@id='featureBar']/menu/li[1]/a/span", //全部折叠按钮
            /* 分组 */
            'dropdownBtn' => "//*[@id='tasksTable']/table/thead/tr/th[1]/button",
            'story'       => "//*[@data-id='execution-grouptask']/../div[3]//li[1]/a",
            'status'      => "//*[@data-id='execution-grouptask']/../div[3]//li[2]/a",
            'priority'    => "//*[@data-id='execution-grouptask']/../div[3]//li[3]/a",
            'assignedTo'  => "//*[@data-id='execution-grouptask']/../div[3]//li[4]/a",
            'finishedBy'  => "//*[@data-id='execution-grouptask']/../div[3]//li[5]/a",
            'closedBy'    => "//*[@data-id='execution-grouptask']/../div[3]//li[6]/a",
            'type'        => "//*[@data-id='execution-grouptask']/../div[3]//li[7]/a",
            /* 全部展开时左侧区块统计 */
            'task'  => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[1]",
            'time'  => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[2]",
            /* 全部收起时右侧区块统计 */
            'rtask'      => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[1]/div/div[1]/div[2]",
            'rdoing'     => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[1]/div/div[2]/div[2]",
            'rwaiting'   => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[1]/div/div[3]/div[2]",
            'restimates' => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[2]/div/div[1]/div[2]",
            'rcost'      => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[2]/div/div[2]/div[2]",
            'rleft'      => "//*[@id='tasksTable']/table/tbody/tr[7]/td[2]/div/div[2]/div/div[3]/div[2]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
