<?php
/**
 * Created by PhpStorm.
 * User: Mahesh
 * Date: 16/11/15
 * Time: 4:35 PM
 *
 *
 * this will display the list of students with submitted status and graded status
 * this code is taken from moodle vpl submission list as a reference
 *
 *
 */
?>

<html>
<body>

<?php

class vpl_submissionlist_order{

    static $field;   //field to compare
    static $ascending; //value to return when ascending or descending order
    static $corder = null; //usort of old PHP versions don't call static class functions

            static public function cpm_userid($a,$b){ //Compare two submission fields
                if($a->userinfo->id < $b->userinfo->id ){
                    return self::$ascending;
                }else{
                    return -self::$ascending;
                }
            }
            static public function cpm_userinfo($a,$b){ //Compare two userinfo fields
                $field = self::$field;
                $adata = $a->userinfo->$field;
                $bdata = $b->userinfo->$field;
                if($adata == $bdata) {
                    return self::cpm_userid($a,$b);
                }
                if(is_string($adata) && function_exists('collatorlib::compare')){
                    return (collatorlib::compare($adata, $bdata))*(self::$ascending);
                }
                if($adata < $bdata){
                    return self::$ascending;
                }else{
                    return -self::$ascending;
                }
            }
            static public function cpm_submission($a,$b){ //Compare two submission fields
                $field = self::$field;
                $submissiona = $a->submission;
                $submissionb = $b->submission;
                if($submissiona == $submissionb){
                    return self::cpm_userid($a,$b);
                }
                if($submissiona == null){
                    return self::$ascending;
                }
                if($submissionb == null){
                    return -self::$ascending;
                }
                $adata = $submissiona->get_instance()->$field;
                $bdata = $submissionb->get_instance()->$field;
                if($adata === null){
                    return self::$ascending;
                }
                if($bdata === null){
                    return -self::$ascending;
                }
                if($adata == $bdata) {
                    return self::cpm_userid($a,$b);
                }elseif($adata < $bdata){
                    return self::$ascending;
                }else{
                    return -self::$ascending;
                }
            }

            /**
             * Check and set data to sort return comparation function
             * $field field to compare
             * $descending order
             * @return function
             */
            static public function set_order($field,$ascending = true){
                if(self::$corder === null){
                    self::$corder = new vpl_submissionlist_order;
                }
                $userinfofields = array('firstname'=>0,'lastname'=>0);
                $submissionfields = array('datesubmitted'=>0,'gradesortable'=>0,'grader'=>0,'dategraded'=>0,'nsubmissions'=>0);
                self::$field = $field;
                if($ascending){
                    self::$ascending = -1;
                }else{
                    self::$ascending = 1;
                }
                //usort of old PHP versions don't call static class functions
                if(isset($userinfofields[$field])){
                    return array(self::$corder,'cpm_userinfo');
                }elseif(isset($submissionfields[$field])){
                    return array(self::$corder,'cpm_submission');
                }else{
                    self::$field = 'firstname';
                    return array(self::$corder,'cpm_userinfo');
                }
            }
}
            function vpl_evaluate($vpl,$all_data,$userinfo,$nevaluation,$groups_url){
                global $OUTPUT;
                $nevaluation++;
                try{
                    echo '<h2>'.s(get_string('evaluating',VPL)).'</h2>';
                    $text =  $nevaluation.'/'.count($all_data);
                    $text .= ' '.$vpl->user_picture($userinfo);
                    $text .= ' '.fullname($userinfo);
                    $text .= ' <a href="'.$groups_url.'">'.get_string('cancel').'</a>';
                    echo $OUTPUT->box($text);
                    $id=$vpl->get_course_module()->id;
                    $userid=$userinfo->id;
                    $ajaxurl="../forms/edit.json.php?id={$id}&userid={$userinfo->id}&action=";
                    $url=vpl_url_add_param($groups_url,'evaluate',optional_param('evaluate', 0, PARAM_INT));
                    $url=vpl_url_add_param($url,'nevaluation',$nevaluation);
                    $nexturl=str_replace('&amp;','&',urldecode($url));
                    vpl_editor_util::generateEvaluateScript($ajaxurl,$nexturl);
                }catch(Exception $e){
                    echo $OUTPUT->box($e->getMessage());
                }
                $vpl->print_footer();
                die;
            }

            function vpl_submissionlist_arrow($burl, $sort, $selsort, $seldir){
                global $OUTPUT;
                $newdir = 'down';
                $url = vpl_url_add_param($burl,'sort',$sort);
                if($sort == $selsort){
                    $sortdir = $seldir;
                    if($sortdir == 'up'){
                        $newdir = 'down';
                    }elseif($sortdir == 'down'){
                        $newdir = 'up';
                    }
                }else{
                    $sortdir = 'move';
                }
                $url = vpl_url_add_param($url,'sortdir',$newdir);
                return ' <a href="'.$url.'">'.($OUTPUT->pix_icon('t/'.$sortdir,get_string($sortdir))).'</a>';
            }

