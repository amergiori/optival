<template>
  <Layout>
    <div class="input-group mt-3 col-md-2">
      <div class="input-group-prepend">
        <label class="input-group-text" for="inputGroupSelect01">Sort By:</label>
      </div>
      <select class="custom-select" id="inputGroupSelect01" name="sort" v-model="sort" @change="handleSort">
        <option value="name">Name</option>
        <option value="rating">Rating</option>
        <option value="created_at">Date</option>
        <option value="thumbs">Thumbs Up</option>
      </select>
    </div>
    <div class="container d-flex justify-content-center">
      <div class="alert alert-danger mt-2 col-md-4 justify-content-md-center" id="thumbs-error" v-if="thumbs_error">{{thumbs_error}}</div>
    </div>
    <div class="container-fluid mt-2">
      <div class="card mr-3 mt-2 inline-flex" style="width: 18rem;" v-for="movie in movies" :key="movie.id">
        <img class="card-img-top" :src="movie.image" :alt="movie.name">
        <div class="card-body">
          <a v-if="user.user_role==='admin'" :href="'/edit/'+movie.id">✎</a>
          <small class="float-right">
            <span class="font-weight-bold">Premired:</span>
            {{(new Date(movie.created_at)).toLocaleDateString("en-US")}}
          </small>
          <h4 class="card-title font-weight-bold">{{movie.name}} </h4>
          <div class="card-text mb-2">
            <p><span>Our Rating: </span>{{movie.rating}}</p>
            <div>
              <button v-if="user && movie.id" class="align-text-bottom mr-2" @click="handleThumbsup(movie.id)">👍</button>
              <span >{{movie.thumbs}}</span> 
              <span>people like</span> 
              <span >{{movie.name}}</span> 
            </div>
          </div>
          <a target="_blank" :href="movie.url" class="btn btn-primary mt-2">Visit IMDB Page!</a>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script>
import Layout from '@/Layouts/MoviesLayout'

export default {
  props: [
    'movies',
    'user',
    'sort',
    'thumbs_error'
    ],

  date(){
    return{
      thumbsup: false,
    }
  },

  components: {
    Layout,
  },

  methods: {
    handleThumbsup: function(id) {
      let self = this;
      this.thumbsup = !this.thumbsup;
      axios.post('/thumbs', {movie_id: id, user_id: this.user.user_id})
      .then(function(res){
            if(res.data.status === 'success'){
              self.movies.forEach(movie => {
                movie.id === id ? movie.thumbs++ : ''
              });
            } else {
              self.thumbs_error = res.data.message; 
              setTimeout(function() {
                    self.thumbs_error = '';
                    self.$forceUpdate();
                }, 3000);
            }
          })
          .catch(function(e){
            console.log(e);
          });
    },
    handleSort(){
      let self = this;
       this.movies.sort((a,b) => {
        switch(self.sort){
          case 'rating':
          case 'thumbs':
              return b[sort] - a[sort];
          case 'name':
            return ( a[sort] < b[sort] ) ? -1 : 1 
          case 'created_at':
            return new Date(a[sort]) - new Date(b[sort])
        }
      });
    }
  },

  mounted: function(){
    var self = this;
  }
}
</script>
