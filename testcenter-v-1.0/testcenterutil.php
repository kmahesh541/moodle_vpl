<?php
/**
 * Created by PhpStorm.
 * User: Mahesh
 * Date: 16/11/15
 * Time: 4:44 PM
 *
 *
 * this is the file contains  the methods where we can perform all ajax calls from test center.
 *
 *
 */

require_once dirname(__FILE__).'/../../config.php';
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');
require_once("$CFG->dirroot/enrol/locallib.php");


$secid = optional_param('secid','1', PARAM_INT);
$cid = optional_param('cid', '2',PARAM_INT);
$mid=required_param('mid', PARAM_INT);//retrieve method id
$aid=optional_param('aid', '1',PARAM_INT);//retrieve method id
//retriving sections and activities based on course

//print_r($aid);

            switch($mid){
                case 1: section_activities($secid,$cid);break;
                case 2: set_activity_status($aid);break;
                case 3: set_activity_completiondate($aid);break;
                case 4: get_activity_status();break;
		case 5: get_student_sections($cid);break;
            }

            function section_activities($secid,$cid){
                $course=get_course($cid);
                $modinfo = get_fast_modinfo($course);
                $mods = $modinfo->get_cms();
                $sections = $modinfo->get_section_info_all();
                $sec_array = get_sections($sections);
                $arr = array();
                $cnt=0;

            //preparing an array which contains sections and activities
                foreach ($mods as $mod) {
                    $arr[$cnt++]=array('secid'=>$mod->section,'modid'=>$mod->id,'modname'=>$mod->name,'modcontent'=>$mod->content);
                    //print_r($mod->name);
                }

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

            // Get all course sections in a array
                function get_sections($sections)
                {   $cnt=0;
                    $sec_array = array();
                    foreach ($sections as $sec) {
                        $sec_array[$cnt++] = array('secid'=>$sec->id,'secname'=>$sec->name);
                    }
                    return $sec_array;
                }


                $activities=get_activities($secid,$arr);
                $html='';
                for($i=0;$i<count($activities);$i++) {
                    $html .= '<tr >
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
                echo $html;
            }//end of section_activities() function



            function set_activity_status($id){
                global $DB;
                $timenow = time();
                $activity_status = new stdClass();
                $activity_status->activityid     = $id;
                $activity_status->activity_start_time   =$timenow;
		$activity_status->status=1;
                //print_r($activity_status);
                try {
                    if(!$DB->get_field('activity_status', 'id', array('activityid' => $id))){
                        echo $DB->insert_record_raw('activity_status', $activity_status, false);}
                    else{
                        $activity_status->id=$DB->get_field('activity_status', 'id', array('activityid' => $id));
                        $activity_status->status=!($DB->get_field('activity_status', 'status', array('activityid' => $id)));
                        echo $DB->update_record_raw('activity_status', $activity_status, false);
                    }
                    //echo 'executed';

                } catch (dml_write_exception $e) {
                    // During a race condition we can fail to find the data, then it appears.
                    // If we still can't find it, rethrow the exception.
                    $activity_status_time = $DB->get_field('activity_status', 'activity_start_time', array('activityid' => $id));
                    if ($activity_status_time === false) {
                        //throw $e;
                        return 0;
                    }

                }
            }//end of set activity status


            function get_activity_status(){
                global $DB;
                $timenow = time();
                
                //echo $DB->get_field('activity_status','status',  array('activityid' => 5));
                try {
                    if(count($DB->get_records('activity_status', array('status' => 1)))){
                        $result=$DB->get_records('activity_status', array('status' => 1));
			$idstring='';
			foreach($result as $res){
				$idstring.=$res->activityid.',';			
			}
			echo rtrim($idstring, ",");
                    }
		else{
		echo '';
		}
                    //echo 'executed';

                } catch (dml_write_exception $e) {
                    
		echo '';                    

                }
            }//end of get activity status

            function set_activity_completiondate($aid){
                global $DB;
                $timenow = time();
                $activity_status_completiondate = new stdClass();
                $activity_status_completiondate->id     = $aid;
                $activity_status_completiondate->completionexpected =$timenow;
                //print_r($activity_status);
                try {
                    if($DB->get_field('course_modules', 'id', array('id' => $aid))){
                        echo $DB->update_record_raw('course_modules', $activity_status_completiondate, false);
                        $DB->delete_records('activity_status', null);
                    }

                    //echo 'executed';

                } catch (dml_write_exception $e) {
                    // During a race condition we can fail to find the data, then it appears.
                    // If we still can't find it, rethrow the exception.
                    $activity_status_time = $DB->get_field('course_modules', 'completionexpected', array('id' => $aid));
                    if ($activity_status_time === false) {
                        //throw $e;
                        return 0;
                    }

                }
            }//end of set activity status

		//get students section information based on course
		function get_student_sections($cid){
		$context = context_course::instance($cid);
		$students = get_role_users(5 , $context);//getting all the students from a course level


		$stuarr=array();$stcnt=0;
		foreach($students as $student){
		if(get_complete_user_data(id,$student->id)->profile['sect']){
		$stu_section=get_complete_user_data(id,$student->id)->profile['sect'];
		$stuarr[$stcnt++]=array('stusec'=>$stu_section,'stid'=>$student->id);
		}
		}

		$ss=array_count_values(array_column($stuarr, 'stusec'));
		ksort($ss);

		$stu_sec_info=array();$seccount=0;
		foreach( $ss as $key => $value)
		{
		$stu_sec_info[$seccount++]=array("secname"=>$key,"seccount"=>$value);
		}
		echo json_encode($stu_sec_info);

		}






?>

