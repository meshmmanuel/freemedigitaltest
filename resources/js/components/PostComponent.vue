<template>
  <div>
    <div class="card-body" v-for="post in userPosts" :key="post.id">
      <div class="card" style="margin-bottom: 10px">
        <img class="card-img-top" v-bind:src="`./posts/${post.content}`" />
        <b>Posted by: {{post.name}}</b>
        <div v-if="post.user_id === authUser.id"><a class="btn btn-info" href="/post/delete">Trash</a></div>
      </div>
    </div>
  </div>
</template>

<script src="js/app.js"></script>
<script>
export default {
  props: ["authUser", "posts"],
  data: function() {
    return {
      userPosts: this.posts
    };
  }
};
</script>
