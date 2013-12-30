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
  $mois=$this->getMoisFr(date('m'), time());
    
  $jour=$this->getJourFr(date('D'), time());
    
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
    foreach($event['Events'] as $event_i)
    {
      //public function isEvent($gevent, $when = 'now')
      $isToday = $this->isEvent($event_i);
      if($isToday)
      {
       break; 
      }

     }
  return $isToday;
}

   public function isEvent($gevent, $when = 'now')
  {
    $yes = false;
      $when = new DateTime( $when );
    
      foreach($gevent['Gevent']['GeventDate'] as $geventDate)
      {
        $startDate = new DateTime($geventDate['start']);
        $endDate = new DateTime($geventDate['end']);
       if($startDate <= $when && $when <= $endDate)
        {
          $yes = true;
          break;
        }
      }
      return $yes;
  }

  public function getTimeTable($events)
  {
    $days = array();
    $i = 0;
    foreach ($events['Event'] as $event)
      {
	foreach ($event['Gevent']['GeventDate'] as $date)
	{
	  if( date('G',strtotime($date['end'])) < 14 )
	  {
	    $key = 'morning';
	  }
	  else
	  {
	    $key = 'afternoon';
	  }
	  $day = mktime(0, 0, 0, date("m",strtotime($date['start'] ))  , date("d",strtotime($date['start'] )), date("Y",strtotime($date['start'] )));
	  $days[$day][$key] = $date;
	}
      }
    ksort($days);

    return $days;
  }


}


?>
