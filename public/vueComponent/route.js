var index = {
    template : heredoc(function () {
        /*
           <div class="index-content">
              <div class="i-c-bg"></div>
              <div class="i-c-content">
                <router-link v-for="item in list" :to="item.href">
                  <card style="width:320px;background: transparent;float: left;margin-left: 20px;margin-bottom: 20px;">
                    <div style="text-align:center">
                      <img :src="item.src" style="width: 200px;height: 100px;">
                      <h3>{{item.name}}</h3>
                    </div>
                  </card>
                </router-link>
              </div>
            </div>
         */
    }),
    props:{
    },
    data:function(){
        return {
            list:[
                {'name':'数据分析','href':'/DataAnalysis','src':'/static/image/1.jpg'},
                {'name':'在线Web文件管理器','href':'/web_file_admin','src':'/static/image/1.jpg'},
            ],
        }
    },
    created:function(){
        this.$store.dispatch('update',{
            'model':'leftMenu',
            'key':'displayType',
            'value':'alert'
        });
        this.$store.dispatch('update',{
            'model':'leftMenu',
            'key':'alertList',
            'value':[
                {'type':'success','title':'系统更新通知','content':'你有可用版本更新啦，点我更新'},
            ]
        });
        // 导航
        this.$store.dispatch('update',{
            'key':'breadcrumb',
            'value':[]
        });
    },
    methods: {
    }
};

