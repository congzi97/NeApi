<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Image;

class Picture
{
    private $fileName = '';
    private $newFileName = '';
    private $SavePath = '';
    private $srcArr = ['w' => 0, 'h' => 0, 'resource' => '', 'mime' => ''];
    private $dstArr = ['w' => 0, 'h' => 0, 'resource' => ''];
    private $waterTextInfo = ['x' => 0, 'y' => 0, 'font' => '', 'text' => '', 'size' => '', 'color' => '', 'alpha' => ''];
    private $waterText = false;
    private $waterImage = false;
    private $waterImageInfo = ['w' => 0, 'h' => 0,'x' => 0, 'y' => 0, 'font' => '', 'text' => '', 'size' => '', 'color' => '', 'alpha' => '','pct'=>80];
    private $waterImageSrc = '';
    /**
     * 指定宽度、高度 缩放图片
     * @param $fileName
     * @param string $savePath
     * @param string $newFileName
     * @param int $width
     * @param int $height
     * @return bool
     */
    public function zoom($fileName, $savePath = '', $newFileName = '', $width = 100, $height = 100){
        if (false === $this->CheckInit($fileName, $savePath, $newFileName)) {
            return false;
        }
        $this->mkdirSave();
        $this->dstArr = ['w' => $width, 'h' => $height, 'resource' => ''];
        $this->resource();
        return $this->saveImage();
    }

    /**
     * 水印文本相关
     * @param $font         字体文件路径
     * @param $text         水印文本
     * @param int $size     水印大小
     * @param string $color 水印颜色
     * @param int $alpha    水印透明度 0 - 270
     * @param int $pos      快速定位水印[1左上角2右上角3右下角4左下角5水平居中垂直居中]
     * @param int $x        水印文本的X轴位置
     * @param int $y        水印文本的Y轴位置
     * @return $this
     */
    public function waterTextInfo($font, $text, $size = 16, $color = '#ff0000', $alpha = 0, $pos = 0, $x = 0, $y = 0)
    {
        $this->waterText = true;
        $this->waterTextInfo['pos'] = $pos;
        $this->waterTextInfo['x'] = $x;
        $this->waterTextInfo['y'] = $y;
        $this->waterTextInfo['font'] = $font;
        $this->waterTextInfo['text'] = $text;
        $this->waterTextInfo['size'] = $size;
        $this->waterTextInfo['color'] = $color;
        $this->waterTextInfo['alpha'] = $alpha;
        return $this;
    }

    /**
     * 图片添加水印
     * @param $fileName     `需要添加水印的图片
     * @param $savePath     `保存路径
     * @param $newFileName  `保存用户名
     * @return bool
     */
    public function waterText($fileName, $savePath = '', $newFileName = ''){
        if (false === $this->CheckInit($fileName, $savePath, $newFileName)) {
            return false;
        }
        $this->mkdirSave();
        $this->resource();
        return $this->saveImage();
    }

    /**
     * 图片水印相关参数
     * @param $font         字体文件路径
     * @param $text         水印文本
     * @param int $size     水印大小
     * @param string $color 水印颜色
     * @param int $alpha    水印透明度 0 - 270
     * @param int $pos      快速定位水印[1左上角2右上角3右下角4左下角5水平居中垂直居中]
     * @param int $w        剪裁水印图片的长度
     * @param int $h        剪裁水印图片的高度
     * @param int $x        从哪里开始剪裁
     * @param int $y        从哪里开始剪裁
     * @param int $pct      合并程度 0什么也没有做
     * @return $this
     */
    public function waterImageInfo($font, $text, $size = 16, $color = '#ff0000', $alpha = 0, $pos = 0, $w = 100, $h = 100, $x = 0, $y = 0,$pct = 80){
        $this->waterImage = true;
        $this->waterImageInfo['pos'] = $pos;
        $this->waterImageInfo['h'] = $h;
        $this->waterImageInfo['w'] = $w;
        $this->waterImageInfo['x'] = $x;
        $this->waterImageInfo['y'] = $y;
        $this->waterImageInfo['font'] = $font;
        $this->waterImageInfo['text'] = $text;
        $this->waterImageInfo['size'] = $size;
        $this->waterImageInfo['color'] = $color;
        $this->waterImageInfo['alpha'] = $alpha;
        $this->waterImageInfo['pct'] = $pct;
        return $this;
    }

