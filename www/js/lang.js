/**
 *	OIBS - Open Innovation Banking System
 *	Javascript: Change  language
 *	
 *	authors:	Jani Palovuori 		<jani.palovuori@cs.tamk.fi>
 *				Matti Särkikoski 	<matti.sarkikoski@cs.tamk.fi>
 */
function setCharAt(str,index,character) {
	if(index > str.length-1) return str;
	return str.substr(0,index) + character + str.substr(index+1);
}

function getSelection(selection)
{
	alert(selection);
}
