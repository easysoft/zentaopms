#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

/**

title=测试 projectModel::getBudgetUnitList();
cid=17817

- 获取预算单位
 - 属性CNY @¥ 人民币
 - 属性USD @$ 美元
 - 属性HKD @HK$ 港元
 - 属性NTD @NT$ 台币
 - 属性EUR @€ 欧元
 - 属性DEM @DEM 马克
 - 属性CHF @₣ 瑞士法郎
 - 属性FRF @₣ 法国法郎
 - 属性GBP @£ 英镑
 - 属性NLG @ƒ 荷兰盾
 - 属性CAD @$ 加拿大元
 - 属性RUR @₽ 卢布
 - 属性INR @₹ 卢比
 - 属性AUD @A$ 澳大利亚元
 - 属性NZD @NZ$ 新西兰元
 - 属性THB @฿ 泰国铢
 - 属性SGD @S$ 新加坡元

*/

global $tester;
$tester->loadModel('project');
$tester->config->project->unitList = 'CNY,USD,HKD,NTD,EUR,DEM,CHF,FRF,GBP,NLG,CAD,RUR,INR,AUD,NZD,THB,SGD';

r($tester->project->getBudgetUnitList()) && p('CNY,USD,HKD,NTD,EUR,DEM,CHF,FRF,GBP,NLG,CAD,RUR,INR,AUD,NZD,THB,SGD') && e('¥ 人民币,$ 美元,HK$ 港元,NT$ 台币,€ 欧元,DEM 马克,₣ 瑞士法郎,₣ 法国法郎,£ 英镑,ƒ 荷兰盾,$ 加拿大元,₽ 卢布,₹ 卢比,A$ 澳大利亚元,NZ$ 新西兰元,฿ 泰国铢,S$ 新加坡元'); // 获取预算单位
