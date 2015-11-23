<!-- Static navbar -->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Login Skell</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">        
        <?php
          if ( $this->session->id !== FALSE && $this->session->admin == 1 ){
            echo '<li><a href="/users">Users</a></li>';            
          }
        ?>
        <li><a href="/login/logout">Logout</a></li>                 
    </div><!--/.nav-collapse -->
  </div><!--/.container-fluid -->
</nav>