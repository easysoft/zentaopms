$(function()
{
    selectedPrivIdList = Object.values(selectedPrivIdList);
    recommendSelect = new Array();

    $('.priv-footer').on('change', '.check-all', checkAllChange);
    $('#privList').on('change', 'tbody > tr > th .check-all', checkAllChange);
    $('#privPackageList').on('change', 'tbody > tr .check-all', checkAllChange);
    $('#privList').on('change', 'tbody > tr .group-item input[type=checkbox]', groupItemChange);
    $('#privPackageList .package-column').on('click', '.privs.popover input[type=checkbox]', groupItemChange);
});

function showPriv()
{
    loadPage($.createLink('group', 'managePriv', "type=" + type + "&param="+ groupID + "&menu=&version=" + $('input[name=version]').val()));
}

window.handleSideRecommentCheckClick = function($target)
{
    const checked = $target.prop('checked');
    if($target.attr('data-has-children') == 'true')
    {
        $target.closest('.checkbox-group').find('ul > li input[type=checkbox]').each(function(){
            recommendChange($target, checked);
        });
    }
    else
    {
        recommendChange($target, checked);
    }
    updatePrivTree(selectedPrivIdList);
};

window.handlePrivPackageListClick = function(event)
{
    const $target = $(event.target);

    const $privToggle = $target.closest('.package > .priv-toggle.icon');
    if($privToggle.length) return handlePrivToggleClick($privToggle);

    const $privsPrivToggle = $target.closest('.privs .priv-toggle.icon');
    if($privsPrivToggle.length) return handlePrivsPrivToggle($privsPrivToggle);

    const $menuPrivsCheck = $target.closest('.package-column .menus-privs input[type=checkbox]');
    if($menuPrivsCheck.length) return handleMenuPrivsCheck($menuPrivsCheck);

    const $privsCheckAll = $target.closest('.package-column .privs .check-all');
    if($privsCheckAll.length) return handlePropvsCheckAll($privsCheckAll);
}

function handlePrivToggleClick($target)
{
    let thisIsOpen = $target.hasClass('open');

    $target.closest('#privPackageList').find('.priv-toggle.open').removeClass('open');
    $('.privs.popover').remove();
    if(thisIsOpen) return;

    const $td            = $target.closest('td');
    const $packages      = $td.find('.package');
    const $parentPackage = $target.closest('.package');

    $target.addClass('open');
    var moduleName = $parentPackage.attr('data-module');
    var packageID  = $parentPackage.attr('data-package');

    /* The privs should be inserted after which permission package. */
    var perRowPackages = Math.floor($td.width() / $packages.width());
    var packageIndex   = $parentPackage.index() / 2 ;
    var appendIndex    = (Math.floor(packageIndex / perRowPackages) + 1) * perRowPackages - 1;

    var $privs     = $(".privs.hidden[data-module='" + moduleName + "'][data-package='" + packageID + "']")
    var $showPrivs = $('<div class="privs popover bottom" data-module="' + moduleName + '" data-package="' + packageID + '">'
        + $privs.html().replace(/actions/g, 'showPrivs')
        + '</div>');

    /* Calculate the triangle position of privs popover. */
    var position = $packages.width() * (packageIndex % perRowPackages) + 15;

    $showPrivs.find('.arrow').css('left', position + 'px');

    if($packages.eq(appendIndex).length == 0)
    {
        $packages.eq(-1).after($showPrivs);
        $showPrivs.css('margin-bottom', '0');
    }
    else
    {
        $packages.eq(appendIndex).after($showPrivs);
    }
}

