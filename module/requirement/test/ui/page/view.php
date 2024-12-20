<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'storyName'       => "//*[@id='mainContent']/div/div/div/span[2]",
            'status'          => "//*[@id='zin_requirement_view_tabPane']/div/div[6]/div[2]/span",
            'parentStoryName' => "//*[@id='zin_requirement_view_tabPane']/div/div[3]/div[2]/div/div/a",
            /*激活弹窗激活按钮*/
            'activate'    => "//*[@type='submit']"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
