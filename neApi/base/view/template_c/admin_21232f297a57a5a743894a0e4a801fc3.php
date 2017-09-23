<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title><?php echo NeApi\WebController::$vars['title'];?></title>
    <!-- 先引入 Vue -->
    <script type="text/javascript" src="/static/dist/vue.js"></script>
    <link href="/static/dist/styles/iview.css" rel="stylesheet" type="text/css">
    <script src="/static/dist/iview.min.js"></script>
    <script src="/static/js/jquery-3.2.1.min.js"></script>
    <script src="/static/js/jsencrypt.js"></script>
    <script src="/static/js/function.js"></script>
    <script src="/static/js/crypt.js"></script>
    <script>
//        window.onload=function(){
//            document.onkeydown=function(){
//                var e=window.event||arguments[0];
//                if(e.keyCode==123){
//                    //alert("小样你想干嘛？");
//                    return false;
//                }else if((e.ctrlKey)&&(e.shiftKey)&&(e.keyCode==73)){
//                    //alert("还是不给你看。。");
//                    return false;
//                }
//            };
//            document.oncontextmenu=function(){
//                //alert("小样不给你看");
//                return false;
//            }
//        }
    </script>
</head>
<body>

<div id="app" class="admin">
    <!-- 页面背景 -->
    <div class="container demo-1">
      <div class="content">
        <div id="large-header" class="large-header">
          <canvas id="demo-canvas"></canvas>
          <!--<h1 class="main-title">-->
            <!--Connect  <span class="thin" >Server</span>-->
          <!--</h1>-->
        </div>
      </div>
    </div>

    <div class="layout" :class="{'layout-hide-text': spanLeft < 5}" style="position: absolute;left: 0%;top: 0%;height: 100%;width: 100%;z-index: 99;" :style="{ background : theme.background.layout}">
        <Row type="flex">
            <i-col :span="spanLeft" class="layout-menu-left" :style="{ background : theme.background.menuLeft}" >
              <i-menu active-name="1" theme="dark" width="auto" v-on:on-open-change="open_menu_change" v-on:on-select="menu_on_select_change">
                  <div class="layout-logo-left">
                    <div class="layout-left-title">
                      NeApi后台管理
                    </div>
                  </div>
                  <div v-if=" 'menu' === $store.state.leftMenu.displayType">
                      <div v-for="(item,key) in this.$store.state.leftMenu.menuList">
                          <div v-if="'list' === item.name">
                              <submenu :name="item.list.name">
                                  <template slot="title">{{item.list.title}}</template>
                                  <menu-item v-for="l in item.list.content" :name="l.name">{{l.text}}</menu-item
                              </submenu>
                          </div>
                          <menu-item :name="key+1" v-else>
                              <Icon :type="item.icon" :size="iconSize"></Icon>
                              <span class="layout-text">{{item.name}}</span>
                          </menu-item>
                      </div>
                  </div>
                  <div v-if=" 'alert' === $store.state.leftMenu.displayType">
                      <div v-for="item in this.$store.state.leftMenu.alertList">
                          <alert  :type="item.type">
                              {{item.title}}
                              <template slot="desc" v-if=" item.content && '' !== item.content">{{item.content}}</template>
                          </alert>
                      </div>
                  </div>
                  <div v-if=" 'tree' === $store.state.leftMenu.displayType" style="margin: 15px;font-size: 3rem;color: #fff;">
                      <tree v-on:on-select-change="tree_on_select_change" v-on:on-toggle-expand="tree_on_toggle_expand" :data="$store.state.leftMenu.tree" show-checkbox></tree>
                  </div>
              </i-menu>
            </i-col>
            <i-col :span="spanRight">
                <div class="layout-header" :style="{ background : theme.background.header}">
                  <i-menu mode="horizontal" theme="dark" active-name="1">
                    <div style="float: left;width: 10%;">
                      <i-button type="text"  @click="toggleClick" style="color: #fff;">
                        <Icon type="navicon" size="32"></Icon>
                      </i-button>
                    </div>
                    <div style="float:left;width: 60%;text-align: center;color:#fff;font-size: 1.2rem;line-height: 60px;">
                      管理员:admin
                    </div>
                    <div class="layout-nav" style="float: right;width: 30%;">
                      <menu-item name="1">
                        <Icon type="heart" style="font-size: 2rem;float: left;padding-top: 13px;"></Icon>
                        <span>捐赠</span>
                      </menu-item>
                      <menu-item name="2">
                        <Icon type="person" style="font-size: 2rem;float: left;padding-top: 13px;"></Icon>
                        关于
                      </menu-item>
                      <menu-item name="3">
                        <Icon type="social-github" style="font-size: 2rem;float: left;padding-top: 13px;"></Icon>
                        开源
                      </menu-item>
                      <submenu name="4">
                        <template slot="title">更多</template>
                        <menu-group title="使用">
                            <menu-item name="3-1">更多内容</menu-item>
                        </menu-group>
                        <menu-group title="哈哈">
                            <menu-item name="3-4">更多内容</menu-item>
                        </menu-group>
                      </submenu>
                    </div>
                    </i-menu>
                </div>
                <div class="layout-breadcrumb">
                    <Breadcrumb>
                        <Breadcrumb-item href="/">首页</Breadcrumb-item>
                        <Breadcrumb-item v-if="0<$store.state.breadcrumb.length"
                                         v-for="item in $store.state.breadcrumb"
                                         :href="'' === item.href ? '#' : item.href">
                            {{item.name}}
                        </Breadcrumb-item>
                    </Breadcrumb>
                </div>
                <div class="layout-content" :style="{ background : theme.background.content}">
                    <div class="layout-content-main" v-if=" true === $store.state.adminPower">
                      <router-view></router-view>
                    </div>
                    <div class="layout-content-main" v-else style="border: 1px solid #fff;height: 90%;">
                        <div style="text-align: center;font-size: 2rem;color: #fff;margin-top: 20%;">对不起，你没有权限</div>
                        <div style="text-align: center;font-size: 2rem;color: #fff;"><a href="/admin/index.html">>>登录再来</a></div>
                    </div>
                </div>
                <div class="layout-copy">
                    2017 &copy; NeApi PHP API FRAMEWORK
                </div>
            </i-col>
        </Row>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="/static/index/css/component.css" />
