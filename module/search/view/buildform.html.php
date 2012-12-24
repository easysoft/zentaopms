<?php
/**
 * The buildform view of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/alert.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
.helplink {display:none}
.button-s, .button-r, .button-c {padding:3px} 
.select-1 {width:80%}
.text-2{margin-bottom:2px; width:123px}
.select-2{margin-bottom:2px}
.date{width:104px}
</style>
<script language='Javascript'>

$(function() {
    $('.date').each(function(){
        time = $(this).val();
        if(!isNaN(time) && time != ''){
            var Y = time.substring(0, 4);
            var m = time.substring(4, 6);
            var d = time.substring(6, 8);
            time = Y + '-' + m + '-' + d;
            $('.date').val(time);
        }
    });

    startDate = new Date(1970, 1, 1);
    $(".date").datePicker({createButton:true, startDate:startDate, displayDynamic:true})
        .dpSetPosition($.dpConst.POS_TOP, $.dpConst.POS_RIGHT)
});

var params        = <?php echo json_encode($fieldParams);?>;
var groupItems    = <?php echo $config->search->groupItems;?>;
var setQueryTitle = '<?php echo $lang->search->setQueryTitle;?>';
var module        = '<?php echo $module;?>';
var actionURL     = '<?php echo $actionURL;?>';

/**
 * When the value of the fields select changed, set the operator and value of the new field.
 * 
 * @param  string $fieldName 
 * @param  int    $fieldNO 
 * @access public
 * @return void
 */
function setField(fieldName, fieldNO)
{
    $('#operator' + fieldNO).val(params[fieldName]['operator']);   // Set the operator according the param setting.
    $('#valueBox' + fieldNO).html($('#box' + fieldName).children().clone());
    $('#valueBox' + fieldNO).children().attr({name : 'value' + fieldNO, id : 'value' + fieldNO});

    if(typeof(params[fieldName]['class']) != undefined && params[fieldName]['class'] == 'date')
    {
        $("#value" + fieldNO).datePicker({createButton:true, startDate:startDate, displayDynamic:true})
        $("#value" + fieldNO).addClass('date');   // Shortcut the width of the datepicker to make sure align with others. 
        var groupItems = <?php echo $config->search->groupItems?>;
        var maxNO      = 2 * groupItems;
        var nextNO     = fieldNO > groupItems ? fieldNO - groupItems + 1 : fieldNO + groupItems;
        var nextValue  = $('#value' + nextNO).val();
        if(nextNO <= maxNO && fieldNO < maxNO && (nextValue == '' || nextValue == 0))
        {
            $('#field' + nextNO).val($('#field' + fieldNO).val());
            $('#operator' + nextNO).val('<=');
            $('#valueBox' + nextNO).html($('#box' + fieldName).children().clone());
            $('#valueBox' + nextNO).children().attr({name : 'value' + nextNO, id : 'value' + nextNO});
            $("#value" + nextNO).datePicker({createButton:true, startDate:startDate, displayDynamic:true});
            $("#value" + nextNO).addClass('date');
        }
    }
}

/**
 * Reset forms.
 * 
 * @access public
 * @return void
 */
function resetForm()
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        $('#value' + i).val('');
    }
}

/**
 * Show more fields.
 * 
 * @access public
 * @return void
 */
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

/**
 * Show lite search form.
 * 
 * @access public
 * @return void
 */
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

/**
 * Save the query.
 * 
 * @access public
 * @return void
 */
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

/**
 * Execute a query.
 * 
 * @param  int    $queryID 
 * @access public
 * @return void
 */
function executeQuery(queryID)
{
    if(!queryID) return;
    location.href = actionURL.replace('myQueryID', queryID);
}

/**
 * Delete a query.
 * 
 * @access public
 * @return void
 */
function deleteQuery()
{
    queryID = $('#queryID').val();
    if(!queryID) return;
    hiddenwin.location.href = createLink('search', 'deleteQuery', 'queryID=' + queryID);
}
</script>

<div class='hidden'>
<?php
/* Print every field as an html object, select or input. Thus when setFiled is called, copy it's html to build the search form. */
foreach($fieldParams as $fieldName => $param)
{
    echo "<span id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select($fieldName, $param['values'], '', 'class=select-2 searchSelect');
    if($param['control'] == 'input')  echo html::input($fieldName, '', "class='text-2 searchInput'");
    echo '</span>';
}
?>
</div>
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='searchform'>
<table class='table-1'>
  <tr valign='middle'>
    <th width='10'><span id='searchicon'>&nbsp;</span></th>
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

          /* Get params of current field. */
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* Print and or. */
          if($i == 1) echo "<span id='searchgroup1'><strong>{$lang->search->group1}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo "<br />" . html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"]);

          /* Print field. */
          echo html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)'");

          /* Print operator. */
          echo html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"]);

          /* Print value. */
          echo "<span id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='select-2 searchSelect'");
          if($param['control'] == 'input') 
          {
              $fieldName  = $formSession["field$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';
              echo html::input("value$fieldNO",  $formSession["value$fieldNO"], "class='text-2 $extraClass searchInput'");
          }
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

          /* Get params of current field. */
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* Print and or. */
          if($i == 1) echo "<span id='searchgroup2'><strong>{$lang->search->group2}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo "<br />" . html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"]);

          /* Print field. */
          echo html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)'");

          /* Print operator. */
          echo html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"]);

          /* Print value. */
          echo "<span id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='select-2 searchSelect'");

          if($param['control'] == 'input')
          {
              $fieldName  = $formSession["field$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';
              echo html::input("value$fieldNO",  $formSession["value$fieldNO"], "class='text-2 $extraClass searchInput'");
          }
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
      echo html::submitButton($lang->search->common, "class='button-search' title='{$lang->search->common}'");
      echo html::commonButton($lang->search->reset, 'onclick=resetForm();');
      echo html::commonButton($lang->save, 'onclick=saveQuery()');
      ?>
      </nobr>
    </td>
    <td width='250' class='a-center'>
      <?php
      echo html::select('queryID', $queries, $queryID, 'class=select-1 onchange=executeQuery(this.value)');
      if(common::hasPriv('search', 'deleteQuery')) echo html::a('javascript:deleteQuery()', '&nbsp;', '', 'class="icon-delete"');
      ?>
    </td>
    <th width='10' class='a-center' style='cursor:pointer; padding:0'>
      <span id='searchmore' onclick='showmore()'>&nbsp;</span>
      <span id='searchlite' onclick='showlite()' class='hidden'>&nbsp;</span>
      <?php echo html::hidden('formType', 'lite');?>
    </th>
  </tr>
</table>
</form>
<script language='Javascript'>
<?php if(isset($formSession['formType'])) echo "show{$formSession['formType']}()";?>
</script>
