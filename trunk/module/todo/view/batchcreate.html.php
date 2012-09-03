<?php
/**
 * The batch create view of todo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     todo
 * @version     $Id: create.html.php 2741 2012-04-07 07:24:21Z areyou123456 $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post'>
  <table class='table-1' > 
    <caption> <?php echo $lang->todo->batchCreate . $lang->colon . html::input('date', $date, "class='select-2 date' onchange='updateAction(this.value)'");?>
    <input type='checkbox' id='switchDate' onclick='switchDateTodo(this);'><?php echo $lang->todo->futureTodos;?></caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-100px'><?php echo $lang->todo->type;?></th>
      <th class='w-70px'><?php echo $lang->todo->pri;?></th>
      <th class='red'><?php echo $lang->todo->name;?></th>
      <th><?php echo $lang->todo->desc;?></th>
      <th class='w-150px'><?php echo $lang->todo->beginAndEnd;?></th>
    </tr>

    <?php $pri = 3;?>
    <?php for($i = 0; $i < $config->todo->batchCreate; $i++):?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("types[$i]", $lang->todo->typeList, '', "onchange=loadList(this.value,$i+1) class='select-1'");?></td>
      <td><?php echo html::select("pris[$i]", $lang->todo->priList, $pri, 'class=select-1');?></td>
      <td>
        <div id='<?php echo "nameBox" . ($i+1);?>' class='hidden'><?php echo html::input("names[$i]", '', 'class="f-left text-1"');?></div>
        <div class='<?php echo "nameBox" . ($i+1);?>'><?php echo html::input("names[$i]", '', 'class="f-left text-1"');?></div>
      </td>
      <td><?php echo html::textarea("descs[$i]", '', "rows='1' class=text-1");?></td>
      <td>
        <?php 
        echo html::select("begins[$i]", $times, $time, "onchange=setBeginsAndEnds($i,'begin')");
        echo html::select("ends[$i]",   $times, '', "onchange=setBeginsAndEnds($i,'end')");
        ?>
      <td>
    </tr>  
    <?php endfor;?>
    <tr><td colspan='6' class='a-center'><?php echo html::submitButton() . html::resetButton();?></td></tr>
  </table>
</form>
<?php include './footer.html.php';?>
<script language='Javascript'>
var batchCreateNum = '<?php echo $config->todo->batchCreate;?>';
setBeginsAndEnds();
</script>
