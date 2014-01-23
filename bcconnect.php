<?php

include('group.php');
include('student.php');
include('auth.php');
include('result.php');

class BCConnect{

	public $baseUrl = "https://tst-brightcenter.trifork.nl/api/";

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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$response=curl_exec ($ch);
		curl_close ($ch);



		$groups = json_decode($response);

		if(count($response) == 0){
			return "Something went wrong with loging in. Please check username/pas";
		}

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
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
		$response=curl_exec ($ch);
		curl_close ($ch);
		$resultsFromServer = json_decode($response);

		if(count($resultsFromServer) == 0){
			return "No assessments found, are you sure the assessmentId/studentId are right?";
		}

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
		 
		$ch = curl_init($url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);                                                                    
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($body))                                                                       
		);                                                                                                                   
		 
		$result = curl_exec($ch);
		curl_close($ch);

		if(strlen($result) > 0){
			return "Something went wrong with posting a result :( One or more parameters have the wrong value.";
		}
		return "Result is posted to the server!";

	}

}
?>
