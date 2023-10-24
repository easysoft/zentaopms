window.testMetric = function()
{
    let formData = $('.params-form').serialize();

    const postData = {};
    const params = new URLSearchParams(formData);

    for(const[key, value] of params.entries())
    {
        const decodedKey = decodeURIComponent(key);
        const decodedValue = decodeURIComponent(value);

        if(!(decodedKey in postData)) postData[decodedKey] = [];
        postData[decodedKey].push(decodedValue);
    }

    let url = $.createLink('metric', 'view', 'id=' + metricID);
    $.post(url, postData, function(response){
        response = JSON.parse(response);

        if(response.result == 'success')
        {
            $('.response-box').removeClass('text-danger').html(response.queryResult);
        }
        else
        {
             $('.response-box').addClass('text-danger').html(response.errors);
        }
    })
}

function toggleVarType(event)
{
    let varTypeControl = $(event.target);
    let varName = varTypeControl.closest('.form-body').children().first().text().slice(1);
    let varType = varTypeControl.val();

    let optionsControl = varTypeControl.closest('.form-body-group').find('#options');

    if(varType == 'select')
    {
        optionsControl.removeAttr('disabled');
        toggleOptionsControl('defaultValue', varName, 'select', optionsControl.val());
        toggleOptionsControl('queryValue', varName, 'select', optionsControl.val());

    }
    else if(varType == 'date')
    {
        optionsControl.attr('disabled', true);
        toggleOptionsControl('defaultValue', varName, 'date', optionsControl.val());
        toggleOptionsControl('queryValue', varName, 'date', optionsControl.val());
    }
    else if(varType == 'input')
    {
        optionsControl.attr('disabled', true);
        toggleOptionsControl('defaultValue', varName, 'input',optionsControl.val());
        toggleOptionsControl('queryValue', varName, 'input', optionsControl.val());
    }
}

function toggleOptionsControl(controlName, paramName, controlType, optionsType)
{
    let name = controlName + '-' + paramName;

    let selectBox = $('.' + name + '-select');
    let dateBox   = $('.' + name + '-date');
    let inputBox  = $('.' + name + '-input');

    let selectControl = selectBox.find('input.pick-value');
    let dateControl   = dateBox.find('input.pick-value');
    let inputControl  = inputBox.find('input.form-control');

    if(controlType == 'select')
    {
        dateBox.addClass('hidden');
        inputBox.addClass('hidden');
        selectBox.removeClass('hidden');

        dateControl.attr('disabled', true);
        inputControl.attr('disabled', true);
        selectControl.removeAttr('disabled');

        loadOptionsList(optionsType, selectBox);
    }
    else if(controlType == 'date')
    {
        selectBox.addClass('hidden');
        inputBox.addClass('hidden');
        dateBox.removeClass('hidden');

        selectControl.attr('disabled', true);
        inputControl.attr('disabled', true);
        dateControl.removeAttr('disabled');
    }
    else if(controlType == 'input')
    {
        selectBox.addClass('hidden');
        dateBox.addClass('hidden');
        inputBox.removeClass('hidden');

        selectControl.attr('disabled', true);
        dateControl.attr('disabled', true);
        inputControl.removeAttr('disabled');
    }
}

function toggleOptionsList(event)
{
    let varName = $(event.target).closest('.form-body').children().first().text().slice(1);
    let optionsType = $(event.target).val();
    let selectControl = $('.' + name + '-select');

    loadOptionsList(optionsType, $('.' + 'defaultValue-' + varName + '-select'));
    loadOptionsList(optionsType, $('.' + 'queryValue-' + varName + '-select'));
}

function loadOptionsList(optionsType, selectControl)
{
    $.get($.createLink('metric', 'ajaxGetControlOptions', 'optionType=' + optionsType), function(options)
    {
        const defaultValuePicker = selectControl.find('.pick-value').zui('picker');
        options = JSON.parse(options);
        defaultValuePicker.render({items: options});
    });
}
