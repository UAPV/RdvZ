PKG=rdvz-1.2
PKGPATH=/home/alex/Dev/RELEASE/$(PKG)

all:
	-rm -rf $(PKGPATH) $(PKGPATH).tgz
	mkdir $(PKGPATH)
	cp -R * $(PKGPATH)
	cd $(PKGPATH) && rm Makefile
	cd $(PKGPATH)/includes && rm config.inc*
	find $(PKGPATH) -name '.svn' -type d -print0 | xargs -r0 rm -r
	find $(PKGPATH) -name '.old' -type d -print0 | xargs -r0 rm -r
	find $(PKGPATH) -name '.bak' -type d -print0 | xargs -r0 rm -r
	find $(PKGPATH) -name '.*~' -type d -print0 | xargs -r0 rm -r


	cd $(PKGPATH) && cd .. && tar cvzf $(PKG).tgz $(PKG)
	rm -rf $(PKGPATH) 
	@echo	
	@echo "$(PKG).tgz release is out ! (in /tmp)"
	@echo

