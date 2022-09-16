$(function()
{
    $.get(createLink('zahost', 'ajaxImageList', 'hostID=' + hostID), function(response)
    {
        var resultData= JSON.parse(response);
        if(resultData.result == 'success')
        {
            var options = '';
            resultData.data.forEach(function(item)
            {
                options += "<option value='" + item.name + "'>" + item.name + "</option>";
            });

            $('#imageName').replaceWith("<select name='imageName' id='imageName' class='form-control'>" + options + "</select>");
            $("#imageName_chosen").remove();
            $("#imageName").next('.picker').remove();
            $('#imageName').chosen();
        }
        else
        {
            $('#imageName_chosen a:first-child').addClass('has-error');
            if(resultData.message.imageName)
            {
                var errors = resultData.message.imageName.join('');
                $('#imageName_chosen').after("<div id='imageNameLabel' class='text-danger helper-text'>" + errors + "</div>");
            }
        }
    });

    $('#osCategory').change(function()
    {
        var os = $('#osCategory').val();
        $('#osType').empty();
        var types = zahostConfig.os.type[os];
        for(code in types) $('#osType').append('<option value="' + code + '">' + types[code] + '</option>');
        $('#osType').chosen().trigger('chosen:updated');
        $('#osType').change();
    });

    $('#osType').change(function()
    {
        var type = $('#osType').val();
        $('#osVersion').empty();
        var versions = zahostLang.versionList[type];
        for(code in versions) $('#osVersion').append('<option value="' + code + '">' + versions[code] + '</option>');
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
