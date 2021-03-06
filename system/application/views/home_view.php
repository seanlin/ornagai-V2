<?php
$data['base']=$base;
$this->load->view("header_view.php",$data);
$this->load->helper('form');
?>
<link rel="stylesheet" type="text/css" href="<?= $base ?>/css/jquery.autocomplete.css" />
<script type="text/javascript" src="<?= $base ?>/js/jquery.bgiframe.min.js"></script>
<script type="text/javascript" src="<?= $base ?>/js/jquery.autocomplete.js"></script>
<!-- Tooltip -->
<script src="<?= $base ?>/js/jquery.tooltip.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?= $base ?>/css/jquery.tooltip.css" />
<script>
var h_id;
var history_flag;
history_flag=1;
h_id=1;
$(document).ready(function(){
    $("#login_form").hide();
    
    $("#top_menu *").tooltip({
	showURL: false 
	});
    
    
    $("#message").autocomplete("<?= $base ?>/index.php/search/autocomplete", {
		
		selectFirst: false
	});
    $("#search").click(function(){
        
    if($("#message").val()!="")
    {
	 message_val=$("#message").val();
	message_val=message_val.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
	
         $.ajax({
            type: "POST",
            url: "<?= $base ?>/index.php/search",
            data: "message="+message_val,
            success: function(html){
              $("#result").html(html);
              $("#left").append('<div id="history_'+h_id+'" class="history"><a rel="'+message_val+'" href="#" class="history_result">'+message_val+'<img rel="history_'+h_id+'" src="./images/remove.png" align="middle" class="sidebar_rm" align="right" /></a></div>');
               
                h_id=h_id+1;
            },
            beforeSend:function(){
                $("#result").html("Loading...")
            }
        });
     }
        return false;
    });
    ////////////
    $("#forget_send").live("click",function(){
	
	email=$("#forg_email").val();
	 $.ajax({
            type: "POST",
            url: "<?= $base ?>/index.php/register/forgotpwd",
            data: "email="+email,
            success: function(html){
            	$("#loading").fadeOut("normal");
				if(html!="false")
				{
					$("#forget_popup").fadeOut("normal");
					$("#shadow").fadeOut();	
					//Will change with jquery popup soon
					//alert("Check Your Email,please");
				}
				else
				{
					$("#forget_popup").fadeIn("fast");
					$("#forget_message").html("Email Address Not Found");
				}
	    },
	    beforeSend:function(){
               //$("#forget_message").html("Loading...");
               $("#forget_popup").fadeOut("fast");
                $("#loading").fadeIn("normal");

        }
	 });
	    
	return false;
    });
    $("#login_btn").live("click", function(){
        if($("#login_form").is(":hidden")) {
	    $("#login_btn").attr("src","<?= $base ?>/images/user.png");
            $("#login_form").slideDown("fast");
            $("#wrapper").css("margin-top","0px");
        }
        else{
	    
	    $("#login_btn").attr("src","<?= $base ?>/images/user_off.png");
            $("#login_form").slideUp("fast");
            $("#wrapper").css("margin-top","40px");
        }

    });
    ////////////
    
    $("#login").live("click",function(){
    
        username=$("#username").val();
        password=$("#pwd").val();
         $.ajax({
            type: "POST",
            url: "<?= $base ?>/index.php/user/login",
            data: "name="+username+"&pwd="+password,
            success: function(html){
               
              if(html=='true')
              {
                $("#loading").fadeOut("fast");
                //$("#login_form").remove();
                $("#login_form").html("<a href='<?= $base ?>/index.php/user/logout' id='logout'>Logout</a> | <a href='#logout' id='change_pwd'>Change Password</a> ");
                $("#wrapper").css("margin-top","40px");
                $("#add_btn").show();
              }
              else
              {
                    $("#loading").fadeOut("fast");
                    $("#err_msg").html("Wrong username or password")
              }
            },
            beforeSend:function(){
                $("#loading").fadeIn("fast");
            }
        });
         return false;
    });
    
     $("#cancel").live("click", function(){
      
            $("#login_form").slideUp("fast");
            $("#wrapper").css("margin-top","40px");

    });

    $(".sidebar_rm").live("click", function() {
    
        frmdiv_id=$(this).attr("rel");
      //  alert(frmdiv_id);
        $("#"+frmdiv_id).fadeOut("fast");
        return false;
    });
    
    $(".page_nav").live("click",function()
    {
      
        $.ajax({
            type: "POST",
            url: "<?= $base ?>/index.php/search",
            data: "message="+$(this).attr("rel")+"&page="+$(this).html(),
            success: function(html){
              $("#result").html(html);
            },
            beforeSend:function(){
                $("#result").html("Loading...")
            }
        });
    });
    
    $("#username").focus(function(){
         $(this).css({"color":"#333"});
        if($(this).val()=="Username")
        {
            
            $(this).val("");
           
        }
    });
    
    $("#txtpwd").focus(function(){
       $(this).hide();
       $("#pwd").show();
       $("#pwd").focus();
    })
    
   $(".history").live("mouseover", function() {
    form_id=this.id;
    $("#"+form_id+" .sidebar_rm").css({"visibility":"visible"});
    
   });
   
    $(".history").live("mouseout", function() {
    form_id=this.id;
    $("#"+form_id+" .sidebar_rm").css({"visibility":"hidden"});
    
   });
    
    $("#history_onoff").click(function()
    {
       if(history_flag==1)
       {
     
        $("#history_onoff").attr("src","images/history_off.png");
        $("#left").hide();
        $("body").css({"background-image":"none"});
        history_flag=0;
      
       }
       
       else{
        $("#history_onoff").attr("src","images/history.png");
        $("#left").show();
         $("body").css({"background-image":"url('./images/bg.jpg')"});
        history_flag=1;
       }
    })
   $(".history_result").live("click", function() {

                if($(this).attr("rel")!="")
                {
                    $.ajax({
            			type: "POST",
            			url: "<?= $base ?>/index.php/search",
            			data: "message="+$(this).attr("rel"),
            			success: function(html){
              				$("#result").html(html);
               				h_id=h_id+1;
            			},
            			beforeSend:function(){
                			$("#result").html("Loading...")
            			}
        			});
                }
   });
    
   $("#chpwd_send").click(function(){
   		
   		if($("#new_pwd").val()!=$("#conf_pwd").val())
   		{
   			$("#chpwd_err").html("Not Same");
   		}
   		else
   		{
   			curr_pwd=$("#current_pwd").val();
   			new_pwd=$("#new_pwd").val();
   			conf_pwd=$("#conf_pwd").val();
   			
   			//// Change Password
   			$.ajax({
            		type: "POST",
            		url: "<?= $base ?>/index.php/user/changepwd",
            		data: "currpwd="+curr_pwd+"&newpwd="+new_pwd,
            		success: function(html){
            			if(html=='false')
            			{
            				$("#chpwd_err").html("Current Password Wrong");
            				return false;
            			}
            			
            			//alert Box will replace in there
            			$("#chpwd_popup").fadeOut();
						$("#shadow").fadeOut();
              				
            		},
            		beforeSend:function(){
                			$("#chpwd_err").html("Loading...")
            			}
        	});
        	
        	
        }

   		return false;
   });
   
   $("#new_pwd").keyup(function(){
   		if($("#conf_pwd").val()!="")
   		{
   			if($("#new_pwd").val()!=$("#conf_pwd").val())
   			{
   				$("#chpwd_err").html("Not Same");
   			}
   			else
   			{
   				$("#chpwd_err").html("");
   			}
   		}
   });
   
   $("#conf_pwd").keyup(function(){
   		if($("#conf_pwd").val()!="")
   		{
   			if($("#new_pwd").val()!=$("#conf_pwd").val())
   			{
   				$("#chpwd_err").html("Not Same");
   			}
   			else
   			{
   				$("#chpwd_err").html("");
   			}
   		}   
   });
   
   $("#chpwd_cancel_hide").click(function(){
   	$("#chpwd_popup").fadeOut();
   	$("#shadow").fadeOut();
   });
   $("#forget_password").click(function(){
	
	$("#shadow").fadeIn();
	$("#forget_message").html("");
	$("#forget_popup").fadeIn("normal");
        
   });

    $("#forget_cancel_hide").click(function(){
        $("#forget_popup").fadeOut("normal");
        $("#shadow").fadeOut();
   });
      
   $("#cancel_hide").click(function(){
        $("#add_word_popup").fadeOut("normal");
        $("#shadow").fadeOut();
   });
   
    $("#add_btn").click(function(){
	
	$("#add_err").html("");
	$("#addword").val("");
	$("#addstate").val("");
	$("#adddef").val("");
        $("#shadow").fadeIn("normal");
         $("#add_word_popup").fadeIn("normal");
         $("#addword").focus();
    });
    
    $("#change_pwd").live("click",function(){
	    $("#shadow").fadeIn("fast");
    	$("#chpwd_popup").fadeIn();
    	
    });

    
    $("#addnewword").click(function(){
	
        word=$("#addword").val(); 
        state=$("#addstate").val();
        def=$("#adddef").val();
  
        
         err_flag=false;
         
         
        
        if(word=="")
        {
            err="Word is required";
            err_flag=true;
        }
        
        if(err_flag==false)
        {
            if(state=="")
            {
                err="State is required";
                err_flag=true;
            }
        }
        
        if(err_flag==false)
        {
          if(def=="")
            {
          
            err="Defination is required";
            err_flag=true;
            }
        }
        
        if(err_flag)
        {

            $("#add_err").html(err);
            return false;
        }
     
        
          $.ajax({
            type: "POST",
            url: "<?= $base ?>/index.php/word/add",
            data: "word="+word+"&state="+state+"&def="+def,
            success: function(html){
		
                $("#add_word_popup").fadeOut("normal");
		$("#shadow").fadeOut();
               
            },
            beforeSend:function(){
                $("#add_err").html("Loading...")
            }
        });
          
          return false;
          
    });
    
        
    
});
</script>
<body>

    <div id="forget_popup">
        <div class="err" id="forget_err"></div>
       <form action="<?= $base ?>/index.php/user/forgetpwd">
       <div id="forget_message" class="err"></div>
       <label>Email : </label>
       <input type="text" id="forg_email" style="width:165px;" >
		<div class="break">
        <input type="submit" id="forget_send" value="Send">
        <input type="button" id="forget_cancel_hide" value="Cancel">
	    </div>
       </form>
    </div>
    
    <div id="chpwd_popup">
    	 <div class="err" id="chpwd_err"></div>
       <form action="<?= $base ?>/index.php/user/changepwd">
       <div id="chpwd_message" class="err"></div>
       <label>Current Password : </label>
       <input type="password" id="current_pwd" style="width:170px;" >
       <label>New Password : </label>
       <input type="password" id="new_pwd" style="width:170px;" >
       <label>Confirm Password : </label>
       <input type="password" id="conf_pwd" style="width:170px;" >
		<div class="break">
        <input type="submit" id="chpwd_send" value="Send">
        <input type="button" id="chpwd_cancel_hide" value="Cancel">
	    </div>
       </form>

    </div>
    
    <div id="add_word_popup">
        <div class="err" id="add_err"></div>
       <form action="<?= $base ?>/index.php/word/add">
       <label>Word</label>
       <input type="text" id="addword" >
        <label>State</label>
        <input type="text" id="addstate">
        <label>Def</label>
        <input type="text" id="adddef">
        <label></label><br/>
        <input type="submit" id="addnewword" value="Add">
        <input type="button" id="cancel_hide" value="Cancel">
       </form>
    </div>
    
    <div id="shadow" class="popup"></div>
    
    <div class="popup" id="loading">
    	<p><br/><br/><br/><br/><img src="<?= $base ?>/images/load.gif"></p>
    </div>
    
    <div id="login_form" class="login_frm">
    <? if($login)
    {
    ?>
    <a href='<?= $base ?>/index.php/user/logout' id='logout'>Logout</a> | <a href='#logout' id='change_pwd'>Change Password</a>
    <?
    } else {
    ?>
    <?= form_open("user/login"); ?>
    <input type="text" value="Username" id="username" >
    <input type="text" value="Password" id="txtpwd">
    <input type="password" id="pwd" style="display:none" >
    <input type="submit" value="Login" id="login">
    <input type="button" value="Cancel" id="cancel">
    <span class="err" id="err_msg"></span> &nbsp;
    <a href="<?= $base ?>/index.php/register">Not a member</a> | 
    <a href="#" id="forget_password">Forgot Password</a>
    <?= form_close(); ?>
    <? } ?>
    </div>
    <?= form_open("search/result"); ?>
    <div id="top_menu" class="top_menu">
        <img src="<?= $base ?>/images/logo.png" class="logo" >
        <input type="text" id="message" name="message" class="searchbox">
        <input type="submit" value="Search" id="search">
        <img src="./images/history.png" id="history_onoff" class="btn" title="history" >
        <img src="./images/user_off.png" id="login_btn" class="btn" title="Users">
        <? if($login) { ?>
        <img src="./images/add.png" id="add_btn" class="btn" title="Add New Word">
        <? } else { ?>
        <img src="./images/add.png" id="add_btn" class="btn" title="Add New Word" style="display:none">
        <? } ?>
    </div>
<?= form_close(); ?>
    <div id="wrapper">
        <div id="left">
            <h3>History</h3>
        </div>
        <div id="result"></div>
        <div class="history_result"></div>
    </div>
</body>
</html>