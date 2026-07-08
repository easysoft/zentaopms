<?php
include dirname(__FILE__) . '/lib/ui.php';
use Facebook\WebDriver\WebDriverBy;

$urlList = shell_exec("cat generated_urls_v4.txt");
$urlList = explode("\n", trim($urlList));

$resultDir = 'uiResult';
if(!is_dir($resultDir)) mkdir($resultDir);

$tester = new tester();
$tester->login();
sleep(1);

$driver = $tester->webdriver->driver;


foreach($urlList as $url)
{
    $waitTime = 1;
    $urlQuery = parse_url($url);
    $path = empty($urlQuery['path']) ? '' : $urlQuery['path'];
    $path = str_replace(array('/', '.html'), '', $path);
    $path = explode('-', $path);
    $module = isset($path[0]) ? $path[0] : '';
    $method = isset($path[1]) ? $path[1] : '';

    $tester->page->openURL($url);
    $tester->page->wait($waitTime);
    try
    {
        $title = $tester->page->getPageTitle();
    }
    catch(Exception $e)
    {
        $title = '';
    }
    if(empty($title)) continue;

    $tester->page->wait($waitTime);
    $outerIframes = $driver->findElements(WebDriverBy::tagName('iframe'));
    if (count($outerIframes) > 0) {
        $driver->switchTo()->frame($outerIframes[0]); // 切换到外层第一个iframe（索引0）

        // 2. 验证并切换到外层iframe内的第一个子iframe
        $innerIframes = $driver->findElements(WebDriverBy::tagName('iframe'));
        if (count($innerIframes) > 0) {
            $driver->switchTo()->frame($innerIframes[0]); // 切换到子第一个iframe

            // 执行子iframe内的操作
            echo "成功进入嵌套iframe！\n";

        } else {
            echo "外层iframe 0内没有子iframe！\n";
        }
    } else {
        echo "页面上没有外层iframe 0！\n";
    }
    $iframeInfo = $driver->executeScript("
       // 判断是否在iframe中
       if (window.self !== window.top) {
           // 返回当前iframe的ID、name、src等信息
           return {
               id: window.frameElement.id,
               name: window.frameElement.name,
               src: window.frameElement.src,
               isIframe: true
           };
       } else {
           return {isIframe: false};
       }
    ");
    try
    {
        $driver->executeScript('zin();');
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
        continue;
    }
    $template = $driver->executeScript('return $(".prettyprint")[0].textContent;');
    if($module && $method)
    {
        if(!is_dir($resultDir . '/' . $module)) mkdir ($resultDir . '/' . $module);
        file_put_contents($resultDir . '/' . $module . '/' . strtolower($method) . '.html.php', $template);
    }
}

$tester->closeBrowser();
