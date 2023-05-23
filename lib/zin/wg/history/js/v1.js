function reverseList(event)
{
    document.querySelector('.history-list').classList.toggle('sort-reverse');
    const icon = event.target.querySelector('.icon');
    icon.classList.toggle('icon-arrow-up');
    icon.classList.toggle('icon-arrow-down');
}

function expandAll(event)
{
    const icon = event.target.querySelector('.icon');
    icon.classList.toggle('icon-plus');
    icon.classList.toggle('icon-minus');

    const isExpand = icon.classList.contains('icon-minus');
    const changeBoxs = document.querySelectorAll('[id^="changeBox"]');
    const icons = document.querySelectorAll('.btn-expand > .icon');

    if(isExpand)
    {
        changeBoxs.forEach((box) => box.classList.add('show'));
        icons.forEach((icon) =>
        {
            icon.classList.remove('icon-plus');
            icon.classList.add('icon-minus');
        });
    }
    else
    {
        changeBoxs.forEach((box) => box.classList.remove('show'));
        icons.forEach((icon) =>
        {
            icon.classList.remove('icon-minus');
            icon.classList.add('icon-plus');
        });
    }
}
