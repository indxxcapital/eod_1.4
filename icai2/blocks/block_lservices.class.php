<?php
class Block_lservices extends Block
{
     function __construct($smarty)
	{
    parent::__construct($smarty);
	
	$block_main_menu=$this->db->getResult("SELECT linkUrl,name FROM tbl_service WHERE status='1' order by id desc",true,30);
   // $this->pr($block_main_menu);
	$menu=array();
	if (!empty($block_main_menu))
	{
		foreach ($block_main_menu as $key=>$value)
		{
		$menu[$value['linkUrl']]=$value['name'];
		}
		//$this->pr($menu);
		//exit;
	}
	$this->smarty->assign("lservices",$menu);
	}
	
}
?>