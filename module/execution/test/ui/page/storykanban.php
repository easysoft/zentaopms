<?php
class storykanbanPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'kanbanBtn'  => "//*[@id='actionBar']/div[1]/a[2]/i",
            'projected'  => "//*[@id='kanban']/div/div[1]/div[2]/div[1]/div/div/span",
            'developing' => "//*[@id='kanban']/div/div[1]/div[2]/div[2]/div/div/span",
            'developed'  => "//*[@id='kanban']/div/div[1]/div[2]/div[3]/div/div/span",
            'testing'    => "//*[@id='kanban']/div/div[1]/div[2]/div[4]/div/div/span",
            'tested'     => "//*[@id='kanban']/div/div[1]/div[2]/div[5]/div/div/span",
            'accepted'   => "//*[@id='kanban']/div/div[1]/div[2]/div[6]/div/div/span",
            'released'   => "//*[@id='kanban']/div/div[1]/div[2]/div[7]/div/div/span"
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
