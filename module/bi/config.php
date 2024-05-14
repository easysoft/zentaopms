<?php
$config->bi = new stdclass();
$config->bi->builtin = new stdclass();

$config->bi->builtin->screens = array(1, 2, 3, 4, 5, 6, 7, 8,1001);

$config->bi->default = new stdclass();
$config->bi->default->styles  = json_decode('{"filterShow":false,"hueRotate":0,"saturate":1,"contrast":1,"brightness":1,"opacity":1,"rotateZ":0,"rotateX":0,"rotateY":0,"skewX":0,"skewY":0,"blendMode":"normal","animations":[]}');
$config->bi->default->status  = json_decode('{"lock":false,"hide":false}');
$config->bi->default->request = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestInterval":null,"requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->bi->default->events  = json_decode('{"baseEvent":{"click":null,"dblclick":null,"mouseenter":null,"mouseleave":null},"advancedEvents":{"vnodeMounted":null,"vnodeBeforeMount":null}}');
