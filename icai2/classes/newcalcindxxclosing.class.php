<?php

class Newcalcindxxclosing extends Application{

	function __construct()
	{
		parent::__construct();
	}
	
	
	function index()
	{
		$this->closing->closingLive();
		//sleep(1);
		$this->closing->closingTemp();
				//sleep(1);

		$this->closing->compositclose();
			///	sleep(1);

		$this->closing->calccash();
			//	sleep(1);

		$this->closing->calccashtemp();
			//	sleep(1);

		$this->closing->calclsc();
			//	sleep(1);

		$this->closing->calccsi();
			//	sleep(1);

		$this->closing->calcsl();
	//	sleep(1);
$this->closing->checkivchange();	
	//	sleep(1);
$this->closing->checkpvchange();		
		}
}