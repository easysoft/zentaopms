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
        'execution-task': 'kanban',
        'execution-executionkanban': 'list-recent',
    }
    function optionRenderProgram($option, b) {
        /* transform ， to , then split to fit lang */
        var textArr = b.text.replace(/[\uff0c]/g,",").split(',');
        $option.empty();
        $option.addClass('has-img')
        /** dom to prepend
         *  <div class="border>
         *    <div class="has-img-img"><img src="theme/default/images/guide/' + b.value + '.png"></div>
         *    <div class="has-img-text">
         *      <div class="title"></div>
         *      <div class="context"></div>
         *     </div>
         *  </div> 
         **/
        $option.prepend('<div class="border"><div class="has-img-img"><img src="theme/default/images/guide/' + objPngSrc[b.value] + '.png"></div><div class="has-img-text"><div class="title">' + textArr[0] + '</div><div class="context">' + textArr[1] + '</div></div></div>');
        return $option;
    }
    function optionRenderURSR($option, b) {
        $option.addClass('option-ursr');
        $option.parent().addClass('list-ursr');
        $option.empty();
        $option.prepend('<div class="border"><div class="value"><p>' + b.value + '</p></div><div class="context">' + b.text + '</div></div>');
        return $option;
    }
    
    $('#programLink').picker({
        optionRender: optionRenderProgram
    });
    $('#productLink').picker({
        optionRender: optionRenderProgram
    });
    $('#projectLink').picker({
        optionRender: optionRenderProgram
    });
    $('#executionLink').picker({
        optionRender: optionRenderProgram
    });
    $('#URSR').picker({
        optionRender: optionRenderURSR
    });
}

initPreference();
