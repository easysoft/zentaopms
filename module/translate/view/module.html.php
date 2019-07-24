<?php include '../../common/view/header.html.php';?>
<?php if($cmd):?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='article-content text-danger'><?php printf($lang->translate->notice->failDirPriv, $cmd);?></div>
    <hr>
    <?php echo html::commonButton($lang->translate->refreshPage, 'onclick=location.href=location.href', 'btn btn-primary');?>
  </div>
</div>
<?php else:?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::a(inlink('chooseModule', "language=$language"), $lang->goback, '', "class='btn btn-secondary'");?>
    <div class='divider'></div>
    <?php foreach($lang->dev->groupList as $group => $groupName):?>
    <?php if(!isset($moduleGroup[$group])) continue;?>
    <?php $active = $group == $currentGroup ? 'btn-active-text' : '';?>
    <?php echo html::a(inlink('module', "language=$language&module=" . current($moduleGroup[$group])), "<span class='text'>{$groupName}</span>", '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <?php $isSplit = $this->cookie->translateView == 'split';?>
      <?php echo html::a('javascript:setTranslateView("unified")', "<i class='icon icon-bars'></i>", '', "class='btn btn-icon" . (!$isSplit ? " text-primary" : '') . "'");?>
      <?php echo html::a('javascript:setTranslateView("split")', "<i class='icon icon-columns'></i>", '', "class='btn btn-icon" . ($isSplit ? " text-primary" : '') . "'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php foreach($moduleGroup[$currentGroup] as $module):?>
        <?php echo html::a(inlink('module', "language=$language&module=$module"), zget($lang->dev->tableList, $module, $module), '', $module == $currentModule ? "class='active'" : '');?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class='main-form form-ajax' method='post' data-ride="table">
      <table class='table table-bordered table-data'>
        <thead>
          <tr>
            <th class='w-50px'></th>
            <th><?php echo $lang->translate->key;?></th>
            <?php if($isSplit):?>
            <th><?php echo $config->langs[$referLang];?></th>
            <th><?php echo $config->langs[$language];?></th>
            <?php else:?>
            <th><?php echo $config->langs[$referLang] . ' / ' . $config->langs[$language];?></th>
            <?php endif;;?>
            <th class='w-80px'><?php echo $lang->translate->status;?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;?>
          <?php foreach($referItems as $key => $item):?>
          <?php
          $hasNL     = strpos($item, "\n") !== false;
          $hideClass = $this->translate->checkNeedTranslate($item) ? '' : "class='hidden'";
          if(empty($hideClass)) $i++;

          $hiddenKey = "value='{$key}'";
          if(strpos($key, "'") !== false) $hiddenKey = 'value="' . $key . '"';
          $hiddenKey = '<input type="hidden" name="keys[]" id="keys[]" ' . $hiddenKey . '>';
          ?>
          <?php if($isSplit):?>
          <tr <?php echo $hideClass;?>>
            <td class='text-right'><?php echo $i;?></td>
            <td class='text-right'><?php echo "<nobr>$key</nobr>" . $hiddenKey;?></td>
            <td>
              <?php
              $specialItem = htmlspecialchars($item);
              echo $hasNL ? nl2br($specialItem) : $specialItem;
              echo html::hidden('refers[]', $specialItem);
              ?>
            </td>
            <?php $translation = zget($translations, $key, '');?>
            <td class='translated'>
            <?php
            $function = $hasNL ? 'textarea' : 'input';
            $value    = $translation;
            if(is_object($translation)) $value = $translation->value;
            echo html::$function('values[]', ($value != $item) ? htmlspecialchars($value) : '', "class='form-control'");
            ?>
            </td>
            <td>
              <?php
              if(is_object($translation))
              {
                  echo zget($lang->translate->statusList, $translation->status);
                  if($translation->status == 'rejected') echo " <span title='$translation->reason'><i class='icon icon-help'></i></span>";
              }
              ?>
            </td>
          </tr>
          <?php else:?>
          <tr <?php echo $hideClass;?>>
            <?php $translation = zget($translations, $key, '');?>
            <td class='text-right' rowspan='2'><?php echo $i;?></td>
            <td class='text-right' rowspan='2'><?php echo "<nobr>$key</nobr>" . $hiddenKey;?></td>
            <td>
              <?php
              $specialItem = htmlspecialchars($item);
              echo $hasNL ? nl2br($specialItem) : (!empty($specialItem) ? $specialItem : '&nbsp;');
              echo html::hidden('refers[]', $specialItem);
              ?>
            </td>
            <td rowspan='2'>
              <?php
              if(is_object($translation))
              {
                  echo zget($lang->translate->statusList, $translation->status);
                  if($translation->status == 'rejected') echo " <span title='$translation->reason'><i class='icon icon-help'></i></span>";
              }
              ?>
            </td>
          </tr>
          <tr <?php echo $hideClass;?>>
            <td class='translated'>
            <?php
            $function = $hasNL ? 'textarea' : 'input';
            $value    = $translation;
            if(is_object($translation)) $value = $translation->value;
            echo html::$function('values[]', ($value != $item) ? htmlspecialchars($value) : '', "class='form-control'");
            ?>
            </td>
          </tr>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class="table-footer text-center"><?php echo html::submitButton() . ' ' . html::a(inlink('chooseModule', "language=$language"), $lang->goback, '', "class='btn btn-wide'");?></div>
    </form>
  </div>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
