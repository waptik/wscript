<?php
header ( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
header ( 'Cache-Control: post-check=0, pre-check=0', FALSE );
header ( "Pragma: no-cache" );

header ( "Content-type: image/png; charset=UTF-8" );

$text = isset ( $_GET [ 'text' ] ) ? base64_decode ( $_GET [ 'text' ] ) : "MarcoSlot.net";
$fontSize = isset ( $_GET [ 'fontSize' ] ) ? $_GET [ 'fontSize' ] : 12;

$font = "AlteHaasGroteskBold.ttf"; //"trado.ttf";
$textColor = Color::createFromHex ( 'FFFFFF' );

$textImage = ImageGenerator::createTextImage ( $text, $font, $fontSize, $textColor );
$textImage->addShadow ( 125, 0.7, 0.6, 2 );
//addShadow($angle, $offset, $transparency, $softness)
imagePNG ( $textImage->imageData );

//http://marcoslot.net/files/text.phps


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
	
	public function negative ()
	{
		for ( $x = 0; $x < $this->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $this->getHeight (); $y ++ )
			{
				$newColor = $this->getColor ( $x, $y )->negative ();
				$realColor = imageColorAllocate ( $this->imageData, $newColor->r, $newColor->g, $newColor->b );
				imageSetPixel ( $this->imageData, $x, $y, $realColor );
			}
		}
	}
	
	public function substract ( $subImage, $startX = 0, $startY = 0 )
	{
		for ( $x = 0; $x < $subImage->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $subImage->getHeight (); $y ++ )
			{
				$orgColor = $this->getColor ( $x + $startX, $y + $startY );
				$subColor = $subImage->getColor ( $x, $y );
				$newColor = $orgColor->substract ( $subColor );
				$realColor = imageColorAllocate ( $this->imageData, $newColor->r, $newColor->g, $newColor->b );
				imageSetPixel ( $this->imageData, $x + $startX, $y + $startY, $realColor );
			}
		}
	}

	public function add ( $addImage, $startX = 0, $startY = 0 )
	{
		for ( $x = 0; $x < $addImage->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $addImage->getHeight (); $y ++ )
			{
				$orgColor = $this->getColor ( $x + $startX, $y + $startY );
				$addColor = $addImage->getColor ( $x, $y );
				$newColor = $orgColor->add ( $addColor );
				$realColor = imageColorAllocateAlpha ( $this->imageData, $newColor->r, $newColor->g, $newColor->b, $newColor->a );
				imageSetPixel ( $this->imageData, $x + $startX, $y + $startY, $realColor );
			}
		}
	}

	public function merge ( $mergeImage, $startX = 0, $startY = 0 )
	{
		imageAlphaBlending ( $this->imageData, true );
		imageCopy ( $this->imageData, $mergeImage->imageData, $startX, $startY, 0, 0, $mergeImage->getWidth (), $mergeImage->getHeight () );
	}

	public function writeText ( $text, $font, $fontSize, $fontColor, $x = 0, $y = 0, $angle = 0 )
	{
		$size = imageTTFBBox ( $fontSize, $angle, $font, $text );
		$textColor = imageColorAllocate ( $this->imageData, $fontColor->r, $fontColor->g, $fontColor->b );
		imageTTFText ( $this->imageData, $fontSize, $angle, $x, $y + abs ( $size [ 5 ] ), $textColor, $font, $text );
	}

	public function blurGaussian ( $spread )
	{
		self::imageGaussian ( $this->imageData, $spread, $this->getWidth (), $this->getHeight () );
	}
	
	public static function imageGaussian ( &$image, $spread, $width, $height )
	{
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

	public function addShadow ( $angle, $offset, $transparency, $softness )
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
		$shadowImage = ImageGenerator::createShadowImage ( $this, $transparency, $softness );
		$newImage->merge ( $shadowImage, $shadowX, $shadowY );
		$newImage->merge ( $this, $newX, $newY );
		
		$this->imageData = imageClone ( $newImage->imageData );
	}

	public function fill ( $fillImage, $startX = 0, $startY = 0 )
	{
		imageAlphaBlending ( $this->imageData, false );
		for ( $x = 0; $x < min ( $fillImage->getWidth (), $this->getWidth () ); $x ++ )
		{
			for ( $y = 0; $y < min ( $fillImage->getHeight (), $this->getHeight () ); $y ++ )
			{
				$orgColor = $this->getColor ( $x + $startX, $y + $startY );
				$fillColor = $fillImage->getColor ( $x, $y );
				$newAlpha = $orgColor->a * ( 1 - $fillColor->a / 127 );
				$realColor = imageColorAllocateAlpha ( $this->imageData, $fillColor->r, $fillColor->g, $fillColor->b, $newAlpha );
				imageSetPixel ( $this->imageData, $x + $startX, $y + $startY, $realColor );
			}
		}
		imageAlphaBlending ( $this->imageData, true );
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

	public static function createShadowImage ( $image, $transparency, $softness )
	{
		$shadowImage = self::createBlankShadowImage ( $image, $transparency, $softness );

		imageAlphaBlending ( $shadowImage->imageData, false );
		imageSaveAlpha ( $shadowImage->imageData, true );

		for ( $x = 0; $x < $shadowImage->getWidth (); $x ++ )
		{
			for ( $y = 0; $y < $shadowImage->getHeight (); $y ++ )
			{
				$color = $shadowImage->getColor ( $x, $y );
				$shadowColor = imageColorAllocateAlpha ( $shadowImage->imageData, 0, 0, 0, 127 - ( $color->r / 2 ) );
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