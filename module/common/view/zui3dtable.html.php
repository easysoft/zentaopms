<?php
js::import($jsRoot . 'dtable/min.js');
css::import($jsRoot . 'dtable/min.css');
?>
<style>
.page-has-dtable {overflow: hidden;}
#main {padding-bottom: 0;}
#sidebar > .cell {padding: 0; display: flex; flex-direction: column;}
#sidebar > .cell > .tree {flex: auto; overflow: auto; padding: 10px 10px 0;}
#sidebar > .cell > div {flex: none}
.dtable {box-shadow: 0 1px 1px rgba(0,0,0,.05), 0 2px 6px 0 rgba(0,0,0,.045)}
.dtable-header {border-bottom: 1px solid #f4f5f7;}
.dtable-header .dtable-cell {font-weight: bold;}
</style>
<script>
/* set checked rows of main table in cookie */
var oldSetCheckedCookie = window.setCheckedCookie;
window.setCheckedCookie = function()
{
    var dtable = $('#mainContent [data-zui-dtable]').data('zui.DTable');
    if(!dtable) return oldSetCheckedCookie();
    $.cookie('checkedItem', dtable.$.getChecks().join(','), {expires: config.cookieLife, path: config.webRoot});
};

zui.DTable.definePlugin(
{
    name: 'zentao18',
    options: function(options)
    {
        let allLeftColsFixed = true;
        const cols = options.cols.map(function(col)
        {
            if(col.fixed == 'no') col.fixed = false;
            if(col.type === 'html' && col.flex === undefined) col.flex = col.width === 'auto' ? 1 : false;
            if(col.width === 'auto') allLeftColsFixed = false;
            delete col.sort;
            return col;
        });
        return $.extend({fixedLeftWidth: '40%'}, options, {
            cols: cols,
            responsive: 'window,parent,#queryBox',
            height: function(actualHeight)
            {
                const height = Math.max(0, window.innerHeight - ($('#mainContent').offset().top || 0)) - 1;
                const $queryBox = $('#queryBox');
                $('#sidebar>.cell').css('maxHeight', height).children('.tree').addClass('scrollbar-hover');
                return Math.min(actualHeight, height - ($('#mainContent .table-footer').outerHeight() || 0) - ($queryBox.outerHeight() || 0) - (Number.parseInt($queryBox.css('margin-bottom')) || 0));
            }
        }, allLeftColsFixed ? {fixedLeftWidth: 'auto'} : {});
    },
    onMounted: function()
    {
        if(!$(this.parent).closest('#main').length) return;
        $('body').addClass('page-has-dtable');
    }
}, {buildIn: true})
zui.DTable.defineFn();

/**
 * Get checked items.
 *
 * @access public
 * @return array
 */
function getCheckedItems()
{
    var checkedItems = [];
    const $dtable = zui.DTable.query('#mainContent [data-zui-dtable]').$;
    $dtable.getChecks().forEach(function(id)
    {
        if($dtable.getRowInfo(id) == undefined) return true;

        checkedItems.push(id);
    });
    return checkedItems;
}
</script>
