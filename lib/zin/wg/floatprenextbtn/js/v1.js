function updateBtnPosition(margin, offset)
{
    const preBtn  = document.querySelector('#preButton');
    const nextBtn = document.querySelector('#nextButton');

    if(preBtn) $(preBtn).css('top', margin).css('left', offset);
    if(nextBtn) $(nextBtn).css('top', margin).css('right', offset);
}

window.addEventListener('resize', function(event)
{
    updateBtnPosition(event.target.innerHeight / 2, getOffset());
});

window.getOffset = function()
{
    let $mainContainer = $('#mainContainer');
    let offset = parseFloat($mainContainer.css('margin-left'));
    return offset;
}

$(() => {
    updateBtnPosition(window.innerHeight / 2, getOffset());
});
