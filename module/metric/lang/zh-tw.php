<?php
$lang->metric->common        = "度量項";
$lang->metric->name          = "名稱";
$lang->metric->stage         = "階段";
$lang->metric->scope         = "度量範圍";
$lang->metric->object        = "度量對象";
$lang->metric->purpose       = "度量目的";
$lang->metric->unit          = "度量單位";
$lang->metric->code          = "度量項代號";
$lang->metric->desc          = "度量項描述";
$lang->metric->definition    = "定義";
$lang->metric->formula       = "計算規則";
$lang->metric->when          = "收集方式";
$lang->metric->createdBy     = "創建者";
$lang->metric->implement     = "實現";
$lang->metric->implementedBy = "由誰實現";
$lang->metric->offlineBy     = "由誰下架";
$lang->metric->lastEdited    = "最後修改";
$lang->metric->value         = "數值";
$lang->metric->date          = "日期";
$lang->metric->metricData    = "度量數據";
$lang->metric->system        = "system";
$lang->metric->weekCell      = "%s年第%s週";
$lang->metric->create        = "創建" . $this->lang->metric->common;
$lang->metric->afterCreate   = "保存後";
$lang->metric->definition    = "計算規則";
$lang->metric->customUnit    = "自定義";

$lang->metric->descTip       = '請輸入度量項含義、目的和作用等';
$lang->metric->definitionTip = '請輸入度量項的計算規則及過濾條件等';

$lang->metric->noDesc    = "暫無描述";
$lang->metric->noFormula = "暫無計算規則";
$lang->metric->noCalc    = "暫未實現度量項PHP算法";

$lang->metric->legendBasicInfo  = '基本信息';
$lang->metric->legendCreateInfo = '創建編輯信息';

$lang->metric->confirmDelete = "確認要刪除嗎？";

$lang->metric->browseAction = '度量項列表';

$lang->metric->stageList = array();
$lang->metric->stageList['wait']     = "未發布";
$lang->metric->stageList['released'] = "已發布";

$lang->metric->unitList = array();
$lang->metric->unitList['count']   = '個';
$lang->metric->unitList['measure'] = '工時';
$lang->metric->unitList['hour']    = '小時';
$lang->metric->unitList['day']     = '天';
$lang->metric->unitList['manday']  = '人天';

$lang->metric->afterCreateList = array();
$lang->metric->afterCreateList['back']   = '返回列表頁';
$lang->metric->afterCreateList['finish'] = '去實現度量項';

$lang->metric->dateList = array();
$lang->metric->dateList['year']  = '年';
$lang->metric->dateList['month'] = '月';
$lang->metric->dateList['week']  = '週';
$lang->metric->dateList['day']   = '日';

$lang->metric->purposeList = array();
$lang->metric->purposeList['scale'] = "規模估算";
$lang->metric->purposeList['time']  = "工期控制";
$lang->metric->purposeList['cost']  = "成本計算";
$lang->metric->purposeList['hour']  = "工時統計";
$lang->metric->purposeList['qc']    = "質量控制";
$lang->metric->purposeList['rate']  = "效率提升";

$lang->metric->scopeList = array();
$lang->metric->scopeList['system']    = "系統";
$lang->metric->scopeList['program']   = "項目集";
$lang->metric->scopeList['product']   = "產品";
$lang->metric->scopeList['project']   = "專案";
$lang->metric->scopeList['execution'] = "執行";
$lang->metric->scopeList['dept']      = "團隊";
$lang->metric->scopeList['user']      = "個人";
// $lang->metric->scopeList['code']      = "程式庫";
// $lang->metric->scopeList['pipeline']  = "流水線";

global $config;
$lang->metric->objectList = array();
$lang->metric->objectList['program']     = "項目集";
$lang->metric->objectList['line']        = "產品線";
$lang->metric->objectList['product']     = "產品";
$lang->metric->objectList['project']     = "專案";
$lang->metric->objectList['productplan'] = "計劃";
$lang->metric->objectList['execution']   = "執行";
$lang->metric->objectList['release']     = "發佈";
$lang->metric->objectList['story']       = "研發需求";
$lang->metric->objectList['requirement'] = "使用者需求";
$lang->metric->objectList['task']        = "任務";
$lang->metric->objectList['bug']         = "錯誤";
$lang->metric->objectList['case']        = "測試案例";
$lang->metric->objectList['user']        = "人員";
$lang->metric->objectList['effort']      = "工時";
$lang->metric->objectList['doc']         = "文件";
if($config->edition != 'open')
{
$lang->metric->objectList['feedback'] = "回饋";
$lang->metric->objectList['risk']     = "風險";
$lang->metric->objectList['issue']    = "問題";
}

$lang->metric->implementInstructions = "實現說明";
$lang->metric->implementTips         = array();
$lang->metric->implementTips[]       = '1.下載度量項目模板code.php，注意：檔案名稱要與度量代號保持一致。';
$lang->metric->implementTips[]       = '2.對檔案進行編碼開發操作，操作參考手冊。';
$lang->metric->implementTips[]       = '3.請將開發後的code.php檔案放到[使用者禪道目錄]/tmp/metric.php目錄下。';
$lang->metric->implementTips[]       = '4.執行命令賦予檔案可執行權限。';
