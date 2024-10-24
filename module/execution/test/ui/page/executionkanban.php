<?php
class executionkanbanPage  extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'wait'    => "(//div[@class='kanban-header-title'])[1]/span[2]/div/span",
            'doing'   => "(//div[@class='kanban-header-title'])[2]/span[2]/div/span",
            'suspend' => "(//div[@class='kanban-header-title'])[3]/span[2]/div/span",
            'closed'  => "(//div[@class='kanban-header-title'])[4]/span[2]/div/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
