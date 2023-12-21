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
        $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&&needCreate=&type=noempty,notrunk,separate,singled'), function(data)
        {
            if(data)
            {
                data = JSON.parse(data);
                const $buildsPicker = $('select[name^=builds]').zui('picker');
                $buildsPicker.render({items: data, multiple: true});
                $('select[name^=builds]').attr('data-placeholder', multipleSelect);
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
            $('input[name=execution]').closest('.form-row').removeClass('hidden');
            $('select[name^=builds]').closest('.form-row').addClass('hidden');
            loadProducts(executionID);
        }
        else
        {
            $('input[name=execution]').closest('.form-row').addClass('hidden');
            $('select[name^=builds]').closest('.form-row').removeClass('hidden');

            loadProducts(projectID);
            let productID = $('input[name=product]').val();
            $.get($.createLink('build', 'ajaxGetProjectBuilds', 'projectID=' + projectID + '&productID=' + productID + '&varName=builds&build=&branch=all&needCreate=&type=noempty,notrunk,separate,singled'), function(data)
            {
                if(data)
                {
                    data = JSON.parse(data);
                    const $buildsPicker = $('select[name^=builds]').zui('picker');
                    $buildsPicker.render({items: data, multiple: true});
                    $('select[name^=builds]').attr('data-placeholder', multipleSelect);
                }
            });
        }
    });
    loadBranches();
    window.waitDom('[name=execution]', function()
    {
        loadProducts();
    })
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
    if(!executionID) executionID = $(document).find('[name=execution]').val();

    $.getJSON($.createLink('product', 'ajaxGetProducts', 'executionID=' + executionID), function(data)
    {
        const $product       = $('input[name=product]');
        const $productPicker = $product.zui('picker');
        const productID      = data.length ? data[0].value : 0;
        $productPicker.render({items: data});
        $productPicker.$.setValue(productID);

        if(data)
        {
            $('select[name^=builds]').attr('data-placeholder', multipleSelect);
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
 * @return viod
 */
function loadArtifactrepo(event)
{
    const productID = $(event.target).val();

    var data = Object.values(artifactRepos[productID]);
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
 * @return viod
 */
function onShowArtifactRepo()
{
    var isArtifactRepo = $('input[name=isArtifactRepo]:checked').val();
    if(isArtifactRepo == 'yes')
    {
        var productID = $('[name=product]').val();

        var data = Object.values(artifactRepos[productID]);
        if(data.length > 0)
        {
            $('.artifactrepo-id').removeClass('hidden');
            var items = [];
            for(i in artifactRepos[productID])
            {
                items.push({'text': artifactRepos[productID][i].name, 'value': artifactRepos[productID][i].id});
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
 * @return viod
 */
function onChangeArtifactRepo(event)
{
    const repoID   = $(event.target).val();
    const datePicker = $('#date').datePicker().zui();

    var productID  = $('[name=product]').val();
    var data       = Object.values(artifactRepos[productID]);
    if(data.length == 0 || !repoID)
    {
        $('#filePath').val('');
        datePicker.$.setValue(today);
        return;
    }

    for(i in artifactRepos[productID])
    {
        if(artifactRepos[productID][i].id == repoID)
        {
            var url = productArtifactRepos[productID][i].url;
            if(url[url.length - 1] != '/') url = url + '/';
            $('#filePath').val(url);
            datePicker.$.setValue(artifactRepos[productID][i].createdDate.substr(0, 10));
        }
    }
}
