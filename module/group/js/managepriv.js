function showPriv(value)
{
  location.href = createLink('group', 'managePriv', "type=" + type + "&param="+ groupID + "&menu=&version=" + value);
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

    updatePrivList(module, '');
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

    var selectedModule   = $('#module').val();
    var selectedPackages = ',' + $('#packageBox select').not('.hidden').val().join(',') + ',';

    updatePrivList(selectedModule, selectedPackages);

}

function setNoChecked()
{
    var noCheckValue = '';
    $('tbody .group-item > div > div > input').each(function()
    {
        if(!$(this).prop('checked') && $(this).attr('data-id') != undefined) noCheckValue = noCheckValue + ',' + $(this).attr('data-id');
    });
    $('#noChecked').val(noCheckValue);
}

/**
 * Update the action box when module or package selected.
 *
 * @param  string  selectedModule
 * @param  string  selectedPackages
 * @access public
 * @return void
 */
function updatePrivList(selectedModule, selectedPackages)
{
    $.get(createLink('group', 'ajaxGetPrivByParents', 'module=' + selectedModule + '&packages=' + selectedPackages), function(data)
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
    var moduleAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']:not(.menus-browse)').length;
    var moduleSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +']:not(.menus-browse)').find('[checked=checked]').length;
    var $moduleItem       = $item.closest('tbody').find('.module[data-module=' + moduleName +']');
    if($item.closest('tbody').find('.menus.' + moduleName).length > 0)
    {
        moduleAllPrivs    += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:not(input[value=browse])').length;
        moduleSelectPrivs += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions][checked=checked]:not(input[value=browse])').length;
    }
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

    var packageAllPrivs    = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']:not(.menus-browse)').length;
    var packageSelectPrivs = $item.closest('tbody').find('.group-item[data-module=' + moduleName +'][data-package=' + packageID +']:not(.menus-browse)').find('[checked=checked]').length;
    var $packageItem       = $item.closest('tbody').find('.package[data-module=' + moduleName +'][data-package=' + packageID +']');
    if($item.closest('tbody').find('.menus.' + moduleName).length > 0)
    {
        packageAllPrivs    += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions]:not(input[value=browse])').length;
        packageSelectPrivs += $item.closest('tbody').find('.menus.' + moduleName + ' input[name^=actions][checked=checked]:not(input[value=browse])').length;
    }
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
        initialState: 'expand',
        itemCreator: function($li, item)
        {
            var privID = item.module + '-' + item.method;
            var index = recommendSelect.indexOf(privID);
            $li.append('<div class="checkbox-primary ' + (item.children ? 'check-all' : '') + '"><input ' + (index >= 0 ? 'checked="checked"' : '') + 'data-has-children="' + (item.children ? !!item.children.length : false) + '"' + (item.children ? '' : 'data-type="recommend" data-privid="' + privID + '" data-relationpriv="' + privID + '"') + 'type="checkbox" name="recommendPrivs[]" value="' + privID + '" title="' + item.title + '" id="recommendPrivs[' + item.module + ']' + item.method + '" data-module="' + item.module + '" data-method="' + item.method + '"><label>' + item.title + '</label></div>');
        }
    });
    $('.menuTree.recommend i.list-toggle').remove();
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
        initialState: 'expand',
        itemCreator: function($li, item)
        {
            var privID = item.module + '-' + item.method;
            $li.append('<div class="checkbox-primary"><input disabled="disabled" checked="checked" data-has-children="' + (item.children ? !!item.children.length : false) + '"' + (item.children ? '' : 'data-type="depend" data-privid="' + privID + '" data-relationpriv="' + item.relationPriv + '"') + 'type="checkbox" name="dependPrivs[]" value="' + item.relationPriv + '" title="' + item.title + '" id="dependPrivs[' + item.module + ']' + item.method + '" data-module="' + item.module + '" data-method="' + item.method + '"><label>' + item.title + '</label></div>');
        }
    });
    $('.menuTree.depend i.list-toggle').remove();
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
            var priv = $(this).attr('data-id');
            if($(this).closest('popover-content').length == 0 && privList.indexOf(priv) < 0) privList.push(priv);
        });
        selectedPrivList = privList;
    }

    $.ajax(
    {
        url: createLink('group', 'ajaxGetRelatedPrivs'),
        dataType: 'json',
        method: 'post',
        data: {"selectPrivList": privList.toString(), "recommendSelect": recommendSelect.toString(), "allPrivList": Object.values(allPrivList).toString()},
        success: function(data)
        {
            if(data.depend == undefined || data.depend.length == 0)
            {
                $('.side .menuTree.depend').empty();
                $('.side .menuTree.depend').closest('.priv-panel').find('.table-empty-tip').removeClass('hidden');
            }
            else
            {
                $(".menuTree.depend").data('zui.tree').reload(data.depend);
                $('.side .menuTree.depend').closest('.priv-panel').find('.table-empty-tip').addClass('hidden');
            }

            if(data.recommend == undefined || data.recommend.length == 0)
            {
                $('.side .menuTree.recommend').empty();
                $('.side .menuTree.recommend').closest('.priv-panel').find('.table-empty-tip').removeClass('hidden');
            }
            else
            {
                $(".menuTree.recommend").data('zui.tree').reload(data.recommend);
                $('.side .menuTree.recommend').closest('.priv-panel').find('.table-empty-tip').addClass('hidden');
                $('.menuTree.recommend > li').each(function(){
                    var allItemLength     = $(this).find('ul input[type=checkbox]').length;
                    var checkedItemLength = $(this).find('ul input[type=checkbox][checked=checked]').length;
                    if(checkedItemLength > 0 && allItemLength == checkedItemLength)
                    {
                        $(this).find('.check-all input').attr('checked', 'checked');
                    }
                    else if(checkedItemLength > 0)
                    {
                        $(this).find('.check-all label').addClass('checkbox-indeterminate-block');
                    }
                });
            }

            $('.menuTree i.list-toggle').remove();
            $('.menuTree li.has-list').addClass('open');
        }
    });
}

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
        var $children  = $(this).closest('th').hasClass('package') ? $(this).closest('tbody').find('[data-module=' + moduleName +'][data-package=' + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

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
        var $children  = $(this).parent().hasClass('package') ? $(this).closest('td').find('[data-module=' + moduleName +'][data-package=' + packageID +']') : $(this).closest('tbody').find('[data-module=' + moduleName +']');

        $children.find('input[type=checkbox]').prop('checked', checked);
        $children.find('.checkbox-indeterminate-block').removeClass('checkbox-indeterminate-block');

        if(checked) $children.find('input[type=checkbox]').attr('checked', checked);
        if(!checked) $children.find('input[type=checkbox]').removeAttr('checked');

        changeParentChecked($(this), moduleName, packageID);
    }
    updatePrivTree(null);
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
        $(this).attr('checked', 'checked');
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
            $priv.attr('checked', 'checked');
        }
        else
        {
            $priv.removeAttr('checked');
        }
    }
    var moduleName = $(this).closest('.group-item').attr('data-module');
    var packageID  = $(this).closest('.group-item').attr('data-package');
    changeParentChecked($(this), moduleName, packageID);

    var priv = $(this).attr('data-id');
    if(priv != '')
    {
        var index = selectedPrivList.indexOf(priv);

        if(priv && index < 0 && checked) selectedPrivList.push(priv);
        if(priv && index > -1 && !checked) selectedPrivList.splice(index, 1);

        updatePrivTree(selectedPrivList);
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
        $item.attr('checked', 'checked');
        $actionItem.attr('checked', 'checked');
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
    var checkedItemLength = $item.closest('ul').find('input[type=checkbox][checked=checked]').length;
    if(checkedItemLength == 0)
    {
        $parentItem.removeAttr('checked');
        $parentItem.closest('.checkbox-primary').find('label').removeClass('checkbox-indeterminate-block');
    }
    else if(allItemLength == checkedItemLength)
    {
        $parentItem.attr('checked', 'checked');
        $parentItem.closest('.checkbox-primary').find('label').removeClass('checkbox-indeterminate-block');
    }
    else
    {
        $parentItem.removeAttr('checked');
        $parentItem.closest('.checkbox-primary').find('label').addClass('checkbox-indeterminate-block');
    }

    var priv = $item.attr('data-module') + '-' + $item.attr('data-method');
    if(priv != '-')
    {
        var index = selectedPrivList.indexOf(priv);

        if(priv && index < 0 && checked) selectedPrivList.push(priv);
        if(priv && index > -1 && !checked) selectedPrivList.splice(index, 1);

        index = recommendSelect.indexOf(priv);

        if(priv && index < 0 && checked) recommendSelect.push(priv);
        if(priv && index > -1 && !checked) recommendSelect.splice(index, 1);
    }
}

