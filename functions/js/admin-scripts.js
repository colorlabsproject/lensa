/*
 * CoLabs admin jQuery functions
 *
 * Copyright (c) 2011 ColorLabs (http://colorlabsproject.com)
 *
 * Built for use with the jQuery library
 * http://jquery.com
 *
 * Version 1.0
 *
 */

// <![CDATA[

/* initialize the tooltip feature */
jQuery(document).ready(
    function(){
        jQuery("a").easyTooltip();
    }
);


/* initialize the form validation */
jQuery(document).ready(
    function(){
        jQuery("#mainform").validate({errorClass: "invalid"});
    }
);


/* show/hide rows based on selected values */
var oldD="";
var d="";
function show(o){
    if(oldD!="") oldD.style.display='none';
    if(o.selectedIndex>0){
        d=document.getElementById(o[o.selectedIndex].value);
        d.style.display='table-row';
        oldD=d;
    }
}

/* dashboard loader script */
jQuery(function () {
    jQuery('.insider').hide(); //hide all the content boxes on the page
});

var i = 0; //initialize
var int = 0; //IE fix
jQuery(window).bind("load", function() { //The load event will only fire if the entire page or document is fully loaded
    var int = setInterval("doThis(i)",500); //500 is the fade in speed in milliseconds
});

function doThis() {
    var item = jQuery('.insider').length; //count the number of elements on the page
    if (i >= item) { // Loop through the elements
        clearInterval(int); //When it reaches the last element the loop ends
    }
    jQuery('.insider:hidden').eq(0).fadeIn(500); //fades in the hidden elements one by one
    i++; //add 1 to the count
}
/* end dashboard loader script */

// ]]>
