<?php
/**
 * The buildform view of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id: buildform.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
$jsRoot = $this->app->getWebRoot() . "js/";
include '../../common/view/datepicker.html.php';
include '../../common/view/chosen.html.php';
?>
<style>
#bysearchTab {transition: all .3s cubic-bezier(.175, .885, .32, 1);}
#bysearchTab.active {background: #fff; padding: 2px 10px 3px; padding-bottom: 2px\0; border: 1px solid #ddd; border-bottom: none}
#bysearchTab.active:hover {background: #ddd}
#bysearchTab.active > a:after {font-size: 14px; font-family: NewZenIcon; content: ' \e6e2'; color: #808080}
#featurebar .nav {position: relative;}
#featurebar .nav .dropdown .dropdown-menu{z-index:1010;}
#querybox form{padding-right: 40px;}
#querybox .form-control {padding: 2px}
@-moz-document url-prefix() {#querybox .form-control {padding: 6px 2px;}}
#querybox .table {border: none}
#querybox .table-form td {border: none}
#querybox .btn {padding: 5px 8px;}
#querybox .table-form td td {padding: 2px;}
#querybox .table .table {margin: 0;}
.outer #querybox .table tr > th:first-child, .outer #querybox .table tr > td:first-child,
.outer #querybox .table tr > th:last-child, .outer #querybox .table tr > td:last-child,
.outer #querybox .table tbody > tr:last-child td {padding: 2px}
#querybox a:hover {text-decoration: none;}

#selectPeriod {padding: 4px; height: 197px; min-width: 120px}
#selectPeriod > .dropdown-header {background: #f1f1f1; display: block; text-align: center; padding: 4px 0; line-height: 20px; margin-bottom: 5px; font-size: 14px; border-radius: 2px; color: #333; font-size: 12px}
#selectPeriod li > a {padding: 4px 15px; border-radius: 2px}

#moreOrLite {position: absolute; right: -10px; top: 0; bottom: 0}
#searchlite, #searchmore {color: #4d90fe; width: 50px; padding: 0 5px; line-height: 60px; text-align: center;}
#searchlite {line-height: 127px}
#searchform.showmore #searchmore, #searchform #searchlite {display: none;}
#searchform.showmore #searchlite, #searchform #searchmore {display: inline-block;}
#searchform .chosen-container .chosen-drop{min-width: 400px;}
#searchform .chosen-container .chosen-drop ul.chosen-results li{white-space:normal}
#searchmore > i, #searchlite > i {font-size: 28px;}
#searchmore > i {position: relative; top: 4px;}
#searchmore:hover, #searchlite:hover {color: #145CCD; background: #e5e5e5}

#groupAndOr {display: inline-block;}

.outer > #querybox {margin: -20px -20px 20px; border-top: none; border-bottom: 1px solid #ddd}

#searchform input.date::-webkit-input-placeholder{color: #000000; opacity: 1;}
#searchform input.date::-moz-placeholder{color: #000000; opacity: 1;} 
#searchform input.date:-ms-input-placeholder{color: #000000; opacity: 1;}
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

var <?php echo $module . 'params'?> = <?php echo empty($fieldParams) ? '{}' : json_encode($fieldParams);?>;
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
function setDateField(query, fieldNO)
{
    var $period = $('#selectPeriod');
    if(!$period.length)
    {
        $period = $("<ul id='selectPeriod' class='dropdown-menu'><li class='dropdown-header'><?php echo $lang->datepicker->dpText->TEXT_OR . ' ' . $lang->datepicker->dpText->TEXT_DATE;?></li><li><a href='#lastWeek'><?php echo $lang->datepicker->dpText->TEXT_PREV_WEEK;?></a></li><li><a href='#thisWeek'><?php echo $lang->datepicker->dpText->TEXT_THIS_WEEK;?></a></li><li><a href='#yesterday'><?php echo $lang->datepicker->dpText->TEXT_YESTERDAY;?></a></li><li><a href='#today'><?php echo $lang->datepicker->dpText->TEXT_TODAY;?></a></li><li><a href='#lastMonth'><?php echo $lang->datepicker->dpText->TEXT_PREV_MONTH;?></a></li><li><a href='#thisMonth'><?php echo $lang->datepicker->dpText->TEXT_THIS_MONTH;?></a></li></ul>").appendTo('body');
        $period.find('li > a').click(function(event)
        {
            var target = $(query).closest('form').find('#' + $period.data('target'));
            if(target.length)
            {
                if(target.next('input[type=hidden]').length)
                {
                    target.next('input[type=hidden]').val($(this).attr('href').replace('#', '$'));
                    target.attr('placeholder', $(this).attr('href').replace('#', '$'));
                }
                else
                {
                    target.val($(this).attr('href').replace('#', '$'));
                }

                $(query).closest('form').find('#operator' + $period.data('fieldNO')).val('between');
                $period.hide();
            }
            event.stopPropagation();
            return false;
        });
    }
    $(query).datetimepicker('remove').datetimepicker(dtOptions).on('show', function(e)
    {
        var $e = $(e.target);
        var ePos = $e.offset();
        $period.css({'left': ePos.left + 175, 'top': ePos.top + 29, 'min-height': $('.datetimepicker').outerHeight()}).show().data('target', $e.attr('id')).data('fieldNO', fieldNO).find('li.active').removeClass('active');
        if($e.attr('placeholder'))
        {
            $period.find("li > a[href='" + $e.attr('placeholder').replace('$', '#') + "']").closest('li').addClass('active');
        }
        else
        {
            $period.find("li > a[href='" + $e.val().replace('$', '#') + "']").closest('li').addClass('active');
        }
    }).on('changeDate', function()
    {
        var opt = $(query).closest('form').find('#operator' + $period.data('fieldNO'));
        var target = $('#' + $period.data('target'));
        if(target.length)
        {
            if(target.next('input[type=hidden]').length)
            {
                target.next('input[type=hidden]').val(target.val());
            }
        }
        if(opt.val() == 'between') opt.val('<=');
        $period.hide();
    }).on('hide', function(){setTimeout(function(){$period.hide();}, 200);});
}

/**
 * When the value of the fields select changed, set the operator and value of the new field.
 * 
 * @param  string $obj 
 * @param  int    $fieldNO 
 * @access public
 * @return void
 */
