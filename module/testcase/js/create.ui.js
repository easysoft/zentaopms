function changeProduct(event)
{
    const productID = $(event.target).val();

    loadProductBranchs(productID);
    loadProductModules(productID);
    loadProductStories(productID);
    loadScenes(productID);
}

function changeBranch(event)
{
    const productID = $('#product').val();
    loadProductModules(productID);
    loadProductStories(productID);
}

function clickRefresh(event)
{
    const productID = $('#product').val();
    loadProductModules(productID);
}

function changeStory(event)
{
    const storyID = $(event.target).val();
    if(storyID)
    {
        const storyLink = $.createLink('story', 'view', 'storyID=' + storyID);
        $('#preview').parent().removeClass('hidden');
        $('#preview').attr('href', storyLink);
    }
    else
    {
        $('#preview').parent().addClass('hidden');
    }
}

function loadProductBranchs(productID)
{
    var param     = config.currentMethod == 'create' ? 'active' : 'all';
    var oldBranch = config.currentMethod == 'edit' ? caseBranch : 0;
    var param     = 'productID=' + productID + '&oldBranch=' + oldBranch + '&param=' + param;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += '&projectID=' + objectID;

    $.get($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#branch').show();

            let $branchPicker = $('[name=branch]').zui('picker');
            data = JSON.parse(data);
            $branchPicker.render({items: data});
            $branchPicker.$.changeState({value: ''});
        }
        else
        {
            $('#branch').hide();
        }
    })
}

function loadProductModules(productID)
{
    let branch = $('[name=branch]').val();
    if(typeof(branch) == 'undefined') branch = 0;

    const currentModuleID = config.currentMethod == 'edit' ? $('[name=module]').val() : 0;
    const getModuleLink   = $.createLink('testcase', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModuleID);

    $.get(getModuleLink, function(data)
    {
        if(data)
        {
            let $modulePicker = $('[name=module]').zui('picker');
            data = JSON.parse(data);
            $modulePicker.render({items: data});
            $modulePicker.$.changeState({value: ''});
        }
    })
}

function loadProductStories(productID)
{
    let branch   = $('[name=branch]').val();
    let moduleID = $('[name=module]').val();
    let storyID  = $('[name=story]').val();

    if(typeof(branch)   == 'undefined') branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    if(typeof(storyID)  == 'undefined') storyID  = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&executionID=' + executionID);
    $.get(link, function(data)
    {
        if(data)
        {
            let $storyPicker = $('[name=story]').zui('picker');
            data = JSON.parse(data);
            $storyPicker.render({items: data});
            $storyPicker.$.changeState({value: ''});
        }
    })
}

function loadScenes(productID)
{
    let branch   = $('[name=branch]').val();
    let moduleID = $('[name=module]').val();
    if(typeof(branch) == 'undefined')   branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;

    const link = $.createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');
    $.get(link, function(data)
    {
        let $scenePicker = $('[name=scene]').zui('picker');
        data = JSON.parse(data);
        $scenePicker.render({items: data});
        $scenePicker.$.changeState({value: ''});
    });
}
