function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function isNumberDot(evt) {
	evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // alert(charCode);
    if (charCode > 31 && (charCode!=46 && charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}

function allNumbers(inputs){
    var numbers = /^[0-9]*\.?[0-9]*$/;
    if(document.getElementById(inputs).value.match(numbers))  {
      	return true;
    }  else  {
      	alert('PLEASE ENTER NUMBERS ONLY!');
		document.getElementById(inputs).value="";
		document.getElementById(inputs).focus();
      	return false;
    }
}

function isNumberDecimal(evt){
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)){
		return false;
	}
	return true;
}

function allNumbers_a(id){
    var numbers = /^[0-9]*\.?[0-9]*$/;  
      if(document.getElementById(id).value.match(numbers))  {  
      return true;  
      }  else  {  
      alert('PLEASE INPUT NUMBERS ONLY');
		document.getElementById(id).value="";
		document.getElementById(id).focus();
      return false;  
      }  
}

function all_letter(inputs){
	var input_val = document.getElementById(inputs).value;
	var letters = /^[A-Za-zñ ]+$/;
	if (input_val.match(letters)) {
		
	}else{
		alert('PLEASE ENTER LETTERS ONLY!');
		document.getElementById(inputs).value="";
		document.getElementById(inputs).focus();
	}
	return false;
}

function limitText(field,limit){
	var f = document.getElementById(field);
	if(f.value.length > limit){
		f.value = f.value.substring(0, limit);
	}
	return false;
}

function restrictEleven(field){
	var f = document.getElementById(field);
	if(f.value.length < parseFloat(11)){
		alert('Mobile number must be Eleven digits.');
		$('#'+field).focus();
	}
	return false;
}

function addField(row_id,rowcounter){
	for(var i=2;i<=30;i++){
		var control = document.getElementById(row_id+i);
		if(control!=null){	
			if(control.style.display=="none"){
				control.removeAttribute("style");
				document.getElementById(rowcounter).value = i;
				break;
			}
		}
	}
}

function removeField(row_id,row_counter){
	var rowcounter=document.getElementById(row_counter);
	if(rowcounter.value>=2 && rowcounter.value!=1){
		document.getElementById(row_id+rowcounter.value).style.display="none";
		var a = +rowcounter.value -1;
		rowcounter.value=a;
	}

}

function addOneMonth(date_fld, result_element) {
  const dateInput = document.getElementById(date_fld);
  const result = document.getElementById(result_element);

  // Get the selected date from the input
  const selectedDate = new Date(dateInput.value);

  if (isNaN(selectedDate)) {
    result.textContent = "Please select a valid date.";
    return;
  }

  // Add one month
  const updatedDate = new Date(selectedDate);
  updatedDate.setMonth(updatedDate.getMonth() + 1);

  // Handle edge cases where the new month has fewer days
  if (updatedDate.getDate() !== selectedDate.getDate()) {
    updatedDate.setDate(0); // Adjust to the last day of the previous month
  }

  // Format the date as "MMM D, YYYY"
  const options = { year: 'numeric', month: 'short', day: 'numeric' };
  const formattedDate = updatedDate.toLocaleDateString('en-US', options);

  // Display the formatted date
  result.textContent = `${formattedDate}`;
}

/* calculate age */
function ageCount() { //looped text fields of age
	for(var i=1;i<=10;i++){
		var date1 = new Date();
		var dob= document.getElementById("date"+i).value;
		var date2=new Date(dob);
		var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/; //Regex to validate date format (mm/dd/yyyy)
		if (pattern.test(dob)) {
			if(dob!=null){
				var y1 = date1.getFullYear(); //getting current year
				var y2 = date2.getFullYear(); //getting dob year
				
				var m1= date1.getMonth() + 1;
				var m2= date2.getMonth() +1;
				
				if(m2 <= m1){
					var age = y1 - y2;           //calculating age 
				}else{
					age = (y1-1) - y2;
				}
			
				document.getElementById("ben_age"+i).value=age;
			}
		} 
	}
}

function ageCount1() { //looped text fields of age
	for(var i=1;i<=10;i++){
		var date1 = new Date();
		var dob= document.getElementById("date_a"+i).value;
		var date2=new Date(dob);
		var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/; //Regex to validate date format (mm/dd/yyyy)
		if (pattern.test(dob)) {
			if(dob!=null){
				var y1 = date1.getFullYear(); //getting current year
				var y2 = date2.getFullYear(); //getting dob year
				
				var m1= date1.getMonth() + 1;
				var m2= date2.getMonth() +1;
				
				if(m2 <= m1){
					var age = y1 - y2;           //calculating age 
				}else{
					age = (y1-1) - y2;
				}
			
				document.getElementById("ben_age_a"+i).value=age;
			}
		} 
	}
}

