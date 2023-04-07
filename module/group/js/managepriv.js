function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=byGroup&param="+ groupID + "&menu=&version=" + value);
}

/**
 * Control the packages select control for a module.
 *
 * @param   string $module
 * @access  public
 * @return  void
 */
function setModulePackages(module)
{
    $('#packageBox select').addClass('hidden');                      // Hide all select first.
    $('#packageBox select').val('');                                 // Unselect all select.
    $("select[data-module='" + module + "']").removeClass('hidden'); // Show the action control for current module.

    updatePrivList('module', module);
}

/**
 * Control the actions select control for a package.
 *
 * @access public
 * @return void
 */
function setActions()
{
    $('#actionBox select').val('');

    var hasSelectedPackage = $('#packageBox select').not('.hidden').val().join(',');

    updatePrivList('package', hasSelectedPackage);

}

function setNoChecked()
{
    var noCheckValue = '';
    $(':checkbox').each(function()
    {
        if(!$(this).prop('checked') && $(this).next('span').attr('id') != undefined) noCheckValue = noCheckValue + ',' + $(this).next('span').attr('id');
    })
    $('#noChecked').val(noCheckValue);
}

/**
 * Update the action box when module or package selected.
 *
 * @param  string  parentType  module|package
 * @param  string  parentList
 * @access public
 * @return void
 */
function updatePrivList(parentType, parentList)
{
    var selectedModule = $('#module').val();
    $.get(createLink('group', 'ajaxGetPrivByParents', 'module=' + selectedModule + '&parentType=' + parentType + '&parentList=' + parentList), function(data)
    {
        $('#actions').replaceWith(data);
    })
}

/**
 * Change parent item checked.
 *
 * @access public
 * @return void
 */
function changeParentChecked($item, moduleName, packageID)
{
    var moduleAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').length;
    var moduleSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']').find('[checked=checked]').length;
    var $moduleItem       = $item.closest('tbody').find('.module[data-module=' + moduleName +']');
    if(moduleSelectPrivs == 0)
    {
        $moduleItem.find('input[type=checkbox]').removeAttr('checked');
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(moduleAllPrivs == moduleSelectPrivs)
    {
        $moduleItem.find('input[type=checkbox]').attr('checked', 'checked');
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $moduleItem.find('input[type=checkbox]').removeAttr('checked');
        $moduleItem.find('label').addClass('checkbox-indeterminate-block');
    }

    if(packageID == '') return;

    var packageAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').length;
    var packageSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']').find('[checked=checked]').length;
    var $packageItem       = $item.closest('tbody').find('.package[data-module=' + moduleName +'][data-package=' + packageID +']');
    if(packageSelectPrivs == 0)
    {
        $packageItem.find('input[type=checkbox]').removeAttr('checked');
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(packageAllPrivs == packageSelectPrivs)
    {
        $packageItem.find('input[type=checkbox]').attr('checked', 'checked');
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $packageItem.find('input[type=checkbox]').removeAttr('checked');
        $packageItem.find('label').addClass('checkbox-indeterminate-block');
    }
}

/**
  * Init comp recomend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initRecomendTree(data)
{
    $(".menuTree.recommend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="depend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') +  '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * Init comp depend-tree.
  *
  * @param  array  data
  * @access public
  * @return void
  */
function initDependTree(data)
{
    $(".menuTree.depend").tree(
    {
        data: data,
        initialState: 'active',
        itemCreator: function($li, item)
        {
            $li.append('<a class="priv-item" data-has-children="' + (item.children ? !!item.children.length : false) + '" href="#" title="' + item.title + '">' + item.title + (item.children ? '' : '<i class="icon icon-close hidden" data-type="recommend" data-privid=' + item.privID + ' data-relationpriv=' + item.relationPriv + '></i>') + '</a>');
            if(item.active) $li.addClass('active open in');
        }
    });
}

/**
  * update comp tree after click checkbox-label.
  *
  * @param  obj objTree
  * @access public
  * @return void
  */
