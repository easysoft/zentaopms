$(function()
{
    $('#copyProjects a').click(function()
    {
        setCopyProject($(this).data('id'));
        $('#copyProjectModal').modal('hide')
    });

    setAclList($("#parent").val());

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

    $('[data-toggle="popover"]').popover();
});

/**
 * Set parent program.
 *
 * @param  $parentProgram
 * @access public
 * @return void
 */
function setParentProgram(parentProgram)
{
    location.href = createLink('project', 'create', 'model=' + model + '&programID=' + parentProgram);
}

/**
 * Set copy project.
 *
 * @param  int $copyProjectID
 * @access public
 * @return void
 */
function setCopyProject(copyProjectID)
{
    location.href = createLink('project', 'create', 'model=' + model + '&programID=' + programID + '&copyProjectID=' + copyProjectID);
}

/**
 * Add new product.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function addNewProduct(obj)
{
    if($(obj).attr('checked'))
    {
        $('#productName').removeAttr('disabled', true);
        $('#productName').closest('tr').removeClass('hidden');
        $('#plansBox .col-sm-4').find('select').attr('disabled', true).trigger("chosen:updated");
        $('#plansBox').closest('tr').addClass('hidden');
        $('#productsBox .col-sm-4 .input-group').find('select').attr('disabled', true).trigger("chosen:updated");
    }
    else
    {
        $('#productName').attr('disabled', true);
        $('#productName').closest('tr').addClass('hidden');
        $('#plansBox .col-sm-4').find('select').removeAttr('disabled', true).trigger("chosen:updated");
        $('#plansBox').closest('tr').removeClass('hidden');
        $('#productsBox .col-sm-4 .input-group').find('select').removeAttr('disabled').trigger("chosen:updated");
    }
}

/**
 * Set access control box.
 *
 * @param  int $programID
 * @access public
 * @return void
 */
function setAclList(programID)
{
    if(programID != 0)
    {
        $('.aclBox').html($('#programAcl').html());
    }
    else
    {
        $('.aclBox').html($('#projectAcl').html());
    }
}

/**
 * Load branches.
 *
 * @param  int $product
 * @access public
 * @return void
 */
function loadBranches(product)
{
    $('#productsBox select').each(function()
    {
        var $product = $(product);
        if($product.val() != 0 && $product.val() == $(this).val() && $product.attr('id') != $(this).attr('id'))
        {
            alert(errorSameProducts);
            $product.val(0);
            $product.trigger("chosen:updated");
            return false;
        }
    });

    if($('#productsBox .input-group:last select:first').val() != 0)
    {
        var length = $('#productsBox .input-group').size();
        var $html  = $('#productsBox .col-sm-4:last').html();
        $('#productsBox .col-sm-4:last').find('.input-group-addon').remove();
        $('#productsBox .row').append('<div class="col-sm-4">' + $html + '</div>');
        if($('#productsBox .input-group:last select').size() >= 2) $('#productsBox .input-group:last select:last').remove();
        $('#productsBox .input-group:last .chosen-container').remove();
        $('#productsBox .input-group:last select:first').attr('name', 'products[' + length + ']').attr('id', 'products' + length);
        $('#productsBox .input-group:last .chosen').chosen();

        adjustProductBoxMargin();
    }

    var $inputgroup = $(product).closest('.input-group');
    if($inputgroup.find('select').size() >= 2) $inputgroup.removeClass('has-branch').find('select:last').remove();
    if($inputgroup.find('.chosen-container').size() >= 2) $inputgroup.find('.chosen-container:last').remove();

    var index = $inputgroup.find('select:first').attr('id').replace('products' , '');
    $.get(createLink('branch', 'ajaxGetBranches', "productID=" + $(product).val()), function(data)
    {
        if(data)
        {
            $inputgroup.addClass('has-branch').append(data);
            $inputgroup.find('select:last').attr('name', 'branch[' + index + ']').attr('id', 'branch' + index).attr('onchange', "loadPlans('#products" + index + "', this.value)").chosen();
        }
    });

    loadPlans(product);
}

/**
 * Load plans.
 *
 * @param  obj $product
 * @param  int $branchID
 * @access public
 * @return void
 */
function loadPlans(product, branchID)
{
    if($('#plansBox').size() == 0) return false;

    var productID = $(product).val();
    var branchID  = typeof(branchID) == 'undefined' ? 0 : branchID;
    var index     = $(product).attr('id').replace('products', '');

    if(productID != 0)
    {
        if(typeof(planID) == 'undefined') planID = 0;
        planID = $("select#plans" + productID).val() != '' ? $("select#plans" + productID).val() : planID;
        $.get(createLink('product', 'ajaxGetPlans', "productID=" + productID + '&branch=' + branchID + '&planID=' + planID + '&fieldID&needCreate=&expired=' + ((config.currentMethod == 'create' || config.currentMethod == 'edit') ? 'unexpired' : '')), function(data)
        {
            if(data)
            {
                if($("div#plan" + index).size() == 0) $("#plansBox .row").append('<div class="col-sm-4" id="plan' + index + '"></div>');
                $("div#plan" + index).html(data).find('select').attr('name', 'plans[' + productID + ']').attr('id', 'plans' + productID).chosen();

                adjustPlanBoxMargin();
            }
        });
    }
}

/**
 * Adjust product box margin.
 *
 * @access public
 * @return void
 */
function adjustProductBoxMargin()
{
    var productRows = Math.ceil($('#productsBox > .row > .col-sm-4').length / 3);
    if(productRows > 1)
    {
        for(i = 1; i <= productRows - 1; i++)
        {
            $('#productsBox .col-sm-4:lt(' + (i * 3) + ')').css('margin-bottom', '10px');
        }
    }
}

/**
 * Adjust plan box margin.
 *
 * @access public
 * @return void
 */
function adjustPlanBoxMargin()
{
    var planRows = Math.ceil($('#plansBox > .row > .col-sm-4').length / 3);
    if(planRows > 1)
    {
        for(j = 1; j <= planRows - 1; j++)
        {
            $('#plansBox .col-sm-4:lt(' + (j * 3) + ')').css('margin-bottom', '10px');
        }
    }
}

/**
 * Fuzzy search projects by project name.
 *
 * @access public
 * @return void
 */
$('#projectName').on('keyup', function()
{
    var name = $(this).val();
    name = name.replace(/\s+/g, '');
    link = createLink('project', 'ajaxGetCopyProjects');
    $.post(link, {name: name, cpoyProjectID : copyProjectID}, function(data)
    {
        $('#copyProjects').html(data);
        $('#copyProjects a').click(function()
        {
            setCopyProject($(this).data('id'));
            $('#copyProjectModal').modal('hide');
        });
    })
})
