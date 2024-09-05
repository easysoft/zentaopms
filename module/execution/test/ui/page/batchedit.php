<?php
class batchEditPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'firstCheckbox' = "(//*[@data-col='rawID'])[2]",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
