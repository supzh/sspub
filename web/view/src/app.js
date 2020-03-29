import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import GlobalPlugin from './global-plugin'
import App from './App.vue'

Vue.use(VueRouter)
Vue.use(VueResource)
Vue.use(GlobalPlugin)

//login
//password reset
//register
//user center
// - my subscriptions
// - - new subscription
// - - my subscriptions
// - my bill
// - - my bill
// - support
// - - library
// - - - q&a
// - - - guide
// - - - - windows
// - - - - os x
// - - - - android
// - - - - ios
// - - questions
// - my account
// - - info
// - - safe
// - - notify

//site
// - homepage
// - gift price
// - user center
// - node state
// - about
// - tos


const routes = [
    {
        path: '/',
        name: "welcome",
        component: require('./components/Welcome.vue')
    },
    {
        path: '/login',
        name: "login",
        component: require('./components/Login.vue')
    },
    {
        path: '/usercenter',
        name: "home",
        component: require('./components/MySub.vue')
    },
    {
    	path:'/usercenter/newsub',
    	name:'newsub',
    	component: require('./components/NewSub.vue')
    },
    {
    	path:'/usercenter/subdetail/:id',
    	name:'subdetail',
    	component: require('./components/SubDetail.vue')
    },
    {
        path: '/mybill',
        name: "mybill",
        component: require('./components/MyBill.vue')
    },
    {
        path: '/support',
        name: "support",
        component: require('./components/Support.vue')
    },
    {
        path: '/support/category/:name',
        name: "category",
        component: require('./components/Category.vue')
    },
    {
        path: '/support/guides',
        name: "guides",
        component: require('./components/Guides.vue')
    },
    {
        path: '/support/category',
        name: "categorylist",
        component: require('./components/CategoryList.vue')
    },
    {
        path: '/support/doc/:title',
        name: "doc",
        component: require('./components/Doc.vue')
    },
    {
        path: '/support/tickets',
        name: "tickets",
        component: require('./components/SupportTickets.vue')
    },
    {
        path: '/support/tickets/new',
        name: "newticket",
        component: require('./components/NewSupportTicket.vue')
    },
    {
        path: '/support/ticket/:id',
        name: "modifyticket",
        component: require('./components/NewSupportTicket.vue')
    },
    {
        path: '/usercenter/paysub/:id',
        name: "paysub",
        component: require('./components/PaySub.vue')
    },
    {
        path: '/usercenter/profile',
        name: "profile",
        component: require('./components/Profile.vue')
    },
    {
        path: '/usercenter/authentication',
        name: "authentication",
        component: require('./components/Authentication.vue')
    },
    {
        path: '/usercenter/settingsnotifications',
        name: "settingsnotifications",
        component: require('./components/SettingsNotifications.vue')
    },
    {
        path: '/usercenter/servers',
        name: "servers",
        component: require('./components/Servers.vue')
    },
    {
        path: '/usercenter/trafficlog/:id',
        name: "trafficlog",
        component: require('./components/TrafficLog.vue')
    },
    {
        path: '/admincenter',
        name: "admincenter",
        component: require('./components/admincenter/AdminCenter.vue')
    },
    {
        path: '/admincenter/giftmanage',
        name: "giftmanage",
        component: require('./components/admincenter/GiftManage.vue')
    },
    {
        path: '/admincenter/giftmanage/modifygift',
        name: "newgift",
        component: require('./components/admincenter/ModifyGift.vue')
    },
    {
        path: '/admincenter/giftmanage/modifygift/:id',
        name: "modifygift",
        component: require('./components/admincenter/ModifyGift.vue')
    },
    {
        path: '/admincenter/servermanage',
        name: "servermanage",
        component: require('./components/admincenter/ServerManage.vue')
    }   ,
    {
        path: '/admincenter/servermanage/modifyserver/:id',
        name: "modifyserver",
        component: require('./components/admincenter/ModifyServer.vue')
    }  ,
    {
        path: '/admincenter/servermanage/newserver',
        name: "newserver",
        component: require('./components/admincenter/ModifyServer.vue')
    },
    {
        path: '/admincenter/usermanage',
        name: "usermanage",
        component: require('./components/admincenter/UserManage.vue')
    }    ,
    {
        path: '/admincenter/usermanage/modifyuser/:id',
        name: "modifyuser",
        component: require('./components/admincenter/ModifyUser.vue')
    }   ,
    {
        path: '/admincenter/usermanage/newuser',
        name: "newuser",
        component: require('./components/admincenter/ModifyUser.vue')
    }
    ,
    {
        path: '/admincenter/trafficmanage',
        name: "trafficmanage",
        component: require('./components/admincenter/TrafficManage.vue')
    } ,
    {
        path: '/admincenter/friendcodemanage',
        name: "friendcodemanage",
        component: require('./components/admincenter/FriendCodeManage.vue')
    }   ,
    {
        path: '/admincenter/newfriendcode',
        name: "newfriendcode",
        component: require('./components/admincenter/ModifyFriendCode.vue')
    }   ,
    {
        path: '/admincenter/modifyfriendcode/:id',
        name: "modifyfriendcode",
        component: require('./components/admincenter/ModifyFriendCode.vue')
    },
    {
        path: '/admincenter/paymanage',
        name: "paymanage",
        component: require('./components/admincenter/PayManage.vue')
    } ,
    {
        path: '/admincenter/paymanage/:id',
        name: "payrecorddetail",
        component: require('./components/admincenter/PayRecordDetail.vue')
    } ,
    {
        path: '/admincenter/sitemanage',
        name: "sitemanage",
        component: require('./components/admincenter/SiteManage.vue')
    }    ,
    {
        path: '/admincenter/supportmanage',
        name: "supportmanage",
        component: require('./components/admincenter/SupportManage.vue')
    },
    {
        path: '/admincenter/supportmanage/modifycategory/:id',
        name: "modifycategory",
        component: require('./components/admincenter/ModifyCategory.vue')
    },
    {
        path: '/admincenter/supportmanage/modifycategory',
        name: "newcategory",
        component: require('./components/admincenter/ModifyCategory.vue')
    },
    {
        path: '/admincenter/supportmanage/modifydoc/:id',
        name: "modifydoc",
        component: require('./components/admincenter/ModifyDoc.vue')
    },
    {
        path: '/admincenter/supportmanage/modifydoc',
        name: "newdoc",
        component: require('./components/admincenter/ModifyDoc.vue')
    },

    {
        path: '/admincenter/supportmanage/ticketsmanage',
        name: "ticketsmanage",
        component: require('./components/admincenter/TicketsManage.vue')
    },

    {
        path: '/admincenter/supportmanage/ticket/:id',
        name: "adminmodifyticket",
        component: require('./components/admincenter/ReplyTicket.vue')
    }
]

const router = new VueRouter({
	mode: 'hash',
    linkActiveClass:'active',
    routes
})

gdata.bus = new Vue()

var app = new Vue(Vue.util.extend({ router }, App)).$mount('#app')
