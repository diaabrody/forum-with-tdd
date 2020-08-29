<template>
    <button type="button" class="btn btn-light" @click="toggle">
        <i class="material-icons  md-18 red" v-if="active" >favorite</i>
        <i class="material-icons  md-18" v-else >favorite_border</i>

        <span v-text="count"></span>
    </button>
</template>

<script>
    export default {
        props:{
            reply:{
                'required':true,
                'type':Object
            }
        },
        data() {
            return {
                count: isNaN(this.reply.favouritesCount)?0
                    :this.reply.favouritesCount,
                active:this.reply.isFavorited
            }
        },
        created() {

        },
        methods:{
            toggle(){
                if(this.active){
                    this.removeFav();
                }else {
                    this.createFav();
                }
            } ,
            async removeFav(){
                this.active = false;
                this.count--;
                try {
                    await axios.delete("/replies/"+this.reply.id+"/favourites")
                }catch (e) {
                    this.active = true;
                    this.count++;
                    console.log(e)
                }
            },
           async createFav(){
               this.active = true;
               this.count++;
               try {
                   await axios.post("/replies/"+this.reply.id+"/favourites")

               }catch (e) {
                   this.active = false;
                   this.count--;
                   console.log(e)
               }
            }
        },
    }
</script>
<style>
    .red{
        color: red;
    }

</style>
