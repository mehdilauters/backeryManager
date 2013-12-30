<?php
foreach ($gevent['Gevent']['GeventDate'] as $date) {
?>
   {
        title: '<?php echo $gevent['Gevent']['title'] ?>',
        start: '<?php echo $date['start'] ?>',
        <?php 
        $dateFin = new DateTime($date['end']);
        $dateInterval = new DateInterval('P1D');
        $dateDeb = new DateTime($date['start']);

        if($date['start'] == $date['end'] || $dateFin == $dateDeb->add( $dateInterval) ) { ?>
           allDay: true,
        <?php } else { ?>
           allDay: false,
           end: '<?php echo $date['end'] ?>',
         <?php } ?>
   },
<?php
}

?>
