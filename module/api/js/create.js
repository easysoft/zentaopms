var currentParam = null
var paramsLen = 0
$(document).ready(function()
{
    $('.col-custom').html()

    $('.customType').click(function () {
        currentParam = $(this).parents('.col-custom')
    })

    $('.submit-custom').click(function () {
        var val = $(this).parent().prev().find('.customTypeTextarea').val()
        currentParam.find('.custom').val(val)
        $('#customType').modal('hide')
    })

    $('#customType').on('shown.zui.modal', () => {
        var val = currentParam.find('.custom').val()
        var newVal = val ? val : example;
        $('.customTypeTextarea').val(newVal);
    })
    $('.formatCustom').click(function () {
        var text = $(this).parent().prev().find('.customTypeTextarea')
        var val = text.val()
        console.log(val);
        try {
            var format = JSON.stringify(JSON.parse(val), null, '    ');
            text.val(format);
        } catch (e) {
        }
    });
});

function addItem(t)
{
    var html = $(t).parents('#paramDiv').find('.col-custom:first').html()
    paramsLen += 1
    html = html.replace(/params\[\d\]/g, 'params['+ paramsLen +']')
    $('#paramDiv').append('<div class="row row-no-gutters col-custom">' + html + '</div>')
    changeType($('#paramDiv .col-custom').last().find('#paramsTypeOptions'));
}

function deleteItem(t)
{
    if ($('.col-custom').length < 2) {
        return false
    }
    $(t).parents('.col-custom').remove()
}

/**
 * Load params type options by scope
 */
function loadParamsTypeOptions(t)
{
    var field = $(t).attr('name');
    var scope = $(t).val();
    field = field.replace('scope', 'paramsType')

    var url = createLink('api', 'ajaxGetParamsTypeOptions', 'scope='+ scope)
    $.get(url, function(data)
    {
        data = data.replace('paramsTypeOptions', field)
        $(t).parents('.col-custom').find('select[name="'+ field +'"]').replaceWith(data)
    });
}

function changeType(t)
{
    var val = $(t).val()

    console.log(val)
    var customRef = $(t).parents('.col-custom').find('.typeCustom')
    if (val == 'custom') {
        customRef.removeClass('hidden')
    } else {
        customRef.addClass('hidden')
    }

}