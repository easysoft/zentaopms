<?php
namespace zin;

function createLink($moduleName, $methodName = 'index', $vars = '', $viewType = 'json')
{
    \helper::createLink($moduleName, $methodName, $vars, $viewType);
}
