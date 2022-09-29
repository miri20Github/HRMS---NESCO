<?php include("header.php"); ?>

<style type="text/css">
    
    .search-results{

       box-shadow: 5px 5px 5px #ccc; 
       margin-top: 1px; 
       margin-left : 0px; 
       background-color: #F1F1F1;
       width : 78%;
       border-radius: 3px 3px 3px 3px;
       font-size: 18x;
       padding: 8px 10px;
       display: block;
       position:absolute;
       z-index:9999;
       max-height:300px;
       overflow-y:scroll;
       overflow:auto; 
    } 

</style>
<div class="container-fluid">
    <div class="panel panel-default">

        <div class="panel-heading">  
            <div class="row">
              <div class="col-md-12">      
                <span style="font-size:18px;"> Reprint Permit/Contract </span>
              </div>
            </div>  
        </div>  
        <div class="panel-body">
            
            <i style="color:gray;font-size:18px"> Permit </i><br><br>
            <p><a href="javascript:void" class='btn btn-primary btn-sm' onclick="reprintPermit()"> Reprint Permit </a></span></p>
            <i> Allows reprinting of Permit to Work. </i>
            <hr>

            <i style="color:gray;font-size:18px"> Contract </i><br><br>
            <p><a href="javascript:void" class='btn btn-primary btn-sm' onclick="rePrintContract()"> Reprint Contract </a></td></span></p>
            <i> Allows reprinting of Contract of Employment. </i>
            <hr>

        </div>
      </div>
</div>

<div id = "reprintPermit" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Reprint Permit</h4>
      </div>
      <div class="modal-body">
        <div class = "reprintPermit">
            
        </div>
      </div>

      <div class="modal-footer">
        <span class = 'loadingPermit'></span>
        <button class="btn btn-primary print_btn" onclick="printPermit()"> Reprint </button>
        <button type="button" class="dis_ btn btn-default" data-dismiss="modal">Close</button>
     
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<div id = "reprintContract" class="modal fade bs-example-modal-md">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header alert-info">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Reprint Contract</h4>
      </div>
      <div class="modal-body">
        <div class = "reprintContract"></div>
      </div>
      <div class="modal-footer">
            <span class = 'loadingContract'></span>
            <button class="btn btn-primary print_btn" onclick="printContract()"> Reprint </button>
            <button type="button" class="dis_ btn btn-default " data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div>

