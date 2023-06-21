const preBtn  = document.querySelector('#preButton');
const nextBtn = document.querySelector('#nextButton');

window.addEventListener('resize', function(event)
{
    const margin = event.target.innerHeight / 2;

    $(preBtn).css('position', 'absolute');
    $(preBtn).css('margin-top', margin);
    $(preBtn).css('float', 'left');
    $(preBtn).css('left', 0);

    $(nextBtn).css('position', 'absolute');
    $(nextBtn).css('margin-top', margin);
    $(nextBtn).css('float', 'right');
    $(nextBtn).css('right', 0);
});

$(() => {
    const margin = window.innerHeight / 2;

    $(preBtn).css('position', 'absolute');
    $(preBtn).css('margin-top', margin);
    $(preBtn).css('float', 'left');
    $(preBtn).css('left', 0);

    $(nextBtn).css('position', 'absolute');
    $(nextBtn).css('margin-top', margin);
    $(nextBtn).css('float', 'right');
    $(nextBtn).css('right', 0);
});
