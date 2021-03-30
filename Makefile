all: flow
clean:
	rm -fr max
	rm -fr *.zip
flow:
ifneq ($(wildcard ranzhi),)
	rm ranzhi -r
endif
	mkdir -p max/www
	# move max extension to max.
	cp -fr config max 
	cp -fr db max 
	cp -fr module max 
	cp -fr www/* max/www 
	cp www/favicon.ico max/www 
