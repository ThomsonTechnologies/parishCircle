
function addEvent(object, evName, fnName, cap) {
   if (object.attachEvent)
       object.attachEvent("on" + evName, fnName);
   else if (object.addEventListener)
       object.addEventListener(evName, fnName, cap);
}

addEvent(window, "load", initPage, false);

function initPage() {

  $(function(){
      $('#post_in_box').slimScroll({
          height: '940px'
      });
  });

  $(document).ready(function () {
	    $('img#photo').imgAreaSelect({
	        aspectRatio: '1:1',
	        onSelectEnd: getSizes
	    });
	});


  $(function() {
      $('div').click(function() {
  		displayBlock($(this).attr('id'));
  		});
  	});




}

function displayBlock(divId) {

    if(divId == "homeparish"){
      document.getElementById("parish_contbox").style.display = "block";
      document.getElementById("profhome_contbox").style.display = "none";
      document.getElementById("diocese_contbox").style.display = "none";
    }else if(divId == "profhome"){
      document.getElementById("profhome_contbox").style.display = "block";
      document.getElementById("parish_contbox").style.display = "none";
      document.getElementById("diocese_contbox").style.display = "none";
    }else if(divId == "homediocese"){
      document.getElementById("profhome_contbox").style.display = "none";
      document.getElementById("parish_contbox").style.display = "none";
      document.getElementById("diocese_contbox").style.display = "block";
    }




    var theIdNum = parseInt($('#' + divId).attr('id').replace(/[^\d]/g, ''), 10);

    document.getElementById("commentbox" + theIdNum).style.display = "block";

}//end displayBlock()



function getSizes(im,obj)
	{
		var x_axis = obj.x1;
		var x2_axis = obj.x2;
		var y_axis = obj.y1;
		var y2_axis = obj.y2;
		var thumb_width = obj.width;
		var thumb_height = obj.height;
		if(thumb_width > 0)	{
				if(confirm("Do you want to save image..!"))	{
						$.ajax({
							type:"GET",
							url:"ajax_image.php?t=ajax&img="+$("#image_name").val()+"&w="+thumb_width+"&h="+thumb_height+"&x1="+x_axis+"&y1="+y_axis,
							cache:false,
							success:function(rsponse)	{
								 $("#cropimage").hide();
								 $("#thumbs").html("");
								 $("#thumbs").html("<img src='upload_sm/"+rsponse+"' />");
							}
						});
					}
			}
		else
			alert("Please select portion..!");
	}
