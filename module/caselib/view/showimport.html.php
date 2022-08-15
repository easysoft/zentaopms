<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php
$requiredFields = $datas->requiredFields;
$allCount       = $datas->allCount;
$allPager       = $datas->allPager;
$pagerID        = $datas->pagerID;
$isEndPage      = $datas->isEndPage;
$maxImport      = $datas->maxImport;
$dataInsert     = $datas->dataInsert;
$fields         = $datas->fields;
$suhosinInfo    = $datas->suhosinInfo;
$model          = $datas->model;
$datas          = $datas->datas;
?>
<style>
form{overflow-x: scroll}
thead > tr > th{width:150px;}
.c-estimate {width:150px;}
.c-story{width:150px;}
.c-step{width:400px;}
.c-pri,.c-stage{width:150px}
.c-precondition{width:200px;}
</style>
<?php if(!empty($suhosinInfo)):?>
<div class='alert alert-info'><?php echo $suhosinInfo?></div>
<?php elseif(empty($maxImport) and $allCount > $this->config->file->maxImport):?>
<div id="mainContent" class="main-content">
  <div class="main-header">
    <h2><?php echo $lang->port->import;?></h2>
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
    $('#import').click(function()
    {
        $.cookie('maxImport', $('#maxImport').val());
        location.href = createLink('caselib', 'showImport', "libID=<?php echo $libID;?>&pageID=1&maxImport=" + $('#maxImport').val())
    });
});
</script>
<?php else:?>
<?php js::set('requiredFields', $requiredFields);?>
<div id="mainContent" class="main-content">
  <div class="main-header clearfix">
    <h2><?php echo $lang->port->import;?></h2>
  </div>
  <form class='main-form' target='hiddenwin' method='post'>
    <table class='table table-form' id='showData'>
      <thead>
        <tr>
          <th class='w-70px'> <?php echo $lang->port->id?></th>

          <?php foreach($fields as $key => $value):?>

          <?php if($key == 'stepDesc' or $key == 'stepExpect'):?>
          <?php if($key == 'stepExpect') continue;?>
          <th class='c-step'>
            <table class='w-p100 table-borderless'>
              <tr>
                <th class="no-padding"><?php echo $fields['stepDesc']['title']?></th>
                <th class="no-padding"><?php echo $fields['stepExpect']['title']?></th>
              </tr>
            </table>
          </th>

          <?php elseif($value['control'] != 'hidden'):?>
          <th class='c-<?php echo $key?>'  id='<?php echo $key;?>'>  <?php echo $value['title'];?></th>

          <?php endif;?>
          <?php endforeach;?>
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
        <?php foreach($datas as $key => $object):?>
        <tr class='text-top'>
          <td>
            <?php
            if(!empty($object->id))
            {
                echo $object->id . html::hidden("id[$key]", $object->id);
                $insert = false;
            }
            else
            {
                $sub = (strpos($object->title, '>') === 0) ? " <sub style='vertical-align:sub;color:red'>{$lang->port->children}</sub>" : " <sub style='vertical-align:sub;color:gray'>{$lang->port->new}</sub>";
                echo $addID++ . $sub;
            }
            echo html::hidden("lib[$key]", $libID);
            ?>
          </td>
          <?php foreach($fields as $field => $value):?>

          <?php if($value['control'] == 'select'):?>
          <td><?php echo html::select("{$field}[$key]", $value['values'], !empty($object->$field) ? $object->$field : '', "class='form-control chosen'")?></td>

          <?php elseif($value['control'] == 'multiple'):?>
          <?php if(!isset($value['values'][''])) $value['values'][''] = '';?>
          <td><?php echo html::select("{$field}[$key][]", $value['values'], !empty($object->$field) ? $object->$field : '', "multiple class='form-control chosen'")?></td>

          <?php elseif($value['control'] == 'date'):?>
          <?php $dateMatch = $this->config->port->dateMatch;?>
          <?php if(!preg_match($dateMatch, $object->$field)) $object->$field = ''; ?>
          <td><?php echo html::input("{$field}[$key]", !empty($object->$field) ? $object->$field : '', "class='form-control form-date autocomplete='off'")?></td>

          <?php elseif($value['control'] == 'datetime'):?>
          <td><?php echo html::input("{$field}[$key]", !empty($object->$field) ? $object->$field : '', "class='form-control form-datetime autocomplete='off'")?></td>

          <?php elseif($value['control'] == 'hidden'):?>
          <?php echo html::hidden("{$field}[$key]", $object->$field)?>

          <?php elseif($value['control'] == 'textarea'):?>
          <td><?php echo html::textarea("{$field}[$key]", $object->$field, "class='form-control' cols='50' rows='1'")?></td>

          <?php elseif($field == 'stepDesc' or $field == 'stepExpect'):?>
          <?php if($field == 'stepExpect') continue;?>
          <td>
              <?php if(isset($stepData[$key]['desc'])):?>
              <table class='w-p100 bd-0'>
              <?php $hasStep = false;?>
              <?php foreach($stepData[$key]['desc'] as $id => $desc):?>
              <?php if(empty($desc['content'])) continue;?>
                <tr class='step'>
                  <?php $hasStep = true;?>
                  <td><?php echo $id . html::hidden("stepType[$key][$id]", $desc['type'])?></td>
                  <td><?php echo html::textarea("desc[$key][$id]", htmlSpecialString($desc['content']), "class='form-control'")?></td>
                  <td><?php if($desc['type'] != 'group') echo html::textarea("expect[$key][$id]", isset($stepData[$key]['expect'][$id]['content']) ? htmlSpecialString($stepData[$key]['expect'][$id]['content']) : '', "class='form-control'")?></td>
                </tr>
              <?php endforeach;?>
              <?php if(!$hasStep):?>
                <tr class='step'>
                  <td><?php echo '1' . html::hidden("stepType[$key][1]", 'step')?></td>
                  <td><?php echo html::textarea("desc[$key][1]", '', "class='form-control'")?></td>
                  <td><?php echo html::textarea("expect[$key][1]", '', "class='form-control'")?></td>
                </tr>
              <?php endif;?>
              </table>
              <?php else:?>
              <table class='w-p100 bd-0'>
                <tr class='step'>
                  <td><?php echo '1' . html::hidden("stepType[$key][1]", 'step')?></td>
                  <td><?php echo html::textarea("desc[$key][1]", '', "class='form-control'")?></td>
                  <td><?php echo html::textarea("expect[$key][1]", '', "class='form-control'")?></td>
                </tr>
              </table>
              <?php endif;?>
          </td>

          <?php else:?>
          <td><?php echo html::input("{$field}[$key]", !empty($object->$field) ? $object->$field : '', "class='form-control autocomplete='off'")?></td>
          <?php endif;?>

          <?php endforeach;?>
          <?php
          if(!empty($appendFields))
          {
              $this->loadModel('flow');
              foreach($appendFields as $field)
              {
                  if(!$field->show) continue;
                  $value = $field->defaultValue ? $field->defaultValue : zget($datas, $field->field, '');
                  echo '<td>' . $this->flow->buildControl($field, $value, "$field->field[$key]", true) . '</td>';
              }
          }
          ?>
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
            echo ' &nbsp; ' . html::a("javascript:history.back(-1)", $lang->goback, '', "class='btn btn-back btn-wide'");
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
