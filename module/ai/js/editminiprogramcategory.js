$(function()
{
    const template = document.getElementById('custom-item');
    const $clone = $(document.importNode(template.content, true));
    $('.custom-category-list').append($clone);
});

function addItem(event)
{
    const $target = $(event.target);
    const template = document.getElementById('custom-item');
    const $clone = $(document.importNode(template.content, true));
    $target.closest('.category-item').after($clone);
}

function deleteItem(event)
{
    const $target = $(event.target);
    $target.closest('.category-item').remove();
}
