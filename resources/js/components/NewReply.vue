<template>
   <div class="page">
<!--    @if (auth()->check())-->
       <div v-if="signedIn">
           <div class="form-group">
              <textarea name="body" id="body" class="form-control"
                        v-model="body" placeholder="Have something to say?" rows="5"></textarea>
           </div>
           <button type="button" class="btn btn-primary" @click="addReply">Post</button>

       </div>
       <div v-else>
           <p class="text-center">Please <a href="/login">sign in</a> to participate in this
               discussion.</p>
       </div>

   </div>
</template>

<script>
    export default {
        props:[],
        data() {
            return {
                body:''

            }
        },
        created() {

        },
        methods:{
            addReply(){
                if(this.body.trim() !== ""){
                    axios.post(location.pathname + '/replies' , {
                        body: this.body
                    })
                        .then(({data})=>{
                            this.$emit('created' , data);
                            this.body='';
                        })
                        .catch(()=>{});
                }

            }

        },
        computed:{
            signedIn(){
                return window.App.signedIn;
            }
        }
    }
</script>
<style scoped>
    .page{
        margin: 0 auto;
    }

</style>
