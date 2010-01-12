VERSION=$(shell head -n 1 VERSION)

all: tgz

clean:
	rm -fr pms
	rm -fr *.tar.gz
tgz:
	mkdir -p pms/lib
	mkdir -p pms/db
	cp -fr db/zentao.sql pms/db/zentao.sql
	cp doc/COPY* pms
	cp -fr lib/ pms/
	cp -fr config pms/
	cp -fr www pms/
	cp -fr module pms/
	find pms -name .svn |xargs rm -fr
	find pms -name tests |xargs rm -fr
	mkdir -p pms/tmp/cache
	mkdir -p pms/tmp/log
	chmod 777 -R pms/tmp/
	tar czvf ZenTaoPMS.$(VERSION).tar.gz pms
	rm -fr pms
pmsdoc:
	phpdoc -d config,lib,module,www -t zentaopms -o HTML:frames:phphtmllib -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
	phpdoc -d config,lib,module,www -t zentaopms.chm -o chm:default:default -ti ZenTaoPMSAPI参考手册 -s on -pp on -i *test*