function ageCountFN(dobfield, agefield) { //looped text fields of age
	var date1 = new Date();
	var dob= document.getElementById(dobfield).value;
	var date2=new Date(dob);
	var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/; //Regex to validate date format (mm/dd/yyyy)
	if (pattern.test(dob)) {
		if(dob!=null){
			var y1 = date1.getFullYear(); //getting current year
			var y2 = date2.getFullYear(); //getting dob year
			
			var m1= date1.getMonth() + 1;
			var m2= date2.getMonth() +1;
			
			if(m2 <= m1){
				var age = y1 - y2;           //calculating age 
			}else{
				age = (y1-1) - y2;
			}
		
			document.getElementById(agefield).value=age;
		}
	} 
}

function compAge(){ //compute single age
	var date1 = new Date();
	var dateofbirth= document.getElementById("dateofbirth").value;
	var date2=new Date(dateofbirth);
	var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/; //Regex to validate date format (mm/dd/yyyy)
	if (pattern.test(dateofbirth)) {
		if(dateofbirth!=null){
			var y1 = date1.getFullYear(); //getting current year
			var y2 = date2.getFullYear(); //getting dob year
			var m1= date1.getMonth() + 1;
			var m2= date2.getMonth() +1;
			
			if(m2 <= m1){
				var age = y1 - y2; //calculating age
			}else{
				age = (y1-1) - y2;
			}
			
			//var age1 = (m2+12*y2)-(m1+12*y1) + "months"; // in months
			/* if(age!=0) */
				document.getElementById("age").value=age;
			/* else
				document.getElementById("age").value=age1; */
			return true;
		}
	} 
}

function deActivate(url, id){
	if(confirm("Are you sure you want to Deactivate this user?")){
		window.location.href=url+id;
	}
}

function Activate(url, id){
	if(confirm("Are you sure you want to Activate this user?")){
		window.location.href=url+id;
	}
}

function addAsCollector(url, id){
	if(confirm("Are you sure you want to Add this user as Collector?")){
		window.location.href=url+id;
	}
}

function removeAsCollector(url, id){
	if(confirm("Are you sure you want to Remove this user as Collector?")){
		window.location.href=url+id;
	}
}
function confirmSubmit(){
	if(confirm('DO YOU REALLY WANT TO CONTINUE?')){
		return true;
	}else{
		return false;
	}
}
function confirmAction(url, label){
	if(confirm(label)){
		window.location.href=url;
	}else{
		return false;
	}
}
function nextpage(){
	link=document.getElementById('link').value;
	page=document.getElementById('pg').value;
	window.location.href=link +'pg='+ page +'#content_wrapper';
}

function checkIfZero(input){
	var x = input.value;
	if(x==0 || x==null){
		alert("THE FIELD MUST NOT BE ZERO, PLEASE INPUT HIGHER THAN ZERO. (e.g. 1)");
		input.value="";
		input.focus();
		return false;  
	}else{
		return true;
	}
}

function randomString() {
	/* abcdefghiklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXTZ*/
	var chars = "0123456789";
	var string_length = 3;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	//document.getElementById('rand').value=randomstring;
}

function disableSubmitButton(btn_id){
	document.getElementById(btn_id).disabled = true;
}

function getFirstLetter(str) {
	if (str != "") {
		// var str = "Java Script Object Notation";
	    var matches = str.match(/\b(\w)/g); // ['J','S','O','N']
	    var acronym = matches.join(''); // JSON
	    console.log(acronym)
	    return acronym;
	} else {
		return "";
	}
}

function generateUsername(fname_fld, lname_fld, uname_fld) {
    var fname = document.getElementById(fname_fld).value;
    var lname = document.getElementById(lname_fld).value;
    var fname_fl = getFirstLetter(fname);
    var str_u = lname;
    str_u = str_u.replace(/ +/g, "");
    var uname = fname_fl+str_u;
    document.getElementById(uname_fld).value = "owpms_" + uname.toLowerCase();
}

function genaratePassword(chkbox_id, password_fld) {
    var chkd = $('#'+chkbox_id).is(':checked');

    if (chkd) {
        let pass = '';
	    let str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' +
	        'abcdefghijklmnopqrstuvwxyz0123456789@#$';

	    for (let i = 1; i <= 8; i++) {
	        let char = Math.floor(Math.random()
	            * str.length + 1);

	        pass += str.charAt(char)
	    }

	    // return pass;
	    document.getElementById(password_fld).value=pass;
    } else {
    	document.getElementById(password_fld).value="";
    }
}

function showPass(cb_id, fld_id) {
    var chkd = $('#'+cb_id).is(':checked');
    if (chkd) {
        document.getElementById(fld_id).type="text";
    } else{
        document.getElementById(fld_id).type="password";
    }
}