<div class='container mw-800px bd-0'>
  <div class='cards'>
    <?php
    unset($lang->misc->zentao->version);
    ?>
    <?php foreach($lang->misc->zentao as $label => $groupItems):?>
    <?php if(strpos(',labels,icons,version,', ",$label,") !== false) continue; ?>
    <div class='col-sm-6'>
      <div class='card card-<?php echo $label;?>'>
        <div class='media'>
          <?php echo html::icon($lang->misc->zentao->icons[$label], 'icon');?>
          <h5><?php echo $lang->misc->zentao->labels[$label];?></h5>
        </div>
        <div class='card-content'>
          <ul>
            <?php foreach($groupItems as $item => $label):?>
            <li><?php echo html::a("http://api.zentao.net/goto.php?item=$item&from=about", $label, '_blank', "id='$item'");;?></li>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
