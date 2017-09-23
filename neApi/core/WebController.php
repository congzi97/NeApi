<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NeApi;

class WebController {
    private $view_dir = ROOT_PATH.'/neApi/base/view';
    private $file = '';
    public static $vars = [];
    public static $TplContent = '';
    private $c_name = '';
    private $h_name = '';
    private $model  = '';
    private $head   = '';
    private $floor  = '';
    private $d      = false;
    private $mf   = '';
    protected function read($file, $data = [],$head = '',$floor = '',$d = false){
        $this->d = $d;
        $this->head = '' === $head ? 'head' : $head;
        $this->floor = '' === $floor ? 'head' : $floor;
        $this->mf = $file;
        $tmp = explode('/',$file);
        $this->model = $tmp[0];
        $this->file = $tmp[1];
        self::$vars = $data;
        $eTime=microtime(true);
        $total=$eTime-START_TIME;
        $thiTime = round($total,3);
        self::$vars['total'] = $thiTime;
        $md5name = md5($this->file);
        $this->c_name = $this->file.'_'.$md5name.'.php';
        $this->h_name = $this->file.'_'.$md5name.'.html';;
        $this->getTemplateContent();
    }
    private function getTemplateContent(){
        $this->c_template();return;
        // 如果模板修改时间 大于 HTML文件修改时间 怎重新
        $tpl_time=filemtime($this->view_dir.'/template/'.$this->mf.'.html');
        $h_time=filemtime($this->view_dir.'/template/'.$this->head.'.html');
        $f_time=filemtime($this->view_dir.'/template/'.$this->floor.'.html');
        $h_time=filemtime($this->view_dir.'/template_c/'.$this->c_name);
        if ( $tpl_time > $h_time ||  $h_time > $h_time ||  $f_time > $h_time ){
            $this->c_template();
            return;
        }
        include $this->view_dir.'/html/'.$this->h_name;
    }
    /**
     * 编译模板
     */
    private function c_template(){
        ob_start();//开启缓存
        $c_name = $this->c_name;
        $h_name = $this->h_name;
        $headContent = file_get_contents($this->view_dir.'/template/'.$this->head.'.html');
        $floorContent = file_get_contents($this->view_dir.'/template/'.$this->floor.'.html');
        if (file_exists($this->view_dir.'/template/'.$this->mf.'.html') && false === \Ne::$app->error){
            $tplContent = file_get_contents($this->view_dir.'/template/'.$this->mf.'.html');
        }else{
            $tplContent = file_get_contents($this->view_dir.'/template/error.html');
        }
        if ( true === $this->d ){
            self::$vars['content'] = $tplContent;
            self::$TplContent = $headContent.$floorContent;
        }else{
            self::$TplContent = $headContent.$tplContent.$floorContent;
        }
        // 导入消耗耗时
        self::CompileData();
        self::CompileIF();
        self::CompileElseIF();
        self::CompileIFT();
        self::CompileForeach();

        file_put_contents($this->view_dir.'/template_c/'.$c_name,self::$TplContent);
        include ($this->view_dir.'/template_c/'.$c_name);
        file_put_contents($this->view_dir.'/html/'.$h_name, ob_get_contents());
        //清除缓冲区，清除了编译文件
        ob_end_clean();
        //载入缓存文件
        include $this->view_dir.'/html/'.$h_name;
    }
    // 导入数据
    private static function CompileData(){
        if (preg_match('/\[\$([\w]+)\]/',self::$TplContent)){
            self::$TplContent = preg_replace('/\[\$([\w]+)\]/', '<?php echo NeApi\WebController::\$vars[\'\\1\'];?>',self::$TplContent);
        }
    }
    // 解析 if 语法
    private static function CompileIF(){
        $modeIf = '/\[if\s+\$([\w]+)\]/';
        $modeEndIf = '/\[\/if\]/';
        $modeElse = '/\[else\]/';
        if (preg_match($modeIf,self::$TplContent)) {
            if (preg_match($modeEndIf, self::$TplContent)) {
                self::$TplContent = preg_replace($modeIf, "<?php if(NeApi\WebController::\$vars['$1']){?>", self::$TplContent);
                self::$TplContent = preg_replace($modeEndIf, "<?php }?>",self::$TplContent);
                if (preg_match($modeElse, self::$TplContent)) {
                    self::$TplContent = preg_replace($modeElse, "<?php }else{?>",self::$TplContent);
                }
            } else {
                echo('ErrorInfo<br />模板文件有误，IF语句没有关闭 ');exit;
            }
        }
    }
    // 解析 if 语法
    private static function CompileIFT(){
        $modeIf = '/\[if\s+\$([\w]+),([\w]+)\]/';
        $modeEndIf = '/\[\/if\]/';
        $modeElse = '/\[else\]/';
        if (preg_match($modeIf,self::$TplContent)) {
            if (preg_match($modeEndIf, self::$TplContent)) {
                self::$TplContent = preg_replace($modeIf, "<?php if($2==NeApi\WebController::\$vars['$1']){?>", self::$TplContent);
                self::$TplContent = preg_replace($modeEndIf, "<?php }?>",self::$TplContent);
                if (preg_match($modeElse, self::$TplContent)) {
                    self::$TplContent = preg_replace($modeElse, "<?php }else{?>",self::$TplContent);
                }
            } else {
                echo('ErrorInfo<br />模板文件有误，IF语句没有关闭 ');exit;
            }
        }
    }
    // 解析 elseif 语句
    private static function CompileElseIF(){
        $modeIf = '/\[Tif\s+content=\$([\w]+),([\w]+)\]/';
        $modeEndIf = '/\[\/Tif\]/';
        $modeElse = '/\[else\]/';
        $modeElseif = '/\[else\s+if\s+content=\$([\w]+),([\w]+)\]/';
        if (preg_match($modeIf,self::$TplContent)) {
            if (preg_match($modeEndIf, self::$TplContent)) {
                self::$TplContent = preg_replace($modeIf, "<?php if(NeApi\WebController::\$vars['$1']==$2){?>",self::$TplContent);

                if (preg_match($modeElse, self::$TplContent)) {
                    self::$TplContent = preg_replace($modeElse, "<?php }else{?>",self::$TplContent);
                }else if(preg_match($modeElseif, self::$TplContent)){
                    self::$TplContent = preg_replace($modeElseif, "<?php }else if(NeApi\WebController::\$vars['$1']==$2){?>",self::$TplContent);
                }

                self::$TplContent = preg_replace($modeEndIf, "<?php }?>", self::$TplContent);
            } else {
                echo('ErrorInfo<br />模板文件有误，ElseIF语句没有关闭 ');exit;
            }
        }
    }

    /**
     *  解析 Foreach
     *  {foreach $_var(key,val) }
     */
    private static function CompileForeach(){
        $_patternForeach = '/\[foreach\s+\$([\w]+)\(([\w]+),([\w]+)\)\]/';
        $_patternEndForeach = '/\[\/foreach\]/';
        //foreach里的值
        $_patternVar = '/\[@(\w+)\]/';
        //判断是否存在
        if(preg_match($_patternForeach,self::$TplContent)){
            //判断结束标志
            if(preg_match($_patternEndForeach,self::$TplContent)){
                //替换开头
                self::$TplContent = preg_replace($_patternForeach, "<?php foreach(NeApi\WebController::\$vars['$1'] as \$$2=>\$$3){?>", self::$TplContent);
                //替换结束
                self::$TplContent = preg_replace($_patternEndForeach, "<?php } ?>", self::$TplContent);
                //替换值
                self::$TplContent = preg_replace($_patternVar, "<?php echo \$$1?>",self::$TplContent);
            }else{
                echo('ErrorInfo<br />模板文件有误，Foreach语句没有关闭 ');exit;
            }
        }
    }
}