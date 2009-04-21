function checkLogin() {
  var uri = "/wp-content/plugins/twig/twig-data.php?command=verify_login";
  jQuery.ajax({
  		type: "POST",
  		url: uri,
  		data: "username="+jQuery('#twig_config_twitter_username').val()+"&password="+jQuery('#twig_config_twitter_password').val(),
  		dataType: "json",
  		success: function(msg){
      	if(msg) {
      		alert("Success: Username and password are valid!");
      	} else {
      		alert("Error: Login failed.");
      	}
  		}
  	});
}

function restoreDefaults() {
  var twig_update_fields = new Array(
    "twig_config_refresh_interval", 
    "twig_config_tweet_limit", 
    "twig_config_hide_replies", 
    "twig_config_prepend_username", 
    "twig_config_trim_dashes", 
    "twig_config_show_template", 
    "twig_config_date_format",
    "twig_config_tweet_filter",
    "twig_config_template");
  var uri = "/wp-content/plugins/twig/twig-data.php?command=restore_defaults";

  jQuery.ajax({
  		type: "GET",
  		url: uri,
  		dataType: "json",
  		success: function(msg){
  			for(i=0;i<twig_update_fields.length;i++) {
  			  var fld = jQuery('#'+twig_update_fields[i]);
  			  var type = fld.attr('type');
  			  if(type == 'checkbox') {
  			    eval("var chk_value = msg."+twig_update_fields[i]+";");
            fld.attr('checked', chk_value == 1);
          } else {
            eval("fld.val(msg."+twig_update_fields[i]+");");
          }
        }
        alert("Default settings have been set.");
  		}
  	});
}

function ajaxCall(cmd, qs) {
	jQuery.ajax({
		type: "POST",
		url: "/wp-content/plugins/twig/twig-data.php?command="+cmd,
		data: qs,
		dataType: "json",
		success: function(msg){
		  //alert(msg.twig_config_refresh_interval);
			return msg.twig_config_refresh_interval;
		}
	});
}

function twig_validateFields(frm) {
  var twig_validate_result = false;
  jQuery(document).ready(function(){


    twig_validate_result = jQuery("#twig_admin_options").validate({
      rules: {
        twig_config_refresh_interval: {
          required: true,
          number: true,
          min: 15
        },
        twig_config_tweet_limit: {
          required: true,
          number: true,
          min: 0
        }
      }
    }).form();
  });
  return twig_validate_result;
}