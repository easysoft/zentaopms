window.renderRowCol = function($result, col, row)
{
    if(col.name == 'module')
    {
        $result.find('.picker-box').on('inited', function(e, info)
        {
            const $modulePicker = info[0];
            $modulePicker.render({items: modules[row.branch == undefined || row.branch == '' ? 0 : row.branch]});
            $modulePicker.$.setValue(row.module);
        });
    }
    if(col.name == 'story')
    {
        $result.find('.picker-box').on('inited', function(e, info)
        {
            const storyLink = $.createLink('story', 'ajaxGetProductStories', 'productID=' + row.product + '&branch=' + row.branch + '&moduleID=' + row.module + '&storyID=' + row.story + '&onlyOption=false&status=active&limit=0&type=&hasParent=0');
            $.getJSON(storyLink, function(stories)
            {
                let $story = info[0];
                $story.render({items: stories});
                $story.$.setValue(row.story);
            });
        });
    }
    if(col.name == 'scene')
    {
        $result.find('.picker-box').on('inited', function(e, info)
        {
            const sceneLink = $.createLink('testcase', 'ajaxGetProductScenes', 'productID=' + row.product + '&moduleID=' + row.module + '&branch=' + row.branch);
            $.getJSON(sceneLink, function(scenes)
            {
                let $scene = info[0];
                $scene.render({items: scenes});
                $scene.$.setValue(row.scene);
            });
        });
    }
}

function computeImportTimes()
{
    if(parseInt($(this).val()))
    {
        $('#times').html(Math.ceil(parseInt($("#totalAmount").html()) / parseInt($(this).val())));
    }
}

function importNextPage()
{
    $.cookie.set('maxImport', $('#maxImport').val(), {expires:config.cookieLife, path:config.webRoot});
    link = $.createLink('testcase', 'showImport', "productID=" + productID + "&branch=" + branch + "&pageID=1&maxImport=" + $('#maxImport').val());
    loadPage(link);
}

function changeModule(event)
{
    const $target      = $(event.target);
    const moduleID     = $target.val();
    const $currentRow  = $target.closest('tr');
    const $storyPicker = $currentRow.find('.form-batch-control[data-name="story"] .picker').zui('picker');
    const oldStory     = $currentRow.find('input[name^=story]').val();
    const storyLink    = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=active&limit=0&type=&hasParent=0');
    $.getJSON(storyLink, function(stories)
    {
        $storyPicker.render({items: stories});
        $storyPicker.$.setValue(oldStory);
    });

    const $scenePicker = $currentRow.find('.form-batch-control[data-name="scene"] .picker').zui('picker');
    const oldScene     = $currentRow.find('input[name^=scene]').val();
    const sceneLink    = $.createLink('testcase', 'ajaxGetProductScenes', 'productID=' + productID + '&moduleID=' + moduleID + '&branch=' + branch);
    $.getJSON(sceneLink, function(scenes)
    {
        $scenePicker.render({items: scenes});
        $scenePicker.$.setValue(oldScene);
    });
}

function changeBranch(event)
{
    const $target       = $(event.target);
    const branchID      = $target.val();
    const $modulePicker = $target.closest('tr').find('.form-batch-control[data-name="module"] .picker').zui('picker');
    const oldModule     = $modulePicker.$.value;

    $modulePicker.render({items: modules[branchID]});
    $modulePicker.$.setValue(oldModule);
}
