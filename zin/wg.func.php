<?php
function wgFactory($wgType, $text)
{
    global $app;
    include_once $app->getBasePath() . 'zin' . DS . 'wg' . DS . $wgType . DS . 'v1.php';
    return new $wgType($text);
}

function button($text = '', $v = 0) {return wgFactory('button', $text);}
function select($text = '', $v = 0) {return wgFactory('select', $text);}
function tab($text = '', $v = 0) {return wgFactory('tab', $text);}
function toolbar($text = '', $v = 0) {return wgFactory('toolbar', $text);}
function actionbar($text = '', $v = 0) {return wgFactory('actionbar', $text);}
function dtable($text = '', $v = 0) {return wgFactory('dtable', $text);}
function form($text = '', $v = 0) {return wgFactory('form', $text);}
