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
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<script language='Javascript'>
var params = <?php echo json_encode($fieldParams);?>;
/* 根据字段的参数，重新设置它对应的操作符和值。*/
function setField(fieldName, fieldNO)
{
    $('#operator' + fieldNO).val(params[fieldName]['operator']);
    htmlString = $('#box' + fieldName).html().replace(fieldName, 'value' + fieldNO).replace(fieldName, 'value' + fieldNO);
    $('#valueBox' + fieldNO).html(htmlString);
}
</script>

<div style='display:none'>
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
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin'>
<table class='table-1'>
  <caption><?php echo $lang->search->common;?></caption>
  <tr valign='middle'>
    <td class='a-right' width='100'>
      <nobr>
      <?php
      $formSessionName = $module . 'Form';
      $formSession     = $this->session->$formSessionName;

      $fieldNO = 1;
      for($i = 1; $i <= $groupItems; $i ++)
      {
          /* 取当前字段的设置。*/
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* 打印and or。*/
          if($i == 1) echo "<strong>{$lang->search->group1}</strong>" . html::hidden("andOr$fieldNO", 'AND');
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
      }
      ?>
      </nobr>
    </td>
    <td class='a-center' width='20'><nobr><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'])?></nobr></td>
    <td class='a-right' width='100'>
      <nobr>
      <?php
      for($i = 1; $i <= $groupItems; $i ++)
      {
          /* 取当前字段的设置。*/
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* 打印and or。*/
          if($i == 1) echo "<strong>{$lang->search->group2}</strong>" . html::hidden("andOr$fieldNO", 'AND');
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
      }
      ?>
      </nobr>
    </td>
    <td width='20'> 
      <nobr>
      <?php
      echo html::hidden('module',     $module);
      echo html::hidden('actionURL',  $actionURL);
      echo html::hidden('groupItems', $groupItems);
      echo html::submitButton($lang->search->common) . '<br />';
      echo html::submitButton($lang->search->saveQuery);
      ?>
      </nobr>
    </td>
    <td><?php echo $lang->search->myQuery; ?></td>
  </tr>
</table>
</form>
