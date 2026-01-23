window.loadExistPipeline = function()
{
    const $existPipeline = $('[name=existPipeline]');
    const createType     = $('[name=createType]:checked').val();
    if(createType == 'new')
    {
        $existPipeline.closest('.form-group').addClass('hidden');
    }
    else
    {
        $existPipeline.closest('.form-group').removeClass('hidden');
    }
}

window.copyPipeline = function()
{
    const existPipeline = $('[name=existPipeline]').val();
    if(existPipeline)
    {
        $.getJSON($.createLink('pipeline', 'ajaxGetPipeline', "id=" + existPipeline), function(data)
        {
            $('[name=name]').val(data.data.name);
            $('zen-editor[name=desc]')[0].setHTML(data.data.desc);
        });
    }
}
