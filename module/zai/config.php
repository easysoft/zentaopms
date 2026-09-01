<?php
$config->zai->zaiTokenTTL        = 1200;
$config->zai->installUrl         = 'https://www.zentao.net/book/zentaopms/zai-install-1774.html';
$config->zai->vectorEnqueueLimit = 1000; // 向量化每次入队最大数量
$config->zai->vectorQueueLimit   = 5000; // 向量化每次出队最大数量
$config->zai->vectorBatchSize    = 250; // 向量化每次同步ZAI最大数量
$config->zai->vectorMaxRetries   = 3; // 向量化每次同步ZAI失败后重试次数
