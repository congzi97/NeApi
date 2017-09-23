var component_list = {
    template : heredoc(function () {
        /*
            <div class="index-content">
              <div class="i-c-bg"></div>
              <div class="i-c-head"><h1 style="color: #fff;">NeApi后台管理系统</h1></div>
              <div class="i-c-content">
                <a v-for="item in list" :href="item.href">
                  <card style="width:320px;background: transparent;float: left;margin-left: 20px;margin-bottom: 20px;">
                    <div style="text-align:center">
                      <img :src="item.src" style="width: 200px;height: 100px;">
                      <h3>{{item.name}}</h3>
                    </div>
                  </card>
                </a>
              </div>
            </div>
         */
    }),
    props:{
    },
    data:function(){
        return {
            list:[
                {'name':'首页 - 数据分析、数据查看','href':'/admin/admin.html','src':'/static/image/1.jpg'},
            ],
        }
    },
    methods: {
    }
};


