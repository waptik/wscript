<?php
header ( "Content-type: image/png; charset=UTF-8" );

if ( ! defined ( 'BUTTONS_TEMP_DIR' ) ) {
	define ( 'BUTTONS_TEMP_DIR', realpath ( dirname(__FILE__) ) . '/../various/temp/' );
}

if ( ! defined ( 'BASEPATH' ) ) {
	define ( 'BASEPATH', realpath ( dirname(__FILE__) ) . '/../system/' );
}

$text = ( isset ( $_GET [ 'text' ] ) && ! empty ( $_GET [ 'text' ] ) ) ? base64_decode ( $_GET [ 'text' ] ) : "drSoft Buttons Test";
$fontSize = ( isset ( $_GET [ 'size' ] ) && ! empty ( $_GET [ 'size' ] ) ) ? $_GET [ 'size' ] : 11;
$font_type = ( isset ( $_GET [ 'font' ] ) && ! empty ( $_GET [ 'font' ] ) ) ? $_GET [ 'font' ] : 'AlteHaasGroteskBold';
$angle = ( isset ( $_GET [ 'angle' ] ) && ! empty ( $_GET [ 'angle' ] ) ) ? $_GET [ 'angle' ] : 125;
$offset = ( isset ( $_GET [ 'offset' ] ) && ! empty ( $_GET [ 'offset' ] ) ) ? $_GET [ 'offset' ] : 0.6;
$transparency = ( isset ( $_GET [ 'transparency' ] ) && ! empty ( $_GET [ 'transparency' ] ) ) ? $_GET [ 'transparency' ] : 0.6;
$softness = ( isset ( $_GET [ 'softness' ] ) && ! empty ( $_GET [ 'softness' ] ) ) ? $_GET [ 'softness' ] : 3;
$color = ( isset ( $_GET [ 'color' ] ) && ! empty ( $_GET [ 'color' ] ) ) ? $_GET [ 'color' ] : 'FFFFFF';
$shadow_color = ( isset ( $_GET [ 'shadow_color' ] ) && ! empty ( $_GET [ 'shadow_color' ] ) ) ? $_GET [ 'shadow_color' ] : '000000';

$filename = md5 ( $text . $fontSize . $font_type . $angle . $offset . $transparency . $softness . $color . $shadow_color ) . '.png';

if ( file_exists ( BUTTONS_TEMP_DIR . $filename ) ) {
	echo @read_file ( BUTTONS_TEMP_DIR . $filename );
	exit;
}

@ob_start ();

$font = BASEPATH . "fonts/$font_type.ttf";

if ( ! file_exists ( $font ) ) {
	die ( "Unable to load font at $font" );
}


$textColor = Color::createFromHex ( $color );
$shadowColor = Color::createFromHex ( $shadow_color );

$textImage = ImageGenerator::createTextImage ( $text, $font, $fontSize, $textColor );
$textImage->addShadow ( $angle, $offset, $transparency, $softness, $shadowColor );

imagePNG ( $textImage->imageData );

$output = @ob_get_contents();

@ob_end_clean ();

@touch ( BUTTONS_TEMP_DIR . $filename );
@write_file ( BUTTONS_TEMP_DIR . $filename, $output );
echo $output;
die ();
//http://marcoslot.net/files/text.phps


function read_file ( $file )
{
	if ( ! file_exists ( $file ) )
	{
		return FALSE;
	}

	if ( function_exists ( 'file_get_contents' ) )
	{
		return file_get_contents ( $file );		
	}

	if ( ! $fp = @fopen ( $file, FOPEN_READ ) )
	{
		return FALSE;
	}
	
	flock($fp, LOCK_SH);

	$data = '';
	if (filesize($file) > 0)
	{
		$data =& fread($fp, filesize($file));
	}

	flock($fp, LOCK_UN);
	fclose($fp);

	return $data;
}

function write_file($path, $data, $mode = 'w' )
{
	if ( ! $fp = @fopen($path, $mode))
	{
		return FALSE;
	}
	
	flock($fp, LOCK_EX);
	fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);	

	return TRUE;
}

