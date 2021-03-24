BackeryManager
![build status](https://travis-ci.org/mehdilauters/bakeryManager.png)
==============

This project is an fullweb open source backery manager tool

Currently available in French only.

The aims of this tool is to help the baker to plan and manage its production depending on statistics of previous days/month/year.
Data are plots in a visual way that helps to find very quicly the needed information.
It can generate export in Excel format, orders in pdf, send emails to customers...

a demo version is available here http://lauters.fr/bakeryManager/



![Cake Power](https://raw.github.com/cakephp/cakephp/master/lib/Cake/Console/Templates/skel/webroot/img/cake.power.gif)



## Setup
- setup your .env files with mysql users/passwords
- create initial database ````cat ./src/app/Config/Schema/databaseCreate.sql | dc exec -T bakerymanager-mysql mysql -u root -D MYSQL_DATABASE -pMYSQL_PASSWORD````
- you may need to disable ONLY_FULL_GROUP_BY https://tableplus.com/blog/2018/08/mysql-how-to-turn-off-only-full-group-by.html
