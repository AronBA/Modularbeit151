# Just do nothing
is a very boring and not very Unique WebApp.

![img_2.png](img/github/img.png)


## About
This is a boring WebApp. You have to Log in to use the App. There are two types of Users.
Admins can delete, edit and create users, but don't have access to the userspace.
User can create, edit and delete ToDo's. Every ToDo has a category, only Users with access to the ToDo's Category can see them.
Only the creator of a ToDO can edit it.

## Config

#### Database
The database backup is located in the db folder.

#### Admin Login
Username: AronderAdmin <br>
Password: Adminpasswort123$

#### Database User
The Database is configured on the root user.
You should change the database user to something more secure, the config file is on this location in the project: common/Backend/DB/DB_config.php 

Database User:
```mysql
create user db_user@localhost
	identified by 'youpasswordhere';

GRANT select,delete,update,insert ON db_m151_modularbeit TO 'db_user'@'localhost';

FLUSH PRIVILEGES;
```
Database Admin:
```mysql
create user db_admin@localhost
	identified by 'youpasswordhere';

GRANT ALL PRIVILEGES ON db_m151_modularbeit TO 'db_admin'@'localhost';

FLUSH PRIVILEGES;

```