<script src="/static/index/js/TweenLite.min.js"></script>
<script src="/static/index/js/EasePack.min.js"></script>
<script src="/static/index/js/demo-1.js"></script>
<script src="/static/js/vue-router.js"></script>
<script src="/static/js/vuex.js"></script>

<script src="/static/src-noconflict/ace.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/src-noconflict/ext-language_tools.js" type="text/javascript" charset="utf-8"></script>
<script src="/static/src-noconflict/ext-textarea.js" type="text/javascript" charset="utf-8"></script>
<!-- 引入路由配置 -->
<script src="/vueComponent/route.js"></script>
<!-- 引入 Vuex配置 -->
<script src="/vueComponent/store.js"></script>
<script>
      const store = new Vuex.Store({
        state     : states,
        actions   : actions,
        mutations : mutations,
      });
      const router = new VueRouter({
        routes
      })

var _vm =  new Vue({
        store,
        router,
        el :'#app',
        data : {
            login:'<?php echo NeApi\WebController::$vars['str_login'];?>',
            spanLeft: 5,
            spanRight: 19,
            index:0,
            theme:{
                background : {
                    // 整体布局 
                    'layout':'transparent',
                    // 左边菜单
                    'menuLeft': 'transparent',
                    // 头部head
                    'header':'transparent',
                    'content':'transparent',
                }
            }
        },
        computed: {
          iconSize :function() {
              return this.spanLeft === 5 ? 14 : 24;
          }
        },
        created:function(){
            this.$Notice.config({
                top: 70,
                duration: 5
            });
            // 初始化页面弹出的内容
            this.$Notice.open({
                title: '不喜欢透明的感觉？',
                desc: '支持可以自定义皮肤哦<br /><a href="#">点击我设置皮肤</a>'
            });
            if ( 'yes' === this.login){
                this.$store.dispatch('update',{
                    'model':'',
                    'key':'adminPower',
                    'value':true
                });
            }
        },
        mounted : function(){
          // Main
          initHeader();
          initAnimation();
          addListeners();
        },
        methods: {
            open_menu_change:function (array) {

            },
            menu_on_select_change:function (res) {
                console.log(res)
            },
            tree_on_toggle_expand : function (res) {
                // 关闭的时候直接返回
                if ( false === res['expand']){
                    return false;
                }
                // 文本文件管理器部分
                if ( 'web_file' === this.$store.state.tree_mod){
                    var obj = this.$route.matched[0]['components']['default']['methods'];
                    obj.changeTreeDir(this,res);
                }

            },
            tree_on_select_change:function (res) {
                if ( 0 === res.length){
                    return false;
                }
                if ( 'web_file' === this.$store.state.tree_mod){
                    res[0]['expand'] = true; // 展开目录
                    var obj = this.$route.matched[0]['components']['default']['methods'];
                    if ( true === res[0]['dir']){
                        obj.changeTreeDir(this,res[0]);
                    }else{
                        obj.changeTreeFile(this,res[0]);
                    }
                }

            },
            toggleClick :function() {
                if (this.spanLeft === 5) {
                  this.spanLeft = 2;
                  this.spanRight = 22;
                } else {
                  this.spanLeft = 5;
                  this.spanRight = 19;
                }
            }
        }
        }).$mount('#app')
        router.beforeEach(function (to, from, next) {
          _vm.$Loading.config({
              color: '#44CC00',
              failedColor: '#ff0000'
          });
          next()
        })
        router.afterEach(function (to) {
            _vm.$Loading.finish();
        })
