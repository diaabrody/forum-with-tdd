<template>
    <div v-if="shouldPaginate"  class="page">
        <nav aria-label="...">
            <ul class="pagination">
                <li class="page-item" :class="{'disabled': !prev}" @click.prevent="changPage('prev')">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
<!--                <li class="page-item"><a class="page-link" href="#">1</a></li>-->
<!--                <li class="page-item active" aria-current="page">-->
<!--                    <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>-->
<!--                </li>-->
<!--                <li class="page-item"><a class="page-link" href="#">3</a></li>-->
                <li class="page-item" :class="{'disabled': !next}"  @click.prevent="changPage('next')">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
    export default {
        props:['resultSet'],
        data() {
            return {
                page: 1 ,
                next:'',
                prev:'' ,
                last:''

            }
        },
        watch:{
            resultSet(){
                this.page = this.resultSet.current_page;
                this.prev = this.resultSet.prev_page_url;
                this.next = this.resultSet.next_page_url;
                this.last = this.resultSet.last_page;
            } ,
            page(){
                this.$emit('pageChanged'  , this.page);
            }

        },
        created() {

        },
        methods:{
          changPage(type){
              let newPage = this.page;
              switch(type){
                  case 'next':
                      newPage++
                      break;
                  case 'prev':
                      newPage--
                      break;
              }
              if(newPage > this.last ||newPage <1){
                  return;
              }
              this.page = newPage;
         }



        },
        computed:{
            shouldPaginate(){
                return !! this.next || !! this.prev
            }
        }
    }
</script>
<style scoped>
.page{
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
}
</style>
