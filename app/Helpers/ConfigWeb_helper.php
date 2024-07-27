<?php

use App\Models\UserPermissionModel;

if (!function_exists('base_config')) {

	function base_config() {

        $default_image = '/default.jpg';
        $default_image_16_x_9 = '/dafault-image-16x9.webp';

        $data = [
            'app_name' 			=> 'Santri',
            'app_description' 	=> 'Santri',
            'app_logo'		    => '/pengaturan/1717433109_c9af8d0c6de271eeadb7.png',
            'app_url' 			=> base_url(),
            'company_name' 		=> 'Universitas Nahdlatul Ulama',
			'default_image'		=> $default_image,
            'default_image_16_x_9' => $default_image_16_x_9,
			'cookie_name'		=> '___DEV_BY_AGUNG_TRI_WIBOWO_2023___',
        ];
        return $data;
	}
}

if (!function_exists('getSharedDirectory')) {
    function getSharedDirectory()
    {
        return $_SERVER['DOCUMENT_ROOT'] . getenv('app.shared'); ;
    }
}

if (!function_exists('checkPermission')) {
    function checkPermission($label)
    {
        if(session()->usr_otoritas == 'superadmin'){
            return true;
        }
        
        if(in_array($label, session()->usr_prm_label)){
            return true;
        }else{
            return false;
        }
    }
}


if (!function_exists('getFormatRibuan')) {
    function getFormatRibuan($nominal)
    {
		return number_format($nominal,0,',','.');
    }
}

if (!function_exists('obfuscateName')) {

	function obfuscateName($name) {
        // Split the name into parts
        $parts = explode(' ', $name);   
        // Obfuscate the characters of each part except the first one
          for ($i = 0; $i < count($parts); $i++) {
              $partLength = strlen($parts[$i]);
              if($partLength >= 2){
                    $parts[$i] = substr($parts[$i], 0, 2) . str_repeat('*', strlen($parts[$i]) - 2);
              }else{
                  $parts[$i] = '***';
              }
        }
         
        // Join the parts back together and return
        return implode(' ', $parts);
	}
}
if (!function_exists('obfuscatePhoneNumber')) {

	function obfuscatePhoneNumber($phoneNumber) {
        if (!preg_match('/^\d{10,}$/', $phoneNumber)) {
            return $phoneNumber;
        }
        // Obfuscate all but the last four digits
        $obfuscated = str_repeat('*', strlen($phoneNumber) - 4) . substr($phoneNumber, -4);
        
        return $obfuscated;
	}
}

function convertToWhatsAppFormat($phoneNumber) {
    // Remove non-numeric characters
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    // Remove leading zeros
    $phoneNumber = ltrim($phoneNumber, '0');
    
    // Check if the number starts with the country code '62'
    if (substr($phoneNumber, 0, 2) != '62') {
        // Add country code if not present
        $phoneNumber = '62' . $phoneNumber;
    }

    if($phoneNumber == '62')
    {
        $phoneNumber = '-';
    }
    
    return $phoneNumber;
}

if (!function_exists('getNameFileShared')) {
    function getNameFileShared($name_file)
    {
		$file_exp 		= explode('/', $name_file);
		$get_row_name 	= count($file_exp) - 1;
		$file_name 		= $file_exp[$get_row_name];
		return $file_name;
    }
}