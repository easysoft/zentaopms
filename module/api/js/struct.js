var paramsLen = 0

$(document).ready(function()
{
    if (structID > 0) {
        $('.paramsType').each(function () {
            $(this).trigger('change')
        })
    }

})

function addItem(t)
{
    var html = $(t).parents('#paramDiv').find('.col-attr:first').html()
    paramsLen += 1
    html = html.replace(/attribute\[\d\]/g, 'attribute['+ paramsLen +']')
    $('#paramDiv').append('<div class="row row-no-gutters col-attr">' + html + '</div>')
    // changeType($('#paramDiv .col-attr').last().find('#paramsTypeOptions'));
}

function changeType(t)
{
    var val = $(t).val()
    var customRef = $(t).parents('.table-row').find('.ref')
    if (val == 'ref') {
        customRef.attr("style", "display: contents")
        var field = $(t).attr('name');
        field = field.replace('paramsType', 'ref')
        var url = createLink('api', 'ajaxGetRefOptions', 'libID='+ libID)
        $.get(url, function(data)
        {
            data = data.replace('refTarget', field)
            $(t).parents('.col-attr').find('select[name="'+ field +'"]').replaceWith(data)
        });
    } else {
        customRef.attr("style", "display: none")
    }
}