* For what is the folder

This directory should contain all specific dto files.

Data transfer object (DTO) is used to transfer data between software application subsystems.
DTOs are often used in conjunction with data access objects to retrieve data from a database.
To create data transfer object (DTO) that holds all data that is required for the remote call and modify the remote method signature to accept the DTO 
as the single parameter and to return a single DTO parameter to the client.
After the calling application receives the DTO and stores it as a local object, the application can make a series of individual 
procedure calls to the DTO without incurring the overhead of remote calls.

   
* What kind of files can be placed there

In this folder should contain no business logic and limit its behavior to activities such as internal consistency checking and basic validation. 
Be careful not to make the DTO depend on any new classes as a result of implementing these methods.
 
* File system access rights
  Read only by php.
 
* Example
  
This an exmaple how can you use functionality using dto file.For example you have in database -"ngs_example"(this should identify in yourProjectName/conf/config.ini file) 2 fields:"id","example_id".
The *.sql file is in yourProjectFolder/classes/dal/ngs_example_data.sql file.
Just import in your Database.
If you want get from database data, you should identify in the manager(classes/managers/NgsExampleManager.class.php) or in your template(templates/ngs_example.tpl) like this

	dto->getField()
	dto->setField(values)
 
 