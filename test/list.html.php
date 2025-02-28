<?php
include '../test/result.php';
$links = array();
foreach($link as $module => $group)
{
    foreach($group as $name => $url)
    {
        $method = explode('_', $name)[0];
        $links[$module][$method][$name] = $url;
    }
}
?>
<script src="https://cdn.staticfile.net/jquery/1.10.2/jquery.min.js">
</script>
<style>
h1 {text-align: center;}
ul {padding: 0;}
ul li {list-style: none; padding: 4px 0px; margin: 0px;}
.row {width: 100%; margin: 20px; display: flex; flex-flow: row wrap;}
.col {flex: 0 0 19.5%;}
a {color: #0c64eb;}
.moduleName {color: #000;}
.methodName {color: #000;}
.methodList {border: 1px solid #000; margin: 10px; margin-left: 0px; padding: 10px;}
body {overflow-x: hidden;}
</style>
