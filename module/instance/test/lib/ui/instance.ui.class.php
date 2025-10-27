<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 6) . '/test/lib/ui.php';
class instance extends tester
{
    /**
     * 检查应用详情页。
     * instance view.
     *
     * @param  string  $project
     * @access public
     * @return object
     */
    public function instanceView(string $type)
    {
        $this->login();
        $form = $this->initForm('space', 'browse', '', 'appIframe-devops');
        $elementCount = $this->page->webdriver->driver->findElement(WebDriverBy::xpath("(//div[text()='$type']/..)[1]"))->getAttribute('data-row');
        $this->page->webdriver->driver->findElement(WebDriverBy::xpath("//div[@data-col='name' and @data-row=$elementCount]//a[text()]"))->click();
        $this->webdriver->wait(1);
        $newPage = $this->loadPage('instance', 'view');
        $buttonList = $this->page->webdriver->driver->findElements(WebDriverBy::xpath($newPage->dom->xpath['copyButton']));
        if($type == 'GitLab' || $type == 'SonarQube')
        {
            $form->dom->btn($this->lang->instance->management)->click();
            $this->webdriver->wait(1);
            $search = $form->dom->btn($this->lang->searchAB)->getText();
            if($search == '搜索') return $this->success($type . '详情页无误');
            return $this->failed($type . '详情页有误，请检查');
        }
        else
        {
            if($this->instanceViewAssert($newPage, $buttonList)) return $this->success($type . '详情页无误');
            return $this->failed($type . '详情页有误，请检查');
        }
    }

    /**
     * 应用详情页添加断言。
     * instance view assert.
     *
     * @param  object  $page
     * @param  array   $buttonCount
     * @access public
     * @return bool
     */
    public function instanceViewAssert(object $page, array $buttonList)
    {
        $toastList = [];
        foreach($buttonList as $element)
        {
            $element->click();
            $this->webdriver->wait(1);
            $toast = $page->dom->toast->getText();
            if($toast == '复制成功') $toastList[] = $toast;
        }
        if(count($toastList) == count($buttonList)) return true;
        return false;
    }
}