function updatePrivTree(objTree)
{
    $(".menuTree.depend").data('zui.tree').reload(objTree.dependData || [] );
    if(objTree.dependData && objTree.dependData.length)
    {
        $(".menuTree.depend + .empty-tip").addClass('hidden');

        var privCount = 0;
        $.each(objTree.dependData, function(index, item){if(item.children) privCount += item.children.length;});
        $(".menuTree.depend").closest('.priv-panel').find('.panel-title .priv-count').html('(' + privCount + ')');
    }
    else
    {
        $(".menuTree.depend + .empty-tip").removeClass('hidden');
        $(".menuTree.depend").closest('.priv-panel').find('.panel-title .priv-count').html('');
    }
    $(".menuTree.recommend").data('zui.tree').reload(objTree.recommendData || []);
    if(objTree.recommendData && objTree.recommendData.length)
    {
        $(".menuTree.recommend + .empty-tip").addClass('hidden');

        var privCount = 0;
        $.each(objTree.recommendData, function(index, item){if(item.children) privCount += item.children.length;});
        $(".menuTree.recommend").closest('.priv-panel').find('.panel-title .priv-count').html('(' + privCount + ')');
    }
    else
    {
        $(".menuTree.recommend + .empty-tip").removeClass('hidden');
        $(".menuTree.recommend").closest('.priv-panel').find('.panel-title .priv-count').html('');
    }
    if(!canDeleteRelation) $('.side a.priv-item .icon-close').remove();
}

/**
 * Get relation side.
 *
 * @param  int $privID
 * @access public
 * @return void
 */
function getSideRelation(privID)
{
    $.get(createLink('group', 'ajaxGetPrivRelations', "privID=" + privID), function(data)
    {
        var objTree = {};
        if(data)
        {
            var relatedPriv = JSON.parse(data);
            objTree.dependData    = relatedPriv.depend;
            objTree.recommendData = relatedPriv.recommend;
        }
        updatePrivTree(objTree);
    })
}

/**
 * Update depend and recommend privs.
 *
 * @param e $e
 * @access public
 * @return void
 */
function updateRelations(e)
{
    if($('.bg-primary-pale').length) $('.bg-primary-pale').removeClass('bg-primary-pale');
    $('#privListTable').length > 0 ? $(e.target).closest('tr').addClass('bg-primary-pale') : $(e.target).addClass('bg-primary-pale');

    var selectedID = $('#privListTable').length == 0 ? $(e.target).siblings('input:checkbox').data('id') : $(e.target).closest('tr').attr('data-id');
    $('.side a#addDependent').attr('href', createLink('group', 'addRelation', "privIdList=" + selectedID + '&type=depend')).removeAttr('disabled');
    $('.side a#addRecommendation').attr('href', createLink('group', 'addRelation', "privIdList=" + selectedID + '&type=recommend')).removeAttr('disabled');
    if(!$('.side a#addDependent').hasClass('modaled')) $('.side a#addDependent,.side a#addRecommendation').modal().addClass('modaled');
    getSideRelation(selectedID);
}

function checkAllChange()
{
    var id      = $(this).find('input[type=checkbox]').attr('id');
    var checked = $(this).find('input[type=checkbox]').prop('checked');

    if(id == 'allChecker')
    {
        $('input[type=checkbox]').prop('checked', checked);

        if(checked) $('input[type=checkbox]').attr('checked', checked);
        if(!checked) $('input[type=checkbox]').removeAttr('checked');
        $(this).closest('.priv-footer').siblings('#mainContent').find('#privList tbody .checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        var moduleName = $(this).closest('th').attr('data-module');
        var packageID  = $(this).closest('th').hasClass('package') ? $(this).closest('th').attr('data-package') : '';
        var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-module=' + moduleName +'][data-package=' + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

        changeParentChecked($(this), moduleName, packageID);
    }
}

$(function()
{
    relatedPrivData = $.parseJSON(relatedPrivData);
    initDependTree(relatedPrivData.depend);
    if(relatedPrivData.depend) $(".menuTree.depend + .empty-tip").hide();
    initRecomendTree(relatedPrivData.recommend);
    if(relatedPrivData.recommend) $(".menuTree.recommend + .empty-tip").hide();

    $('#privList > tbody > tr > th .check-all').change(checkAllChange);
    $('.priv-footer .check-all').change(checkAllChange);

    $('#privList > tbody > tr > td input[type=checkbox]').change(function()
    {
        var checked = $(this).prop('checked');
        if(checked)
        {
            $(this).attr('checked', 'checked');
        }
        else
        {
            $(this).removeAttr('checked');
        }
        var moduleName = $(this).closest('.group-item').attr('data-module');
        var packageID  = $(this).closest('.group-item').attr('data-package');
        changeParentChecked($(this), moduleName, packageID);
    });

    $('#submit').click(function()
    {
        $('#managePrivForm').submit();
    });
});
