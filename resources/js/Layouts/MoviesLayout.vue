<template>
<main>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <a href="/public" class="navbar-brand">Movies</a>  
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <a href="/login" class="nav-item nav-link" replace>Login</a>  
          <a href="/register" class="nav-item nav-link">Register</a>  
        </ul>
    </div>
    <form method="POST" @submit.prevent="logout">
        <button class="text-white">
            Logout
        </button>
    </form>
  </nav>

  <article>
    <slot />
  </article>
</main>
</template>

<script>
  export default {
    methods: {
            logout() {
                axios.post(route('logout').url()).then(response => {
                    window.location = '/';
                })
            },
        }
  }
</script>

