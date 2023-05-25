<?php
$lang->custom->object = array();
$lang->custom->system = array();
include $app->getModuleRoot() . 'common/view/header.html.php';
js::set('object', $object);
?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php include './menu.html.php';?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php
        foreach($lang->custom->tipProgressList as $key => $value)
        {
            echo html::a(inlink('setTips', "type=progress&object=$key"), $value, '', " id='{$key}Tab'");
        }
        foreach($lang->custom->tipCostList as $key => $value)
        {
            echo html::a(inlink('setTips', "type=cost&object=$key"), $value, '', " id='{$key}Tab'");
        }
        ?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <table class='table table-form mw-900px'>
        <thead>
          <tr class='text-center'>
            <th class='w-200px'><?php echo $lang->custom->region;?></th>
            <th class='w-150px'><?php echo $lang->custom->isRange;?></th>
            <th><?php echo $lang->custom->tips;?></th>
            <th class="w-90px"> <?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0;?>
          <?php if(!empty($tipsConfig)):?>
          <?php foreach($tipsConfig as $key => $tipConfig):?>
          <?php if($tipConfig->type != $object) continue;?>
          <tr class='addedItem'>
            <td>
              <div class='input-group'>
                <input type='text' name='mins[]' id='maxs<?php echo $i;?>' value='<?php echo $tipConfig->min?>' class='form-control' />
                <span class='input-group-addon'><?php echo $lang->dash;?></span>
                <input type='text' name='maxs[]' id='maxs<?php echo $i;?>' value='<?php echo $tipConfig->max?>' class='form-control' />
              </div>
            </td>
            <td class='text-center'><?php echo html::radio("ranges[$i]", $lang->custom->tipRangeList, $tipConfig->range ? $tipConfig->range : 0);?></td>
            <td><input type='text' name='tips[]' id='tips<?php echo $i;?>' value='<?php echo $tipConfig->tip?>' class='form-control' /></td>
            <td class='c-actions text-center'>
              <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>

          <?php for($j = 0; $j < 2; $j ++):?>
          <tr class='addedItem'>
            <td>
              <div class='input-group'>
                <input type='text' name='mins[]' id='maxs<?php echo $i;?>' value='' class='form-control' />
                <span class='input-group-addon'><?php echo $lang->dash;?></span>
                <input type='text' name='maxs[]' id='maxs<?php echo $i;?>' value='' class='form-control' />
              </div>
            </td>
            <td class='text-center'><?php echo html::radio("ranges[$i]", $lang->custom->tipRangeList, 0);?></td>
            <td><input type='text' name='tips[]' id='tips<?php echo $i;?>' value='' class='form-control' /></td>
            <td class='c-actions text-center'>
              <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
              <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
            </td>
          </tr>
          <?php $i ++;?>
          <?php endfor;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='3' class='text-center'><?php echo html::submitButton();?></td>
          </tr>
        </tfoot>
      </table>
      <?php js::set('i', $i);?>
    </form>

    <?php $i = '%i%';?>
    <table class='hidden'>
      <tr id='addItem' class='hidden'>
        <td>
          <div class='input-group'>
            <input type='text' name='mins[]' id='mins<?php echo $i;?>' class='form-control' />
            <span class='input-group-addon'><?php echo $lang->dash;?></span>
            <input type='text' name='maxs[]' id='maxs<?php echo $i;?>' class='form-control' />
          </div>
        </td>
        <td class='text-center'><?php echo html::radio("ranges[$i]", $lang->custom->tipRangeList, 0);?></td>
        <td><input type='text' name='tips[]' id='tips<?php echo $i;?>' class='form-control' /></td>
        <td class='c-actions text-center'>
          <a href='javascript:;' onclick='addItem(this)' class='btn btn-link'><i class='icon-plus'></i></a>
          <a href='javascript:;' onclick='deleteItem(this)' class='btn btn-link'><i class='icon icon-close'></i></a>
        </td>
      </tr>
    </table>
  </div>
</div>
<script>
$('#' + object + 'Tab').addClass('active');
function addItem(obj)
{
    var item = $('#addItem').html().replace(/%i%/g, i);
    $(obj).closest('tr').after('<tr class="addedItem">' + item  + '</tr>');
    i ++;
}

function deleteItem(obj)
{
    $(obj).closest('tr').remove();
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.html.php';?>
