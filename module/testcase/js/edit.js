/**
 * Get story list.
 *
 * @param  string $module
 * @access public
 * @return void
 */
function getList()
{
    productID = $('#product').get(0).value;
    storyID   = $('#story').get(0).value;
    link = createLink('search', 'select', 'productID=' + productID + '&projectID=0&module=story&moduleID=' + storyID);
    $('#storyListIdBox a').attr("href", link);
}

$(document).ready(function()
{
    /* Set secondary menu highlighting. */
    if(isLibCase)
    {
      $('#navbar li[data-id=caselib]').addClass('active');
      $('#navbar li[data-id=testcase]').removeClass('active');
    }

    $(document).on('change', '[name^=steps], [name^=expects]', function()
    {
        var steps   = [];
        var expects = [];
        var status  = $('#status').val();

        $('[name^=steps]').each(function(){ steps.push($(this).val()); });
        $('[name^=expects]').each(function(){ expects.push($(this).val()); });

        $.post(createLink('testcase', 'ajaxGetStatus', 'methodName=update&caseID=' + caseID), {status : status, steps : steps, expects : expects}, function(status)
        {
            $('#status').val(status).change();
        });
    });

    initSteps();
});

/**
 * Load lib modules.
 *
 * @param  int $libID
 * @access public
 * @return void
 */
function loadLibModules(libID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'libID=' + libID + '&viewtype=caselib&branch=0&rootModuleID=0&returnType=html&fieldID=&needManage=true');
    $('#moduleIdBox').load(link, function()
    {
        $(this).find('select').chosen()
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>")
    });
}

/**
 * Load by branch.
 *
 * @param  int    oldBranch
 * @access public
 * @return void
 */
function loadBranch(oldBranch)
{
    var branch = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;

    var result = true;
    if(branch)
    {
        for(taskID in testtasks)
        {
            if(branch != oldBranch && testtasks[taskID]['branch'] != branch)
            {
                var tip = confirmUnlinkTesttask.replace("%s", caseID);
                result  = confirm(tip);
                if(!result) $('#branch').val(oldBranch).trigger("chosen:updated");
                break;
            }
        }
    }

    if(result)
    {
        loadProductModules($('#product').val(), branch);
        setStories();
    }
}

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
        setStories();
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
    setStories();
}

function setScenes()
{
    moduleID  = $('#module').val();
    productID = $('#product').val();
    branch    = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    link = createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');

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

    setStories();
}

function loadModuleRelatedNew()
{
    setScenes();
    setStories();
}