/**
 * Change btn position.
 *
 * @access public
 * @return void
 */
function positionBtn()
{
    var btnLeft = $('.main.main-content > table').width() - 63;
    $('.main.main-content > .btn-group').css('left', btnLeft).css('visibility', 'unset');
}

$(function()
{
    if($(".side .icon-help").length > 0) $(".side .icon-help").popover();

    positionBtn();
    $('.main.main-content').resize(positionBtn);

    selectedPrivList = Object.values(selectedPrivList);

    recommendSelect = new Array();

    relatedPrivData = $.parseJSON(relatedPrivData);
    initDependTree(relatedPrivData.depend);
    if(relatedPrivData.depend == undefined || relatedPrivData.depend == 0) $(".menuTree.depend").closest('.priv-panel').find('.table-empty-tip').removeClass('hidden');
    initRecomendTree(relatedPrivData.recommend);
    if(relatedPrivData.recommend == undefined || relatedPrivData.recommend == 0) $(".menuTree.recommend").closest('.priv-panel').find('.table-empty-tip').removeClass('hidden');

    $('#privList > tbody > tr > th .check-all').change(checkAllChange);
    $('#privPackageList > tbody > tr .check-all').change(checkAllChange);
    $('.priv-footer .check-all').change(checkAllChange);

    $('#privList tbody > tr .group-item input[type=checkbox]').change(groupItemChange);
    $('#privPackageList .package-column').on('click', '.privs.popover input[type=checkbox]', groupItemChange);

    $('.side').on('click', '.recommend input[type=checkbox]', (function()
    {
        var checked = $(this).prop('checked');
        if($(this).attr('data-has-children') == 'true')
        {
            $(this).closest('li').find('ul > li input[type=checkbox]').each(function(){
                recommendChange($(this), checked);
            });
        }
        else
        {
            recommendChange($(this), checked);
        }
        updatePrivTree(selectedPrivList);
    }));

    $('#privPackageList .package > .priv-toggle.icon').click(function()
    {
        var opened = $(this).hasClass('open');

        $('#privPackageList .package > .priv-toggle.icon').removeClass('open');
        $('.privs.popover').remove();

        if(!opened)
        {
            $(this).addClass('open');
            var moduleName = $(this).closest('.package').attr('data-module');
            var packageID  = $(this).closest('.package').attr('data-package');

            /* The privs should be inserted after which permission package. */
            var perRowPackages = Math.floor($(this).closest('td').width() / $(this).closest('td').find('.package').width());
            var packageIndex   = $(this).closest('.package').index() / 2 ;
            var appendIndex    = (Math.floor(packageIndex / perRowPackages) + 1) * perRowPackages - 1;

            var $privs     = $(".privs.hidden[data-module='" + moduleName + "'][data-package='" + packageID + "']")
            var $showPrivs = $('<div class="privs popover bottom" data-module="' + moduleName + '" data-package="' + packageID + '">'
              + $privs.html().replace(/actions/g, 'showPrivs')
              + '</div>');

            /* Calculate the triangle position of privs popover. */
            var position = $(this).closest('td').find('.package').width() * (packageIndex % perRowPackages) + 30;

            $showPrivs.find('.arrow').css('left', position + 'px');

            if($(this).closest('td').find('.package').eq(appendIndex).length == 0)
            {
                $(this).closest('td').find('.package').eq(-1).after($showPrivs);
                $showPrivs.css('margin-bottom', '0');
            }
            else
            {
                $(this).closest('td').find('.package').eq(appendIndex).after($showPrivs);
            }
        }
    });

    $('#privPackageList .package-column').on('click', '.privs .priv-toggle.icon', (function()
    {
        var opened = $(this).hasClass('open');

        $('#privPackageList .group-item > .priv-toggle.icon').removeClass('open');
        $('.menus-privs.popover').remove();

        if(!opened)
        {
            $(this).addClass('open');
            var moduleName     = $(this).closest('.privs').attr('data-module');
            var packageID      = $(this).closest('.privs').attr('data-package');

            /* The menus privs should be inserted after which priv. */
            var perRowPrivs = Math.floor($(this).closest('.popover-content').width() / $(this).closest('.popover-content').find('.group-item').width());
            var privIndex   = $(this).closest('.group-item').index();
            var appendIndex = (Math.floor(privIndex / perRowPrivs) + 1) * perRowPrivs - 1;

            var $menusPrivs = $(this).closest('.group-item').find('.menus-privs')
            var $showPrivs  = $('<div class="menus-privs popover bottom" data-module="' + moduleName + '" data-package="' + packageID + '">' + $menusPrivs.html() + '</div>');

            /* Calculate the triangle position of privs popover. */
            var position = $(this).closest('.popover-content').width() * (privIndex % perRowPrivs) + 30;

            $showPrivs.find('.arrow').css('left', position + 'px');

            if($(this).closest('.popover-content').find('.group-item:not(.menus-item)').eq(appendIndex).length == 0)
            {
                $showPrivs.css('margin-bottom', '0');
                $(this).closest('.popover-content').find('.group-item:not(.menus-item)').eq(-1).after($showPrivs);
            }
            else
            {
                $(this).closest('.popover-content').find('.group-item:not(.menus-item)').eq(appendIndex).after($showPrivs);
            }
        }
    }));

    $('#privPackageList .package-column').on('click', '.menus-privs input[type=checkbox]', (function()
    {
        var $menusPrivs  = $(this).closest('.popover-content').find('.menus-item');
        var $parentPrivs = $(this).closest('.privs').find('.checkbox-primary.check-all');
        var allPrivs     = $menusPrivs.length;
        var selectPrivs  = $menusPrivs.find('input[checked=checked]').length;

        if(allPrivs > 0 && allPrivs == selectPrivs)
        {
            $parentPrivs.find('input').attr('checked', 'checked');
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
    }));

    $('#privPackageList .package-column').on('click', '.privs .check-all', (function()
    {
        var checked   = $(this).find('input[type=checkbox]').prop('checked');
        var $children = $(this).closest('.package-column').find('.menus-privs .menus-item');
        if(checked)
        {
            $children.find('input').attr('checked', 'checked');
        }
        else
        {
            $children.find('input').removeAttr('checked');
        }
        $children.find('label').removeClass('checkbox-indeterminate-block');
        $(this).find('label').removeClass('checkbox-indeterminate-block');
        changeParentChecked($(this), $(this).closest('.group-item').attr('data-module'), $(this).closest('.group-item').attr('data-package'));
    }));
});
