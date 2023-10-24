<?php
declare(strict_types=1);
namespace zin;

require_once __DIR__ . DS . 'zui.class.php';

function rounded($value = true)     {return zui::rounded($value);}
function shadow($value = true)      {return zui::shadow($value);}
function primary($value = true)     {return zui::primary($value);}
function secondary($value = true)   {return zui::secondary($value);}
function success($value = true)     {return zui::success($value);}
function warning($value = true)     {return zui::warning($value);}
function danger($value = true)      {return zui::danger($value);}
function important($value = true)   {return zui::important($value);}
function special($value = true)     {return zui::special($value);}
function bg($value = null)          {return zui::bg($value);}
function muted($value = true)       {return zui::muted($value);}
function opacity($value)            {return zui::opacity($value);}
function disabled($value = true)    {return zui::disabled($value);}
function width($value)              {return zui::width($value);}
function height($value)             {return zui::height($value);}
function ring(/* ...$args */)       {return call_user_func_array('\zin\zui::ring', func_get_args());}
function border(/* ...$args */)     {return call_user_func_array('\zin\zui::border', func_get_args());}
