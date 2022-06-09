VERSION     = $(shell head -n 1 VERSION)
LITEVERSION = $(shell head -n 1 extension/lite/LITEVERSION)
XUANVERSION = $(shell head -n 1 xuanxuan/XUANVERSION)
XVERSION    = $(shell head -n 1 xuanxuan/XVERSION)

XUANPATH     := $(XUANXUAN_SRC_PATH)
BUILD_PATH   := $(if $(ZENTAO_BUILD_PATH),$(ZENTAO_BUILD_PATH),$(shell pwd))
RELEASE_PATH := $(if $(ZENTAO_RELEASE_PATH),$(ZENTAO_RELEASE_PATH),$(shell pwd))

all:
	make clean
	make ci
clean:
	rm -fr zentaopms
	rm -fr zentaostory
	rm -fr zentaotask
	rm -fr zentaotest
	rm -fr *.tar.gz
	rm -fr *.zip
	rm -fr build/linux/lampp
	rm -rf buildroot/
	rm -fr lampp
	rm -fr zentaoxx
	rm -fr tmp/
	rm -f  *.sh
	rm -f *.deb *.rpm
common:
	mkdir zentaopms
	cp -fr api zentaopms/
	cp -fr bin zentaopms/
	cp -fr config zentaopms/ && rm -fr zentaopms/config/my.php
	cp -fr db zentaopms/
	cp -fr doc zentaopms/ && rm -fr zentaopms/doc/phpdoc && rm -fr zentaopms/doc/doxygen
	cp -fr framework zentaopms/
	cp -fr lib zentaopms/
	cp -fr module zentaopms/
	cp -fr extension zentaopms/
	cp -fr sdk zentaopms/
	cp -fr www zentaopms && rm -fr zentaopms/www/data/ && mkdir -p zentaopms/www/data/upload && mkdir zentaopms/www/data/course
	mkdir zentaopms/tmp
	mkdir zentaopms/tmp/cache/
	mkdir zentaopms/tmp/extension/
	mkdir zentaopms/tmp/log/
	mkdir zentaopms/tmp/model/
	mv zentaopms/www/install.php.tmp zentaopms/www/install.php
	mv zentaopms/www/upgrade.php.tmp zentaopms/www/upgrade.php
	cp VERSION zentaopms/
	# create index.html of each folder.
	for path in `find zentaopms/ -type d`; do touch "$$path/index.html"; done
	rm zentaopms/www/index.html
	# combine js and css files.
	cp -fr tools zentaopms/tools && cd zentaopms/tools/ && php ./minifyfront.php
	rm -fr zentaopms/tools
	# create the restart file for svn.
	# touch zentaopms/module/svn/restart
	# delete the unused files.
	find zentaopms -name .gitkeep |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	# notify.zip.
	mkdir zentaopms/www/data/notify/
