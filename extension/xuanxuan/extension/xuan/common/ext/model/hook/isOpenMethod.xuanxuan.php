<?php
if($module == 'entry' and $method == 'visit')      return true;
if($module == 'integration' and $method == 'wopi') return true;
if($module == 'im' and $method == 'authorize')     return true;
