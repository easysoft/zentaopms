<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 5) . '/test/lib/ui.php';
class testcase extends tester
{
	/**
     * 添加测试用例
     * create testcase
     *
     * @param  array  $project
     * @param  array  $testcase
     * @access public
     * @return object
     */
    public function createTestCase($project = array(), $testcase = array())
    {
        $this->login();
        $form = $this->initForm('testcase', 'create', $project, 'appIframe-qa');
        if(isset($testcase['caseName']))   $form->dom->title->setValue($testcase['caseName']);
        if(isset($testcase['type']))       $form->dom->type->picker($testcase['type']);
        if(isset($testcase['stage']))      $form->dom->{'stage[]'}->multiPicker($testcase['stage']);
        if(isset($testcase['pri']))        $form->dom->pri->picker($testcase['pri']);
        if(isset($testcase['prediction'])) $form->dom->prediction->setValue($testcase['prediction']);
        if(isset($testcase['steps']))      $this->fillInCaseSteps($form, $testcase);
        $form->dom->saveButton->click();
        $this->webdriver->wait(2);

        $caseLists = $form->dom->getElementList($form->dom->xpath['caseNameList']);
        $caseList  = array_map(function($element){return $element->getText();}, $caseLists->element);
        if(in_array($testcase['caseName'], $caseList)) return $this->success('创建多层级测试用例成功');
        return $this->failed('创建多层级测试用例失败');
    }

	/**
     * 测试用例列表。
     * check testcase list.
     *
     * @param  array  $project
     * @access public
     * @return object
     */
    public function testcaseBrowse($project)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $project, 'appIframe-qa');
        if($form->dom->caseListID->getText()) return $this->success('测试用例列表验证成功');
        return $this->failed('测试用例列表验证失败');
    }

	/**
     * 添加测试用例步骤
     * fill in case steps
     *
     * @param  object  $form
     * @param  array   $testcase
     * @access public
     * @return void
     */
    public function fillInCaseSteps($form, $testcase)
    {
        $parentGroup = 0;
        /* 遍历$testcase['steps']数组，将该数组内的键值对，作为用例的步骤和预期 */
        foreach($testcase['steps'] as $parentSteps => $parentExpects)
        {
            $parentGroup++;
            /* 判断如果遍历次数>=3时，点击一下创建步骤按钮 */
            if($parentGroup >= 3)
            {
                $parentSibButton = "//textarea[@name = 'steps[$parentGroup]']/../..//button[@data-action='sib']/i";
                $parentSibButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($parentSibButton));
                $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$parentSibButtonXpath]);
                $this->page->webdriver->driver->executeScript("arguments[0].click();", [$parentSibButtonXpath]);
            }
            /* 如果$parentExpects不是数组，则视将该键值对视为一级步骤预期 */
            if(!is_array($parentExpects))
            {
                $form->dom->{"steps[$parentGroup]"}->setValue($parentSteps);
                $form->dom->{"expects[$parentGroup]"}->setValue($parentExpects);
            }
            else
            {
                $group = 0;
                $form->dom->{"steps[$parentGroup]"}->setValue($parentSteps);
                $subButton = "//textarea[@name = 'steps[$parentGroup]']/../..//button[@data-action='sub']/i";
                $subButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($subButton));
                $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$subButtonXpath]);
                $this->page->webdriver->driver->executeScript("arguments[0].click();", [$subButtonXpath]);
                /* 如果$parentExpexts是数组，则继续遍历该数组，并将遍历的键值对作为二级预期步骤 */
                foreach($parentExpects as $steps => $expects)
                {
                    $group++;
                    $this->webdriver->wait(1);
                    $sibButton = "//textarea[@name = 'steps[$parentGroup.$group]']/../..//button[@data-action='sib']/i";
                    $sibButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($sibButton));
                    $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$sibButtonXpath]);
                    $this->page->webdriver->driver->executeScript("arguments[0].click();", [$sibButtonXpath]);
                    /* 如果$expects不是数组，则视将该键值对视为二级步骤预期 */
                    if(!is_array($expects))
                    {
                        $form->dom->{"steps[$parentGroup.$group]"}->setValue($steps);
                        $form->dom->{"expects[$parentGroup.$group]"}->setValue($expects);
                    }
                    else
                    {
                        $sonGroup = 0;
                        $form->dom->{"steps[$parentGroup.$group]"}->setValue($steps);
                        $subButton = "//textarea[@name = 'steps[$parentGroup.$group]']/../..//button[@data-action='sub']/i";
                        $subButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($subButton));
                        $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$subButtonXpath]);
                        $this->page->webdriver->driver->executeScript("arguments[0].click();", [$subButtonXpath]);
                        /* 如果$expexts是数组，则继续遍历该数组，并将遍历的键值对作为三级预期步骤 */
                        foreach($expects as $sonSteps => $sonExpects)
                        {
                            $sonGroup++;
                            $sonSibButton = "//textarea[@name = 'steps[$parentGroup.$group.$sonGroup]']/../..//button[@data-action='sib']/i";
                            $sonSibButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($sonSibButton));
                            $form->dom->{"steps[$parentGroup.$group.$sonGroup]"}->setValue($sonSteps);
                            $form->dom->{"expects[$parentGroup.$group.$sonGroup]"}->setValue($sonExpects);
                            $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView()", [$sonSibButtonXpath]);
                            $this->page->webdriver->driver->executeScript("arguments[0].click()", [$sonSibButtonXpath]);
                            $this->webdriver->wait(1);
                        }
                    }
                }
            }
        }
    }
}
