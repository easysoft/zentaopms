<?php
$config->screen->chart = new stdclass();
$config->screen->chart->default = new stdclass();
$config->screen->chart->default->styles  = json_decode('{"filterShow": false, "hueRotate": 0, "saturate": 1, "contrast": 1, "brightness": 1, "opacity": 1, "rotateZ": 0, "rotateX": 0, "rotateY": 0, "skewX": 0, "skewY": 0, "blendMode": "normal", "animations": []}');
$config->screen->chart->default->status  = json_decode('{"lock": false, "hide": false}');
$config->screen->chart->default->request = json_decode('{ "requestDataType": 0, "requestHttpType": "get", "requestUrl": "", "requestIntervalUnit": "second", "requestContentType": 0, "requestParamsBodyType": "none", "requestSQLContent": { "sql": "select * from  where" }, "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');

$config->screen->chart->global = new stdclass();
$config->screen->chart->global->request = json_decode('{ "requestDataPond": [], "requestOriginUrl": "", "requestInterval": 30, "requestIntervalUnit": "second", "requestParams": { "Body": { "form-data": {}, "x-www-form-urlencoded": {}, "json": "", "xml": "" }, "Header": {}, "Params": {} } }');
