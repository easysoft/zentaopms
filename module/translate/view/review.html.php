<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('chooseModule', "language=$language"), $lang->goback, '', "class='btn btn-secondary'");?>
    <div class='divider'></div>
    <?php foreach($lang->dev->groupList as $group => $groupName):?>
    <?php if(!isset($moduleGroup[$group])) continue;?>
    <?php $active = $group == $currentGroup ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('review', "language=$language&module=" . current($moduleGroup[$group])), "<span class='text'>{$groupName}</span>", '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php foreach($moduleGroup[$currentGroup] as $module):?>
        <?php echo html::a(inlink('review', "language=$language&module=$module"), zget($lang->dev->tableList, $module, $module), '', $module == $currentModule ? "class='active'" : '');?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
  <?php $hasPriv = common::hasPriv('translate', 'batchPass');?>
  <form class='main-form form-ajax' method='post' action='<?php echo inlink('batchPass');?>' data-ride="table">
      <table class='table table-bordered table-data'>
        <thead>
          <tr>
            <th class='w-80px'>
              <?php if($hasPriv):?>
              <div class='checkbox-primary check-all' title='<?php echo $this->lang->selectAll;?>'>
                <label></label>
              </div>
              <?php endif;?>
            </th>
            <th><?php echo $lang->translate->key;?></th>
            <th><?php echo $config->langs[$referLang];?>
            <th><?php echo $config->langs[$language];?></th>
            <th class='w-80px'><?php echo $lang->translate->status;?></th>
            <th class='w-90px'><?php echo $lang->actions?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;?>
          <?php foreach($referItems as $key => $item):?>
          <?php
          if(!isset($translations[$key])) continue;
          $translation = $translations[$key];
          $hasNL     = strpos($item, "\n") !== false;
          $hideClass = $this->translate->checkNeedTranslate($item) ? '' : "class='hidden'";
          if(empty($hideClass)) $i++;
          ?>
          <tr <?php echo $hideClass;?>>
            <td class='text-left'><?php echo $hasPriv ? html::checkbox('idList', array($translation->id => $i)) : $i;?></td>
            <td class='text-right'><?php echo "<nobr>$key</nobr>"?></td>
            <td><?php echo $hasNL ? nl2br(htmlspecialchars($item)) : htmlspecialchars($item);?></td>
            <td><?php echo $hasNL ? nl2br(htmlspecialchars($translation->value)) : htmlspecialchars($translation->value);?></td>
            <td class='status'>
              <?php
              echo zget($lang->translate->statusList, $translation->status);
              if($translation->status == 'rejected') echo " <span title='$translation->reason'><i class='icon icon-help'></i></span>";
              ?>
            </td>
            <td>
              <?php
              if($translation->status == 'translated' and common::hasPriv('translate', 'result'))
              {
                  echo html::a(inlink('result', "id={$translation->id}&result=pass"), $lang->translate->resultList['pass'], '', "class='pass' data-id='{$translation->id}'");
                  echo html::a(inlink('result', "id={$translation->id}&result=reject"), $lang->translate->resultList['reject'], '', "class='iframe'");
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($hasPriv):?>
      <div class="table-footer">
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar"><?php echo html::submitButton($lang->translate->resultList['pass']);?></div>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
