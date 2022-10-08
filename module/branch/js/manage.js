function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group').clone();
    $inputgroup.find('input').val('');
    $(obj).closest('.input-group').after($inputgroup);
}

function deleteItem(obj)
{
    if($(obj).closest('#newbranches').find("input[id^='newbranch']").size() <= 1) return;
    $(obj).closest('.input-group').remove();
}

$(function()
{
    $('#branchTableList').addClass('sortable').sortable(
    {
        reverse: orderBy === 'order_desc',
        selector: 'tr',
        dragCssClass: 'drag-row',
        trigger: $('#branchTableList').find('.sort-handler').length ? '.sort-handler' : null,

        canMoveHere: function($ele, $target)
        {
            return $target.data('id') != 0;
        },

        finish: function(e)
        {
            var branches = '';
            e.list.each(function()
            {
                branches += $(this.item).data('id') + ',';
            });

            $.post(createLink('branch', 'sort'), {'branches': branches, 'orderBy': orderBy});
        }
    });

    //$('td.c-name.flex').mouseenter(function()
    //{
    //    $(this).find('.setDefault').removeClass('hidden');
    //    if($(this).find('span.label-primary').length > 0)
    //    {
    //        $(this).find('span.text-ellipsis').css('max-width', 'calc(100% - 50px)');
    //    }
    //    else
    //    {
    //        $(this).find('span.text-ellipsis').css('max-width', 'calc(100% - 120px)');
    //    }
    //}).mouseleave(function()
    //{
    //    $(this).find('.setDefault').addClass('hidden');
    //    if($(this).find('span.label-primary').length > 0)
    //    {
    //        $(this).find('span.text-ellipsis').css('max-width', 'calc(100% - 50px)');
    //    }
    //    else
    //    {
    //        $(this).find('span.text-ellipsis').css('max-width', '100%');
    //    }
    //});

    $("input[id*='branchIDList']").change(function()
    {
        $("input[id*='branchIDList']").each(function()
        {
            if($(this).prop('checked') && ($(this).closest('tr').data('status') === 'closed' || $(this).closest('tr').data('id') == '0'))
            {
                $("a[href='#mergeBranch']").hide();
                return false;
            }

            $("a[href='#mergeBranch']").show();
        })
    });

    $('#createBranch').change(function()
    {
        if($(this).prop('checked'))
        {
            $('#targetBranch').attr('disabled', true).trigger('chosen:updated');

            var newBranchName = '<tr><th>' + branchLang.name + "</th><td class='required' colspan='7'><input type='text' name='name' id='name' class='form-control' /></td></tr>";
            var newBranchDesc = '<tr><th>' + branchLang.desc + "</th><td colspan='7'><input type='text' name='desc' id='desc' class='form-control' /></td></tr>";
            $(this).closest('tr').after(newBranchName + newBranchDesc);
        }
        else
        {
            $('#targetBranch').attr('disabled', false).trigger('chosen:updated');

            $('#name').closest('tr').remove();
            $('#desc').closest('tr').remove();
        }
    });

    $("#merge").click(function()
    {
        var mergedBranchIDList = [];
        var mergedBranchName   = '';

        $("input:checkbox[name^='branchIDList']:checked").each(function()
        {
            mergedBranchIDList.push($(this).val());
            mergedBranchName += ',' + $(this).closest('tr').find('.branchName').attr('title');
        });
        mergedBranchName   = mergedBranchName.substr(1);
        mergedBranchIDList = mergedBranchIDList.join(',');

        $.get(createLink('branch', 'ajaxGetTargetBranches', "productID=" + productID + "&mergedBranches=" + mergedBranchIDList), function(data)
        {
            $('#targetBranch').replaceWith(data);
            $('#targetBranch_chosen').remove();
            $('#targetBranch').chosen();

            if($('#createBranch').prop('checked')) $('#targetBranch').attr('disabled', true).trigger('chosen:updated')
        })
    })

    $('#saveButton').on('click', function()
    {
        var mergedBranchIDList = [];
        var mergedBranchName   = '';
        var targetBranchName   = $('#targetBranch_chosen').find('span').text();

        $("input:checkbox[name^='branchIDList']:checked").each(function()
        {
            mergedBranchIDList.push($(this).val());
            mergedBranchName += ',' + $(this).closest('tr').find('.branchName').attr('title');
        });

        mergedBranchName = mergedBranchName.substr(1);
        targetBranchName = $('#createBranch').prop('checked') ? $('#name').val() : targetBranchName;

        var confirmMergeMessage = branchLang.confirmMerge.replace(/(.*)mergedBranch(.*)targetBranch(.*)/, "$1" + mergedBranchName + "$2" + targetBranchName + "$3");

        var isChecked = $('#createBranch').attr('checked') ? 1 : 0;
        if(isChecked && $('#name').val() == '')
        {
            alert(branchLang.nameNotEmpty);
            return false;
        }

        var branchNames = Object.values(branchPairs);
        if(isChecked && branchNames.indexOf($('#name').val()) !== -1)
        {
            alert(branchLang.existName);
            return false;
        }

        if(confirm(confirmMergeMessage))
        {
            var postData = {'name' : $('#name').val(), 'desc' : $('#desc').val(), 'createBranch' : isChecked, 'mergedBranchIDList' : mergedBranchIDList, 'targetBranch' : $('#targetBranch').val()};
            $.ajax(
            {
                url: createLink('branch', 'mergeBranch', 'productID=' + productID),
                dataType: 'json',
                method: 'post',
                data: postData,
                success: function(data)
                {
                    if(data.result == 'fail')
                    {
                        alert(data.message.name)
                        return false;
                    }
                    else
                    {
                        $('#mergeBranch').modal('hide');
                        window.location.reload();
                    }
                }
            })
        }
        else
        {
            window.location.reload();
        }
    });
})
