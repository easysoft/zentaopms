<?php
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverSelect;
use Facebook\WebDriver\WebDriverTargetLocator;
use Facebook\WebDriver\WebDriverDimension;

require_once('vendor/autoload.php');

/**
 * Webdriver engine class.
 *
 * @copyright Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author    zhouxin
 * @package
 * @license   LGPL
 * @version   $Id$
 * @Link      http://www.zentao.net
 */
class webdriver
{
    public $driver;

    public $element;

    public $config;

    public $stepNO = 1;

    public $cookieFile;

    public $results;

    public $errors = array();

    protected $exceptions = array();

    public function __construct($config)
    {
        global $results;
        $this->results = $results;
        $this->config  = $config;;

        $this->initBrowser($config);
        $this->cookieFile = dirname(__FILE__, 4) . '/config/cookie/cookie';
    }

    /**
     * If exception to close browser.
     *
     * @param  Throwable $exception
     * @access public
     * @return mixed
     */
    public function resetException(Throwable $exception)
    {
        echo "Exception: " , $exception->getMessage();
        $this->closeBrowser();
    }

    /**
     * Init webdriver.
     *
     * @param  object    $browser
     * @access public
     * @return void
     */
    public function initBrowser($browser)
    {
        /* If $argv[1] is post, use it as driver host. */
        if(isset($GLOBALS['argv'][1]) and $GLOBALS['argv'][1] != '' and preg_match('/^http(s)?/', $GLOBALS['argv'][1])) $browser->host = $GLOBALS['argv'][1];
        $capabilities = DesiredCapabilities::chrome();

        $options = new ChromeOptions();
        $options->addArguments($browser->options);
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create($browser->host, $capabilities);
    }

    /**
     * Open url.
     *
     * @param  string  $url
     * @access public
     * @return void
     */
    public function get($url)
    {
        $url = trim($url);
        if(substr($this->config->webRoot, -1) !== '/') $this->config->webRoot .= '/';
        if(!preg_match('/^http:|^https:/', $url)) $url = $this->config->webRoot . $url;
        $this->driver->get($url);
        $this->setLang();
    }

    /**
     * Set browser language.
     *
     * @access public
     * @return object
     */
    public function setLang()
    {
        global $lang;

        $langName = $this->driver->manage()->getCookieNamed('lang')->getValue();
        if(substr($langName, 0, 2) == 'zh')
        {
            $langName = 'zh-cn';
        }
        elseif($langName)
        {
            $langName = 'en';
        }

        $langFile = dirname(__FILE__, 4) . "/lang/{$langName}.php";

        if(file_exists($langFile)) include $langFile;

        return $lang;
    }

    /**
     * Set browser window size.
     *
     * @param  int    $width
     * @param  int    $height
     * @access public
     * @return mixed
     */
    public function setWindowSize($width, $height)
    {
        $this->driver->manage()->window()->setSize(new WebDriverDimension($width, $height));
    }

    /**
     * Get cookies.
     *
     * @param  string $cookieFile
     * @access public
     * @return array
     */
    public function getCookie($cookieFile = '')
    {
        if(!$cookieFile) $cookieFile = $this->cookieFile;

        $cookies   = $this->driver->manage()->getCookies();
        $cookieArr = array();
        foreach($cookies as $cookie)
        {
            $cookieArr[] = $cookie->toArray();
        }
        $isSave = @file_put_contents($cookieFile, json_encode($cookieArr));

        if($isSave)
        {
            return $cookies;
        }
        else
        {
            echo "cookie获取失败！" . PHP_EOL;
            return false;
        }
    }

    /**
     * Delete cookies in page.
     *
     * @access public
     * @return void
     */
    public function deleteCookie()
    {
        return $this->driver->manage()->deleteAllCookies();
    }

