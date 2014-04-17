<?php
/**
 * The buildform view of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: buildform.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/alert.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<style>
#bysearchTab {transition: all 0.3s;}
#bysearchTab.active {background: #fff; padding: 2px 10px 3px; border: 1px solid #ddd; border-bottom: none}
#bysearchTab.active:hover {background: #f1f1f1}
#bysearchTab.active > a:after {font-size: 14px; font-family: ZenIcon; content: ' \e6e2'; color: #808080}
#featurebar .nav {z-index: 999; position: relative;}
#querybox .form-control {padding: 2px; padding: 6px 2px\0;}
#querybox .table-form td {border: none}
#querybox .btn {padding: 5px 8px;}
#querybox .table-form td td {padding: 2px;}
#querybox .table .table {margin: 0;}
.outer #querybox .table tr > th:first-child, .outer #querybox .table tr > td:first-child,
.outer #querybox .table tr > th:last-child, .outer #querybox .table tr > td:last-child,
.outer #querybox .table tbody > tr:last-child td {padding: 2px}
#querybox a:hover {text-decoration: none;}

#selectPeriod {padding: 4px; height: 244px}
#selectPeriod > .dropdown-header {background: #f1f1f1; display: block; text-align: center; padding: 5px 0; line-height: 20px; margin-bottom: 5px; font-size: 14px; border-radius: 4px; color: #333}
#selectPeriod li > a {padding: 6px 15px; border-radius: 4px}
</style>
<script language='Javascript'>
var dtOptions = 
{
    language: '<?php echo $this->app->getClientLang();?>',
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    minView: 2,
    forceParse: 0,
    format: 'yyyy-mm-dd'
};

$(function()
{
    $('.date').each(function()
    {
        time = $(this).val();
        if(!isNaN(time) && time != ''){
            var Y = time.substring(0, 4);
            var m = time.substring(4, 6);
            var d = time.substring(6, 8);
            time = Y + '-' + m + '-' + d;
            $('.date').val(time);
        }
    });
    setDateField('.date');
});

var params        = <?php echo json_encode($fieldParams);?>;
var groupItems    = <?php echo $config->search->groupItems;?>;
var setQueryTitle = '<?php echo $lang->search->setQueryTitle;?>';
var module        = '<?php echo $module;?>';
var actionURL     = '<?php echo $actionURL;?>';

/**
 * Set date field
 * 
 * @param  string $query 
 * @return void
 */
