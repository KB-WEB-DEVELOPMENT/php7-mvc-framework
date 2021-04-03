<?php

/*

ch 18: ex 3

We'll use Google Cloud Storage and  write :

- the method storeCloudFile($filename) to store the uploaded image file on the Cloud.

- the method retrieveCloudFile($filename) to get the uploaded image file from the Cloud.

*/

include( "Google/Client.php" );
include( "Google/Service/Storage.php" );

use Shared\Controller as Controller;

class Users extends Controller	{
	
	// Of course, these 3 variables are hidden and stored in an .ini file or config file, read ch 4. "Configuration"
	$serviceAccount	= "kami@developer.gserviceaccount.com";
	$key_file = "/path/to/keyfile.p12";
	$bucket	= "kami_bucket";
	
	protected function storeCloudFile($filename)	{
	  
		$auth = new Google_Auth_AssertionCredentials(
					$serviceAccount,
					array('https://www.googleapis.com/auth/devstorage.read_write'),
					file_get_contents($key_file)
					);

		$client = new Google_Client();
		
		$client->setAssertionCredentials($auth);

		$storageService = new Google_Service_Storage($client);
		
		$file = File::first(array(
				"name = ?" = > $filename,
		));

		try	{
			
			$postbody = array( 
				'name' => $file["name"], 
				'data' => $file,
				'uploadType' => "media"
				);

			$gsso = new Google_Service_Storage_StorageObject();
			
			$gsso->setName($file["name"]);

			$storageService->objects->insert($bucket, $gsso, $postbody);
	
		}
		
		catch (Exception $e)	{
			
			print $e->getMessage();
		}		
	}
	
	protected function retrieveCloudFile($filename)	{
	  
		$file = File::first(array(
				"name = ?" = > $filename,
		));
		
		
		try {

		    $object = $storageService->objects->get($bucket,$file["name"]);

		    $request = new Google_Http_Request($object['mediaLink'], 'GET');
		   
		    $signed_request = $client->getAuth()->sign($request);
		   
		    $http_request = $client->getIo()->makeRequest($signed_request);
		   
		    $uploaded_file = $http_request->getResponseBody();
		
		    return $uploaded_file;
			
	  exit();
		}
		
		catch (Exception $e)	{
			
			print $e->getMessage();
		}
	}
	
}
	
?>
