<?php
    $cont['key']='username';

    $cont['user1']['todaysend']='1000';
    $cont['user1']['yestsend']='109';
    $cont['user1']['10Mdelay']='1090';

    $cont['user2']['todaysend']='900';
    $cont['user2']['yestsend']='1230';
    $cont['user2']['10Mdelay']='110';

    $cont['user3']['todaysend']='13400';
    $cont['user3']['yestsend']='13400';
    $cont['user3']['10Mdelay']='140';
    #$cont['user3']['10Msend']='160';
    echo json_encode($cont);
