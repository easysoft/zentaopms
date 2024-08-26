<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 5) . '/test/lib/ui.php';
class instance extends tester
{
    public function instanceView($type)
    {
        $this->login();
        $form = $this->initForm('space', 'browse', '', 'appIframe-devops');
        $elementCount = $this->page->webdriver->driver->findElement(WebDriverBy::xpath("(//div[text()='$type']/..)[1]"))->getAttribute('data-row');
        $this->page->webdriver->driver->findElement(WebDriverBy::xpath("//div[@data-col='name' and @data-row=$elementCount]//a[text()]"))->click();
        $this->webdriver->wait(1);
        $newPage = $this->loadPage('instance', 'view');
        if($type == 'GitLab' || $type == 'SonarQube')
        {
            $form->dom->btn($this->lang->instance->management)->click();
        }
        elseif($type == 'GitFox')
        {
            $newPage->dom->tokenCopy->click();
        }
        else
        {
            $newPage->dom->passwdCopy->click();
            $this->webdriver->wait(1);
            $newPage->dom->tokenCopy->click();
        }
        $this->webdriver->wait(1);
    }
}
