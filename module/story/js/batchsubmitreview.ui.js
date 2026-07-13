window.renderRowData = function($row, index, story)
{
    const forceReview    = story.forceReview == 1 || story.forceReview == '1';
    const $needNotReview = $row.find('[data-name="needNotReview"]');
    const $reviewer      = $row.find('[data-name="reviewer"]');
    const $titleInput    = $row.find('[data-name="title"] input');

    if($titleInput.length) $titleInput.attr('title', story.title);

    if(forceReview)
    {
        $needNotReview.closest('td').addClass('hidden');
        $reviewer.attr('data-required', '1');
        return;
    }

    const needNotReview = story.needNotReview == 1 || story.needNotReview == '1';
    if(needNotReview) $needNotReview.prop('checked', true);

    $reviewer.on('inited', function(e, info)
    {
        if(needNotReview) info[0].render({disabled: true});
    });
};

window.toggleBatchReviewer = function(e)
{
    const $target   = $(e.target);
    const $row      = $target.closest('tr');
    const isChecked = $target.prop('checked');
    const $reviewer = $row.find('[name^="reviewer"]');
    const picker    = $reviewer.zui('picker');

    picker.render({disabled: isChecked});
};
