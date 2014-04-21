<?php include '../../common/view/header.lite.html.php';?>
<table class='table'>
  <caption><?php echo $lang->story->tasks;?></caption>
  <?php
  foreach($tasks as $task)
  {
      echo "<tr><td>$task</td></tr>";
  }
  ?>
</table>
<?php include '../../common/view/footer.lite.html.php';?>
