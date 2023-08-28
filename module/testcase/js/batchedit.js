/**
 * Set duplicate field.
 *
 * @param  string $resolution
 * @param  int    $storyID
 * @access public
 * @return void
 */
function setDuplicateAndChild(resolution, storyID)
{
    if(resolution == 'duplicate')
    {
        $('#childStoryBox' + storyID).hide();
        $('#duplicateStoryBox' + storyID).show();
    }
    else if(resolution == 'subdivided')
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).show();
    }
    else
    {
        $('#duplicateStoryBox' + storyID).hide();
        $('#childStoryBox' + storyID).hide();
    }
}

/**
 * Load branches.
 *
 * @param  int    product
 * @param  int    branch
 * @param  int    caseID
 * @param  int    oldBranch
 * @access public
 * @return void
 */
function loadBranches(product, branch, caseID, oldBranch)
{
    if(typeof(branch) == 'undefined') branch = 0;
    if(!branch) branch = 0;

    var result = true;
    if(branch)
    {
        var endLoop = false;
        for(index in testtasks)
        {
            if(endLoop) break;
            if(index == caseID)
            {
                endLoop = true;
                for(taskID in testtasks[index])
                {
                    if(branch != oldBranch && testtasks[index][taskID]['branch'] != branch)
                    {
                        var tip = confirmUnlinkTesttask.replace("%s", caseID);
                        result  = confirm(tip);
                        if(!result) $('#branches' + caseID).val(oldBranch).trigger("chosen:updated");
                        break;
                    }
                }
            }
        }
    }

    if(result)
    {
        var currentModuleID = $('#modules' + caseID).val();
        moduleLink          = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + product + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=' + caseID + '&needManage=false&extra=nodeleted&currentModuleID=' + currentModuleID);
        $('#modules' + caseID).parent('td').load(moduleLink, function()
        {
            $("#modules" + caseID).attr('onchange', "loadStories("+ product + ", this.value, " + caseID + ")").chosen();
        });

        loadStories(product, 0, caseID);
    }
}

$(document).ready(function()
{
     /* Set secondary menu highlighting. */
    if(isLibCase)
    {
      $('#navbar li[data-id=caselib]').addClass('active');
      $('#navbar li[data-id=testcase]').removeClass('active');
    }
})

$(function()
{
    removeDitto();  //Remove 'ditto' in first row.
    if($('th.c-title').width() < 150) $('th.c-title').width(150);
    $('#subNavbar li[data-id="testcase"]').addClass('active');
    if(hasStory)
    {
        $("[name^='story']").each(function()
        {
            var id        = $(this).attr('id');
            var num       = id.substring(5);
            var moduleID  = $('#modules' + num).val();
            var branchID  = typeof($('#branches' + num).val()) == 'undefined' ? 0 : $('#branches' + num).val();
            var storyID   = $("#story" + num).val();
            var storyLink = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=' + storyID +'&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&objectID=0&number=' + num);
            $.get(storyLink, function(stories)
            {
                if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
                $('#story' + num).replaceWith(stories);
                $('#story' + num + "_chosen").remove();
                $('#story' + num).next('.picker').remove();
                $('#story' + num).attr('name', 'story[' + num + ']').picker();
            });
        });
    }

    $('#customField').click(function()
    {
        hiddenRequireFields();
    });
});

$(document).on('click', '.chosen-with-drop', function(){oldValue = $(this).prev('select').val();})//Save old value.

/* Set ditto value. */
$(document).on('change', 'select', function()
{
    if($(this).val() == 'ditto')
    {
        var index = $(this).closest('td').index();
        var row   = $(this).closest('tr').index();
        var tbody = $(this).closest('tr').parent();

        var value = '';
        for(i = row - 1; i >= 0; i--)
        {
            value = tbody.children('tr').eq(i).find('td').eq(index).find('select').val();
            if(value != 'ditto') break;
        }

        if($(this).attr('name').indexOf('modules') != -1)
        {
            var valueStr = ',' + $(this).find('option').map(function(){return $(this).val();}).get().join(',') + ',';
            if(valueStr.indexOf(',' + value + ',') != -1)
            {
                $(this).val(value);
            }
            else
            {
                alert(dittoNotice);
                $(this).val(oldValue);
            }
        }
        else
        {
            $(this).val(value);
        }

        $(this).trigger("chosen:updated");
        $(this).trigger("change");
    }
});

function loadStories2(productID, moduleID, num)
{
    var branch    = $('#branches' + num).val();
    var storyLink = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1&objectID=0&number=' + num);
    $.get(storyLink, function(stories)
    {
        if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
        if(config.currentMethod == 'batchcreate')
        {
            for(var i = num; i < 10 ; i ++)
            {
                if(i != num && $('#module' + i).val() != 'ditto') break;
                var nowStories = stories.replaceAll('story' + num, 'story' + i);
                $('#story' + i).replaceWith(nowStories);
                $('#story' + i + "_chosen").remove();
                $('#story' + i).next('.picker').remove();
                $('#story' + i).attr('name', 'story[' + i + ']');
                $('#story' + i).chosen();
            }
        }
        else
        {
            $('#story' + num).replaceWith(stories);
            $('#story' + num + "_chosen").remove();
            $('#story' + num).next('.picker').remove();
            $('#story' + num).attr('name', 'story[' + num + ']');
            $('#story' + num).chosen();
        }
    });

    const sceneID = $('#scene' + num).val();
    const link = createLink('testcase', 'ajaxGetScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&element=scene&sceneID=' + sceneID + '&number=' + num);
    $.get(link, function(scenes){
        if(!scenes) scenes = '<select id="scene' + num + '" name="scene[' + num + ']" class="form-control"></select>';
        if(config.currentMethod == 'batchcreate')
        {
            for(var i = num; i < 10 ; i ++)
            {
                if(i != num && $('#module' + i).val() != 'ditto') break;
                var nowScenes = scenes.replaceAll('scene' + num, 'scene' + i);
                $('#scene' + i).replaceWith(nowScenes);
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
