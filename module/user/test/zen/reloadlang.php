#!/usr/bin/env php
<?php

/**

title=测试 userZen::reloadLang();
timeout=0
cid=19680

- 执行userZenTest模块的reloadLangTest方法，参数是'zh-cn' 属性currentLang @zh-cn
- 执行userZenTest模块的reloadLangTest方法，参数是'en' 属性currentLang @en
- 执行userZenTest模块的reloadLangTest方法，参数是'zh-tw' 属性currentLang @zh-tw
- 执行userZenTest模块的reloadLangTest方法，参数是'de' 属性currentLang @de
- 执行userZenTest模块的reloadLangTest方法，参数是'fr' 属性currentLang @fr

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userZenTest = new userZenTest();

r($userZenTest->reloadLangTest('zh-cn')) && p('currentLang') && e('zh-cn');
r($userZenTest->reloadLangTest('en')) && p('currentLang') && e('en');
r($userZenTest->reloadLangTest('zh-tw')) && p('currentLang') && e('zh-tw');
r($userZenTest->reloadLangTest('de')) && p('currentLang') && e('de');
r($userZenTest->reloadLangTest('fr')) && p('currentLang') && e('fr');