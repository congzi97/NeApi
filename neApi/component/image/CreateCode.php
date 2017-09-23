<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Image;

class CreateCode {
    private $code = 'hello';
    private $codeLength = 4;
    private $width   = 100;
    private $height  = 30;
    private $img;
    private $font = '';
    private $fontSize = 8;
    private $fontColor;
    private $angle = 0;
    protected function Create(){
        $config             =   \Ne::$app->VerifyConfig;
        $this->width        =   $config['width'];
        $this->height       =   $config['height'];
        $this->font         =   $config['font'];
        $this->fontSize     =   $config['fontSize'];
        $this->angle        =   $config['angle'];
        $this->codeLength   =   $config['codeLength'];
    }
    /**
     * 创建背景，随机颜色
     */
    private function createBg() {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color     = imagecolorallocate($this->img, mt_rand(157, 255), mt_rand(157, 255), mt_rand(157, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 生成文字
     */
    private function createFont() {
        $_x     = $this->width/$this->codeLength;
        $obj    = \Ne::$app->get();
        for ($i = 0; $i < $this->codeLength; $i++) {
            $this->fontColor = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            if ( '' === $this->font || !file_exists($this->font)){
                imagestring($this->img, 5, $_x*$i+mt_rand(1, 5), $this->height/5, $this->code[$i], $this->fontColor);
            }else{
                $ranH = $obj::randomFloat(1.5,2);
                imagettftext($this->img,$this->fontSize, $this->angle, $_x*$i+mt_rand(1, 5), $this->height/$ranH, $this->fontColor, $this->font,$this->code[$i]);
            }
        }
    }

    /**
     * 生成线条、雪花
     */
    private function createLine() {
        //线条
        for ($i = 0; $i < 2; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(0, 156), mt_rand(0, 156), mt_rand(0, 156));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        //雪花
        for ($i = 0; $i < 100; $i++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 输出验证码
     */
    private function outPut() {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }
    //对外生成
    protected function doImg($code) {
        $this->code = $code;
        $this->createBg();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }
    ################################动态 GIF 验证码###########################
    protected function GIF_Image_Code($code) {
        $this->code   = $code;
        $authstr      = $code;
        $board_width  = $this->width;
        $board_height = $this->height;
        // 生成一个32帧的GIF动画
        for ($i = 0; $i < 32; $i++) {
            ob_start();
            $image = imagecreate($board_width, $board_height);
            imagecolorallocate($image, 0, 0, 0);
            // 设定文字颜色数组
            $colorList[] = ImageColorAllocate($image, 15, 73, 210);
            $colorList[] = ImageColorAllocate($image, 0, 64, 0);
            $colorList[] = ImageColorAllocate($image, 0, 0, 64);
            $colorList[] = ImageColorAllocate($image, 0, 128, 128);
            $colorList[] = ImageColorAllocate($image, 27, 52, 47);
            $colorList[] = ImageColorAllocate($image, 51, 0, 102);
            $colorList[] = ImageColorAllocate($image, 0, 0, 145);
            $colorList[] = ImageColorAllocate($image, 0, 0, 113);
            $colorList[] = ImageColorAllocate($image, 0, 51, 51);
            $colorList[] = ImageColorAllocate($image, 158, 180, 35);
            $colorList[] = ImageColorAllocate($image, 59, 59, 59);
            $colorList[] = ImageColorAllocate($image, 0, 0, 0);
            $colorList[] = ImageColorAllocate($image, 1, 128, 180);
            $colorList[] = ImageColorAllocate($image, 0, 153, 51);
            $colorList[] = ImageColorAllocate($image, 60, 131, 1);
            $colorList[] = ImageColorAllocate($image, 0, 0, 0);
            $fontcolor   = ImageColorAllocate($image, 0, 0, 0);
            $gray        = ImageColorAllocate($image, 245, 245, 245);
            $color       = imagecolorallocate($image, 255, 255, 255);
            $color2      = imagecolorallocate($image, 255, 0, 0);
            imagefill($image, 0, 0, $gray);
            $space = 25;// 字符间距
            if ($i > 0)// 屏蔽第一帧
            {
                for ($k = 0; $k < strlen($authstr); $k++) {
                    $colorRandom = mt_rand(0, sizeof($colorList)-1);
                    $float_top   = rand(0, 4);
                    $float_left  = rand(0, 3);
                    if ( '' === $this->font || !file_exists($this->font)){
                        imagestring($image, 6, $float_left + $space*$k+5, $float_top*5, substr($authstr, $k, 1), $colorList[$colorRandom]);
                    }else{
                        imagettftext($image, $this->fontSize, $this->angle,$float_left + $space*$k+5, $float_top*10, $colorList[$colorRandom], $this->font, substr($authstr, $k, 1));
                    }
                }
            }
            for ($k = 0; $k < 20; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList)-1);
                imagesetpixel($image, rand()%70, rand()%15, $colorList[$colorRandom]);
            }
            // 添加干扰线
            for ($k = 0; $k < 3; $k++) {
                $colorRandom = mt_rand(0, sizeof($colorList)-1);
                // $todrawline = rand(0,1);
                $todrawline = 1;
                if ($todrawline) {
                    imageline($image, mt_rand(0, $board_width), mt_rand(0, $board_height), mt_rand(0, $board_width), mt_rand(0, $board_height), $colorList[$colorRandom]);
                } else {
                    $w = mt_rand(0, $board_width);
                    $h = mt_rand(0, $board_width);
                    imagearc($image, $board_width-floor($w/2), floor($h/2), $w, $h, rand(90, 180), rand(180, 270), $colorList[$colorRandom]);
                }
            }
            imagegif($image);
            imagedestroy($image);
            $imagedata[] = ob_get_contents();
            ob_clean();
            ++$i;
        }

        $gif = new GIFEncoder($imagedata);
        Header('Content-type:image/*');
        echo $gif->GetAnimation();
    }
}
