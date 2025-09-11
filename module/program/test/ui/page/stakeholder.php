<?php
class stakeholderPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'unlinkFirBtn'   => "//*[@id='table-program-stakeholder']/div[2]/div[3]/div/div[1]/div/nav/a/i",
            'stakeholderNum' => "//*[@id='table-program-stakeholder']/div[3]/div[2]/strong",
            'selectAllBtn'   => "//*[@id='table-program-stakeholder']/div[1]/div[1]/div/div[1]/div/div/label",
            'batchUnlinkBtn' => "//*[@id='table-program-stakeholder']/div[3]/nav[1]/button",
            'title'          => "//*[@id='table-program-stakeholder']/div[2]/div[1]/div/div[2]/div",
            'type'           => "//*[@id='table-program-stakeholder']/div[2]/div[2]/div/div[1]/div",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
