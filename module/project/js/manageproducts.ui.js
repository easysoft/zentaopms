window.checkUnlink = function()
{
    const $elem = $(this);
    if($elem.prop('checked')) return true;

    const productID = +$elem.val();
    if(unmodifiableProducts.includes(productID))
    {
        const $branch = $elem.closest('.product-block').find('[name^=branch]');
        if($branch.length)
        {
            const branchID = +$branch.val();
            if((branchID == BRANCH_MAIN && unmodifiableMainBranches[productID]) || (branchID != BRANCH_MAIN && $.inArray(branchID, unmodifiableBranches) != -1))
            {
                zui.Modal.alert(unLinkProductTip.replace("%s", branchGroups[productID][branchID]));
            }
        }
        else
        {
            zui.Modal.alert(unLinkProductTip.replace("%s", allProducts[productID]));
        }
    }
}

$(function()
{
    $('#manageProducts [type="submit"]').on('click', function(){
        let checkedProducts = [];
        $('input[name^="products"]:checked').each(function()
        {
            let value = $(this).val();
            if(checkedProducts.indexOf(value) == -1) checkedProducts.push(value);
        })

        if(noticeSwitch && checkedProducts.length > 1)
        {
            notice();
            return false;
        }
    });

    $('#linkProduct [type="submit"]').on('click', function(){
        let checkedProducts = $('[name^="otherProducts"]').val();

        if(noticeSwitch && checkedProducts.length > 1)
        {
            notice('otherProducts');
            return false;
        }
    });
})

function notice(type)
{
    zui.Modal.confirm(
        {
            'message' : noticeDivsion,
            'actions': [
                {text: stageBySwitchList['1'], key: 'confirm'},
                {text: stageBySwitchList['0'], key: 'cancel'},
            ],
            onResult: function(result)
            {
                let link;
                let formData;
                let stageBy = result ? 'product' : '';
                if(type == 'otherProducts')
                {
                    link     = $('#linkProduct form').attr('action');
                    formData = {'otherProducts[]': $('[name^="otherProducts"]').val(), stageBy: stageBy};
                }
                else
                {
                    let products = [];
                    let branch   = [];
                    $('input[type="checkbox"][name^="products"]:checked').each(function() {
                        if(products.indexOf($(this).val()) == -1) products.push($(this).val());
                    });

                    $('input[type="hidden"][name^="products"]').each(function() {
                        if(products.indexOf($(this).val()) == -1) products.push($(this).val());
                    });

                    $('input[type="hidden"][name^="branch"]').each(function() {
                        if(branch.indexOf($(this).val()) == -1) branch.push($(this).val());
                    });

                    $('select[name^="branch"]').each(function() {
                        if(branch.indexOf($(this).val()) == -1) branch.push($(this).val());
                    });

                    link     = $.createLink('project', 'manageproducts', 'projectID=' + projectID);
                    formData = {"products[]": products, "branch[]": branch, stageBy: stageBy};
                }

                $.ajaxSubmit({url: link, data: formData});
            }
        }
    );
}
