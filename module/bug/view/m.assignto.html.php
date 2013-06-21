<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <div><?php echo $bug->title;?></div>
  <div><?php echo $lang->bug->assignedTo . html::select('assignedTo', $users, $bug->assignedTo, "class='text-3'");?></div>
  <div><?php echo $lang->comment . html::textarea('comment', '', "rows='6' class='area-1'");?></div>
  <?php include '../../common/view/action.html.php';?>
  <div data-role='footer' data-position='fixed'>
    <div data-role='navbar'>
      <ul>
      <?php
      echo '<li>' . html::submitButton() . '</li>';
      echo '<li>' . html::linkButton($lang->goback, $this->server->http_referer) . '</li>';
      ?>
      </ul>
    </div>
  </div>
</form>
<?php include '../../common/view/m.footer.html.php';?>
