$(function()
{
    $('#productsBox input:checkbox').each(function()
    {
        var $cb = $(this);
        if($cb.prop('checked')) $cb.closest('.product').addClass('checked');
    });

    $('#productsBox input:checkbox').change(function()
    {
        var $cb = $(this);
        $cb.closest('.product').toggleClass('checked', $cb.prop('checked'));

        if($cb.prop('checked')) return true;

        var productID = String($cb.val());
        if($.inArray(productID, unmodifiableProducts) != -1)
        {
            var $branch = $cb.closest('.product').find('[name^=branch]');
            if($branch.length)
            {
                var branchID = String($branch.val());

                if((branchID == BRANCH_MAIN && unmodifiableMainBranches[productID]) || (branchID != BRANCH_MAIN && $.inArray(branchID, unmodifiableBranches) != -1))
                {
                    bootbox.alert(unLinkProductTip.replace("%s", branchGroups[productID][branchID]));
                }
            }
            else
            {
                bootbox.alert(unLinkProductTip.replace("%s", allProducts[productID]));
            }
        }
    });

    $("select[id^=branch]").change(function()
    {
        var checked = $(this).closest('div').hasClass('checked');
        if(!checked)
        {
            $(this).closest('div').addClass('checked');
            $(this).closest('div').find("input[id^=products]").prop('checked', true);
        }
    });

    $('.saveOtherProduct').click(function()
    {
        $('#productsBox').ajaxForm(
        {
            finish:function(response)
            {
                if(response.result == 'success')
                {
                    $('#productsBox #submit').popover('destroy');
                    
                    $('.saveOtherProduct').popover(
                    {
                        trigger: 'manual',
                        content: response.message,
                        tipClass: 'popover-success popover-form-result',
                        placement: 'right'
                    }).popover('show');

                    setTimeout(function(){$('.saveOtherProduct').popover('destroy')}, 2000);

                    var reloadUrl = response.locate == 'reload' ? location.href : response.locate;
                    setTimeout(function(){location.href = reloadUrl;}, 1200);
                }
            }
        });

        var checkedProducts = [];
        var otherProducts = $('#otherProducts').val();
        for(key in otherProducts)
        {
            var productBranch = otherProducts[key].split('_');
            var selectProduct = productBranch[0];
            if(checkedProducts.indexOf(selectProduct) == -1) checkedProducts.push(selectProduct);
        }

        $('input[name^="products"]:checked').each(function()
        {
            var value = $(this).val();
            if(checkedProducts.indexOf(value) == -1) checkedProducts.push(value);
        })

        if(noticeSwitch && checkedProducts.length > 1)
        {
            notice();
        }
        else
        {
            $('form#productsBox').submit();
        }
    });

    $('#submit').click(function()
    {
        var checkedProducts = [];
        $('input[name^="products"]:checked').each(function()
        {
            var value = $(this).val();
            if(checkedProducts.indexOf(value) == -1) checkedProducts.push(value);
        })

        if(noticeSwitch && checkedProducts.length > 1)
        {
            notice();
            return false;
        }
    })
});

function notice()
{
    bootbox.confirm(
        {
            'message' : noticeDivsion,
            'buttons':{
                confirm:{
                    label: divisionSwitchList['1'],
                    className: 'btn'
                },
                cancel:{
                    label: divisionSwitchList['0'],
                    className: 'btn-primary'
                },
            },
            callback: function(result)
            {
                if(result) $('#submit').after("<input type='hidden' value='1' name='division'>");
                $('form#productsBox').submit();
            }
        }
    );
}
