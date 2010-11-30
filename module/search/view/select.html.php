<?php
/**
 * The select view of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     search
 * @version     $Id: select.html.php 942 2010-07-06 10:03:51Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<style>
.helplink {display:none}
body {background:white; margin:20px 10px 0 0; padding-bottom:0}
</style>
<script language='javascript'>
var params = <?php echo json_encode($fieldParams);?>;
var module = '<?php echo $module;?>';
/* 根据字段的参数，重新设置它对应的操作符和值。*/
function setField(fieldName, fieldNO)
{
    $('#operator' + fieldNO).val(params[fieldName]['operator']);
    htmlString = $('#box' + fieldName).html().replace(fieldName, 'value' + fieldNO).replace(fieldName, 'value' + fieldNO);
    $('#valueBox' + fieldNO).html(htmlString);
}
function setSelected(result)
{
    if(module == 'story') parent.$('#story').val(result);
    else if(module == 'task') parent.$('#task').val(result);
    parent.$.fn.colorbox.close();
}
</script>

<div class='hidden'>
<?php
/* 输出所有字段的控件代码，当触发setField事件的时候，拷贝控件的html代码。*/
foreach($fieldParams as $fieldName => $param)
{
    echo "<span id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select($fieldName, $param['values'], '', 'class=select-4');
    if($param['control'] == 'input') echo html::input($fieldName, '', 'class=text-4');
    echo '</span>';
}
?>
</div>
<form method='post'>
<table align='center' class='table-5 tablesorter fixed'>
  <nobr>
  <?php
  $formSessionName = $module . 'Form';
  $formSession     = $this->session->$formSessionName;
  $fieldNO = 1;

  /* 取当前字段的设置。*/
  $currentField = $formSession["field$fieldNO"];
  $param        = $fieldParams[$currentField];
  /* 打印字段。*/
  echo html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)'");

  /* 打印操作符。*/
  echo html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"]);

  /* 打印值。*/
  echo "<span id='valueBox$fieldNO'>";
  if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], 'class=select-4');
  if($param['control'] == 'input') echo html::input("value$fieldNO",  $formSession["value$fieldNO"], 'class=text-4');
  echo '</span>';
  ?>
  </nobr>
  <?php echo html::submitButton($lang->search->common);?>
  <thead>
    <tr class='colhead'>
      <th><?php echo $lang->search->{$module . 'Title'};?></th>
      <th class='w-100px {sorter:false}'><?php echo $lang->search->select;?></th>
    </tr> 
  </thead>
  <tbody>
  <?php foreach($moduleTitles as $id => $title):?>
  <?php if(!$title) continue;?>    
    <tr class='a-center'>
      <td class='a-left nobr'><nobr><?php echo $title;?></nobr></td>
      <td><input type='radio' name='moduleTitle' onclick=setSelected(this.value) value='<?php echo $id;?>' <?php if($id == $moduleID) echo "checked='checked'";?>></td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
</form>