require_once dirname(__FILE__).'/../../config.php';
require_once $CFG->dirroot.'/mod/vpl/locallib.php';
require_once $CFG->dirroot.'/mod/vpl/vpl.class.php';
require_once $CFG->dirroot.'/mod/vpl/vpl_submission_CE.class.php';

require_login();

$id = required_param('id', PARAM_INT);
$secname = optional_param('secname', 'All',PARAM_TEXT);
$subselection = vpl_get_set_session_var('subselection','allsubmissions','selection');
$options = array('height' => 550, 'width' => 780, 'directories' =>0, 'location' =>0, 'menubar'=>0,
    'personalbar'=>0,'status'=>0,'toolbar'=>0);
$group = optional_param('group', -1, PARAM_INT);
$sort = 'dategraded';
$sortdir = 'up';
//print_r($secname);
$acivitystatus='';


		if($DB->get_field('activity_status','status',  array('activityid' => $id))){
                        $result=$DB->get_field('activity_status','status',  array('activityid' => $id));
			$acivitystatus= $result;
                    }

//print_r($acivitystatus);

$vpl = new mod_vpl($id);
$vpl->prepare_page('views/submissionslist.php',array('id' => $id));

$course = $vpl->get_course();
$cm = $vpl->get_course_module();
$context_module = $vpl->get_context();

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
$section_flag=0;
$studentCount=0;
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

usort($all_data,vpl_submissionlist_order::set_order($sort,$sortdir != 'up'));


//content display start
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

	if($secname=='All'||$secnmae=='0')
	{
	$section_flag=0;
	}
	else{
	$section_flag=1;
	}
	$display_flag=1;
foreach ($all_data as $data) {
    $userinfo = $data->userinfo;
	$section_name=get_complete_user_data(id,$userinfo->id)->profile['sect'];
	if($section_flag){
	
	if(($secname==$section_name))
	{
	$display_flag=1;
	}
	else{
	$display_flag=0;
	}
	}

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
        $gradedon ='';
    }
    else{
	if($display_flag){
        $subCount++;
	}
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
            $grade = '<span id="g'.$subid.'">'.$text.'</span>';
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

                }
            }
            if($text ==''){
                $text=get_string('nograde');

            }
            $grades=$text;
            $action = new popup_action('click', $hrefgrade,'gradesub'.$userinfo->id,$options);
            $text = '<span id="g'.$subid.'">'.$text.'</span>';
            $grade = $text;//$OUTPUT->action_link($hrefgrade,$text,$action);
            $grader = '&nbsp;';
            $gradedon = '&nbsp;';
            //Add new next user
            if($last_id){
                $next_ids[$last_id]=$userinfo->id;
            }
            $last_id=$subid; //Save submission id as next index
        }
//Add span id to submission info
        $grader ='<span id="m'.$subid.'">'.$grader.'</span>';
        $gradedon ='<span id="o'.$subid.'">'.$gradedon.'</span>';
        if($grades!==get_string('nograde')){
            if($display_flag){$gradeCount++;}
		$statusImag='flag-green-icon.png';
        }
        else{
            $statusImag='flag-orange-icon.png';
        }

    }


    $rollno=get_complete_user_data(id,$userinfo->id)->profile['rollno'];
    if($display_flag){

    echo '<tr>
				<td><img src=" http://kartparadigm.com/kmit/'.$statusImag.'" width="  16px" /></td>
				<td><a href="reportstudentview.html">'.$rollno.'</a></td>
				<td><a href="reportstudentview.html">'.$userinfo->firstname.'</a></td>
				<td>'.$grade.'</td>
				<td>'.$subtime.'</td>
				<td>'.$section_name.'</td>
				<td><img src=" http://kartparadigm.com/kmit/eye-24-512.png" width="  24px"/></td>

			</tr>';
   }
}
echo '</tbody>
		</table>
	</div>
		<div id="scrollable"></div>
	</div>';

echo '<span style="display:none" id="subCount">'.$subCount.'</span>';
echo '<span style="display:none" id="gradeCount">'.$gradeCount.'</span>';
echo '<span style="display:none" id="acivitystatus">'.$acivitystatus.'</span>';
//echo '<span style="display:none" id="studentCount">'.$studentCount.'</span>';

?>
</body>
</html>
