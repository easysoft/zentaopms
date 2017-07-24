<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php if(!empty($lang->datatable)):?>
<style>
.table-datatable tbody > tr td,
.table-datatable thead > tr th {height: 34px; line-height: 20px;}
.table-datatable tbody > tr td .btn-icon > i {line-height: 19px;}
.hide-side .table-datatable thead > tr > th.check-btn i {visibility: hidden;}
.hide-side .side-handle {line-height: 33px}
.table-datatable .checkbox-row {display: none}
.outer .datatable {border: 1px solid #ddd;}
.outer .datatable .table, .outer .datatable .table tfoot td {border: none; box-shadow: none}
.datatable .table>tbody>tr.active>td.col-hover, .datatable .table>tbody>tr.active.hover>td {background-color: #f3eed8 !important;}
.datatable-span.flexarea .scroll-slide {bottom: -30px}

.panel > .datatable, .panel-body > .datatable {margin-bottom: 0;}
</style>
<script> 
<?php $datatableId = $this->moduleName . ucfirst($this->methodName);?>
$(document).ready(function()
{
    'use strict';

    var $datatable  = $('table.datatable').first();
    var datatableId = $datatable.attr('id');
    var dtSetting   = $.cookie('datatable.<?php echo $datatableId?>' + '.cols') || {};
    if(dtSetting === 'null') dtSetting = {};
    if(typeof dtSetting === 'string') dtSetting = $.parseJSON(dtSetting);
    var $modal = $('#customDatatable');
    var $checkList = $modal.find('.modal-body > .table > tbody');

    $datatable.find('thead>tr>th').each(function(idx)
    {
        var $th = $(this);
        idx = $th.data('index') || idx;
        var colSetting = dtSetting[idx];
        $th.toggleClass('ignore', !!(colSetting && colSetting.ignore));
    });

    $checkList.on('click', 'tr', function()
    {
        var $tr = $(this);
        if($tr.hasClass('disabled')) return;
        $tr.toggleClass('checked');
    });

    $datatable.datatable(
    {
        customizable  : false, 
        sortable      : false,
        scrollPos     : 'out',
        tableClass    : 'tablesorter',
        storage       : false,
        fixCellHeight : false,
        selectable    : 
        {
            clickBehavior: 'multi',
            startDrag: function(e)
            {
                if(!this.multiKey && !$(e.target).closest('td[data-index="0"]').length) return false;
            }
        },
        fixedHeader: true,
        ready: function()
        {
            if(!this.$table) return;
            var customMenu = this.$table.data('customMenu');

            var $dropdown = $("<div class='datatable-menu-wrapper'><div class='dropdown datatable-menu'><button type='button' class='btn btn-link' data-toggle='dropdown'><i class='icon-cogs'></i> <span class='caret'></span></button></div></div>");
            var $dropmenu = $("<ul class='dropdown-menu pull-right'></ul>");
            if(customMenu) $dropmenu.append("<li><a id='customBtn' href='<?php echo $this->createLink('datatable', 'ajaxCustom', 'id=' . $this->moduleName . '&method=' . $this->methodName)?>' data-toggle='modal' data-type='ajax'><?php echo $lang->datatable->custom?></a></li>");
            $dropmenu.append("<li><a href='javascript:;' id='switchToTable'><?php echo $lang->datatable->switchToTable?></a></li>");
            $dropdown.children('.dropdown').append($dropmenu);
            this.$datatable.before($dropdown);
            this.$datatable.find('[data-toggle="modal"], a.iframe').modalTrigger();
            if($.fn.progressPie) this.$datatable.find('.progress-pie').progressPie();
            $('a[data-toggle="showModuleModal"]').click(function(){$('#showModuleModal').modal('show')});

            $('#customBtn').modalTrigger();

            $('#switchToTable').click(function()
            {
                saveDatatableConfig('mode', 'table', true);
            });
        }
    });

    window.saveDatatableConfig = function(name, value, reload)
    {
        var datatableId = '<?php echo $datatableId;?>';
        if(typeof value === 'object') value = JSON.stringify(value);
        if('<?php echo $this->app->user->account?>' == 'guest') return;
        $.ajax(
        {
            type: "POST",
            dataType: 'json',
            data: {target: datatableId, name: name, value: value},
            success:function(e){if(reload) window.location.reload();},
            url: '<?php echo $this->createLink('datatable', 'ajaxSave')?>'
        });
    };
    setTimeout(function(){fixScroll()}, 500);
});
</script>
<?php endif;?>

<script>
/**
 * Fix scroll bar.
 * 
 * @access public
 * @return void
 */
function fixScroll()
{
    var $scrollwrapper = $('div.datatable').first().find('.scroll-wrapper:first');
    if($scrollwrapper.size() == 0)return;

    var $tfoot       = $('div.datatable').first().find('table tfoot:last');
    var scrollOffset = $scrollwrapper.offset().top + $scrollwrapper.find('.scroll-slide').height();
    if($tfoot.size() > 0) scrollOffset += $tfoot.height();
    if($('div.datatable.head-fixed').size() == 0) scrollOffset -= '34';
    var windowH = $(window).height();
    var bottom  = $tfoot.hasClass('fixedTfootAction') ? 53 + $tfoot.height() : 53;
    if(typeof(ssoRedirect) != "undefined") bottom = 53;
    if(scrollOffset > windowH + $(window).scrollTop()) $scrollwrapper.css({'position': 'fixed', 'bottom': bottom + 'px'});
    $(window).scroll(function()
    {
       newBottom = $tfoot.hasClass('fixedTfootAction') ? 53 + $tfoot.height() : 53;
       if(typeof(ssoRedirect) != "undefined") newBottom = 53;
       if(scrollOffset <= windowH + $(window).scrollTop()) 
       {    
           $scrollwrapper.css({'position':'relative', 'bottom': '0px'});
       }    
       else if($scrollwrapper.css('position') != 'fixed' || bottom != newBottom)
       {    
           $scrollwrapper.css({'position': 'fixed', 'bottom': newBottom + 'px'});
           bottom = newBottom;
       }
    });
}
</script>
