<template>
    <li class="dropdown">
        <a href="#">
            <i class="material-icons" v-text="bellIcon" @click="toggleBellIcon"></i>
        </a>
        <div class="dropdown-menu" :style="{display:active?'block':'none'}"
             v-click-outside="closeMenu" v-if="active">
                <a v-for="notification in notifications"
                   v-text="cutTitle(notification.data.message)"
                   :href="notification.data.link"
                   :class="classes(notification)"
                   @click="markAsRead(notification.id)"
                ></a>
            <a v-if="notifications.length ===0"> No Notifications Found</a>
        </div>
    </li>
</template>

<style scoped>
    .unread{
        background-color: #E5EAF2;
    }
</style>
<script>
    import Vue from 'vue'
    import vClickOutside from 'v-click-outside'

    Vue.use(vClickOutside)

    export default {
        data(){
            return {
                notifications:[],
                unReadNotification:false ,
                active:false
            };
        },
        methods:{
           async markAsRead(id){
                try{
                    await axios.delete(`/profiles/${window.App.user.name}/notifications/${id}`);
                }catch (e) {
                    console.log(e);
                }
            } ,
            cutTitle(oldTitle){
               if (!oldTitle)
                   return '';
               try {
                   const title = (oldTitle.length>40)
                       ?`${oldTitle.substr(0 ,20)}....`
                       :oldTitle;
                   return title;
               }catch (e) {
                   console.log(e);
               }

            },
            toggleBellIcon(){
               this.active = !this.active;
            } ,
            closeMenu(){
               this.active = false;
            }
        },
        computed:{
            classes() {
                return notification => (['dropdown-item' ,(!notification.read_at)?'unread':'' ])
            },
            bellIcon(){
                return this.active?'notifications':'notifications_none'
            }
        },
        async created() {
            try{
                const {data:notifications}= await axios.get(`/profiles/${window.App.user.name}/notifications`);
                this.notifications = notifications;

            }catch (e) {
                console.log(e);
            }
        }
    }
</script>
