function reverseList(event)
{
    const history = event.target.closest('.history');
    history.querySelector('.history-list').classList.toggle('sort-reverse');

    const button = event.target.closest('button');
    const icon = button.querySelector('.icon');
    icon.classList.toggle('icon-arrow-up');
    icon.classList.toggle('icon-arrow-down');
}

function expandAll(event)
{
    const button = event.target.closest('button');
    const icon = button.querySelector('.icon');
    icon.classList.toggle('icon-plus');
    icon.classList.toggle('icon-minus');

    const isExpand = icon.classList.contains('icon-minus');
    const history = event.target.closest('.history');
    const changeBoxs = history.querySelectorAll('.history-changes');
    const icons = history.querySelectorAll('.btn-expand > .icon');

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

function expand(event)
{
    const btn = event.target.closest('button');
    const changeBox = btn.nextElementSibling;
    const icon = btn.querySelector('.icon');
    icon.classList.toggle('icon-plus');
    icon.classList.toggle('icon-minus');

    if(icon.classList.contains('icon-plus')) changeBox.classList.remove('show');
    else changeBox.classList.add('show');
}

function editComment(event)
{
    event.target.closest('.article-content.comment').classList.add('hidden');
    const history = event.target.closest('.history');
    const form = history.querySelector('.history-list .comment-edit-form');
    if(!form) return;

    form.classList.remove('hidden');
}

function closeCommentForm(event)
{
    const current = event.target;
    current.closest('.comment-edit-form').classList.add('hidden');

    const comment = current.closest('li').querySelector('.article-content.comment');
    if(!comment) return;

    comment.classList.remove('hidden');
}

const closeBtn = document.querySelector('#btn-close-form');
if(closeBtn) closeBtn.addEventListener('click', closeCommentForm);