</script>
<style>

    .editor-head-nav li {float: left;position: relative;list-style: none;margin-right: 15px;}
    .editor-head-nav li i {font-size: 1.2rem;}

    .layout-content-header {width: 100%;background: #5b6270;height: 50px;line-height: 50px;font-size: 1.2rem;color: #fff;padding-left: 10px;}
    .demo-spin-icon-load{
        animation: ani-demo-spin 1s linear infinite;
    }
    @keyframes ani-demo-spin {
        from { transform: rotate(0deg);}
        50%  { transform: rotate(180deg);}
        to   { transform: rotate(360deg);}
    }
    .demo-spin-col{
        width: 100%;height: 100%;
        position: absolute;top: 0;left: 0;padding-top: 40%;
    }
    .web-admin {position: relative;}
    .web-admin .editor-tip {position: absolute;height: 100%;width: 100%;text-align: center;background: #000000;opacity: .2;top: 0;left: 0;font-size: 2rem;padding-top: 25%;}

    .admin .ivu-tree-title {color: #fff;}
    .admin .ivu-tree ul {font-size: 1.3rem;}
    .admin .demo-spin-container{
        display: inline-block;
        width: 200px;
        height: 100px;
        position: relative;
        border: 1px solid #eee;
    }
    .admin .ivu-menu-dark.ivu-menu-vertical .ivu-menu-opened {background: transparent;}
    .admin .ivu-menu-dark {background: transparent;}
    .admin .layout-content {width: 96%;height: 80%;margin: 15px 2%;}
    .admin .ivu-row-flex {width: 100%;height: 100%;}
    .admin .ivu-breadcrumb {font-size: 1.3rem;color: #fff;}
    .admin .ivu-breadcrumb a {color: #fff;}
    .admin .ivu-breadcrumb span {color: #fff;}

    .layout-left-title {text-align: center;font-size: 1.2rem;color: #fff;}
    .layout{
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }
    .layout-breadcrumb{
        padding: 10px 15px 0;
    }
    .layout-content{
        min-height: 200px;
        margin: 15px;
        overflow: hidden;
        background: #fff;
        border-radius: 4px;
    }
    .layout-content-main{
        padding: 10px;
    }
    .layout-copy{
        text-align: center;
        padding: 10px 0 20px;
        color: #9ea7b4;
    }
    .layout-menu-left{
        background: transparent;overflow: auto;
    }
    .layout-header{
        height: 60px;
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
    }
    .layout-header button{color: #fff;}
    .layout-logo-left{
        width: 90%;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        margin: 15px auto;
    }
    .layout-ceiling-main a{
        color: #9ba7b5;
    }
    .layout-hide-text .layout-text{
        display: none;
    }
    .ivu-col{
        transition: width .2s ease-in-out;
    }
</style>

</body>
</html>