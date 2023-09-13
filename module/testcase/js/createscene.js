function loadAllNew(productID)
{
    loadProductBranchesNew(productID);
}

function loadProductBranchesNew(productID)
{
    $('#branch').remove();

    var param     = page == 'create' ? 'active' : 'all';
    var oldBranch = page == 'edit' ? caseBranch : 0;
    var param     = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + param;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get(createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '95px');
        }

        loadProductModulesNew(productID);
    })
}

function loadProductModulesNew(productID, branch)
{
    if(typeof(branch) == 'undefined') branch = $('#branch').val();
    if(!branch) branch = 0;
    var currentModuleID = config.currentMethod == 'edit' ? $('#module').val() : 0;
    link = createLink('testcase', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=' + currentModuleID);
    $('#moduleIdBox').load(link, function()
    {
        var $inputGroup = $(this);
        $inputGroup.find('select').chosen()
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
        $inputGroup.fixInputGroup();
    });
    setScenes();
}

function setScenes()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=1&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');

    $('#sceneIdBox').load(link, function()
    {
        $(this).find('select').chosen()
    });
}

function loadBranchNew()
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    loadProductModulesNew($('#product').val(), branch);
}

function loadModuleRelatedNew()
{
    setScenes();
}
