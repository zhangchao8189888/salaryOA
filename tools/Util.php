<?php
function findNullReturnNumber($val){
	if($val==null||$val==NULL||$val==""||empty($val)){
		return 0;
	}else{
	    return $val;
	}
}