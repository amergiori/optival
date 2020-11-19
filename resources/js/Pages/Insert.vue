<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add A Movie
            </h2>
        </template>

        <div class="py-12">
          <div class="container d-flex justify-content-center">
            <div class="col-md-6 bg-white rounded py-2">
              <form action="\store" enctype="multipart/form-data" method="POST" @submit.prevent="addMovie">
                <div class="form-group">
                  <label for="name">Title</label>
                  <input type="text" class="form-control" id="name" placeholder="Title" v-model="form.name">
                </div>
                <div class="form-group">
                  <label for="url">Link</label>
                  <input type="text" class="form-control" id="url" placeholder="Link" v-model="form.url">
                </div>
                <div class="form-group">
                  <label for="image">Image</label>
                  <input type="file" class="form-control" id="image" placeholder="Title" @change="handleUploadFile">
                  <small class="text-danger" v-if="movie">No need to upload same image again</small>
                </div>
                <div class="form-group">
                  <label for="rating">Rating: {{form.rating}}</label>
                  <input type="range" min="0" max="10" step="0.1" class="form-control" id="rating" v-model="form.rating">
                </div>
                <button type="submit" class="btn btn-primary">Add Movie</button>
              </form>
            </div>
          </div>
        </div>
    </app-layout>
</template>

<script>
  import AppLayout from '@/Layouts/AppLayout'

  export default {
      components: {
          AppLayout,
      },
      props: ['errors', 'movie'],
      data(){
        return{
          form: {
            name: '',
            url: '',
            image: '',
            rating: 5
          },
        }
      },
      methods: {
        handleUploadFile(e){
          this.form.image = event.target.files[0];
        },
        addMovie(e){
          const data = new FormData();
          data.append('image', this.form.image);
          
          axios.post( 
            '/store', 
            this.form
          )
          .then(function(res){
            console.log(res);
          })
          .catch(function(e){
            console.log( e);
          });
        },  
        editMovie(){
          this.form.name = this.movie.name;
          this.form.url = this.movie.url;
          this.form.image = this.movie.image;
          this.form.rating = this.movie.rating;
        }
      },
      mounted(){
        if(this.movie){
          this.editMovie();
        }
      }
  }
</script>
