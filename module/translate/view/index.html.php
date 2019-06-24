<?php include '../../common/view/header.html.php';?>
<div class='center-block mw-800px'>
  <div id="mainMenu" class="clearfix">
<?php printf($lang->translate->allItems, $itemCount);?>
<div class='pull-right'>
<?php echo html::a(inlink('setting'), "<i class='icon icon-cog'></i>", '', "class='iframe btn btn-sm btn-link'");?>
</div>
</div>
  <div class='row'>
    <div class='col-sm-6'>
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $lang->translate->finishedLang;?></div>
          <div class='panel-actions'><?php printf($lang->translate->count, count($finishedLangs));?></div>
        </div>
        <div class='panel-body'>
          <?php foreach($finishedLangs as $langKey => $langName):?>
          <div class='item'>
            <div class='pull-right'><?php echo in_array($langKey, $config->translate->defaultLang) ? $lang->translate->builtIn : $lang->translate->finished;?></div>
            <h4><?php echo $langName;?></h4>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
    <div class='col-sm-6' id='translatingLangs'>
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $lang->translate->translatingLang;?></div>
          <div class='panel-actions'><?php printf($lang->translate->count, count($translatingLangs));?></div>
        </div>
        <div class='panel-body'>
          <table class='table table-form'>
            <?php foreach($translatingLangs as $langKey => $data):?>
            <tr>
              <th class='text-left'><?php echo $data->name;?></th>
              <td class='text-progress text-center'><?php printf($lang->translate->progress, ($data->progress * 100) . '%');?></td>
              <td class='w-110px'>
                <?php
                echo html::a(inlink('chooseModule', "language=$langKey"), $lang->translate->common, '', "class='btn btn-sm'");
                echo html::a(inlink('export', "language=$langKey"), $lang->export, '', "class='btn btn-sm iframe'");
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </table>
        </div>
        <p class='text-center'><?php echo html::a(inlink('addLang'), $lang->translate->addLang, '', "class='btn btn-primary'");?></p>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