function handlePrivsPrivToggle($target)
{
    var opened = $target.hasClass('open');

    $('#privPackageList .group-item > .priv-toggle.icon').removeClass('open');
    $('.menus-privs.popover').remove();

    if(!opened)
    {
        $target.addClass('open');
        var moduleName     = $target.closest('.privs').attr('data-module');
        var packageID      = $target.closest('.privs').attr('data-package');

        /* The menus privs should be inserted after which priv. */
        var perRowPrivs = Math.floor($target.closest('.popover-content').width() / $target.closest('.popover-content').find('.group-item').width());
        var privIndex   = $target.closest('.group-item').index();
        var appendIndex = (Math.floor(privIndex / perRowPrivs) + 1) * perRowPrivs - 1;

        var $menusPrivs = $target.closest('.group-item').find('.menus-privs')
        var $showPrivs  = $('<div class="menus-privs popover bottom" data-module="' + moduleName + '" data-package="' + packageID + '">' + $menusPrivs.html() + '</div>');

        /* Calculate the triangle position of privs popover. */
        var position = $target.closest('.popover-content').width() * (privIndex % perRowPrivs) + 30;

        $showPrivs.find('.arrow').css('left', position + 'px');

        if($target.closest('.popover-content').find('.group-item:not(.menus-item)').eq(appendIndex).length == 0)
        {
            $showPrivs.css('margin-bottom', '0');
            $target.closest('.popover-content').find('.group-item:not(.menus-item)').eq(-1).after($showPrivs);
        }
        else
        {
            $target.closest('.popover-content').find('.group-item:not(.menus-item)').eq(appendIndex).after($showPrivs);
        }
    }
}

