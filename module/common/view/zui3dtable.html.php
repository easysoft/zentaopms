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
function convertCols(cols)
{
    cols.forEach(function(col)
    {
        if(col.fixed == 'no') col.fixed = false;
    });

    return cols;
}
zui.DTable.defineFn();
</script>
