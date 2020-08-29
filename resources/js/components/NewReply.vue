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
                        @input="onChangeHandler"
              ></textarea>
               <span class="help-block text-danger" v-if="error">this field is required</span>
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
    import 'jquery.caret';
    import 'at.js';
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
        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 2000,
                callbacks: {
                    remoteFilter: function(query, callback) {
                        $.getJSON("/api/users", {name: query}, function(usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
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
                            const errorField = Object.keys(response.data.errors)[0];
                            const errorFieldMessage = response.data.errors[errorField];
                            const errorMessage = `${errorField} Field  ${errorFieldMessage}`;
                            if (response.status === 422){
                                flash(errorMessage ,'danger');
                            }

                        });
                }else{
                    this.error = true;
                }

            },
            onChangeHandler(){
                this.error = false
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