    /**
     * Switch to Url.
     *
     * @param  string $url
     * @param  bool   $loginFlag
     * @param  string $cookieFile
     * @access public
     * @return void
     */
    public function switchToUrl($url, $loginFlag = false, $cookieFile = '')
    {
        $url = trim($url);
        if(substr($this->config->webRoot, -1) !== '/') $this->config->webRoot .= '/';
        if(!preg_match('/^http:|^https:/', $url)) $url = $this->config->webRoot . $url;

        if($loginFlag == true)
        {
            if(!$cookieFile) $cookieFile = $this->cookieFile;
            $cookies = json_decode(file_get_contents($cookieFile), true);
        }
        else
        {
            sleep(2);
            $cookies = $this->driver->manage()->getCookies();
        }
        $this->driver->get($this->config->webRoot);
        $this->driver->manage()->deleteAllCookies();
        foreach($cookies as $cookie)
        {
            if($cookie["name"] == "zentaosid")  $this->driver->manage()->addCookie($cookie);
            if($cookie["name"] == "quchengsid") $this->driver->manage()->addCookie($cookie);
        }
        $this->driver->get($url);
    }

    /**
     * Find elements.
     *
     * @param  string $selector  format: [type]:selector,for example: 'xpath://*[@id="account"]' 'id:heading' 'tag:a' '//*[@id="account"]'
     * @access public
     * @return void
     */
    public function getElementList($selector = '')
    {
        if(!$selector) return $this->element;

        if(preg_match('/^(xpath:|css:|id:|class:|name:|tag:|link:).*/i', $selector, $matches) !== 0)
        {
            $type = str_replace(':', '', $matches[1]);
            switch($type)
            {
                case 'xpath':
                    $this->element = $this->driver->findElements(WebDriverBy::xpath($selector));
                    break;
                case 'css':
                    $this->element = $this->driver->findElements(WebDriverBy::cssSelector($selector));
                    break;
                case 'id':
                    $this->element = $this->driver->findElements(WebDriverBy::id($selector));
                    break;
                case 'class':
                    $this->element = $this->driver->findElements(WebDriverBy::className($selector));
                    break;
                case 'name':
                    $this->element = $this->driver->findElements(WebDriverBy::name($selector));
                    break;
                case 'tag':
                    $this->element = $this->driver->findElements(WebDriverBy::tagName($selector));
                    break;
                case 'link':
                    $this->element = $this->driver->findElements(WebDriverBy::linkText($selector));
                    break;
            }
        }
        else
        {
            $this->element = $this->driver->findElements(WebDriverBy::xpath($selector));
        }

        return $this->element;
    }

    /**
     * Find element.
     *
     * @param  string $selector  format: [type]:selector,for example: 'xpath://*[@id="account"]' 'id:heading' 'tag:a' '//*[@id="account"]'
     * @access public
     * @return object $element
     */
    public function getElement($selector = '')
    {
        if(!$selector) return $this->element;

        if(preg_match('/^(xpath:|css:|id:|class:|name:|tag:|link:).*/i', $selector, $matches) !== 0)
        {
            $type     = str_replace(':', '', $matches[1]);
            $selector = str_replace("$type:", '', $selector);
            switch(strtolower($type))
            {
                case 'xpath':
                    $this->element = $this->driver->findElement(WebDriverBy::xpath($selector));
                    break;
                case 'css':
                    $this->element = $this->driver->findElement(WebDriverBy::cssSelector($selector));
                    break;
                case 'id':
                    $this->element = $this->driver->findElement(WebDriverBy::id($selector));
                    break;
                case 'class':
                    $this->element = $this->driver->findElement(WebDriverBy::className($selector));
                    break;
                case 'name':
                    $this->element = $this->driver->findElement(WebDriverBy::name($selector));
                    break;
                case 'tag':
                    $this->element = $this->driver->findElement(WebDriverBy::tagName($selector));
                    break;
                case 'link':
                    $this->element = $this->driver->findElement(WebDriverBy::linkText($selector));
                    break;
            }
        }
        else
        {
            $this->element = $this->driver->findElement(WebDriverBy::xpath($selector));
        }

        return $this->element;
    }

