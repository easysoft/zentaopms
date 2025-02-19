<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
            'saveBtn' => '//*[@id="zin_testsuite_edit_form"]/div[4]/div/button[1]'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
