<?php declare(strict_types = 1);

namespace LSB;

/**
 * Least Significant Bit class.
 */
class LSB
{
    /**
     * @param string $message
     * @param string $pathToImage
     * @param string $pathToSaveImageWithMessage
     * @throws \Exception
     */
    public function encrypt(string $message, string $pathToImage, string $pathToSaveImageWithMessage)
    {
        $messageBinary = toBin($message);
        $messageLength = strlen($messageBinary);

        $src = $pathToImage;
        $image = imagecreatefromjpeg($src);

        $imageSize = getimagesize($src);
        $imageWidth  = $imageSize[0];
        $imageHeight = $imageSize[1];

        if ($messageLength > $imageHeight * $imageWidth) {
            throw new \Exception('Message is very long');
        }

        for ($y = 0; $y < $imageHeight && $y * $imageWidth < $messageLength; $y++) {
            for ($x = 0; $x < $imageWidth && $x + $y * $imageWidth < $messageLength; $x++) {
                $rgb = imagecolorat($image, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $newR = $r;
                $newG = $g;
                $newB = toBin($b);
                $newB[strlen($newB) - 1] = $messageBinary[$x + $y * $imageWidth];
                $newB = toString($newB);
                $newB = (int)$newB;

                $new_color = imagecolorallocate($image, $newR, $newG, $newB);
                imagesetpixel($image, $x, $y, $new_color);
            }
        }

        imagepng($image, $pathToSaveImageWithMessage);
        imagedestroy($image);
    }

    /**
     * @param string $pathToImage
     * @return string
     */
    public function decrypt(string $pathToImage): string
    {
        $src = $pathToImage;
        $imageWithMessage = imagecreatefrompng($src);
        $imageSize = getimagesize($src);
        $imageWidth  = $imageSize[0];
        $imageHeight = $imageSize[1];

        $message = "";
        for ($y = 0; $y < $imageHeight; $y++) {
            for ($x = 0; $x < $imageWidth; $x++) {
                $rgb = imagecolorat($imageWithMessage, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                $blue = toBin($b);
                $message .= $blue[strlen($blue) - 1];
            }
        }

        $message = toString($message);
        for ($i = 0; $i < strlen($message); $i++) {
            $char = ord($message[$i]);
            if ($char >= 255) {
                $message = substr($message, 0, $i);
                break;
            }
        }

        return $message;
    }
}