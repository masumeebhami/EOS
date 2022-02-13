
* simple API for Users Crud


How to use
------------
There are some simple Api's that you can use for select and insert/update and delete users from users database

some examples
----------------------

[1]: http://127.0.0.1:8000/api/users
<p>this api allows user for fetch all users from database. the return type is JSON and HTTP method is GET</p>
[2]: http://127.0.0.1:8000/api/user/{id}
<p>this api allows user for fetch custom user with uuid equal id input variable from database. the return type is JSON and HTTP method is GET</p>
[3]: http://127.0.0.1:8000/api/user/{id}
<p>this api allows user for fetch custom user with uuid equal id input variable from database and then delete user. the return type is JSON and HTTP method is DELETE</p>
[4]: http://127.0.0.1:8000/api/user/{id}
<p>this api allows user for fetch custom user with uuid equal id input variable from database and then update just the name of user. the return type is JSON and HTTP method is PUT</p>
<p>the parameter type is JSON and like this : { "name" : "name changed"}</p>
[5]: http://127.0.0.1:8000/api/user
<p>this api allows user for fetch custom user with uuid equal id input variable from database and then create new user. the return type is JSON and HTTP method is POST</p>
<p>the parameter type is JSON and like this : { "name" : "new user", "email" : "new@gmail.com", "username":"usernameusername", "password":"26537ff648325658"}</p>
