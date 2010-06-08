![Massidea.org](http://www.massidea.org/images/massidea_logo.png "Massidea.org")

Massidea.org is a free open innovation community where users are uploading their ideas, visions of the future and today's challenges and linking them with other user.s brainchildren.
The mixed outcome - lucky insight - is boosting individual.s creativity and enabling the birth of smashing idea.

## Git and GitHub - Guideline ##

** PLEASE DON'T USE FORK QUEUE. HOWTO MERGE LATEST CHANGES FROM UPSTREAM, READ: http://help.github.com/forking/#pulling_in_upstream_changes **

## Migration notes (Old Migration notes.txt)
 -----------------------------------------------------------
| Zend FW 1.7.4 to 1.9.0 Migration notes by Tuomas Valtanen |  
 -----------------------------------------------------------

Briefing
--------
No changes on the DB side, you can use the old SQL schemas. Repository folder for the migrated version in our SVN is /site/branches/alpha_3.0


1. 	Bootstrapping is now object -based, you should get a pretty good picture of it when you just look at the file. Zend FW no longer accepts procedural 		bootstrapping, which is only a good thing.

2. 	Custom routes are now in application/config/routes.ini. The syntax is fairly straightforward, should not be a pain to anybody.

3. 	The url helper should now work pretty much okay. Please create urls only by using the helper, if realistically possible. It's not very dynamic to use 	static urls in your redirect- and flash-function calls. :)

4. 	THE NAMING POLICY. Since Zend FW 1.8.0, the naming convention is stricter. This is because of the way more strict namespace. Custom libraries need to 	be declared in the configuration file, if such libraries are to be used. All models and form classes need to be named as:

	Module_Model_Name
	Module_Form_Name

	For example:

	Default_Model_User
	Default_Form_LoginUser

	I have no idea if this default-naming can be overdriven, but I don't see any reason for that, so I suggest we stick to this default one.

5. 	Last but not least. I did this migration in less than 3 days, so there might some mistakes in the migrated code, I'm just a human being. :)  Just let 	me know, and I'll take a look at it. Also, test the code actively and when we have killed all bugs, we can start developing on this new version of 	Zend Framework. 


Alright, these are the main points that came into my mind while doing the migration. If you have any specific questions, just ask in the forums or come chat with me on #dev.oibs in freenode.net.

See ya!
