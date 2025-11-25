<?php
class tasksPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);

        $this->xpath = array(
            'id'         => "//div[@data-col='id' and @data-row!='HEADER']",
            'name'       => "//div[@data-col='name' and @data-row!='HEADER']",
            'pri'        => "//div[@data-col='pri' and @data-row!='HEADER']",
            'status'     => "//div[@data-col='status' and @data-row!='HEADER']",
            'assignedTo' => "//div[@data-col='assignedTo' and @data-row!='HEADER']",
            'estimate'   => "//div[@data-col='estimate' and @data-row!='HEADER']",
            'consumed'   => "//div[@data-col='consumed' and @data-row!='HEADER']",
            'left'       => "//div[@data-col='left' and @data-row!='HEADER']",
            'progress'   => "//div[@data-col='progress' and @data-row!='HEADER']",
        );

        $this->dom->xpath = array_merge($this->dom->xpath, $this->xpath);
    }
}
