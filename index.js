/*
  ISAK PHYSICS LAB APP JAVASCRIPT

  Last Updated:   08/23/2019
  Recent Author:  GABRIEL PONON

  Disclaimer:
  Code is largely based on open source code provided in w3schools.com. Author complies willingly
  to copyright law and guarantees that this software will only be used for personal non-commercial
  use by UWC ISAK Japan in the absence of any kind of monetary transaction by any party.

  Credit is given on the app web page
*/

var _scannerIsRunning = false;
var screenHeight = screen.height;
var screenWidth = screen.width;
var ct = 0;

var item = {
  name : "Item Name",
  code : 0000,
  date : "YYYY-MM-DD HH:MM:SS"
};

var items = [ item ];

var currentItemCode , currentItemName;
var currentScanCode;
var currentEmail="";

// ui scripts


$(document).ready( function () {
  // hide UI elements
  $("#returnFailureAlert").hide();
  $("#returnSuccessAlert").hide();
  $("#emptyCodeAlert").hide();
  $("#NoSuchCodeAlert").hide();
  $("#WrongInputAlert").hide();
});

// modal scripts

/*
This function overwrites the html of the display element for the items the user currently has in the
<items> array
*/

function updateYourItemsModal() {
  var str = "";
  for (var i = 0; i < items.length; i++) {
    str += "<tr><th scope='row'>";
    str += items[i].name;
    str += "</th><td>";
    str += items[i].date;
    str += "</td><td><button type='button' class='btn btn-outline-primary' id='";
    str += i;
    str += "' onclick='returnItem(";
    str += items[i].code;
    str += ")'>Return</button></td></tr>";
  }
  document.getElementById("yourItemsTable").innerHTML = str;
}

/*
This function opens a dialog window telling the user to confirm borrowing the item after its code
has been input
*/

function openConfirmItemDialog( code ) {
var barCode = -1;
if ( code < 0 ) {
  code = $("#codeInput").val();
  if ( checkCode( code ) ) {
    barCode = code;
  }
  else {
    $("#emptyCodeAlert").hide();
    $("#NoSuchCodeAlert").hide();
    $("#WrongInputAlert").show();
  }
}
else {
  barCode = code;
}

if( barCode > 0 ) {
  getItemName( barCode );
}
}

/*
This function verifies the scanned/typed code using the following scheme:

code length must be 7
<start mark character> + <actual item code in inventory sheet> + <ending mark character>

start mark character: 3
end mark character: 5

item code is extracted from the string and checked if it is divisible by the security number (currently 13)
*/

function checkCode( code ) {
// code scheme check
if ( code == null || code == "" )
  return false;
var str = code + "";
if ( str.length != 7 ) {
  return false;
}
var no = parseInt(str.substring(1,6));
if ( no % 13 != 0 || parseInt(str[0]) != 3 || parseInt(str[6]) != 5 ) {
  return false;
}
return true;
}

// database accessing code

/*
This function gets item name from database given the input parameter <inputCode>, the actual item code
On success, it runs the 'getnames.php' script that opens the database once, retrieves the name, then closes it again.
On failure, script is not run, and error message modals are displayed
*/

function getItemName( inputCode ) {

if (inputCode == "") { // if null, displays error message
    $("#emptyCodeAlert").show();
    $("#NoSuchCodeAlert").hide();
    $("#WrongInputAlert").hide();
    return;
} else {
    if (window.XMLHttpRequest) {  // server-side code for accessing php scripts
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var itemName = this.responseText;
          if ( itemName == "error_no_such_item" ) {
            $("#emptyCodeAlert").hide();
            $("#NoSuchCodeAlert").show();
            $("#WrongInputAlert").hide();
          }
          else {
            $( "#pendingItem" ).html( itemName );
            $( "#confirmModal" ).modal();
            $("#emptyCodeAlert").hide();
            $("#NoSuchCodeAlert").hide();
            $("#WrongInputAlert").hide();

            currentItemCode = inputCode;
            currentItemName = itemName;
          }
            // $("#titleBar").html( this.responseText ); // DEBUG CODE
        }
    };
    xmlhttp.open("GET","scr/getnames.php?q="+inputCode,true); // input passed as header url
    xmlhttp.send();
}
}

