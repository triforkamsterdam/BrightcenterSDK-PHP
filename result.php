<?php 

abstract class CompletionStatus{
	const COMPLETED = "COMPLETED";
	const INCOMPLETE = "INCOMPLETE";

}

class Result {

	public $assessmentId;
	public $questionId;
	public $studentId;
	public $score;
	public $duration;
	public $attempts;
	public $completionStatus;
	public $date;

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}
}


?>