#Brightcenter-PHP-SDK V1.0
In this repo you'll find the PHP-SDK for Brightcenter. In this file I'll describe how you can use the SDK.

###Download the project
First of all you need to download the project. You can either check it out with git or download the zip. Once you've done this you'll have all the files needed.

###Put the files in place
Then you can move the files to any folder you want. Just remember that all the downloaded files need to be in the same folder. The demo folder can be removed anytime, it just contains a little demo that show how to get the groups of a teacher.

###How to use the SDK
The SDK provides three functions you can use. First you'll need to include the `bcconnect.php` file in your file like this:

```php
include('../bcconnect.php');
```

From then you can use all the files you've downloaded, but I would recommend that you stick to `bcconnector.php` and result.php only!

###How to login and get the groups
First we need to make a new connector object like this:
```php
$connector = new BCConnect();
```

To get the groups of a teacher simply call the following method:
```php
$groups = $connector->getGroupsOfTeacher(username, password);
```
where `username` is the teachers username and `password` his password. `$groups` now contains an array with Group objects as specified in `group.php`. To get a student you can call something like:
```php
$students = $groups[0]->students;
```
if an error has occured an error message(String) will be returned.

###Get results of a student
To get the results of a student for an assessment you can use the following function:
```php
$results = $connector->getResultsOfStudentForAssessment(assessmentId, studentId);
```
where `assessmentId` is the id of the assessment as string and `studentId` is the id of the student as string. `$results` now contains an array with all the results of a student for an assessment as specified in `result.php`

###Post results of a student
To post a result of an student you can call the following method: 
```php
$connector->postResultOfStudentForAssessment(assessmentId, questionId, studentId, score, duration, CompletionStatus, date);
```
this method will return true if the result is accepted and gives back an error message if rejected.

`assessmentId` is the id of the assessment as string

`questionId` is the id of the question as string

`studentId` is the id of the student as string

`score` is the score of the student for the question as float. This can be 0.1, 0.00001, 1.3, 100.0, 847, etc.

`duration` is the time in seconds as float.

`CompletionStatus` is the completion of the question. the completionstatus can be retrieved as constant from the CompletionStatus object. You can use:
```php
CompletionStatus::COMPLETED;
//and
CompletionStatus::INCOMPLETE;
```
Please use these constants to prevent errors!

`date` is the date in milliseconds(Unix timestamp). for example: 18 dec 2013 = 1387321200000
