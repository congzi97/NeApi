<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace BaseApp\Controller;



use NeApi\WebController;

class Admin extends WebController {
    private $login = false;
    public function __construct(){
        $s = \Ne::$app->session;
        $s_login = $s->get('login');
        if ( is_null($s_login) ) {
            $this->login = 'no';
        }else{
            $this->login = 'yes';
        }
    }
    public function ActionDelFile(){
        if ( true === $this->login){
            \Ne::$app->AppEnd('请登录再来',-1);
        }
        $file = \Ne::$app->request->post('file');
        if (!file_exists(ROOT_PATH.$file)){
            \Ne::$app->AppEnd('找不到文件',-1);
        }
        \Ne::$app->AppEnd('删除成功',200);
    }
    /**
     * 获取文件内容
     */
    public function ActionGetFileContent(){
        if ( true === $this->login){
            \Ne::$app->AppEnd('请登录再来',-1);
        }
        $file = \Ne::$app->request->post('file');
        if (!file_exists(ROOT_PATH.$file)){
            \Ne::$app->AppEnd('找不到文件',-1);
        }
        $content = file_get_contents(ROOT_PATH.$file);
        \Ne::$app->AppEnd('success',200,['content'=>$content]);
    }
    public function ActionGetFileAndFolder(){
        if ( true === $this->login){
            \Ne::$app->AppEnd('请登录再来..',-1);
        }
        $path = \Ne::$app->request->post('path');
        $new_path = ROOT_PATH.$path;
        if (!is_dir($new_path)){
            \Ne::$app->AppEnd('msg',-1);
        }
        \Ne::$app->AppEnd('success',200,[
            'dir'=>\Ne::$app->fileOperation->getDir($new_path),
            'file'=>\Ne::$app->fileOperation->getFile($new_path)
        ]);
    }
    public function ActionAdmin(){
        parent::read('admin/admin',['title'=>'NeApi后台管理系统','str_login'=> $this->login]);
    }
    /**
     * 后台首页
     */
    public function ActionIndex(){
        parent::read('admin/index',['title'=>'NeApi后台管理系统','str_login'=> $this->login ,'verify'=>\Ne::$app->verify->getSrc()]);
    }
    /**
     * 先模拟登录成功
     * 账户为 admin 密码为admin123
     */
    public function ActionLogin(){
        if ( true === $this->login){
            \Ne::$app->AppEnd('请登录再来..',-1);
        }
        $s = \Ne::$app->session;
        if (!is_null($s->get('login'))){
            \Ne::$app->AppEnd('已经登录',200);
        }
        $v_res = \Ne::$app->verify->Check(\Ne::$app->request->post('verify'));
        if ( 200 !== $v_res['code'] ){
            \Ne::$app->AppEnd($v_res['msg'],$v_res['code']);
        }
        if ( 'admin' !== \Ne::$app->request->post('username')){
            \Ne::$app->AppEnd('登录账户错误',-1);
        }
        $str = 'kJhEc4Z+I9Z3Dy1TmGl82RTpaqyfUcalNJc7FBUyJEmC3tUicw0u7q4zlWYmj5ExXFLGNHxEKFVTmgWr99Jyf+hIiu6LzaJWrb+fCwyVMRvAQFi7+sFWf8Q6zQBdZJMIBlag8wteo6SB4ylQE6z0B6CEIoPz+Ao4yY+xZFprgqs=';
        $res = $this->CheckPass(\Ne::$app->request->post('pass'),$str);
        if ( false === $res ){
            \Ne::$app->AppEnd('密码错误',-1);
        }
        $s->set('login',true,0);
        \Ne::$app->AppEnd('登录成功',200);
    }
    /**
     * 检查密码
     * @param $pass
     * @param $sqlPass
     * @return bool
     */
    private function CheckPass($pass , $sqlPass){
        $de  = \Ne::$app->crypt->de($sqlPass);
        $en  = md5(sha1($pass));
        if ( $de === $en){
            return true;
        }
        return false;
    }
    /**
     * 切换验证码
     * @return string
     */
    public function ActionVerify(){
        return \Ne::$app->AppEnd('获取成功',200,[
            'src'   =>  \Ne::$app->verify->getSrc(),
        ]);
    }
}