<template>
  <div>
    <form method="post" enctype="multipart/form-data" v-if="canUpdate">
      <image-upload @loaded="onLoad"></image-upload>
    </form>
    <img id="avatar" :src="avatar" width="100px" />
  </div>
</template>
<script>
import ImageUpload from "./ImageUpload";
export default {
  props: ["user"],
  components: {
    ImageUpload
  },
  computed: {
    canUpdate() {
      return this.authorize(signegUser => this.user.id === signegUser.id);
    }
  },

  data() {
    return {
      avatar: ""
    };
  },
  mounted() {
    this.avatar = this.user.avatar_path;
  },
  methods: {
    onLoad(avatar) {
      this.avatar = avatar.src;
      this.persist(avatar.file);
    },
    async persist(file) {
      let data = new FormData();
      data.append("avatar", file);
      try {
        await axios.post(`/api/users/${this.user.id}/avatar`, data);
        flash("added !");
      } catch (e) {
        flash("An Error occured please try again later", "danger");
      }
    }
  }
};
</script>