function imageClone ( $source )
{
	$width = imagesx ( $source );
	$height = imagesy ( $source );
	$newImage = imageCreateTrueColor ( $width, $height );
	imagePaletteCopy ( $newImage, $source );
	imageAlphaBlending ( $newImage, false );
	imageSaveAlpha ( $newImage, true );
	imageCopy ( $newImage, $source, 0, 0, 0, 0, $width, $height );
	return $newImage;
}

class ImageObject {

	public $imageData;

	public function __construct ( $image )
	{
		$this->imageData = $image;
	}

	public function __destruct ()
	{
		@imageDestroy ( $this->imageData );
	}

	public function getWidth ()
	{
		return imagesx ( $this->imageData );
	}

	public function getHeight ()
	{
		return imagesy ( $this->imageData );
	}

	public function getColor ( $x, $y )
	{
		$colorIndex = imageColorAt ( $this->imageData, $x, $y );
		$colorRGB = imageColorsForIndex ( $this->imageData, $colorIndex );
		return new Color ( $colorRGB [ 'red' ], $colorRGB [ 'green' ], $colorRGB [ 'blue' ], $colorRGB [ 'alpha' ] );
	}

	public function merge ( $mergeImage, $startX = 0, $startY = 0 )
	{
		imageAlphaBlending ( $this->imageData, true );
		imageCopy ( $this->imageData, $mergeImage->imageData, $startX, $startY, 0, 0, $mergeImage->getWidth (), $mergeImage->getHeight () );
	}

	public function blurGaussian ( $spread )
	{
		self::imageGaussian ( $this->imageData, $spread, $this->getWidth (), $this->getHeight () );
	}
	