function setField(obj, fieldNO, moduleparams)
{
    var params    = moduleparams;
    var fieldName = $(obj).val();
    $(obj).closest('form').find('#operator' + fieldNO).val(params[fieldName]['operator']);   // Set the operator according the param setting.
    $(obj).closest('form').find('#valueBox' + fieldNO).html($(obj).closest('form').find('#box' + fieldName).children().clone());
    $(obj).closest('form').find('#valueBox' + fieldNO).children().attr({name : 'value' + fieldNO, id : 'value' + fieldNO});

    if(typeof(params[fieldName]['class']) != undefined && params[fieldName]['class'] == 'date')
    {
        setDateField($(obj).closest('form').find("#value" + fieldNO), fieldNO);
        $(obj).closest('form').find("#value" + fieldNO).addClass('date');   // Shortcut the width of the datepicker to make sure align with others. 
        var groupItems = <?php echo $config->search->groupItems?>;
        var maxNO      = 2 * groupItems;
        var nextNO     = fieldNO > groupItems ? fieldNO - groupItems + 1 : fieldNO + groupItems;
        var nextValue  = $(obj).closest('form').find('#value' + nextNO).val();
        var operator   = $(obj).closest('form').find("#operator" + fieldNO).val();
        if(nextNO <= maxNO && fieldNO < maxNO && (nextValue == '' || nextValue == 0) && operator == ">=")
        {
            $(obj).closest('form').find('#field' + nextNO).val($(obj).closest('form').find('#field' + fieldNO).val());
            $(obj).closest('form').find('#operator' + nextNO).val('<=');
            $(obj).closest('form').find('#valueBox' + nextNO).html($(obj).closest('form').find('#box' + fieldName).children().clone());
            $(obj).closest('form').find('#valueBox' + nextNO).children().attr({name : 'value' + nextNO, id : 'value' + nextNO});
            setDateField($(obj).closest('form').find("#value" + nextNO), nextNO);
            $(obj).closest('form').find("#value" + nextNO).addClass('date');
        }
    }
    else if(params[fieldName]['control'] == 'select')
    {
        $(obj).closest('form').find("#value" + fieldNO).chosen(defaultChosenOptions).on('chosen:showing_dropdown', function()
        {
            var $this = $(this);
            var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
            var $drop = $chosen.find('.chosen-drop');
            $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
        });
    }
}

/**
 * Reset forms.
 * 
 * @access public
 * @return void
 */
function resetForm(obj)
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        $(obj).closest('form').find('#value' + i).val('').trigger('chosen:updated');
    }
}

/**
 * Show more fields.
 * 
 * @access public
 * @return void
 */
