<?php
$config->screen->builtinScreen = array(6, 8);
$config->screen->builtinChart  = array(10018, 10019, 10020, 10021, 10022, 10211, 10212, 10213, 10214, 10215, 10216, 10217, 10218, 10219, 10220);
$config->screen->builtinPivot  = array(1000, 1001, 1002);
$config->screen->phpChart      = array(20002, 20004, 20007, 20010, 20011, 20012, 20013);

$config->screen->editCanvasConfig = new stdclass();
$config->screen->editCanvasConfig->width            = 1300;
$config->screen->editCanvasConfig->height           = 1080;
$config->screen->editCanvasConfig->filterShow       = false;
$config->screen->editCanvasConfig->hueRotate        = 0;
$config->screen->editCanvasConfig->saturate         = 1;
$config->screen->editCanvasConfig->contrast         = 1;
$config->screen->editCanvasConfig->brightness       = 1;
$config->screen->editCanvasConfig->opacity          = 1;
$config->screen->editCanvasConfig->rotateZ          = 0;
$config->screen->editCanvasConfig->rotateX          = 0;
$config->screen->editCanvasConfig->rotateY          = 0;
$config->screen->editCanvasConfig->skewX            = 0;
$config->screen->editCanvasConfig->skewY            = 0;
$config->screen->editCanvasConfig->blendMode        = 'normal';
$config->screen->editCanvasConfig->background       = '#001028';
$config->screen->editCanvasConfig->selectColor      = true;
$config->screen->editCanvasConfig->chartThemeColor  = 'dark';
$config->screen->editCanvasConfig->previewScaleType = 'scrollY';

$config->screen->chartConfig = array();
$config->screen->chartConfig['cluBarX']     = '{"category" : "Bars", "categoryName" : "柱状图", "chartFrame" : "echarts", "chartKey" : "VBarCommon", "conKey" : "VCBarCommon", "image" : "bar_x.png", "key" : "BarCommon", "package" : "Charts", "title" : "簇状柱形图", "dataset" : {}}';
$config->screen->chartConfig['cluBarY']     = '{"category" : "Bars", "categoryName" : "柱状图", "chartFrame" : "echarts", "chartKey" : "VBarCrossrange", "conKey" : "VCBarCrossrange", "image" : "bar_y.png", "key" : "BarCrossrange", "package" : "Charts", "title" : "簇状条形图", "dataset" : {}}';
$config->screen->chartConfig['stackedBar']  = '{"category" : "Bars", "categoryName" : "柱状图", "chartFrame" : "echarts", "chartKey" : "VBarStacked", "conKey" : "VCBarStacked", "image" : "bar_stacked_x.png", "key" : "BarStacked", "package" : "Charts", "title" : "堆积柱形图", "dataset" : {}}';
$config->screen->chartConfig['stackedBarY'] = '{"category" : "Bars", "categoryName" : "柱状图", "chartFrame" : "echarts", "chartKey" : "VBarStackedCrossrange", "conKey" : "VCBarStackedCrossrange", "image" : "bar_stacked_y.png", "key" : "BarStackedCrossrange", "package" : "Charts", "title" : "堆积条形图", "dataset" : {}}';
$config->screen->chartConfig['pie']         = '{"category" : "Pies", "categoryName" : "饼图", "chartFrame" : "echarts", "chartKey" : "VPieCommon", "conKey" : "VCPieCommon", "image" : "pie.png", "key" : "PieCommon", "package" : "Charts", "title" : "饼图", "dataset" : {}}';
$config->screen->chartConfig['piecircle']   = '{"category" : "Pies", "categoryName" : "饼图", "chartFrame" : "echarts", "chartKey" : "VPieCircle", "conKey" : "VCPieCircle", "image" : "pie-circle.png", "key" : "PieCircle", "package" : "Charts", "title" : "饼图-环形", "dataset" : {}}';
$config->screen->chartConfig['radar']       = '{"category" : "Mores", "categoryName" : "更多", "chartFrame" : "common", "chartKey" : "VRadar", "conKey" : "VCRadar", "image" : "radar.png", "key" : "Radar", "package" : "Charts", "title" : "雷达图", "dataset" : {}}';
$config->screen->chartConfig['line']        = '{"category" : "Lines", "categoryName" : "折线图", "chartFrame" : "echarts", "chartKey" : "VLineCommon", "conKey" : "VCLineCommon", "image" : "line.png", "key" : "LineCommon", "package" : "Charts", "title" : "折线图", "dataset" : {}}';
$config->screen->chartConfig['table']       = '{"category" : "Tables", "categoryName" : "列表", "chartFrame" : "common", "chartKey" : "VTableMergeCell", "conKey" : "VCTableMergeCell", "image" : "table_scrollboard.png", "key" : "TableMergeCell", "package" : "Tables", "title" : "透视表", "dataset" : {}, "tableInfo": {}}';
$config->screen->chartConfig['card']        = '{"category" : "Texts", "categoryName" : "文本", "chartFrame" : "common", "chartKey" : "VTextCommon", "conKey" : "VCTextCommon", "image" : "text_static.png", "key" : "TextCommon", "package" : "Informations", "title" : "文字", "dataset" : {}}';
$config->screen->chartConfig['waterpolo']   = '{"category" : "Mores", "categoryName" : "更多", "chartFrame" : "common", "chartKey" : "VWaterPolo", "conKey" : "VCWaterPolo", "image" : "water_WaterPolo.png", "key" : "WaterPolo", "package" : "Charts", "title" : "水球图", "dataset" : {}}';