	public static function imageGaussian ( &$image, $spread, $width, $height )
	{
		$use_low_memory_method = false;
		// Check for silly spreads
		if ( $spread == 0 ) {
			return;
		}

		if ( $spread > 10 ) {
			$spread = 10;
		}

		if ( strlen ( $memory_limit = trim ( ini_get ( 'memory_limit' ) ) ) > 0 )
		{
			$last = strtolower ( $memory_limit { strlen ( $memory_limit ) - 1 } );
			switch ( $last )
			{
				// The 'G' modifier is available since PHP 5.1.0
				case 'g' :
					$memory_limit *= 1024;
				case 'm' :
					$memory_limit *= 1024;
				case 'k' :
					$memory_limit *= 1024;
			}
			
			if ( $memory_limit <= 8 * 1024 * 1024 )
			{
				$use_low_memory_method = true;
			}
		}
		else {
			$use_low_memory_method = false;
		}
		
		// Perform gaussian blur convlution. First, prepare the convolution 
		// kernel and precalculated multiplication array. Algorithm
		// adapted from the simply exceptional code by Mario Klingemann
		// <http://incubator.quasimondo.com>. Kernel is essentially an
		// approximation of a gaussian distribution by utilizing squares.
		$kernelsize = $spread * 2 - 1;
		$kernel = array_fill ( 0, $kernelsize, 0 );
		$mult = array_fill ( 0, $kernelsize, array_fill ( 0, 256, 0 ) );
		
		for ( $i = 1; $i < $spread; $i ++ )
		{
			$smi = $spread - $i;
			$kernel [ $smi - 1 ] = $kernel [ $spread + $i - 1 ] = $smi * $smi;
			
			for ( $j = 0; $j < 256; $j ++ )
			{
				$mult [ $smi - 1 ] [ $j ] = $mult [ $spread + $i - 1 ] [ $j ] = $kernel [ $smi - 1 ] * $j;
			}
		}
		$kernel [ $spread - 1 ] = $spread * $spread;
		
		for ( $j = 0; $j < 256; $j ++ )
		{
			$mult [ $spread - 1 ] [ $j ] = $kernel [ $spread - 1 ] * $j;
		}
		
		if ( ! $use_low_memory_method )
		{
			$i = 0;
			
			for ( $x = 0; $x < $width; $x ++ )
			{
				for ( $y = 0; $y < $height; $y ++ )
				{
					$rgb = imagecolorat ( $image, $x, $y );
					$imagearray [ $i ++ ] = $rgb;
				}
			}
		}
		
		// Everything's set. Let's run the first pass. Our first pass will be a 
		// vertical pass.
		for ( $x = 0; $x < $width; $x ++ )
		{
			for ( $y = 0; $y < $height; $y ++ )
			{
				$sum = 0;
				$cr = $cg = $cb = 0;
				
				for ( $j = 0; $j < $kernelsize; $j ++ )
				{
					$kernely = $y + ( $j - ( $spread - 1 ) );
					
					if ( ( $kernely >= 0 ) && ( $kernely < $height ) )
					{
						if ( ! $use_low_memory_method )
						{
							$ci = ( $x * $height ) + $kernely;
							$rgb = $imagearray [ $ci ];
						}
						else
						{
							$rgb = imagecolorat ( $image, $x, $kernely );
						}
						$cr += $mult [ $j ] [ ( $rgb >> 16 ) & 0xFF ];
						$cg += $mult [ $j ] [ ( $rgb >> 8 ) & 0xFF ];
						$cb += $mult [ $j ] [ $rgb & 0xFF ];
						$sum += $kernel [ $j ];
					}
				}
				
				$ci = ( $x * $height ) + $y;
				$shadowarray [ $ci ] = ( ( intval ( round ( $cr / $sum ) ) & 0xff ) << 16 ) | ( ( intval ( round ( $cg / $sum ) ) & 0xff ) << 8 ) | ( intval ( round ( $cb / $sum ) ) & 0xff );
			}
		}
		
		// Free up some memory
		if ( isset ( $imagearray ) )
		{
			unset ( $imagearray );
		}
		
		// Now let's make with the horizontal passing. That sentence
		// contruct never gets old: "make with the". Oh the humor.
		for ( $x = 0; $x < $width; $x ++ )
		{
			for ( $y = 0; $y < $height; $y ++ )
			{
				$sum = 0;
				$cr = $cg = $cb = 0;
				
				for ( $j = 0; $j < $kernelsize; $j ++ )
				{
					$kernelx = $x + ( $j - ( $spread - 1 ) );
					
					if ( ( $kernelx >= 0 ) && ( $kernelx < $width ) )
					{
						$ci = ( $kernelx * $height ) + $y;
						$cr += $mult [ $j ] [ ( $shadowarray [ $ci ] >> 16 ) & 0xFF ];
						$cg += $mult [ $j ] [ ( $shadowarray [ $ci ] >> 8 ) & 0xFF ];
						$cb += $mult [ $j ] [ $shadowarray [ $ci ] & 0xFF ];
						$sum += $kernel [ $j ];
					}
				}

				$r = intval ( round ( $cr / $sum ) );
				$g = intval ( round ( $cg / $sum ) );
				$b = intval ( round ( $cb / $sum ) );
				
				if ( $r < 0 ) {
					$r = 0;
				}
				else if ( $r > 255 ) {
					$r = 255;
				}

				if ( $g < 0 ) {
					$g = 0;
				}	
				else if ( $g > 255 ) {
					$g = 255;
				}
					
				
				if ( $b < 0 ) {
					$b = 0;
				}
				else if ( $b > 255 ) {
					$b = 255;
				}

				$color = ( $r << 16 ) | ( $g << 8 ) | $b;
				
				if ( ! isset ( $colors [ $color ] ) )
				{
					$colors [ $color ] = imagecolorallocate ( $image, $r, $g, $b );
				}
				
				imagesetpixel ( $image, $x, $y, $colors [ $color ] );
			}
		}
	
	}

	public function addShadow ( $angle, $offset, $transparency, $softness, $shadowColor )
	{
		$semiSoft = 0.5 * $softness;
		$xOffset = round ( cos ( deg2rad ( $angle ) ) * $offset );
		$yOffset = round ( sin ( deg2rad ( $angle ) ) * $offset );
		
		$newWidth = $this->getWidth () + abs ( $xOffset ) + $softness;
		$newHeight = $this->getHeight () + abs ( $yOffset ) + $softness;
		
		$shadowX = $xOffset < 0 ? 0 : $xOffset;
		$shadowY = $yOffset < 0 ? 0 : $yOffset;
		
		$newX = $xOffset < 0 ? $semiSoft + abs ( $xOffset ) : ( $xOffset < $semiSoft ? $semiSoft - $xOffset : 0 );
		$newY = $yOffset < 0 ? $semiSoft + abs ( $yOffset ) : ( $yOffset < $semiSoft ? $semiSoft - $yOffset : 0 );
		
		$newImage = ImageGenerator::createTransparentImage ( $newWidth, $newHeight );
		$shadowImage = ImageGenerator::createShadowImage ( $this, $transparency, $softness, $shadowColor );
		$newImage->merge ( $shadowImage, $shadowX, $shadowY );
		$newImage->merge ( $this, $newX, $newY );
		
		$this->imageData = imageClone ( $newImage->imageData );
	}
}

