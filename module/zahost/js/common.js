$(function()
{
    $('#osCategory').change(function()
    {
        var os = $('#osCategory').val();
        $('#osType').empty();
        var types = zahostConfig.os.type[os];
        for(code in types) $('#osType').append('<option value="' + code + '">' + types[code] + '</option>');
        if(typeof(template) == 'object') $('#osType').val(template.osType);
        $('#osType').chosen().trigger('chosen:updated');
        $('#osType').change();
    });

    $('#osType').change(function()
    {
        var type = $('#osType').val();
        $('#osVersion').empty();
        var versions = zahostLang.versionList[type];
        for(code in versions) $('#osVersion').append('<option value="' + code + '">' + versions[code] + '</option>');
        if(typeof(template) == 'object') $('#osVersion').val(template.osVersion);
        $('#osVersion').chosen().trigger('chosen:updated');
        $('#osVersion').change();
        /* Remove close icon to forbid clearning content of select list. */
        $('#osType_chosen a .search-choice-close').remove();
        $('#osVersion_chosen a .search-choice-close').remove();
    });

    $('#osVersion').change(function()
    {
        $('#osVersion_chosen a .search-choice-close').remove();
    });

    $('#osCategory').change();

    $('[data-toggle="popover"]').popover();
})
