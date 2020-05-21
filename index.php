<?php
//Setting up infinite execution for this file.
ini_set('max_execution_time', 0);
function resize($image_source_directory , $image_destination_directory , $image_quality)
{
	if (!file_exists($image_destination_directory)) {
    			mkdir($image_destination_directory, 0777, true);
		}		
	if($dir = opendir($image_source_directory)){
		while(($file = readdir($dir))!== false){
			
			$image_path = $image_source_directory.$file;
			$dest_path = $image_destination_directory.$file;
			$check_valid_Image = @getimagesize($image_path);
			
			if(file_exists($image_path) && $check_valid_Image)
			{		
				if(resize_image($image_path,$dest_path,$image_quality))
				{
					echo $file.' resize Success!<br />';
				}else{
					echo $file.' resize Failed!<br />';
				}
			}
		}
		closedir($dir);
	}	
}
function resize_image($SrcImage,$DestImage,$image_quality)
{	
   	list($iWidth,$iHeight,$type)	= getimagesize($SrcImage);
    $ImageScale          	= min($iWidth , $iHeight);
    $NewWidth              	= $iWidth;
    $NewHeight             	= $iHeight;
    $NewCanves             	= imagecreatetruecolor($NewWidth, $NewHeight);
	switch(strtolower(image_type_to_mime_type($type)))
	{
		case 'image/jpeg':
			$NewImage = imagecreatefromjpeg($SrcImage);
			break;
		case 'image/png':
			$NewImage = imagecreatefrompng($SrcImage);
			break;
		case 'image/gif':
			$NewImage = imagecreatefromgif($SrcImage);
			break;
		default:
			return false;
	}
    if(imagecopyresampled($NewCanves, $NewImage,0, 0, 0, 0, $NewWidth, $NewHeight, $iWidth, $iHeight))
    {
		if(imagejpeg($NewCanves,$DestImage,$image_quality))
        {
          imagedestroy($NewCanves);
         return true;
        }
    }
}
//Call Function with source and destination folders here...
resize(dirname(__FILE__)."/old/" , dirname(__FILE__)."/compressed/" , 30 );
?>