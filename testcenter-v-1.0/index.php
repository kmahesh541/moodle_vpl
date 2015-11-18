<?php
/**
 * Created by PhpStorm.
 * User: Mahesh
 * Date: 16/11/15
 * Time: 2:55 PM
 *
 *
 *Functionalities:
    1.display current course and name of the topic
    2.display current activities based on topic(with start button enabled by default)
    3.display activity name which is in progress
    4.display 3 html meters to show logged in,submitted,graded users.
    5.display list of all students
 *
 *
 */

require_once(dirname(__FILE__).'/../../config.php');
require_once($CFG->dirroot.'/mod/vpl/locallib.php');
require_once($CFG->dirroot.'/enrol/locallib.php');
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');
require_once('sub_list_head.php');


//login required to view this page
require_login();

//retriving course id from url
$id = required_param('cid', PARAM_INT);
$secid = required_param('secid', PARAM_INT);



$course=get_course($id);



/*logic to get activities and course name and section name*/

$modinfo = get_fast_modinfo($course);
$mods = $modinfo->get_cms();
$sections = $modinfo->get_section_info_all();
$secname=get_sections_name($secid,$sections);
$cm=$modinfo->get_cm($course->id);
$currentgroup = groups_get_activity_group($cm, true);
$context_module=context_course::instance($course->id);

$cid=$course->id;

            //preparing an array which contains sections and activities
            foreach ($mods as $mod) {
                $arr[$cnt++]=array('secid'=>$mod->section,'modid'=>$mod->id,'modname'=>$mod->name,'modcontent'=>$mod->content);
                //print_r($mod->name);
            }
            $activities=get_activities($secid,$arr);

            //get activities of a section
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

            //get current section name among all sections in the course
            function get_sections_name($sectionid,$sections)
            {

                foreach ($sections as $sec) {
                    if($sec->id==$sectionid){
                        return $sec->name;}
                }

            }



