<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            /*运营管理界面*/
            'storyName'        => "//*[@id='mainContent']/div/div[1]/div[1]/span[2]",
            'TargetLife'       => "//*[@id='mainContent']/div/div[2]/div[2]/div[1]/div[1]/ul/li[2]/a",
            'openedBy'         => "//*[@id='mainContent']/div/div[2]/div[2]//div[2]/div[2]//div[1]/div[3]/div[2]//span",
            'storyStatus'      => '//*[@id="zin_projectstory_view_tabPane"]/div/div[2]/div[2]/span',
            'targetLife'       => '//*[@id="mainContent"]/div/div[2]/div[2]/div[1]/div[1]/ul/li[2]',
            'assignTo'         => '//*[@id="zin_projectstory_view_tabPane_1"]/div/div[2]/div[2]',
            'storyReviwer'     => '//*[@zui-key="side"]/div/div[2]/div[2]/div/div[3]/div[2]//span',
            'storyEstimate'    => '//*[@id="zin_projectstory_view_tabPane"]/div/div[4]/div[2]',
            /*lite操作栏按钮*/
            'closeBtn'    => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[last()-3]',
            'activateBtn' => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[last()-3]',
            'revokeBtn'   => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[2]',
            'assignBtn'   => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[last()-4]',
            /*lite操作弹窗*/
            'closestoryBtn'    => '//*[@id="zin_story_close_1_form"]/div[4]/div/button',
            'activateStoryBtn' => '//*[@id="zin_story_activate_1_form"]/div[3]/div/button',
            'submitReviewBtn'  => '//*[@id="zin_story_submitreview_1_form"]/div[2]/div/button',
            'assignSubmitBtn'  => '//*[@id="zin_story_assignto_1_form"]/div[3]/div/button',
            'confirmBtn'       => '//button[@z-key="confirm"]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
