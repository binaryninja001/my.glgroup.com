$(function() {
  if($.browser.msie){
    setTimeout(function(){
      $('input').blur();
    },100);
  }
});

function checkResetForm(form)
  {
    if(form.user_name.value == "") {
      alert("Error: Username cannot be blank! Please enter your email address");
      form.user_name.focus();
      return false;
    }
    if(form.old_password.value == "") {
      alert("Error: Please check that you've entered your old password!");
      form.old_password.focus();
      return false;
    } else if(form.new_password.value == "") {
      alert("Error: Please check that you've entered a new password!");
      form.new_password.focus();
      return false;
    } else if(form.new_password.length < 8) {
      alert("Error: Please check that your password is at least 8 characters!");
      form.new_password.focus();
      return false;
    } else if(form.confirm_new_password.value == "") {
      alert("Error: Please check that you've confirmed your new password!");
      form.confirm_new_password.focus();
      return false;
    } else if(form.new_password.value != form.confirm_new_password.value) {
      alert("Error: New password fields do not match!");
      form.confirm_new_password.focus();
      return false;
    }
    return true;
  }
