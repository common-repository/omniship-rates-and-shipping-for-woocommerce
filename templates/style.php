<?php
echo '<style>

  .form.disable .next {
    display: none !important;
  }
  
  .heading {
    font-weight: bold;
    font-size: 13px;
  }
  
  .d-none {
    display: none !important;
  }
  
  .edit {
    float: right;
    cursor: pointer;
  }
  
  .disable .fields {
    pointer-events: none;
    opacity: 0.7;
  }
 
 .hidden {
 	display: none !important;
 }
 
 
.omniShipfields{
  width:300px;
	
}

.omnishipText {
    width: 120px;
    padding: 4px 7px;
    margin: 5px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }
  
 .omnishipLabel, .omnishipLabelPost  {
	width: 50px;
    margin-top: 3px;  
    text-align: left;
    clear: both;
    float:left;
    margin-right:15px;
    font-size: 12px;
    font-weight: bold;
}
 
   
.omnishipTextPost {
    width: 250px;
    margin-top: 2px; 
    clear: both;
    float:left;
  }
  
 #labelURL {
  font-size: 8px;
  width: 100%
 }
  
 .omnishipLabelPost {
	width: 50px;
    margin-top: 14px;  
    text-align: left;
    clear: both;
    float:left;
    margin-right:15px;
    font-size: 12px;
    font-weight: bold;

    }
  
  
#labelImg {
	width: 57px;
    display: inline-block;
}

 select{
 	width: 75%;
    padding: 4px 7px;
    margin: 5px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    
 }
 
  .shippingSubmit {
    padding: 7px 6px;
    font-size: 13px;
    background: #2f363f;
    width: 120px !important;
    color: #dd6137;
  }
  
  input[type=submit]:hover {
    background-color: #45a049;
  }
  /* The container */
  .container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 20px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
  }
  

  .container input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
  }
  
  /* Create a custom radio button */
  .checkmark {
      position: absolute;
      top: 0;
      left: 0;
      background-color: #eee;
      border-radius: 50%;
  }
  
  /* On mouse-over, add a grey background color */
  .container:hover input~.checkmark {
      background-color: #ccc;
  }
  
  /* When the radio button is checked, add a blue background */
  .container input:checked~.checkmark {
      background-color: #2196F3;
  }
  
  /* Create the indicator (the dot/circle - hidden when not checked) */
  .checkmark:after {
      content: "";
      position: absolute;
      display: none;
  }
  
  /* Show the indicator (dot/circle) when checked */
  .container input:checked~.checkmark:after {
      display: block;
  }
  
  /* Style the indicator (dot/circle) */
  .container .checkmark:after {
      top: 9px;
      left: 9px;
      border-radius: 50%;
      background: white;
  }
  
  #requestForm {
      margin: auto;
      margin-top:-30px;
  }
  
  .logo {
      width: 300px;
  }
  
  .addressP {
      font-size: 12px;
      margin: 5px !important;
  }
  
  .selectOrigin {
      width: 70%;
      margin-top: 30px;
      margin-left: -5px;
  }

  .dimensionDiv {
    float: right;
    width: 21%;
    top: 54px;
    right: 367px;
    position: absolute;
  }

  #dimension {
    width:290px;
  }

  #getLabelButton {
    margin-top:20px;
    margin-left:20px;
  }
  
 
  .packageDiv {
    margin-left: -11px;
  }

  .subDiv {
    width: 156px;
    margin-right: 48px !important;
    float: right;
    margin-top: 107px;
  }


  .inputdims {
    top: -10px;
    position: relative;
    height: 25px;
    width: 40% !important;
  }

  #labelImage {
    top: -97px;
    position: relative;
    right: 240px;
    float: right;
    height: 220px;
  }

  .reportForm {
    display: inline;
  }

  .reportDetail {
      font-size:15px;
      display: inline-block;
  }

  .reportLabel {
    font-size:15px;
    font-weight: bold;
    display: inline-block;
    width: 105px;
}

.reportLine {
    font-size:15px;
    padding: 1px;
}

.errorForm {
    font-weight: bold;
    color: red;
}

#PrintLabelButton {
    left: 180px;
    top: 30px;
    position: relative;
}
#TrackLabelButton {
    left: 81px;
    top: 30px;
    position: relative;
}
</style>';
?>