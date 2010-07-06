<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:white; margin:20px 10px 0 0; padding-right:20px}</style>
<div class='yui-d0 yui-t1'>
  <div class='yui-b a-center'>
   <img src='theme/default/images/main/logo2.png' /><br />
   <h3><?php printf($lang->misc->zentao->version, $config->version);?></h3>
  </div>
  <div class='yui-main'>
  <div class='yui-b'>
  <table class='table-1'>
    <tr class='colhead'>
      <?php foreach($lang->misc->zentao->labels as $label) echo "<th class='w-p25'>$label</th>";?>
    </tr>
    <?php
    unset($lang->misc->zentao->version);
    unset($lang->misc->zentao->labels);
    ?>
    <tr class='a-left' valign='top'>
      <?php foreach($lang->misc->zentao as $groupItems):?>
      <td>
        <ul>
          <?php foreach($groupItems as $item => $label):?>
          <li><?php echo html::a("http://www.zentaoms.com/goto.php?item=$item", $label, '_blank');;?></li>
          <?php endforeach;?>
        </ul>
      </td>
      <?php endforeach;?>
    </tr>
  </table>
  <div class='a-right'><?php echo $lang->misc->copyright;?></div>
  </div>
  </div>
</div>
</body>
</html>
