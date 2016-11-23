<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<style>
tbody.sortable > tr.drag-shadow {display: none}
tbody.sortable > tr > td.sort-handler {cursor: move; color: #999;}
tbody.sortable > tr > td.sort-handler > i {position: relative; top: 2px}
tbody.sortable-sorting > tr {transition: all .2s; position: relative; z-index: 5; opacity: .3;}
tbody.sortable-sorting {cursor: move;}
tbody.sortable-sorting > tr.drag-row {opacity: 1; z-index: 10; box-shadow: 0 2px 4px red}
tbody.sortable-sorting > tr.drag-row + tr > td {box-shadow: inset 0 4px 2px rgba(0,0,0,.2)}
tbody.sortable-sorting > tr.drag-row > td {background-color: #edf3fe!important}
tbody.sortable > tr.drop-success > td {background-color: #cfe0ff; transition: background-color 2s;}
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
            reverse: true,
            selector: 'tr',
            dragCssClass: 'drag-row',
            trigger: $tbody.find('.sort-handler').length ? '.sort-handler' : null,
            finish: function(e)
            {
                var orders = {};
                e.list.each(function(){
                    var $this = $(this);
                    orders[$this.data('id')] = parseInt($this.attr('data-order'));
                });
                e.orders = orders;
                $tbody.trigger('sort.sortable', e);
                var $thead = $tbody.closest('table').children('thead');
                $thead.find('.headerSortDown, .headerSortUp').removeClass('headerSortDown headerSortUp').addClass('header');
                $thead.find('th.sort-default .header').removeClass('header').addClass('headerSortDown');
                e.element.addClass('drop-success');
                setTimeout(function(){e.element.removeClass('drop-success');}, 800)
            }
        });
    });
});
</script>
