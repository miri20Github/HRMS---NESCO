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

if($tblid == "benefits")
{    
    $td_arr = array('EMPID','NAME','SSS','PHILHEALTH','PAGIBIG MID','PAGIBIG RTN','T.I.N.');
    echo showTble($title,$td_arr,"benefits");
}
else if($tblid == "nescoregulars")
{
    $td_arr = array('EMPNO','NAME','POSITION','EMPTYPE','BUSINESSUNIT','DEPARTMENT','SECTION');
    echo showTble($title,$td_arr,"nescoregulars");
}
else if($tblid == "blacklists")
{
    $td_arr = array('EMPNO','NAME','REPORTED BY','BLACKLIST DATE','REASON','STATUS','ACTION');
    echo showTble($title,$td_arr,"blacklists");
}
else if($tblid == "masterfile")
{
    $td_arr = array('EMPID','NAME','BUSINESS UNIT','DEPARTMENT','POSITION','TYPE','STATUS');
    echo showTble($title,$td_arr,"masterfile");
}
?>