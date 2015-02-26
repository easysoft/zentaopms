<?php include '../../common/view/header.html.php';?>
<div id='featurebar'>
  <ul class='nav'>
  <?php 
  echo '<li id="api">'; common::printLink('dev', 'api', "", $lang->dev->api); echo '</li>';
  echo '<li id="db">'; common::printLink('dev', 'db', "",   $lang->dev->db); echo '</li>';
  echo "<li id='editor'>"; common::printLink('editor', 'index', 'type=editor', $lang->dev->editor); echo '</li>';
  ?>
  </ul>
</div>
<script>$('#<?php echo $tab;?>').addClass('active')</script>
