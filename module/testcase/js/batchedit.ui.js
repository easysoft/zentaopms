window.handleRenderRow = function($row, index, row)
{
    /* Set the modules for the row. */
    $row.find('.form-batch-control[data-name="module"] .picker-box').on('inited', function(e, info)
    {
        let $module = info[0];
        $module.render({items: modulePairs[row.id]});
        $module.$.setValue(row.module);
    });

    /* Set the scenes for the row. */
    $row.find('.form-batch-control[data-name="scene"] .picker-box').on('inited', function(e, info)
    {
        let $scene = info[0];
        $scene.render({items: scenePairs[row.id]});
        $scene.$.setValue(row.scene);
    });
}
