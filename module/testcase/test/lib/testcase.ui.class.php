<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 5) . '/test/lib/ui.php';
class testcase extends tester
{
    public function createTestCase($project = array(), $testcase = array())
    {
        $this->login();
        $form = $this->initForm('testcase', 'create',$project, 'appIframe-qa');
        if(isset($testcase['caseName']))   $form->dom->title->setValue($testcase['caseName']);
        if(isset($testcase['type']))       $form->dom->type->picker($testcase['type']);
        if(isset($testcase['stage']))      $form->dom->{'stage[]'}->multiPicker($testcase['stage']);
        if(isset($testcase['pri']))        $form->dom->pri->picker($testcase['pri']);
        if(isset($testcase['prediction'])) $form->dom->prediction->setValue($testcase['prediction']);
        if(isset($testcase['steps']))
        {
            $parentGroup = 0;
            foreach($testcase['steps'] as $parentSteps => $parentExpects)
            {
                $parentGroup++;
                if($parentGroup >= 3)
                {
                    $parentSibButton = "//textarea[@name = 'steps[$parentGroup]']/../..//button[@data-action='sib']/i";
                    $parentSibButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($parentSibButton));
                    $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$parentSibButtonXpath]);
                    $this->page->webdriver->driver->executeScript("arguments[0].click();", [$parentSibButtonXpath]);
                }
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
                    foreach($parentExpects as $steps => $expects)
                    {
                        $group++;
                        $this->webdriver->wait(1);
                        $sibButton = "//textarea[@name = 'steps[$parentGroup.$group]']/../..//button[@data-action='sib']/i";
                        $sibButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($sibButton));
                        if(!is_array($expects))
                        {
                            $form->dom->{"steps[$parentGroup.$group]"}->setValue($steps);
                            $form->dom->{"expects[$parentGroup.$group]"}->setValue($expects);
                            $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$sibButtonXpath]);
                            $this->page->webdriver->driver->executeScript("arguments[0].click();", [$sibButtonXpath]);
                        }
                        else
                        {
                            $sonGroup = 0;
                            $form->dom->{"steps[$parentGroup.$group]"}->setValue($steps);
                            $sonSubButton = "//textarea[@name = 'steps[$parentGroup.$group]']/../..//button[@data-action='sub']/i";
                            $sonSubButtonXpath = $this->page->webdriver->driver->findElement(WebDriverBy::xpath($sonSubButton));
                            $this->page->webdriver->driver->executeScript("arguments[0].scrollIntoView();", [$sonSubButtonXpath]);
                            $this->page->webdriver->driver->executeScript("arguments[0].click();", [$sonSubButtonXpath]);
                        }
                    }
                }
            }
        }
        $form->dom->saveButton->click();
        $this->webdriver->wait(1);

        $caseLists = $form->dom->caseName->getElementList($form->dom->page->xpath['caseNameList']);
        $caseList  = array_map(function($element){return $element->getText();}, $caseLists->element);
        if(in_array($testcase['caseName'], $caseList)) return $this->success('创建测试用例成功');
        return $this->failed('创建测试用例失败');
    }
}
