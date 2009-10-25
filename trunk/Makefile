VERSION=$(shell head -n 1 VERSION)

all: tgz

clean:
	rm -fr pms
	rm -fr *.tar.gz
tgz:
	mkdir -p pms/lib
	mkdir -p pms/doc
	cp doc/zentao.mysql4.sql pms/doc/zentao.sql
	cp doc/COPY* pms
	cp -fr lib/front pms/lib
	cp -fr config pms/
	cp -fr www pms/
	cp -fr module pms/
	find pms -name .svn |xargs rm -fr
	find pms -name tests |xargs rm -fr
	mkdir pms/cache
	tar czvf ZenTaoPMS.$(VERSION).tar.gz pms
	rm -fr pms
