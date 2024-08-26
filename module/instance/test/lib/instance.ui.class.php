<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 5) . '/test/lib/ui.php';
class instance extends tester
{
    public function instanceView($type)
    {
        $this->login();
        $this->initForm('space', 'browse', '', 'appIframe-devops');
        $xpathNum = $this->page->webdriver->driver->findElement(WebDriverBy::xpath("(//div[text()='$type']/..)[1]"))->getAttribute('data-row');
        $this->page->webdriver->driver->findElement(WebDriverBy::xpath("//div[@data-col='name' and @data-row=$xpathNum]//a[text()]"))->click();
        $this->webdriver->wait(1);
    }
}
