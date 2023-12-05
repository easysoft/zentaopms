<?php
/**
 * The buildform view of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
#save-query .text {font-weight: 400; font-size: 14px;}
#selectPeriod {padding: 4px 0; height: 197px; min-width: 120px}
#selectPeriod > .dropdown-header {background: #f1f1f1; display: block; text-align: center; padding: 4px 0; line-height: 20px; margin: 5px 10px; font-size: 14px; border-radius: 2px; color: #333; font-size: 12px}
#groupAndOr {display: inline-block;}
#<?php echo $formId;?> > table {margin: 0 auto;}
#<?php echo $formId;?> > table > tbody > tr > td {padding: 8px;}
#<?php echo $formId;?> .form-actions {padding-bottom: 20px; padding-top: 0;}
<?php if(common::checkNotCN()):?>
#<?php echo $formId;?> [id^="valueBox"] .chosen-container .chosen-single {min-width: 70px;}
<?php else:?>
#<?php echo $formId;?> [id^="valueBox"] .chosen-container .chosen-single {min-width: 100px;}
<?php endif;?>
#<?php echo $formId;?> .chosen-container .chosen-drop ul.chosen-results li {white-space:normal}
#<?php echo $formId;?> input.date::-webkit-input-placeholder {color: #838A9D; opacity: 1;}
#<?php echo $formId;?> input.date::-moz-placeholder {color: #838A9D; opacity: 1;}
#<?php echo $formId;?> input.date:-ms-input-placeholder {color: #838A9D; opacity: 1;}
#<?php echo $formId;?> .btn-expand-form {background: transparent;}
#<?php echo $formId;?> .btn-expand-form:hover {background: #e9f2fb;}
.showmore .btn-expand-form .icon-chevron-double-down:before {content: '\e959';}

#queryBox select[id^="operator"] {padding-right:2px; padding-left:5px;}
#queryBox select#groupAndOr {padding-right:2px; padding-left:5px;}
#queryBox .chosen-container-single .chosen-single > span {margin-right:5px;}

#queryBox .form-actions .btn {margin-right: 5px;}
@media screen and (max-width: 1366px) { #userQueries {width: 130px!important;} }
#userQueries {border-left: 1px solid #eee; vertical-align: top;}
#userQueries > h4 {margin: 0 0 6px;}
#userQueries ul {list-style: none; padding-left: 0; margin: 0; max-height:75px; overflow:auto;}
.showmore #userQueries ul {max-height:170px;}
#userQueries ul li + li {margin-top: 5px;}
#userQueries .label {line-height: 24px; padding: 0 20px 0 8px; display: inline-block; background-color: #EEEEEE; color: #A6AAB8; border-radius: 12px; max-width: 100%; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; position: relative;}
#userQueries .label:hover {background-color: #aaa; color: #fff;}
#userQueries .label > .icon-close {position: absolute; top: 2px; right: 2px; border-radius: 9px; font-size: 12px; line-height: 18px; width: 18px; display: inline-block;}
#userQueries .label > .icon-close:hover {background-color: #ff5d5d; color: #fff;}
@media (max-width: 1050px) {#userQueries, #toggle-queries {display: none}}
<?php if($style == 'simple'):?>
#<?php echo $formId;?> .form-actions {text-align: left; padding: 0!important; max-width: 200px; vertical-align: middle; width: 100px;}
#queryBox.show {min-height: 66px;}
<?php endif;?>
#toggle-queries {position: absolute; right: 0px; top: 40px; width: 13px; background: #79cdfb; border-radius: 6px; height: 30px;cursor: pointer}
#toggle-queries .icon {position: absolute; top: 6px; right: -2px; color: #fff;}

.fieldWidth {width: 130px !important;}
.operatorWidth {width: 110px !important;}
html[lang^='zh-'] .fieldWidth {width: 110px !important;}
html[lang^='zh-'] .operatorWidth {width: 90px !important;}
.table tbody tr td input {display: block !important;}

#save-query {float: unset !important; position: absolute; right: 50px;}
#save-query .text {top: 0px;}
#save-query .text:after {border-bottom: 0px solid #0c64eb;}
#<?php echo $formId;?> [id^='valueBox'] > div.picker span.picker-selection-text {padding-right: 10px;}
</style>
<?php if($style != 'simple'):?>
  <div id='toggle-queries'>
    <i class='icon icon-angle-left'></i>
  </div>
<?php endif;?>
<form method='post' action='<?php echo $this->createLink('search', 'buildQuery');?>' target='hiddenwin' id='<?php echo $formId;?>' class='search-form no-stash<?php if($style == 'simple') echo ' search-form-simple';?>'>
<div class='hidden'>
<?php
/* Print every field as an html object, select or input. Thus when setFiled is called, copy it's html to build the search form. */
foreach($fieldParams as $fieldName => $param)
{
    echo "<div id='box$fieldName'>";
    if($param['control'] == 'select') echo html::select('field' . $fieldName, $param['values'], '', "class='form-control searchSelect'");
    if($param['control'] == 'input')  echo html::input('field' . $fieldName, '', "class='form-control searchInput'");
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
            $formSession     = $_SESSION[$formSessionName];

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
                echo "<td class='fieldWidth' style='overflow: visible'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control chosen'") . '</td>';

                /* Print operator. */
                echo "<td class='operatorWidth'>" . html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control' onchange='setPlaceHolder($fieldNO)'") . '</td>';

                /* Print value. */
                echo "<td id='valueBox$fieldNO' style='overflow:visible'>";
                if(isset($config->moreLinks["field{$currentField}"])) $config->moreLinks["value$fieldNO"] = $config->moreLinks["field{$currentField}"];
                if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect chosen' data-max_drop_width='0'");
                if($param['control'] == 'input')
                {
                    $fieldName  = $formSession["field$fieldNO"];
                    $fieldValue = $formSession["value$fieldNO"];
                    $extraClass = isset($param['class']) ? $param['class'] : '';

                    $placeholder = '';
                    if($fieldValue && strpos('$lastWeek,$thisWeek,$today,$yesterday,$thisMonth,$lastMonth',$fieldValue) !== false)
                    {
                        $placeholder = "placeholder='{$fieldValue}'";
                    }
                    elseif($fieldName == 'id' and $formSession["operator$fieldNO"] == '=')
                    {
                        $placeholder = "placeholder='{$lang->search->queryTips}'";;
                    }

                    echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' $placeholder");
                }
                echo '</td>';

                $fieldNO ++;
                echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </td>
      <td class='text-center nobr w-70px'><?php echo html::select('groupAndOr', $lang->search->andor, $formSession['groupAndOr'], "class='form-control'")?></td>
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
                echo "<td class='fieldWidth' style='overflow: visible'>" . html::select("field$fieldNO", $searchFields, $formSession["field$fieldNO"], "onchange='setField(this, $fieldNO, {$module}params)' class='form-control chosen'") . '</td>';

                /* Print operator. */
                echo "<td class='operatorWidth'>" . html::select("operator$fieldNO", $lang->search->operators, $formSession["operator$fieldNO"], "class='form-control' onchange='setPlaceHolder($fieldNO)'") . '</td>';

                /* Print value. */
                echo "<td id='valueBox$fieldNO'>";
                if(isset($config->moreLinks["field{$currentField}"]))
                {
                    $selected = $formSession["value$fieldNO"];
                    if(!isset($param['values'][$selected])) $config->moreLinks["value$fieldNO"] = $config->moreLinks["field{$currentField}"];
                }
                if($param['control'] == 'select') echo html::select("value$fieldNO", $param['values'], $formSession["value$fieldNO"], "class='form-control searchSelect chosen' data-max_drop_width='0'");

                if($param['control'] == 'input')
                {
                    $fieldName  = $formSession["field$fieldNO"];
                    $fieldValue = $formSession["value$fieldNO"];
                    $extraClass = isset($param['class']) ? $param['class'] : '';

                    $placeholder = '';
                    if($fieldValue && strpos('$lastWeek,$thisWeek,$today,$yesterday,$thisMonth,$lastMonth',$fieldValue) !== false)
                    {
                        $placeholder = "placeholder='{$fieldValue}'";
                    }
                    elseif($fieldName == 'id' and $formSession["operator$fieldNO"] == '=')
                    {
                        $placeholder = "placeholder='{$lang->search->queryTips}'";;
                    }

                    echo html::input("value$fieldNO", $fieldValue, "class='form-control $extraClass searchInput' $placeholder data-max_drop_width='0'");
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
      <td class='w-160px hidden' rowspan='2' id='userQueries'>
        <h4><?php echo $lang->search->savedQuery;?></h4>
        <ul>
          <?php foreach($queries as $query):?>
          <?php if(empty($query->id)) continue;?>
          <li><?php echo html::a("javascript:executeQuery($query->id)", $query->title . ((common::hasPriv('search', 'deleteQuery') and $this->app->user->account == $query->account) ? '<i class="icon icon-close"></i>' : ''), '', "class='label user-query' data-query-id='$query->id' title='{$query->title}'");?></li>
          <?php endforeach;?>
        </ul>
      </td>
    </tr>
    <tr>
      <?php endif;?>
      <td colspan='3' class='text-center form-actions'>
        <?php
        echo html::hidden('module',     $module);
        echo html::hidden('actionURL',  $actionURL);
        echo html::hidden('groupItems', $groupItems);
        echo html::submitButton($lang->search->common, '', 'btn btn-primary') . " &nbsp; ";
        if($style != 'simple') echo html::commonButton($lang->search->reset, '', 'btn-reset-form btn');
        echo html::commonButton('<i class="icon icon-chevron-double-down"></i>', '', 'btn-expand-form btn btn-info pull-right');
        if($style != 'simple' and common::hasPriv('search', 'saveQuery')) echo html::a($this->createLink('search', 'saveQuery', "module=$module&onMenuBar=$onMenuBar"), '<span class="text"><i class="icon-bug-confirm icon-save"></i> ' . $lang->search->saveCondition . '</span>', '', "class='btn-save-form btn btn-link btn-active-text text iframe' id='save-query'");
        echo html::hidden('formType', zget($formSession, 'formType', 'lite'));
        ?>
      </td>
    </tr>
  </tbody>
</table>
</form>
<?php js::set('searchCustom', $lang->search->custom);?>
<?php js::set('canSaveQuery', !empty($_SESSION[$module . 'Query']));?>
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
    if(!canSaveQuery)
    {
        $('.btn-save-form').attr('disabled', 'disabled');
        $('.btn-save-form').css('pointer-events', 'none');
    }
    var $searchForm = $('#<?php echo $formId;?>');
    $searchForm.find('select.chosen').chosen().on('chosen:showing_dropdown', function()
    {
        var $this = $(this);
        var $chosen = $this.next('.chosen-container').removeClass('chosen-up');
        var $drop = $chosen.find('.chosen-drop');
        if($this.data('drop_direction') === 'auto') $chosen.toggleClass('chosen-up', $drop.height() + $drop.offset().top - $(document).scrollTop() > $(window).height());
    });

    $searchForm.find('.picker-select').each(function()
    {
        var $select = $(this);
        var pickerOptions = {chosenMode: true}
        if($select.attr('data-pickertype') == 'remote') pickerOptions.remote = $select.attr('data-pickerremote');
        $select.picker(pickerOptions);
    });

    $('#queryBox select, #queryBox input').change(function()
    {
        $('#save-query').attr("disabled", "disabled");
    })

    /* Toggle user queries action. */
    $('#toggle-queries').click(function()
    {
        $('#userQueries').toggleClass('hidden');
        if(!$('#userQueries').hasClass('hidden'))
        {
            $('#toggle-queries .icon').removeClass('icon-angle-left');
            $('#toggle-queries .icon').addClass('icon-angle-right');
            $('#toggle-queries').css('right', $('#userQueries').outerWidth());
            $('#save-query').css('right', $('#userQueries').outerWidth() + 50);
        }
        else
        {
            $('#toggle-queries .icon').removeClass('icon-angle-right');
            $('#toggle-queries .icon').addClass('icon-angle-left');
            $('#toggle-queries').css('right', '0px');
            $('#save-query').css('right', 50);
        }
    });

    $('.sidebar-toggle').click(function()
    {
        if(!$('#userQueries').hasClass('hidden')) $('#toggle-queries').click();
    })

    $(window).resize(function()
    {
        if(!$('#userQueries').hasClass('hidden'))
        {
            $('#toggle-queries').css('right', $('#userQueries').outerWidth());
            $('#save-query').css('right', $('#userQueries').outerWidth() + 50);
        }
    })

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
                html += '<style>#mainMenu #query.btn-group li {position: relative;} #mainMenu #query.btn-group li a{margin-right:20px;} #mainMenu #query.btn-group li .btn-delete{ padding:0 7px; position: absolute; right: -10px; top: -3px; display: block; width: 20px; text-align: center; } </style>';
                html += "<script> function removeQueryFromMenu(obj) { var $obj = $(obj); var link = createLink('search', 'ajaxRemoveMenu', 'queryID=' + $obj.data('id')); $.get(link, function() { $obj.closest('li').remove(); if($('#mainMenu #query.btn-group').find('li').length == 0) $('#mainMenu #query.btn-group').remove(); })}<\/script>";
                $('#mainMenu .btn-toolbar.pull-left #bysearchTab').before(html);
            }
            html  = "<li><a href='" + actionURL.replace('myQueryID', queryID) + "'>" + name + "</a>";
            html += "<a href='###' class='btn-delete' data-id='" + queryID + "' onclick='removeQueryFromMenu(this)'><i class='icon icon-close'></i></a></li>";
            $('#mainMenu .btn-toolbar.pull-left #query ul.dropdown-menu').append(html);
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

        $searchForm.find('#formType').val(expand ? 'more' : 'lite');
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

        <?php
        $selectPeriod  = "<ul id='selectPeriod' class='dropdown-menu'>";
        $selectPeriod .= "<li class='dropdown-header'>{$lang->datepicker->dpText->TEXT_OR} {$lang->datepicker->dpText->TEXT_DATE}</li>";
        $selectPeriod .= "<li><a href='#lastWeek'>{$lang->datepicker->dpText->TEXT_PREV_WEEK}</a></li>";
        $selectPeriod .= "<li><a href='#thisWeek'>{$lang->datepicker->dpText->TEXT_THIS_WEEK}</a></li>";
        $selectPeriod .= "<li><a href='#yesterday'>{$lang->datepicker->dpText->TEXT_YESTERDAY}</a></li>";
        $selectPeriod .= "<li><a href='#today'>{$lang->datepicker->dpText->TEXT_TODAY}</a></li>";
        $selectPeriod .= "<li><a href='#lastMonth'>{$lang->datepicker->dpText->TEXT_PREV_MONTH}</a></li>";
        $selectPeriod .= "<li><a href='#thisMonth'>{$lang->datepicker->dpText->TEXT_THIS_MONTH}</a></li></ul>";
        ?>
        $period = $(<?php echo json_encode($selectPeriod);?>).appendTo('body');
        $period.find('li > a').click(function(event)
        {
            var target = $(query).closest('form').find('#' + $period.data('target'));
            if(target.length)
            {
                if(target.next('input[type=hidden]').length)
                {
                    target.next('input[type=hidden]').val($(this).attr('href').replace('#', '$'));
                }
                else
                {
                    target.val($(this).attr('href').replace('#', '$'));
                }
                target.attr('placeholder', $(this).attr('href').replace('#', '$'));
                $(query).closest('form').find('#operator' + $period.data('fieldNO')).val('between');
                $period.hide();
            }
            event.stopPropagation();
            return false;
        });

        $(query).datetimepicker('remove').datepicker(dtOptions).on('show', function(e)
        {
            var $e = $(e.target);
            var ePos = $e.offset();
            $period.css({'left': ePos.left - 120, 'top': ePos.top + 29, 'min-height': $('.datetimepicker').outerHeight()}).show().data('target', $e.attr('id')).data('fieldNO', fieldNO).find('li.active').removeClass('active');
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
        if(fieldName == 'id') setPlaceHolder(fieldNO);

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
            $searchForm.find(".picker#value" + fieldNO).remove();
            if($searchForm.find("#value" + fieldNO).attr('data-pickertype') == 'remote')
            {
                $searchForm.find("#value" + fieldNO).picker(
                {
                    chosenMode: true,
                    dropWidth: 'auto',
                    minAutoDropWidth: '100%',
                    maxAutoDropWidth: 350,
                    remote: $searchForm.find("#value" + fieldNO).attr('data-pickerremote')
                });
            }
            else
            {
                $searchForm.find("#value" + fieldNO).picker(
                {
                    chosenMode: true,
                    dropWidth: '100%'
                });
            }
        }
    };

    /**
     * When the value of the operator select changed, set the placeholder for the valueBox.
     *
     * @param  int    $fieldNO
     * @access public
     * @return void
     */
    var setPlaceHolder = window.setPlaceHolder = function(fieldNO)
    {
        var operator  = $('#operator' + fieldNO).val();
        var fieldName = $('#field' + fieldNO).val();
        if(operator == '=' && fieldName == 'id')
        {
            $('#value' + fieldNO).attr("placeholder","<?php echo $lang->search->queryTips;?>");
        }
        else
        {
            $('#value' + fieldNO).attr("placeholder","");
        }
    }

    /*
     * Reset form
     */
    window.resetForm = function()
    {
        for(i = 1; i <= groupItems * 2; i ++)
        {
            if(!$searchForm.find('#value' + i).hasClass('picker-select')) $searchForm.find('#value' + i).val('').trigger('chosen:updated');
            if($searchForm.find('#value' + i).hasClass('picker-select'))  $searchForm.find('#value' + i).data('zui.picker').setValue('');
            $searchForm.find('#value' + i + '.date').val('').attr('placeholder', '');
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

    if($('#formType').val() == 'more') expandForm(true);
    $searchForm.on('click', '.user-query .icon-close', function(e)
    {
        e.preventDefault(); // Fix bug #21572.
        var $query = $(this).closest('.user-query');
        var queryId = $query.data('queryId');
        var deleteQueryLink = $.createLink('search', 'deleteQuery', 'queryID=' + queryId);
        $.get(deleteQueryLink, function(data)
        {
            if(data == 'success') $query.remove();
        });
        e.stopPropagation();
    });

    /* Init datepicker for search. */
    $searchForm.find('.table-condensed input.date').each(function()
    {
        setDateField($(this), $(this).attr('id').substr(5));
    });
});
</script>