/*
This code adds the item currently held in the global <currentItemCode> and <currentItemName> variables directly
into the item ledger and registers the borrow transaction using the <userEmail> session variable with date
and time
*/

function addItem() {

if (currentItemCode == null || currentItemName == null ) {
    alert( "There is no item pending for addition" );
    return;
} else {
    if (window.XMLHttpRequest) { // server-side code for accessing php scripts
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          var sqlReply = this.responseText;
          if ( sqlReply == "error_cannot_write_to_database" ) {
            alert( "Cannot write to database at this time. Please try again later." );
          }
          else if ( sqlReply == "success_write_database" ) {
            $("#borrowSuccessModal").modal();
            currentItemCode = null;
            currentItemName = null;
          }
          else {
            // $("#errorLogBox").html( sqlReply ); // DEBUG CODE
          }
            // $("#titleBar").html( this.responseText ); // DEBUG CODE
        }
    };

    // get current machine date
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
    var dateTime = date+' '+time;

    /*
    form usable header url passing <userEmail> session variable previously saves as <currentEmail>,
    <currentItemName> the name of borrowing item,
    <currentItemCode> the actual code of borrowing item,
    <dateTime> the machine date retrieved beforehand
    */
    var url = "scr/writetoledger.php";
    var params = "email="+currentEmail+
                    "&itemName="+currentItemName+
                    "&code="+currentItemCode+
                    "&date="+dateTime;
    xmlhttp.open("POST", url ,true); // run php script with url as header
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send( params );
}
}

$("#updateYourItemsModalButton").click( function () {
  getUserItems( updateYourItemsModal );
});
/*
This function retrieves an regular array of items the user had already borrowed using a simple one-time read
from the database to be ready to be displayed through the 'updateYourItemsModal' function above
*/

function getUserItems( callback ) {
  if (window.XMLHttpRequest) { // server-side code to access php script
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) { // if borrow data line retrieved, parse and add
        var str = this.responseText;
        var itemRows = str.split("%");
        items = [];
        for (var i = 0; i < itemRows.length-1; i++) {
          var res = itemRows[i].split(";");
          items.push( {
            name : res[0],
            code : parseInt( res[1] ),
            date : res[2]
           } );
        }
        callback();
      }
  };
  var url = "scr/getitems.php";
  xmlhttp.open("GET", url, true); // run php using url as header
  xmlhttp.send();
}

/*
This function removes the item with input <itemCode> borrow transaction registered with <userEmail>
from the item ledger
*/

function returnItem( itemCode ) {
var retPrompt = prompt("Return item in box and type the item code:");
if( retPrompt == itemCode ) {
  if (window.XMLHttpRequest) {
      // code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp = new XMLHttpRequest();
  } else {
      // code for IE6, IE5
      xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var sqlReply = this.responseText;
        if ( sqlReply == "error_deleting_record" ) {
          alert( "Cannot erase from database at this time. Please try again later." );
        }
        else if ( sqlReply == "success_delete_record_ledger" ) {
          $("#returnSuccessAlert").show();
          $("#returnFailureAlert").hide();
          getUserItems( updateYourItemsModal );
        }
        else {
          // $("#errorLogBox").html( sqlReply ); // DEBUG CODE
        }
      }
  };
  var url = "scr/removefromledger.php";
  var params = "?email="+currentEmail+"&code="+itemCode;
  xmlhttp.open("POST",url,true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send( params );
}
else if ( retPrompt != itemCode && retPrompt != null ) {
  $("#returnFailureAlert").show();
  $("#returnSuccessAlert").hide();
  getUserItems( updateYourItemsModal );
}

}