<script>
    
    function reprintPermit(){

        $("#reprintPermit").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#reprintPermit").modal("show");
        $(".loadingPermit").html('<br><img src = "../images/ajax.gif"> <span class = loading_msg"><font size = "2">Please Wait....</font></span>');

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=reprintPermit",
            success : function(data){
            
                $(".reprintPermit").html(data);  
                $(".loadingPermit").html('');
                $(".print_btn").prop("disabled",true);
                $("[name = 'empId']").val("");
                $("[name = 'recordNo']").val("");
            }
        });
    }

    function nameSearch(key){

        $(".search-results").show();

        var str = key.trim();
        $(".search-results").hide();
        if(str == '') {
            $(".search-results-loading").slideUp(100);
        }
        else {
            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=findEmp",
                data : { str : str},
                success : function(data){
                    if(data){
                        $(".search-results").show().html(data);
                    }
                } 
            });
        }
    }

    function getEmpId(id){

        var id = id.split("*");
        var empId = id[0].trim();
        var recordNo = id[1].trim();
        var name = id[2].trim();

        $("[name='app_id']").val(empId+" * "+name);
        $("[name='recordNo']").val(recordNo);
        $("[name='empId']").val(empId);
        $(".search-results").hide();
        $(".print_btn").prop("disabled",false);

        var type = $("[name = 'type']").val();
        if(type == "contract"){

            $.ajax({
                type : "POST",
                url  : "functionquery.php?request=contractForm",
                data : { empId : empId, recordNo:recordNo},
                success : function(data){
                    if(data){
                        $(".contractForm").html(data);
                    }
                } 
            });
        }
    }

    function printPermit(){

        var rec = $("[name = 'recordNo']").val();

        var r = confirm("Generate Permit-To-Work now?")
        if(r == true){
            
            window.open("../report/permittowork_NESCO.php?rec="+rec,"_blank");

        }
    }

    function rePrintContract(){

        $("#reprintContract").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#reprintContract").modal("show");
        $(".loadingPermit").html('<br><img src = "../images/ajax.gif"> <span class = loading_msg"><font size = "2">Please Wait....</font></span>');

        $.ajax({
            type : "POST",
            url  : "functionquery.php?request=reprintContract",
            success : function(data){
            
                $(".reprintContract").html(data);  
                $(".loadingContact").html('');
                $(".print_btn").prop("disabled",true);
                $("[name = 'empId']").val("");
                $("[name = 'recordNo']").val("");
            }
        });
    }

    function sssctc(val)
    {
        if(val == 'ctc'){
            $("#cleartf").prop("disabled",false);           
            $("#ssstf").prop("disabled",true);          
            $('#ssstf').hide();
            $('#cleartf').show();
            $('#issuedon').show();  
            $('#is').show();        
        }
        else if(val == 'sss'){
            $("#ssstf").prop("disabled",false);         
            $("#cleartf").prop("disabled",true);            
            $('#ssstf').show();
            $('#cleartf').hide();
            $('#issuedon').hide();  
            $('#is').hide();
        }   
    }

    function printContract()
    {

        var r1      = $("#r1").val();
        var r2      = $("#r2").val();
        var cleartf = $("[name = 'cleartf']").val();
        var ssstf   = $("[name = 'ssstf']").val();
        var issuedon= $("[name = 'issuedon']").val();
        var issuedat= $("[name = 'issuedat']").val();
        var cdate   = $("[name = 'contractdate']").val();
        var recordNo= $("[name = 'recordNo']").val();
        var empType = $("[name = 'empType']").val();
        var witness1= $("[name = 'witness1']").val();
        var witness2= $("[name = 'witness2']").val();
        var clear   = "";

        if($("#r1").is(':checked')){
            
            clear = r1; 
        } else if($("#r2").is(':checked')){
            
            clear = r2;
        } else {

            clear = "";
        }

        if(witness1 == ""){

            alert("Please fill up WITNESS 1 first!");
            $("#witness1").css('border-color','red');
            $("#witness1").focus();
        }

        else if(witness2 == ""){

            alert("Please fill up WITNESS 2 first!");
            $("#witness2").css('border-color','red');
            $("#witness2").focus();
        }
         
        else if(clear == ""){

            alert('Please choose either to use Cedula (CTC No.) or SSS No.');
        }

        if($("#r1").is(':checked')){
            if(cleartf == ""){

                alert("Please fill up CEDULA (CTC NO.) first!");
                $("#cleartf").css('border-color','red');
                $("#cleartf").focus();

            } else if(issuedon == ""){

                alert("Please fill up ISSUED ON first!");
                $("#issuedon").css('border-color','red');
                $("#issuedon").focus();
            
            } else if(issuedat == ""){

                alert("Please fill up ISSUED AT first!");
                $("#issuedat").css('border-color','red');
                $("#issuedat").focus();

            } else if(cdate == ""){

                alert("Please fill up ISSUED AT first!");
                $("#contractdate").css('border-color','red');
                $("#contractdate").focus();
            }
        }

        if($("#r2").is(':checked')){

            if(ssstf == ""){

                alert("Please fill up SSS NO. first!");
                $("#ssstf").css('border-color','red');
                $("#ssstf").focus();

            } else if(issuedat == ""){

                alert("Please fill up ISSUED AT first!");
                $("#issuedat").css('border-color','red');
                $("#issuedat").focus();

            } else if(cdate == ""){

                alert("Please fill up DATE OF SIGNING OF CONTRACT/EMPLOYEE first!");
                $("#contractdate").css('border-color','red');
                $("#contractdate").focus();
            }
        }
        
        if(clear != "" && clear != "" && cdate != ""){  

            window.open("../report/contract_NESCCO.php?clear="+clear+"&ssstf="+ssstf+"&cleartf="+cleartf+"&issuedon="+issuedon+"&issuedat="+issuedat+"&rec="+recordNo+"&cdate="+cdate+"&w1="+witness1+"&w2="+witness2,"_blank");            
        }
    }

    function onkeyupWitness(id)
    {

        var witness = $("#"+id).val();
        if(witness.trim() != ""){

            $("#"+id).css('border-color','#ccc');
        }
    }
</script>