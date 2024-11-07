<?php
class todoPage extends page
{
    public function __construct($webdriver)
    {
        parent::__construct($webdriver);
        $xpath = array(
            'addTodo'    => '/html/body/div/div/div[1]/div[2]/div/a',
            'addTodoBtn' => '/html/body/div[2]/div/div/div[2]/div/div[2]/form/div[15]/div/button'
        );
        $this->dom->xpath = array_merge($this->dom->xpath, $xpath);
    }
}
