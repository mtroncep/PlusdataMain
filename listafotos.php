<?php
	include_once 'google-api-php-client--PHP7.0/vendor/autoload.php';

    $claveJSON = '1qSuy0f5WOGd1FCqSv7emcPtHclT39XvE';
    $pathJSON = 'messaging-67753-a6c4365e67b6.json';

	putenv('GOOGLE_APPLICATION_CREDENTIALS='.$pathJSON);

    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();
    $client->setScopes(['https://www.googleapis.com/auth/drive.file']);

	$service = new Google_Service_Drive($client);

	$resultado = $service->files->listFiles();

	foreach($resultado as $elemento){
		$ruta = 'https://drive.google.com/open?id=' . $elemento->id;
		echo '<a href="'.$ruta.'" target="_blank">'. $elemento->name . '</a> <br>';
	}

?>