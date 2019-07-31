<?php include '../../common/view/header.html.php';?>
<?php $disabled = $active ? '' : "disabled";?>
<div class='center-block mw-800px'>
  <div id="mainMenu" class="clearfix">
  <?php printf($lang->translate->allItems, $itemCount);?>
  <div class='pull-right'>
  <?php echo html::a(inlink('setting'), "<i class='icon icon-cog'></i>", '', "class='iframe btn btn-sm btn-link $disabled'");?>
  </div>
</div>
  <div class='row'>
    <div class='col-sm-6' id='finishedLangs'>
      <div class='panel'>
        <div class='panel-heading'>
          <div class='panel-title'><?php echo $lang->translate->finishedLang;?></div>
          <div class='panel-actions'><?php printf($lang->translate->count, count($finishedLangs));?></div>
        </div>
        <div class='panel-body'>
          <?php foreach($finishedLangs as $langKey => $langName):?>
          <div class='item'>
            <div class='pull-right'>
              <?php
              if(in_array($langKey, $config->translate->defaultLang))
              {
                  echo $lang->translate->builtIn;
              }
              else
              {
                  echo html::a(inlink('chooseModule', "language=$langKey"), $lang->translate->common, '', "class='btn btn-sm $disabled'");
                  echo html::a(inlink('export', "language=$langKey"), $lang->export, '', "class='btn btn-sm iframe $disabled'");
              }
              ?>
              </div>
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
              <td class='actions thWidth'>
                <?php
                echo html::a(inlink('chooseModule', "language=$langKey"), $lang->translate->common, '', "class='btn btn-sm $disabled'");
                echo html::a(inlink('export', "language=$langKey"), $lang->export, '', "class='btn btn-sm iframe $disabled'");
                ?>
              </td>
            </tr>
            <?php endforeach;?>
          </table>
        </div>
        <p class='text-center'><?php echo html::a(inlink('addLang'), $lang->translate->addLang, '', "class='btn btn-primary $disabled'");?></p>
      </div>
    </div>
  </div>
  <?php if(!$active):?>
  <div class='alert alert-warning'><?php echo $this->lang->editor->onlyLocalVisit;?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