function setDateField(query)
{
    var $period = $('#selectPeriod');
    if(!$period.length)
    {
        $period = $("<ul id='selectPeriod' class='dropdown-menu show'><li class='dropdown-header'><?php echo $lang->datepicker->dpText->TEXT_OR . ' ' . $lang->datepicker->dpText->TEXT_DATE;?></li><li><a href='#lastWeek'><?php echo $lang->datepicker->dpText->TEXT_PREV_WEEK;?></a></li><li><a href='#thisWeek'><?php echo $lang->datepicker->dpText->TEXT_THIS_WEEK;?></a></li><li><a href='#yesterday'><?php echo $lang->datepicker->dpText->TEXT_YESTERDAY;?></a></li><li><a href='#today'><?php echo $lang->datepicker->dpText->TEXT_TODAY;?></a></li><li><a href='#lastMonth'><?php echo $lang->datepicker->dpText->TEXT_PREV_MONTH;?></a></li><li><a href='#thisMonth'><?php echo $lang->datepicker->dpText->TEXT_THIS_MONTH;?></a></li></ul>").appendTo('body');
        $period.find('li > a').click(function()
        {
            var target = $('#' + $period.data('target'));
            if(target.length)
            {
                target.val($(this).attr('href').replace('#', '$'));
                // target.datetimepicker('hide');
                $period.hide();
            }
            return false;
        });
    }
    $(query).datetimepicker('remove').datetimepicker(dtOptions).on('show', function(e)
    {
        var $e = $(e.target);
        var ePos = $e.offset();
        $period.css({'left': ePos.left + 200, 'top': ePos.top + 29, 'min-height': $('.datetimepicker').outerHeight()}).show().data('target', $e.attr('id')).find('li.active').removeClass('active');
        $period.find("li > a[href='" + $e.val().replace('$', '#') + "']").closest('li').addClass('active');
    }).on('hide', function(e){setTimeout(function(){$period.hide();}, 100)});
}

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
        setDateField("#value" + fieldNO);
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
            setDateField("#value" + nextNO);
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
    if($param['control'] == 'select') echo html::select($fieldName, $param['values'], '', "class='form-control searchSelect'");
    if($param['control'] == 'input')  echo html::input($fieldName, '', "class='form-control searchInput'");
    echo '</span>';
}
?>
</div>
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='searchform' class='form-condensed'>
<table class='table table-condensed table-form'>
  <tr>
    <td>
      <table class='table active-disabled'>
      <?php
      $formSessionName = $module . 'Form';
      $formSession     = $this->session->$formSessionName;

      $fieldNO = 1;
      for($i = 1; $i <= $groupItems; $i ++)
      {
          $spanClass = $i == 1 ? '' : 'hidden';
          echo "<tr id='searchbox$fieldNO' class='$spanClass'>";

          /* Get params of current field. */
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* Print and or. */
          echo "<td class='text-right w-60px'>";
          if($i == 1) echo "<span id='searchgroup1'><strong>{$lang->search->group1}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
          echo '</td>';

          /* Print field. */
          echo "<td class='w-100px'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)' class='form-control'") . '</td>';

          /* Print operator. */
          echo "<td class='w-70px'>" . html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

          /* Print value. */
          echo "<td id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect'");
          if($param['control'] == 'input') 
          {
              $fieldName  = $formSession["field$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';
              echo html::input("value$fieldNO",  $formSession["value$fieldNO"], "class='form-control $extraClass searchInput'");
          }
          echo '</td>';

          $fieldNO ++;
          echo '</tr>';
      }
      ?>
      </table>
    </td>
    <td class='text-center nobr'><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'], "class='form-control w-60px'")?></td>
    <td>
      <table class='table active-disabled'>
      <?php
      for($i = 1; $i <= $groupItems; $i ++)
      {
          $spanClass = $i == 1 ? '' : 'hidden';
          echo "<tr id='searchbox$fieldNO' class='$spanClass'>";

          /* Get params of current field. */
          $currentField = $formSession["field$fieldNO"];
          $param        = $fieldParams[$currentField];

          /* Print and or. */
          echo "<td class='text-right w-60px'>";
          if($i == 1) echo "<span id='searchgroup2'><strong>{$lang->search->group2}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
          echo '</td>';

          /* Print field. */
          echo "<td class='w-100px'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this.value, $fieldNO)' class='form-control'") . '</td>';

          /* Print operator. */
          echo "<td class='w-70px'>" .  html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

          /* Print value. */
          echo "<td id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect'");

          if($param['control'] == 'input')
          {
              $fieldName  = $formSession["field$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';
              echo html::input("value$fieldNO",  $formSession["value$fieldNO"], "class='form-control $extraClass searchInput'");
          }
          echo '</td>';

          $fieldNO ++;
          echo '</tr>';
      }
      ?>
      </table>
    </td>
    <td class='w-180px'> 
      <?php
      echo html::hidden('module',     $module);
      echo html::hidden('actionURL',  $actionURL);
      echo html::hidden('groupItems', $groupItems);
      echo "<div class='btn-group'>";
      echo html::submitButton("<i class='icon-search'></i> " . $lang->search->common, '', 'btn-primary');
      echo html::commonButton($lang->search->reset, 'onclick=resetForm(); class=btn');
      echo html::commonButton($lang->save, 'onclick=saveQuery() class=btn');
      echo '</div>';
      ?>
    </td>
    <td class='w-120px'>
      <div class='input-group'>
      <?php
      echo html::select('queryID', $queries, $queryID, 'onchange=executeQuery(this.value) class=form-control');
      if(common::hasPriv('search', 'deleteQuery')) echo "<span class='input-group-btn'>" . html::a('javascript:deleteQuery()', '<i class="icon-remove"></i>', '', 'class=btn') . '</span>';
      ?>
      </div>
    </td>
    <th width='50' class='text-center' style='cursor:pointer; padding:0'>
      <a id="searchmore" href="javascript:showmore()"><i class="icon-double-angle-down icon-2x"></i></a>
      <a id="searchlite" href="javascript:showlite()" class="hidden"><i class="icon-double-angle-up icon-2x"></i></a>
      <?php echo html::hidden('formType', 'lite');?>
    </th>
  </tr>
</table>
</form>
<script language='Javascript'>
<?php if(isset($formSession['formType'])) echo "show{$formSession['formType']}()";?>
</script>