function showmore(obj)
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        if(i != 1 && i != groupItems + 1 )
        {
            $(obj).closest('form').find('#searchbox' + i).removeClass('hidden');
        }
    }

    $(obj).closest('form').find('#formType').val('more');
    $(obj).closest('form').addClass('showmore');
}

/**
 * Show lite search form.
 * 
 * @access public
 * @return void
 */
function showlite(obj)
{
    for(i = 1; i <= groupItems * 2; i ++)
    {
        if(i != 1 && i != groupItems + 1)
        {
            $(obj).closest('form').find('#value' + i).val('');
            $(obj).closest('form').find('#searchbox' + i).addClass('hidden');
        }
    }
    $(obj).closest('form').removeClass('showmore');
    $(obj).closest('form').find('#formType').val('lite');
}

/**
 * loadQueries.
 * 
 * @param  queryID $queryID 
 * @access public
 * @return void
 */
function loadQueries(queryID)
{
    $('#queryBox').load(createLink('search', 'ajaxGetQuery', 'module=' + module + '&queryID=' + queryID));
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
    deleteQueryLink = createLink('search', 'deleteQuery', 'queryID=' + queryID);
    $.get(deleteQueryLink, function(data)
    {
        if(data == 'success') $('#queryBox').load(createLink('search', 'ajaxGetQuery', 'module=' + module));
    });
}

$(function()
{
    $('#searchform select[id^="operator"]').change(function()
    {
        var value = $(this).val();
        if(value == '>=' && $(this).closest('tr').find('input[id^="value"]').hasClass('date'))
        {
            fieldNO   = parseInt($(this).attr('id').replace('operator', ''));
            fieldName = $(this).closest('tr').find("select[id^='field']").val();
            var $form      = $(this).closest('form');
            var groupItems = <?php echo $config->search->groupItems?>;
            var maxNO      = 2 * groupItems;
            var nextNO     = fieldNO > groupItems ? fieldNO - groupItems + 1 : fieldNO + groupItems;
            var nextValue  = $form.find('#value' + nextNO).val();
            if(nextNO <= maxNO && fieldNO < maxNO && (nextValue == '' || nextValue == 0))
            {
                $form.find('#field' + nextNO).val($form.find('#field' + fieldNO).val());
                $form.find('#operator' + nextNO).val('<=');
                $form.find('#valueBox' + nextNO).html($form.find('#box' + fieldName).children().clone());
                $form.find('#valueBox' + nextNO).children().attr({name : 'value' + nextNO, id : 'value' + nextNO});
                setDateField($form.find("#value" + nextNO), nextNO);
                $form.find("#value" + nextNO).addClass('date');
            }
        }
    })
})
</script>

