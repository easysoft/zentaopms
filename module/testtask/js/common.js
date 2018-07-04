$(document).ready(function()
{    
    $('#startForm, #closeForm, #activateForm, #blockForm').ajaxForm(
    {
        finish:function(response)
        {
            if(response.locate)
            {
                if(response.locate == 'parent')
                {
                    parent.$.cookie('selfClose', 1);
                    setTimeout(function(){parent.$.closeModal(null, 'this')}, 1200);
                }
                else
                {
                    setTimeout(function(){window.location.href = response.locate;}, 1200);
                }
            }
            return false;
        }
    });
})

function adjustPriBoxWidth()
{
    var boxWidth   = $('#ownerAndPriBox').width();
    var beginWidth = $("input[name='begin']").outerWidth();
    var addonWidth = $('#ownerAndPriBox .input-group-addon').outerWidth();
    $('#pri,#pri_chosen .chosen-single').css('width', boxWidth - beginWidth -addonWidth);
}

function createBug(obj)
{
    var $form  = $(obj).closest('form');
    var params = $form.data('params');
    var stepIdList = '';
    $form.find('.step .step-id :checkbox').each(function()
    {
        if($(this).prop('checked')) stepIdList += $(this).val() + '_';
    });

    var onlybody    = config.onlybody;
    config.onlybody = 'no';
    window.open(createLink('bug', 'create', params + ',stepIdList=' + stepIdList), '_blank');
    config.onlybody = onlybody;
}
