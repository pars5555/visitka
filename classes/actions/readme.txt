* For what is the folder

This directory should contain all project specific action files, that are
placed within the corresponding packages (subfolders of this directory).
 
 Below are the basic steps of Action execution:
1) Actions are requested via a special URL patterns "http://host/dyn/actionpackage_actionInnerPackage/do_action_name".
   For example, for requesting NgsExampleAction.class.php the "http://host/do_ngs_example" URL should be used.
2) As a result of the request, the framework will load the action's class and automatically will call its "service" method. 
   That method should contain the project specific custom logic/code. 

* What kind of files can be placed there

Only php files that contain definitions of action classes.

* File system access rights

  Read only by server process (httpd).

* Example

For example you can identify variables like this:
   
   $ngsData["example_id"] = $_REQUEST["example_id"];
   $ngsData["user_id"]    = $_REQUEST["user_id"];
   $ngsData["UserName"]   = $_REQUEST["UserName"];
   $ngsData["LAST_TIME"]  = $_REQUEST["LAST_TIME"];
   
   or you can write like this:
  
   $ngsData["example_id"] = $_GET["example_id"];
   $ngsData["user_id"]    = $_GET["user_id"];
   $ngsData["UserName"]   = $_GET["UserName"];
   $ngsData["LAST_TIME"]  = $_GET["LAST_TIME"];


   
