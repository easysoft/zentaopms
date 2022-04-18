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
