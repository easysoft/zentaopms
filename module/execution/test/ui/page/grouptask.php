<?php
class managemembersPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'taskLinkedStoryBtn' => "//*[@id='featureBar']/menu/li[4]/a/span",
            /* 分组 */
            'dropdownBtn' => "//*[@id='tasksTable']/table/thead/tr/th[1]/button",
            'story'       => "//*[@data-id='execution-grouptask']/../div[3]//li[1]",
            'status'      => "//*[@data-id='execution-grouptask']/../div[3]//li[2]",
            'priority'    => "//*[@data-id='execution-grouptask']/../div[3]//li[3]",
            'assignedTo'  => "//*[@data-id='execution-grouptask']/../div[3]//li[4]",
            'finishedBy'  => "//*[@data-id='execution-grouptask']/../div[3]//li[5]",
            'closedBy'    => "//*[@data-id='execution-grouptask']/../div[3]//li[6]",
            'type'        => "//*[@data-id='execution-grouptask']/../div[3]//li[7]"
            /* 全部展开时左侧区块统计 */
            'group'     => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/a",
            'tasks'     => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[1]/strong",
            'waiting'   => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[1]/text()[2]",
            'doing'     => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[1]/text()[3]",
            'estimates' => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[2]/strong",
            'cost'      => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[1]/text()[2]",
            'left'      => "//*[@id='tasksTable']/table/tbody/tr[1]/td[1]/div/div/div[2]/text()[3]"
            /* 全部收起时右侧区块统计 */
            'rtasks'     => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[1]/div[1]/div[1]/div[2]",
            'rdoing'     => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[1]/div[1]/div[2]/div[2]/span",
            'rwaiting'   => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[1]/div[1]/div[3]/div[2]/span",
            'restimates' => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[2]/div[1]/div[1]/div[2]",
            'rcost'      => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[2]/div[1]/div[2]/div[2]",
            'rleft'      => "//tr[@class='group-summary group-toggle']/td[2]/div[1]/div[2]/div[1]/div[3]/div[2]"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
