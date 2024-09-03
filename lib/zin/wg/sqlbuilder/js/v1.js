window.switchStep = function(event, selectedClass, defaultClass)
{
    const step = $(event.currentTarget).data('step');
    const isSelected = $(event.target).hasClass('selected');

    if(isSelected) return;

    const $selected = $('.builder-step-bar .selected');
    $selected.removeClass(selectedClass).addClass(defaultClass);
    $(event.currentTarget).addClass(selectedClass).removeClass(defaultClass);

    $('.builder-content').addClass('hidden');
    $('.builder-content#builder' + step).removeClass('hidden');
}
