window.checkSolution = function()
{
    toggleLoading('#solutionForm', true);

    $('#submitBtn').attr('disabled', true);
    const formData = new FormData($("#solutionForm")[0]);
    $.ajaxSubmit({
        url: $.createLink('install', 'app'),
        data: formData,
        onComplete: function()
        {
            toggleLoading('#solutionForm', false);
            $('#submitBtn').attr('disabled', false);
        }
    })
};

window.checkMemory = function()
{
    $('#submitBtn').attr('disabled', true);
    let apps = [];
    $.each(category, (index, cate) =>
    {
        const item = $(`input[name=${cate}]`).val();
        if(item != '') apps.push(item);
    });

    $.ajaxSubmit({
        url:  $.createLink('install', 'ajaxCheck'),
        data: {'apps[]': apps},
        onComplete: function(res)
        {
            if(res.code == undefined || res.code == 41010)
            {
                $('#skipBtn').removeClass('hidden');
                $('#overMemoryNotice').removeClass('hidden');
                $('#submitBtn').attr('disabled', true);
                return false;
            }

            $('#skipBtn').addClass('hidden');
            $('#overMemoryNotice').addClass('hidden');
            $('#submitBtn').removeAttr('disabled');
        }
    })
}
