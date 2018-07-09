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
$formId = 'searchForm-' . uniqid('');
?>
<style>
#selectPeriod {padding: 4px 0; height: 197px; min-width: 120px}
#selectPeriod > .dropdown-header {background: #f1f1f1; display: block; text-align: center; padding: 4px 0; line-height: 20px; margin: 5px 10px; font-size: 14px; border-radius: 2px; color: #333; font-size: 12px}
#groupAndOr {display: inline-block;}
#<?php echo $formId;?> > table {margin: 0 auto;}
#<?php echo $formId;?> > table > tbody > tr > td {padding: 10px 15px; color: #838A9D;}
#<?php echo $formId;?> .form-actions {padding-bottom: 20px; padding-top: 0;}
#<?php echo $formId;?> .chosen-container[id^="field"] .chosen-drop {min-width: 140px;}
#<?php echo $formId;?> [id^="valueBox"] .chosen-container .chosen-single {min-width: 100px;}
#<?php echo $formId;?> [id^="valueBox"] .chosen-container .chosen-drop {min-width: 300px;}
#<?php echo $formId;?> .chosen-container .chosen-drop ul.chosen-results li {white-space:normal}
#<?php echo $formId;?> input.date::-webkit-input-placeholder {color: #000000; opacity: 1;}
#<?php echo $formId;?> input.date::-moz-placeholder {color: #000000; opacity: 1;}
#<?php echo $formId;?> input.date:-ms-input-placeholder {color: #000000; opacity: 1;}
#<?php echo $formId;?> .btn-expand-form {background: transparent;}
#<?php echo $formId;?> .btn-expand-form:hover {background: #e9f2fb;}
.showmore .btn-expand-form .icon-chevron-double-down:before {content: '\e959';}

#userQueries {border-left: 1px solid #eee; vertical-align: top;}
#userQueries > h4 {margin: 0 0 6px;}
#userQueries ul {list-style: none; padding-left: 0; margin: 0;}
#userQueries ul li + li {margin-top: 5px;}
#userQueries .label {line-height: 24px; padding: 0 20px 0 8px; display: inline-block; background-color: #EEEEEE; color: #A6AAB8; border-radius: 12px; max-width: 100%; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; position: relative;}
#userQueries .label:hover {background-color: #aaa; color: #fff;}
#userQueries .label > .icon-close {position: absolute; top: 2px; right: 2px; border-radius: 9px; font-size: 12px; line-height: 18px; width: 18px; display: inline-block;}
#userQueries .label > .icon-close:hover {background-color: #ff5d5d; color: #fff;}
@media (max-width: 1150px) {#userQueries {display: none}} 
</style>
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='<?php echo $formId;?>' class='search-form'>
<div class='hidden'>
<?php
/* Print every field as an html object, select or input. Thus when setFiled is called, copy it's html to build the search form. */
foreach($fieldParams as $fieldName => $param)
{
    echo "<div id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select('field' . $fieldName, $param['values'], '', "class='form-control searchSelect'");
    if($param['control'] == 'input')  echo html::input('field' . $fieldName, '', "class='form-control searchInput' autocomplete='off'");
    echo '</div>';
}
?>
</div>
<table class='table table-condensed table-form' id='<?php echo "{$module}-search";?>'>
  <tbody>
    <tr>
      <td class='w-400px'>
        <table class='table table-form table-fixed'>
          <tbody>
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
                echo "<td class='text-right w-80px'>";
                if($i == 1) echo "<span id='searchgroup1'><strong>{$lang->search->group1}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
                if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
                echo '</td>';

                /* Print field. */
                echo "<td class='w-110px' style='overflow: visible'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control chosen'") . '</td>';

                /* Print operator. */
                echo "<td class='w-90px'>" . html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

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
                        echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' placeholder='{$fieldValue}'");
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
          </tbody>
        </table>
      </td>
      <td class='text-center nobr w-90px'><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'], "class='form-control'")?></td>
      <td class='w-400px'>
        <table class='table table-form'>
          <tbody>
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
                echo "<td class='text-right w-80px'>";
                if($i == 1) echo "<span id='searchgroup2'><strong>{$lang->search->group2}</strong></span>" . html::hidden("andOr$fieldNO", 'AND');
                if($i > 1)  echo html::select("andOr$fieldNO", $lang->search->andor, $formSession["andOr$fieldNO"], "class='form-control'");
                echo '</td>';

                /* Print field. */
                echo "<td class='w-110px' style='overflow: visible'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control chosen'") . '</td>';

                /* Print operator. */
                echo "<td class='w-90px'>" .  html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control'") . '</td>';

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
                        echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' placeholder='{$fieldValue}'");
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
          </tbody>
        </table>
      </td>
      <?php if($style != 'simple'):?>
      <td class='w-160px' rowspan='2' id='userQueries'>
        <h4><?php echo $lang->search->savedQuery;?></h4>
        <ul>
          <?php foreach($queries as $queryID => $queryName):?>
          <?php if(empty($queryID)) continue;?>
          <li><?php echo html::a("javascript:executeQuery($queryID)", $queryName . (common::hasPriv('search', 'deleteQuery') ? '<i class="icon icon-close"></i>' : ''), '', "class='label user-query' data-query-id='$queryID' title='{$queryName}'");?></li>
          <?php endforeach;?>
        </ul>
      </td>
      <?php endif;?>
    </tr>
    <tr>
      <td colspan='3' class='text-center form-actions'>
        <?php
        echo html::hidden('module',     $module);
        echo html::hidden('actionURL',  $actionURL);
        echo html::hidden('groupItems', $groupItems);
        echo html::submitButton($lang->search->common, '', 'btn btn-wide btn-primary') . " &nbsp; ";
        if($style != 'simple')
        {
            if(common::hasPriv('search', 'saveQuery')) echo html::a($this->createLink('search', 'saveQuery', "module=$module&onMenuBar=$onMenuBar"), $lang->save, '', "class='btn-save-form btn btn-secondary btn-wide'") . "&nbsp;";
            echo html::commonButton($lang->search->reset, 'onclick=resetForm(this)', 'btn-reset-form btn btn-wide');
        }
        echo html::commonButton('<i class="icon icon-chevron-double-down"></i>', '', 'btn-expand-form btn btn-info pull-right');
        echo html::hidden('formType', 'lite');
        ?>
      </td>
    </tr>
  </tbody>
