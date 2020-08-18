<template>
        <div class=" alert alert-flash"
             role="alert"
             v-show="show"
             v-text="body"
             :class=" 'alert-'+type"
        >
        </div>
</template>

<script>
    export default {
        props:['data'],
        data() {
            return {
                body: '',
                type:'success',
                show: false
            }
        },
        created() {
            window.events.$on('flash' , (data)=>{
                this.display(data);
            });
            if(this.data){
                this.display(this.data);
            }
        },
        methods:{
            display({message , type}){
                this.body = message;
                this.type = type;
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