    /**
     * 添加图片水印
     * @param $src_image            水印图片路径
     * @param $fileName             需要添加水印图片的路径
     * @param string $savePath      保存路径
     * @param string $newFileName   保存名称
     * @return bool
     */
    public function waterImage($src_image,$fileName, $savePath = '', $newFileName = ''){
        if (!file_exists($src_image)){
            return false;
        }
        if (false === $this->CheckInit($fileName, $savePath, $newFileName)) {
            return false;
        }
        $this->waterImageSrc = $src_image;
        $this->mkdirSave();
        $this->resource();
        return $this->saveImage(true);
    }
    # 1左上角2右上角3右下角4左下角5水平居中垂直居中
    private function getPosition($pos)
    {
        $x = 0;
        $y = 0;
        $w = $this->srcArr['w'];
        $h = $this->srcArr['h'];
        switch ($pos) {
            case 1:
                $x = 0;
                $y = 0;
                break;
            case 2:
                $x = $w;
                $y = 0;
                break;
            case 3:
                $x = $w;
                $y = $h;
                break;
            case 4:
                $x = 0;
                $y = $h;
                break;
            case 5:
                $x = $w / 2;
                $y = $h / 2;
                break;
        }
        return ['x' => $x, 'y' => $y];
    }

    /**
     * 等比例缩放图片
     * @param float $percent 缩放比例
     * @param string $fileName
     * @param string $savePath
     * @param string $newFileName
     * @return bool
     */
    public function EqualScale($fileName, $percent = 0.5, $savePath = '', $newFileName = '')
    {
        if (false === $this->CheckInit($fileName, $savePath, $newFileName)) {
            return false;
        }
        $this->mkdirSave();
        $this->resource($percent);
        return $this->saveImage();
    }

    /**
     * 初始化变量
     * @param $fileName
     * @param $savePath
     * @param $newFileName
     * @return bool
     */
    private function CheckInit($fileName, $savePath, $newFileName)
    {
        if ('' === $fileName || !file_exists($fileName)) {
            return false;
        }
        $this->fileName = $fileName;
        $this->SavePath = $savePath;
        $this->newFileName = $newFileName;
        return true;
    }

    /**
     * 保存图片
     * @param bool $water
     * @return bool
     */
    private function saveImage($water = false){
        imagecopyresampled($this->dstArr['resource'], $this->srcArr['resource'], 0, 0, 0, 0, $this->dstArr['w'], $this->dstArr['h'], $this->srcArr['w'], $this->srcArr['h']);
        ####水印部分####
        if ( true === $this->waterText){
            $this->textImage();
        }elseif ( true === $this->waterImage){
            $this->w_image();
        }
        ####水印部分####
        $fileInfo = pathinfo($this->fileName);
        $array = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileInfo['extension']), $array)) {
            return false;
        }
        if ('' === $this->newFileName) {
            $fileInfo['filename'] = \Ne::$app->xxs->string_remove_xss($fileInfo['filename']);
            $this->newFileName = $fileInfo['filename'] . '_' . $this->dstArr['w'] . 'x' . $this->dstArr['h'] . '.' . $fileInfo['extension'];
        }
        $saveFun = str_replace('/', '', $this->srcArr['mime']);
        if ('' === $this->SavePath) {
            $this->SavePath = ROOT_PATH;
        }
