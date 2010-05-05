To keep things under control, please read and take heed.

---

When you need to make changes to the database:

Please don't edit the creates.sql or inserts.sql, but rather add a new sql file
named with a running number and a description, e.g. 01_alter_users.sql,
02_create_groups.sql and so forth.

This way new devs could always easily and accurately recreate the database by
importing the sql files in the correct order.

---

When recreating the db on an existing dev system, this is what you should do:

* Save any test data you might have created (users, content, etc.) by exporting
it to an SQL file. Please note that the following tables will be filled with the
inserts.sql, so they should be excluded from your export:
  - content_types_cty
  - futureinfo_classes_fic
  - industries_ind
  - innovation_types_ivt
  - languages_lng
  - notifications_ntf
  - usr_roles_urr

* Delete your oibs database and recreate it by importing the files found in the
sql directory in this order:
  - creates.sql creates the database structure
  - inserts.sql inserts required fields into tables
  - numbered sql files make further alterations

* Import the custom data you previously exported.
Some key names might have changed in the new db structure. If you get errors
while importing your data, check the error messages and edit the statements
accordingly.
