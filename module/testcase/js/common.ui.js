$(document).off('click', '.batch-btn').on('click', '.batch-btn', function()
{
    const dtable = zui.DTable.query($(this).target);
    const checkedList = dtable.$.getChecks();
    if(!checkedList.length) return;

    const url  = $(this).data('url');
    const form = new FormData();
    checkedList.forEach((id) => form.append('caseIdList[]', id));

    if($(this).hasClass('ajax-btn'))
    {
        $.ajaxSubmit({url, data:form});
    }
    else
    {
        postAndLoadPage(url, form);
    }
});

/**
 * Set stories.
 *
 * @param  int     productID
 * @param  int     moduleID
 * @param  int     num
 * @access public
 * @return void
 */
function loadStories(productID, moduleID, num, $currentRow = null)
{
    var branchIDName = (config.currentMethod == 'batchcreate' || config.currentMethod == 'showimport') ? '#branch' : '#branches';
    var branchID     = config.currentMethod == 'batchcreate' ? $(branchIDName + '_' + num).val() : $(branchIDName + num).val();
    var storyLink    = $.createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branchID + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&executionID=0&number=' + num);
    $.getJSON(storyLink, function(stories)
    {
        if(config.currentMethod == 'batchcreate')
        {
            if(!stories) return;

            let $row = $currentRow;
            while($row.length)
            {
                const $story = $row.find('.form-batch-input[data-name="story"]').empty();

                $.each(stories, function(index, story)
                {
                    $story.append('<option value="' + story.value + '">' + story.text + '</option>');
                });

                $row = $row.next('tr');

                if(!$row.find('td[data-name="story"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
            }
        }
        else
        {
            if(!stories) stories = '<select id="story' + num + '" name="story[' + num + ']" class="form-control"></select>';
            $('#story' + num).replaceWith(stories);
            $('#story' + num + "_chosen").remove();
            $('#story' + num).next('.picker').remove();
            $('#story' + num).attr('name', 'story[' + num + ']');
            $('#story' + num).picker();
        }
    });
}

/**
 * Set modules.
 *
 * @param  int     $branchID
 * @param  int     $productID
 * @param  int     $num
 * @access public
 * @return void
 */
function setModules(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const branchID    = $target.val();
    const moduleID    = $currentRow.find('.form-batch-input[data-name="module"]').val();

    $.getJSON($.createLink('tree', 'ajaxGetModules', 'productID=' + productID + '&viewType=case&branch=' + branchID + '&number=0&currentModuleID=' + moduleID), function(data)
    {
        if(!data || !data.modules) return;

        let $row = $currentRow;
        while($row.length)
        {
            const $module = $row.find('.form-batch-input[data-name="module"]').empty();

            $.each(data.modules, function(index, module)
            {
                $module.append('<option value="' + module.value + '"' + (module.value == data.currentModuleID ? 'selected' : '')  + '>' + module.text + '</option>');
            });

            $row = $row.next('tr');

            if(!$row.find('td[data-name="module"][data-ditto="on"]').length || !$row.find('td[data-name="branch"][data-ditto="on"]').length) break;
        }
    });

    loadStories(productID, moduleID, 0, $currentRow);
}

/**
 * Load module related items when the module changed.
 *
 * @access public
 * @return void
 */
function loadModuleRelated()
{
    setStories();
    setScene();
}

/**
 * Set story field.
 *
 * @access public
 * @return void
 */
function setStories()
{
    const moduleID  = $('#module').val();
    const productID = $('#product').val();
    var branch      = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    const link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&storyID=0&onlyOption=false&status=noclosed&limit=0&type=full&hasParent=1&executionID=' + executionID);

    $.get(link, function(stories)
    {
        const value = $('#story').val();

        if(!stories) stories = '<select id="story" name="story"></select>';

        $('#story').replaceWith(stories);

        $('#story').val(value);
    });
}

function setScenes()
{
    const moduleID  = $('#module').val();
    const productID = $('#product').val();
    var branch      = $('#branch').val();
    if(typeof(branch) == 'undefined') branch = 0;
    const link = createLink('testcase', 'ajaxGetModuleScenes', 'productID=' + productID + '&branch=' + branch + '&moduleID=' + moduleID + '&stype=2&storyID=0&onlyOption=false&status=noclosed&limit=50&type=full&hasParent=1');

    $('#sceneIdBox').load(link);
}

function loadProductRelated()
{
    loadProductBranches();
    loadProductModules();
    if(config.currentMethod == 'create' || config.currentMethod == 'edit') setStories();
}

function loadProductBranches()
{
    $('#branch').remove();

    const productID = $('#product').val();
    var param     = config.currentMethod == 'create' ? 'active' : 'all';
    var oldBranch = caseBranch !== 'undefined' ? caseBranch : 0;
    var param     = "productID=" + productID + "&oldBranch=" + oldBranch + "&param=" + param;
    if(typeof(tab) != 'undefined' && (tab == 'execution' || tab == 'project')) param += "&projectID=" + objectID;
    $.get(createLink('branch', 'ajaxGetBranches', param), function(data)
    {
        if(data)
        {
            $('#product').closest('.input-group').append(data);
            $('#branch').css('width', config.currentMethod == 'create' ? '120px' : '95px');
        }
    })
}

function loadProductModules()
{
    const productID = $('#product').val();
    var branch      = $('#branch').val();
    const moduleID  = config.currentMethod == 'edit' ? $('#module').val() : 0;

    if(!branch) branch = 0;

    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case&branch=' + branch + '&rootModuleID=0&returnType=html&fieldID=&needManage=true&extra=nodeleted&currentModuleID=' + moduleID);
    $('#moduleIdBox').load(link, function()
    {
        var $inputGroup = $(this);
        if(typeof(caseModule) == 'string') $('#moduleIdBox').prepend("<span class='input-group-addon'>" + caseModule + "</span>");
    });
}

function loadBranchRelated()
{
    loadProductModules();

    if(config.currentMethod == 'create' || config.currentMethod == 'edit') setStories();
}
