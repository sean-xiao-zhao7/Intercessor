
function edit(row_index, item) {

    /**
        edit
            - shows an text input box for entering edits to user information.
            - this submits to profile.php ( the same page it is on)
        @param row_index: the item's residing row.
               item: the content that is to be edited.
    */
    var row = document.getElementById("userinfo").getElementsByTagName("tr")[row_index];
    
    //remove the "edit" td
    var td = row.getElementsByTagName("td")[2];
    row.removeChild(td);    
    
    //display a text submit form or a birthday menu
    var target =  row.getElementsByTagName("td")[1];
    target.colSpan = "3";

    if (item == "birthdate") {
	
	/* if the birthdate is to be changed,
	   display an select menu.
	   Also, preselect the options 
	*/
	var birthdate = target.innerHTML.split("/");    
    
	var form =  "<form method='post' action='profile.php'>\n";
	form += "<select name=\"year\">\n";	
	for (i = 1; i < 10; i++ ) {
	    var year = "198" + i;
	    if (year == birthdate[0]) {
		form += "<option value=\"" + year + "\" selected=\"selected\">" + year + "</option>\n";
	    } else {
		form += "<option value=\"" + year + "\" >" + year + "</option>\n";
	    }	    
	}
	form += "</select>\n";
	
	months = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	form += "<select name=\"month\">\n";
	for (i = 1; i < 13; i++ ) {
	    if (i == birthdate[1]) {
		form += "<option value=\"" + i + "\" selected=\"selected\">" + months[i - 1] + "</option>\n";
	    } else {
	        form += "<option value=\"" + i + "\">" + months[i - 1] + "</option>\n";
	    }
	}
	form += "</select>";
	form += "<input type=\"text\" name=\"day\" maxlength=\"2\" size=\"1\" value=\"" + birthdate[2] + "\">\n";
	form += "<input type='submit' value='Submit!' />\n </form>\n";		

	target.innerHTML = form;

    } else {
	
	/* for everything else, use a text box */	
    
	target.innerHTML =  "<form method='post' action='profile.php'>\n" +
		"<input type='text' name='" + item + "' value='" + target.innerHTML + 
		    "' length='2' maxlength='30' />\n" +
		"<input type='submit' value='Submit!' />\n </form>\n";
    }
		  
}

function displayMessage(message) {

    var div = document.getElementById("message");
     
    div.innerHTML = "<br />" + message;
    
    setTimeout("removeMessage()", 2500);
}

function removeMessage() {
    var div = document.getElementById("message");
    div.innerHTML = "";
}