<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<div class="container mt-3">
  <h3>Tooltip Example</h3>
  <div>
      <input type="checkbox" data-bs-toggle="tooltip" title="Bueno, este es un texto medio largo, para ver como se arma el globito y poder detectar que hace cuando ya no entra completo hacia arriba. Ahora le agregue mas texto a ver como lo maneja.">
        Ponete arriba
      </input>
  </div>
  <div>
      <button type="button" class="btn btn-primary" data-bs-toggle="tooltip" title="Hooray!">
        Hover over me!
      </button>
  </div>

</div>

<script>
// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    console.log(tooltipTriggerEl);
  return new bootstrap.Tooltip(tooltipTriggerEl);
})
</script>

</body>
</html>
