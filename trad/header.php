<?php if (isset($_SESSION['idioma'])): ?>
    <script src="trad/traduccion.php?lang=<?php $_SESSION['idioma'] ?>"></script>
    <script src="trad/traductor.js"></script>
<?php endif; ?>