class ImageGenerator {

	public static function createTransparentImage ( $width, $height )
	{
		$image = new ImageObject ( imageCreateTrueColor ( $width, $height ) );
		imageAlphaBlending ( $image->imageData, false );
		imageSaveAlpha ( $image->imageData, true );

		$transparency = imageColorAllocateAlpha ( $image->imageData, 0, 0, 0, 127 );
		imageFill ( $image->imageData, 0, 0, $transparency );

		return $image;
	}

	public static function createBlankShadowImage ( $image, $transparency, $softness )
	{
		$shadowImage = new ImageObject ( imageCreateTrueColor ( $image->getWidth () + $softness, $image->getHeight () + $softness ) );
		$black = imageColorAllocate ( $image->imageData, 0, 0, 0 );

		imageFill ( $shadowImage->imageData, 0, 0, $black );

		for ( $x = 0; $x < $image->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $image->getHeight (); $y ++ )
			{
				$colorIndex = imageColorAt ( $image->imageData, $x, $y );
				$colorRGB = imageColorsForIndex ( $image->imageData, $colorIndex );
				$alphaValue = ( int ) round ( ( 127 - $colorRGB [ 'alpha' ] ) * 2 * $transparency );
				$shadowColor = imageColorAllocate ( $shadowImage->imageData, $alphaValue, $alphaValue, $alphaValue );
				imageSetPixel ( $shadowImage->imageData, $x + $softness / 2, $y + $softness / 2, $shadowColor );
			}
		}

		$shadowImage->blurGaussian ( $softness );

		return $shadowImage;
	}

	public static function createShadowImage ( $image, $transparency, $softness, $shadowColor )
	{
		$shadowImage = self::createBlankShadowImage ( $image, $transparency, $softness );

		imageAlphaBlending ( $shadowImage->imageData, false );
		imageSaveAlpha ( $shadowImage->imageData, true );

		for ( $x = 0; $x < $shadowImage->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $shadowImage->getHeight (); $y ++ )
			{
				$color = $shadowImage->getColor ( $x, $y );
				$shadowColor = imageColorAllocateAlpha ( $shadowImage->imageData, $color->r, $color->g, $color->b, 127 - ( $color->r / 2 ) );
				imageSetPixel ( $shadowImage->imageData, $x, $y, $shadowColor );
			}
		}

		return $shadowImage;
	}

	public static function createTextImage ( $text, $font, $fontSize, $color )
	{
		$size = imageTTFBBox ( $fontSize, 0, $font, $text );
		$width = abs ( $size [ 2 ] ) + abs ( $size [ 0 ] );
		$height = abs ( $size [ 7 ] ) + abs ( $size [ 1 ] );

		$image = self::createTransparentImage ( $width, $height );
		$textColor = imageColorAllocateAlpha ( $image->imageData, $color->r, $color->g, $color->b, 0 );

		imageTTFText ( $image->imageData, $fontSize, 0, 0, abs ( $size [ 5 ] ), $textColor, $font, $text );

		return $image;
	}
}

class Color {

	public function __construct ( $red, $green, $blue, $alpha = 0 )
	{
		$this->r = $red;
		$this->g = $green;
		$this->b = $blue;
		$this->a = $alpha;
	}

	public static function createFromHex ( $hexColor )
	{
		$red = hexdec ( self::charAt ( $hexColor, 0 ) . self::charAt ( $hexColor, 1 ) );
		$green = hexdec ( self::charAt ( $hexColor, 2 ) . self::charAt ( $hexColor, 3 ) );
		$blue = hexdec ( self::charAt ( $hexColor, 4 ) . self::charAt ( $hexColor, 5 ) );

		return new Color ( $red, $green, $blue );
	}

	public static function charAt ( $str, $pos )
	{
		return ( substr ( $str, $pos, 1 ) ) ? substr ( $str, $pos, 1 ) : - 1;
	}
}