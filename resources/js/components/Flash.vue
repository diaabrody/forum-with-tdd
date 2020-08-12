<template>
        <div class=" alert alert-success alert-flash" role="alert" v-show="show">
            {{body}}
        </div>
</template>

<script>
    export default {
        props:['message'],
        data() {
            return {
                body: '',
                show: false
            }
        },
        created() {
            window.events.$on('flash' , (messagae)=>{
                this.display(messagae);
            });
            if(this.message){
                this.display(this.message);
            }
        },
        methods:{
            display(message){
                this.body = message;
                this.show = true
                this.hide();
            },
            hide(){
                setTimeout(()=>{this.show = false}, 5000);
            }
        },
    }
</script>
<style>
    .alert-flash {
        position: fixed;
        right: 25px;
        bottom: 25px;
    }
</style>
