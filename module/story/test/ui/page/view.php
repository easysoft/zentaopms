<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'storyName'        => "//*[@id='mainContent']/div/div[1]/div[1]/span[2]",
            'storyFrom'        => "//*[@id='sourceBox']",
            'status'           => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[1]/div/div[6]/div[2]/span",
            'openedBy'         => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/div[2]",
            'assignToONE'      => "/html/body/div/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[2]/div[2]",
            'reviewer'         => "/html/body/div[1]/div/div/div[1]/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[3]/div[2]/div/span",
            'closeReason'      => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[6]/div[2]",
            'activate'         => "//*[@type='submit']",
            'assignToBtn'      => "//*[@type='submit']",
            'submitReviewSave' => "//*[@type='submit']",
            'subReviewerBtn'   => "//*[@id='reviewer']",
            'subReviewer'      => "/html/body/div[3]/div/menu/menu/li[2]/a/div",
            'firLinkStories'   => "//*[@id='mainContainer']/div/div/div[2]/div[2]/div[2]/div[2]/div/ul/li[2]/div/div",
            'secLinkStories'   => "//*[@id='mainContainer']/div/div/div[2]/div[2]/div[2]/div[2]/div/ul/li[4]/div/div",
            /*关联需求页面*/
            'searchBox'        => "//*[@type='submit']",
            'selectAll'        => "//*[@id='linkStories']/div[3]/div[1]/div"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
