PKG=rdvz-1.0
PKGPATH=/home/alex/Dev/RELEASE/$(PKG)
SAUVE=/home/alex/Dev/RELEASE/sauve

all:
	-rm -rf $(PKGPATH) $(PKGPATH).tgz
	mkdir $(PKGPATH)
	cp -R * $(PKGPATH)
	cd $(PKGPATH) && rm Makefile
	cd $(PKGPATH)/includes && mv config.inc.RELEASE.php config.inc.php && rm *-prod.php && rm *-test.php 
	find $(PKGPATH) -name '.svn' -type d -print0 | xargs -r0 rm -r
	cd $(PKGPATH) && cd .. && tar cvzf $(PKG).tgz $(PKG) && cp  $(PKG).tgz $(SAUVE)
	rm -rf $(PKGPATH) 
	@echo	
	@echo "$(PKG).tgz release is out ! (in /Dev/RELEASE)"
	@echo

