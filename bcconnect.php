<?php

include('httpful.phar');
include('group.php');
include('student.php');
include('auth.php');
include('result.php');

class BCConnect{

	public $baseUrl = "http://localhost:8080/api/";

	public $username;
	public $password;

	/*
	*gets the groups of a teacher and functions as login mechanism
	*@param username the username of the teacher
	*@param password the password of the teacher
	*/
	function getGroupsOfTeacher($username, $password){

		//set the authentication to use it in other functions
		$this->username = $username;
		$this->password = $password;


		$url = $this->baseUrl . "groups";
		$response = \Httpful\Request::get($url)->authenticateWith($this->username, $this->password)->send();

		//Check if we got some JSON back, if not return an error message.
		$headers = $response->_parseHeaders($response->raw_headers);
		if($headers['Content-Type'] != "application/json;charset=UTF-8"){
			return "Something went wrong with fetching the groups! Check your username and password.";
		}

		$groups = $response->body;
		$newGroups = array();
		for($i = 0; $i < count($groups); $i++){
			$groupFromServer = $groups[$i];
			$group = new Group();
			$group->__set('name', $groupFromServer->name);
			$group->__set('id', $groupFromServer->id);

			$students = $groupFromServer->students;
			for ($j=0; $j < count($students); $j++) {
				$studentFromServer = $students[$j];
				$student = new Student();
				$student->__set('firstName', $studentFromServer->firstName);
				$student->__set('lastName', $studentFromServer->lastName);
				$student->__set('id', $studentFromServer->id);

				array_push($group->students, $student);
			}

			array_push($newGroups, $group);
		}
		return $newGroups;
	}

	/*
	*gets all the results of a student for a assessment
	*@param assessmentId the id of the assessment
	*@param studentId the id of the student
	*/
	function getResultsOfStudentForAssessment($assessmentId, $studentId){
		$url = $this->baseUrl . "assessment/" . $assessmentId . "/students/" . $studentId . "/assessmentItemResult";
		$response = \Httpful\Request::get($url)->authenticateWith($this->username, $this->password)->send();

		//Check if we got some JSON back, if not return an error message.
		$headers = $response->_parseHeaders($response->raw_headers);
		if($headers['Content-Type'] != "application/json;charset=UTF-8"){
			return "Something went wrong with fetching the results! Check the assessmentId or studentId!";
		}

		$resultsFromServer = $response->body;

		$allResults = array();
		for ($i=0; $i < count($resultsFromServer); $i++) { 
			$resultFromServer = $resultsFromServer[$i];
			$result = new Result();

			$result->assessmentId = $assessmentId;
			$result->studentId = $studentId;
			$result->score = $resultFromServer->score;
			$result->questionId = $resultFromServer->questionId;
			$result->duration = $resultFromServer->duration;
			$result->attempts = $resultFromServer->attempts;
			$result->date = date("Y-m-d H:i:s", ($resultFromServer->date / 1000));
			$result->completionStatus = $resultFromServer->completionStatus;
			array_push($allResults, $result);
		}

		return $allResults;
	}

	/*
	*posts a result to brightcenter.
	*@param assessmentId the id of the assessment
	*@param questionId the id of the question
	*@param studentId the id of the student
	*@param score(float) the score
	*@param duration(int) the duration in seconds
	*@param completionStatus(String) the completion of the assessment, use CompletionStatus::COMPLETED or CompletionStatus::INCOMPLETE 
	*@param date (int) the date in milliseconds
	*/
	function postResultOfStudentForAssessment($assessmentId, $questionId, $studentId, $score, $duration, $completionStatus, $date){
		if($assessmentId == null){
			return "assessmentId is null";
		}
		if($studentId == null){
			return "studentId is null";
		}
		if($questionId == null){
			return "questionId is null";
		}
		if($score == null){
			return "score is null";
		}
		if($duration == null){
			return "duration is null";
		}
		if($completionStatus == null){
			return "completionStatus is null";
		}
		if($date == null){
			return "date is null";
		}
		$url = $this->baseUrl . "assessment/" . $assessmentId . "/student/" . $studentId . "/assessmentItemResult/" . $questionId;
		$body = '{"score" : ' . $score . ', "duration": ' . $duration .	', "completionStatus": "' . $completionStatus .'", "date": ' . $date .'}';
		$response = \Httpful\Request::post($url)
									->sendsJson()
									->authenticateWith($this->username, $this->password)->body($body)
									->send();	
		if(strlen($response->body) > 0){
			return "Something went wrong with posting a result :( One or more parameters have the wrong value.";
		}
		return true;

	}




















}
?>