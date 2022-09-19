$(function()
{
    if(config.currentMethod == 'create') $('#hostID').change();

    $('#hostID').change(function()
    {
        var hostID = $('#hostID').val();
        var link   = createLink('executionnode', 'ajaxGetTemplates', 'hostID=' + hostID);
        $.get(link, function(data)
        {
            $('#template').html('').append(data);
            $('#templateID').chosen().trigger("chosen:updated");
            $('#templateID').change();
        });
    });

    $(document).on("change", '#templateID', function()
    {
        var templateID = $('#templateID').val();
        var link       = createLink('executionnode', 'ajaxGetTemplateInfo', 'templateID=' + templateID);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            console.log(data);
            $('#osCategory').val(data.osCategoryName);
            $('#osType').val(data.osTypeName);
            $('#osVersion').val(data.osVersionName);
            $('#osLang').val(data.osLangName);
        });
    });

    if(typeof templateID == 'undefined' || !templateID) $('#osCategory').change();
})
