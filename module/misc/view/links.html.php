<div class='container mw-800px' id="zentaoLinks">
  <div class='row'>
    <?php unset($lang->misc->zentao->version);?>
    <?php foreach($lang->misc->zentao as $label => $groupItems):?>
    <?php if(strpos(',labels,icons,version,', ",$label,") !== false) continue; ?>
    <div class='col-sm-3'>
      <div class='panel'>
        <div class='panel-heading'>
          <div class="panel-title"><?php echo $lang->misc->zentao->labels[$label];?></div>
        </div>
        <div class='panel-body'>
          <ul>
            <?php foreach($groupItems as $item => $label):?>
            <li><?php echo html::a($lang->misc->api . "/goto.php?item=$item&from=about", $label, '_blank', "id='$item'");;?></li>
            <?php endforeach;?>
          </ul>
        </div>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<style>
#proversion, #zentaotrain {color: #ff5d5d}
#zentaoLinks .panel {box-shadow: 0 2px 4px 0 rgba(240,242,246,0.50); border: 1px solid #eee; min-height: 230px;}
#zentaoLinks .panel-heading {background: #eeeeee; color: #666;}
#zentaoLinks .panel-body {padding: 10px;}
#zentaoLinks ul {}
#zentaoLinks ul > li {line-height: 24px;}
</style>