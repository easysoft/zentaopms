<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <div><?php echo $bug->title;?></div>
  <div><?php echo html::textarea('comment', '', "rows='6' class='area-1' placeholder=$lang->comment");?></div>
  <div class='a-center'>
  <?php echo html::submitButton('', 'data-inline="true" data-theme="b"');?>
  <?php echo html::backButton("data-inline='true'");?>
  </div>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
