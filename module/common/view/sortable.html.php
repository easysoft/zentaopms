<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<?php js::import($jsRoot . 'zui/zui.sortable.js');?>
<style>
tbody.sortable > tr {position: relative; z-index: 5}
tbody.sortable > tr.drag-shadow {display: none}
tbody.sortable > tr > td.sort-handler {cursor: move; color: #999;}
tbody.sortable > tr > td.sort-handler > i {position: relative; top: 2px}
tbody.sortable-sorting > tr {transition: all .2s;}
tbody.sortable-sorting {cursor: move;}
tbody.sortable-sorting > tr {opacity: .3;}
tbody.sortable-sorting > tr.drag-row {opacity: 1; z-index: 10; box-shadow: 0 2px 4px red}
tbody.sortable-sorting > tr.drag-row + tr > td {box-shadow: inset 0 4px 2px rgba(0,0,0,.2)}
tbody.sortable-sorting > tr.drag-row > td {background-color: #edf3fe!important}
</style>
<script> 
$(document).ready(function()
{
    $('.sortable:not(tbody)').sortable();
    $('tbody.sortable').each(function()
    {
        var $tbody = $(this);
        $tbody.sortable(
        {
            selector: 'tr',
            dragCssClass: 'drag-row',
            trigger: $tbody.find('.sort-handler').length ? '.sort-handler' : null,
            finish: function(e)
            {
                $tbody.trigger('sort.sortable', e);
                var $thead = $tbody.closest('table').children('thead');
                $thead.find('.headerSortDown, .headerSortUp').removeClass('headerSortDown headerSortUp').addClass('header');
                $thead.find('th.sort-default .header').removeClass('header').addClass('headerSortDown');
            }
        });
    });
});
</script>
