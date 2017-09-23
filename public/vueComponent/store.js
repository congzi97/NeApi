/**
 * state 储存变量
 * @type {Object}
 */
const states = {
    editorIndex:0,
    editor:[
        {
            open:true,
            closeTip:'暂无打开的文件...',
            height:500,
            filename:'创建文件 - 1',
            content:'编辑器默认内容 \r\n 如需编辑文件可以在左边点击文件打开\r\n',
            theme:'twilight',
            language:'txt', // 默认打开的文件
        }
    ],
    tmp:{},
    adminPower:false,// false 没有权限
    tree_mod:'web_file',
	leftMenu:{
		displayType:'alert',
		menuList:[
			//{'icon':'ios-navigate','name':'导航'},
			//{'icon':'ios-keypad','name':'模块'},
			//{'icon':'ios-analytics','name':'分析'},
            // {'icon':'ios-navigate','name':'list','list':{
             //    'name':'1',
             //    'title':'API接口请求数量',
             //    'content':[
             //        {'name':'1-1','text':'列表'}
             //    ]
            // }},
		],
		alertList:[
			//{'type':'success','title':'消息提示文案','content':''},
			//{'type':'success','title':'消息提示文案'},
		],
        tree:[]
	},
	breadcrumb:[]
};
/**
 * 接收需要更改state的值
 * @type {Object}
 */
const actions = {
	'update' : function (obj,params) {
		obj.commit('update',params);
    }
};
/**
 * 改变state值
 * @type {Object}
 */
const mutations = {
    update : function (state,params) {
        if (params['model'] && '' !== params['model'] ){
            state[params['model']][params['key']] = params['value'];
        }else{
            state[params['key']] = params['value'];
        }
    }
};

