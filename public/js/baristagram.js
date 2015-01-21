// This is used to only show the Baristagram icon for New York based employees
$(function() {
	function readCookie(name) {
	    var nameEQ = name + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0;i < ca.length;i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1,c.length);
	        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	    }
	    return null;
	}
	var cookieValue = readCookie('glgOffice')

	if (cookieValue == "New_York_850" || cookieValue == "New_York_-_850" || cookieValue == "NY" || cookieValue == "New_York" || cookieValue == "New_York_845") {
	} else {
		$('a[href$="baristagram/"]:first').parent().hide();
	}
});