<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='searchform' class='form-condensed'>
<div class='hidden'>
<?php
/* Print every field as an html object, select or input. Thus when setFiled is called, copy it's html to build the search form. */
foreach($fieldParams as $fieldName => $param)
{
    echo "<span id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select('field' . $fieldName, $param['values'], '', "class='form-control searchSelect'");
    if($param['control'] == 'input')  echo html::input('field' . $fieldName, '', "class='form-control searchInput' autocomplete='off'");
    echo '</span>';
}
?>
</div>
<table class='table table-condensed table-form' id='<?php echo "{$module}-search";?>' style='max-width: 1200px; margin: 0 auto'>
  <tr>
    <td class='w-400px'>
      <table class='table active-disabled table-fixed'>
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
          if(!isset($fieldParams[$currentField]))
          {
              $currentField = key($searchFields);
              $formSession["field$fieldNO"]    = $currentField;
              $formSession["operator$fieldNO"] = isset($fieldParams[$currentField]['operator']) ? $fieldParams[$currentField]['operator'] : '=';
              $formSession["value$fieldNO"]    =  '';
          }

          $param = $fieldParams[$currentField];

          /* Print and or. */
          echo "<td class='text-right w-60px'>";
          if($i == 1) echo "<span id='searchgroup1'><strong>{$lang->search->group1}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
          echo '</td>';

          /* Print field. */
          echo "<td class='w-90px'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control'") . '</td>';

          /* Print operator. */
          echo "<td class='w-70px'>" . html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

          /* Print value. */
          echo "<td id='valueBox$fieldNO' style='overflow:visible'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect chosen'");
          if($param['control'] == 'input') 
          {
              $fieldName  = $formSession["field$fieldNO"];
              $fieldValue = $formSession["value$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';

              if($fieldValue && strpos('$lastWeek,$thisWeek,$today,$yesterday,$thisMonth,$lastMonth',$fieldValue) !== false)
              {
                  echo html::input("dateValue$fieldNO", '', "class='form-control $extraClass searchInput' placeholder='{$fieldValue}'");
                  echo html::hidden("value$fieldNO", $fieldValue);
              }
              else
              {
                  echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' autocomplete='off'");
              }
          }
          echo '</td>';

          $fieldNO ++;
          echo '</tr>';
      }
      ?>
      </table>
    </td>
    <td class='text-center nobr'><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'], "class='form-control w-60px'")?></td>
    <td class='w-400px'>
      <table class='table active-disabled'>
      <?php
      for($i = 1; $i <= $groupItems; $i ++)
      {
          $spanClass = $i == 1 ? '' : 'hidden';
          echo "<tr id='searchbox$fieldNO' class='$spanClass'>";

          /* Get params of current field. */
          $currentField = $formSession["field$fieldNO"];
          if(!isset($fieldParams[$currentField]))
          {
              $currentField = key($searchFields);
              $formSession["field$fieldNO"]    = $currentField;
              $formSession["operator$fieldNO"] = isset($fieldParams[$currentField]['operator']) ? $fieldParams[$currentField]['operator'] : '=';
              $formSession["value$fieldNO"]    =  '';
          }
          $param = $fieldParams[$currentField];

          /* Print and or. */
          echo "<td class='text-right w-60px'>";
          if($i == 1) echo "<span id='searchgroup2'><strong>{$lang->search->group2}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
          if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
          echo '</td>';

          /* Print field. */
          echo "<td class='w-90px'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control'") . '</td>';

          /* Print operator. */
          echo "<td class='w-70px'>" .  html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

          /* Print value. */
          echo "<td id='valueBox$fieldNO'>";
          if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect chosen'");

          if($param['control'] == 'input')
          {
              $fieldName  = $formSession["field$fieldNO"];
              $fieldValue = $formSession["value$fieldNO"];
              $extraClass = isset($param['class']) ? $param['class'] : '';

              if($fieldValue && strpos('$lastWeek,$thisWeek,$today,$yesterday,$thisMonth,$lastMonth',$fieldValue) !== false)
              {
                  echo html::input("dateValue$fieldNO", '', "class='form-control $extraClass searchInput' placeholder='{$fieldValue}'");
                  echo html::hidden("value$fieldNO", $fieldValue);
              }
              else
              {
                  echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' autocomplete='off'");
              }
          }
          echo '</td>';

          $fieldNO ++;
          echo '</tr>';
      }
      ?>
      </table>
    </td>
    <td class='<?php echo $style == 'simple' ? 'w-80px' : 'w-160px';?>'> 
      <?php
      echo html::hidden('module',     $module);
      echo html::hidden('actionURL',  $actionURL);
      echo html::hidden('groupItems', $groupItems);
      echo "<div class='btn-group'>";
      echo html::submitButton($lang->search->common);
      if($style != 'simple')
      {
          echo html::commonButton($lang->search->reset, 'onclick=resetForm(this)');
          if(common::hasPriv('search', 'saveQuery')) echo html::a($this->createLink('search', 'saveQuery', "module=$module&onMenuBar=$onMenuBar"), $lang->save, '', "class='saveQuery btn'");
      }
      echo '</div>';
      ?>
    </td>
    <?php if($style != 'simple'):?>
    <td class='w-120px'>
      <div class='input-group'>
      <span id='queryBox'><?php echo html::select('queryID', $queries, $queryID, 'onchange=executeQuery(this.value) class=form-control');?></span>
      <?php if(common::hasPriv('search', 'deleteQuery')) echo "<span class='input-group-btn'>" . html::a('javascript:deleteQuery()', '<i class="icon-remove"></i>', '', 'class=btn') . '</span>';?>
      </div>
    </td>
    <?php endif;?>
  </tr>
</table>
<div id='moreOrLite'>
  <a id="searchmore" href="javascript:;" onclick="showmore(this)"><i class="icon-double-angle-down icon-2x"></i></a>
  <a id="searchlite" href="javascript:;" onclick="showlite(this)"><i class="icon-double-angle-up icon-2x"></i></a>
  <?php echo html::hidden('formType', 'lite');?>
</div>
</form>
<script>
$(function()
{
   $(".saveQuery").modalTrigger({width:650, type:'iframe', title:'<?php echo $lang->search->setQueryTitle?>'});
   <?php if(isset($formSession['formType'])) echo "show{$formSession['formType']}('#{$module}-search')";?>
});
</script>
