<?php
/**
 * Created by PhpStorm.
 * User: Mahesh
 * Date: 16/11/15
 * Time: 4:31 PM
 *
 * this will display all participants of a course before starting any activity
 *
 */

require_once dirname(__FILE__).'/../../config.php';
require_once("$CFG->dirroot/enrol/locallib.php");



//retriving course id from url
$cid = required_param('cid', PARAM_INT);
$secname = optional_param('secname', 'All',PARAM_TEXT);
$course=get_course($cid);
$context = context_course::instance($course->id);
$students = get_role_users(5 , $context);//getting all the students from a course level

	if($secname=='All'||$secnmae=='0')
	{
	$section_flag=0;
	}
	else{
	$section_flag=1;
	}
	$display_flag=1;

/*$stuarr=array();$stcnt=0;
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
echo json_encode($stu_sec_info);*/

                //displaying enrolled students
                echo '<div class="repo" >
                    <div id="container" style="overflow-y: scroll; height:480px;padding-bottom:30px;" >


                        <table id="myTable"  class="CSSTableGenerator" >
                            <thead>
                            <tr>
                                <th>Status</th>
                                <th>S.NO</th>
                                <th>Full Name</th>
                                <th>Grade</th>
                                <th>Submission</th>
                                <th>Section</th>
                                <th>Watch List</th>
                            </tr>
                            </thead>
                            <tbody >';

                            $statusImag='flag-red-icon.png';//not submitted and not graded
                            $grade='--';
                            $subtime='--';



                        foreach($students as $student){
                            if(get_complete_user_data(id,$student->id)->profile['rollno']){
                                $rollnumber=get_complete_user_data(id,$student->id)->profile['rollno'];
                            }
                            else{
                                $rollnumber='';
                            }
                            if(get_complete_user_data(id,$student->id)->profile['sect']){
                                $stu_section=get_complete_user_data(id,$student->id)->profile['sect'];
                            }
                            else{
                                $stu_section='';
                            }

				if($section_flag){
	
				if(($secname==$stu_section))
				{
				$display_flag=1;
				}
				else{
				$display_flag=0;
				}
				}
   	if($display_flag){
                            echo '<tr>
                                <td><img src=" http://kartparadigm.com/kmit/'.$statusImag.'" width="  16px" /></td>
                                <td><a href="reportstudentview.html">'.$rollnumber.'</a></td>
                                <td><a href="reportstudentview.html">'.$student->firstname.' '.$student->lastname.'</a></td>
                                <td>'.$grade.'</td>
                                <td>'.$subtime.'</td>
                                <td>'.$stu_section.'</td>
                                <td><img src=" http://kartparadigm.com/kmit/eye-24-512.png" width="  24px"/></td>

                            </tr>';
			}
                        }

                        echo '</tbody>
                                </table>
                            </div>
                                <div id="scrollable"></div>
                            </div>';
