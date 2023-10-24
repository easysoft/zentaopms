$(document).ready(function()
{
    removeDitto();//Remove 'ditto' in first row.
    var $title = $('#batchCreateForm table thead tr th.c-title');
    if($title.width() < 170) $title.width('170');

    $(document).keydown(function(event)
    {
        if(event.ctrlKey && event.keyCode == 38)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('up');
        }
        else if(event.ctrlKey && event.keyCode == 40)
        {
            event.stopPropagation();
            event.preventDefault();
            selectFocusJump('down');
        }
        else if(event.keyCode == 38)
        {
            inputFocusJump('up');
        }
        else if(event.keyCode == 40)
        {
            inputFocusJump('down');
        }
    });

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });

    /* Implement a custom form without feeling refresh. */
    $('#formSettingForm .btn-primary').click(function()
    {
        saveCustomFields('batchCreateFields', 8, $title, 170);
        return false;
    });
});

function findRealRowIndex(num)
{
    var $sel = $('#module' + num);
    var $tr = $sel.closest("tr");
    return $tr.get(0).rowIndex-1;
}

function findModuleID(moduleID, num)
{
    if(moduleID != "ditto")
        return moduleID;

    var rIndex = findRealRowIndex(num);

    var trList = $("#tableBody").find("tbody").find("tr");
    for(var i=rIndex-1; i>=0; i--)
    {
        var currentID = $(trList[i]).find("td:eq(2)").find("select").val();
        if(currentID != "ditto")
        {
            moduleID = currentID;
            break;
        }
    }

    return moduleID;
}

function canSceneDitto(num)
{
    var rIndex = findRealRowIndex(num);
    if(rIndex == 0) return false;

    var trList = $("#tableBody").find("tbody").find("tr");

    var rowModule = $(trList[rIndex]).find("td:eq(2)").find("select").val();
    var preModule = $(trList[rIndex-1]).find("td:eq(2)").find("select").val();

    return rowModule == "ditto" || rowModule == preModule;
}

function onModuleChanged(productID, moduleID, num)
{
    loadStories(productID, moduleID, num);
    loadScenes(productID, moduleID, num);
}

function loadScenes(productID, moduleID, num)
{
    moduleID = findModuleID(moduleID, num);

    var branchIDName = (config.currentMethod == 'batchcreate' || config.currentMethod == 'showimport') ? '#branch' : '#branches';
    var branchID     = $(branchIDName + num).val();
    if(!branchID) branchID = 0;

    var sceneLink = createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&element=scene&sceneID=0&number=' + num + '&ditto=1');
    $.get(sceneLink, function(scenes)
    {
        if(!scenes) scenes = '<select id="scene' + num + '" name="scene[' + num + ']" class="form-control"></select>';
        if(config.currentMethod == 'batchcreate')
        {
            for(var i = num; i <= rowIndex ; i ++)
            {
                var nowScenes = scenes.replaceAll('scene' + num, 'scene' + i);
                $('#scene' + i).replaceWith(nowScenes);
                if(canSceneDitto(i) == false) $('#scene' + i).find("option:last").remove();
                $('#scene' + i + "_chosen").remove();
                $('#scene' + i).next('.picker').remove();
                $('#scene' + i).attr('name', 'scene[' + i + ']');
                $('#scene' + i).chosen();
            }
        }
        else
        {
            $('#scene' + num).replaceWith(scenes);
            $('#scene' + num + "_chosen").remove();
            $('#scene' + num).next('.picker').remove();
            $('#scene' + num).attr('name', 'scene[' + num + ']');
            $('#scene' + num).chosen();
        }
    });
}