</table>
</form>
<?php js::set('searchCustom', $lang->search->custom);?>
<script>
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
var <?php echo $module . 'params'?> = <?php echo empty($fieldParams) ? '{}' : json_encode($fieldParams);?>;
var groupItems    = <?php echo $config->search->groupItems;?>;
var setQueryTitle = '<?php echo $lang->search->setQueryTitle;?>';
var module        = '<?php echo $module;?>';
var actionURL     = '<?php echo $actionURL;?>';

function executeQuery(queryID)
{
    if(!queryID) return;
    location.href = actionURL.replace('myQueryID', queryID);
}

$(function()
{
    var $searchForm = $('#<?php echo $formId;?>');
    $searchForm.find('select.chosen').chosen();

    /*
     * Load queries form
     */
    var loadQueries = window.loadQueries = function(queryID, shortcut, name)
    {
        $('#userQueries ul').load($.createLink('search', 'ajaxGetQuery', 'module=' + module + '&queryID=' + queryID));
        if(shortcut)
        {
            if($('#mainMenu .btn-toolbar.pull-left #query').size() == 0)
            {
                var html = '<div class="btn-group" id="query"><a href="javascript:;" data-toggle="dropdown" class="btn btn-link " style="border-radius: 2px;">' + searchCustom + ' <span class="caret"></span></a><ul class="dropdown-menu"></ul></div>';
                $('#mainMenu .btn-toolbar.pull-left #bysearchTab').before(html);
            }
            $('#mainMenu .btn-toolbar.pull-left #query ul.dropdown-menu').append("<li><a href='" + actionURL.replace('myQueryID', queryID) + "'>" + name + "</a></li>")
        }
    };

    /*
     * Expand or collapse form
     *
     * @param expand    true for expand form, false for collapse form
     */
    var expandForm = function(expand)
    {
        if (expand === undefined) expand = !$searchForm.hasClass('showmore');
        $searchForm.toggleClass('showmore', expand);
        for(i = 1; i <= groupItems * 2; i ++)
        {
            if(i != 1 && i != groupItems + 1 )
            {
                $searchForm.find('#searchbox' + i).toggleClass('hidden', !expand);
            }
        }

        $searchForm.find('#formType').val(expand ? 'more' : '');
        $searchForm.toggleClass('showmore', expand);
    };

    /**
     * Set date field
     *
     * @param  string $query
     * @return void
     */
    var setDateField = function(query, fieldNO)
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
        $(query).datetimepicker('remove').datepicker(dtOptions).on('show', function(e)
        {
            var $e = $(e.target);
            var ePos = $e.offset();
            $period.css({'left': ePos.left + 211, 'top': ePos.top + 29, 'min-height': $('.datetimepicker').outerHeight()}).show().data('target', $e.attr('id')).data('fieldNO', fieldNO).find('li.active').removeClass('active');
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
    var setField = window.setField = function(obj, fieldNO, moduleparams)
    {
        var params    = moduleparams;
        var $obj      = $(obj);
        var fieldName = $obj.val();
        $searchForm.find('#operator' + fieldNO).val(params[fieldName]['operator']);   // Set the operator according the param setting.
        $searchForm.find('#valueBox' + fieldNO).html($searchForm.find('#box' + fieldName).children().clone());
        $searchForm.find('#valueBox' + fieldNO).children().attr({name : 'value' + fieldNO, id : 'value' + fieldNO});

        if(typeof(params[fieldName]['class']) != undefined && params[fieldName]['class'] == 'date')
        {
            setDateField($searchForm.find("#value" + fieldNO), fieldNO);
            $searchForm.find("#value" + fieldNO).addClass('date');   // Shortcut the width of the datepicker to make sure align with others.
            var maxNO      = 2 * groupItems;
            var nextNO     = fieldNO > groupItems ? fieldNO - groupItems + 1 : fieldNO + groupItems;
            var nextValue  = $searchForm.find('#value' + nextNO).val();
            var operator   = $searchForm.find("#operator" + fieldNO).val();
            if(nextNO <= maxNO && fieldNO < maxNO && (nextValue == '' || nextValue == 0) && operator == ">=")
            {
                $searchForm.find('#field' + nextNO).val($searchForm.find('#field' + fieldNO).val());
                $searchForm.find('#operator' + nextNO).val('<=');
                $searchForm.find('#valueBox' + nextNO).html($searchForm.find('#box' + fieldName).children().clone());
                $searchForm.find('#valueBox' + nextNO).children().attr({name : 'value' + nextNO, id : 'value' + nextNO});
                setDateField($searchForm.find("#value" + nextNO), nextNO);
                $searchForm.find("#value" + nextNO).addClass('date');
            }
        }
        else if(params[fieldName]['control'] == 'select')
        {
            $searchForm.find("#value" + fieldNO).chosen().on('chosen:showing_dropdown', function()
            {
                var $this = $(this);
                var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
                var $drop = $chosen.find('.chosen-drop');
                $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
            });
        }
    };

    /*
     * Reset form
     */
    window.resetForm = function()
    {
        for(i = 1; i <= groupItems * 2; i ++)
        {
            $searchForm.find('#value' + i).val('').trigger('chosen:updated');
            $searchForm.find('#dateValue' + i).val('').attr('placeholder','');
        }
    };

    $searchForm.on('click', '.btn-expand-form', function() {expandForm();});
    $searchForm.on('click', '.btn-reset-form', function() {resetForm();});
    $searchForm.on('change', 'select[id^="operator"]', function()
    {
        var $select = $(this);
        var value = $select.val();
        var $tr = $select.closest('tr');
        if(value == '>=' && $tr.find('input[id^="value"].date').length)
        {
            var fieldNO   = parseInt($(this).attr('id').replace('operator', ''));
            var fieldName = $tr.find("select[id^='field']").val();
            var maxNO      = 2 * groupItems;
            var nextNO     = fieldNO > groupItems ? fieldNO - groupItems + 1 : fieldNO + groupItems;
            var nextValue  = searchForm.find('#value' + nextNO).val();
            if(nextNO <= maxNO && fieldNO < maxNO && (nextValue == '' || nextValue == 0))
            {
                searchForm.find('#field' + nextNO).val(searchForm.find('#field' + fieldNO).val());
                searchForm.find('#operator' + nextNO).val('<=');
                searchForm.find('#valueBox' + nextNO).html(searchForm.find('#box' + fieldName).children().clone());
                searchForm.find('#valueBox' + nextNO).children().attr({name : 'value' + nextNO, id : 'value' + nextNO});
                setDateField(searchForm.find("#value" + nextNO), nextNO);
                searchForm.find("#value" + nextNO).addClass('date');
            }
        }
    });

    $searchForm.find('.btn-save-form').modalTrigger({width:650, type:'iframe', title: setQueryTitle});

    $searchForm.on('click', '.user-query .icon-close', function(e)
    {
        var $query = $(this).closest('.user-query');
        var queryId = $query.data('queryId');
        var deleteQueryLink = $.createLink('search', 'deleteQuery', 'queryID=' + queryId);
        $.get(deleteQueryLink, function(data)
        {
            if(data == 'success') $query.remove();
        });
        e.stopPropagation();
    });
});
</script>
