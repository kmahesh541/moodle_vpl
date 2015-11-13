<?php
require_once dirname(__FILE__).'/../../../config.php';
require_once $CFG->dirroot.'/mod/vpl/locallib.php';
require_once("$CFG->dirroot/enrol/locallib.php");
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');


//login required to view this page
require_login();


//retriving course id from url
$id = required_param('id', PARAM_INT);

// Get all course sections in a array
function get_sections($sections)
{   $cnt=0;
    $sec_array = array();
    foreach ($sections as $sec) {
        $sec_array[$cnt++] = array('secid'=>$sec->id,'secname'=>$sec->name);
    }
    return $sec_array;
}

$course=get_course($id);
//initializing all variables using vpl object
//$vpl = new mod_vpl($id);
//$course = $vpl->get_course();
//$cm = $vpl->get_course_module();
//$context_module = $vpl->get_context();
//$currentgroup = groups_get_activity_group($cm, true);
print_r($course->fullname);


$modinfo = get_fast_modinfo($course);
$mods = $modinfo->get_cms();
$sections = $modinfo->get_section_info_all();
$sec_array = get_sections($sections);
$cm=$modinfo->get_cm($course->id);
$currentgroup = groups_get_activity_group($cm, true);
$context_module=context_course::instance($course->id);

$cid=$course->id;

				echo '<select name="topics" id="topics">';
				for($i=0;$i<count($sec_array);$i++){
					if($sec_array[$i]['secname']){
					echo '<option value="'.$sec_array[$i]['secid'].'">'.$sec_array[$i]['secname'].'</option>';
					}
				}
				echo '</select>';



    echo '<table>';
    echo '<div class="topics-div"></div>';
    echo '</table>';

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
$params['courseid'] = 4;
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
echo '<div class="sub">
			<p class="subp"><b>Logged : '.$usercount.' of '.$totalenrolled.'</b></p>
			<meter max="'.$totalenrolled.'" min="0" value="'.$usercount.'">'.$usercount.' out of '.$totalenrolled.'</meter><br>
		</div>';

/*function display(){
$i=0;
echo $i++;
require_once $CFG->dirroot.'/mod/vpl/views/sub_list.php';
}*/
echo '<div id="sub_list"></div>';


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





var hideshowurl='<?php echo $CFG->wwwroot."/course/mod.php?sesskey=".sesskey()."&sr=0&" ?>';

updateDiv();
function updateDiv(){

$j.ajax({
        url: "http://172.20.36.41/moodle/mod/vpl/views/sub_list.php",
	data: {
        "id": 4,
	"group":-1,
	"sort":'datesubmitted',
	"sortdir":'up'
    	},
        type: "GET",
        dataType: "html",
        success: function (data) {
            var result = $j('<div />').append(data).html();
            $j('#sub_list').html(result);
        },
        error: function (xhr, status) {
            alert("Sorry, there was a problem!");
        },
        complete: function (xhr, status) {
            $j("#myTable").tablesorter();
        }
    });
}//updateDiv



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

//on change of dropdown
$j('#topics').on('change', function() {
  //alert( this.value ); // or $(this).val()

    var secid=this.value;
        $j.ajax({
            url: 'http://172.20.36.41/moodle/mod/vpl/views/testcenterutil.php?secid='+secid+'&cid='+cid,
            type: "GET",
            dataType: "html",
            success: function (data) {
                var result = $j('<div />').append(data).html();
                $j('.topics-div').html(result);


            },
            error: function (xhr, status) {
                alert("Sorry, there was a problem!");
            },
            complete: function (xhr, status) {
                //alert($j(this).attr('id'));
                //alert(clickvalue);

            }
        });//ajax call end


});
    if($j("#topics").val()){

        var secid=$j("#topics").val();
        $j.ajax({
            url: 'http://172.20.36.41/moodle/mod/vpl/views/testcenterutil.php?secid='+secid+'&cid='+cid,
            type: "GET",
            dataType: "html",
            success: function (data) {
                var result = $j('<div />').append(data).html();
                $j('.topics-div').html(result);


            },
            error: function (xhr, status) {
                alert("Sorry, there was a problem!");
            },
            complete: function (xhr, status) {
                //alert($j(this).attr('id'));
                //alert(clickvalue);

            }
        });//ajax call end
    }


 });//end of ready
</script>
