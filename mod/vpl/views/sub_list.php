<?php


require_once dirname(__FILE__).'/../../../config.php';
require_once $CFG->dirroot.'/mod/vpl/locallib.php';
require_once $CFG->dirroot.'/mod/vpl/vpl.class.php';
require_once $CFG->dirroot.'/mod/vpl/vpl_submission_CE.class.php';

require_login();

$id = required_param('id', PARAM_INT);
$subselection = vpl_get_set_session_var('subselection','allsubmissions','selection');
$options = array('height' => 550, 'width' => 780, 'directories' =>0, 'location' =>0, 'menubar'=>0,
        'personalbar'=>0,'status'=>0,'toolbar'=>0);




$vpl = new mod_vpl($id);
$vpl->prepare_page('views/submissionslist.php',array('id' => $id));

$course = $vpl->get_course();
$cm = $vpl->get_course_module();
$context_module = $vpl->get_context();
print_r($id);print_r("<br/>");print_r("<br/>");
print_r($course);print_r("<br/>");print_r("<br/>");
print_r($cm);print_r("<br/>");print_r("<br/>");
print_r($context_module);print_r("<br/>");print_r("<br/>");


//get students
$currentgroup = groups_get_activity_group($cm, true);
if(!$currentgroup){
    $currentgroup='';
}
$list = $vpl->get_students($currentgroup);
$submissions = $vpl->all_last_user_submission();
$submissions_number = $vpl->get_submissions_number();
//Get all information
$all_data = array();
$subCount=0;
$gradeCount=0;
foreach ($list as $userinfo) {
    if($vpl->is_group_activity() && $userinfo->id != $vpl->get_group_leaderid($userinfo->id)){
        continue;
    }
    $submission = null;
    if(!isset($submissions[$userinfo->id])){
        if($subselection != 'all'){
            continue;
        }
        $submission = null;
    }
    else{
        $subinstance = $submissions[$userinfo->id];
        $submission = new mod_vpl_submission_CE($vpl,$subinstance);
        $subid=$subinstance->id;
        $subinstance->gradesortable = null;
	
        if($subinstance->dategraded>0){
            if($subselection == 'notgraded'){
                continue;
            }
            if($subselection == 'gradedbyuser' && $subinstance->grader != $USER->id){
                continue;
            }
            //TODO REUSE showing
            $subinstance->gradesortable = $subinstance->grade;
        }else{
            $subinstance->grade = null;
            if($subselection == 'graded' ||$subselection == 'gradedbyuser'){
                continue;
            }
            //TODO REUSE showing
            $result=$submission->getCE();
            if($result['executed']!==0){
                $prograde=$submission->proposedGrade($result['execution']);
                if($prograde>''){
                    $subinstance->gradesortable=$prograde;
                }
            }
        }
        //I know that subinstance isn't the correct place to put nsubmissions but is the easy
        if(isset($submissions_number[$userinfo->id])){
            $subinstance->nsubmissions = $submissions_number[$userinfo->id]->submissions;
        }else{
            $subinstance->nsubmissions = ' ';
        }

    }
    $data = new stdClass();
    $data->userinfo = $userinfo;
    $data->submission = $submission;
    //When group activity => change leader object lastname to groupname for order porpouse
    if($vpl->is_group_activity()){
        $data->userinfo->firstname = '';
        $data->userinfo->lastname = $vpl->fullname($userinfo);
    }
    $all_data[] = $data;
}
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
				<th>Watch List</th>
			</tr>
			</thead>
			<tbody >';

