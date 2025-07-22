<?php
$config->screen->builtinScreen = array(6, 8);
$config->screen->builtinChart  = array(10018, 10019, 10020, 10021, 10022, 10211, 10212, 10213, 10214, 10215, 10216, 10217, 10218, 10219, 10220);
$config->screen->builtinPivot  = array(1000, 1001, 1002);
$config->screen->phpChart      = array(20002, 20004, 20007, 20010, 20011, 20012, 20013);

$config->screen->annualRankingChart = array(1085, 1086, 1087, 1088, 1089, 1090, 1091, 1092, 1093, 1094, 1096, 1097, 1098, 1099, 1100, 1101, 1102, 1103, 1104, 1105, 1106, 1107, 1108, 1109, 1110);

$config->screen->phpScreen = array();
$config->screen->phpScreen['usageReport'] = 1001;

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
$config->screen->chartConfig['text']        = '{"category" : "Texts", "categoryName" : "文本", "chartFrame" : "common", "chartKey" : "VTextCommon", "conKey" : "VCTextCommon", "image" : "text_static.png", "key" : "TextCommon", "package" : "Informations", "title" : "文字", "dataset" : {}}';
$config->screen->chartConfig['waterpolo']   = '{"category" : "Mores", "categoryName" : "更多", "chartFrame" : "common", "chartKey" : "VWaterPolo", "conKey" : "VCWaterPolo", "image" : "water_WaterPolo.png", "key" : "WaterPolo", "package" : "Charts", "title" : "水球图", "dataset" : {}}';
$config->screen->chartConfig['sunburst']    = '{"category" : "Mores", "categoryName" : "更多", "chartFrame" : "common", "chartKey" : "VSunBurst", "conKey" : "VCSunBurst", "image" : "pie.png", "key" : "SunBurst", "package" : "Charts", "title" : "旭日图", "dataset" : {}}';
$config->screen->chartConfig['box']         = '{"category" : "Mores", "categoryName" : "更多", "chartFrame" : "plotlys", "chartKey" : "VBox", "conKey" : "VCBox", "image" : "line.png", "key" : "Box", "package" : "Charts", "title" : "箱线图", "dataset" : {}}';
$config->screen->chartConfig['metric']      = '{"category" : "Metrics", "categoryName" : "度量项", "chartFrame" : "common", "chartKey" : "VMetrics", "conKey" : "VCMetrics", "image" : "bar_x.png", "key" : "Metrics", "package" : "Metrics", "title" : "度量项", "dataset" : {}}';
$config->screen->chartConfig['help']        = '{"category" : "Informations", "categoryName" : "信息", "chartFrame" : "common", "chartKey" : "VHint", "conKey" : "VCHint", "image" : "./static/png/hint.png", "key" : "Hint", "package" : "Decorates", "title" : "提示"}';

