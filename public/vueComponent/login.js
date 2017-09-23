var component_login = {
    template : heredoc(function () {
        /*
        <div class="login">
            <div class="login-bg"></div>
            <div class="login-head"><h1>NeApi后台登录管理</h1></div>
            <div class="login-content">
                <div class="from verify-from">
                    <i-form ref="formInline" :model="formInline" :rules="ruleInline" >
                        <form-item prop="user" label="账户">
                            <i-input type="text" v-model="formInline.user" placeholder="请输入管理员账户"></i-input>
                        </form-item>
                        <form-item prop="password" label="密码">
                            <i-input type="password" v-model="formInline.password" placeholder="密码"></i-input>
                        </form-item>
                        <form-item prop="verify" label="验证码">
                            <i-input type="text" v-model="formInline.verify" placeholder="请输入右边的验证码"></i-input>
                            <div class="verify-img">
                                <img :src="verify" alt="" @click="onReplaceCode">
                            </div>
                        </form-item>
                        <div style="margin-top: 30px;">
                            <form-item>
                                <i-button type="primary" size="large" long @click="onLogin" :loading="formInline.loading">登录</i-button>
                            </form-item>
                        </div>
                    </i-form>
                </div>
            </div>
        </div>
         */
    }),
    props:{
        verify : {
            type : String,
            default:'',
        },
        done:Function
    },
    data:function(){
        return {
            formInline: {
                loading:false,
                user: '',
                password: '',
                verify:'',
            },
            ruleInline: {
                user: [
                    { required: true, message: '请填写用户名', trigger: 'blur' }
                ],
                password: [
                    { required: true, message: '请填写密码', trigger: 'blur' },
                    { type: 'string', min: 6, message: '密码长度不能小于6位', trigger: 'blur' }
                ],
                verify: [
                    { required: true, message: '请填写验证码', trigger: 'blur' },
                ],
            },
        }
    },
    methods: {
        onLogin : function(){
            this.formInline.loading = true;
            var data = {
                'username':enCrypt(this.formInline.user),
                'pass':enCrypt(this.formInline.password),
                'verify':enCrypt(this.formInline.verify),
                'serverDecodeField':enCrypt('username,pass,verify')
            };
            var _vm = this;
            $.ajax({
                url: '/admin/login',
                data: data,
                type: 'post',
                dataType:'json',
                success: function (msg) {
                    _vm.done(msg['code']);
                    _vm.formInline.loading = false;
                    _vm.$Modal.warning({
                        title: '登录提示',
                        content: msg['msg']
                    });
                },
                error:function () {
                    _vm.formInline.loading = false;
                    _vm.$Modal.warning({
                        title: '登录提示',
                        content: '连接网络失败'
                    });
                }
            })
        },
        onReplaceCode : function(){
            var _vm = this;
            $.ajax({
                url: '/admin/verify',
                type: 'get',
                dataType:'json',
                success: function (msg) {
                    _vm.verify = msg['data']['src'];
                }
            })
        }
    }
};


