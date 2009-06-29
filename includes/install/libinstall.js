
function chgVal(newValue,idToChange)
{
valueToChange=document.getElementById(idToChange).value;
if (valueToChange.length==0) document.getElementById(idToChange).value=newValue;
}

function checkField(field)
{
	if (field.value=="")
	{
	 field.style.backgroundColor='#ffffb5';
     return false;
	}
	else return true;
}


function checkDataDB(frm)
{
	
	var checkOK=true;
	if (checkField(frm.elements['db_host'])==false) checkOK=false;
	if (checkField(frm.elements['db_name'])==false) checkOK=false;
    if (checkField(frm.elements['users_db_host'])==false) checkOK=false;
	if (checkField(frm.elements['users_db_name'])==false) checkOK=false;
	if (checkField(frm.elements['users_table'])==false) checkOK=false;
	if (checkField(frm.elements['users_field'])==false) checkOK=false;
	if (checkField(frm.elements['pwd_field'])==false) checkOK=false;
	
	if (checkOK==false) alert('Les champs en surbrillance sont obligatoires !');
		
	return checkOK;
}

function checkDataCAS(frm)
{
var checkOK=true;
	if (checkField(frm.elements['db_host'])==false) checkOK=false;
	if (checkField(frm.elements['db_name'])==false) checkOK=false;
    if (checkField(frm.elements['casservername'])==false) checkOK=false;
    if (checkField(frm.elements['ldap'])==false) checkOK=false;
    if (checkField(frm.elements['dnread'])==false) checkOK=false;
    if (checkField(frm.elements['user_root'])==false) checkOK=false;
	
	if (checkOK==false) alert('Les champs en surbrillance sont obligatoires !');
		
	return checkOK;
	}

	function checkCase(c)
	{
		c[0].checked=true;
	}