foreach ($all_data as $data) {
    $userinfo = $data->userinfo;

if($data->submission == null){
        $subtime = get_string('nosubmission',VPL);
        $hrefview=vpl_mod_href('forms/submissionview.php','id',$id,
                'userid',$userinfo->id,'inpopup',1);
        //TODO clean comment
        $action = new popup_action('click', $hrefview,'viewsub'.$userinfo->id,$options);
        //$subtime = $OUTPUT->action_link($hrefview, $text,$action);
        
        $prev = '';
        $grade ='';
        $grader ='';
        $gradedon ='';$hrefgrade='';
    }
else{
$subCount++;
 $submission = $data->submission;
        $subinstance = $submission->get_instance();
        $hrefview=vpl_mod_href('forms/submissionview.php','id',$id,
                'userid',$subinstance->userid,'inpopup',1);
        $hrefprev=vpl_mod_href('views/previoussubmissionslist.php','id',$id,
                'userid',$subinstance->userid,'inpopup',1);
        $hrefgrade=vpl_mod_href('forms/gradesubmission.php','id',$id,
                'userid',$subinstance->userid,'inpopup',1);
$subtime=userdate($subinstance->datesubmitted);
	if($subinstance->nsubmissions>0){
		    $prev = $OUTPUT->action_link($hrefprev,
		    $subinstance->nsubmissions);
		}else{
		    $prev='';
		}
if($subinstance->dategraded>0){
     $text = $submission->print_grade_core();

            //Add propossed grade diff
            $result=$submission->getCE();
            if($result['executed']!==0){
                $prograde=$submission->proposedGrade($result['execution']);
                if($prograde>'' && $prograde != $subinstance->grade){
                    $text.= ' ('.$prograde.')';
                }
            }
	  //print_r("<br/>result-".$prograde."<br/>");
            $grade = '<div id="g'.$subid.'">'.$text.'</div>';
            //print_r("<br/>result-".$text."<br/>");

            $graderid=$subinstance->grader;
            $graderuser = $submission->get_grader($graderid);
            
            $grader = fullname($graderuser);
            $gradedon = userdate($subinstance->dategraded);
}
else{
            $result=$submission->getCE();
            $text='';
            if(($evaluate == 1 && $result['compilation'] === 0)||
               ($evaluate == 2 && $result['executed'] === 0 && $nevaluation <= $usernumber) ||
               ($evaluate == 3 && $nevaluation <= $usernumber)){ //Need evaluation
                   vpl_evaluate($vpl,$all_data,$userinfo,$usernumber,$groups_url);
            }
            if($result['executed']!==0){
                $prograde=$submission->proposedGrade($result['execution']);
                if($prograde>''){
                    $text=get_string('proposedgrade',VPL,$submission->print_grade_core($prograde));
		    $gradeCount++;
                }
            }
            if($text ==''){
                $text=get_string('nograde');
            }
            $action = new popup_action('click', $hrefgrade,'gradesub'.$userinfo->id,$options);
            $text = '<div id="g'.$subid.'">'.$text.'</div>';
            $grade = $OUTPUT->action_link($hrefgrade,$text,$action);
            $grader = '&nbsp;';
            $gradedon = '&nbsp;';
            //Add new next user
            if($last_id){
                $next_ids[$last_id]=$userinfo->id;
            }
            $last_id=$subid; //Save submission id as next index
        }
//Add div id to submission info
        $grader ='<div id="m'.$subid.'">'.$grader.'</div>';
        $gradedon ='<div id="o'.$subid.'">'.$gradedon.'</div>';

}


			echo '<tr>
				<td><img src=" flag-green-icon.png" width="  16px" /></td>
				<td><a href="reportstudentview.html">10BD1A0501</a></td>
				<td><a href="reportstudentview.html">'.$userinfo->firstname.'</a></td>
				<td><a href="'.$hrefgrade.'">'.$grade.'</a></td>
				<td>'.$gradedon.'</td>
				<td><img src=" eye-24-512.png" width="  24px"/></td>

			</tr>';
			
			
/*echo $userinfo->id;
echo $userinfo->firstname;
echo "submissions(";
echo $prev;echo ")";
echo  '<a href="'.$hrefview.'">'.$subtime.'</a>';
echo '<a href="'.$hrefgrade.'">'.$grade.'</a>';
echo $grader;
echo $gradedon;*/

}
echo '</tbody>
		</table>
	</div>
		<div id="scrollable"></div>
	</div>';

print_r("Total Submissions:".$subCount);
print_r("Total Graded:".$gradeCount);
?>
