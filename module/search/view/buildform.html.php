<?php
/**
 * The edit view of search module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.helplink {display:none}
.button-s, .button-r, .button-c {padding:3px} 
.select-1 {width:80%}
</style>
<script language='Javascript'>

var params        = <?php echo json_encode($fieldParams);?>;
var groupItems    = <?php echo $config->search->groupItems;?>;
var setQueryTitle = '<?php echo $lang->search->setQueryTitle;?>';
var module        = '<?php echo $module;?>';
var actionURL     = '<?php echo $actionURL;?>';

/* 根据字段的参数，重新设置它对应的操作符和值。*/
function setField(fieldName, fieldNO)
{
    $('#operator' + fieldNO).val(params[fieldName]['operator']);
    htmlString = $('#box' + fieldName).html().replace(fieldName, 'value' + fieldNO).replace(fieldName, 'value' + fieldNO);
    $('#valueBox' + fieldNO).html(htmlString);
}

/* 重置表单。*/
function resetForm()
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        $('#value' + i).val('');
    }
}

/* 显示更多的搜索选项。*/
function showmore()
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        if(i != 1 && i != groupItems + 1 )
        {
            $('#searchbox' + i).removeClass('hidden');
        }
    }

    $('#searchmore').addClass('hidden');
    $('#searchlite').removeClass('hidden');
    $('#formType').val('more');
}

/* 显示简洁的搜索选项。*/
function showlite()
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        if(i != 1 && i != groupItems + 1)
        {
            $('#value' + i).val('');
            $('#searchbox' + i).addClass('hidden');
        }
    }
    $('#searchmore').removeClass('hidden');
    $('#searchlite').addClass('hidden');
    $('#formType').val('lite');
}

/* 保存用户设定的查询条件。*/
function saveQuery()
{
    jPrompt(setQueryTitle, '', '', function(r) 
    {
        if(!r) return;
        saveQueryLink = createLink('search', 'saveQuery');
        $.post(saveQueryLink, {title: r, module: module}, function(data)
        {
            if(data == 'success') location.reload();
        });
    });
}

/* 执行用户选中的query。*/
function executeQuery(queryID)
{
    if(!queryID) return;
    location.href = actionURL.replace('myQueryID', queryID);
}

/* 删除Query。*/
function deleteQuery()
{
    queryID = $('#queryID').val();
    if(!queryID) return;
    hiddenwin.location.href = createLink('search', 'deleteQuery', 'queryID=' + queryID);
}

</script>

<div class='hidden'>
<?php
/* 输出所有字段的控件代码，当触发setField事件的时候，拷贝控件的html代码。*/
foreach($fieldParams as $fieldName => $param)
{
    echo "<span id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select($fieldName, $param['values'], '', 'class=select-2');
    if($param['control'] == 'input') echo html::input($fieldName, '', 'class=text-2');
    echo '</span>';
}
?>
</div>
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='searchform'>
<table class='table-1'>
  <tr valign='middle'>
    <th width='10' class='bg-gray'><?php echo $lang->search->common;?></th>
    <td class='a-right'>
      <nobr>
      <?php
      $formSessionName = $module . 'Form';
      $formSession     = $this->session->$formSessionName;

      $fieldNO = 1;
      for($i = 1; $i <= $groupItems; $i ++)
      {
          $spanClass = $i == 1 ? 'inline' : 'hidden';
          echo "<span id='searchbox$fieldNO' class='$spanClass'>";

          /* 取当前字段的设置。*/
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* 打印and or。*/
          if($i == 1) echo "<span id='searchgroup1'><strong>{$lang->search->group1}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo "<br />" . html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"]);

          /* 打印字段。*/
          echo html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)'");

          /* 打印操作符。*/
          echo html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"]);

          /* 打印值。*/
          echo "<span id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], 'class=select-2');
          if($param['control'] == 'input') echo html::input("value$fieldNO",  $formSession["value$fieldNO"], 'class=text-2');
          echo '</span>';

          $fieldNO ++;
          echo '</span>';
      }
      ?>
      </nobr>
    </td>
    <td class='a-center' width='60'><nobr><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'])?></nobr></td>
    <td class='a-right'>
      <nobr>
      <?php
      for($i = 1; $i <= $groupItems; $i ++)
      {
          $spanClass = $i == 1 ? 'inline' : 'hidden';
          echo "<span id='searchbox$fieldNO' class='$spanClass'>";

          /* 取当前字段的设置。*/
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* 打印and or。*/
          if($i == 1) echo "<span id='searchgroup2'><strong>{$lang->search->group2}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo "<br />" . html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"]);

          /* 打印字段。*/
          echo html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)'");

          /* 打印操作符。*/
          echo html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"]);

          /* 打印值。*/
          echo "<span id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], 'class=select-2');
          if($param['control'] == 'input') echo html::input("value$fieldNO",  $formSession["value$fieldNO"], 'class=text-2');
          echo '</span>';

          $fieldNO ++;
          echo '</span>';
      }
      ?>
      </nobr>
    </td>
    <td width='100'> 
      <nobr>
      <?php
      echo html::hidden('module',     $module);
      echo html::hidden('actionURL',  $actionURL);
      echo html::hidden('groupItems', $groupItems);
      echo html::submitButton($lang->search->common);
      echo html::commonButton($lang->search->reset, 'onclick=resetForm();');
      echo html::commonButton($lang->save, 'onclick=saveQuery()');
      ?>
      </nobr>
    </td>
    <td width='250' class='a-center'>
      <?php
      echo html::select('queryID', $queries, $queryID, 'class=select-1 onchange=executeQuery(this.value)');
      if(common::hasPriv('search', 'deleteQuery')) echo html::commonButton(' x ', 'onclick=deleteQuery();');
      ?>
    </td>
    <th width='10' class='bg-gray' style='cursor:pointer; padding:0'>
      <span id='searchmore' onclick='showmore()' style='width:100%; height:100%'><?php echo $lang->search->more;?></span>
      <span id='searchlite' onclick='showlite()' style='width:100%; height:100%' class='hidden'><?php echo $lang->search->lite;?></span>
      <?php echo html::hidden('formType', 'lite');?>
    </th>
  </tr>
</table>
</form>
<script language='Javascript'>
<?php if(isset($formSession['formType'])) echo "show{$formSession['formType']}()";?>
</script>
