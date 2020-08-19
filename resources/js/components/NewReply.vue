<template>
   <div class="page">
<!--    @if (auth()->check())-->
       <div v-if="signedIn">
         <form>
           <div class="form-group">
              <textarea name="body"
                        id="body"
                        class="form-control"
                        v-model="body"
                        placeholder="Have something to say?"
                        rows="5"
                        required
                        @input="error = false"
              ></textarea>
               <span class="help-block text-danger" v-if="error">this field is reuired</span>


           </div>

           <button type="submit" class="btn btn-primary" @click.prevent="addReply">Post</button>
          </form>
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
                body:'' ,
                error:false

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
                            flash('added');
                        })
                        .catch(({response})=>{
                            flash(response.data.message ,'danger');
                        });
                }else{
                    this.error = true;
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
