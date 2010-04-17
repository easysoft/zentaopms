VERSION=$(shell head -n 1 VERSION)

all: tgz

clean:
	rm -fr zentaopms
	rm -fr *.tar.gz
	rm -fr *.zip
tgz:
	mkdir -p zentaopms/lib
	mkdir -p zentaopms/db
	mkdir -p zentaopms/bin
	mkdir -p zentaopms/config
	cp -fr db zentaopms/
	cp -fr doc/* zentaopms/
	cp -fr lib/ zentaopms/
	cp -fr config/config.php zentaopms/config/
	cp -fr www zentaopms/
	cp -fr module zentaopms/
	cp bin/ztc* zentaopms/bin
	find zentaopms -name .svn |xargs rm -fr
	find zentaopms -name tests |xargs rm -fr
	mkdir -p zentaopms/tmp/cache
	mkdir -p zentaopms/tmp/log
	chmod 777 -R zentaopms/tmp/
	chmod 777 zentaopms/www/data
	chmod 777 zentaopms/config
	find -name *.svn zentaopms |xargs rm -fr
	zip -r -9 ZenTaoPMS.$(VERSION).zip zentaopms
	rm -fr zentaopms
zentaopmsdoc:
	phpdoc -d config,lib,module,www -t zentaozentaopms -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d config,lib,module,www -t zentaozentaopms.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
