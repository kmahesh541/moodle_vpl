<?php
require_once dirname(__FILE__).'/../../../config.php';
require_once $CFG->dirroot.'/mod/vpl/locallib.php';
require_once("$CFG->dirroot/enrol/locallib.php");
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');

//login required to view this page
require_login();


//retriving course id from url
$id = required_param('id', PARAM_INT);
//$secid = required_param('secid', PARAM_INT);
$secid=2;
$secname='';
// Get all course sections in a array
function get_sections($sections)
{   $cnt=0;
	$sec_array = array();
	foreach ($sections as $sec) {
		$sec_array[$cnt++] = array('secid'=>$sec->id,'secname'=>$sec->name);
		if($secid==$sec->id){
		$secname=$sec->name;}
	}
	return $sec_array;
}
print_r($secname);
//returns the all activities associated to perticular section in a course
function get_activities($sectionid,$arr)
{
	$cnt=0;
	$sec_activity_array = array();
	for($i=0;$i<count($arr);$i++) {
		if($arr[$i]['secid']==$sectionid){
			$sec_activity_array[$cnt] = array('modid'=>$arr[$i]['modid'],'modname'=>$arr[$i]['modname'],'modcontent'=>$arr[$i]['modcontent']);
			$cnt++;
		}

	}
	return $sec_activity_array;
}

$course=get_course($id);
//print_r($course);
//initializing all variables using vpl object
//$vpl = new mod_vpl($id);
//$course = $vpl->get_course();
//$cm = $vpl->get_course_module();
//$context_module = $vpl->get_context();
//$currentgroup = groups_get_activity_group($cm, true);

$modinfo = get_fast_modinfo($course);
$mods = $modinfo->get_cms();
$sections = $modinfo->get_section_info_all();
$sec_array = get_sections($sections);
$cm=$modinfo->get_cm($course->id);
$currentgroup = groups_get_activity_group($cm, true);
$context_module=context_course::instance($course->id);

$cid=$course->id;
//var_dump($sec_array);
//preparing an array which contains sections and activities
foreach ($mods as $mod) {
	$arr[$cnt++]=array('secid'=>$mod->section,'modid'=>$mod->id,'modname'=>$mod->name,'modcontent'=>$mod->content);
	//print_r($mod->name);
}
$activities=get_activities($secid,$arr);





//var_dump($currentgroup);
if(!$currentgroup){
	$currentgroup='';
}

$groupmembers = "";
$groupselect  = "";
$params = array();


$timetoshowusers = 300; //Seconds default
if (isset($CFG->block_online_users_timetosee)) {
	$timetoshowusers = $CFG->block_online_users_timetosee * 60;
}
$now = time();
$timefrom = 100 * floor(($now - $timetoshowusers) / 100); // Round to nearest 100 seconds for better query cache
$params['now'] = $now;
$params['timefrom'] = $timefrom;

list($esqljoin, $eparams) = get_enrolled_sql($context_module);
$params = array_merge($params, $eparams);
$params['courseid'] = $cid;
//var_dump($params);
$userfields = user_picture::fields('u', array('username'));
$csql = "SELECT COUNT(u.id)
                      FROM {user} u $groupmembers
                     WHERE u.lastaccess > :timefrom
                           AND u.lastaccess <= :now
                           AND u.deleted = 0
                           $groupselect";

$usercount = $DB->count_records_sql($csql, $params);
//var_dump($usercount);
$manager = new course_enrolment_manager($PAGE, $course, $filter='', $role='', $search='', $fgroup='', $status='');
$totalenrolled= $manager->get_total_users();


echo '<div class="container container-demo" style="width: 1170px;margin-right: auto;margin-left: auto;">

	<div class="report">';

echo '<div id="flip" >
<p id="fl" style="text-align:right;padding-right:20px;margin-top:5px" ><i class="fa fa-angle-double-up" style="font-size:18px;"></i>
<span id="titile-status">
<span style="float:left;margin-right:180px;margin-left:40px;" id="ccourse">Course : '.$course->fullname.' </span><span id="ctopic" style="float:left;margin-right:180px;"> Topic : Inheritance</span>     <span style="float:left;margin-right:180px;" id="cactivity">Activity : Quiz</span> <i style="float:right" class="fa fa-angle-double-down" style="font-size:18px;"></i>

</span>
</p>
</div>';
echo '<div id="panel"><div class="tleft" ><form style="margin: 0px;padding-top: 0px;">
				<table style="width:100%">
					<tr>
						<td>
							Course : </td><td><b>'.$course->fullname.'</b></td>
					</tr>
					<tr>
						<td>Chapter : </td><td>';

echo '<span>'.$secname.'</span>';


echo '</td>
					</tr>
					<tr>
						<td colspan="2"
							style="padding-left: 150px;padding-top: 2px !important;
							padding-right: 2px !important;
							padding-bottom: 2px !important;">
					
						</td>
