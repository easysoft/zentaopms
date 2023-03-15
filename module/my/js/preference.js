/**
 * Init preference for picker.
 *
 * @access public
 * @return void
 */
function initPreference() {
    var objPngSrc = {
        'program-browse': 'list',
        'program-project': 'list-recent',
        'program-kanban': 'kanban',

        'product-index': 'panel-recent-browse',
        'product-all': 'list',
        'product-dashboard': 'panel',
        'product-browse': 'list-recent',
        'product-kanban': 'kanban',

        'project-browse': 'list',
        'project-execution': 'list-recent',
        'project-index': 'panel-recent-browse',
        'project-kanban': 'kanban',

        'execution-all': 'list',
        'execution-task': 'list-recent',
        'execution-executionkanban': 'kanban',
    }

    /**
     * Render program option.
     *
     * @param  object $option
     * @param  object $b
     * @access public
     * @return void
     */
    function optionRenderProgram($option, b)
    {
        /* transform ， to , then split to fit lang */
        var textArr = b.text.split('/');
        if (!$option.hasClass('preference'))
        {
            $option.empty();
            $option.addClass('preference');
            $option.attr("title", textArr[0]);
            $option.prepend('<div class="preference-border"><div class="preference-img"><img src="theme/default/images/guide/' + objPngSrc[b.value] + '.png"></div><div class="preference-text"><div class="title">' + textArr[0] + '</div><div class="context">' + textArr[1] + '</div></div></div>');
        }
        return $option;
    }

    /**
     * Render program text.
     *
     * @param  object $option
     * @param  object $b
     * @access public
     * @return void
     */
    function textRenderProgram($text, b)
    {
        $text.empty();
        $text.addClass('preference-selection');
        $text.prepend('<span>' + b.split('/')[0] + '</span>')
        return $text;
    }

    /**
     * Render URSR option.
     *
     * @param  object $option
     * @param  object $b
     * @access public
     * @return void
     */
    function optionRenderURSR($option, b)
    {
        if (!$option.hasClass('option-ursr'))
        {
            $option.addClass('option-ursr');
            $option.parent().addClass('list-ursr');
            $option.empty();
            $option.prepend('<div class="border shadow-primary-hover"><div class="value"><p>' + (b.$_index + 1) + '</p></div><div class="context">' + b.text + '</div></div>');
        }
        return $option;
    }

    $('.programLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.productLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.projectLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.executionLink').picker({
        optionRender: optionRenderProgram,
        selectionTextRender: textRenderProgram
    });

    $('.URSR').picker({
        optionRender: optionRenderURSR
    });

    $(document).on('mousemove', 'a.picker-option', function()
    {
        $('.picker-option.text-primary').removeClass('text-primary');
        $(this).addClass('text-primary');
    });

}

$(function()
{
    initPreference();
})
