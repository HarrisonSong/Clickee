// Login Form

$(function() {
    var button = $('#loginButton');
    var box = $('#loginBox');
    var form = $('#loginForm');
    button.removeAttr('href');
    button.mouseup(function(login) {
        box.toggle();
        button.toggleClass('active');
    });
    form.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#loginButton').length > 0)) {
            button.removeClass('active');
            box.hide();
        }
    });
    var signInbutton = $('#signInButton');
    var signInbox = $('#signInBox');
    var signInform = $('#signInForm');
    signInbutton.removeAttr('href');
    signInbutton.mouseup(function(login) {
        signInbox.toggle();
        signInbutton.toggleClass('active');
    });
    signInform.mouseup(function() { 
        return false;
    });
    $(this).mouseup(function(login) {
        if(!($(login.target).parent('#signInButton').length > 0)) {
            signInbutton.removeClass('active');
            signInbox.hide();
        }
    });
});
