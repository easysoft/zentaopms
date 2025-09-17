<?php
use Facebook\WebDriver\WebDriverBy;

include dirname(__FILE__, 6) . '/test/lib/ui.php';
class testcase extends tester
{
	/**
     * 添加测试用例
     * create testcase
     *
     * @param  array  $product
     * @param  array  $testcase
     * @access public
     * @return object
     */
    public function createTestCase($product = array(), $testcase = array())
    {
        $this->login();
        $form = $this->initForm('testcase', 'create', $product, 'appIframe-qa');
        if(isset($testcase['caseName']))   $form->dom->title->setValue($testcase['caseName']);
        if(isset($testcase['type']))       $form->dom->type->picker($testcase['type']);
        if(isset($testcase['stage']))      $form->dom->{'stage[]'}->multiPicker($testcase['stage']);
        if(isset($testcase['pri']))        $form->dom->pri->picker($testcase['pri']);
        if(isset($testcase['prediction'])) $form->dom->prediction->setValue($testcase['prediction']);
        if(isset($testcase['steps']))      $this->fillInCaseSteps($form, $testcase);
        $form->dom->saveButton->click();
        $this->webdriver->wait(10);

        $caseLists = $form->dom->getElementList($form->dom->xpath['caseNameList']);
        $caseList  = array_map(function($element){return $element->getText();}, $caseLists->element);
        if(in_array($testcase['caseName'], $caseList)) return $this->success('创建多层级测试用例成功');
        return $this->failed('创建多层级测试用例失败');
    }

