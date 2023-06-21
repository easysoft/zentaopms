function updateBtnPosition(margin)
{
    const preBtn  = document.querySelector('#preButton');
    const nextBtn = document.querySelector('#nextButton');

    if(preBtn)
    {
        $(preBtn).css('top', margin);
    }

    if(nextBtn)
    {
        $(nextBtn).css('top', margin);
    }
}

window.addEventListener('resize', function(event)
{
    updateBtnPosition(event.target.innerHeight / 2);
});

$(() => {
    updateBtnPosition(window.innerHeight / 2);
});
