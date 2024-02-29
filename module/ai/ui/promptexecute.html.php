<?php
declare(strict_types=1);

namespace zin;

js("requestAnimationFrame(() => setTimeout(() => openUrl(`{$formLocation}`), 1000));");
