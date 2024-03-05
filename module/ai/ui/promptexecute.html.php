<?php
declare(strict_types=1);

namespace zin;

h::globalJS("requestAnimationFrame(() => setTimeout(() => openUrl(`{$formLocation}`), 1000));");
