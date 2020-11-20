/**
 * Add item.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addItem(obj)
{
    var $inputgroup = $(obj).closest('.input-group').clone();
    $inputgroup.find('input').val('');
    $(obj).closest('.input-group').after($inputgroup);
}

/**
 * Delete item.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function deleteItem(obj)
{
    if($('#paramDiv').find("input").size() <= 2) return;
    $(obj).closest('.input-group').remove();
}

/**
 * Set param name.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function setParamName(obj)
{
    if(obj.value == '$zentao_version') $(obj).closest('.input-group').find("input[name^='paramName']").val('ZENTAO_VERSION');
    if(obj.value == '$zentao_account') $(obj).closest('.input-group').find("input[name^='paramName']").val('ZENTAO_ACCOUNT');
    if(obj.value == '$zentao_product') $(obj).closest('.input-group').find("input[name^='paramName']").val('ZENTAO_PRODUCT');
    if(obj.value == '$zentao_repopath') $(obj).closest('.input-group').find("input[name^='paramName']").val('ZENTAO_REPOPATH');
}

/**
 * Show input, hidden select.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function setValueInput(obj)
{
    if(obj.checked)
    {
        $(obj).closest('.input-group').find('select').attr('disabled', true);
        $(obj).closest('.input-group').find('select').addClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeAttr('disabled');
    }
    else
    {
        $(obj).closest('.input-group').find("input[name^='paramValue']").attr('disabled', true);
        $(obj).closest('.input-group').find("input[name^='paramValue']").addClass('hidden');
        $(obj).closest('.input-group').find('select').removeClass('hidden');
        $(obj).closest('.input-group').find('select').removeAttr('disabled');
    }
}
