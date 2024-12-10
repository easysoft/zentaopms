<?php
class viewPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            /*需求详情页*/
            'status'           => "//*[@class='tab-content']/div/div/div[6]/div[2]/span",
            'closeReason'      => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[6]/div[2]",
            'childStoryName'   =>"//*[@id='table-story-children']/div[2]/div[1]/div/div[2]/div/a",
            'childStoryStatus' =>"//*[@id='table-story-children']/div[2]/div[2]/div/div[3]/div/span",
            'parentStoryName'  =>"//*[@id='zin_requirement_view_tabPane']/div/div[3]/div[2]/div/div/a",
            /*激活弹窗的激活按钮*/
            'activate'    => "//*[@type='submit']",
            /*关闭弹窗中的关闭按钮*/
            'closedButton'   => "//*[@id='zin_epic_close_form']/div[4]/div/button"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
