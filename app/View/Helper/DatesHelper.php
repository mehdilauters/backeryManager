<?php
class DatesHelper extends AppHelper {
  function dateFrToTime($dateFR) {
      $d = explode('/',$dateFR);
    return mktime(0,0,0,$d[1], $d[0], $d[2]);
    
  }



function timeToDateJS($time) {
      
    return date("Y,m,d", $time);
    
  }
  
function timeToDateFR($time) {
      if(!empty($time))
      return date("d/m/Y", $time);
    else
      return '';    
  }

function timeToDateHeureFR($time) {
      
    return date("d/m/Y, H:m", $time);
    
  }
  
  function timeToDateDetailFeria($time){
    $mois='';
    switch(date('m', $time)){
      case '01':
        $mois="Janv.";
      break;
      case '02':
        $mois="F&eacute;vr.";
      break;
      case '03':
        $mois="Mars";
      break;
      case '04':
        $mois="Avr.";
      break;
      case '05':
        $mois="Mai";
      break;
      case '06':
        $mois="Juin";
      break;
      case '07':
        $mois="Juil.";
      break;
      case '08':
        $mois="Aout";
      break;
      case '09':
        $mois="Sept.";
      break;
      case '10':
        $mois="Oct.";
      break;
      case '11':
        $mois="Nov.";
      break;
      case '12':
        $mois="D&eacute;c.";
      break;
    }
    return '<span class="jour">'.date('d', $time).'</span><br/><span class="mois">'.$mois.'</span>';
  }

  function timeToDateCompleteFR($time){
  $mois=$this->getMoisFr(date('m', time()));
    
  $jour=$this->getJourFr(date('w', time()));
    
    return $jour .' '.date('d', $time).' '.$mois;
  }

  function getMoisFr($nbMois){
    switch($nbMois){
      case '01':
        $mois="janvier";
      break;
      case '02':
        $mois="f&eacute;vrier";
      break;
      case '03':
        $mois="mars";
      break;
      case '04':
        $mois="avril";
      break;
      case '05':
        $mois="mai";
      break;
      case '06':
        $mois="juin";
      break;
      case '07':
        $mois="juillet";
      break;
      case '08':
        $mois="août";
      break;
      case '09':
        $mois="septembre";
      break;
      case '10':
        $mois="octobre";
      break;
      case '11':
        $mois="novembre";
      break;
      case '12':
        $mois="d&eacute;cembre";
      break;
    }
    return $mois;
  }
  
  function getJourFr($nbJour){
    switch($nbJour){
      case '01':
        $jour="lundi";
      break;
      case '02':
        $jour="mardi";
      break;
      case '03':
        $jour="mercredi";
      break;
      case '04':
        $jour="jeudi";
      break;
      case '05':
        $jour="vendredi";
      break;
      case '06':
        $jour="samedi";
      break;
      case '00':
      case '07':
        $jour="dimanche";
      break;
      default:
	$jour = $nbJour;
      break;
    }
    return $jour;
  }


public function isToday($event)
{
    $isToday = false;
	$data = $event;
	if(!isset($event['Event']))
	{
		if(isset($event['Events']))
		{
			$data = $event['Events'];
		}
	}
	
    if(count($data != 0))
    {
		$startDate = new DateTime($data['start']);
		$endDate = new DateTime($data['end']);
		$when = new DateTime( 'now' );
		
		if($startDate <= $when && $when <= $endDate)
		{
		  $yes = true;
		}
  }
  return $isToday;
}


  public function getTimeTable($events)
  {
    $days = array();
    $i = 0;
    foreach ($events['Event'] as $event)
	{
		if( date('G',strtotime($event['end'])) < Configure::read('Settings.midday') )
		{
			$key = 'morning';
		}
		else
		{
			$key = 'afternoon';
		}
		$day = mktime(0, 0, 0, date("m",strtotime($event['start'] ))  , date("d",strtotime($event['start'] )), date("Y",strtotime($event['start'] )));
		$days[$day][$key] = $event;
		}
		ksort($days);

    return $days;
  }

  public function getNextOpenClose($events)
  {
      $nextOpened = 0;
      $nextClose = 0;
      $days = $this->getTimeTable($events);
      foreach($days as $dayId => $timeTable)
      {
	if($dayId >= mktime(0, 0, 0, date("m")  , date("d"), date("Y")))
	{
	    krsort($timeTable); // process morning before afternoon
	    foreach($timeTable as $key => $time)
	    {
	      if($nextOpened == 0 && strtotime($time['start']) > time() )
	      {
		if(strtotime($time['start']) > $nextOpened)
		{
		  $nextOpened = strtotime($time['start']);
		}
	      }
	      if($nextClose == 0 && strtotime($time['end']) > time() )
	      {
		if(strtotime($time['end']) > $nextClose)
		{
		  $nextClose = strtotime($time['end']);
		}
	      }
	    }
	  $day = $this->getJourFr(date('N',$dayId ));
	}
      }
    return array('nextClose'=> $nextClose, 'nextOpened'=>$nextOpened);
  }


}


?>
