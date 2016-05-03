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
.cols-list .col {border: 1px solid #ddd; height: 30px; line-height: 29px; padding: 0 10px; border-bottom: none; background: #fff}
.cols-list .col.drag-shadow {border: 1px solid #ddd;}
.cols-list {border-bottom: 1px solid #ddd}
.cols-list .col:hover {background: #edf3fe}
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
.cols-list .col .title .icon-move {position: absolute; left: -26px; background: #fff; top: 5px; width: 20px; height: 20px; line-height: 20px; text-align: center; border-radius: 5px;}
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
.cols-list .col:hover .icon-move {opacity: 1}
.cols-list.sort-disabled .icon-move {display: none;}
</style>
<div class='modal-dialog' id='customDatatable' style='width: 800px'>
  <div class='modal-content'>
    <div class='modal-header'>
      <button class="close" data-dismiss="modal">&times;</button>
      <h4 class='modal-title'><?php echo $lang->datatable->custom?></h4>
    </div>
    <div class='modal-body'>
      <div id='colsFixedLeft' class='cols-list'></div>
      <div id='colsFixedFlex' class='cols-list'></div>
      <div id='colsFixedRight' class='cols-list'></div>
      <div class='cols-list cols-list-origin'>
        <?php foreach ($cols as $key => $col):?>
        <?php
        $required = $col['required'] == 'yes';
        $fixed = $col['fixed'];
        $autoWidth = $col['width'] == 'auto';
        ?>
        <div class='clearfix col<?php echo ($required ? ' require' : '') . (' fixed-' . $fixed) ?>' data-key='<?php echo $key?>' data-fixed='<?php echo $fixed?>' data-width='<?php echo $col['width']?>'>
        <i class='icon-ok'></i> &nbsp;<span class='title'><span class='title-bar'><strong><?php echo $col['title']?></strong><i class='icon-move'></i></span></span> <?php if($required) echo "<span class='text-muted'>({$lang->datatable->required})</span>"?>
          <div class='actions pull-right'>
            <span><span class='text-muted'><?php echo $lang->datatable->width?></span> <input type='text' class='form-control' <?php echo $autoWidth ? "disabled='disabled'" : '' ?>value='<?php echo $col['width']?>'><?php echo $autoWidth ? '&nbsp;' : 'px' ?></span>
            <button type='button' class='btn btn-link show-hide<?php echo $required ? ' disabled' : '' ?>'><span class='label-show'><?php echo $lang->datatable->show?></span><span class='text-muted'>/</span><span class='label-hide'><?php echo $lang->datatable->hide?></span></button>
          </div>
        </div>
        <?php endforeach;?>
      </div>
      <div class='actions text-center'>
        <button type='button' class='btn btn-primary btn-save' id='btnSaveCustom'><?php echo $lang->save ?></button> &nbsp; 
        <button type='button' class='btn btn-default' data-dismiss='modal'><?php echo $lang->close ?></button>
      </div>
    </div>
  </div>
</div>
<script>
$(function()
{
    var setting = JSON.parse('<?php echo $setting;?>');

    var $cols = $('.col').addClass('disabled');
    $cols.filter('.fixed-left').appendTo('#colsFixedLeft');
    $cols.filter('.fixed-flex, .fixed-no').appendTo('#colsFixedFlex');
    $cols.filter('.fixed-right').appendTo('#colsFixedRight');
    $('.cols-list-origin').remove();

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
        $col.toggleClass('disabled', !col.show);
        $col.attr('data-order', col.order);
        if(col.width) $col.find('input').val(col.width.replace('px', ''));
    }

    $('.cols-list').on('click', '.col:not(.require) .show-hide, .col:not(.require) .title', function()
    {
        $(this).closest('.col').toggleClass('disabled');
    }).each(function()
    {
        var $list = $(this),
            $cols = $list.children('.col');
        if($cols.length < 2)
        {
            $list.addClass('sort-disabled');
            $cols.find('input').val('auto').attr('disabled', 'disabled');
        }
        else
        {
            $cols.detach().sort(function(a, b)
            {
                return $(a).data('order') - $(b).data('order');
            }).appendTo($list);

            $list.sortable({trigger: '.title', selector: '.col'});
        }
    }).on('keyup change', 'input', function()
    {
        renderColWidth($(this).closest('.col'));
    });

    function renderColWidth($col)
    {
        var width = $col.find('input').val();
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
            var sets = {id: $col.data('key'), order: index + 1, show: !$col.hasClass('disabled'), width: $col.find('input').val(), fixed: $col.data('fixed')};
            if(sets.width !== 'auto') sets.width += 'px';
            return sets;
        }).get();

        window.saveDatatableConfig('cols', setting, true);
    });
});
</script>