$config->screen->chartOption['cluBarX']     = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"left":"center"},"xAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"bottom","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":false,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"category"},"yAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"left","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":true,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"value"},"grid":{"show":false,"left":"10%","top":90,"right":"10%","bottom":"60"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true,"trigger":"axis","axisPointer":{"show":true,"type":"shadow"}},"dataset":{},"series":[{"type":"bar","barWidth":15,"label":{"show":true,"position":"top","color":"#fff","fontSize":12},"itemStyle":{"color":null,"borderRadius":2}}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['cluBarY']     = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"left":"center"},"xAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"bottom","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":false,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"value"},"yAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"left","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":true,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"category"},"grid":{"show":false,"left":"10%","top":70,"right":"10%","bottom":"60"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true,"trigger":"axis","axisPointer":{"show":true,"type":"shadow"}},"dataset":{},"series":[{"type":"bar","barWidth":null,"label":{"show":true,"position":"right","color":"#fff","fontSize":12},"itemStyle":{"color":null,"borderRadius":0}}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['stackedBar']  = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"left":"center"},"xAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"bottom","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":false,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"category"},"yAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"left","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":true,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"value"},"grid":{"show":false,"left":"10%","top":90,"right":"10%","bottom":"60"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true,"trigger":"axis","axisPointer":{"show":true,"type":"shadow"}},"series":[{"type":"bar","stack":"total","barWidth":null,"label":{"show":true,"position":"top","color":"#fff","fontSize":12},"itemStyle":{"color":null,"borderRadius":0}}],"backgroundColor":"rgba(0,0,0,0)","dataset":{}}';
$config->screen->chartOption['stackedBarY'] = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"left":"center"},"xAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"bottom","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":false,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"value"},"yAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"left","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":true,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"category"},"grid":{"show":false,"left":"10%","top":70,"right":"10%","bottom":"60"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true,"trigger":"axis","axisPointer":{"show":true,"type":"shadow"}},"dataset":{},"series":[{"type":"bar","stack":"total","barWidth":null,"label":{"show":true,"position":"right","color":"#fff","fontSize":12},"itemStyle":{"color":null,"borderRadius":0}},{"type":"bar","stack":"total","barWidth":null,"label":{"show":true,"position":"right","color":"#fff","fontSize":12},"itemStyle":{"color":null,"borderRadius":0}}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['pie']         = '{"legend":{"show":true,"top":30,"textStyle":{"color":"#B9B8CE"},"left":"center"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"type":"nomal","tooltip":{"show":true,"trigger":"item"},"grid":{"top":70},"dataset":{},"series":[{"type":"pie","radius":["40%","65%"],"center":["50%","60%"],"label":{"show":true,"formatter":"{b} {d}%","fontSize":12}}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['radar']       = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"data":[],"left":"center"},"title":{"text":"\u6298\u7ebf\u56fe","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true},"grid":{"top":90},"dataset":{},"radar":{"shape":"polygon","radius":["0%","60%"],"center":["50%","55%"],"splitArea":{"show":true},"splitLine":{"show":true},"axisName":{"show":true,"color":"#eee","fontSize":12},"axisLine":{"show":true},"axisTick":{"show":true},"indicator":[]},"series":[{"name":"radar","type":"radar","areaStyle":{"opacity":0.1},"data":[]}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['line']        = '{"legend":{"show":true,"top":40,"textStyle":{"color":"#B9B8CE"},"left":"center"},"xAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"bottom","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":false,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"category"},"yAxis":{"show":true,"name":"","nameGap":15,"nameTextStyle":{"color":"#B9B8CE","fontSize":12},"inverse":false,"axisLabel":{"show":true,"fontSize":12,"color":"#B9B8CE","rotate":0},"position":"left","axisLine":{"show":true,"lineStyle":{"color":"#B9B8CE","width":1},"onZero":true},"axisTick":{"show":true,"length":5},"splitLine":{"show":true,"lineStyle":{"color":"#484753","width":1,"type":"solid"}},"type":"value"},"grid":{"show":false,"left":"10%","top":90,"right":"10%","bottom":"60"},"title":{"text":"","show":true,"textStyle":{"color":"#BFBFBF","fontSize":18},"subtextStyle":{"color":"#A2A2A2","fontSize":14},"top":10,"x":"center"},"tooltip":{"show":true,"trigger":"axis","axisPointer":{"type":"line"}},"dataset":{},"series":[{"type":"line","label":{"show":true,"position":"top","color":"#fff","fontSize":12},"symbolSize":5,"itemStyle":{"color":null,"borderRadius":0},"lineStyle":{"type":"solid","width":3,"color":null}},{"type":"line","label":{"show":true,"position":"top","color":"#fff","fontSize":12},"symbolSize":5,"itemStyle":{"color":null,"borderRadius":0},"lineStyle":{"type":"solid","width":3,"color":null}}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['table']       = '{}';
$config->screen->chartOption['text']        = '{"fontSize":20,"fontColor":"#fff","textAlign":"center","fontWeight":"normal","borderWidth":0,"borderColor":"#fff","borderRadius":5}';
$config->screen->chartOption['waterpolo']   = '{"type":"nomal","series":[{"type":"liquidFill","radius":"90%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}';
$config->screen->chartOption['sunburst']    = '{"series":{"type":"sunburst","data": [], "radius": [0, "90%"], "label": {"rotate": "radial"}}}';
$config->screen->chartOption['box']         = '{"data": [], "layout": {"title": {"text": ""}}, "config": {}}';
$config->screen->chartOption['help']        = '{"text": "", "icon": "", "textSize": 30, "textColor": "#ffffff", "textWeight": "bold", "placement": "right-bottom", "distance": 8, "hint": "提示文本", "width": 0, "height": 0, "paddingX": 16, "paddingY": 8, "borderWidth": 1, "borderStyle": "solid", "borderColor": "#1a77a5", "borderRadius": 6, "color": "#ffffff", "textAlign": "left", "fontWeight": "normal", "backgroundColor": "rgba(89, 196, 230, .2)", "fontSize": 24}';
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
