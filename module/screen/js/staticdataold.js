location.hash = '#/chart/preview/1';
window.location.replace(window.location.href.toString().replace(window.location.hash, '')+'#/chart/preview/1');

$('link[href^="/theme/zui/css"]').remove();
$('link[href^="/theme/default/style.css"]').remove();

function setStaticData(staticData)
{
    staticData = JSON.parse(staticData);
    window.staticData = staticData;
}