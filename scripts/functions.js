/*
sources listed as comments
*/
function updateTotal() {
	var tot = 0.00;
	var max_tot = 0.00;
	$('tr.grade_line').each(function(){
		tot = 0.00;
		max_tot = 0.00;
		$(this).children('td.grade_num').each(function(){
			var v = parseFloat($(this).children().val());
			if (!isNaN(v)) tot += v;
		});
		$(this).children('td.max_grade_num').each(function(){
			var v = parseFloat($(this).text());
			if (!isNaN(v)) max_tot += v;
		});
		if (max_tot != 0) {
			tot = tot/max_tot * 100;
			tot = tot.toFixed(2);
			$(this).children().children('#total').val(tot);
		}
	});	

}
//for get_grades.php
$(document).ready(function(){
	
	$('input.grades_num').change(function (e) {
		var $this = $(this); //cache the form element for use in this function
		e.preventDefault(); //prevent the default submission of the form

		//run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
		$.post('add_grades.php', $this.serialize(), function (responseData) {
			//in here you can analyze the output from your server-side script (responseData)
			var message = responseData;              //get message
			$('#error').html(message); //Set output element html
			
		});
	}); 

	$('input.grades_num').blur(function(){	
		updateTotal();
	});


//from somewhere on stackoverflow.com
//bind an event handler to the submit event for your form
	$('#addForm').submit(function (e) {
		var $this = $(this); //cache the form element for use in this function
		e.preventDefault(); //prevent the default submission of the form

		//run an AJAX post request to your server-side script, $this.serialize() is the data from your form being added to the request
		$.post($this.attr('action'), $this.serialize(), function (responseData) {
			//in here you can analyze the output from your server-side script (responseData) 
			var message = responseData;              //get message
			$('#error').html(message); //Set output element html
		});
	});
	
});
//gets information from website parameter and displays it in the 
//docID field on the html page
function ajaxData(docID, website){
		if(window.XMLHttpRequest){
			xmlhttp = new XMLHttpRequest();
		}else{
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		xmlhttp.onreadystatechange = function() {
			if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
			//document.getElementById(""+ docID).innerHTML = xmlhttp.responseText;
			$("#"+docID).html(xmlhttp.responseText);
			}
		};
		document.getElementById(""+docID).innerHTML = "";

		xmlhttp.open('GET', ""+website, true);
	
		xmlhttp.send();
}

//specific code for creating the listview
function getAjax(pageDOM, website, newDOM){
	//http://the-jquerymobile-tutorial.org/jquery-mobile-tutorial-CH11.php
		$.ajax (
		{ 
  			url : ""+website, 
  			complete : function (xhr, result)
  		{
   			if (result != "success") return;
   	 		var response = xhr.responseText;
   	 		$("#"+pageDOM+" div:jqmData(role=content)").append(response);
   	 		$("#"+newDOM).listview();
 		}
	}); 
}


//function gets the variables from the url
//http://snipplr.com/view/19838/get-url-parameters/
function getUrlVars() {
	var map = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		map[key] = value;
	});
	return map;
}