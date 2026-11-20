<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" 
          integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  </head>
  <style type="text/css">
    .carousel-item > div {
      height: 50%;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .slide {
        background: "#535353";
    }
    .caro
 
  </style>
  <body>
   <section class=marcelo>
    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active justify-content-center">
          <img src="./imagenes/fondoAlicante.jpg" class="d-block mx-auto" alt="...">
        </div>
        <div class="carousel-item justify-content-center">
          <img src="./imagenes/globoTerraqueo.jpg" class="d-block mx-auto" alt="...">
        </div>
        <div class="carousel-item justify-content-center">
          <img src="./imagenes/fondoMarbella.jpg" class="d-block mx-auto" alt="...">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" 
              data-bs-target="#carouselExampleControls" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: dimgrey"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" 
              data-bs-target="#carouselExampleControls" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true" style="background-color: dimgrey"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>
   </section>
      
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  </body>
</html>