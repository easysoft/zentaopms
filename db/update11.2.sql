update `zt_build` set `deleted`='1' where `project`='0' and `id` in (select `build` from `zt_release` where `deleted`='1');
