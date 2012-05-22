
//######################################################################
//# OVERVIEW:  the AJAX core funcitons 
//# ALGORITHM/DEAILS: 
//#    - see readme.txt for the complete protocol.
//######################################################################


//######################################################################
//# Overview:  sends the message
//# Parameter: message, sender and recepient.
//######################################################################

function addMessage(message, sender, recepient) {
	if (message != "") {	
		var xmlHttp=GetXmlHttpObject();
		if (xmlHttp==null) {
			alert ("Browser does not support HTTP Request");
			return;
		}
	
		var url="lib/addMessage.php";
		message_obj = sender + ": " + message + "\n";
		message_sub = "You: " + message + "\n";
		url=url+"?message=" + message_obj + "&recepient=" + recepient;
		xmlHttp.onreadystatechange=function(){ addMessageStateChanged(xmlHttp, message_sub, sender);};
		xmlHttp.open("GET",url,true);
		xmlHttp.send(null);
	}
}

//######################################################################
//# Overview:  confirms the message is sent.
//# Parameter: xmlHttp, sender.
//######################################################################

function addMessageStateChanged(xmlHttp, message, sender) { 
	if (xmlHttp.readyState == 4 ) {
		// if the send was successful, essentially do nothing
		// redisplay the message to the sender
		document.getElementById("messages").innerHTML += message;
	}
}

//######################################################################
//# Overview:  see if any new message has arrived
//# Parameter: xmlHttp, sender.
//######################################################################

function getMessages(user){

	var xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) {
 		alert ("Browser does not support HTTP Request");
 		return;
 	}

	var url="lib/getMessages.php";
	url=url+"?user=" + user;

	xmlHttp.onreadystatechange=function(){ getMessagesStateChanged(xmlHttp);};
	xmlHttp.open("GET",url,true); // arg1=GET/POST, arg2=url, arg3=isAsynchronous
	xmlHttp.send(null); // null is replaced with the body of POST
}

function getMessagesStateChanged(xmlHttp) { 
	if (xmlHttp.readyState == 4 ) {
		if (xmlHttp.responseText != "") {
			document.getElementById("recent").innerHTML = xmlHttp.responseText;
			document.getElementById("messages").innerHTML += xmlHttp.responseText;
		}
	}
}


function GetXmlHttpObject() {
	var xmlHttp=null;
	try { xmlHttp=new XMLHttpRequest(); } 
	catch (e) {
		try { xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); } 
		catch (e)  { xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }
	}
	return xmlHttp;
}

//######################################################################
//# Overview:  periodic message retreval
//# Parameter: sender
//######################################################################

function checkMessage(user) {
	var intervalId=0;
	intervalId=setInterval("getMessages(\"" + user + "\")", 2000);
}