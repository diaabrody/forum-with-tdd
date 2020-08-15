<template>
    <div>
        <button :class="classes" @click="subscribe">{{btnName}}</button>
    </div>
</template>
<script>
    export default {
        props:{
            'active':{
                required:true ,
                type:Boolean
            }
        },
        data(){
            return{
                isActive:this.active
            }
        },
        methods:{
           async subscribe(){
               const tempActive =this.isActive;
                const requestType = this.isActive?'delete':'post';
                try {
                    this.isActive = !this.isActive;
                    await axios[requestType](location.pathname + '/subscriptions');

                }catch (e) {
                   this.isActive = tempActive;
                }
            }
        },
        computed:{
            classes(){
                return ['btn ' , this.isActive?'btn-danger':'btn-success']
            },
            btnName(){
                return this.isActive?'subscribed':'subscribe';
            }

        }
    }
</script>
