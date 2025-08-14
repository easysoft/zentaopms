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
            'closeBtn'         => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[last()-3]',
            'closestoryBtn'    => '//*[@id="zin_story_close_1_form"]/div[4]/div/button',
            'activateBtn'      => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[last()-3]',
            'activateStoryBtn' => '//*[@id="zin_story_activate_1_form"]/div[3]/div/button',
            'revokeBtn'        => '//*[@id="mainContent"]/div/div[2]/div[1]/div[3]/div/div/a[2]',
            'confirmBtn'       => '//button[@z-key="confirm"]',
            'storyStatus'      => '//*[@id="zin_projectstory_view_tabPane"]/div/div[2]/div[2]/span',
            'storyReviwer'     => '//*[@zui-key="side"]/div/div[2]/div[2]/div/div[3]/div[2]//span',
            'storyEstimate'    => '//*[@id="zin_projectstory_view_tabPane"]/div/div[4]/div[2]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
