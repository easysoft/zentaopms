/**
  * Load all users as assignedTo list.
  *
  * @access public
  * @return void
  */
function loadAllUsers()
{
    link = createLink('bug', 'ajaxLoadAllUsers', 'selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link, function()
    {
        moduleID  = $('#module').val();
        productID = $('#product').val();
        setAssignedTo(moduleID, productID);
        $('#assignedTo').chosen(defaultChosenOptions);
    });

}

/**
  * Load team members of the latest project of a product as assignedTo list.
  *
  * @param  $productID
  * @access public
  * @return void
  */
function loadProjectTeamMembers(productID)
{
    link = createLink('bug', 'ajaxLoadProjectTeamMembers', 'productID=' + productID + '&selectedUser=' + $('#assignedTo').val());
    $('#assignedToBox').load(link, function(){$('#assignedTo').chosen(defaultChosenOptions);});
}

/**
 * load assignedTo and stories of module.
 * 
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    setAssignedTo(moduleID, productID);
    setStories(moduleID, productID);
}

/**
 * Set the assignedTo field.
 * 
 * @access public
 * @return void
 */
function setAssignedTo(moduleID, productID)
{
    if(typeof(productID) == 'undefined') productID = $('#product').val();
    if(typeof(moduleID) == 'undefined')  moduleID  = $('#module').val();
    link = createLink('bug', 'ajaxGetModuleOwner', 'moduleID=' + moduleID + '&productID=' + productID);
    $.get(link, function(owner)
    {
        $('#assignedTo').val(owner);
        $("#assignedTo").trigger("chosen:updated");
    });
}

/* Set template. */
function setTemplate(templateID)
{
    $('#tplBox .list-group-item.active').removeClass('active');
    $('#tplTitleBox' + templateID).closest('.list-group-item').addClass('active');
    steps = $('#template' + templateID).html();
    editor['#'].html(steps);
}

/* Delete template. */
function deleteTemplate(templateID)
{
    if(!templateID) return;
	if(confirm(confirmDeleteTemplate))
    {
		hiddenwin.location.href = createLink('bug', 'deleteTemplate', 'templateID=' + templateID);
		$('#tplBox' + templateID).addClass('hidden');
	}
}

/* Display template x icon. */
function displayXIcon(templateID)
{
    $('#templateID' + templateID).removeClass('hidden');
}

/* Hide template x icon. */
function hideXIcon(templateID)
{
    $('#templateID' + templateID).addClass('hidden');
}

$(function()
{
    if($('#project').val()) loadProjectRelated($('#project').val());
    $('#saveTplModal').on('hide.zui.modal', function(){$(this).find('#title').val('');});
    $('#saveTplBtn').click(function(){$('#saveTplModal').modal('show');});
    $('#saveTplModal #submit').click(function()
    {
        var $inputGroup = $('#saveTplModal div.input-group');
        var $publicBox  = $inputGroup.find('input[id^="public"]');
        var title       = $inputGroup.find('#title').val();
        var content     = $('#steps').val();
        var isPublic    = ($publicBox.size() > 0 && $publicBox.prop('checked')) ? $publicBox.val() : 0;
        if(!title || !content) return;
        saveTemplateLink = createLink('bug', 'saveTemplate');
        $.post(saveTemplateLink, {title:title, content:content, public:isPublic}, function(data)
        {
            $('#tplBox').html(data);
            // If has error then not hide.
            if(data.indexOf('alert') == -1) $('#saveTplModal').modal('hide');
        });
    });

    $('[data-toggle=tooltip]').tooltip();

    // adjust size of bug type input group
    var adjustBugTypeGroup = function()
    {
        var $group = $('#bugTypeInputGroup');
        var width = $group.width(), addonWidth = 0;
        var $controls = $group.children('.form-control');
        $group.children('.input-group-addon').each(function()
        {
            addonWidth += $(this).outerWidth();
        });
        $controls.css('width', Math.floor((width - addonWidth)/$controls.length));
    };
    adjustBugTypeGroup();

    // adjust style for file box
    var ajustFilebox = function()
    {
        applyCssStyle('.fileBox > tbody > tr > td:first-child {transition: none; width: ' + ($('#contactListGroup').width() - 2) + 'px}', 'filebox')
    };
    ajustFilebox();
    $(window).resize(function()
    {
        ajustFilebox();
        adjustBugTypeGroup();
    });
});
