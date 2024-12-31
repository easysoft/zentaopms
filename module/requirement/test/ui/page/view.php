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
            'closeReason'     => "/html/body/div[1]/div/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[6]/div[2]",
            /*激活弹窗激活按钮*/
            'activate'    => "//*[@type='submit']",
            /*关闭弹窗中的关闭按钮*/
            'closedButton'   => "//*[@id='zin_requirement_close_form']/div[4]/div/button"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