var DataAnalysis = {
    template : heredoc(function () {
        /*
           <div>
              数据分析
            </div>
         */
    }),
    props:{
    },
    data:function(){
        return { 
        }
    },
    created:function(){
        // 改变左侧内容
        this.$store.dispatch('update',{
            'model':'leftMenu',
            'key':'displayType',
            'value':'menu'
        });
        this.$store.dispatch('update',{
            'model':'leftMenu',
            'key':'menuList',
            'value':[
                {'icon':'ios-navigate','name':'list','list':{
                    'name':'1',
                    'title':'API接口请求数量',
                    'content':[
                        {'name':'1-1','text':'列表'}
                    ]
                }},
            ]
        });
        // 导航
        this.$store.dispatch('update',{
            'key':'breadcrumb',
            'value':[
                {'href':'/DataAnalysis','name':'数据分析'}
            ]
        });
    },
    methods: {
    }
};
var web_file_admin = {
    template : heredoc(function () {
        /*
           <div class="web-admin">
                <!--显示加载-->
               <!--<Spin size="large" fix v-if="spinShow"></Spin>-->
               <Col class="demo-spin-col" span="8" v-if="spinShow">
                    <Spin fix>
                        <Icon type="load-c" size=60 class="demo-spin-icon-load"></Icon>
                        <div>正在加载...</div>
                    </Spin>
                </Col>
                <div class="layout-content-header">
                    <div class="editor-head-nav">
                        <li @click="plusWindows">
                            <poptip trigger="hover" content="添加窗口" placement="right-end">
                                <Icon type="plus"></Icon>
                            </poptip>
                        </li>
                        <li @click="delFileTip = true">
                            <poptip trigger="hover" content="删除文件" placement="right-end">
                                <Icon type="trash-a"></Icon>
                            </poptip>
                        </li>
                        <li @click="saveFile">
                            <poptip trigger="hover" content="保存文件" placement="right-end">
                                <Icon type="android-done"></Icon>
                            </poptip>
                        </li>
                        <li>
                            <p v-html="'正在编辑文件:'+$store.state.editor[$store.state.editorIndex].filename"></p>
                        </li>
                    </div>
                </div>
                <Modal v-model="delFileTip" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="information-circled"></Icon>
                        <span>删除确认</span>
                    </p>
                    <div style="text-align:center">
                        <p>删除{{$store.state.editor[$store.state.editorIndex].filename}}文件</p>
                        <p>是否继续删除？</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :loading="delFileTipLoading" @click="delFile">删除</Button>
                    </div>
                </Modal>
                <!--代码输入框（注意请务必设置高度，否则无法显示）-->
                <Carousel v-model="$store.state.editorIndex">
                    <Carousel-item v-for="item in codeList">
                        <pre :id="item.id" class="ace_editor" :style="{height:$store.state.editor[0].height+'px'}" style="margin:0px;">
                            <textarea class="ace_text-input"></textarea>
                        </pre>
                    </Carousel-item>
                </Carousel>
                <div class="editor-tip" v-if=" false === $store.state.editor[0].open" v-html="$store.state.editor[0].closeTip"></div>
            </div>
         */
    }),
    props:{
    },
    data:function(){
        return {
            delFileTip:false,
            delFileTipLoading:false,
            spinShow:true,
            resData : {},
            lists:[],
            codeList:[
                {'id':'code-1'}
            ]
        }
    },
    created:function(){
        this.$store.dispatch('update',{
            'model':'',
            'key':'tree_mod',
            'value':'web_file'
        });
        this.$store.dispatch('update',{
            'model':'leftMenu',
            'key':'displayType',
            'value':'tree'
        });
        this.$store.dispatch('update',{
            'key':'breadcrumb',
            'value':[
                {'href':'/web_file_admin','name':'Web在线文件管理器'}
            ]
        });
        this.getFileAndFolder('/');
    },
    mounted:function () {
        this.forEditObj();
    },
    updated:function () {
        var obj = document.getElementsByClassName('layout-content');
        this.$store.state.editor[0].height = obj[0].offsetHeight - 50 ;
    },
    methods: {
        saveFile:function () {
            var file = this.$store.state.editor[this.$store.state.editorIndex];
            console.log(file);
        },
        forEditObj:function () {
            var length = this.codeList.length;
            if ( 0 < length){
                for (var i = 0; i < length;i++){
                    this.initEditObj(this.codeList[i]['id'],i);
                }
            }
        },
        delFile : function () {
            this.delFileTipLoading = true;
            var _vm = this;
            // 获取删除文件的对象
            var file = this.$store.state.editor[this.$store.state.editorIndex];
            console.log(file);
            $.ajax({
                url: '/admin/delFile',
                type: 'post',
                data: {'file': file.filename},
                dataType: 'json',
                success: function (msg) {
                    _vm.delFileTipLoading = false;
                    _vm.delFileTip = false;
                    _vm.$Message.success(msg['msg']);
                },
                error: function () {
                    _vm.delFileTipLoading = false;
                    _vm.delFileTip = false;
                    _vm.$Message.success('请检查网络');
                }
            });
        },
        plusWindows:function(){
            this.spinShow = true;
            var obj = document.getElementsByClassName('layout-content');
            // 添加对象
            var number = this.codeList.length+1;
            this.codeList.push({'id':'code-'+number});
            var index = this.codeList.length-1;
            var newArray = this.$store.state.editor;
            newArray.push({
                open:true,
                closeTip:'暂无打开的文件...',
                height:obj[0].offsetHeight,
                filename:'新建文件 - ' + number,
                content:'hello word file windows ' + number,
                theme:'twilight',
                language:'txt', // 默认打开的文件
            });
            this.$store.dispatch('update',{
                'model':'',
                'key':'editor',
                'value':newArray
            });
            var _vm = this;
            setTimeout(function () {
                _vm.initEditObj('code-'+number,index);
                _vm.$store.state.editorIndex = index;
                _vm.spinShow = false;
            },1000)
        },
        initEditObj:function (name,index) {
            var editor = ace.edit(name);
            editor.setValue(this.$store.state.editor[index].content);
            editor.setTheme("ace/theme/" + this.$store.state.editor[index].theme);
            editor.session.setMode("ace/mode/" + this.$store.state.editor[index].language);
            editor.moveCursorTo(0, 0);
            editor.setFontSize(18);
            editor.setReadOnly(false);
            editor.setOption("wrap", "free");
            ace.require("ace/ext/language_tools");
            editor.setHighlightActiveLine(true);
            editor.setOptions({
                enableBasicAutocompletion: true,
                enableSnippets: true,
                enableLiveAutocompletion: true
            });
        },
        changeTreeFile:function (_this,res) {
            // 打开文件
            res['path'] = '/' === res['path'] ? '' : res['path'];
            var _vm = _this;
            $.ajax({
                url: '/admin/getFileContent',
                type: 'post',
                data: {'file':res['path']+'/'+res['title']},
                dataType: 'json',
                success: function (msg) {
                    var index = _vm.$store.state.editorIndex;
                    var  editor = _vm.$store.state.editor;
                    editor[index].content = msg['data']['content'];
                    editor[index].language = getFileType(res['title']);
                    editor[index].filename = res['path']+'/'+res['title'];
                    _vm.$store.dispatch('update',{
                        'model':'',
                        'key':'editor',
                        'value':editor
                    });
                    var number = index + 1;
                    var editorObj = ace.edit('code-'+number);
                    editorObj.setValue(editor[index].content);
                    editorObj.setTheme("ace/theme/" + _vm.$store.state.editor[index].theme);
                    editorObj.session.setMode("ace/mode/" + editor[index].language);
                    editorObj.moveCursorTo(0, 0);
                    editorObj.setFontSize(18);
                    editorObj.setReadOnly(false);
                    editorObj.setOption("wrap", "free");
                    ace.require("ace/ext/language_tools");
                    editorObj.setHighlightActiveLine(true);
                    editorObj.setOptions({
                        enableBasicAutocompletion: true,
                        enableSnippets: true,
                        enableLiveAutocompletion: true
                    });
                },
                error: function () {

                }
            });
        },
        changeTreeDir : function(_this,res){
            if ('undefined'  !== typeof _this.$store.state.tmp.web_dir){
                if ( -1 < $.inArray(res['title'],_this.$store.state.tmp.web_dir)){
                    console.log('重复');
                    return;
                }else{
                    var newDir = _this.$store.state.tmp.web_dir;
                    newDir.push(res['title']);
                    _this.$store.dispatch('update',{'model':'tmp','key':'web_dir','value':newDir});
                }
            }else{
                var array = new Array();
                array.push(res['title']);
                _this.$store.dispatch('update',{'model':'tmp','key':'web_dir','value':array});
            }
            _this.index++;
            console.log('发送到服务器途径=>'+res['path']);
            res['children'] = [];
            var data = {
                'path':res['path']
            };
            var _vm = _this;
            $.ajax({
                url: '/admin/getFileAndFolder',
                type: 'post',
                data: data,
                dataType:'json',
                success: function (msg) {
                    var dir = msg['data']['dir'];              
                    $.each(dir,function (key,val) {
                        res['children'].push({
                            title   : val['name'],
                            path    : val['path'],
                            dir     : true,
                            children: [{
                                title: '<span style="color: red">正在加载...</span>',
                            }]
                        });
                    });
                    var file = msg['data']['file'];
                    var file_length = msg['data']['file'].length;
                    for (var i =0;  i  < file_length;i++){
                        res['children'].push({
                            title   : file[i],
                            path    : res['path'],
                            dir     : false,
                        });
                    }
                },
                error:function () {
                    this.$Modal.info({
                        title: '连接网络错误',
                        content: '请检查你的网络是否连接成功...'
                    });
                }
            })
        },
        getFileAndFolder:function (path) {
            var data = {
                'path':enCrypt(path),
                'serverDecodeField':enCrypt('path')
            };
            var _vm = this;
            $.ajax({
                url: '/admin/getFileAndFolder',
                type: 'post',
                data: data,
                dataType:'json',
                success: function (msg) {
                    _vm.resData = msg['data'];
                    var lists = [];
                    var dir = msg['data']['dir'];
                    $.each(dir,function (key,val) {
                        lists.push({
                            title   :   val['name'],
                            path    :   val['path'],
                            dir     : true,
                            children: [{
                                title: '<span style="color: red">正在加载...</span>',
                            }]
                        });
                    });
                    var file = msg['data']['file'];
                    var file_length = msg['data']['file'].length;
                    for (var i =0;  i  < file_length;i++){
                        lists.push({
                            title: file[i],
                            path    : '/',
                            dir     : false,
                        });
                    }
                    var base = [{
                        expand: true,
                        title: '/',
                        children: lists
                    }];
                    _vm.$store.dispatch('update',{
                        'model':'leftMenu',
                        'key':'tree',
                        'value':base
                    });
                    // 关闭加载
                    _vm.spinShow = false;
                },
                error:function () {
                    _vm.spinShow = false;
                    this.$Modal.info({
                        title: '连接网络错误',
                        content: '请检查你的网络是否连接成功...'
                    });
                }
            })
        }
    }
};
const routes = [
	{ path: '/', component: index },
	{ path: '/DataAnalysis', component: DataAnalysis },
    { path: '/web_file_admin', component: web_file_admin },
];
