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
        $buttonCount = $this->page->webdriver->driver->findElements(WebDriverBy::xpath($newPage->dom->xpath['copyButton']));
        if($type == 'GitLab' || $type == 'SonarQube')
        {
            $form->dom->btn($this->lang->instance->management)->click();
        }
        else
        {
            if($this->instanceViewAssert($newPage, $buttonCount)) return $this->success($type . '详情页无误');
            return $this->failed($type . '详情页有误，请检查');
        }
    }

    public function instanceViewAssert($page, $buttonCount)
    {
        $toastList = [];
        foreach($buttonCount as $element)
        {
            $element->click();
            $this->webdriver->wait(1);
            $toast = $page->dom->toast->getText();
            if($toast == '复制成功') $toastList[] = $toast;
        }
        if(count($toastList) == count($buttonCount)) return true;
        return false;
    }
}