function handleMenuPrivsCheck($target)
{
    var $menusPrivs  = $target.closest('.popover-content').find('.menus-item');
    var $parentPrivs = $target.closest('.privs').find('.checkbox-primary.check-all');
    var allPrivs     = $menusPrivs.length;
    var selectPrivs  = $menusPrivs.find('input[type=checkbox]:checked').length;

    if(allPrivs > 0 && allPrivs == selectPrivs)
    {
        $parentPrivs.find('input').attr('checked', true);
        $parentPrivs.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(selectPrivs == 0)
    {
        $parentPrivs.find('input').removeAttr('checked');
        $parentPrivs.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $parentPrivs.find('input').removeAttr('checked');
        $parentPrivs.find('label').addClass('checkbox-indeterminate-block');
    }
    groupItemChange();
}

function handlePropvsCheckAll($target)
{
    var checked   = $target.find('input[type=checkbox]').prop('checked');
    var $children = $target.closest('.package-column').find('.menus-privs .menus-item');
    if(checked)
    {
        $children.find('input').attr('checked', true);
    }
    else
    {
        $children.find('input').removeAttr('checked');
    }
    $children.find('label').removeClass('checkbox-indeterminate-block');
    $target.find('label').removeClass('checkbox-indeterminate-block');
    changeParentChecked($target, $target.closest('.group-item').attr('data-module'), $target.closest('.group-item').attr('data-package'));
};

/**
 * When check all change.
 *
 * @access public
 * @return void
 */
function checkAllChange()
{
    var id      = $(this).find('input[type=checkbox]').attr('id');
    var checked = $(this).find('input[type=checkbox]').prop('checked');

    if(id == 'allChecker')
    {
        $('input[type=checkbox]').prop('checked', checked);

        if(checked) $('input[type=checkbox]').attr('checked', checked);
        if(!checked) $('input[type=checkbox]').removeAttr('checked');
        $('tbody .checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');
    }
    else if($(this).closest('#privList').length > 0)
    {
        var moduleName = $(this).closest('th').attr('data-module');
        var packageID  = $(this).closest('th').hasClass('package') ? $(this).closest('th').attr('data-package') : '';
        var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-divid=' + moduleName + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

        changeParentChecked($(this), moduleName, packageID);
    }
    else if($(this).closest('#privPackageList').length > 0)
    {
        var moduleName = $(this).parent().attr('data-module');
        var packageID  = $(this).parent().hasClass('package') ? $(this).parent().attr('data-package') : '';
        var $children  = $(this).parent().hasClass('package') ? $(this).closest('td').find('[data-divid=' + moduleName + packageID + ']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

        changeParentChecked($(this), moduleName, packageID);
    }
    updatePrivTree(null);
}

/**
 * Change parent item checked.
 *
 * @access public
 * @return void
 */
function changeParentChecked($item, moduleName, packageID)
{
    var moduleAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName + ']:not(.menus-browse)').length;
    var moduleSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName + ']:not(.menus-browse)').find('input[type=checkbox]:checked').length;
    var $moduleItem       = $item.closest('tbody').find('.module[data-module=' + moduleName + ']');
    if($item.closest('tbody').find('.menus.' + moduleName).length > 0)
    {
        moduleAllPrivs    += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:not(input[value=browse])').length;
        moduleSelectPrivs += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:checked:not(input[value=browse])').length;
    }
    if(moduleSelectPrivs == 0)
    {
        $moduleItem.find('input[type=checkbox]').prop('checked', false);
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(moduleAllPrivs == moduleSelectPrivs)
    {
        $moduleItem.find('input[type=checkbox]').prop('checked', true);
        $moduleItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $moduleItem.find('input[type=checkbox]').prop('checked', false);
        $moduleItem.find('label').addClass('checkbox-indeterminate-block');
    }

    if(packageID == '')
    {
        const allModules        = $item.closest('tbody').find('.module input[type=checkbox]').length;
        const allCheckedModules = $item.closest('tbody').find('.module input[type=checkbox]:checked').length;
        $('#allChecker').prop('checked', allModules == allCheckedModules);
        return;
    }

    var packageAllPrivs    = $item.closest('tbody').find('.group-item[data-divid=' + moduleName + packageID + ']:not(.menus-browse)').length;
    var packageSelectPrivs = $item.closest('tbody').find('.group-item[data-divid=' + moduleName + packageID + ']:not(.menus-browse)').find('input[type=checkbox]:checked').length;
    var $packageItem       = $item.closest('tbody').find('.package[data-divid=' + moduleName + packageID + ']');
    if($item.closest('tbody').find('.menus.' + moduleName).length > 0)
    {
        packageAllPrivs    += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:not(input[value=browse])').length;
        packageSelectPrivs += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:checked:not(input[value=browse])').length;
    }
    if(packageSelectPrivs == 0)
    {
        $packageItem.find('input[type=checkbox]').prop('checked', false);
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(packageAllPrivs == packageSelectPrivs)
    {
        $packageItem.find('input[type=checkbox]').prop('checked', true);
        $packageItem.find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $packageItem.find('input[type=checkbox]').prop('checked', false);
        $packageItem.find('label').addClass('checkbox-indeterminate-block');
    }

    const allModules        = $item.closest('tbody').find('.module input[type=checkbox]').length;
    const allCheckedModules = $item.closest('tbody').find('.module input[type=checkbox]:checked').length;
    $('#allChecker').prop('checked', allModules == allCheckedModules);
}

/**
  * update comp tree after click checkbox-label.
  *
  * @param  obj objTree
  * @access public
  * @return void
  */
function updatePrivTree(privList)
{
    if(privList == null)
    {
        privList = new Array();
        $('tbody .group-item input:checked').each(function()
        {
            var privID = $(this).closest('.group-item').attr('data-id');
            if($(this).closest('popover-content').length == 0 && privList.indexOf(privID) < 0) privList.push(privID);
        });
        selectedPrivIdList = privList;
    }

    loadTarget($.createLink('group', 'ajaxGetRelatedPrivs'), '.side',
    {
        method: 'post',
        data: {"selectPrivList" : privList.toString(), "recommendSelect": recommendSelect.toString(), "allPrivList": Object.values(allPrivList).toString()},
        beforeUpdate: (data) =>
        {
            const $side = $('.side');
            $side.children('.zin-page-css,.zin-page-js,.priv-panel').remove();
            $side.append(data);
            toggleLoading($side, false);
            return false;
        }
    });
}

/**
 * Control the packages select control for a module.
 *
 * @access  public
 * @return  void
 */
window.setSubsetPackages = function()
{
    let subset = $(this).val();
    $('#packageBox select').addClass('hidden');                      // Hide all select first.
    $('#packageBox select').val('');                                 // Unselect all select.
    $("select[data-module='" + subset + "']").removeClass('hidden'); // Show the action control for current module.

    updatePrivList(subset, '');
}

/**
 * Update the action box when subset or package selected.
 *
 * @param  string  selectedSubset
 * @param  string  selectedPackages
 * @access public
 * @return void
 */
function updatePrivList(selectedSubset, selectedPackages)
{
    $.get($.createLink('group', 'ajaxGetPrivByParents', 'subset=' + selectedSubset + '&packages=' + selectedPackages), function(data)
    {
        $('#actions').replaceWith(data);
    })
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

    var selectedSubset   = $('[name=module]').val();
    var selectedPackages = '|' + $('#packageBox select').not('.hidden').val().join('|') + '|';

    updatePrivList(selectedSubset, selectedPackages);
}

window.setNoChecked = function()
{
    var noCheckValue = '';
    $('tbody .group-item > div > div > input').each(function()
    {
        if(!$(this).prop('checked') && $(this).attr('data-id') != undefined) noCheckValue = noCheckValue + ',' + $(this).attr('data-id');
    });
    $('#noChecked').val(noCheckValue);
}

/**
 * Whrn group item change.
 *
 * @param  object $item
 * @access public
 * @return void
 */
function groupItemChange()
{
    var checked = $(this).prop('checked');
    if(checked)
    {
        $(this).attr('checked', true);
    }
    else
    {
        $(this).removeAttr('checked');
    }
    if($('#privPackageList').length > 0)
    {
        var dataid = $(this).attr('data-id');
        var $priv  = $('#privPackageList').find('.group-item input[data-id="' + dataid + '"]');
        if(checked)
        {
            $priv.attr('checked', true);
        }
        else
        {
            $priv.removeAttr('checked');
        }
    }

    var moduleName = $(this).closest('.group-item').attr('data-module');
    var packageID  = $(this).closest('.group-item').attr('data-package');
    changeParentChecked($(this), moduleName, packageID);

    var privID = $(this).closest('.group-item').attr('data-id');
    if(privID)
    {
        var index = selectedPrivIdList.indexOf(privID);

        if(index < 0 && checked) selectedPrivIdList.push(privID);
        if(index > -1 && !checked) selectedPrivIdList.splice(index, 1);

        updatePrivTree(selectedPrivIdList);
    }
}

/**
 * When recommend privs change.
 *
 * @param  object $item
 * @param  bool   $checked
 * @access public
 * @return void
 */
function recommendChange($item, checked)
{
    var privModule  = $item.attr('data-module');
    var privMethod  = $item.attr('data-method');
    var $actionItem = $('#privList').length > 0 ? $('#privList input[data-id="' + privModule + '-' + privMethod + '"]') : $('#privPackageList input[data-id="' + privModule + '-' + privMethod + '"]');
    $actionItem.prop('checked', checked);
    if(checked)
    {
        $item.attr('checked', true);
        $actionItem.attr('checked', true);
    }
    else
    {
        $item.removeAttr('checked');
        $actionItem.removeAttr('checked');
    }
    var moduleName = $actionItem.closest('tr').find('.package').attr('data-module');
    var packageID  = $('#privList').length > 0 ? $actionItem.closest('tr').find('.package').attr('data-package') : $actionItem.closest('.privs').attr('data-package');
    changeParentChecked($actionItem, moduleName, packageID);

    var $parentItem       = $item.closest('ul').closest('li').find('input[data-has-children="true"]');
    var allItemLength     = $item.closest('ul').find('input[type=checkbox]').length;
    var checkedItemLength = $item.closest('ul').find('input[type=checkbox]:checked').length;
    if(checkedItemLength == 0)
    {
        $parentItem.removeAttr('checked');
        $parentItem.closest('.checkbox-primary').find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(allItemLength == checkedItemLength)
    {
        $parentItem.attr('checked', true);
        $parentItem.closest('.checkbox-primary').find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $parentItem.removeAttr('checked');
        $parentItem.closest('.checkbox-primary').find('label').addClass('checkbox-indeterminate-block');
    }

    var privID = $item.attr('data-id');
    if(privID)
    {
        var index = selectedPrivIdList.indexOf(privID);

        if(index < 0 && checked) selectedPrivIdList.push(privID);
        if(index > -1 && !checked) selectedPrivIdList.splice(index, 1);

        index = recommendSelect.indexOf(privID);

        if(index < 0 && checked) recommendSelect.push(privID);
        if(index > -1 && !checked) recommendSelect.splice(index, 1);
    }
}
