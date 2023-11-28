<?php
$config->screen->chart = new stdclass();
$config->screen->chart->global = new stdclass();
$config->screen->chart->global->request = json_decode('{ "requestDataPond": [], "requestOriginUrl": "", "requestInterval": 30, "requestIntervalUnit": "second", "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

$config->screen->chart->default = new stdclass();
$config->screen->chart->default->config = new stdclass();
$config->screen->chart->default->config->styles  = json_decode('{"filterShow": false, "hueRotate": 0, "saturate": 1, "contrast": 1, "brightness": 1, "opacity": 1, "rotateZ": 0, "rotateX": 0, "rotateY": 0, "skewX": 0, "skewY": 0, "blendMode": "normal", "animations": []}');
$config->screen->chart->default->config->status  = json_decode('{"lock": false, "hide": false}');
$config->screen->chart->default->config->request = json_decode('{ "requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

$config->screen->chart->default->line = new stdclass();
$config->screen->chart->default->line->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->line->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->line->key         = "LineCommon";
$config->screen->chart->default->line->chartConfig = json_decode('{"key":"LineCommon","chartKey":"VLineCommon","conKey":"VCLineCommon","title":"折线图","category":"Lines","categoryName":"折线图","package":"Charts","chartFrame":"echarts","image":"/static/png/line-e714bc74.png"}');
$config->screen->chart->default->line->option      = json_decode('{"legend":{"show":true,"top":"5%","textStyle":{"color":"#B9B8CE"}},"xAxis":{"type":"category"},"yAxis":{"show":true,"axisLine":{"show":true},"type":"value"},"backgroundColor":"rgba(0,0,0,0)"}');

$config->screen->chart->default->table = new stdclass();
$config->screen->chart->default->table->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->table->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->table->key         = "TableScrollBoard";
$config->screen->chart->default->table->chartConfig = json_decode('{"key":"TableScrollBoard","chartKey":"VTableScrollBoard","conKey":"VCTableScrollBoard","title":"轮播列表","category":"Tables","categoryName":"表格","package":"Tables","chartFrame":"common","image":"/static/png/table_scrollboard-fb642e78.png"}');
$config->screen->chart->default->table->option      = json_decode('{"header":["列1","列2","列3"],"dataset":[["行1列1","行1列2","行1列3"],["行2列1","行2列2","行2列3"],["行3列1","行3列2","行3列3"]],"rowNum":2,"waitTime":2,"headerHeight":35,"carousel":"single","headerBGC":"#00BAFF","oddRowBGC":"#003B51","evenRowBGC":"#0A2732"}');

$config->screen->chart->default->bar = new stdclass();
$config->screen->chart->default->bar->request     = json_decode('{"requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {}}}');
$config->screen->chart->default->bar->events      = json_decode('{"baseEvent": {}, "advancedEvents": {}}');
$config->screen->chart->default->bar->key         = "BarCrossrange";
$config->screen->chart->default->bar->chartConfig = json_decode('{"key": "BarCrossrange", "chartKey": "VBarCrossrange", "conKey": "VCBarCrossrange", "title": "横向柱状图", "category": "Bars", "categoryName": "柱状图", "package": "Charts", "chartFrame": "echarts", "image": "/static/png/bar_y-05067169.png" }');
$config->screen->chart->default->bar->option      = json_decode('{"xAxis": { "show": true, "type": "category" }, "yAxis": { "show": true, "axisLine": { "show": true }, "type": "value" }, "series": [], "backgroundColor": "rgba(0,0,0,0)"}');

$config->screen->chart->default->pie = new stdclass();
$config->screen->chart->default->pie->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->pie->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->pie->key         = "PieCommon";
$config->screen->chart->default->pie->chartConfig = json_decode('{"key":"PieCommon","chartKey":"VPieCommon","conKey":"VCPieCommon","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-9620f191.png"}');
$config->screen->chart->default->pie->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

$config->screen->chart->default->piecircle = new stdclass();
$config->screen->chart->default->piecircle->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->piecircle->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->piecircle->key         = "PieCircle";
$config->screen->chart->default->piecircle->chartConfig = json_decode('{"key":"PieCircle","chartKey":"VPieCircle","conKey":"VCPieCircle","title":"饼图","category":"Pies","categoryName":"饼图","package":"Charts","chartFrame":"echarts","image":"/static/png/pie-circle-258fcce7.png"}');
$config->screen->chart->default->piecircle->option      = json_decode('{"type":"nomal","series":[{"type":"pie","radius":"70%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

$config->screen->chart->default->waterpolo = new stdclass();
$config->screen->chart->default->waterpolo->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->waterpolo->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->waterpolo->key         = "WaterPolo";
$config->screen->chart->default->waterpolo->chartConfig = json_decode('{"key":"WaterPolo","chartKey":"VWaterPolo","conKey":"VCWaterPolo","title":"水球图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"water_WaterPolo.png"}');
$config->screen->chart->default->waterpolo->option      = json_decode('{"type":"nomal","series":[{"type":"liquidFill","radius":"90%","roseType":false}],"backgroundColor":"rgba(0,0,0,0)"}');

$config->screen->chart->default->radar = new stdclass();
$config->screen->chart->default->radar->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->radar->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->radar->key         = "Radar";
$config->screen->chart->default->radar->chartConfig = json_decode('{"key":"Radar","chartKey":"VRadar","conKey":"VCRadar","title":"雷达图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"common","image":"/static/png/radar-91567f95.png"}');
$config->screen->chart->default->radar->option      = json_decode('{"radar":{"indicator":[{"name":"数据1","max":6500},{"name":"数据2","max":16000},{"name":"数据3","max":30000},{"name":"数据4","max":38000},{"name":"数据5","max":52000}]},"series":[{"name":"radar","type":"radar","areaStyle":{"opacity":0.1},"data":[{"name":"data1","value":[4200,3000,20000,35000,50000]}]}],"backgroundColor":"rgba(0,0,0,0)"}');

$config->screen->chart->default->funnel = new stdclass();
$config->screen->chart->default->funnel->request     = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->screen->chart->default->funnel->events      = json_decode('{"baseEvent":{},"advancedEvents":{}}');
$config->screen->chart->default->funnel->key         = "Funnel";
$config->screen->chart->default->funnel->chartConfig = json_decode('{"key":"Funnel","chartKey":"VFunnel","conKey":"VCFunnel","title":"漏斗图","category":"Mores","categoryName":"更多","package":"Charts","chartFrame":"echarts","image":"/static/png/funnel-d032fdf6.png"}');
$config->screen->chart->default->funnel->option      = json_decode('{"dataset":{"dimensions":["product","dataOne"],"source":[{"product":"data1","dataOne":20},{"product":"data2","dataOne":40},{"product":"data3","dataOne":60},{"product":"data4","dataOne":80},{"product":"data5","dataOne":100}]},"series":[{"name":"Funnel","type":"funnel","gap":5,"label":{"show":true,"position":"inside","fontSize":12}}],"backgroundColor":"rgba(0,0,0,0)"}');
