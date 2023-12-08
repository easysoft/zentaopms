$().ready(function()
{
    new zui.Tooltip('#tooltipHover', {title: autoRelationTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    $(document).on('click', '#lastBuildBtn', function()
    {
        $('#name').val($(this).text());
    });

    $(document).off('change', '#product, #branch').on('change', '#product, #branch', function()
    {
        let projectID = $('input[name=project]').val();
        let productID = $('input[name=product]').val();
        $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&letName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
        {
            if(data)
            {
                data = JSON.parse(data);
                const $buildsPicker = $('select[name^=builds]').zui('picker');
                $buildsPicker.render({items: data, multiple: true});
                $('#builds').attr('data-placeholder', multipleSelect);
            }
        });

        if(productID)
        {
            $.get($.createLink('product', 'ajaxGetProductById', 'produtID=' + productID), function(data)
            {
                $('#branch').prev('.form-label').html(data.branchName);
            }, 'json');
        }
    });

    $(document).on('change', 'input[name=isIntegrated]', function()
    {
        let projectID   = $('input[name=project]').val();
        let executionID = $('input[name=execution]').val();

        if($(this).val() == 'no')
        {
            $('#execution').closest('.form-row').removeClass('hidden');
            $('#builds').closest('.form-row').addClass('hidden');
            loadProducts(executionID);
        }
        else
        {
            $('#execution').closest('.form-row').addClass('hidden');
            $('#builds').closest('.form-row').removeClass('hidden');

            loadProducts(projectID);
            let productID = $('input[name=product]').val();
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&letName=builds&build=&branch=all&index=&needCreate=&type=noempty,notrunk,separate,singled&extra=multiple'), function(data)
            {
                if(data)
                {
                    data = JSON.parse(data);
                    const $buildsPicker = $('select[name^=builds]').zui('picker');
                    $buildsPicker.render({items: data, multiple: true});
                    $('#builds').attr('data-placeholder', multipleSelect);
                }
            });
        }
    });
    loadBranches();
});

/**
 * Load products.
 *
 * @param  int $executionID
 * @access public
 * @return void
 */
function loadProducts(executionID)
{
    executionID = parseInt(executionID);
    if(!executionID) executionID = $('input[name=execution]').val();
    $.get($.createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $product       = $('input[name=product]');
            const $productPicker = $product.zui('picker');
            const productID      = data[0].value;
            $productPicker.render({items: data});
            $productPicker.$.setValue(productID);

            $('#builds').attr('data-placeholder', multipleSelect);

            loadBranches(productID);
        }
    });

    loadLastBuild();
}

/**
 * Load last build
 *
 * @access public
 * @return void
 */
function loadLastBuild()
{
    let isIntegrated = $('input[name=isIntegrated]:checked').val();
    let projectID    = $('input[name=project]').val();
    let executionID  = $('input[name=execution]').val();
    if(isIntegrated == 'yes') executionID = 0;
    $.get($.createLink('build', 'ajaxGetLastBuild', 'projectID=' + projectID + '&executionID=' + executionID), function(data)
    {
        $('#lastBuildBox').html(data);
    });
}

/**
 * 产品改变时更新制品库显示。
 * When change product load artifactrepo.
 *
 * @param  event $event
 * @access public
 * @return void
 */
function loadArtifactrepo(event)
{
    const productID = $(event.target).val();

    var data = Object.values(productArtifactRepos[productID]);
    if(data.length > 0)
    {
        $('.artifactrepo').removeClass('hidden');
    }
    else
    {
        $('.artifactrepo').addClass('hidden');
        $('#filePath').val('');
        var datePicker = $('#date').datePicker().zui();
        datePicker.$.setValue(today);
    }

    onShowArtifactRepo();
}

/**
 * 版本库显示改变事件。
 * Artifact repo show change event.
 *
 * @access public
 * @return void
 */
function onShowArtifactRepo()
{
    var isArtifactRepo = $('input[name=isArtifactRepo]:checked').val();
    if(isArtifactRepo == 'yes')
    {
        var productID = $('[name=product]').val();

        var data = Object.values(productArtifactRepos[productID]);
        if(data.length > 0)
        {
            $('.artifactrepo-id').removeClass('hidden');
            var items = [];
            for(i in productArtifactRepos[productID])
            {
                items.push({'text': productArtifactRepos[productID][i].name, 'value': productArtifactRepos[productID][i].id});
            }
            $artifactRepo = $('#artifactRepoID').zui('picker');
            $artifactRepo.render({items: items});
            $artifactRepo.$.clear();
        }
        else
        {
            $('.artifactrepo-id').addClass('hidden');
        }
    }
    else
    {
        $('.artifactrepo-id').addClass('hidden');
    }
}

/**
 * 版本库改变事件。
 * Artifact repo change event.
 *
 * @access public
 * @return void
 */
function onChangeArtifactRepo(event)
{
    const repoID   = $(event.target).val();
    const datePicker = $('#date').datePicker().zui();

    var productID  = $('[name=product]').val();
    var data       = Object.values(productArtifactRepos[productID]);
    if(data.length == 0 || !repoID)
    {
        $('#filePath').val('');
        datePicker.$.setValue(today);
        return;
    }

    for(i in productArtifactRepos[productID])
    {
        if(productArtifactRepos[productID][i].id == repoID)
        {
            var url = productArtifactRepos[productID][i].url;
            if(url[url.length - 1] != '/') url = url + '/';
            $('#filePath').val(url);
            datePicker.$.setValue(productArtifactRepos[productID][i].createdDate.substr(0, 10));
        }
    }
}

/**
 * Load branches
 *
 * @param  int $productID
 * @access public
 * @return void
 */
window.loadBranches = function(productID)
{
    productID = parseInt(productID);
    if(!productID) productID = $('input[name=product]').val();
    if($('input[name=isIntegrated]:checked').val() == 'yes')
    {
        $('#branch').closest('.form-row').addClass('hidden');
        return false;
    }

    let oldBranch = 0;
    if(typeof(productGroups[productID]) != "undefined")
    {
        oldBranch = productGroups[productID]['branches'];
    }

    $.get($.createLink('branch', 'ajaxGetBranches', 'productID=' + productID + '&oldBranch=0&param=active&projectID=' + $('input[name=execution]').val() + '&withMainBranch=true&isSiblings=no&fieldID=0&multiple=multiple'), function(data)
    {
        if(data)
        {
            data = JSON.parse(data);
            const $branchPicker = $('input[name^=branch]').zui('picker');
            $branchPicker.render({items: data});
            $('#branch').closest('.form-row').removeClass('hidden');
        }
        else
        {
            $('#branch').closest('.form-row').addClass('hidden');
        }
    });
}
