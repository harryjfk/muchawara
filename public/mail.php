<?php



if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
// Realizamos la petición de control: 
	$data = $_POST["data"];

	$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify'; 
	$recaptcha_secret = '6LcTqKoUAAAAAC_rIXZo7Qa5qosRFH6nN3M0eL6W'; 
	$recaptcha_response = $data['recaptcha'];
	$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response); 
	$recaptcha = json_decode($recaptcha); 
	// Miramos si se considera humano o robot: 
	if($recaptcha->score >= 0.6){
	
	

	$to = "buzon.inmobile@gmail.com";
	$subject = "WARA";
	$message = $data['nombre']." te ha contactado a través de tu sitio y te ha escrito lo siguiente: ".$data['mensaje']." Su teléfono es: ".$data['telefono'].", y su correo: ".$data['correo'];

	$from = "contacto@inmobile-cuba.com";
	$headers = "From:" . $from. "\r\n" .'Reply-To: '.$data['correo'];

	
	
	if (mail($to,$subject,$message,$headers))
	{
		// Transfer the value 'sent' to ajax function for showing success message.
		echo 'sent';
	}
	else
	{
		// Transfer the value 'failed' to ajax function for showing error message.
		echo 'failed';
	}
	    
//	    $to = "contacto@inmobile-cuba.com";

//	            $subject = "Correo de Contacto";

//	            $message = "Hello! This is a simple email message.";

//	            $from = "adriel@inmobile-cuba.com";

//	            $headers = "From:" . $from. "\r\n" .
//	                    'Reply-To: contacto@inmobile-cuba.com';

//	            mail($to,$subject,$message,$headers);

}else{
	    echo 'Recaptcha no validado';
}

}

?>