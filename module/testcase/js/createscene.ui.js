function loadProductBranch()
{
    $('#branch').remove();

    var productID = $('#product').val();
    var param     = "productID=" + productID + "&oldBranch=0" + "&param=all";
    $.get($.createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data) $('#product').closest('.input-group').append(data);

        loadProductModule();
    })
}

function loadProductModule()
{
    var productID = $('#product').val();
    var branch    = $('#branch').val();
    if(!branch) branch = 0;

    link = $.createLink('testcase', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=&currentModuleID=0');
    $('#moduleIdBox').load(link, function()
    {
        $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
    });

    setScenes();
}

function loadModuleRelatedNew()
{
    setScenes();
}

function setScenes()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    link = $.createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=1&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');
    $.get(link, function(data)
    {
        if(data)
        {
            $('#parent').remove();
            $('#sceneIdBox').append(data);
        }
    })
}