/*logic to get current logged in users */
/*this code is taken from online users block of moodle*/

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


            /*logic to get total number of participants in course*/

            $manager = new course_enrolment_manager($PAGE, $course, $filter='', $role='', $search='', $fgroup='', $status='');
            $totalenrolled= $manager->get_total_users();

		$baseUrl=$CFG->wwwroot;
            /*page content display start*/
            //starting of page container div
            echo '<div class="container container-demo" >

                                <div class="report">';



	    //code to display loading image
		echo '<div  class="pagecover">
		    
		    <div style="width: 600px; height: 45px; text-align: center; margin: 180px auto 0px;">        
			<div>LOADING</div><div><img src="'.$baseUrl.'/teacher/testcenter/images/loader.gif"></div>
		   </div>
		    <div style="width: 600px; margin: 10px auto; text-align: center; color: rgb(100, 100, 100);">This may take a few seconds depending on your network connection.</div> 
		</div>';

            echo '<div id="flip" >
                                <p id="fl"  >
                                        <i class="fa fa-angle-double-up" ></i>
                                        <span id="titile-status">
                                        <span  id="ccourse">Course : '.$course->fullname.' </span>
                                        <span id="ctopic" > Topic : --</span>
                                        <span  id="cactivity">Activity : --</span>
                                        <i style="float:right" class="fa fa-angle-double-down" ></i>
                                        </span>
                                </p></div>';//end of flip div

            echo '<div id="panel">
                                <div class="tleft" >
                                    <table style="width:100%">
                                        <tr>
                                            <td> Course : </td>
                                            <td><b>'.$course->fullname.'</b></td>
                                        </tr>
                                        <tr>
                                            <td>Chapter : </td>
                                            <td>';
            echo '<span>'.$secname.'</span>';
            echo '</td>
                                        </tr>
                                        <tr>
                                            <td>Section: </td>
					    <td>';
			echo '<select id="stu-section">';
			//for($i=0;$i<3;$i++)
			echo '<option value="All" id="'.$totalenrolled.'">All</option>';
			/*echo '<option value="A">A</option>';
			echo '<option value="B">B</option>';
			echo '<option value="C">C</option>';*/
			echo '</select>';

	     echo     '</td>
                                        </tr>
                                    </table>
                                </div><!-- end of table left -->


                        <div class="tright" >
                            <table id="t01" class="CSSTableGenerator topics-div">';

            for($i=0;$i<count($activities);$i++) {

                if($DB->get_field('course_modules', 'completionexpected', array('id' => $activities[$i]['modid']))){

                    $completiondate=userdate($DB->get_field('course_modules', 'completionexpected', array('id' => $activities[$i]['modid'])));
                    $completedactivity="markascomplete";
                }
                else{
                    $completedactivity="";$completiondate='';
                }

                echo '<tr class="row' . $activities[$i]['modid'] . ' '.$completedactivity.'">
                                <td ><span class="mod' . $activities[$i]['modid'] . '">' . ($i + 1) . '</span></td>
                                <td ><span class="mod' . $activities[$i]['modid'] . ' activitymod' . $activities[$i]['modid'] . '">' . $activities[$i]['modname'];

                if($completiondate){
                    echo '<br/><span>(Closed On: '.$completiondate .')</span>';
                }

                echo '</span></td>';
                /*echo '<td ><span class="mod' . $activities[$i]['modid'] . '">' . $activities[$i]['modcontent'] .. '</span></td>*/

                echo '<td ><button class="showhide show show' . $activities[$i]['modid'] . '" id="show" value=' . $activities[$i]['modid'];

                if($completiondate){
                    echo ' disabled="true" ';
                }
                echo '>
                                <img  alt="start" src="http://kartparadigm.com/kmit/start.png" width="16px"/></button>
                                <button class="showhide hide hide' . $activities[$i]['modid'] . '" id="hide" value=' . $activities[$i]['modid'] . '>
                                <img  alt="stop" src="http://kartparadigm.com/kmit/stop.png" width="16px"/></button>
                                <input type="checkbox" style="width: 20px;height: 25px;" class="complete complete' . $activities[$i]['modid'] . '" value="' . $activities[$i]['modid'] . '" />
                                </td>

                            </tr>';
            }
            echo'</table>

                        </div>
                        </div>';//end of panel div


            //div to display status of the course and students, loggedin , submissions, grade information.
            echo '<div class="sta">
                                        <div class="sub">
                                            <p class="subp">
						Section: <span id="current-stu-section">All</span>
                                            <b>
                                            <span id="current-activity"></span>
                                            <span>In Progress</span>
                                            <select id="refresh-time" style="display:none">
                                            <option value="6">60</option>
                                            <option value="3">30</option>
                                            </select>
                                            </b>
                                            </p>
                                        </div>

                                        <div class="sub">
                                        <p class="subp"><b>Logged : '.$usercount.' of <span id="studentCount1">'.$totalenrolled.'</span></b></p>
                                        <meter max="'.$totalenrolled.'" min="0" value="'.$usercount.'">'.$usercount.' out of <span class="studentCount">'.$totalenrolled.'<span></meter>
                                        <br>
                                        </div>';

            echo '<div class="sub subm">
                                            <p class="submp subp">
                                            <b>Submitted : <span id="csubCount">0</span> of <span class="studentCount">'.$totalenrolled.'</span></b>
                                            </p>
                                            <meter id="subCountMeter" max="'.$totalenrolled.'" min="0" value="0">
                                            <span id="csubCount"></span> out of <span class="studentCount">'.$totalenrolled.'</span></meter>
                                            <br>
                                            </div>';
            echo '<div class="sub subg">
                                            <p class="subgp subp">
                                            <b>Graded : <span id="cgradeCount">0</span> of <span class="studentCount">'.$totalenrolled.'</span></b>
                                            </p>
                                            <meter id="gradeCountMeter" max="'.$totalenrolled.'" min="0" value="0">
                                            <span id="cgradeCount"></span> out of <span class="studentCount">'.$totalenrolled.'</span></meter><br>
                                            </div>
                                </div>';//end of sta div



            echo '<div id="sub_list">';
            require_once('enrolledstudents.php');

            echo '</div>';
            echo '</div></div>';//end of report and container-demo divs
            /*page content display end*/

            //all hidden fields to store dynamic information for the page
            //hc in each id name represents hiddencurrent
            echo '<input type="hidden" id="current-lab" value="0" />';
            echo '<input type="hidden" id="hctopic" value="'.$secname.'" />';
            echo '<input type="hidden" id="hccourse" value="'.$course->fullname.'" />';
            echo '<input type="hidden" id="hcactivity" value="0" />';
            echo '<input type="hidden" id="hcactivity-id" value="0" />';
            echo '<input type="hidden" id="hcactivity-status" value="0" />';
            echo '<input type="hidden" id="setinterval-id" value="0" />';
            echo '<input type="hidden" id="hcstu-section" value="All" />';
            echo '<input type="hidden" id="studentCount" value="'.$totalenrolled.'" />';

?>




