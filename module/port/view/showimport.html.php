<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<style>
form{overflow-x: scroll}
</style>
<?php if(isset($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php elseif(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->task->import;?></h2>
  </div>
  <p><?php echo sprintf($lang->file->importSummary, $allCount, html::input('maxImport', $config->file->maxImport, "style='width:50px'"), ceil($allCount / $config->file->maxImport));?></p>
  <p><?php echo html::commonButton($lang->import, "id='import'", 'btn btn-primary');?></p>
</div>
<script>
$(function()
{
    $('#maxImport').keyup(function()
    {
        if(parseInt($('#maxImport').val())) $('#times').html(Math.ceil(parseInt($('#allCount').html()) / parseInt($('#maxImport').val())));
    });
    $('#import').click(function(){location.href = createLink('task', 'showImport', "executionID=<?php echo $executionID;?>&pageID=1&maxImport=" + $('#maxImport').val())})
});
</script>
<?php else:?>
<?php js::set('requiredFields', $requiredFields);?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->task->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'>                          <?php echo $lang->task->id?></th>
          <th class='w-150px'         id='name'>       <?php echo $lang->task->name?></th>
          <th class='w-150px'         id='module' >    <?php echo $lang->task->module?></th>
          <th class='w-150px'         id='story'>      <?php echo $lang->task->story?></th>
          <th class='w-120px'         id='assignedTo'> <?php echo $lang->task->assignedTo?></th>
          <th class='w-70px'          id='pri'>        <?php echo $lang->task->pri?></th>
          <th class='w-90px'          id='type'>       <?php echo $lang->task->type?></th>
          <th class='estimate w-80px' id='estimate'>   <?php echo $lang->task->estimate?></th>
          <th class='w-100px'         id='estStarted'> <?php echo $lang->task->estStarted?></th>
          <th class='w-100px'         id='deadline'>   <?php echo $lang->task->deadline?></th>
          <th class='w-300px'         id='desc'>       <?php echo $lang->task->desc?></th>
          <?php
          if(!empty($appendFields))
          {
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;

                  $width    = ($field->width && $field->width != 'auto' ? $field->width . 'px' : 'auto');
                  $required = strpos(",$field->rules,", ",$notEmptyRule->id,") !== false ? 'required' : '';
                  echo "<th class='$required' style='width: $width'>$field->name</th>";
              }
          }
          ?>
        </tr>
      </thead>
      <tbody>
        <?php
        $insert      = true;
        $addID       = 1;
        $hasMultiple = false;
        ?>
        <?php foreach($taskData as $key => $task):?>
        <tr class='text-top'>
          <td>
            <?php
            if(!empty($task->id))
            {
                echo $task->id . html::hidden("id[$key]", $task->id);
                $insert = false;
            }
            else
            {
                $sub = (strpos($task->name, '>') === 0) ? " <sub style='vertical-align:sub;color:red'>{$lang->task->children}</sub>" : " <sub style='vertical-align:sub;color:gray'>{$lang->task->new}</sub>";
                echo $addID++ . $sub;
            }
            echo html::hidden("execution[$key]", $executionID);
            ?>
          </td>
          <td><?php echo html::input("name[$key]", htmlspecialchars($task->name, ENT_QUOTES), "class='form-control'")?></td>
          <td style='overflow:visible'><?php echo html::select("module[$key]", $modules, !empty($task->module) ? $task->module : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->module : ''), "class='form-control chosen'")?></td>
          <td style='overflow:visible'><?php echo html::select("story[$key]", $stories, !empty($task->story) ? $task->story : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->story : ''), "class='form-control chosen'")?></td>
          <td style='overflow:visible'><?php echo html::select("assignedTo[$key]", $members, !empty($task->assignedTo) ? $task->assignedTo: ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->assignedTo : ''), "class='form-control chosen'")?></td>
          <td><?php echo html::select("pri[$key]", $lang->task->priList, !empty($task->pri) ? $task->pri : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->pri : ''), "class='form-control'")?></td>
          <td><?php echo html::select("type[$key]", $lang->task->typeList, !empty($task->type) ? $task->type : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->type : ''), "class='form-control'")?></td>
          <td>
          <?php
          if(is_array($task->estimate))
          {
              echo "<table class='table-borderless'>";
              foreach($task->estimate as $account => $estimate)
              {
                  echo '<tr>';
                  echo '<td class="w-150px">' . html::select("team[$key][]", $members, $account, "class='form-control chosen'") . '</td>';
                  echo '<td>' . html::input("estimate[$key][]", $estimate, "class='form-control' autocomplete='off'")  . '</td>';
                  echo '</tr>';
              }
              echo "</table>";
          }
          else
          {
              echo html::input("estimate[$key]", !empty($task->estimate) ? $task->estimate : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->estimate : ''), "class='form-control' autocomplete='off'");
          }
          ?>
          </td>
          <?php $dateMatch = '/[1-9]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])/';?>
          <?php if(!preg_match($dateMatch, $task->estStarted)) $task->estStarted = ''; ?>
          <td><?php echo html::input("estStarted[$key]", !empty($task->estStarted) ? $task->estStarted : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->estStarted : ''), "class='form-control form-date autocomplete='off'")?></td>
          <?php if(!preg_match($dateMatch, $task->deadline)) $task->deadline = '';?>
          <td><?php echo html::input("deadline[$key]", !empty($task->deadline) ? $task->deadline : ((!empty($task->id) and isset($tasks[$task->id])) ? $tasks[$task->id]->deadline : ''), "class='form-control form-date'")?></td>
          <td><?php echo html::textarea("desc[$key]", $task->desc, "class='form-control' cols='50' rows='1'")?></td>
          <?php
          if(!empty($appendFields))
          {
              $this->loadModel('flow');
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;
                  $value = $field->defaultValue ? $field->defaultValue : zget($task, $field->field, '');
                  echo '<td>' . $this->flow->buildControl($field, $value, "$field->field[$key]", true) . '</td>';
              }
          }
          ?>
          <?php if(!empty($task->multiple)) $hasMultiple = true;?>
          <?php echo html::hidden("multiple[$key]", !empty($task->multiple) ? 1 : 0);?>
          <?php echo html::hidden("mode[$key]", !empty($task->multiple) ? $task->mode : '');?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='10' class='text-center form-actions'>
            <?php
            $submitText  = $isEndPage ? $this->lang->save : $this->lang->file->saveAndNext;
            $isStartPage = $pagerID == 1 ? true : false;
            if(!$insert and $dataInsert === '')
            {
                echo "<button type='button' data-toggle='modal' data-target='#importNoticeModal' class='btn btn-primary btn-wide'>{$submitText}</button>";
            }
            else
            {
                echo html::submitButton($submitText);
                if($dataInsert !== '') echo html::hidden('insert', $dataInsert);
            }
            echo html::hidden('isEndPage', $isEndPage ? 1 : 0);
            echo html::hidden('pagerID', $pagerID);
            echo ' &nbsp; ' . html::a($this->createLink('execution', 'task', "executionID=$executionID"), $lang->goback, '', "class='btn btn-back btn-wide'");
            echo ' &nbsp; ' . sprintf($lang->file->importPager, $allCount, $pagerID, $allPager);
            ?>
          </td>
        </tr>
      </tfoot>
    </table>
    <?php if(!$insert and $dataInsert === '') include $app->getModuleRoot() . 'common/view/noticeimport.html.php';?>
  </form>
</div>
<?php endif;?>
<script>
$(function()
{
    $.fixedTableHead('#showData');
    <?php if($hasMultiple):?>
    $('th.estimate').addClass('text-center w-250px');
    <?php endif;?>
    $("#showData th").each(function()
    {
        if(requiredFields.indexOf(this.id) !== -1) $("#" + this.id).addClass('required');
    });
});
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
