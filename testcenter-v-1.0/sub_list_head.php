<?php
/**
 * Created by PhpStorm.
 * User: Mahesh
 * Date: 16/11/15
 * Time: 4:12 PM
 *
 *
 * it includes css and head part of test center
 */
?>
<head>

    <base href="">
	<title>ISEEIT</title>


	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


    <style>

        select, textarea, input[type="text"], input[type="password"], input[type="datetime"],
        input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"],
        input[type="week"], input[type="number"], input[type="email"], input[type="url"],
        input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
            display: inline-block;
            height: 25px !important;
			padding: 4px 6px;
			margin-bottom: 9px;
			font-size: 13px;
			line-height: 18px;
			color: #555;
			border-radius: 4px;
			vertical-align: middle;
		}
		.tleft{ float:left;width:49.5%;height:130px;}
		.tright{ float:right;width:49.5%;overflow-y: scroll;  height: 120px;}
		.sta{border:1px solid gray;  margin-top:10px;float:left;width:100%;height:75px;}
		.sub{
            width: 23%;
            border-right: 1px solid #808080;
			padding: 15px 10px;
			font-size: 16px;
			float: left;
			height: 45px;

		}
		.subp{margin-top:5px;font-size: 16px;padding-top: 0px;}
		.report{border: 1px solid #808080;
			margin-top: 3px;
			padding: 0px;
			float: right;
			width: 100%;}
        .con{width:100%;}

        .container-demo{
            width: 1170px;margin-right: auto;margin-left: auto;
        }


        .repo{margin-top:10px;width:100%;height:350px;float:left;padding-top:10px;}
		#panel, #flip {
			padding: 5px;
			text-align: center
		}
		p, fieldset, table, pre {
            margin-bottom: 0em !important;
		}

		meter {
            font-size: 6px;
			margin-top: 5px;
			width: 85%;
		}
		#flip{
			color: #FFF;
			text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);
			background-color: #0362A9;
			background-image: linear-gradient(to bottom, #0378A9, #0341A9);
			background-repeat: repeat-x;
			border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

			text-align: center;
			border-width: 0px 0px 1px 1px;
			font-size: 15px;
			font-family: Arial;
			font-weight: bold;
			color: #FFF;
			cursor: pointer;
		}

		#fl{
			text-align:right;padding-right:20px;margin-top:5px
		}
		.fa-angle-double-up,.fa-angle-double-down{
            font-size:18px;
		}
		#ccourse{
			float:left;margin-right:180px;margin-left:40px;
		}
		#ctopic{
			float:left;margin-right:180px;
		}
		#cactivity{
			float:left;margin-right:180px;
		}
		#panel {
			display: block;
		}

		#t01 th, td {
			padding: 5px;
			text-align: left;
			border: 1px solid black;

		}
		table#t01 {
			width: 100%;
			border: 1px solid gray;
			border-collapse: collapse;
		}
		.CSSTableGenerator {
            margin:0px;padding:0px;
			width:100%;
			border:1px solid #c4baba;

            -moz-border-radius-bottomleft:0px;
			-webkit-border-bottom-left-radius:0px;
			border-bottom-left-radius:0px;

			-moz-border-radius-bottomright:0px;
			-webkit-border-bottom-right-radius:0px;
			border-bottom-right-radius:0px;

			-moz-border-radius-topright:0px;
			-webkit-border-top-right-radius:0px;
			border-top-right-radius:0px;

			-moz-border-radius-topleft:0px;
			-webkit-border-top-left-radius:0px;
			border-top-left-radius:0px;
		}
        .CSSTableGenerator table{
            border-collapse: collapse;
			 border-spacing: 0;
			 width:100%;
			 height:100%;
			 margin:0px;padding:0px;
		 }
        .CSSTableGenerator tr:last-child td:last-child {
            -moz-border-radius-bottomright:0px;
			  -webkit-border-bottom-right-radius:0px;
			  border-bottom-right-radius:0px;
		  }
		.CSSTableGenerator table tr:first-child th:first-child {
            -moz-border-radius-topleft:0px;
			-webkit-border-top-left-radius:0px;
			border-top-left-radius:0px;
		}
		.CSSTableGenerator table tr:first-child th:last-child {
            -moz-border-radius-topright:0px;
			-webkit-border-top-right-radius:0px;
			border-top-right-radius:0px;
		}.CSSTableGenerator tr:last-child td:first-child{
            -moz-border-radius-bottomleft:0px;
			 -webkit-border-bottom-left-radius:0px;
			 border-bottom-left-radius:0px;
		 }.CSSTableGenerator tr:hover td{

        }
		.CSSTableGenerator tr:nth-child(odd){ background-color:#fcf9f7; }
		.CSSTableGenerator tr:nth-child(even)    { background-color:#ffffff; }
        .CSSTableGenerator td{
            vertical-align:middle;

              border:1px solid #c4baba;
              border-width:0px 1px 1px 0px;
              text-align:left;
              padding: 0px 8px;
              font-size:13px;
              font-family:Arial;
              font-weight:normal;
              color:#000000;
        }
        .CSSTableGenerator tr:last-child td{
                border-width:0px 1px 0px 0px;
        }.CSSTableGenerator tr td:last-child{
                border-width:0px 0px 1px 0px;
                 }.CSSTableGenerator tr:last-child td:last-child{
                border-width:0px 0px 0px 0px;
                                   }
		.CSSTableGenerator tr:first-child th{
            color: #FFF;
            text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);
			background-color: #0362A9;
			background-image: linear-gradient(to bottom, #0378A9, #0341A9);
			background-repeat: repeat-x;
			border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

			border:0px solid #c4baba;
			text-align:center;
			border-width:0px 0px 1px 1px;
			font-size:15px;
			font-family:Arial;
			font-weight:bold;
			color:#ffffff;
		}
		.CSSTableGenerator tr:first-child:hover th{
            color: #FFF;
            text-shadow: 0px -1px 0px rgba(0, 0, 0, 0.25);
			background-color: #0362A9;
			background-image: linear-gradient(to bottom, #0378A9, #0341A9);
			background-repeat: repeat-x;
			//border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);

		}
		.CSSTableGenerator tr:first-child th:first-child{
            border-width:0px 0px 1px 0px;
		}
		.CSSTableGenerator tr:first-child th:last-child{
            border-width:0px 0px 1px 1px;
		}



		#progressBar {
			width: 400px;
			height: 22px;
			border: 1px solid #111;
			background-color: #292929;
		}
		#progressBar div {
			height: 100%;
			color: #fff;
			text-align: right;
			line-height: 22px; /* same as #progressBar height if we want text middle aligned */
			width: 0;
			background-color: #0099ff;
		}
		.btn {
        background: #3498db;
        background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
        background-image: -moz-linear-gradient(top, #3498db, #2980b9);
        background-image: -ms-linear-gradient(top, #3498db, #2980b9);
        background-image: -o-linear-gradient(top, #3498db, #2980b9);
        background-image: linear-gradient(to bottom, #3498db, #2980b9);
			font-family: Arial;
			color: #ffffff;
			font-size: 15px;
			padding: 5px 18px 6px 18px;
			text-decoration: none;
		}

		.btn:hover {
        background: #3cb0fd;
        background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
        background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
        background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			text-decoration: none;
		}
		.CSSTableGenerator { overflow: auto; }
		.CSSTableGenerator tbody { height: auto; }


        tbody tr td:first-child{
            text-align: center;
        }
            tbody tr td:last-child{
        text-align: center;
            }
            tbody tr th:first-child{
        text-align: center !important;
            }
            tbody tr th:last-child{
        text-align: center !important;
            }

            .navbar {
        margin-bottom: 0px;
            }
            thead{
        cursor: pointer;
    }
            td{height: 30px;}
        #t01 img{
        padding:5px;
        }
        .sta {
            border: 1px solid #808080;
            margin-top: 0px;
            float: left;
            width: 100%;
            height: 45px;
        }
        .sub {
            width: 23%;
            border-right: 1px solid #808080;
            padding: 2px 10px;
            font-size: 16px;
            float: left;
            height: 42px;
        }
         .repo {
            margin-top: 0px;
            width: 100%;
            height: 350px;
            float: left;
            padding-top: 5px;
        }
        .report {
            border: 1px solid #808080;
            margin-top: 3px;
            padding: 0px;
            float: right;
            width: 100%;
            height: 750px;
            margin-bottom: 30px;
        }
        #fl{
        height:20px;
        }
        .showhide{
            border: none;
            background: none;
            cursor: pointer;
        }
	.pagecover{
	    display: none; position: absolute; width: 92%; background-color: rgb(255, 255, 255); z-index: 300; opacity: 0.9; height: 800px; top: 183px;margin-left: -10px;
	}
	</style>

<?php
$cid = required_param('cid', PARAM_INT);
?>
    <!--including necessary libraries for the javascript-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://kartparadigm.com/kmit/jquery.tablesorter.js"></script>


    <script>

        $j=$.noConflict();
        $j(document).on('ready',function(){

            //initializing script variables
            var setIntervalId=0;
            var cid='<?php echo $cid; ?>';
            var baseUrl='<?php echo $CFG->wwwroot; ?>';
            var hideshowurl='<?php echo $CFG->wwwroot."/course/mod.php?sesskey=".sesskey()."&sr=0&" ?>';
            $j("#myTable").tablesorter();




            //set refresh time from given dropdown list
            $j('#refresh-time').on('change', function() {

                var setInt=parseInt($j("#setinterval-id").val());
                clearInterval(setInt);
                $j("#setinterval-id").val(setInterval(updateDiv,parseInt(this.value)*1000,$j('#hcactivity-id').val()));

            });

            //selectt section from given dropdown list
            $j('#stu-section').on('change', function() {
		$j('#current-stu-section').text($j(this).val());
		$j('#hcstu-section').val($j(this).val());
		
		$j('#studentCount').val($j('#stu-section option[value="'+$j(this).val()+'"]').attr('id'));
			var studentCount = parseInt($j('#studentCount').val());
                        $j('.studentCount').text(studentCount);
                        $j('#subCountMeter').attr('max',studentCount);
                        $j('#gradeCountMeter').attr('max',studentCount);
                
		get_students_by_section(cid,$j('#hcstu-section').val());
		
		get_activity_id();
                var setInt=parseInt($j("#setinterval-id").val());
		if(setInt){
                clearInterval(setInt);}
                $j("#setinterval-id").val(setInterval(updateDivSection,6000,$j(this).val()));

            });

            //this will get the list of students with submission and grade status based on student section
            function updateDivSection(section){
//alert(section);
		var id=0;
		if(parseInt($j('#hcactivity-id').val())){
		id=parseInt($j('#hcactivity-id').val());
		}
		

		if(id!=0){
		if(parseInt($j('#hcactivity-status').val())){
		$j(".pagecover").css("display","block");
                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/sub_list.php",
                    data: {
                        "id": id,
                        "secname":section,
                    },
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        var result = $j('<div />').append(data).html();
                        $j('#sub_list').html(result);

                        var subCount = $j($j.parseHTML(data)).filter("#subCount").text();
                        var gradeCount = $j($j.parseHTML(data)).filter("#gradeCount").text();
			var activity_status = $j($j.parseHTML(data)).filter("#acivitystatus").text();



			$j('#hcactivity-status').val(activity_status);
                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                        $j("#myTable").tablesorter();$j(".pagecover").css("display","none");
			if(!parseInt($j('#hcactivity-status').val())){
                    		$j('.show'+id).show();
                    		$j('.hide'+id).hide();		
			}
                    }
                });
		}//end of activity check if
		else{
		alert("Activity Completed");
                            var setint=parseInt($j("#setinterval-id").val());
				if(parseInt(setint)){
                            clearInterval(setint);}
		}
		}//end of id  if
		else{
		get_activity_id();
		}


            }//end of updateDivSection function






            //this will get the list of students based on section
            function get_students_by_section(cid,section){
		$j(".pagecover").css("display","block");
                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/enrolledstudents.php",
                    data: {
                        "cid": cid,
                        "secname":section,

                    },
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        var result = $j('<div />').append(data).html();
                        $j('#sub_list').html(result);

                        /*var subCount = $j($j.parseHTML(data)).filter("#subCount").text();
                        var gradeCount = $j($j.parseHTML(data)).filter("#gradeCount").text();
                        var studentCount = $j($j.parseHTML(data)).filter("#studentCount").text();

                        $j('#csubCount').text(subCount);
                        $j('#cgradeCount').text(gradeCount);
                        $j('#studentCount').val(studentCount);
                        $j('#subCountMeter').val(subCount);
                        $j('#gradeCountMeter').val(gradeCount);
                        $j('#subCountMeter').attr('max',studentCount);
                        $j('#gradeCountMeter').attr('max',studentCount);*/

                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                        $j("#myTable").tablesorter();$j(".pagecover").css("display","none");
                    }
                });


            }//end of updateDiv function



            //toggle function

            $j('.hide').hide(); $j('.complete').hide(); //hide stop and complete options on load

            $j(document).delegate(".showhide","click",function(){
		//$j(".pagecover").css("display","block");
                var clickvalue = 'mod'+$j(this).attr('value');
                var modid=$j(this).attr('value');

                //changing and storing current activity id and name based on selection
                $j("#hcactivity").val($j(".activity"+clickvalue).text());
                $j("#hcactivity-id").val(modid);
                $j("#current-activity").text($j(".activity"+clickvalue).text());
                $j('#cactivity').text('Activity: '+$j('#hcactivity').val());
                var id = $j(this).attr('id');
                var value = $j(this).attr('value');
                var hideshowajax=hideshowurl+id+'='+value;

                //this will perform storing of current activity id and time
                if($j(this).attr('id')=='show'){
                    $j('.show'+modid).hide();
                    $j('.hide'+modid).show();$j('#hcactivity-status').val(1);
                    record_activity_date(modid);
			
                }
                if($j(this).attr('id')=='hide'){
			
                    $j('.hide'+modid).hide();$j('.complete'+modid).show();$j('.show'+modid).show();
		record_activity_date(modid);
                }

                //ajax call to show or hide the activity to the student
                $j.ajax({
                    url: hideshowajax,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {

                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {

                        if(id=='show'){

                            //this is set interval method which call the update method for every 60 seconds means 60000 milli seconds
                           // $j("#setinterval-id").val(setInterval(updateDiv,6000,modid));
	$j("#setinterval-id").val(setInterval(updateDivSection,6000,$j('#hcstu-section').val()));

                        }
                        if(id=='hide'){
                            var setint=parseInt($j("#setinterval-id").val());
                            clearInterval(setint);
                        }
                    }
                });//hide or show ajax call end
            });//showhide click function end





            //this will perform storing of current activity id and time in a table
            function record_activity_date(modid){
                var startbtnid='show'+modid;
                var stopbtnid='hide'+modid;
                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/testcenterutil.php",
                    type: "GET",
                    data: {
                        "aid": modid,
                        "mid":2,
                    },
                    dataType: "html",
                    success: function (data) {

                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                        //$j('.'+startbtnid).hide();
                        //$j('.'+stopbtnid).show();
                    }
                });//ajax call end
            }//end of the record_activity_date() function

		get_student_sections(cid);
            //this will get the student sections
            function get_student_sections(cid){
                
                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/testcenterutil.php",
                    type: "GET",
                    data: {
                        "cid": cid,
                        "mid":5,
                    },
                    dataType: "json",
                    success: function (data) {
			//alert(JSON.stringify(data));
			$j.each(data, function (linktext, link) {
			$j('#stu-section').append('<option id="'+link.seccount+'" value="'+ link.secname +'">'+ link.secname +'</option>');

      
    });
                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                        
                    }
                });//ajax call end
            }//end of the get_student_sections(cid) function



            //this will perform retriving of current activity id from activity status table
            function get_activity_id(){
                
                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/testcenterutil.php",
                    type: "GET",
                    data: {
                        "mid":4,
                    },
                    dataType: "text",
                    success: function (data) {
			//alert(data.length);
			if(data.length!=1){
			var activities_array=data.split(',');
			var i=0;
			for(i=0;i<activities_array.length;i++){
			if($j('.show'+activities_array[i]).length){
			//alert(activities_array[i]);
			$j('#hcactivity-status').val(1);
			$j('#hcactivity').val($j('.activitymod'+activities_array[i]).text());
			$j('#hcactivity-id').val(activities_array[i]);
			$j('.show'+activities_array[i]).hide();
                        $j('.hide'+activities_array[i]).show();
			}
			}

			}
                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                       // $j('.show'+startbtnid).hide();
                        //$j('.hide'+stopbtnid).show();
                    }
                });//ajax call end
            }//end of the get_activity_id() function



            //ajax call to mark activity as complete and no more changes after completion of activity
            //on complete it will remove all the data stored in temp table today

            $j('.markascomplete').css('background-color','grey');
            $j(document).delegate(".complete","click",function(){

                var modid=$j(this).attr('value');


                $j.ajax({
                    url: baseUrl+"/teacher/testcenter/testcenterutil.php",
                    type: "GET",
                    data: {
                        "aid": modid,
                        "mid":3,
                    },
                    dataType: "html",
                    success: function (data) {
                        //var result = $j('<div />').append(data).html();
                        /* $j('#sub_list').html(result);*/
                        //alert(data);

                    },
                    error: function (xhr, status) {
                        alert("Sorry, there was a problem!");
                    },
                    complete: function (xhr, status) {
                        $j('.row'+modid).css('background-color','grey');
                        $j('.show'+modid).attr('disabled','true');
                        $j('.complete'+modid).attr('disabled','true');
                    }
                });//ajax call end
            });//end of complete as mark action function


            //toggle control panel visibility
            $j("#titile-status").hide();
            $j("#flip").click(function(){
                toggle_visibility('panel');
            });
            function toggle_visibility(id)
            {
                var e = document.getElementById(id);
                if (e.style.display == 'block' || e.style.display=='')
                {
                    $j("#titile-status").show();
                    e.style.display = 'none';
                }
                else
                {
                    $j("#titile-status").hide();

                    e.style.display = 'block';
                }
            }

            $j('#ctopic').text('Topic: '+$j("#hctopic").val());
            $j('#ccourse').text('Course: '+$j('#hccourse').val());
            $j('#cactivity').text('Activity: '+$j('#hcactivity').val());

        });//end of ready function
    </script>


</head>
