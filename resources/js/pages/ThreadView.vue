<script>
    import Replies from "../components/Replies";
    import SubscribeButton from "../components/SubscribeButton";
    export default {
        components:{
            Replies ,
            SubscribeButton

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
