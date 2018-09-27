<?php
/**
 * The view file of datatable module of ZenTaoPMS.
 *
 * @copyright   Copyright 2014-2014 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     business(商业软件) 
 * @author      Hao sun <sunhao@cnezsoft.com>
 * @package     datatable 
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.cols-list {margin-bottom: 10px;}
.cols-list .col {border: 1px solid #ddd; height: 30px; line-height: 29px; padding: 0 10px; border-bottom: none; background: #fff; float:none; padding-left: 6px;}
.cols-list .checkbox-primary {display: inline-block; position: relative; top: -1px;}
.cols-list .checkbox-primary > label {padding: 0; width: 21px;}
.cols-list .col.drag-shadow {border: 1px solid #ddd;}
.cols-list {border-bottom: 1px solid #ddd}
.cols-list .col:hover {background: #E9F2FB}
.cols-list .col.require:hover {background: none}
.cols-list .col .actions {line-height: 30px; vertical-align: top;}
.cols-list .col .form-control {display: inline-block; width: 50px; padding: 0px 5px; height: 20px; line-height: 20px; border-color: transparent; position: relative; top: -2px}
.cols-list .col .form-control:hover {border-color: #ddd}
.cols-list .col .form-control[disabled] {background: none}
.cols-list .col .form-control[disabled]:hover {border-color: transparent;}
.cols-list .col .btn {padding: 0px 5px; position: relative; top: -1px}
.cols-list .col .btn.show-hide {font-size: 13px}
.cols-list .col .title {cursor: pointer; display: inline-block; width: 500px;}
.cols-list .col .title-bar {white-space:nowrap; cursor: pointer; display: inline-block; width: 300px; background: #f1f1f1; border-left: 1px solid #ddd; border-right: 1px solid #ddd; padding-left: 6px; position: relative; max-width: 500px; min-width: 30px}
.cols-list .col .title .icon-move {width: 20px; height: 20px; line-height: 20px; text-align: center; position: relative; left: 3px;}
.cols-list .col:hover .title-bar {background: #eee; transition: width 0.3s;}
.cols-list .col.require .title {cursor: default;}
.cols-list .col.disabled .title {opacity: 0.5;}
.cols-list .col.disabled .title strong {font-weight: normal;}
.cols-list .col.disabled .icon-ok {opacity: 0.05;}
.cols-list .col .label-hide {color: #03c; background: none}
.cols-list .col .label-show {color: #fff; background: #aaa}
.cols-list .col.disabled .label-hide {color: #fff; background: #aaa}
.cols-list .col.disabled .label-show {color: #03c; background: none}
.cols-list .col.drop-to, .cols-list .col.drag-from {opacity: 0.5; visibility: visible;  background: #e5e5e5}
.cols-list .col .btn.disabled, .cols-list .col.disabled .btn.disabled, .cols-list .col .btn.disabled .label-hide {border: none; color: #aaa; cursor: not-allowed; background: none}
.cols-list .col .icon-move {opacity: 0; transition:all 0.2s; cursor: move;}
.cols-list .col:hover .icon-move {opacity: 0.7;}
.cols-list.sort-disabled .icon-move {display: none;}
#customDatatable .form-actions {margin-top: 10px;}
#customDatatable .form-actions .checkbox-primary {margin-bottom: 10px; margin-left: 7px;}
</style>
<div class='modal-dialog' id='customDatatable' style='width: 800px'>
  <div class='modal-content'>
    <div class='modal-header'>
      <button class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
      <h4 class='modal-title'>
        <?php echo $lang->datatable->custom?>
        &nbsp; <small><?php echo $lang->datatable->customTip ?></small>
      </h4>
    </div>
    <div class='modal-body'>
      <div id='colsFixedLeft' class='cols-list'></div>
      <div id='colsFixedFlex' class='cols-list'></div>
      <div id='colsFixedRight' class='cols-list'></div>
      <div class='cols-list template' id="originCols">
        <?php foreach ($cols as $key => $col):?>
        <?php
        $required = $col['required'] == 'yes';
        $fixed = $col['fixed'];
        $autoWidth = $col['width'] == 'auto';
        ?>
        <div class='clearfix col<?php echo ($required ? ' require' : '') . (' fixed-' . $fixed) ?>' data-key='<?php echo $key?>' data-fixed='<?php echo $fixed?>' data-width='<?php echo $col['width']?>'>
          <div class='actions pull-right'>
            <?php if(isset($col['name'])) echo html::hidden('name', $col['name'])?>
            <span><span class='text-muted'><?php echo $lang->datatable->width?></span> <input type='text' id='width' class='form-control' value='<?php echo $col['width']?>'><?php echo $autoWidth ? '&nbsp;' : 'px' ?></span>
          </div>
          <div class="checkbox-primary<?php echo $required ? ' disabled' : '';?>"><label></label></div>
          <span class='title'><span class='title-bar'><strong><?php echo $col['title']?></strong><i class='icon-move'></i></span></span> <?php if($required) echo "<span class='text-muted'>({$lang->datatable->required})</span>"?>
        </div>
        <?php endforeach;?>
      </div>
      <div class='form-actions text-left'>
        <?php if(common::hasPriv('datatable', 'setGlobal')) echo html::checkbox('global', array(1 => $lang->datatable->setGlobal));?>
        <button type='button' class='btn btn-wide btn-primary btn-save' id='btnSaveCustom'><?php echo $lang->save ?></button>
        <button type='button' class='btn btn-wide' data-dismiss='modal'><?php echo $lang->close ?></button>
        <button type='button' class='btn btn-wide' id='resetBtn'><?php echo $lang->datatable->reset ?></button>
      </div>
    </div>
  </div>
</div>
<?php
js::set('resetText', $lang->datatable->reset);
js::set('resetGlobalText', $lang->datatable->resetGlobal);
?>
<script>
$(function()
{
    var setting = JSON.parse('<?php echo $setting;?>');

    var $cols = $('#originCols .col').addClass('disabled');
    $cols.filter('.fixed-left').appendTo('#colsFixedLeft');
    $cols.filter('.fixed-flex, .fixed-no').appendTo('#colsFixedFlex');
    $cols.filter('.fixed-right').appendTo('#colsFixedRight');
    $('#originCols').remove();
    $('[data-key=actions] #width').attr('disabled', 'disabled');

    if(typeof setting[0] === 'string')
    {
        var newSetting = [];
        $('.col').each(function(idx)
        {
            var $col = $(this);
            var id = $col.data('key');
            newSetting.push({id: id, order: idx, show: $.inArray(id, setting) > -1});
        });
        setting = newSetting;
    }

    setting.sort(function(a, b)
    {
        if(a.order !== undefined && b.order !== undefined)
        {
            if(typeof a.order === 'string') a.order = parseInt(a.order);
            if(typeof b.order === 'string') b.order = parseInt(b.order);
            return a.order - b.order;
        }
        return 0;
    });

    for(var i = 0; i< setting.length; i++)
    {
        var col = setting[i];
        if(typeof col === 'string')
        {
            col = {id: col, disabled: false};
        }
        var $col = $cols.filter('[data-key=' + col.id + ']');
        $col.toggleClass('disabled', !col.show).attr('data-order', col.order);
        $col.find('.checkbox-primary').toggleClass('checked', !!col.show);
        if(col.width) $col.find('input#width').val(col.width.replace('px', ''));
    }

    $('.cols-list').on('click', '.col:not(.require) .title, .col:not(.require) .checkbox-primary', function()
    {
        var $col = $(this).closest('.col').toggleClass('disabled');
        $col.find('.checkbox-primary').toggleClass('checked', !$col.hasClass('disabled'));
    }).each(function()
    {
        var $list = $(this),
            $cols = $list.children('.col');
        if($cols.length < 2)
        {
            $list.addClass('sort-disabled');
        }
        else
        {
            $cols.detach().sort(function(a, b)
            {
                return $(a).data('order') - $(b).data('order');
            }).appendTo($list);

            $list.sortable({trigger: '.title', selector: '.col'});
        }
    }).on('keyup change', 'input#width', function()
    {
        renderColWidth($(this).closest('.col'));
    });

    function renderColWidth($col)
    {
        var width = $col.find('input#width').val();
        if(width == 'auto')
        {
            width = '500';
        }
        else
        {
            width = parseInt(width);
            if(isNaN(width))
            {
                width = $col.data('width');
            }
            if(width == 'auto') width = '500';
        }
        $col.find('.title-bar').css('width', width);
    }

    $('.col').each(function(){renderColWidth($(this));});

    $('#btnSaveCustom').on('click', function()
    {
        var setting = $('.col').map(function(index)
        {
            var $col = $(this);
            var sets = {id: $col.data('key'), order: index + 1, show: !$col.hasClass('disabled'), width: $col.find('input#width').val(), fixed: $col.data('fixed')};
            if(sets.width !== 'auto') sets.width += 'px';
            if($col.find('#name').size() > 0) sets.name = $col.find('#name').val();
            return sets;
        }).get();

        window.saveDatatableConfig('<?php echo $mode == 'table' ? 'tablecols' : 'cols';?>', setting, true, $('#global1').prop('checked') ? 1 : 0);
    });

    $('#resetBtn').on("click", function()
    {
        var system = $('#global1').prop('checked') ? 1 : 0;
        var param  = "<?php echo "module=$module&method=$method"?>&system=" + system;
        hiddenwin.location.href = createLink('datatable', 'ajaxReset', param);
    })

    $('#global1').change(function()
    {
        if($(this).prop('checked'))$('#resetBtn').text(resetGlobalText);
        if(!$(this).prop('checked'))$('#resetBtn').text(resetText);
    })

});
</script>
