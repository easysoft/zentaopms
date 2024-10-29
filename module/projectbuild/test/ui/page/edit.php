<?php
class editPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $xpath = array(
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
