<?php
/**
 * NeAPI PHP API  Framework
 * @author NOT EXPIRED <notexpired@163.com>
 * @version 1.0
 * @package Component\Image
 */
namespace NComponent\Email;
require_once(ROOT_PATH."/neApi/phpmailer/class.phpmailer.php");
require_once(ROOT_PATH."/neApi/phpmailer/class.smtp.php");
class MyEmail {
    private $config = [];
    public function __construct(){
        $data = include ROOT_PATH.'/config/email.php';
        $this->config = $data;
        unset($data);
    }
    /**
     * 发送邮箱
     * @param $to   `对方账户
     * @param $title `发送标题
     * @param $content `发送内容
     * @param null $minTitle    收信人名称
     * @return bool
     */
    public function send($to,$title,$content,$minTitle = null){
        if ( '' === $to or '' ===  $title or '' ===  $content )
            return false;
        $emailConfig = $this->config;
        $mail = new \PHPMailer();
        // 设定调试模式
        $mail->SMTPDebug = false;
        // 设定使用SMTP服务
        $mail->isSMTP();
        // 启用 SMTP 验证功能
        $mail->SMTPAuth=true;
        // SMTP 服务器
        $mail->Host = $emailConfig['smtpHost'];
        // SMTP 安全协议
        $mail->SMTPSecure = 'ssl';
        // SMTP服务器的端口号
        $mail->Port = 465;
        //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置为 UTF-8
        $mail->CharSet = 'UTF-8';
        // SMTP服务器用户名
        $mail->Username = $emailConfig['userName'];
        // SMTP服务器密码
        $mail->Password = $emailConfig['password'];
        // 设置发件人地址
        $mail->From = $emailConfig['from'];
        // 设置发件人名称
        $mail->FromName = $emailConfig['fromName'];
        // 是否发送HTML内容
        $mail->isHTML(true);
        if ($minTitle == null){
            $arr = explode('@',$to);
            $toName = $arr[0];
        }else{
            $toName = $minTitle;
        }
        // 设置收信人地址和名称
        $mail->addAddress($to,$toName);
        // 设置邮件标题
        $mail->Subject = $title;
        // 设置邮件内容
        $mail->Body    = $content;
        return $mail->send();
    }



}
