VERSION     = $(shell head -n 1 VERSION)
XUANPATH    = $(shell head -n 1 XUANPATH)
XUANVERSION = $(shell head -n 1 XUANVERSION)

all: pms
clean:
	rm -fr zentaopms
	rm -fr zentaostory
	rm -fr zentaotask
	rm -fr zentaotest
	rm -fr *.tar.gz
	rm -fr *.zip
	rm -fr api*
	rm -fr build/linux/lampp
	rm -fr lampp
common:
	mkdir zentaopms
	cp -fr bin zentaopms/
	cp -fr config zentaopms/ && rm -fr zentaopms/config/my.php
	cp -fr db zentaopms/
	cp -fr doc zentaopms/ && rm -fr zentaopms/doc/phpdoc && rm -fr zentaopms/doc/doxygen
	cp -fr framework zentaopms/
	cp -fr lib zentaopms/
	cp -fr module zentaopms/
	cp -fr www zentaopms && rm -fr zentaopms/www/data/ && mkdir -p zentaopms/www/data/upload
	mkdir zentaopms/tmp
	mkdir zentaopms/tmp/cache/ 
	mkdir zentaopms/tmp/extension/
	mkdir zentaopms/tmp/log/
	mkdir zentaopms/tmp/model/
	mv zentaopms/www/install.php.tmp zentaopms/www/install.php
	mv zentaopms/www/upgrade.php.tmp zentaopms/www/upgrade.php
	cp VERSION zentaopms/
	# combine js and css files.
	cp -fr tools zentaopms/tools && cd zentaopms/tools/ && php ./minifyfront.php
	rm -fr zentaopms/tools
	# create the restart file for svn.
	# touch zentaopms/module/svn/restart
	# delee the unused files.
	find zentaopms -name .gitkeep |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	# notify.zip.
	mkdir zentaopms/www/data/notify/
zentaoxx:
	#xuanxuan
	mkdir -p zentaoxx/config/ext
	mkdir -p zentaoxx/lib
	mkdir -p zentaoxx/module
	mkdir -p zentaoxx/framework
	mkdir -p zentaoxx/db
	mkdir -p zentaoxx/www
	mkdir -p zentaoxx/module/common/ext/model/
	cd $(XUANPATH); git archive --format=zip --prefix=xuan/ $(XUANVERSION) > xuan.zip
	mv $(XUANPATH)/xuan.zip .
	unzip xuan.zip
	cp xuan/ranzhi/config/ext/xuanxuan.php zentaoxx/config/ext/
	cp -r xuan/ranzhi/lib/phpaes zentaoxx/lib/
	cp -r xuan/ranzhi/framework/xuanxuan.class.php zentaoxx/framework/
	cp -r xuan/ranzhi/db/*.sql zentaoxx/db/
	cp -r xuan/ranzhi/app/sys/chat zentaoxx/module/
	cp -r xuan/ranzhi/app/sys/common/ext/model/hook zentaoxx/module/common/ext/model/
	cp -r xuan/ranzhi/app/sys/action zentaoxx/module/
	cp -r xuanxuan/config/* zentaoxx/config/
	cp -r xuanxuan/module/* zentaoxx/module/
	cp -r xuanxuan/www/* zentaoxx/www/
	mv zentaoxx/www/xuanxuan.php zentaoxx/www/x.php
	sed -i 's/site,//' zentaoxx/module/chat/model.php
	sed -i 's/admin, g/g/' zentaoxx/module/chat/model.php
	sed -i '/password = md5/d' zentaoxx/module/chat/control.php
	sed -i '/getSignedTime/d' zentaoxx/module/chat/control.php
	sed -i 's/tree/dept/' zentaoxx/module/chat/control.php
	sed -i 's/tree/dept/' zentaoxx/module/chat/model.php
	sed -i "s/, 'sys'//" zentaoxx/module/chat/control.php
	sed -i 's/system.sys/system/' zentaoxx/module/chat/control.php
	sed -i 's/&app=sys//' zentaoxx/module/chat/control.php
	sed -i 's/file->createdBy/file->addedBy/' zentaoxx/module/chat/control.php
	sed -i 's/file->createdDate/file->addedDate/' zentaoxx/module/chat/control.php
	sed -i 's/im_/zt_im_/' zentaoxx/db/*.sql
	sed -i 's/sys_user/zt_user/' zentaoxx/db/*.sql
	sed -i 's/sys_file/zt_file/' zentaoxx/db/*.sql
	sed -i '/sys_entry/d' zentaoxx/db/*.sql
	mkdir zentaoxx/tools; cp tools/cn2tw.php zentaoxx/tools; cd zentaoxx/tools; php cn2tw.php
	rm -rf zentaopms/tools
	zip -rqm -9 zentaoxx.$(VERSION).zip zentaoxx/*
	rm -rf xuan.zip xuan zentaoxx
package:
	# change mode.
	chmod -R 777 zentaopms/tmp/
	chmod -R 777 zentaopms/www/data
	chmod -R 777 zentaopms/config
	chmod 777 zentaopms/module
	chmod 777 zentaopms/www
	chmod a+rx zentaopms/bin/*
	if [ ! -d "zentaopms/config/ext" ]; then mkdir zentaopms/config/ext; fi
	for module in `ls zentaopms/module/`; do if [ ! -d "zentaopms/module/$$module/ext" ]; then mkdir zentaopms/module/$$module/ext; fi done
	find zentaopms/ -name ext |xargs chmod -R 777
	mkdir zentaopms/tools; cp tools/cn2tw.php zentaopms/tools; cd zentaopms/tools; php cn2tw.php
	rm -rf zentaopms/tools
pms:
	make common 
	make zentaoxx 
	unzip zentaoxx.*.zip
	cp zentaoxx/* zentaopms/ -r
	cat zentaoxx/db/xuanxuan.sql >> zentaopms/db/zentao.sql
	make package
	zip -rq -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms zentaoxx zentaoxx.*.zip
en:
	make common
	cd zentaopms/; grep -rl 'zentao.net'|xargs sed -i 's/zentao.net/zentao.pm/g';
	cd zentaopms/; grep -rl 'http://www.zentao.pm'|xargs sed -i 's/http:\/\/www.zentao.pm/https:\/\/www.zentao.pm/g';
	cd zentaopms/config/; echo >> config.php; echo '$$config->isINT = true;' >> config.php
	make package
	zip -r -9 ZenTaoPMS.$(VERSION).int.zip zentaopms
	rm -fr zentaopms
	echo $(VERSION).int > VERSION
	make deb
	make rpm
	echo $(VERSION) > VERSION
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
	sudo dpkg -b buildroot/ ZenTaoPMS_${VERSION}_1_all.deb
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
patchphpdoc:
	sudo cp misc/doc/phpdoc/*.tpl /usr/share/php/data/PhpDocumentor/phpDocumentor/Converters/HTML/frames/templates/phphtmllib/templates/
phpdoc:
	phpdoc -d bin,framework,config,lib,module,www -t api -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d bin,framework,config,lib,module,www -t api.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
doxygen:
	doxygen doc/doxygen/doxygen.conf
