<?php 
require_once dirname(__FILE__).'/../../../config.php';
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');



$secid = required_param('secid', PARAM_INT);
$cid = required_param('cid', PARAM_INT);
//retriving sections and activities based on course


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
$activities=get_activities(2,$arr);
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

?>