// photo-taker code

function openCameraModal() {
  $("#scanner-container").show();
}

// scanner code - modified

$(function() {
	// Create the QuaggaJS config object for the live stream
	var liveStreamConfig = {
			inputStream: {
				type : "LiveStream",
        target : document.querySelector("#barcode-scanner"),
				constraints: {
					width: {min: 480 },
					height: {min: 360 },
					aspectRatio: {min: 1, max: 100},
					facingMode: "environment" // or "user" for the front camera
				}
			},
			locator: {
				patchSize: "medium",
				halfSample: true
			},
			numOfWorkers: (navigator.hardwareConcurrency ? navigator.hardwareConcurrency : 4),
			decoder: {
				"readers":[
					{"format":"code_128_reader","config":{}}
				]
			},
			locate: true
		};
	// The fallback to the file API requires a different inputStream option.
	// The rest is the same
	var fileConfig = $.extend(
			{},
			liveStreamConfig,
			{
				inputStream: {
					size: 800
				}
			}
		);
	// Start the live stream scanner when the modal opens
	$('#cameraModal').on('shown.bs.modal', function (e) {
    startScanner();
    });

    function startScanner() {
      Quagga.init(
  			liveStreamConfig,
  			function(err) {
  				if (err) {
  					alert( err.name + ' ' + err.message );
  					Quagga.stop();
  					return;
  				}
  				Quagga.start();
  			}
  		);
    }

	// Make sure, QuaggaJS draws frames an lines around possible
	// barcodes on the live stream
	Quagga.onProcessed(function(result) {
		var drawingCtx = Quagga.canvas.ctx.overlay,
			drawingCanvas = Quagga.canvas.dom.overlay;

		if (result) {
			if (result.boxes) {
				drawingCtx.clearRect(0, 0, parseInt(drawingCanvas.getAttribute("width")), parseInt(drawingCanvas.getAttribute("height")));
				result.boxes.filter(function (box) {
					return box !== result.box;
				}).forEach(function (box) {
					Quagga.ImageDebug.drawPath(box, {x: 0, y: 1}, drawingCtx, {color: "green", lineWidth: 2});
				});
			}

			if (result.box) {
				Quagga.ImageDebug.drawPath(result.box, {x: 0, y: 1}, drawingCtx, {color: "#00F", lineWidth: 2});
			}

			if (result.codeResult && result.codeResult.code) {
				Quagga.ImageDebug.drawPath(result.line, {x: 'x', y: 'y'}, drawingCtx, {color: 'red', lineWidth: 3});
			}
		}
	});

	// Once a barcode had been read successfully, stop quagga and
	// close the modal after a second to let the user notice where
	// the barcode had actually been found.
	Quagga.onDetected(function(result) {
		if (result.codeResult.code){
      var codeResult = result.codeResult.code;
      if ( checkCode(codeResult) ) {
        $("#cameraModal").modal("hide");
        openConfirmItemDialog( codeResult );
        Quagga.stop();
      }
		}
    else {
      $("#code-overlay").show();
      $("#after-scan").hide();
    }
	});

	// Stop quagga in any case, when the modal is closed
    $('#cameraModal').on('hide.bs.modal', function(){
    	if (Quagga){
    		Quagga.stop();
    	}
      $("#after-scan").hide();
    });

});

$('#yourItemsModal').on('hide.bs.modal', function(){
  $("#returnSuccessAlert").hide();
  $("#returnFailureAlert").hide();
});

// TODO : CLEAN UP CODE, PUT ALL THE RAW WARNINGS AND ALERT MESSAGES IN A CENTRALIZED XML FILE
// TODO : OPTIMIZE, CALL BACK ON EACH DATABASE ACTION FOR THE UI ACTION
// TODO : FIX, ISSUE OF MISALIGNED BARS AND WINDOW RESIZE
// TODO : FEATURE, CAMERA SELECTOR