//        header('Content-type:image/' . $fileInfo['extension']);
//        $saveFun($this->dstArr['resource']);
        $saveFun($this->dstArr['resource'],$this->SavePath.'/'.$this->newFileName);
        imagedestroy($this->srcArr['resource']);
        imagedestroy($this->dstArr['resource']);
        return true;
    }

    /**
     * 图片水印
     * @return bool
     */
    private function w_image(){
        if ($imageInfo = getimagesize($this->waterImageSrc)) {
            list($src_w, $src_h) = $imageInfo;
            $mime = $imageInfo['mime'];
            $createFun = str_replace('/', 'createfrom', $mime);
        } else {
            return false;
        }
        $s_im = $createFun($this->waterImageSrc);

        $w = 0 === $this->waterImageInfo['w'] ? $src_w : $this->waterImageInfo['w'];
        $h = 0 === $this->waterImageInfo['h'] ? $src_h : $this->waterImageInfo['h'];

        $x = 0; $y = 0;
        if (0 < $this->waterImageInfo['pos'] && 6 > $this->waterImageInfo['pos']) {
            switch ($this->waterImageInfo['pos']) {
                case 1:
                    $x = 0;
                    $y = 0;
                    break;
                case 2:
                    $x = $this->dstArr['w'] - $w;
                    $y = 0;
                    break;
                case 3:
                    $x = $this->dstArr['w'] - $w;
                    $y = $this->dstArr['h'] - $h;
                    break;
                case 4:
                    $x = 0;
                    $y = $this->dstArr['h'] - $h ;
                    break;
                case 5:
                    $x = ($this->dstArr['w'] - $w) / 2;
                    $y = ($this->dstArr['h'] - $h) / 2;
                    break;
            }
        }


        imagecopymerge($this->dstArr['resource'],$s_im,$x,$y, $this->waterImageInfo['x'], $this->waterImageInfo['y'],$w,$h, $this->waterImageInfo['pct']);
    }
    /**
     * 文本水印操作
     */
    private function textImage(){
        $array = imagettfbbox($this->waterTextInfo['size'], $this->waterTextInfo['alpha'], $this->waterTextInfo['font'], $this->waterTextInfo['text']);
        $textW = abs($array[4] - $array[6]);
        $textH = abs($array[3] - $array[5]);
        $rgb = \Ne::$app->color->hex2RGB($this->waterTextInfo['color']);
        $color = imagecolorallocatealpha($this->dstArr['resource'], $rgb['r'], $rgb['g'], $rgb['b'], $this->waterTextInfo['alpha']);
        if (0 < $this->waterTextInfo['pos'] && 6 > $this->waterTextInfo['pos']) {
            $XYPos = $this->getPosition($this->waterTextInfo['pos']);
            $this->waterTextInfo['x'] = $XYPos['x'];
            $this->waterTextInfo['y'] = $XYPos['y'];
        }
        //$this->waterTextInfo['x'] += $textW;
        $this->waterTextInfo['y'] += $textH;
        imagettftext($this->dstArr['resource'], $this->waterTextInfo['size'], $this->waterTextInfo['alpha'], $this->waterTextInfo['x'], $this->waterTextInfo['y'], $color, $this->waterTextInfo['font'], $this->waterTextInfo['text']);
    }
    /**
     * 创建图片资源
     * @param int $percent 0为非等比例缩放
     * @return bool
     */
    private function resource($percent = 0){
        if (!file_exists($this->fileName)) {
            return false;
        }
        if ($imageInfo = getimagesize($this->fileName)) {
            list($src_w, $src_h) = $imageInfo;
            if (0 !== $percent) {
                $this->dstArr['w'] = $src_w * $percent;
                $this->dstArr['h'] = $src_h * $percent;
            }
            if (true === $this->waterText || true === $this->waterImage) {
                $this->dstArr['w'] = $src_w;
                $this->dstArr['h'] = $src_h;
            }
            $mime = $imageInfo['mime'];
        } else {
            return false;
        }
        $createFun = str_replace('/', 'createfrom', $mime);
        // 创建图片资源
        $src_image = $createFun($this->fileName);
        $dst_image = imagecreatetruecolor($this->dstArr['w'], $this->dstArr['h']);
        $this->srcArr = [
            'w' => $src_w,
            'h' => $src_h,
            'resource' => $src_image,
            'mime' => $mime
        ];
        $this->dstArr['resource'] = $dst_image;
        return true;
    }

    /**
     * 创建目录保存目录
     */
    private function mkdirSave()
    {
        if (!is_dir($this->SavePath)) {
            @mkdir($this->SavePath, 0777, true);
        }
    }


}