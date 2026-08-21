window.renderRowData = function($row, index, story)
{
    const forceReview    = story.forceReview == 1 || story.forceReview == '1';
    const $needNotReview = $row.find('[data-name="needNotReview"]');
    const $reviewer      = $row.find('[data-name="reviewer"]');
    const $titleInput    = $row.find('[data-name="title"] input');

    if($titleInput.length) $titleInput.attr('title', story.title);

    const needNotReview = story.needNotReview == 1 || story.needNotReview == '1';
    if(!forceReview && needNotReview) $needNotReview.prop('checked', true);

    $reviewer.on('inited', function(e, info)
    {
        const opts = {};
        if(story.reviewerItems)
        {
            const items = [];
            $.each(story.reviewerItems, function(account, realname)
            {
                items.push({value: account, text: realname});
            });
            if(items.length) opts.items = items;
        }
        if(!forceReview && needNotReview) opts.disabled = true;
        if(Object.keys(opts).length) info[0].render(opts);
    });

    if(forceReview)
    {
        $needNotReview.closest('td').addClass('hidden');
        $reviewer.attr('data-required', '1');
    }
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