	/**
     * 测试用例列表。
     * check testcase list.
     *
     * @param  array  $product
     * @access public
     * @return object
     */
    public function testcaseBrowse($product)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $product, 'appIframe-qa');
        if($form->dom->caseListID->getText()) return $this->success('测试用例列表验证成功');
        return $this->failed('测试用例列表验证失败');
    }

	/**
     * 编辑测试用例。
     * edit testcase.
     *
     * @param  array  $url
     * @param  array  $testcase
     * @access public
     * @return object
     */
    public function editTestCase($url, $testcase)
    {
        $this->login();
        $form = $this->initForm('testcase', 'edit', $url, 'appIframe-qa');
        if(isset($testcase['caseName']))   $form->dom->title->setValue($testcase['caseName']);
        if(isset($testcase['type']))       $form->dom->type->picker($testcase['type']);
        if(isset($testcase['stage']))      $form->dom->{'stage[]'}->multiPicker($testcase['stage']);
        if(isset($testcase['pri']))        $form->dom->pri->picker($testcase['pri']);
        if(isset($testcase['prediction'])) $form->dom->prediction->setValue($testcase['prediction']);
        if(isset($testcase['steps']))      $this->fillInCaseSteps($form, $testcase);
        $form->dom->saveButton->click();
        $this->webdriver->wait(3);
        if($form->dom->caseName->getText() == $testcase['caseName']) return $this->success('编辑测试用例成功');
        return $this->failed('编辑测试用例失败');
    }

	/**
     * 测试用例详情。
     * testcase view.
     *
     * @param  array  $product
     * @access public
     * @return object
     */
    public function testcaseView($product)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $product, 'appIframe-qa');
        $caseName = $form->dom->caseName->getText();
        $form->dom->caseName->click();
        $this->webdriver->wait(1);
        if($this->response('method') == 'view' && $form->dom->caseNameView->getText() == $caseName) return $this->success('测试用例详情页验证成功');
        return $this->failed('测试用例详情页验证失败');
    }

	/**
     * 批量创建测试用例。
     * batch create testcase.
     *
     * @param  array  $product
     * @access public
     * @return object
     */
    public function batchCreate($product, $testcase)
    {
        $this->login();
        $form = $this->initForm('testcase', 'batchCreate', $product, 'appIframe-qa');
        if(isset($testcase['caseName']))
        {
            $count = 0;
            foreach($testcase['caseName'] as $caseName)
            {
                $count++;
                $form->dom->{"title[$count]"}->setValue($caseName);
            }
        }
        $form->dom->saveButton->click();
        $this->webdriver->wait(2);

        $caseList = array_map(function($element){return $element->getText();}, $form->dom->getElementList($form->dom->xpath['caseNameList'])->element);
        if(!in_array($testcase['caseName'], $caseList)) return $this->success('批量创建测试用例成功');
        return $this->failed('批量创建测试用例成功');
    }

	/**
     * 检查测试用例报表。
     * check testcase review.
     *
     * @param  array  $config
     * @param  array  $product
     * @param  array  $testcase
     * @access public
     * @return object
     */
    public function testcaseReview($config, $product, $testcase)
    {
        /* open testcase review switch */
        $this->login();
        $form = $this->initForm('custom', 'set', $config, 'appIframe-admin');
        $form->dom->openReview->click();
        $form->dom->btn($this->lang->save)->click();

        /* review testcase */
        $form = $this->initForm('testcase', 'browse', $product, 'appIframe-qa');
        $form->dom->review->click();
        $this->webdriver->wait(1);
        if(isset($testcase['reviewedDate'])) $form->dom->reviewedDate->datePicker($testcase['reviewedDate']);
        if(isset($testcase['result']))       $form->dom->result->picker($testcase['result']);
        if(isset($testcase['reviewedBy']))   $form->dom->{'reviewedBy[]'}->multiPicker($testcase['reviewedBy']);
        if(isset($testcase['comment']))      $form->dom->comment->setValueInZenEditor($testcase['comment']);
        $form->dom->needReview->click();
        $this->webdriver->wait(1);
        $assertion = $form->dom->review->attr('href');

        /* close testcase review switch */
        $form = $this->initForm('custom', 'set', $config, 'appIframe-admin');
        $form->dom->closeReview->click();
        $this->webdriver->wait(1);
        $form->dom->btn($this->lang->save)->click();
        $this->webdriver->wait(1);
        if(is_object($form->dom->confirm)) $form->dom->confirm->click();
        $this->webdriver->wait(1);

        if($assertion) return $this->success('测试用例评审通过');
        return $this->failed('测试用例评审失败');
    }

	/**
     * 检查批量编辑测试用例。
     * check testcase review.
     *
     * @param  array  $url
     * @param  array  $testcases
     * @access public
     * @return object
     */
    public function batchEditTestcase($url, $testcases)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $url, 'appIframe-qa');
        $form->dom->caseAllLabel->click();
        $this->webdriver->wait(2);
        $form->dom->batchEdit->click();
        $counter = 0;
        if(!empty($testcases))
        {
            foreach($testcases as $testcase)
            {
                $counter++;
                if(isset($testcase['pri'])) $form->dom->{"pri[$counter]"}->picker($testcase['pri']);
                if(isset($testcase['status'])) $form->dom->{"status[$counter]"}->picker($testcase['status']);
                if(isset($testcase['title'])) $form->dom->{"title[$counter]"}->setValue($testcase['title']);
                if(isset($testcase['type'])) $form->dom->{"type[$counter]"}->picker($testcase['type']);
                if(isset($testcase['steps'])) $form->dom->{"steps[$counter]"}->setValue($testcase['steps']);
                if(isset($testcase['expects'])) $form->dom->{"expects[$counter]"}->setValue($testcase['expects']);
                if(isset($testcase['stage'])) $form->dom->{"stage[$counter]"}->multiPicker($testcase['stage']);
            }
        }
        $form->dom->save->click();
        $this->webdriver->wait(2);
        if($this->response('method') == 'browse') return $this->success('批量编辑测试用例成功');
        return $this->failed('批量编辑测试用例失败');
    }

	/**
     * 检查导出测试用例。
     * check testcase review.
     *
     * @param  array  $url
     * @param  array  $testcases
     * @access public
     * @return object
     */
    public function exportTestcase($url, $testcases)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $url, 'appIframe-qa');
        $form->dom->exportMenu->click();
        $this->webdriver->wait(1);
        $form->dom->exportCaseButton->click();
        if(isset($testcases['fileName'])) $form->dom->fileName->setValue($testcases['fileName']);
        if(isset($testcases['fileType'])) $form->dom->fileType->picker($testcases['fileType']);
        if(isset($testcases['encode'])) $form->dom->encode->picker($testcases['encode']);
        if(isset($testcases['exportType'])) $form->dom->exportType->picker($testcases['exportType']);
        $form->dom->btn($this->lang->export)->click();
        if($this->response('method') == 'browse') return $this->success('导出测试用例成功');
        return $this->failed('导出测试用例失败');
    }

	/**
     * 检查零用例需求列表。
     * check zerocase list.
     *
     * @param  array  $url
     * @access public
     * @return object
     */
    public function zeroCase($url)
    {
        $this->login();
        $form = $this->initForm('testcase', 'zeroCase', $url, 'appIframe-qa');
        if($this->response('method') == 'zeroCase') return $this->success('验证零用例需求列表成功');
        return $this->failed('验证零用例需求列表失败');
    }

	/**
     * 检查自动化测试设置。
     * check zerocase list.
     *
     * @param  array  $url
     * @param  array  $automation
     * @access public
     * @return object
     */
    public function automation($url, $automation)
    {
        $this->login();
        $form = $this->initForm('testcase', 'browse', $url, 'appIframe-qa');
        $form->dom->automation->click();
        if(isset($automation['node'])) $form->dom->node->picker($automation['node']);
        if(isset($automation['scriptPath'])) $form->dom->scriptPath->setValue($automation['scriptPath']);
        if(isset($automation['shell'])) $form->dom->shell->setValue($automation['shell']);
        $form->dom->btn($this->lang->save)->click();
        if($this->response('method') == 'browse') return $this->success('验证自动化测试设置成功');
        return $this->failed('验证自动化测试设置失败');
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
