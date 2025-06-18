<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'realname'      => "//*[@id='realname_0']",
            'passwordfield' => "//*[@id='passwordfield_0']",
            'savebtn'       => "//*[@id='zin_user_batchedit_formBatch']/div[3]/button[1]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
