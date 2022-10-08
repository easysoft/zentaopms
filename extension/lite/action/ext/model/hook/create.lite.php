<?php
if(strtolower($objectType) == 'api' && count($this->loadModel('user')->getVisionList()) < 2) return false;
