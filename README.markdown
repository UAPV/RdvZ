# RdvZ 2.0.3


[Project website](http://gpl.univ-avignon.fr/rdvz/)
[Bug tracker](http://github.com/UAPV/RdvZ/issues)

Developper contact :  

* [through a list](https://listes.univ-avignon.fr/wws/info/gpl)
* or by mail : romain.deveaud@univ-avignon.fr



Introduction
============

RdvZ is a web application developed by the Université d’Avignon et des Pays 
de Vaucluse which allows to create and manage meetings and to poll potential 
attendee avalibility. The application allows both anonymous and authenticated 
access (it can be either with Database or with a CAS server + LDAP or with a 
LDAP server alone), but whatever the access mode, everybody is able to vote 
without being authenticated.



Licence
=======

See the LICENSE file.



Requirements
============

Web Server requirements
-----------------------

RdvZ is developped with the web framework symfony and is based on a LAMP
architecture (Linux, Apache, MySQL, PHP), so you need to setup a web server 
in order to run RdvZ. 

Websites to check :

* https://help.ubuntu.com/community/ApacheMySQLPHP if you are running Ubuntu, 
* http://wiki.debian.org/LaMp if you are running Debian,
* http://www.lamphowto.com/ if you need further help.

Of course MySQL is not required, you can use for example PostgreSQL, Oracle...

In addition, the install bash script requires php-cli to run (PHP Command Line),
if you are running a Debian based distribution, just do :

        $ apt-get install php5-cli
 

Symfony requirements
--------------------

The symfony version used for RdvZ is symfony1.3. It requires :

* PHP >= 5.2.4, and ideally not 5.2.9

It also requires some optional things, if you want to perform a clean install
you may want to check it, do :

        $ wget http://sf-to.org/1.4/check.php
        $ php check_configuration.php

You should also execute the checker from a browser and fix the issues it might 
discover. That's because PHP can have a distinct php.ini configuration file for 
these two environments, with different settings.

Don't forget to remove the index.html file in your root web browser directory.


CAS requirements
----------------

If you plan to run RdvZ with a CAS authentication, note that the php-cas library
is already include in the RdvZ sources, but it requires the curl library :

        $ apt-get install php5-curl


LDAP requirements
-----------------

If you plan to run RdvZ with a LDAP (with or without CAS), note that the php-ldap
library is required :

        $ apt-get install php5-ldap



Install
=======

See the INSTALL file.



I was using RdvZ 1.x and I want my datas in RdvZ 2.0 !
======================================================

You just have to run a command : 

        $ php symfony rdvz:retrieve-old-datas "<rdvz1_database_dsn>" <db_user> <db_password>

The DSN has the following syntax : [pdo]:host=[host_serv];dbname=[db_name]
Where :

* pdo         : mysql, pgsql... (values accepted by PHP PDO : http://www.php.net/manual/en/pdo.drivers.php)
* host_serv   : the server where the database is hosted
* db_name     : the name of the database 
* db_user     : the user that has rights on the database (usually root)
* db_password : the db password of the user


RdvZ 2.x was already running on my web server, but I want to install the latest version
=========================================================================================

Just run the install script as you did the first time, the database structure
will be automatically updated.



I want LDAP autocompletion on mail adresses
===========================================

See the README of `plugins/uapvFormExtraPlugin`. Currently a very experimental feature, 
feel free to contribute !    



Did you find a bug?
===================

Or maybe you think the application or the installer can be improved? Visit our 
bugtracker at http://github.com/UAPV/RdvZ/issues and create a ticket to describe 
your problem (you can leave your messages in french if you are comfortable with it) 
; we will answer as soon as possible.



For a better RdvZ experience...
===============================

RdvZ works on Firefox, Google Chrome, Opera and Safari browsers, but its design
is optimized for the following versions :

* Firefox >= 3.6 (which is a major update, if you are not currently using you should seriously consider upgrading your version),
* All Google Chrome versions
