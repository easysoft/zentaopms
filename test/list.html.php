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

<h1>禅道全连接测试</h1>
<div class="row">
  <?php foreach($links as $module => $group):?>
  <div class="col card">
    <h3 class="title moduleName"><?php echo '模块: ' . $module;?></h3>
      <?php foreach($group as $method => $urlList):?>
      <div class="methodList">
      <h4 class="title"><a class="methodName" href="#"><?php echo '方法: ' . $method;?></a></h4>
      <ul>
         <?php foreach($urlList as $name => $url):?>
         <li><a href="<?php echo $url;?>" target="_blank"><?php echo $name;?></a></li>
         <?php endforeach;?>
      </ul>
      </div>
      <?php endforeach;?>
  </div>
  <?php endforeach;?>
</div>
<script>
$(document).ready(function()
{
    $('li a').on('click', function(e)
    {
        $(this).css('color', '#838383');
    })

    $('.methodName').on('click', function()
    {
        $(this).closest('.methodList').find('ul li a').each(function()
        {
            var link = $(this).attr('href');
            window.open(link);
        });
    })
})
</script>
