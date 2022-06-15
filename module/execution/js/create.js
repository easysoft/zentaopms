function setCopyProject(executionID)
{
    location.href = createLink('execution', 'create', 'projectID=' + projectID + '&executionID=0&copyExecutionID=' + executionID);
}

$(function()
{
    $('#copyProjects a').click(function(){setCopyProject($(this).data('id')); $('#copyProjectModal').modal('hide')});
    $('#begin').on('change', function()
    {
       $("#end").val('');
       $("#days").val('');
       $("input:radio[name='delta']").attr("checked",false);
    });
    $('#end').on('change', function()
    {
        $("input:radio[name='delta']").attr("checked", false);
    })

    if(typeof(currentPlanID) == 'undefined')
    {
        $('#productsBox select[id^="products"]').each(function()
        {
            var branchID = 0;
            if($(this).closest('.input-group').find('select[id^="branch"]').size() > 0)
            {
                var branchID = $(this).closest('.input-group').find('select[id^="branch"]').val();
            }
            loadPlans($(this), branchID);
        });
    }

    /* Assign value to the manage products by the different request type.*/
    var $product = $('#products0');
    if(copyExecutionID) productID = $product.val();
    $product.val(productID);
    $product.trigger("chosen:updated");

    var adjustMainCol = function()
    {
        if(!isStage) $('.main-form .col-main').css('width', Math.max(250, Math.floor(($('#productsBox').outerWidth() - 50)/3) + 10));
    };
    adjustMainCol();
    $(window).on('resize', adjustMainCol);

    $('#teams_chosen').click(function()
    {
        if(systemMode == 'new')
        {
            $('#teams_chosen ul li').each(function(index)
            {
                if(index == 0)
                {
                    var projectName = subString($(this).text(), 56);
                    $(this).text(projectName);
                    $(this).append(' <label class="label">' + projectCommon + '</label>');
                }
                else
                {
                    $(this).prepend('&nbsp;&nbsp;&nbsp;');
                }
            })
        }
    })

    $('#teams').change(function()
    {
        var objectID = $(this).val();
        $.get(createLink('execution', 'ajaxGetTeamMembers', 'objectID=' + objectID), function(data)
        {
            $('#teamMembers').parent().html(data);
            $('#teamMembers').chosen();
        });
    })

    if(copyExecutionID != 0 || projectID != 0) $('#teams').change();

    var acl = $("[name^='acl']:checked").val();
    setWhite(acl);

    $('#submit').click(function()
    {
        var products      = new Array();
        var existedBranch = false;

        /* Determine whether the products of the same branch are linked. */
        $("#productsBox select[name^='products']").each(function()
        {
            var productID = $(this).val();
            if(typeof(products[productID]) == 'undefined') products[productID] = new Array();
            if(multiBranchProducts[productID])
            {
                var branchID = $(this).closest('.input-group').find("select[id^=branch]").val();
                if(products[productID][branchID])
                {
                    existedBranch = true;
                }
                else
                {
                    products[productID][branchID] = branchID;
                }
                if(existedBranch) return false;
            }
        });

        if(existedBranch)
        {
            bootbox.alert(errorSameBranches);
            return false;
        }
    });

    nonClickableSelectedProduct();
    $(document).on('change', "select[id^='products']", nonClickableSelectedProduct);
});

function showLifeTimeTips()
{
    var lifetime = $('#lifetime option:selected').val();
    if(lifetime == 'ops')
    {
        $('#lifeTimeTips').show();
    }
    else
    {
        $('#lifeTimeTips').hide();
    }
}

/**
 * Refresh page.
 *
 * @param  object $projectID
 * @access public
 * @return void
 */
function refreshPage(projectID)
{
    location.href = createLink('execution', 'create', 'projectID=' + projectID);
}

/**
 * Cut a string of letters and characters with the same length.
 *
 * @param  string $title
 * @param  int    $stringLength
 * @access public
 * @return string
 */
function subString(title, stringLength)
{
    if(title.replace(/[\u4e00-\u9fa5]/g, "**").length > stringLength)
    {
        var length = 0;
        for(var i = 0; i < title.length; i ++)
        {
            length += title.charCodeAt(i) > 255 ? 2 : 1;
            if(length > stringLength)
            {
                title = title.substring(0, i) + '...';
                break;
            }
        }
    }

    return title;
}
