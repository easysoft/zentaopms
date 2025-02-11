<?php
class reportPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'selectAll' => "//button[@data-call='selectAll']",
            'clickInit' => "//button[@data-call='clickInit']"
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
