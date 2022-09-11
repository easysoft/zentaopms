$(function()
{
    $('#osCategory').change(function()
    {
        var os = $('#osCategory').val();
        $('#osType').empty();
        var types = zahostConfig.os.type[os];
        for(code in types) $('#osType').append('<option value="' + code + '">' + types[code] + '</option>');
        //if(template) $('#osType').val(template.osType);
        $('#osType').chosen().trigger('chosen:updated');
        $('#osType').change();
    });

    $('#osType').change(function()
    {
        var type = $('#osType').val();
        $('#osVersion').empty();
        var versions = zahostLang.versionList[type];
        for(code in versions) $('#osVersion').append('<option value="' + code + '">' + versions[code] + '</option>');
        //if(template) $('#osVersion').val(template.osVersion);
        $('#osVersion').chosen().trigger('chosen:updated');
        $('#osVersion').change();
    });

    $('#osVersion').change(function()
    {
        if(config.currentMethod != 'create') return;

        var os      = $('#osCategory').val();
        var type    = $('#osType').val();
        var version = $('#osVersion').val();
        var link    = createLink('zahost', 'ajaxGetVmTemplateList', 'osCategory=' + os + '&osType=' + type + '&osVersion=' + version);
        $.get(link, function(data)
        {
            $('#template').html('').append(data);
            if(template) $('#vmTemplate').val(template.id);
            $('#vmTemplate').chosen().trigger("chosen:updated");
        });
    });

    $('#osCategory').change();
})