$config->screen->fieldConfig = new stdclass();
$config->screen->fieldConfig->bug       = new stdclass();
$config->screen->fieldConfig->execution = new stdclass();
// $config->screen->fieldConfig->product   = new stdclass();
// $config->screen->fieldConfig->project   = new stdclass();
// $config->screen->fieldConfig->task      = new stdclass();
// $config->screen->fieldConfig->testcase  = new stdclass();

$this->loadLang('bug');
$config->screen->fieldConfig->bug->name = $this->lang->bug->common;
$config->screen->fieldConfig->bug->fields = array();
$config->screen->fieldConfig->bug->fields['status']     = $this->lang->bug->status;
$config->screen->fieldConfig->bug->fields['confirmed']  = $this->lang->bug->confirmed;
$config->screen->fieldConfig->bug->fields['severity']   = $this->lang->bug->severity;
$config->screen->fieldConfig->bug->fields['product']    = $this->lang->bug->product;
$config->screen->fieldConfig->bug->fields['project']    = $this->lang->bug->project;
$config->screen->fieldConfig->bug->fields['priv']       = $this->lang->bug->pri;
$config->screen->fieldConfig->bug->fields['resolution'] = $this->lang->bug->resolution;

$config->screen->fieldConfig->bug->options = array();
$config->screen->fieldConfig->bug->options['status']     = array('type' => 'lang', 'options' => $this->lang->bug->statusList);
$config->screen->fieldConfig->bug->options['confirmed']  = array('type' => 'lang', 'options' => $this->lang->bug->confirmedList);
$config->screen->fieldConfig->bug->options['severity']   = array('type' => 'lang', 'options' => $this->lang->bug->severityList);
$config->screen->fieldConfig->bug->options['product']    = array('type' => 'sys', 'options' => 'product');
$config->screen->fieldConfig->bug->options['project']    = array('type' => 'sys', 'options' => 'project');
$config->screen->fieldConfig->bug->options['priv']       = array('type' => 'lang', 'options' => $this->lang->bug->priList);
$config->screen->fieldConfig->bug->options['resolution'] = array('type' => 'lang', 'options' => $this->lang->bug->resolutionList);

$this->loadLang('execution');
$config->screen->fieldConfig->execution->name = $this->lang->execution->common;
$config->screen->fieldConfig->execution->fields = array();
$config->screen->fieldConfig->execution->fields['type']   = $this->lang->execution->type;
$config->screen->fieldConfig->execution->fields['status'] = $this->lang->execution->status;

$config->screen->fieldConfig->execution->options = array();
$config->screen->fieldConfig->execution->options['type']   = array('type' => 'lang', 'options' => $this->lang->execution->typeList);
$config->screen->fieldConfig->execution->options['status'] = array('type' => 'lang', 'options' => $this->lang->execution->statusList);
