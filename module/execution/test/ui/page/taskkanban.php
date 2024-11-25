<?php
class taskkanbanPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'namePicker'  => "//input[@name='type']/..",
            'groupPicker' => "//input[@name='group']/..",
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
