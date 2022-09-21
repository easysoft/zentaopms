$(function()
{
    if(typeof(hostID) != 'undefined')
    {
        var templateID = 0;
        if(typeof(template) == 'object') templateID = template.id;
        $.get(createLink('zahost', 'ajaxImageList', 'hostID=' + hostID + '&templateID=' + templateID), function(response)
        {
            var resultData = JSON.parse(response);
            var options    = '';
            if(resultData.result == 'success')
            {
                resultData.data.forEach(function(item)
                {
                    options += "<option value='" + item.name + "'>" + item.name + "</option>";
                });
            }

            $('#imageName').replaceWith("<select name='imageName' id='imageName' class='form-control'>" + options + "</select>");
            $("#imageName_chosen").remove();
            $("#imageName").next('.picker').remove();
            $('#imageName').chosen();

            if(resultData.result == 'fail')
            {
                $('#imageName_chosen a:first-child').addClass('has-error');
                if(resultData.message.imageName)
                {
                    var errors = resultData.message.imageName.join('');
                    $('#imageName_chosen').after("<div id='imageNameLabel' class='text-danger helper-text'>" + errors + "</div>");
                }
            }
        });
    }

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

})
