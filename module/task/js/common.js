/* Set the story module. */
function setStoryModule()
{
    var storyID = $('#story').val();
    if(storyID)
    {
        var link = createLink('story', 'ajaxGetInfo', 'storyID=' + storyID);
        $.getJSON(link, function(storyInfo)
        {
            if(storyInfo)
            {
                $('#module').val(storyInfo.moduleID);
                $("#module").trigger("chosen:updated");

                $('#storyEstimate').val(storyInfo.estimate);
                $('#storyPri').val(storyInfo.pri);
                $('#storyDesc').val(storyInfo.spec);
            }
        });
    }
}

/**
 * Checked show fields.
 *
 * @param  string fields
 * @access public
 * @return void
 */
function checkedShowFields(fields)
{
    var fieldList = ',' + fields + ',';
    $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
    {
        var field     = ',' + $(this).val() + ',';
        var $field    = $('#' + $(this).val());
        var $fieldBox = $('.' + $(this).val() + 'Box' );
        if(fieldList.indexOf(field) >= 0)
        {
            $fieldBox.removeClass('hidden');
            $field.removeAttr('disabled');
        }
        else if(!$fieldBox.hasClass('hidden'))
        {
            $fieldBox.addClass('hidden');
            $field.attr('disabled', true);
        }
    });

    if(config.currentMethod == 'create');
    {
        if(fieldList.indexOf(',estStarted,') >= 0 && fieldList.indexOf(',deadline,') >= 0)
        {
            $('.borderBox').removeClass('hidden');
        }
        else if(fieldList.indexOf(',estStarted,') >= 0 || fieldList.indexOf(',deadline,') >= 0)
        {
            $('.datePlanBox').removeClass('hidden');
            if(!$('.borderBox').hasClass('hidden')) $('.borderBox').addClass('hidden');
        }
        else
        {
            if(!$('.borderBox').hasClass('hidden')) $('.borderBox').addClass('hidden');
            if(!$('.datePlanBox').hasClass('hidden')) $('.datePlanBox').addClass('hidden');
        }
    }
}

/**
 * Hidden require field.
 *
 * @access public
 * @return void
 */
function hiddenRequireFields()
{
    $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
    {
        var field    = ',' + $(this).val() + ',';
        var required = ',' + requiredFields + ',';
        if(required.indexOf(field) >= 0) $(this).closest('div').addClass('hidden');
    });
}
