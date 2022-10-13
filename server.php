<?php

//definimos los recursos disponibles
$allowedResourceType = [
	'books',
	'authors',
	'genres'
];

//validamos que el recurso este disponible
$resourceType = isset($_GET['resource_type'])? : '';

if (!in_array($resourceType, $allowedResourceType)){
	die;
}

//defino los recursos
$books = [
	1 => [
		'titulo' => 'Lo que el viendo se llevo',
		'id_autor' => 2,
		'id_genero' => 2	
	],
	2 => [
		'titulo' => 'La iliada',
		'id_autor' => 1,
		'id_genero' => 1,		
	],
	3 => [
		'titulo' => 'la odisea',
		'id_autor' => 3,
		'id_genero' => 3	
	],
];

header('Content-Type: application/json');

//levantamos el id del recurso buscado
$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';

//generamos la respuesta asumiendo que el pedido es correcto
switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
	case 'GET':
		if (empty($resourceId)) {
			echo json_encode($books);
		} else {
			if (array_key_exists($resourceId, $books)) {
				echo json_encode($books[$resourceId]);
			}
		}
	break;
	case 'POST':
		$json = file_get_contents('php://input');

		$books[] = json_decode($json, true);
		echo array_keys($books)[count($books) - 1];
	break;
	case 'PUT':
		//validamos que el recurso buscado exista
		if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
			//tomamos la entrada cruda
			$json = file_get_contents('php://input');

			$books[$resourceId] = json_decode($json, true);

			//retormanos la coleccion modificada en formato json
			echo json_encode($books);
		}
	break;
	case 'DELETE':
		//validamos que el recurso exista
		if (!empty($resourceId) && array_key_exists($resourceId, $books)) {
			//eliminamos el recurso
			unset($books[$resourceId]);

			//retormanos la coleccion modificada en formato json
			echo json_encode($books);
		}
	break;
}

