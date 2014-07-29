<?php
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21Z areyou123456 $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form class='form-condensed' method='post' target='hiddenwin'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix pull-left'><?php echo html::icon($lang->icons['todo']);?></span>
      <strong class='pull-left'><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->todo->batchCreate;?></strong>
      <div class='input-group w-200px pull-left' id='datepicker'>
        <span class='input-group-addon'><?php echo $lang->todo->date;?></span>
        <?php echo html::input('date', $date, "class='form-control form-date' onchange='updateAction(this.value)'");?>
        <span class='input-group-addon'><input type='checkbox' id='switchDate' onclick='switchDateTodo(this);'> <?php echo $lang->todo->periods['future'];?></span>
      </div>
    </div>
  </div>
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-30px'><?php echo $lang->idAB;?></th> 
        <th class='w-120px'><?php echo $lang->todo->type;?></th>
        <th class='w-80px'><?php echo $lang->todo->pri;?></th>
        <th class='w-p30 red'><?php echo $lang->todo->name;?></th>
        <th><?php echo $lang->todo->desc;?></th>
        <th class='w-300px'><?php echo $lang->todo->beginAndEnd;?></th>
      </tr>
    </thead>
    <?php $pri = 3;?>
    <?php for($i = 0; $i < $config->todo->batchCreate; $i++):?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("types[$i]", $lang->todo->typeList, '', "onchange='loadList(this.value, " . ($i + 1) . ")' class='form-control'");?></td>
      <td><?php echo html::select("pris[$i]", $lang->todo->priList, $pri, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'>
        <div id='<?php echo "nameBox" . ($i+1);?>' class='hidden'><?php echo html::input("names[$i]", '', 'class="text-left form-control"');?></div>
        <div class='<?php echo "nameBox" . ($i+1);?>'><?php echo html::input("names[$i]", '', 'class="text-left form-control"');?></div>
      </td>
      <td><?php echo html::textarea("descs[$i]", '', "rows='1' class='form-control'");?></td>
      <td>
        <div class='input-group'>
          <?php echo html::select("begins[$i]", $times, $time, "onchange=\"setBeginsAndEnds($i, 'begin');\" class='form-control' style='width: 50%'") . html::select("ends[$i]", $times, '', "onchange=\"setBeginsAndEnds($i, 'end');\" class='form-control' style='width: 50%'");?>
          <span class='input-group-addon'><input type='checkbox' name="switchDate[<?php echo $i?>]" id="switchDate<?php echo $i?>" onclick='switchDateList(<?php echo $i?>);'><?php echo $lang->todo->periods['future'];?></span>
        </div>
      </td>
    </tr>  
    <?php endfor;?>
    <tfoot>
      <tr><td colspan='6'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include './footer.html.php';?>
<script language='Javascript'>
var batchCreateNum = '<?php echo $config->todo->batchCreate;?>';
setBeginsAndEnds();
</script>