zentaoxx:
	#xuanxuan
	mkdir -p zentaoxx/config/ext
	mkdir -p zentaoxx/lib
	mkdir -p zentaoxx/extension/xuan
	mkdir -p zentaoxx/framework
	mkdir -p zentaoxx/db
	mkdir -p zentaoxx/www
	mkdir -p zentaoxx/extension/xuan/common/ext/model/
	cd $(XUANPATH); git pull; git archive --format=zip --prefix=xuan/ $(XUANVERSION) > xuan.zip
	mv $(XUANPATH)/xuan.zip .
	unzip xuan.zip
	cp xuan/xxb/config/ext/_0_xuanxuan.php zentaoxx/config/ext/
	cp -r xuan/xxb/lib/phpaes zentaoxx/lib/
	cp -r xuan/xxb/framework/xuanxuan.class.php zentaoxx/framework/
	cp -r xuan/xxb/db/*.sql zentaoxx/db/
	cp -r xuan/xxb/module/im zentaoxx/extension/xuan/
	cp -r xuan/xxb/module/client zentaoxx/extension/xuan/
	cp -r xuan/xxb/module/conference zentaoxx/extension/xuan/
	cp -r xuan/xxb/module/integration zentaoxx/extension/xuan/
	cp -r xuan/xxb/module/license zentaoxx/extension/xuan/
	mkdir -p zentaoxx/extension/xuan/common/view
	cp -r xuan/xxb/module/common/view/header.modal.html.php zentaoxx/extension/xuan/common/view
	cp -r xuan/xxb/module/common/view/marked.html.php zentaoxx/extension/xuan/common/view
	cp -r xuan/xxb/module/common/view/footer.modal.html.php zentaoxx/extension/xuan/common/view
	cp -r xuan/xxb/module/common/view/version.html.php zentaoxx/extension/xuan/common/view
	mkdir -p zentaoxx/www/js/
	cp -r xuan/xxb/www/js/markedjs zentaoxx/www/js/
	cp -r xuan/xxb/www/js/version.js zentaoxx/www/js/
	cp -r xuan/xxb/www/x.php zentaoxx/www/
	mkdir zentaoxx/extension/xuan/action
	cp -r xuan/xxb/module/action/ext zentaoxx/extension/xuan/action
	cp -r xuan/xxb/config/ext/_1_maps.php zentaoxx/config/ext/
	cp -r xuanxuan/config/* zentaoxx/config/
	cp -r xuanxuan/extension/xuan/* zentaoxx/extension/xuan/
	cp -r xuanxuan/www/* zentaoxx/www/
	mv zentaoxx/db/ zentaoxx/db_bak
	mkdir zentaoxx/db/
	cp zentaoxx/db_bak/upgradexuanxuan*.sql zentaoxx/db_bak/xuanxuan.sql zentaoxx/db/
	rm -rf zentaoxx/db_bak/
	sed -i 's/XXBVERSION/$(XVERSION)/g' zentaoxx/config/ext/_0_xuanxuan.php
	sed -i "/\$$config->xuanxuan->backend /c\\\$$config->xuanxuan->backend     = 'zentao';" zentaoxx/config/ext/_0_xuanxuan.php
	sed -i 's/site,//' zentaoxx/extension/xuan/im/model/user.php
	sed -i 's/admin, g/g/' zentaoxx/extension/xuan/im/model/user.php
	sed -i '/password = md5/d' zentaoxx/extension/xuan/im/model/user.php
	sed -i 's/md5(\$$user->password.*$$/\$$user->password;/g' zentaoxx/extension/xuan/im/model/user.php
	sed -i '/getSignedTime/d' zentaoxx/extension/xuan/im/control.php
	sed -i "/loadModel('push')/d" zentaoxx/extension/xuan/im/control.php
	sed -i "/this->push/d" zentaoxx/extension/xuan/im/control.php
	sed -i "s/(int)(microtime/(double)(microtime/" zentaoxx/extension/xuan/im/control.php
	sed -i "s/'yahoo', //g" zentaoxx/extension/xuan/im/config.php
	sed -i "s/'gtalk', //g" zentaoxx/extension/xuan/im/config.php
	sed -i "s/'wangwang', //g" zentaoxx/extension/xuan/im/config.php
	sed -i "s/'site', //g" zentaoxx/extension/xuan/im/config.php
	sed -i "s/'reload'/inlink('browse')/g" zentaoxx/extension/xuan/client/control.php
	sed -i 's/tree/dept/' zentaoxx/extension/xuan/im/model.php
	sed -i 's/tree/dept/' zentaoxx/extension/xuan/im/control.php
	sed -i 's/im_/zt_im_/g' zentaoxx/db/*.sql
	sed -i 's/xxb_user/zt_user/g' zentaoxx/db/*.sql
	sed -i 's/xxb_file/zt_file/g' zentaoxx/db/*.sql
	sed -i '/xxb_entry/d' zentaoxx/db/*.sql
	sed -i '/deviceToken/d' zentaoxx/db/*.sql
	sed -i '/deviceType/d' zentaoxx/db/*.sql
	sed -i "/fetch('push', 'pushMessage');/d" zentaoxx/extension/xuan/im/control.php
	#sed -i "s/marked\.html\.php';?>/marked\.html\.php';?>\n<div id='mainMenu' class='clearfix'><div class='btn-toolbar pull-left'><?php common::printAdminSubMenu('xuanxuan');?><\/div><\/div>/g" zentaoxx/extension/xuan/client/view/checkupgrade.html.php
	sed -i '/var serverVersions/d' zentaoxx/extension/xuan/client/js/checkupgrade.js
	sed -i '/var currentVersion/d' zentaoxx/extension/xuan/client/js/checkupgrade.js
	sed -i '/setRequiredFields(/d' zentaoxx/extension/xuan/common/view/header.modal.html.php
	sed -i 's/header.html.php/header.lite.html.php/g' zentaoxx/extension/xuan/common/view/header.modal.html.php
	sed -i 's/footer.html.php/footer.lite.html.php/g' zentaoxx/extension/xuan/common/view/footer.modal.html.php
	sed -i 's/v\.//g' zentaoxx/extension/xuan/im/js/debug.js
	sed -i 's/helper::jsonEncode(/json_encode(/g' zentaoxx/framework/xuanxuan.class.php
	sed -i 's/moduleRoot/getExtensionRoot() . "xuan\/"/' zentaoxx/framework/xuanxuan.class.php
	sed -i "s/lang->goback,/lang->goback, '',/g" zentaoxx/extension/xuan/im/view/debug.html.php
	sed -i 's/v\.//g' zentaoxx/extension/xuan/client/js/checkupgrade.js
	sed -i 's/commonModel::getLicensePropertyValue/extCommonModel::getLicensePropertyValue/g' zentaoxx/extension/xuan/im/control.php
	sed -i 's/commonModel::getLicensePropertyValue/extCommonModel::getLicensePropertyValue/g' zentaoxx/extension/xuan/im/model/conference.php
	sed -i 's/xxb_/zt_/g' zentaoxx/db/*.sql
	sed -i "s#\$this->app->getModuleRoot() . 'im/apischeme.json'#\$this->app->getExtensionRoot() . 'xuan/im/apischeme.json'#g" zentaoxx/extension/xuan/im/model.php
	sed -i "s/'..\/..\/common\/view\/header.html.php'/\$$app->getModuleRoot() . 'common\/view\/header.html.php'/g" zentaoxx/extension/xuan/conference/view/admin.html.php
	sed -i "s/'..\/..\/common\/view\/footer.html.php'/\$$app->getModuleRoot() . 'common\/view\/footer.html.php'/g" zentaoxx/extension/xuan/conference/view/admin.html.php
	echo "ALTER TABLE \`zt_user\` ADD \`pinyin\` varchar(255) NOT NULL DEFAULT '' AFTER \`realname\`;" >> zentaoxx/db/xuanxuan.sql
	mkdir zentaoxx/tools; cp tools/cn2tw.php zentaoxx/tools; cd zentaoxx/tools; php cn2tw.php
	cp tools/en2de.php zentaoxx/tools; cd zentaoxx/tools; php en2de.php ../
	rm -rf zentaoxx/tools
	zip -rqm -9 zentaoxx.$(VERSION).zip zentaoxx/*
	rm -rf xuan.zip xuan zentaoxx
package:
	# change mode.
	chmod -R 777 zentaopms/tmp/
	chmod -R 777 zentaopms/www/data
	chmod -R 777 zentaopms/config
	chmod -R 777 zentaopms/extension/custom
	chmod 777 zentaopms/module
	chmod 777 zentaopms/www
	chmod a+rx zentaopms/bin/*
	if [ ! -d "zentaopms/config/ext" ]; then mkdir zentaopms/config/ext; fi
	find zentaopms/ -name ext |xargs chmod -R 777
	mkdir zentaopms/tools; cp tools/cn2tw.php zentaopms/tools; cd zentaopms/tools; php cn2tw.php
	#rm -r zentaopms/module/misc/ext
	rm -rf zentaopms/tools
pms:
	make common
	make zentaoxx
	unzip zentaoxx.*.zip
	cp zentaoxx/* zentaopms/ -r
	make package
	zip -rq -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms zentaoxx zentaoxx.*.zip
deb:
	mkdir buildroot
	cp -r build/debian/DEBIAN buildroot
	sed -i '/^Version/cVersion: ${VERSION}' buildroot/DEBIAN/control
	mkdir buildroot/opt
	mkdir buildroot/etc/apache2/sites-enabled/ -p
	cp build/debian/zentaopms.conf buildroot/etc/apache2/sites-enabled/
	cp ZenTaoPMS.${VERSION}.zip buildroot/opt
	cd buildroot/opt; unzip ZenTaoPMS.${VERSION}.zip; mv zentaopms zentao; rm ZenTaoPMS.${VERSION}.zip
	sed -i 's/index.php/\/zentao\/index.php/' buildroot/opt/zentao/www/.htaccess
	sudo dpkg -b buildroot/ ZenTaoPMS.${VERSION}.1.all.deb
	rm -rf buildroot
rpm:
	mkdir ~/rpmbuild/SPECS -p
	cp build/rpm/zentaopms.spec ~/rpmbuild/SPECS
	sed -i '/^Version/cVersion:${VERSION}' ~/rpmbuild/SPECS/zentaopms.spec
	mkdir ~/rpmbuild/SOURCES
	cp ZenTaoPMS.${VERSION}.zip ~/rpmbuild/SOURCES
	mkdir ~/rpmbuild/SOURCES/etc/httpd/conf.d/ -p
	cp build/debian/zentaopms.conf ~/rpmbuild/SOURCES/etc/httpd/conf.d/
	mkdir ~/rpmbuild/SOURCES/opt/ -p
	cd ~/rpmbuild/SOURCES; unzip ZenTaoPMS.${VERSION}.zip; mv zentaopms opt/zentao;
	sed -i 's/index.php/\/zentao\/index.php/' ~/rpmbuild/SOURCES/opt/zentao/www/.htaccess
	cd ~/rpmbuild/SOURCES; tar -czvf zentaopms-${VERSION}.tar.gz etc opt; rm -rf ZenTaoPMS.${VERSION}.zip etc opt;
	rpmbuild -ba ~/rpmbuild/SPECS/zentaopms.spec
	cp ~/rpmbuild/RPMS/noarch/zentaopms-${VERSION}-1.noarch.rpm ./
	rm -rf ~/rpmbuild
en:
	make common
	cd zentaopms/; grep -rl 'zentao.net'|xargs sed -i 's/zentao.net/zentao.pm/g';
	cd zentaopms/; grep -rl 'http://www.zentao.pm'|xargs sed -i 's/http:\/\/www.zentao.pm/https:\/\/www.zentao.pm/g';
	cd zentaopms/config/; echo >> config.php; echo '$$config->isINT = true;' >> config.php
	make zentaoxx
	unzip zentaoxx.*.zip
	cp zentaoxx/* zentaopms/ -r
	make package
	mv zentaopms zentaoalm
	zip -r -9 ZenTaoALM.$(VERSION).int.zip zentaoalm
	rm -fr zentaoalm
	#echo $(VERSION).int > VERSION
	#make endeb
	#make enrpm
	#echo $(VERSION) > VERSION
endeb:
	mkdir buildroot
	cp -r build/debian/DEBIAN buildroot
	sed -i '/^Version/cVersion: ${VERSION}' buildroot/DEBIAN/control
	mkdir buildroot/opt
	mkdir buildroot/etc/apache2/sites-enabled/ -p
	cp build/debian/zentaopms.conf buildroot/etc/apache2/sites-enabled/
	cp ZenTaoALM.${VERSION}.zip buildroot/opt
	cd buildroot/opt; unzip ZenTaoALM.${VERSION}.zip; mv zentaoalm zentao; rm ZenTaoALM.${VERSION}.zip
	sed -i 's/index.php/\/zentao\/index.php/' buildroot/opt/zentao/www/.htaccess
	sudo dpkg -b buildroot/ ZenTaoALM_${VERSION}_1_all.deb
	rm -rf buildroot
enrpm:
	mkdir ~/rpmbuild/SPECS -p
	cp build/rpm/zentaopms.spec ~/rpmbuild/SPECS
	sed -i '/^Version/cVersion:${VERSION}' ~/rpmbuild/SPECS/zentaopms.spec
	sed -i '/^Name:/cName:zentaoalm' ~/rpmbuild/SPECS/zentaopms.spec
	mkdir ~/rpmbuild/SOURCES
	cp ZenTaoALM.${VERSION}.zip ~/rpmbuild/SOURCES
	mkdir ~/rpmbuild/SOURCES/etc/httpd/conf.d/ -p
	cp build/debian/zentaopms.conf ~/rpmbuild/SOURCES/etc/httpd/conf.d/zentaoalm.conf
	mkdir ~/rpmbuild/SOURCES/opt/ -p
	cd ~/rpmbuild/SOURCES; unzip ZenTaoALM.${VERSION}.zip; mv zentaoalm opt/zentao;
	sed -i 's/index.php/\/zentao\/index.php/' ~/rpmbuild/SOURCES/opt/zentao/www/.htaccess
	cd ~/rpmbuild/SOURCES; tar -czvf zentaoalm-${VERSION}.tar.gz etc opt; rm -rf ZenTaoALM.${VERSION}.zip etc opt;
	rpmbuild -ba ~/rpmbuild/SPECS/zentaopms.spec
	cp ~/rpmbuild/RPMS/noarch/zentaoalm-${VERSION}-1.noarch.rpm ./
	rm -rf ~/rpmbuild
ciCommon:
	git pull
	make common

        ifneq ($(XUANPATH), )
	    make zentaoxx
	    unzip zentaoxx.*.zip
	    cp zentaoxx/* zentaopms/ -r
        endif

	make package
	zip -rq -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms zentaoxx zentaoxx.*.zip
	make en
	rm -fr zentaopms zentaoxx zentaoxx.*.zip
	rm -f $(BUILD_PATH)/ZenTao*.zip $(RELEASE_PATH)/ZenTaoPMS.$(VERSION).zip $(RELEASE_PATH)/ZenTaoALM.$(VERSION).int.zip
	cp ZenTaoPMS.$(VERSION).zip $(BUILD_PATH)
	cp ZenTaoPMS.$(VERSION).zip ZenTaoALM.$(VERSION).int.zip $(RELEASE_PATH)
ci:
	make ciCommon
	php tools/packZip.php $(VERSION)
	sh zip.sh
	rm -rf tmp/
	php tools/packDeb.php $(VERSION)
	sh deb.sh
	rm -rf tmp/
	php tools/packRpm.php $(VERSION)
	sh rpm.sh
	rm -rf tmp/
	rm -f zentaobiz*.zip zentaomax*.zip $(RELEASE_PATH)/ZenTaoALM.$(VERSION)*.zip $(RELEASE_PATH)/ZenTaoPMS.$(VERSION)*.zip $(RELEASE_PATH)/*.deb $(RELEASE_PATH)/*.rpm *.sh $(RELEASE_PATH)/pmsPack/*.zip $(RELEASE_PATH)/pmsPack/deb/* $(RELEASE_PATH)/pmsPack/rpm/*
	mv ZenTaoALM.$(VERSION).int.php*.zip ZenTaoPMS.$(VERSION).php*.zip $(RELEASE_PATH)/pmsPack
	mv *.deb $(RELEASE_PATH)/pmsPack/deb
	mv *.rpm $(RELEASE_PATH)/pmsPack/rpm
