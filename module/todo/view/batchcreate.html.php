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
  <table class='table-1 fixed'> 
    <caption>
      <div class='f-left'><?php echo $lang->todo->batchCreate . $lang->colon;?></div>
      <?php echo html::input('date', $date, "class='select-2 date'");?>
    </caption>
    <tr>
      <th class='w-20px'><?php echo $lang->idAB;?></th> 
      <th class='w-100px'><?php echo $lang->todo->type;?></th>
      <th class='w-70px'><?php echo $lang->todo->pri;?></th>
      <th class='w-p40'><?php echo $lang->todo->name;?></th>
      <th class='w-p30'><?php echo $lang->todo->desc;?></th>
      <th class='w-150px'><?php echo $lang->todo->beginAndEnd;?></th>
    </tr>

    <?php $pri = 3;?>
    <?php for($i = 0; $i < $config->todo->batchCreate; $i++):?>
    <tr class='a-center'>
      <td><?php echo $i+1;?></td>
      <td><?php echo html::select("types[$i]", $lang->todo->typeList, '', "onchange=loadList(this.value,$i+1) class='select-1'");?></td>
      <td><?php echo html::select("pris[$i]", $lang->todo->priList, $pri, 'class=select-1');?></td>
      <td>
        <div id='<?php echo "nameBox" . ($i+1);?>' class='hidden'><?php echo html::input("names[$i]", '', 'class="f-left text-1"'); echo "<span class='star'>*</span>";?></div>
        <div class='<?php echo "nameBox" . ($i+1);?>'><?php echo html::input("names[$i]", '', 'class="f-left text-1"'); echo "<span class='star'>*</span>";?></div>
      </td>
      <td><?php echo html::textarea("descs[$i]", '', "rows='2' class=text-1");?></td>
      <td><?php echo html::select('begins[]', $times, '') . html::select('ends[]', $times, '');?><td>
    </tr>  
    <?php endfor;?>
    <tr>
      <td colspan='6'>
        <div class='half-left red'><?php echo $lang->todo->notes;?></div>
        <div class='half-right'><?php echo html::submitButton() . html::resetButton();?></div>
      </td>
    </tr>
  </table>
</form>
<?php include './footer.html.php';?>
<script language='Javascript'>selectNext();</script>
