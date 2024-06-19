<?php
$config->bi = new stdclass();
$config->bi->builtin = new stdclass();

$config->bi->builtin->modules = new stdclass();
$config->bi->builtin->screens = array(1, 2, 3, 4, 5, 6, 7, 8,1001);

$config->bi->default = new stdclass();
$config->bi->default->styles  = json_decode('{"filterShow":false,"hueRotate":0,"saturate":1,"contrast":1,"brightness":1,"opacity":1,"rotateZ":0,"rotateX":0,"rotateY":0,"skewX":0,"skewY":0,"blendMode":"normal","animations":[]}');
$config->bi->default->status  = json_decode('{"lock":false,"hide":false}');
$config->bi->default->request = json_decode('{"requestDataType":0,"requestHttpType":"get","requestUrl":"","requestInterval":null,"requestIntervalUnit":"second","requestContentType":0,"requestParamsBodyType":"none","requestSQLContent":{"sql":"select * from  where"},"requestParams":{"Body":{"form-data":{},"x-www-form-urlencoded":{},"json":"","xml":""},"Header":{},"Params":{}}}');
$config->bi->default->events  = json_decode('{"baseEvent":{"click":null,"dblclick":null,"mouseenter":null,"mouseleave":null},"advancedEvents":{"vnodeMounted":null,"vnodeBeforeMount":null}}');

$charts = array();
$charts['31'] = array("root" => 1, "name" => "产品");
$charts['32'] = array("root" => 1, "name" => "项目");
$charts['33'] = array("root" => 1, "name" => "测试");
$charts['34'] = array("root" => 1, "name" => "组织");
$charts['35'] = array("root" => 1, "name" => "需求");
$charts['36'] = array("root" => 1, "name" => "发布");
$charts['37'] = array("root" => 1, "name" => "项目");
$charts['38'] = array("root" => 1, "name" => "任务");
$charts['39'] = array("root" => 1, "name" => "迭代");
$charts['40'] = array("root" => 1, "name" => "成本");
$charts['41'] = array("root" => 1, "name" => "工期");
$charts['42'] = array("root" => 1, "name" => "需求");
$charts['43'] = array("root" => 1, "name" => "Bug");
$charts['44'] = array("root" => 1, "name" => "项目集");
$charts['45'] = array("root" => 1, "name" => "项目");
$charts['46'] = array("root" => 1, "name" => "产品");
$charts['47'] = array("root" => 1, "name" => "计划");
$charts['48'] = array("root" => 1, "name" => "迭代");
