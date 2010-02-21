<?php
$clientTheme = $this->app->getClientTheme();
$webRoot     = $this->app->getWebRoot();
$jsRoot      = $webRoot . "js/";
$themeRoot   = $webRoot . "theme/";
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dli'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'yui.css';?>' type='text/css' media='screen' />
<link rel='stylesheet' href='<?php echo $clientTheme . 'style.css';?>' type='text/css' media='screen' />
</head>
<body>
<div class="yui-d0" style='margin-top:10px'>
<table class='table-1'>
<caption><?php echo $lang->story->tasks;?></caption>
<?php
foreach($tasks as $task)
{
    echo "<tr><td>$task</td></tr>";
}
?>
</table>
</div>
</body>
</html>
