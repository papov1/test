<?php session_start();

	include_once $_SERVER["DOCUMENT_ROOT"].'admin/classes/general.php';

	if (isset($_SESSION['admin_logged_in'])) {
	
			header('Content-Type: text/csv; charset=utf-8');
			header('Content-Disposition: attachment; filename=feedback.csv');

			$output = fopen('php://output', 'w');

			
			$feedback_arr[0] = array('Date', 'Rating (5 - best, 1 - poor)', 'Customer comment');
			$ctr = 1;

			$ratings = getRatings();
			foreach($ratings as $rating){
				$date = $rating["created"];
				$rating_value = $rating["rating"];
				$comment = $rating["comment"];

				$feedback_arr[$ctr] = array($date, $rating_value, $comment);
				$ctr++;
			}

			foreach ($feedback_arr as $line) {
			  fputcsv($output, $line, ';');
			}
			fclose($output);

	}
?>