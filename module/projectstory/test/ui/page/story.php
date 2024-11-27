<?php
class storyPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /* 标签 */
            'allTab'               => "//*[@id='main']/div/div[1]/div[1]/menu/li[1]/a/span[1]",
            'unclosedTab'          => "//*[@id='main']/div/div[1]/div[1]/menu/li[2]/a/span[1]",
            'draftTab'             => "//*[@id='main']/div/div[1]/div[1]/menu/li[3]/a/span[1]",
            'reviewingTab'         => "//*[@id='main']/div/div[1]/div[1]/menu/li[4]/a/span[1]",
            'changingTab'          => "//*[@id='main']/div/div[1]/div[1]/menu/li[5]/a/span[1]",
            'moreTab'              => "//*[@id='main']/div/div[1]/div[1]/menu/li[6]/a/span[1]",
            'closedTab'            => "//*[@id='more']/menu/menu/li[1]/a/div/div",
            'linkedExecutionTab'   => "//*[@id='more']/menu/menu/li[2]/a/div/div",
            'unlinkedExecutionTab' => "//*[@id='more']/menu/menu/li[3]/a/div/div",
            'num'                  => "//*[@id='stories_table']/div/div[3]/div[2]/strong[1]",
            'allTabNum'            => "//*[@id='main']/div/div[1]/div[1]/menu/li[1]/a/span[2]",
            /* 导出 */
            'format'         => "//*[@data-name='fileType']/div/div/span[1]",
            'encoding'       => "//*[@data-name='encode']/div/div/span[1]",
            'data'           => "//*[@data-name='exportType']/div/div/span[1]",
            'exportBtnAlert' => "//*[@class='modal-content']/div[3]/div/div/form/div[8]/div/div/button/span",
            /* 关联需求 */
            'allTabNum'      => "//*[@id='main']/div/div[1]/div[1]/menu/li[1]/a/span[2]",
            'linkStoryBtn'   => "//*[@id='actionBar']/div[3]/a/span",
            'searchBtn'      => "//*[@class='btn primary']",
            'selectAllStory' => "//*[@id='table-projectstory-linkstory']/div[3]/div/div/label",
            'saveBtn'        => "//*[@id='table-projectstory-linkstory']/div[3]/nav/button/span",
            /* 移除需求 */
            'unlinkFirBtn'   => "//*[@id='stories']/div[2]/div[3]/div/div[1]/div/nav/a[6]/i",
            'batchUnlinkBtn' => "//*[@id='stories']/div[3]/nav[1]/button[1]/span",
            'selectAllBtn'   => "//*[@id='stories']/div[3]/div/div/label"
            /* 指派 */
            'firstAssignTo'  => "//*[@id='stories']/div[2]/div[2]/div/div[7]/div/a/span",
            'assignedTo'     => "//*[@data-name='assignedTo']/div/div",
            'assignBtn'      => "//*[@class='panel-body']/form/div[3]/div/button",
            'batchAssignBtn' => "//*[@id='stories']/div[3]/nav/button[4]/span[1]",
            'assignToAdmin'  => "//*[@class='item-content']/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
