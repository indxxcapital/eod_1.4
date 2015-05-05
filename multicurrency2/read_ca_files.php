<?php include("function.php");

error_reporting(E_ALL);
set_error_handler("error_handler", E_ALL);

//$start_time = get_time();

/* Execution time for the script. Must be defined based on performance and load. */
ini_set('max_execution_time', 60 * 60);
ini_set("memory_limit", "1024M");

/* Prepare logging mechanism */
prepare_logfile();
//date("Y-m-d")
/* Define date for fetching input files and manipulations */
if ($_GET['date'])
	define("file_date", $_GET['date']);
else
	define("file_date", date("Y-m-d"));
	
	
if ("Fri" == date("D", strtotime(file_date)))
	define("date", date("Y-m-d", strtotime("+3 day", strtotime(file_date))));
else
	define("date", date("Y-m-d", strtotime("+1 day", strtotime(file_date))));	

define("log_file", get_logs_folder() . "ca_process_logs_" . date('Y-m-d_H-i-s', $_SERVER ['REQUEST_TIME']) . ".txt");
	
//date="2014-04-27";
define("ca_file", get_input_file("CA", file_date));
//echo ca_file;
delete_old_ca();

read_ca_file();


//echo date;

?>