<?php
class maintainPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'fieldList' => '//*[@id="table-repo-maintain"]/div[1]//*[text()]'
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
