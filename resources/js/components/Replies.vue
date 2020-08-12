<template>
<div>
    <Reply v-for="(reply , index) in items" :key="reply.id"  :reply="reply"
           @removed="remove(index)"/>
    <new-reply @created="add" ></new-reply>
   <paginator :resultSet="resultSet" @pageChanged="fetch"></paginator>

</div>
</template>
<script>
    import Reply from "./Reply";
    import NewReply from "./NewReply";
    import Collection from "../mixins/Collection";
    import Paginator from "./Paginator";
    export default {
        mixins: [Collection],
        components:{
            Reply ,
            NewReply ,
            Paginator
        },
        props:{

        },
        created() {
            this.fetch();
        },
        data() {
            return {
                resultSet:[]
            }
        },
        computed:{

        },
        methods:{
            fetch(page = 1){
                axios.get(this.url(page))
                    .then(({data})=>{
                        this.refresh(data);
                    })
                    .catch((error)=>console.log(error));
            },
            url(page){
                return  location.pathname + '/replies'+'?page='+page;
            } ,
            refresh(items=[]){
                this.items = items.data;
                this.resultSet = items;
            }

        },
    }
</script>