    /**
     * Take screenshot.
     *
     * @param  string $image
     * @param  bool   $saveReport
     * @access public
     * @return void
     */
    public function capture($image = '', $saveReport = true)
    {
        $image = $image ? $image : $this->config->captureRoot;

        if(empty($this->element))
        {
            $this->driver->takeScreenshot($image);
        }
        else
        {
            $this->element->takeElementScreenshot($image);
        }

        $imageUrl = str_replace($this->config->captureRoot, $this->config->captureWebRoot, $image);
        if($saveReport)
        {
            $this->saveImage($imageUrl);
        }
        else
        {
            return $imageUrl;
        }
    }

    /**
     * Switch iframe.
     *
     * @param  mixed  $id  Can pass iframe's id or identifier, if pass nothing, then switch to default content.
     * @access public
     * @return void
     */
    public function switchTo($id = '')
    {
        if($id === '')
        {
            $this->driver->switchTo()->defaultContent();
        }
        elseif(is_numeric($id))
        {
            $this->driver->switchTo()->frame($id);
        }
        else
        {
            $frame = $this->driver->findElement(WebDriverBy::id($id));
            $this->driver->switchTo()->frame($frame);
        }

        return $this->driver;
    }

    /**
     * Switch To window or tab.
     *
     * @param  string    $handler
     * @access public
     * @return mixed
     */
    public function switchToWindow($handler)
    {
        $this->driver->switchTo()->window($handler);

        return $this->driver;
    }

    /**
     * Get value of one element.
     *
     * @access public
     * @return string|bool
     */
    public function getValue()
    {
        return $this->element->getAttribute('value');
    }

    /**
     * Set value of one element.
     *
     * @param  string $value
     * @access public
     * @return string|bool
     */
    public function setValue($value)
    {
        try
        {
            $this->element->clear();
            $this->element->sendKeys($value);
        }
        catch(Exception $e)
        {
            $this->driver->executeScript("arguments[0].defaultValue='{$value}';", array($this->element));
        }
        return $this->element;
    }

    /**
     * Set value of one element by xpath.
     *
     * @param  string $xpath
     * @param  string $value
     * @access public
     * @return void
     */
    public function setValueByXpath($xpath, $value)
    {
        $element = $this->driver->findElement(WebDriverBy::xpath($xpath));
        $element->clear();
        $element->sendKeys($value);

        return $element;
    }

    /**
     * Get attribute of one element.
     *
     * @param  string  $attribute
     * @param  string  $mode  xpath|css|id|class|name|tag|link
     * @access public
     * @return string
     */
    public function attr($attribute)
    {
        return $this->element->getAttribute($attribute);
    }

    /**
     * Get text of one element.
     *
     * @access public
     * @return string
     */
    public function getText()
    {
        $text = $this->element->getText();
        return $text;
    }

    /**
     * Find element and click it.
     *
     * @access public
     * @return object
     */
    public function click()
    {
        try
        {
            $this->element->click();
        }
        catch(Exception $e)
        {
            $this->driver->executeScript("arguments[0].click();", array($this->element));
        }

        return $this->element;
    }

    /**
     * click by xpath.
     *
     * @param  string  $xpath
     * @access public
     * @return object
     */
    public function clickByXpath($xpath)
    {
        $element = $this->driver->findElement(WebDriverBy::xpath($xpath));
        $element->click();
        return $element;
    }

    /**
     * Click By Href in a tag.
     *
     * @access public
     * @return void
     */
    public function clickByHref()
    {
        $link = $this->attr('href');
        $this->get($link);
    }

    /**
     * Click by mouse.
     *
     * @param  bool   $isDouble
     * @access public
     * @return array
     */
    public function clickByMouse($isDouble = false)
    {
        $action = new WebDriverActions($this->driver);
        if($isDouble)
        {
            $action->moveToElement($this->element)->click()->perform();
        }
        else
        {
            $action->moveToElement($this->element)->doubleClick()->perform();
        }

        return $this->element;
    }

