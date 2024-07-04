<?php
if(defined('RUN_MODE') && RUN_MODE == 'xuanxuan' && !empty($this->config->xuanxuan->enabledMethods[$module][$method])) return true;

if($module == 'entry' and $method == 'visit')      return true;
if($module == 'integration' and $method == 'wopi') return true;
if($module == 'im' and $method == 'authorize')     return true;
