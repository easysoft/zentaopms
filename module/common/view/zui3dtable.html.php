<?php
js::import($jsRoot . 'dtable/min.js');
css::import($jsRoot . 'dtable/min.css');
?>
<style>
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

function convertCols(cols)
{
    cols.forEach(function(col)
    {
        if(col.fixed == 'no') col.fixed = false;
    });

    return cols;
}
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