</tr>
				</table>

			</form>
			</div>
			<div class="tright" style="overflow-y: scroll;  height: 120px;">
			<table id="t01" class="CSSTableGenerator topics-div">
				';
			for($i=0;$i<count($activities);$i++) {
			echo '<tr >
					<td ><span class="mod' . $activities[$i]['modid'] . '">' . ($i + 1) . '</span></td>
					<td ><span class="mod' . $activities[$i]['modid'] . '">' . $activities[$i]['modname'] . '</span></td>
					<td ><span class="mod' . $activities[$i]['modid'] . '">' . $activities[$i]['modcontent'] . '</span></td>
					<td >
					<button class="showhide" id="show" value=' . $activities[$i]['modid'] . '>
					<img  alt="start" src="http://kartparadigm.com/kmit/start.png" width="16px"/></button>
					<button class="showhide" id="hide" value=' . $activities[$i]['modid'] . '>
					<img  alt="stop" src="http://kartparadigm.com/kmit/stop.png" width="16px"/></button>
					</td>
				</tr>';
			}
			echo'</table>

			</div></div>';


echo '<div class="sta">
<div class="sub">
			<p class="subp" style="font-size: 20px;padding-top: 0px;"><b>Quiz In Progress</b></p>
		</div>
<div class="sub">
			<p class="subp"><b>Logged : '.$usercount.' of '.$totalenrolled.'</b></p>
			<meter max="'.$totalenrolled.'" min="0" value="'.$usercount.'">'.$usercount.' out of '.$totalenrolled.'</meter><br>
		</div>';

echo '<div class="sub subm">
			<p class="submp subp"><b>Submitted : <span id="subCount1"></span> of '.$totalenrolled.'</b></p>
			<meter id="subCountMeter" max="'.$totalenrolled.'" min="0" value="250">
			<span id="subCount1"></span> out of '.$totalenrolled.'</meter><br>
		</div>';
echo '<div class="sub subg">
			<p class="subgp subp"><b>Graded : <span id="gradeCount1"></span> of '.$totalenrolled.'</b></p>
			<meter id="gradeCountMeter" max="'.$totalenrolled.'" min="0" value="250">
			<span id="gradeCount1"></span> out of '.$totalenrolled.'</meter><br>
		</div></div>';


/*function display(){
$i=0;
echo $i++;
require_once $CFG->dirroot.'/mod/vpl/views/sub_list.php';
}*/
echo '<div id="sub_list"></div>';
echo '</div></div>';

echo '<input type="hidden" id="current-lab" value="0" />';
echo '<input type="hidden" id="hctopic" value="'.$secname.'" />';
echo '<input type="hidden" id="hccourse" value="'.$course->fullname.'" />';
echo '<input type="hidden" id="hcactivity" value="0" />';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="http://kartparadigm.com/kmit/jquery.tablesorter.js"></script>


<script>
	$j=$.noConflict();
	$j(document).on('ready',function(){
//this is set interval method which call the update method for every 30 seconds means 3000 milli seconds
//setInterval(updateDiv,3000);        
//Upadate method
		var cid='<?php echo $cid; ?>';
		var baseUrl='<?php echo $CFG->wwwroot; ?>';
		var hideshowurl='<?php echo $CFG->wwwroot."/course/mod.php?sesskey=".sesskey()."&sr=0&" ?>';
		updateDiv();
		function updateDiv(){

			$j.ajax({
				url: baseUrl+"/mod/vpl/views/sub_list.php",
				data: {
					"id": 2,
					"group":-1,
					"sort":'dategraded',
					"sortdir":'up'
				},
				type: "GET",
				dataType: "html",
				success: function (data) {
					var result = $j('<div />').append(data).html();
					$j('#sub_list').html(result);

					var subCount = $j($j.parseHTML(data)).filter("#subCount").text(); //$j('#sub_list').filter('#subCount').text();
					var gradeCount = $j($j.parseHTML(data)).filter("#gradeCount").text();
//alert(subCount);
					$j('#subCount1').text(subCount);
					$j('#gradeCount1').text(gradeCount);
					$j('#subCountMeter').val(subCount);
					$j('#gradeCountMeter').val(gradeCount);
				},
				error: function (xhr, status) {
					alert("Sorry, there was a problem!");
				},
				complete: function (xhr, status) {
					$j("#myTable").tablesorter();
				}
			});


		}//update div
//toggle function

		$j(document).delegate(".showhide","click",function(){
			var clickvalue = 'mod'+$j(this).attr('value');
			var id = $j(this).attr('id');
			var value = $j(this).attr('value');
			var hideshowajax=hideshowurl+id+'='+value;

			$j.ajax({
				url: hideshowajax,
				type: "GET",
				dataType: "html",
				success: function (data) {
					//var result = $j('<div />').append(data).html();
					/* $j('#sub_list').html(result);*/


				},
				error: function (xhr, status) {
					alert("Sorry, there was a problem!");
				},
				complete: function (xhr, status) {
					//alert($j(this).attr('id'));
					//alert(clickvalue);
					$j('.'+clickvalue).css('color','grey');
				}
			});//ajax call end
		});//show function end



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
