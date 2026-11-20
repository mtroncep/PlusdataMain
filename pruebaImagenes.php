<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
<style>
    #main {
       display:grid; 
       grid-template-columns:2fr;
       gap:1rem;
    }
    .contenedor {
       padding: 200px;
       display:grid;
       place-items: center;
    }
    .item {
       border:5px solid #333;
       display: grid;
       grid-template-columns: 1fr;
       grid-template-rows: 1fr;
       width: 50%;
       height: auto;
    }
    .item img {
       width: 100%;
       height: 100%;
       object-fit: cover;
       object-position: 50% 50%;
    }
</style>
</head>

<body>
    <div id="main">
       <div class="contenedor">
          <div id="uno" class="item">
             <img src="fotosPublicaciones/41018_1219a71a8f7b39ade55a5dcbb8b10125.jpg" alt="Imagen grande" />
          </div>
       </div>
<!--
        <div class="contenedor">
          <div id="dos" class="item">
             <img src="fotosPublicaciones/41013_CalleMonacojpg.jpg" alt="Imagen mediana" />
          </div>
       </div>
        <div class="contenedor">
          <div id="tres" class="item">
             <img src="fotosPublicaciones/41013_CalleMonacojpg.jpg" alt="Imagen pequeña" />
          </div>
       </div>
-->
    </div>
</body>
</html>