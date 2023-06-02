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
        $('#branch').remove();
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '95px');
        }
    })
}

function loadProductModules(productID)
{
    let branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    const currentModuleID = config.currentMethod == 'edit' ? $('#module').val() : 0;
    const link = $.createLink('testcase', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModuleID);
    $('#moduleBox').load(link);
}

function loadProductStories(productID)
{
    let branch   = $('#branch').val();
    let moduleID = $('#module').val();
    let storyID  = $('#story').val();

    if(typeof(branch)   == 'undefined') branch   = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;
    if(typeof(storyID)  == 'undefined') storyID  = 0;

    const link = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=' + storyID + '&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&executionID=' + executionID);
    $('#storyBox').load(link);
}

function loadScenes(productID)
{
    let branch   = $('#branch').val();
    let moduleID = $('#module').val();
    if(typeof(branch) == 'undefined')   branch = 0;
    if(typeof(moduleID) == 'undefined') moduleID = 0;

    const link = $.createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');
    $('#sceneBox').load(link);
}
