Bahn Info Script
==================

.. note::

  this code was written as project for an exam at the university, php was requested :)

requirements (debian)
-----------------------

* php-tidy for cleaning up the broken markup from the website
* php-sqlite for the default db
* php-mysql for the db you would probably use in production setups

configuration
-----------------

the defaults are:
* dbfile.sqlite as database, using sqlite
* "Berlin" as station

to change one needs to create a file named `dbconfig.php`

example::

  $db = new PDO('mysql:host=localhost;dbname=fahrplan', $user, $passwd);
  $my_station = 'Gotha';



usage
-----

$ php script.php
  prints a listing of the current arrival times in Berlin


$ php dbadmin.php create
  create the needed table

$ php dbadmin.php sync
  load new items from the website, update the database

$ php dbadmin.php show
  print a listing of the next 10 items of relevance

$ php dbadmin.php clear
  drop the database table content

$ php dbadmin.php kill
  drop the database table



XXX Todo!!
