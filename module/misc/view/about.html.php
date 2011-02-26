<?php include '../../common/view/header.lite.html.php';?>
<table class='cont-lt1'>
  <tr>
    <td class='side a-center'>
      <img src='theme/default/images/main/logo2.png' />
      <h3><?php printf($lang->misc->zentao->version, $config->version);?></h3>
    </td>
    <td> 
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
              <li><?php echo html::a("http://www.zentao.net/goto.php?item=$item", $label, '_blank');;?></li>
              <?php endforeach;?>
            </ul>
          </td>
          <?php endforeach;?>
        </tr>
      </table>
      <div class='a-right'><?php echo $lang->misc->copyright;?></div>
    </td>
  </tr>
</table>
