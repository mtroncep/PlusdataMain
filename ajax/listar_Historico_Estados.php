<?php
session_start();

/* Connect To Database */
require_once("../conexion.php");
require_once("../cargarDatosFormulario.php");

// ---------------------------------------------------------------------
// 1) VALIDAR SI ES AJAX — SI NO, NO DEVOLVER NADA
// ---------------------------------------------------------------------
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($action !== 'ajax') {
    exit; // Corta TODO, no devuelve ni un byte
}

// ---------------------------------------------------------------------
// 2) CONFIGURAR PAGINACIÓN
// ---------------------------------------------------------------------
include 'pagination.php';

$page       = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
$per_page   = isset($_REQUEST['per_page']) ? intval($_REQUEST['per_page']) : 10;
$adjacents  = 4;
$offset     = ($page - 1) * $per_page;
$total_pages = 1;

// ---------------------------------------------------------------------
// 3) INICIAR BUFFER PARA CONTROLAR EXACTAMENTE LO QUE SE DEVUELVE
// ---------------------------------------------------------------------
ob_start();
?>

<!-- Scripts de traducción (solo se envían en AJAX) -->
<script src="trad/traduccion.php?lang=<?php echo $_SESSION['idioma']; ?>"></script>
<script>
function traducir(texto) {
    if (window.TRADUCCIONES?.[texto]) {
        return window.TRADUCCIONES[texto];
    }
    return texto;
}

function aplicarTraducciones() {
    document.querySelectorAll('[data-trad]').forEach(el => {
        const clave = el.getAttribute('data-trad');
        if (el.hasAttribute('placeholder')) {
            el.setAttribute('placeholder', traducir(clave));
        } else {
            el.innerText = traducir(clave);
        }
    });
}

window.onload = aplicarTraducciones;
</script>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th class="text-center"><span data-trad="Informe">Informe</span></th>
            </tr>
        </thead>
        <tbody>

<?php
// ---------------------------------------------------------------------
// 4) LISTAR ARCHIVOS
// ---------------------------------------------------------------------
$dir       = "../informes/estadoAnunciosGlobal/";
$dirLocal  = "informes/estadoAnunciosGlobal/";

chdir($dir);

// Buscar archivos del anunciante
$files = glob("*_" . $_SESSION['anunciante'] . "_*.pdf");

// Ordenar por fecha descendente
array_multisort(array_map('filemtime', $files), SORT_DESC, $files);

$finales = 0;

foreach ($files as $filename) {
    if ($finales >= 12) break;

    $partes_ruta = pathinfo($filename);
    $partes_file = explode("_", $partes_ruta['basename']);

    $finales++;
?>
        <tr class="col col-2">
            <td class="text-center">
                <a href="mostrarArchivo.php?path=<?php echo $dirLocal . $filename; ?>">
                    <?php echo $partes_file[3] . " " . $partes_file[4]; ?>
                </a>
            </td>
        </tr>
<?php
}
?>

        <tr>
            <td colspan="6">
                <?php
                $inicios = $offset + 1;
                $finales = $inicios + $finales - 1;

                echo "Mostrando $inicios al $finales registros";
                echo paginate($page, $total_pages, $adjacents);
                ?>
            </td>
        </tr>

        </tbody>
    </table>
</div>

<?php
// ---------------------------------------------------------------------
// 5) ENVIAR SOLO EL HTML GENERADO
// ---------------------------------------------------------------------
$html = ob_get_clean();
error_log("html de listarHistoricoEstados: ".$html,0);
echo $html;
exit;
