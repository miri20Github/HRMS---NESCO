  <?php
  $text="Username must contain at least 4 characters,only letters, numbers, hyphens and underscores are accepted!";
  include("save_new_user.php"); 
	
	$employeetypee = "and (emp_type IN ('NESCO','NESCO Contractual','NESCO-PTA','NESCO-PTP','NESCO Regular','NESCO Regular Partimer','NESCO Probationary') )";
  ?>
  <div class="panel panel-default" style='margin-left:auto;margin-right:auto;width:99%;'>
	  <div class="panel-heading"> <b> ADD NEW USER ACCOUNT </b> </div>
	    <div class="panel-body">
        <form action='' method="post" onsubmit="return validate();">
        <table width='100%'>
          <tr>
            <td>
              <label>NESCO Employee</label>
              <input list="se" id="namesearch" type="text" required class="form-control" name="emp_id" autocomplete="off" placeholder="  Search Employee"
                      value="<?php echo @$key;?>"/>
                      <datalist id="se">
                              <?php
                              $res = mysql_query("SELECT emp_id, name from employee3 where current_status = 'active' $employeetypee");                      
                  while($rs = mysql_fetch_array($res))
                  {            
                    $ax = $rs['emp_id']."*".$rs['name'];?>
                    <option value="<?php echo $ax;?>"><?php echo $ax;?></option><?php           
                  }?>
              </datalist> 
            </td>	
          </tr>
          <tr>	
            <td>
              <label>Usertype</label>
              <select class="form-control" required name='usertype' onchange="setusername(this.value)">
                <option></option>
                <option value="employee">Employee</option>
                <option value="nesco">NESCO </option>
              </select> 				
            </td>
          </tr>
          <tr>
            <td>
              <label>Username</label>
              <input type="text" onchange=" this.setCustomValidity(this.validity.patternMismatch ? '<?php echo $text;?>' : '');" name="user" id="user" class="form-control" required value="<?php echo "";?>"/>
              <input type="hidden" id="username" name="username" class="form-control"/>
            </td>
          </tr>
          <tr>
            <td>
              <label>Password</label>
              <input type="password" id="pass" name="pass" class="form-control"/>
              <input type="hidden" id="password" name="password" class="form-control" />
              <br><input type="button" class="btn btn-primary btn-sm" name="" onclick='setdefaultPass()' required value="set default password"/> <i>Default password: Hrms2014</i>
            </td>
          </tr>
          <tr>
            <td colspan='2'><hr><input type="submit" class="btn btn-primary" name="submit" value="Submit"/>
            <a href='?p=home' class="btn btn-default">Cancel</a></td>
          </tr>
        </table>
        </form>
      </div>
    </div>
  </div>

  <script>
  function setdefaultPass()
  {
    var defaults = 'Hrms2014';
    document.getElementById('password').value = defaults;
    document.getElementById('pass').value = defaults;
    document.getElementById('pass').disabled = true;      
    document.getElementById('username').value = document.getElementById('user').value;  
  }
  function validate()
  {
    var pass = document.getElementById('pass').value;
    if(pass == '')
    {
      alert('Please Set Password First!')
      return false;
    }
  }
  function setusername(val)
  {  
    var emp = document.getElementById('namesearch').value;
    var spl = emp.split('*');
    if(val == "employee" || val == "supervisor" || val=="franchise")
    {
      document.getElementById('user').value = spl[0];  
      document.getElementById('user').disabled = true; 
      document.getElementById('username').value = spl[0];
      if(val!='franchise')
      {
        $("#fAccess").hide();
        $("#fLabel").hide(); 
      } 
    }
    else{
      document.getElementById('user').value = ''; 
      document.getElementById('user').disabled = false; 
    }
    if(val=='franchise')
    {
        $("#fAccess").show();
        $("#fLabel").show();
    }
  }
  </script>