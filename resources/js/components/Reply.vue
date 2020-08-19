<template>
<div>
    <div class="card" style="margin-bottom:10px ; margin-top: 10px">
        <div class="card-header" :id="'replay-'+reply.id">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name">
                    </a>
                    <span class="mx-2" v-text="' '+ created_at + ' ...'" ></span>
                </h5>
                <div v-if="signedIn">
                  <favourite :reply="reply" v-cloak></favourite>
                </div>

            </div>


        </div>
        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea  class="form-control" v-model="body" ></textarea>
                </div>
                <button class="btn btn-sm btn-primary"  @click="update">Update</button>
                <button class="btn btn-sm btn-link" @click="cancel">Cancel</button>

            </div>

            <div v-else v-text="body">
            </div>
        </div>
        <div class="card-footer level">
            <div v-if="canEdit">
                <button class="btn btn-secondary btn-sm"  @click="edit" v-if="!editing" >Edit</button>

                <button type="button" @click="delReplay" class="btn btn-danger btn-sm">Delete</button>

            </div>
        </div>
    </div>
</div>
</template>
<script>
    import Favourite from "./Favourite";
    import moment from 'moment';
    export default {
        components:{
            Favourite
        },
        props:{
            'reply':{
                'required':true ,
                'type':Object
            }
        },
        created() {
        },
        computed:{
            signedIn(){
                return window.App.signedIn;
            }  ,
            canEdit(){
                return this.authorize((user)=> user.id === this.reply.user_id);
            } ,
            created_at(){
                return moment(this.reply.created_at)
                    .fromNow();
            }
        },

        data() {
            return {
                'editing':false,
                'body':this.reply.body ,
                'cachedBody':''

            }
        },
        methods:{
            update(){
                if(this.body.trim() !== ''){
                    axios.patch("/replay/"+this.reply.id , {
                        'body':this.body
                    }).then(()=>{
                        this.editing = false ;
                        flash('replay updated !');
                    }).catch(({response})=>{
                       flash(response.data.message , 'danger');
                    });
                }
            },
            cancel(){
                if(this.body.trim() === ''){
                    this.body = this.cachedBody;
                }
                this.editing = false;
            },
            edit(){
                 this.cachedBody = this.body;
                this.editing = true;
            },
            delReplay(){
                axios.delete("/replay/"+this.reply.id)
                    .then(()=>{
                        this.$emit('removed');
                        // $(this.$el).fadeOut("slow", () => {
                        //     flash('Your reply has been deleted.');
                        // });
                    })
                    .catch((error)=>{console.log(error)})
            }

        },
    }
</script>
