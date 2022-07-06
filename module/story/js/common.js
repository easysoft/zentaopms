$(function()
{
    if(typeof(resetActive) != 'undefined') return false;
    if(typeof(storyType) == 'undefined') storyType = '';
    if(typeof(rawModule) == 'undefined') rawModule = 'product';
    if(typeof(app)       == 'undefined') app       = '';
    if(typeof(execution) != 'undefined') rawModule = 'projectstory';
    if(['project', 'projectstory'].indexOf(rawModule) === -1 && app != 'qa')
    {
        if(app != 'my') $('#navbar .nav li').removeClass('active');
        $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');
        $('#subNavbar li[data-id="' + storyType + '"]').addClass('active');
    }
})

/**
 * Get status.
 *
 * @param  method $method
 * @param  params $params
 * @access public
 * @return void
 */
function getStatus(method, params)
{
    $.get(createLink('story', 'ajaxGetStatus', "method=" + method + '&params=' + params), function(status)
    {
        $('form #status').val(status).change();
    });
}

/**
 * Show checked fields.
 *
 * @param  string fields
 * @access public
 * @return void
 */
function showCheckedFields(fields)
{
    var fieldList = ',' + fields + ',';
    $('#formSettingForm > .checkboxes > .checkbox-primary > input').each(function()
    {
        var field     = ',' + $(this).val() + ',';
        var $field    = config.currentMethod == 'create' ? $('#' + $(this).val()) : $('[name^=' + $(this).val() + ']');
        var required  = ',' + requiredFields + ',';
        var $fieldBox = $('.' + $(this).val() + 'Box' );
        if(fieldList.indexOf(field) >= 0 || required.indexOf(field) >= 0)
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
