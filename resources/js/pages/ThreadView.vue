<script>
    import Replies from "../components/Replies";
    export default {
        components:{
            Replies
        },
        props:{
            'replies_count':{
                type:Number ,
                required:true
            }


        },
        created() {
        },
        data() {
            return {
                'repliesCounts':this.replies_count
            }
        },
        methods:{
            remove(){
                this.repliesCounts-- ;
            }

        },
    }
</script>
