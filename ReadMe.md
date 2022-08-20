## SimpleToDo
This is a simple todo web program. 

Todos can be sorted by a category.
This is a simple todo web "program" with an API.  
Code still looks pretty scuft but works nevertheless.  

## Prerequisite
To get this software running, an apache based web server and mariadb server is required.  
The webserver needs to have support for php (duh) as well as the for the rewrite engine.  
Also in the server config, at directory, set `AllowOverride` to `All`.  
On the database server, you are not required to create a user specificly to only access the todo database but it is highly recommended.
## Installation

Copy all files from this project into the file directory of you web server.  
To create the database just run the [creation query](https://github.com/ErikSchnittker/SimpleToDo/blob/master/db/database.sql).    
Change the connection details in [connection file](https://github.com/ErikSchnittker/SimpleToDo/blob/master/db/connection.php).

## API
You can access and manipulate all data from the API.  
The API is designed to be REST compliant.  

## IMPORTANT:

Even though the API uses authentication now, having this on an exposed server this is considered a bad idea.  
The API is vulnerable to sql injection.  
The javascript for the todo web interface is visible, and therefor the api key is visible as well.  
The webserver should be configured in a way to limit access to the web interface!