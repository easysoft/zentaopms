location.hash = '#/chart/preview/1';
window.location.replace(window.location.href.toString().replace(window.location.hash, '')+'#/chart/preview/1');

$('link[href^="/theme/zui/css"]').remove();
$('link[href^="/theme/default/style.css"]').remove();

function setStaticData(staticData, options)
{
    staticData = JSON.parse(staticData);

    let radio = 1;
    if(options.width)
    {
        const originalWidth = staticData.editCanvasConfig.width;
        staticData.editCanvasConfig.width = options.width;
        radio = options.width / originalWidth;
    }

    staticData.componentList = staticData.componentList.map(component => {
        component.attr.w = component.attr.w * radio;
        component.attr.h = component.attr.h * radio;
        component.attr.x = component.attr.x * radio;
        component.attr.y = component.attr.y * radio;

        if(component.option?.paddingX)
        {
            component.option.paddingX = component.option.paddingX * radio;
        }
        if(component.option?.paddingY)
        {
            component.option.paddingY = component.option.paddingY * radio;
        }

        return component;
    });

    window.staticData = staticData;

    let script = document.createElement('script');
    script.type = 'module';
    script.crossorigin = true;
    script.src = webRoot + 'static/js/index.js';
    document.head.appendChild(script);
}