window.renderRowCol = function($result, col, row)
{
    if(col.name == 'module')
    {
        $result.find('.picker-box').on('inited', function(e, info)
        {
            const $modulePicker = info[0];
            $modulePicker.render({items: modules[row.branch != undefined ? row.branch : 0]});
            $modulePicker.$.setValue(row.module);
        });
    }
    if(col.name == 'story')
    {
        $result.find('.picker-box').on('inited', function(e, info)
        {
            const $storyPicker = info[0];
            $storyPicker.render({items: stories[row.module]});
            $storyPicker.$.setValue(row.story);
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
    const $storyPicker = $target.closest('tr').find('.form-batch-control[data-name="story"] .picker').zui('picker');
    const oldStory     = $storyPicker.$.value;

    $storyPicker.render({items: stories[moduleID]});
    $storyPicker.$.setValue(oldStory);
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
