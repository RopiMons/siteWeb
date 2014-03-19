<?php
function hex2rgb($color)
{
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}
function resizeImage($width, $height, $sourceFile, $destinationFile, $watermarks = array(), $textWatermarks = array())
{
	$o_file = $sourceFile;
	$dotPos = strrpos($sourceFile, ".") + 1;
	$extension = substr($sourceFile, $dotPos, strlen($sourceFile) - $dotPos);
	$o_im = null;
	if (strtolower($extension) == "jpg" || strtolower($extension) == "jpeg")
	{
		$o_im = imageCreateFromJPEG($o_file) ;
	}
	else if(strtolower($extension) == "png")
	{
		$o_im = imageCreateFromPNG($o_file) ;
	}
	else if(strtolower($extension) == "gif")
	{
		$o_im = imageCreateFromGIF($o_file) ;
	}
	//$o_im = imageCreateFromJPEG($o_file);
	$t_wd = 0;
	$t_ht = 0;
	if (!isset($ermsg) && isset($o_im))
	{
		$o_wd = imagesx($o_im);
		$o_ht = imagesy($o_im);
		//if width and height is given - resize, else make thumbnail of given dimension
		if($width!="" && $height!="")
		{
			$t_wd = $width;
			$t_ht = $height;
		}
		else
		{
			//if($o_wd >= $o_ht)
			if($width!="")
			{
				if( $o_wd >= $width )
				{
					$t_wd = $width;
					$t_ht = round($o_ht * $t_wd / $o_wd);
				}
				else
				{
					$t_wd = $o_wd;
					$t_ht = $o_ht;
				}
			}
			else
			{
				if( $o_ht >= $height )
				{
					$t_ht = $height;
					$t_wd = round($o_wd * $t_ht / $o_ht);
				}
				else
				{
					$t_wd = $o_wd;
					$t_ht = $o_ht;
				}
			}
		}

		$t_im = imageCreateTrueColor($t_wd, $t_ht);
		if(strtolower($extension) == "png" || strtolower($extension) == "gif")
		{
			imagealphablending( $t_im, false );
			imagesavealpha( $t_im, true );
			if(strtolower($extension) == "gif")
			{
				$transindex = imagecolortransparent($o_im);
				if($transindex >= 0) {
					$transcol = imagecolorsforindex($o_im, $transindex);
					$transindex = imagecolorallocatealpha($t_im, $transcol['red'], $transcol['green'], $transcol['blue'], 127);
					imagefill($t_im, 0, 0, $transindex);
				}
			}
		}

		imageCopyResampled($t_im, $o_im, 0, 0, 0, 0, $t_wd, $t_ht, $o_wd, $o_ht);
		
		if(strtolower($extension) == "gif")
		{
			if($transindex >= 0) 
			{
				imagecolortransparent($t_im, $transindex);
				for($y=0; $y<$t_ht; ++$y)
				for($x=0; $x<$t_wd; ++$x)
				  if(((imagecolorat($t_im, $x, $y)>>24) & 0x7F) >= 100) imagesetpixel($t_im, $x, $y, $transindex);

			}
			imagetruecolortopalette($t_im, true, 255);
		}
		//text watermarks
		if(count($textWatermarks))
		{
			for($i=0; $i<count($textWatermarks); $i++)
			{
				//create the image
				if(substr($textWatermarks[$i]["width"], -1)=="p")
					$width = imagesx($t_im)*(int)substr($textWatermarks[$i]["width"],0,-1)/100;
				else
					$width = (int)$textWatermarks[$i]["width"];
				if(substr($textWatermarks[$i]["height"], -1)=="p")
					$height = imagesx($t_im)*(int)substr($textWatermarks[$i]["height"],0,-1)/100;
				else
					$height = (int)$textWatermarks[$i]["height"];
				$watermark_text_im = imagecreatetruecolor($width, $height);
				imagesavealpha($watermark_text_im, true);

				if($textWatermarks[$i]["background_color"]!="")
				{
					//background color			
					$background_color_rgb = hex2rgb($textWatermarks[$i]["background_color"]);
					//$background_color = imagecolorallocate($watermark_text_im, $background_color_rgb[0], $background_color_rgb[1], $background_color_rgb[2]);
					//imagefilledrectangle($watermark_text_im, 0, 0, $textWatermarks[$i]["width"], $textWatermarks[$i]["height"], $background_color);
					
					//background color
					$background_color = imagecolorallocatealpha($watermark_text_im, $background_color_rgb[0], $background_color_rgb[1], $background_color_rgb[2], floor((int)$textWatermarks[$i]["bg_transparency"]*127/100)); //transparency 0-127
					imagefill($watermark_text_im, 0, 0, $background_color);
				}
				
				if($textWatermarks[$i]["text_color"]!="" && $textWatermarks[$i]["font_size"]!="" && $textWatermarks[$i]["font"]!="" && $textWatermarks[$i]["text"]!="")
				{
					//text color			
					$text_color_rgb = hex2rgb($textWatermarks[$i]["text_color"]);
					$text_color = imagecolorallocatealpha($watermark_text_im, $text_color_rgb[0], $text_color_rgb[1], $text_color_rgb[2], floor((int)$textWatermarks[$i]["text_transparency"]*127/100));
					
					//add the text
					imagealphablending($watermark_text_im, true);
					imagettftext($watermark_text_im, (int)$textWatermarks[$i]["font_size"], 0, (int)$textWatermarks[$i]["text_x"], (int)$textWatermarks[$i]["text_y"], $text_color, $textWatermarks[$i]["font"], $textWatermarks[$i]["text"]);
				}
				
				$marge_right = (int)$textWatermarks[$i]["right"];
				$marge_bottom = (int)$textWatermarks[$i]["bottom"];
				$sx = imagesx($watermark_text_im);
				$sy = imagesy($watermark_text_im);
				imagealphablending($t_im, true);
				imagesavealpha($t_im, true);
				imagecopy($t_im, $watermark_text_im, imagesx($t_im) - $sx - $marge_right, imagesy($t_im) - $sy - $marge_bottom, 0, 0, imagesx($watermark_text_im), imagesy($watermark_text_im));
			}
		}
		//image watermarks
		if(count($watermarks))
		{
			for($i=0; $i<count($watermarks); $i++)
			{
				$dotPos = strrpos($watermarks[$i]["path"], ".") + 1;
				$wextension = substr($watermarks[$i]["path"], $dotPos, strlen($watermarks[$i]["path"]) - $dotPos);
				if (strtolower($wextension) == "jpg" || strtolower($wextension) == "jpeg")
				{
					$watermark = imageCreateFromJPEG($watermarks[$i]["path"]);
				}
				else if(strtolower($wextension) == "png")
				{
					$watermark = imageCreateFromPNG($watermarks[$i]["path"]);
				}
				else if(strtolower($wextension) == "gif")
				{
					$watermark = imageCreateFromGIF($watermarks[$i]["path"]);
				}
				$marge_right = (int)$watermarks[$i]["right"];
				$marge_bottom = (int)$watermarks[$i]["bottom"];
				
				$sx = imagesx($watermark);
				$sy = imagesy($watermark);
				imagealphablending($t_im, true);
				imagesavealpha($t_im, true);
				imagecopy($t_im, $watermark, imagesx($t_im) - $sx - $marge_right, imagesy($t_im) - $sy - $marge_bottom, 0, 0, imagesx($watermark), imagesy($watermark));
			}
		}

		$t_file = $destinationFile;
		if (strtolower($extension) == "jpg" || strtolower($extension) == "jpeg")
		{
			if(imagejpeg($t_im,$t_file,90)) //quality==90 (from 0 to 100)
			{
				//return true;
			}
			else
			{
				return false;
			}
		}
		else if(strtolower($extension) == "png")
		{
			if(imagepng($t_im,$t_file,2)) //0-no compression, 9 - max compression
			{
				//return true;
			}
			else
			{
				return false;
			}
		}
		else if(strtolower($extension) == "gif")
		{
			if(imagegif($t_im,$t_file))
			{
				//return true;
			}
			else
			{
				return false;
			}
		}

		imageDestroy($o_im);
		imageDestroy($t_im);
		return true;
	}
	else
	{
		return false;
	}
}
?>