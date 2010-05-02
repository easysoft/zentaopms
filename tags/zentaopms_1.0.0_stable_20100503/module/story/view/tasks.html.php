<?php include '../../common/view/header.lite.html.php';?>
<style >body{background:white}</style>
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
