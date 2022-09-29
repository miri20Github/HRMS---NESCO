<?php
    function openPanel($title){
        $openPanel = '<div class="panel panel-default" style="width:100%; margin-left:auto; margin-right:auto;">
            <div class="panel-heading"> <b> '.$title.' </b> </div>  	  
                <div class="panel-body">';
        return $openPanel;
    }
    function closePanel(){
        return '</div></div>';
    }

    function tblHeader($td_arr,$id){    
        $tbl = "<table class='table table-striped table-bordered' id='".$id."' style='font-size:11px'>
                    <thead> <tr>";
                        foreach($td_arr as $value){
                            $tbl .= "<th> $value </th>";
                        }
        return $tbl ."</tr></thead></table>";
    }

    function showTble($title,$td_arr,$id)
    {
        $tbl  = openPanel($title);
        $tbl .= tblHeader($td_arr,$id);
        $tbl .= closePanel();
        return $tbl;
    }

if($tblid == "newemployees")
{    
    $td_arr = array('NAME','POSITION','BUSINESS UNIT','DEPARTMENT','SECTION','STARTDATE','EOCDATE');
    echo showTble($title,$td_arr,"newemployees");
}
else if($tblid == "newjobtrans")
{
    $td_arr = array('TRANSFER NO','EMPNO','NAME','EMPTYPE','EFFECTIVE ON','OLD POSITION','RECORD NO');
    echo showTble($title,$td_arr,"newjobtrans");
}
else if($tblid == "newblacklist")
{
    $td_arr = array('EMP NO','NAME','REPORTED BY','BLACKLIST DATE','REASON');
    echo showTble($title,$td_arr,"newblacklist");
}
else if($tblid == "statistics_details")
{
    $td_arr = array('EMPID','NAME','POSITION','BUSINESS UNIT','DEPARTMENT','SECTION');
    echo showTble($title,$td_arr,"statisticsdetails");
}
?>