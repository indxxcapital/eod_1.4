<?php
class Block_indxxstatics extends Block
{
     function __construct($smarty)
	{
		   parent::__construct($smarty);
	$indxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_indxx WHERE status='1' ");
		$this->smarty->assign("totalActiveindxx",$indxx['totalindxx']);
		$newindxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_indxx_temp WHERE status='1' and recalc='0' ");
		$this->smarty->assign("totalNewindxx",$newindxx['totalindxx']);
		$REindxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_indxx_temp WHERE status='1' and recalc='1'");
		$this->smarty->assign("totalRebalanceindxx",$REindxx['totalindxx']);
		$indxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_indxx_ticker ");
		$this->smarty->assign("totalTicker",$indxx['totalindxx']);	
		$totalticker=$this->db->getResult("SELECT distinct(ticker) as totalticker FROM tbl_indxx_ticker union SELECT distinct(ticker) as totalticker FROM tbl_indxx_ticker_temp  union SELECT distinct(ticker) as totalticker FROM tbl_runnsecurities_replaced union SELECT distinct(ticker) as totalticker FROM tbl_tempsecurities_replaced");
		$this->smarty->assign("uniqueTicker",count($totalticker));	
		/*$indxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_ca where eff_date>='".date('Y-m-d')."' ");
		$this->smarty->assign("totalca",$indxx['totalindxx']);*/
		$indxx=$this->db->getResult("SELECT count(id) as totalindxx FROM tbl_ca_user where status='1' ");
		$this->smarty->assign("totalauser",$indxx['totalindxx']);
	}
	
}
?>