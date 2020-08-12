export default {
    data() {
        return {
            'items':this.data,
        }
    },
    methods:{
        remove(index){
            this.items.splice(index  , 1);
            this.$emit('removed');
        } ,
        add(reply){
            this.items.push(reply);
        }
    }
}
