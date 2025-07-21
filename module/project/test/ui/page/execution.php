<?php
class executionPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'name'       => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[2]/div/a",
            'begin'      => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[5]/div",
            'end'        => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[6]/div",
            'subName'    => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[4]/div/a[1]",
            'subBegin'   => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[18]/div",
            'subEnd'     => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[19]/div",
            'editBtn'    => "//*[@id='table-project-execution']/div[2]/div[3]/div/div/div/nav/a[3]/i",
            'submitBtn'  => "//*[@id='zin_programplan_edit_form']/div[9]/div/button/span",
            'addSprint'  => "//*[@id='actionBar']/a[2]/span",
            'sprintName' => "//*[@id='table-project-execution']/div[2]/div[1]/div/div[2]/div/a",
            'planEnd'    => "//*[@id='table-project-execution']/div[2]/div[2]/div/div[6]/div",
            /* 运营界面元素 */
            'allTab'       => "//*[@id='featureBar']/menu/li[1]/a/span[1]",
            'undoneTab'    => "//*[@id='featureBar']/menu/li[2]/a/span[1]",
            'waitTab'      => "//*[@id='featureBar']/menu/li[3]/a/span[1]",
            'doingTab'     => "//*[@id='featureBar']/menu/li[4]/a/span[1]",
            'suspendedTab' => "//*[@id='featureBar']/menu/li[5]/a/span[1]",
            'delayedTab'   => "//*[@id='featureBar']/menu/li[6]/a/span[1]",
            'closedTab'    => "//*[@id='featureBar']/menu/li[7]/a/span[1]",
            'num'          => "//*[@id='featureBar']/menu/li[1]/a/span[2]",
            /* 研发界面元素 */
            'numDom'       => "//*[@id='table-project-execution']/div[3]/div[2]/strong[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
