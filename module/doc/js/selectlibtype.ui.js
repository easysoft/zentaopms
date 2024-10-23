window.getSpaceType = function()
{
    return $('.modal-body [name=rootSpace]:checked').val();
}

window.reloadMineAndCustom = function()
{
    const objectType = getSpaceType();
    const objectID   = $(`.modal-body input[name=${objectType}]`).val();
    const libID      = $('.modal-body input[name=lib]').val();
    const params     = window.btoa('objectID=' + objectID + '&libID=' + libID);

    loadModal($.createLink('doc', 'selectLibType', `objectType=${objectType}&params=${params}`));
}

window.reloadProduct = function()
{
    const objectType = getSpaceType();
    const docType    = $('.modal-body input[name=type]:checked').val();
    const objectID   = $('.modal-body input[name=product]').val();
    const libID      = $('.modal-body input[name=lib]').val();
    const params     = window.btoa('docType=' + docType + '&objectID=' + objectID + '&libID=' + libID);

    loadModal($.createLink('doc', 'selectLibType', `objectType=${objectType}&params=${params}`));
}

window.reloadProject = function()
{
    const objectType  = getSpaceType();
    const docType     = $('.modal-body input[name=type]:checked').val();
    const objectID    = $('.modal-body input[name=project]').val();
    const executionID = $('.modal-body input[name=execution]').val();
    const libID       = $('.modal-body input[name=lib]').val();
    const params      = window.btoa('docType=' + docType + '&objectID=' + objectID + '&executionID=' + executionID + '&libID=' + libID);

    loadModal($.createLink('doc', 'selectLibType', `objectType=${objectType}&params=${params}`));
}

/**
 * Change space.
 *
 * @access public
 * @return void
 */
window.changeSpace = function()
{
    const objectType = getSpaceType();
    if(objectType) loadModal($.createLink('doc', 'selectLibType', `objectType=${objectType}`));
}

/**
 * Change doc type.
 *
 * @access public
 * @return void
 */
window.changeDocType = function()
{
    let docType = $('.modal-body .radio-primary [name=type]:not(.hidden):checked').val();
    $('.executionTH').removeClass('hidden');
    $('.executionHelp').removeClass('hidden');
    $('#executionBox').removeClass('hidden');
    if(docType == 'api')
    {
        $('.executionTH').addClass('hidden');
        $('.executionHelp').addClass('hidden');
        $('#executionBox').addClass('hidden');
    }

    if($('[name=rootSpace]:checked').val()) changeSpace();
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
    if(apiType == 'project') loadExecutions();
    if(apiType == 'product') loadObjectModulesForSelect('product');;
    if(apiType == 'nolink')  loadDocLibs('api', 'api');
}

/**
 * Load doc libs by type.
 *
 * @param  string  type
 * @return void
 */
window.loadDocLibs = function(space, type, extra = '')
{
    const link = $.createLink('doc', 'ajaxGetLibsByType', `space=${space}&type=${type}&extra=${extra}`);
    $.getJSON(link, function(data)
    {
        const $libPicker = $('#selectLibTypeForm [name=lib]').zui('picker');
        $libPicker.render({items: data.libs});
        $libPicker.$.setValue('');

        const $modulePicker = $('#selectLibTypeForm [name="module"]').zui('picker');
        $modulePicker.render({items: data.modules});
        $modulePicker.$.setValue('');
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
    const projectID = $('.modal-body input[name=project]').val();
    const docType   = $('.modal-body .radio-primary [name=type]:not(.hidden):checked').val();
    if(docType == 'doc')
    {
        const projectID   = $('.modal-body input[name=project]').val();
        const executionID = $('.modal-body input[name=execution]').val();
        const link        = $.createLink('project', 'ajaxGetExecutions', "projectID=" + projectID + "&mode=multiple,leaf,noprefix");
        $.getJSON(link, function(data)
        {
            const $executionPicker = $('.modal-body input[name=execution]').zui('picker');
            $executionPicker.render({items: data.items, disabled: !data.multiple});
            $executionPicker.$.setValue(executionID);
        });
    }

    loadObjectModulesForSelect('project', projectID);
}

window.loadObjectModulesForSelect = function(objectType, objectID)
{
    if(typeof objectID == 'undefined') objectID = $(`.modal-body input[name=${objectType}]`).val();
    if(!objectID && objectType == 'execution')
    {
        objectType = 'project';
        objectID   = $(`.modal-body input[name=project]`).val();
    }

    let $libPicker = $('#selectLibTypeForm [name=lib]').zui('picker');
    $libPicker.render({items: []});
    $libPicker.$.setValue('');

    let $modulePicker = $('#selectLibTypeForm [name="module"]').zui('picker');
    $modulePicker.render({items: []});
    $modulePicker.$.setValue('');

    if(!objectID || !objectType) return false;

    let docType = $('.modal-body .radio-primary [name=type]:not(.hidden):checked').val();
    if(typeof docType == 'undefined') docType = 'doc';

    const link = $.createLink('doc', 'ajaxGetModules', 'objectType=' + objectType + '&objectID=' + objectID + '&type=' + docType);
    $.getJSON(link, function(data)
    {
        $libPicker.render({items: data.libs});
        $modulePicker.render({items: data.modules});
    });
}

window.loadLibModulesForSelect = function(e)
{
    const objectID = e.target.value;

    let docType = $('.radio-primary [name=type]:not(.hidden):checked').val();
    if(typeof docType == 'undefined') docType = 'doc';

    const link = $.createLink('tree', 'ajaxGetOptionMenu', 'rootID=' + objectID + '&viewType=' + docType + '&branch=all&rootModuleID=0&returnType=items');
    $.get(link, function(data)
    {
        data = JSON.parse(data);

        const $modulePicker = $('#selectLibTypeForm [name="module"]').zui('picker');
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
