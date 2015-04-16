// This is used to determine which Human Resources homepage link to load in for the user based on their office location cookie
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

	if (cookieValue == "New_York_850" || cookieValue == "New_York_-_850" || cookieValue == "NY" || cookieValue == "New_York" || cookieValue == "New_York_845" || cookieValue == "Austin" || cookieValue == "Boston" || cookieValue == "San_Francisco" || cookieValue == "SF" || cookieValue == "Chicago" || cookieValue == "Washington_DC" || cookieValue == "Los_Angeles" || cookieValue == "LA") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=4");
	}
	if (cookieValue == "London") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=2326");
	}
	if (cookieValue == "Singapore" || cookieValue == "Hong_Kong" || cookieValue == "Seoul" || cookieValue == "Tokyo" || cookieValue == "Sydney") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=2319");
	}
	if (cookieValue == "Dublin" || cookieValue == "DUB") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=2333");
	}
	if (cookieValue == "Gurgaon" || cookieValue == "Mumbai") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", " https://services.glgresearch.com/human-resources/en-us/?page_id=2324");
	}
	if (cookieValue == "Shanghai" || cookieValue == "Beijing" || cookieValue == "Shenzhen") {
	    $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=2639");
	}
	if (cookieValue == "Paris" || cookieValue == "Dubai" || cookieValue == "Munich") {
	  $('a[href$="human-resources/en-us/"]:first').attr("href", "https://services.glgresearch.com/human-resources/en-us/?page_id=2659");
	}
});