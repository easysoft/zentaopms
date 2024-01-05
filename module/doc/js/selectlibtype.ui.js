$(function()
{
    changeSpace();
});

/**
 * Change space.
 *
 * @access public
 * @return void
 */
window.changeSpace = function()
{
    let space = $('[name=space]:checked').val();
    $('.apiTypeTR').toggleClass('hidden', space != 'api');
    $('.projectTR').toggleClass('hidden', space != 'project');
    $('.productTR').toggleClass('hidden', space != 'product');
    $('#typedoc').toggleClass('hidden', space == 'api');
    $('#typeapi').toggleClass('hidden', space == 'mine' || space == 'custom');
    $('#typedoc').closest('.radio-primary').toggleClass('hidden', space == 'api');
    $('#typeapi').closest('.radio-primary').toggleClass('hidden', space == 'mine' || space == 'custom');
    $('#docType').toggleClass('hidden', $('#docType [name=type]:not(.hidden)').length == 1);

    let docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    if(space == 'project' && docType) $('#projectBox').trigger('change');
    if(space == 'product' && docType) $('#product').trigger('change');
    if((space == 'mine' || space == 'custom') && docType) loadDocLibs(space, docType);
    if(space == 'api' && docType) changeApiType();

    if(!docType)
    {
        $('[name=type]:not(.hidden)').first().prop('checked', true);
        changeDocType();
    }
}

/**
 * Change doc type.
 *
 * @access public
 * @return void
 */
window.changeDocType = function()
{
    let docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    $('.executionTH').removeClass('hidden');
    $('.executionHelp').removeClass('hidden');
    $('#executionBox').removeClass('hidden');
    if(docType == 'api')
    {
        $('.executionTH').addClass('hidden');
        $('.executionHelp').addClass('hidden');
        $('#executionBox').addClass('hidden');
        $('#projectBox').attr('onchange', "loadObjectModules('project', '" + docType + "')");
    }
    else if(docType == 'doc')
    {
        $('#projectBox').attr('onchange', "loadExecutions()");
    }
    $('#product').attr('onchange', "loadObjectModules('product', '" + docType + "')");

    let space = $('[name=space]:checked').val();
    if(space) changeSpace();

    $('#submit').removeAttr('disabled');
}

/**
 * Change api type.
 *
 * @access public
 * @return void
 */
window.changeApiType = function()
{
    let apiType = $('input[name=apiType]').val();
    $('.projectTR').toggleClass('hidden', apiType != 'project');
    $('.productTR').toggleClass('hidden', apiType != 'product');
    if(apiType == 'project') $('#projectBox').trigger('change');
    if(apiType == 'product') $('#product').trigger('change');
    if(apiType == 'nolink')  loadDocLibs('api', 'api');
    if(apiType == '')
    {
        const $modulePicker = $('#selectLibTypeForm .moduleBox').zui('picker');
        $modulePicker.render({items: []});
    }
}

/**
 * Load doc libs by type.
 *
 * @param  string  type
 * @return void
 */
window.loadDocLibs = function(space, type)
{
    const link = $.createLink('doc', 'ajaxGetLibsByType', `space=${space}&type=${type}`);
    $.get(link, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $modulePicker = $('#selectLibTypeForm .moduleBox').zui('picker');
            $modulePicker.render({items: data});
            $modulePicker.$.setValue('');
        }
    });
}

/**
 * Load executions.
 *
 * @param  int $projectID
 * @access public
 * @return void
 */
window.loadExecutions = function()
{
    const projectID   =  $('.modal-body input[name=project]').val();
    const executionID = $('#execution').val();
    if(executionID)
    {
        const link = createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&executionID=" + executionID + "&mode=multiple,leaf,noprefix&type=sprint,stage");
    }
    else
    {
        const link = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix&type=sprint,stage");
    }
    $.get(link, function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $executionPicker = $('.modal-body input[name^=execution]').zui('picker');
            $executionPicker.render({items: data});
        }
    });
    loadObjectModules('project', 'doc', projectID);
}

window.loadObjectModules = function(objectType, docType, objectID)
{
    if(typeof objectID == 'undefined') objectID = $(`.modal-body input[name=${objectType}]`).val();
    if(!objectID || !objectType) return false;

    docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    if(typeof docType == 'undefined') docType = 'doc';
    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=' + objectType + '&objectID=' + objectID + '&type=' + docType);

    $.get(link, function(data)
    {
        data = JSON.parse(data);
        const $modulePicker = $('#selectLibTypeForm .moduleBox').zui('picker');
        $modulePicker.render({items: data});
        $modulePicker.$.setValue('');
    });
}

/**
 * Redirect the parent window.
 *
 * @param  string objectType
 * @param  int    libID
 * @param  string docType
 * @access public
 * @return void
 */
window.redirectParentWindow = function(objectType, libID, moduleID, docType)
{
    let link = '';
    if(docType == 'api')
    {
        link = $.createLink('api', 'create', 'libID=' + libID + '&moduleID=' + moduleID + '&space=' + objectType);
    }
    else
    {
        link = $.createLink('doc', 'create', 'objectType=' + objectType + '&objectID=0&libID=' + libID + '&moduleID=' + moduleID + '&docType=' + docType) + '#app=doc';
    }
    openUrl(link, 'doc');
}