    /**
     * clear value for element.
     *
     * @access public
     * @return object
     */
    public function clear()
    {
        $this->element->clear();
        return $this->element;
    }

    /**
     * hover an element, Some class like .dropdown-hover can be hovered.
     *
     * @access public
     * @return void
     */
    public function hover()
    {
        $coordinates = $this->element->getCoordinates();
        $this->driver->getMouse()->mouseMove($coordinates);
    }

    /**
     * Assert function.
     *
     * @param  string    $mode
     * @param  mixed     $expect
     * @param  string    $step
     * @access public
     * @return void
     */
    public function assert($mode, $expect, $step = '', $isClose = false)
    {
        if($step == '') $step = "Step " . $this->stepNO;
        $this->stepNO ++;
        if($mode == 'text')  return $this->assertText($expect, $step, $isClose);
        if($mode == 'exist') return $this->assertExist($expect, $step, $isClose);
    }

    /**
     * assert by text of one selector.
     *
     * @param  string    $expect
     * @param  string    $step
     * @access public
     * @return mixed
     */
    public function assertText($expect, $step, $isClose = false)
    {
        if($expect == $this->getText())
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'PASS'</h4>");
            $this->results->setResult("$step : PASS!");
            if($isClose) $this->closeBrowser();
            return true;
        }
        else
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'FAIL'</h4>");
            $this->capture();
            $this->results->setResult("$step : FAIL!\n");
            if($isClose) $this->closeBrowser();
            throw new Exception("$step : Assert Fail");
        }

    }

    /**
     * Assert exist of selector.
     *
     * @param  bool    $expect
     * @param  string  $step
     * @access public
     * @return mixed
     */
    public function assertExist($expect, $step, $isDie = false)
    {
        $isExist = !empty($this->element);
        if($isExist === $expect)
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'PASS'</h4>");
            $this->results->setResult("$step : PASS!");
            return true;
        }
        else
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'FAIL'</h4>");
            $this->capture();
            $this->results->setResult("$step : FAIL!\n");
            $this->closeBrowser($isDie);
            throw new Exception("$step : Assert Fail");
        }
    }

    /**
     * Assert attribute's value of selector.
     *
     * @param  string  $attribute
     * @param  string  $expect
     * @param  string  $step
     * @access public
     * @return void|true
     */
    public function assertAttr($attribute, $expect, $step, $isDie = false)
    {
        if($step == '') $step = "Step " . $this->stepNO;
        $this->stepNO ++;

        if(strpos($this->attr($attribute), $expect) !== false)
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'PASS'</h4>");
            $this->results->setResult("$step : PASS!");
            return true;
        }
        else
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'FAIL'</h4>");
            $this->capture();
			$this->results->setResult("$step : FAIL!\n");
            $this->closeBrowser($isDie);
            throw new Exception("$step : Assert Fail");
        }
    }

    /**
     * Assert modal text.
     *
     * @param  string $expect
     * @param  string $step
     * @param  int    $isDie
     * @access public
     * @return void
     */
    public function assertModal($expect, $step = '', $isDie = false)
    {
        if($step == '') $step = "Step " . $this->stepNO;
        $this->stepNO ++;

        $message = $this->modal('text');

        if($expect == $message)
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'PASS'</h4>");
            $this->results->setResult("$step : PASS!");
            return true;
        }
        else
        {
            $this->results->saveReport("<h4>$step . ' : ' . 'FAIL'</h4>");
            $this->capture();
            $this->results->setResult("$step : FAIL!\n");
            $this->closeBrowser($isDie);
            throw new Exception("$step : Assert Fail");
        }
    }

    /**
     * Print Errors of current page.
     *
     * @param  mixed  $switchToIframe  If pass this param, driver will skip to specified iframe.
     * @access public
     * @return array
     */
    public function getErrors($switchToIframe = '')
    {
        $this->driver->switchTo()->defaultContent();
        $alerts = $this->driver->findElements(WebDriverBy::cssSelector('pre.alert'));
        foreach($alerts as $alert)
        {
            $alertInput      = $alert->findElement(WebDriverBy::tagName('input'))->getAttribute('value');
            $this->results->errors[]  = $alert->getText() . $alertInput;
        }
        $this->getErrorsInZinBar();

        $hasException = false;
        for($identifier = 0; $identifier < 10, $hasException == false; $identifier++)
        {
            try{
                $this->switchTo($identifier);
            }
            catch(Exception $e)
            {
                $hasException = true;
            }

            if($hasException == false)
            {
                $alerts = $this->driver->findElements(WebDriverBy::cssSelector('pre.alert'));
                foreach($alerts as $alert)
                {
                    $alertInput      = $alert->findElement(WebDriverBy::tagName('input'))->getAttribute('value');
                    $this->results->errors[]  = $alert->getText() . $alertInput;
                }
                $this->getErrorsInZinBar();
            }
        }

        $this->driver->switchTo()->defaultContent();
        if(!empty($switchToIframe)) $this->switchTo($switchToIframe);

        if(empty($this->results->errors)) return true;

        $this->saveErrorsToReport($this->results->errors);
        return $this;
    }

    /**
     * Get errors in zinbar.
     *
     * @access public
     * @return void
     */
    public function getErrorsInZinBar()
    {
        try
        {
            $this->getElement('//*[@id="zinbar"]/div/div[@data-hint="PHP errors"]');
            $parentDiv = $this->getElement('//*[@id="zinbar"]/div/div[3]');
            $errorDivs = $parentDiv->findElements(WebDriverBy::tagName('div'));
            foreach($errorDivs as $errorDiv)
            {
                $errorInfo = $errorDiv->findElement(WebDriverBy::xpath('div[1]'))->getText();
                $errorLine = $errorDiv->findElement(WebDriverBy::xpath('div[2]/strong'))->getText();
                $errorFile = $errorDiv->findElement(WebDriverBy::xpath('div[2]/span'))->getText();
                $this->results->errors[] = "Error: $errorInfo\nLine: $errorLine $errorFile";
            }
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    /**
     * Save image to report.
     *
     * @param  int    $src
     * @access public
     * @return void
     */
    public function saveImage($src)
    {
        $img = "<img style='max-width:100%;' src='{$src}' />";
        $this->results->saveReport($img);
    }

    /**
     * Print errors to report.
     *
     * @param  array  $errors
     * @access public
     * @return void
     */
    public function saveErrorsToReport($errors)
    {
        $reportType = $this->config->reportType;

        $title = $this->getTitle();
        $url   = $this->getUrl();
        $errorTitle = $reportType == 'html' ? "<h2>Errors in: [{$title}]($url)</h2>" : "## Errors in: [{$title}]($url)";
        $this->results->saveReport($errorTitle);

        $reportType == 'html' ? $this->results->saveReport('<pre>') : $this->results->saveReport('```');
        foreach($errors as $error) $this->results->saveReport($error);
        $reportType == 'html' ? $this->results->saveReport('</pre>') : $this->results->saveReport('```');
    }

    /**
     * Wait and retry until condition occured.
     *
     * @param  string  $condition  format: [title|url]:xxx for example: title:地盘 url:a.com
     * @param  int     $seconds    Wait at most seconds
     * @param  int     $interval   Retry every interval
     * @access public
     * @return object
     */
    public function waitUntil($condition, $seconds = 5, $interval = 500)
    {
        if(preg_match('/^(title:|url:).*/', $condition, $matches) !== 0)
        {
            $type      = str_replace(':', '', $matches[1]);
            $condition = str_replace("$type:", '', $condition);

            switch($type)
            {
                case 'title':
                    $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::titleMatches("/$condition/"));
                    break;
                case 'url':
                    $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::urlContains("$condition"));
                    break;
            }
        }
        else
        {
            $this->driver->wait($seconds, $interval)->until(WebDriverExpectedCondition::titleMatches("/$condition/"));
        }

        return $this->element;
    }

    /**
     * Wait an element appear, or implicit wait.
     *
     * @param  int    $seconds
     * @access public
     * @return void
     */
    public function waitElement($selector, $seconds = 5, $type = 'normal')
    {
        if($type == 'implicit')
        {
            $this->driver->manage()->timeouts()->implicitlyWait($seconds);
        }
        else
        {
            $this->driver->wait($seconds, 500)->until(WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::xpath($selector)));
        }

        return $this;
    }

    /**
     * Wait.
     *
     * @param  int    $seconds
     * @access public
     * @return void
     */
    public function wait($seconds = 3)
    {
        sleep($seconds);

        return $this;
    }

    /**
     * Operate element in modal.
     *
     * @param  string    $type
     * @param  string    $value
     * @access public
     * @return void|string
     */
    public function modal($type = '', $value = '')
    {
        if($type == 'text')
        {
            return $this->driver->executeScript("return arguments[0].innerText;", [$this->element]);
        }
        elseif($type == 'sendKeys')
        {
            return $this->driver->executeScript("arguments[0].sendKeys('$value');", [$this->element]);
        }
        else
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[0].click();');
        }
    }

    /**
     * Operate alert modal.
     *
     * @param  string $action
     * @access public
     * @return void
     */
    public function alertModal($action = '')
    {
        if($action == 'text')
        {
            return $this->driver->executeScript('return $(".modal-alert .modal-body div")[0].innerText;');
        }
        elseif($action == 'dismiss')
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[1].click();');
        }
        else
        {
            return $this->driver->executeScript('$(".modal-alert .modal-footer button")[0].click();');
        }
    }

    /**
     * Get title of current page.
     *
     * @access public
     * @return string
     */
    public function getTitle()
    {
        return $this->driver->getTitle();
    }

    /**
     * Get url of current page.
     *
     * @access public
     * @return string
     */
    public function getUrl()
    {
        $url = $this->driver->getCurrentURL();
        return $url;
    }

    /**
     * Get element's coordinate on page.
     *
     * @access public
     * @return array
     */
    public function getCoordinate()
    {
        $x = $this->element->getCoordinates()->onPage()->getX();
        $y = $this->element->getCoordinates()->onPage()->getY();
        return print_r(array($x, $y));
    }

    /**
     * Close browser.
     *
     * @access public
     * @return void
     */
    public function closeBrowser()
    {
        if($this->config->reportType == 'html') $this->results->endReport();
        $this->driver->quit();
    }

    /**
     * Close the window.
     *
     * @access public
     * @return mixed
     */
    public function closeWindow()
    {
        $this->driver->close();
    }

    /**
     * select by selecter
     *
     * @param  string $type     value|index|text|ptext:like
     * @param  string $value
     * @access public
     * @return void
     */
    public function select($type, $value)
    {
        $select = new WebDriverSelect($this->element);

        if($type == 'value') $select->selectByValue($value);
        if($type == 'index') $select->selectByIndex($value);
        if($type == 'text')  $select->selectByVisibleText($value);
        if($type == 'ptext') $select->selectByVisiblePartialText($value);
    }

    /**
     * get select value or text
     *
     * @param  string $type     value|text
     * @access public
     * @return void
     */
    public function getSelect($type = '')
    {
        $select = new WebDriverSelect($this->element);

        if($type == 'value') echo $select->getFirstSelectedOption()->getAttribute('value') . PHP_EOL;
        echo $select->getFirstSelectedOption()->getText() . PHP_EOL;
    }

    /**
     * Scroll the browser scroll bar. By default, scroll to the top.
     *
     * @param  int    $x
     * @param  int    $y
     * @access public
     * @return void
     */
    public function scrollTo($x = 0, $y = 10000)
    {
        $js = "window.scrollTo({$x},{$y})";
        $this->driver->executeScript($js);
    }

    /**
     * Scroll to selector.
     *
     * @access public
     * @return void
     */
    public function scrollToElement()
    {
        $js = "arguments[0].scrollIntoView(false);";
        $arguments = array($this->element);

        $this->driver->executeScript($js, $arguments);
    }

    /**
     * Select a option of picker.
     *
     * @param  string $picker
     * @param  string $value
     * @access public
     * @return viod
     */
    public function picker($picker, $value)
    {
        if(strcmp(substr($picker, -4), '/div') !== 0 && strpos($picker, '@name') === false)
        {
            $picker = $picker . '/div';
        }
        else
        {
            if(strpos($picker, '@name') !== false) $picker = $picker . '/parent::div';
        }

        $this->driver->findElement(WebDriverBy::xpath($picker))->click();

        sleep(1);

        try
        {
            $pickerInput = $picker . '//*[@class="picker-search"]/input';
            $this->driver->findElement(WebDriverBy::xpath($pickerInput))->click();
        }
        catch (Exception $selectionException)
        {
            $pickerInput = $picker . '//*[@class="picker-selections"]/input';
            $this->driver->findElement(WebDriverBy::xpath($pickerInput))->click();
        }

        $this->driver->findElement(WebDriverBy::xpath($pickerInput))->sendKeys(trim($value));
        sleep(1);

        $pickerID = substr($this->driver->findElement(WebDriverBy::xpath($picker))->getAttribute('id'), 5);
        $this->driver->findElement(WebDriverBy::xpath("//*[@id='pick-pop-$pickerID']//span[@class='is-match-keys']"))->click();
    }

    /**
     * Select multi options of picker.
     *
     * @param  string    $picker
     * @param  array    $values
     * @access public
     * @return void
     */
    public function multiPicker($picker, $values)
    {
        if(strcmp(substr($picker, -4), '/div') !== 0 && strpos($picker, '@name') === false)
        {
            $picker = $picker . '/div';
        }
        else
        {
            if(strpos($picker, '@name') !== false) $picker = $picker . '/parent::div';
        }

        $this->driver->findElement(WebDriverBy::xpath($picker))->click();

        sleep(1);

        foreach($values as $value)
        {
            $pickerInput = $picker . '//*[@class="picker-multi-selections"]//input';
            $this->driver->findElement(WebDriverBy::xpath($pickerInput))->click();
            $this->driver->findElement(WebDriverBy::xpath($pickerInput))->sendKeys(trim($value));
            sleep(1);

            $pickerID = substr($this->driver->findElement(WebDriverBy::xpath($picker))->getAttribute('id'), 5);
            $this->driver->findElement(WebDriverBy::xpath("//*[@id='pick-pop-$pickerID']//span[@class='is-match-keys']"))->click();
        }
    }

    /**
     * Select a option of no search picker.
     *
     * @param  string     $xpath
     * @param  string|int $value
     * @param  string     $type ''|date|menu
     * @access public
     * @return void
     */
    public function noSearchPicker($xpath, $value, $type = '')
    {
        $picker = $xpath . '/parent::div';
        $this->clickByXpath($picker);
        $pickerID = $this->driver->findElement(WebDriverBy::xpath($picker))->getAttribute('id');

        if($type == 'date')
        {
            $this->wait(1);
            return $this->setValueByXpath("$picker/input", $value);
        }
        elseif($type == 'menu')
        {
            return $this->clickByXpath("//*[@id='pick-pop-$pickerID']/menu/menu/li[$value]");
        }

        return $this->clickByXpath("//*[@id='pick-pop-$pickerID']/div/button[$value]");
    }

    /**
     * Search value in search form.
     *
     * @param  array  $searchList
     * @param  string $groupAndOr and|or
     * @access public
     * @return void
     */
    public function search($searchList, $groupAndOr = '')
    {
        $this->clickByXpath('//button[@data-toggle="searchform"]');

        $searchContainer  = '//div[contains(@class, "search-form-container")]';
        $searchSquareBtn  = $searchContainer . '/div/div[2]/div//button[contains(@class, "square")]';
        $leftSearchGroup  = $searchContainer . '/div/div/div/table/tbody';
        $rightSearchGroup = $searchContainer . '/div/div/div[3]/table/tbody';
        $searchBtn        = $searchContainer . '/div/div[2]/button';
        $restBtn          = $searchContainer . '/div/div[2]/button[2]';

        $this->waitElement($searchContainer);
        $this->waitElement($restBtn);
        $this->clickByXpath($restBtn);

        if($groupAndOr)
        {
            $groupAndOrXpath = '//*[@name="groupAndOr"]';
            if($groupAndOr == 'and')
            {
                $this->noSearchPicker($groupAndOrXpath, 1, 'menu');
            }
            else
            {
                $this->noSearchPicker($groupAndOrXpath, 2, 'menu');
            }
        }

        if(count($searchList) > 2) $this->clickByXpath($searchSquareBtn);

        foreach($searchList as $key => $value)
        {
            $index   = $key + 1;
            $trIndex = floor($key / 2) + 1;
            list($field, $operator, $value) = explode(',', $value);

            $fieldXpath    = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[2]/div" : $leftSearchGroup . "/tr[$trIndex]/td[2]/div";
            $operatorXpath = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[3]/div" : $leftSearchGroup . "/tr[$trIndex]/td[3]/div";
            $valueXpath    = $index % 2 === 0 ? $rightSearchGroup . "/tr[$trIndex]/td[4]" : $leftSearchGroup . "/tr[$trIndex]/td[4]";

            $this->picker($fieldXpath, $field);
            $this->wait(1);
            $this->clickByXpath($operatorXpath);
            $operatorID = $this->driver->findElement(WebDriverBy::xpath($operatorXpath))->getAttribute('id');
            $operatorID = substr($operatorID, 5);
            $this->clickByXpath("//*[@id='pick-pop-$operatorID']/menu/menu//*[contains(text(), '$operator')]");
            $this->wait(1);

            $valueTage = $this->getElement($valueXpath . '/*[1]')->getTagName();
            if($valueTage == 'input')
            {
                $this->setValueByXpath("$valueXpath/input", $value);
            }
            else
            {
                $this->picker("$valueXpath/div", $value);
            }
        }

        $this->clickByXpath($searchBtn);
    }

    /**
     * Get page source.
     *
     * @access public
     * @return string
     */
    public function getPageSource()
    {
        return $this->driver->getPageSource();
    }

    /**
     * Get window handles.
     *
     * @access public
     * @return mixed
     */
    public function getWindowHandles()
    {
        return $this->driver->getWindowHandles();
    }

    /**
     * Try to open new window/tab.
     *
     * @param  string $type 'tab'|'window'|''
     * @access public
     * @return object $driver
     */
    public function newWindow($type = '')
    {
        if($type == 'tab')
        {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_TAB);
        }
        elseif($type == 'window')
        {
            $this->driver->switchTo()->newWindow(WebDriverTargetLocator::WINDOW_TYPE_WINDOW);
        }
        else
        {
            $this->driver->switchTo()->newWindow();
        }

        return $this->driver;
    }

    /**
     * Refresh page.
     *
     * @access public
     * @return object
     */
    public function refresh()
    {
        $this->driver->navigate()->refresh();
        return $this;
    }

    /**
     * Set date in datePicker.
     *
     * @param  string    $name
     * @param  string    $value
     * @access public
     * @return object
     */
    public function datePicker($name, $value)
    {
        $this->driver->executeScript("return $('[name={$name}]').zui('datePicker').$.setValue('$value')");
        return $this;
    }

    /**
     * Set element in page.
     *
     * @param  string    $name
     * @param  int    $wait
     * @access public
     * @return object
     */
    public function setElement($name, $wait = 0)
    {
        $element = '//*[@name="' . $name . '"]';

        try
        {
            $this->getElement($element);
        }
        catch (Exception $e)
        {
            $element = '//*[@id="' . $name . '"]';
            try
            {
                $this->getElement($element);
            }
            catch (Exception $e)
            {
                $element = $name;
            }
        }

        if($wait) $this->waitElement($element, $wait);
        $this->getElement($element);
        return $this;
    }
}
