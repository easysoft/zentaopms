window.onRenderSparkline = function(result, info)
{
    if(info.col.name === 'burn' && Array.isArray(info.row.data.burns) && info.row.data.burns.length)
    {
        tryRenderSparkline();
        return [{html: '<span class="sparkline pending text-left no-padding" values="' + info.row.data.burns.join(',') + '"></span>'}];
    }
    return result;
}

window.tryRenderSparkline = function()
{
    if(window.renderingSparkline) clearTimeout(window.renderingSparkline);
    window.renderingSparkline = setTimeout(renderSparkline, 200);
}

window.renderSparkline = function()
{
    $('#dtable .dtable-rows .sparkline.pending').removeClass('pending').sparkline();
}
