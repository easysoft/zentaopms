window.setFormula = function(event)
{
    const name = $(event.target).data('name');
    $('#expressionDIV').parent().find('.panel-body').addClass('hidden');

    if(typeof($('#expressionDIV').parent().find(".expressionDIV[name='" + name + "']").html()) == 'undefined')
    {
        $('#expressionDIV').parent().append("<div class='expressionDIV' name='" + name + "'>" + $('#expressionDIV').html() + '</div>')
    }
    else
    {
        $('#expressionDIV').parent().find(".expressionDIV[name='" + name + "']").removeClass('hidden');
    }

    /* Load existing formula data into the expression builder. */
    var $exprDiv     = $('#expressionDIV').parent().find(".expressionDIV[name='" + name + "']");
    var existingData = $("input[name='" + name + "']").val();
    if(existingData)
    {
        try
        {
            var items = JSON.parse(existingData);
            if(items && items.length > 0)
            {
                $exprDiv.find('input[name=expressionData]').val(existingData);
                $exprDiv.find('.expression .item-expression').remove();
                for(var i in items)
                {
                    var item = items[i];
                    $exprDiv.find('.expression').append("<span class='item-expression item-" + item.type + "'>" + item.text + "</span>");
                }
            }
        }
        catch(e) {}
    }
    if($("input[name='" + name + "']").val()) appendExpression($("input[name='" + name + "']").val(), name);
}

window.appendExpression = function(expression, name)
{
    $('#expressionDIV').parent().find(".expressionDIV[name='" + name + "']").html($('#expressionDIV').html());
    $('#expressionDIV').parent().find(".expressionDIV[name='" + name + "']").find('input[name=expressionData]').val(expression);

    expression = JSON.parse(expression);
    for(var i in expression)
    {
        let current = expression[i];
        let text    = current.text;
        if(current.type == 'target')
        {
            if(current.function)
            {
                text = functions[current.function].replace('%s', modules[current.module]).replace('%s', moduleFields[current.module][current.field]);
            }
            else
            {
                text = modules[current.module] + '_' + moduleFields[current.module][current.field];
            }
        }

        $('#expressionDIV').parent().find(".expressionDIV[name='" + name + "'] .expression").append("<span class='item-expression item-" + current.type + "'>" + text + "</span>");
    }
}

window.saveFormula = function(event)
{
    const name     = $(event.target).closest('.expressionDIV').attr('name');
    const hasError = checkExpression(event);
    if(!hasError)
    {
        const expressionData = $(event.target).closest('.expressionDIV').find('input[name=expressionData]').val();
        $("input[name='" + name + "']").val(expressionData);
        cancelFormula(event);
    }
}

window.cancelFormula = function(event)
{
    $('#expressionDIV').parent().find('.panel-body').removeClass('hidden');
    $(event.target).closest('.expressionDIV').addClass('hidden');
}

window.clickExpression = function(event)
{
    const text = $(event.target).html();
    const data = $(event.target).data();
    const type = $(event.target).data('type');

    $(event.target).closest('.expressionDIV').find('.expression').append("<span class='item-expression item-" + type + "'>" + text + "</span>");

    expressionData = JSON.parse($(event.target).closest('.expressionDIV').find('input[name=expressionData]').val());
    expressionData.push(data);
    $(event.target).closest('.expressionDIV').find('input[name=expressionData]').val(JSON.stringify(expressionData));

    removeErrorLabel(event);
}

window.clearLast = function(event)
{
    $(event.target).closest('.expressionDIV').find('.expression .item-expression').last().remove();

    expressionData = JSON.parse($(event.target).closest('.expressionDIV').find('input[name=expressionData]').val());
    expressionData.pop();
    $(event.target).closest('.expressionDIV').find('input[name=expressionData]').val(JSON.stringify(expressionData));
    removeErrorLabel(event);
}

window.clearAll = function(event)
{
    $(event.target).closest('.expressionDIV').find('.expression .item-expression').remove();
    $(event.target).closest('.expressionDIV').find('input[name=expressionData]').val('[]');
    removeErrorLabel(event);
}

window.removeErrorLabel = function(event)
{
    $(event.target).closest('.expressionDIV').find('.expression').css('border-color', '').next('#expressionLabel').remove();
}

window.appendErrorLabel = function(event, message)
{
    removeErrorLabel(event);
    $(event.target).closest('.expressionDIV').find('.expression').css('border-color', '#953B39').after("<span id='expressionLabel' for='expression' class='danger-pale'>" + message + '</span>');
}

window.checkExpression = function(event)
{
    expressionData = JSON.parse($(event.target).closest('.expressionDIV').find('input[name=expressionData]').val());
    if(expressionData.length == 0)
    {
        appendErrorLabel(event, errorMessage.empty);
        return true;
    }
    else
    {
        let fakeExpression = [];
        for(var i in expressionData)
        {
            let current = expressionData[i];

            if(current.type == 'target')   fakeExpression.push(current.field);
            if(current.type == 'operator') fakeExpression.push(current.operator);
            if(current.type == 'number')   fakeExpression.push(current.value);
        }

        fakeExpression = fakeExpression.join('');
        try
        {
            math.parse(fakeExpression);
        }
        catch(error)
        {
            appendErrorLabel(event, errorMessage.error);
            return true;
        }

        let error  = false;
        let length = expressionData.length;
        for(var i in expressionData)
        {
            i = parseInt(i);

            let current = expressionData[i];
            let prev    = '';
            let next    = '';

            if(i > 0)         prev = expressionData[i - 1];
            if(i < length -1) next = expressionData[i + 1];

            switch(current.type)
            {
                case 'target' :
                    if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                    {
                        error = true;
                        break;
                    }
                    if(next != '' && (next.type != 'operator' || next.operator == '('))
                    {
                        error = true;
                        break;
                    }
                    break;
                case 'number' :
                    if(current.value == '.')
                    {
                        if(prev == '' || prev.type != 'number' || prev.value == '.')
                        {
                            error = true;
                            break;
                        }
                        if(next == '' || next.type != 'number' || next.value == '.')
                        {
                            error = true;
                            break;
                        }
                    }
                    else
                    {
                        if(prev != '' && (prev.type == 'target' || (prev.type == 'operator' && prev.operator == ')')))
                        {
                            error = true;
                            break;
                        }
                        if(next != '' && (next.type == 'target' || (next.type == 'operator' && next.operator == '(')))
                        {
                            error = true;
                            break;
                        }
                    }
                    break;
                case 'operator' :
                    switch(current.operator)
                    {
                        case '(' :
                            if(prev != '' && (prev.type != 'operator' || prev.operator == ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'number' && next.value == '.') || (next.type == 'operator' && next.operator != '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        case ')' :
                            if(prev == '' || (prev.type == 'number' && prev.value == '.') || (prev.type == 'operator' && prev.operator != ')'))
                            {
                                error = true;
                                break;
                            }
                            if(next != '' && (next.type != 'operator' || next.operator == '('))
                            {
                                error = true;
                                break;
                            }
                            break;
                        default :
                            if(prev == '' || (prev.type == 'operator' && prev.operator != ')') || (prev.type == 'number' && prev.value == '.'))
                            {
                                error = true;
                                break;
                            }
                            if(next == '' || (next.type == 'operator' && next.operator != '(') || (next.type == 'number' && next.value == '.'))
                            {
                                error = true;
                                break;
                            }
                    }
                    break;
            }

            if(error)
            {
                appendErrorLabel(event, errorMessage.error);
                return true;
            }
        }

        return false;
    }
}
