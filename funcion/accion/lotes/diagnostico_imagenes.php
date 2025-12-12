<?php
echo "<h1>Diagnóstico de imágenes y permisos</h1>";

function titulo($txt) {
    echo "<h2 style='margin-top:30px;'>$txt</h2>";
}

function checkFolder($label, $path) {
    echo "<h3>$label</h3>";
    echo "Path: <code>$path</code><br>";

    if ($path === false) {
        echo "<span style='color:red;'>❌ realpath() devolvió false</span><br>";
        return;
    }

    if (!is_dir($path)) {
        echo "<span style='color:red;'>❌ No existe la carpeta</span><br>";
        return;
    }

    echo "<span style='color:green;'>✅ Carpeta encontrada</span><br>";

    // Lectura
    if (!is_readable($path)) {
        echo "<span style='color:red;'>❌ No tiene permisos de lectura</span><br>";
    } else {
        echo "<span style='color:green;'>✅ Permisos de lectura OK</span><br>";
    }

    // Escritura
    if (!is_writable($path)) {
        echo "<span style='color:red;'>❌ No tiene permisos de escritura</span><br>";
    } else {
        echo "<span style='color:green;'>✅ Permisos de escritura OK</span><br>";

        // Intento de crear archivo
        $testFile = $path . "/test_permiso_" . uniqid() . ".txt";
        $contenido = "Prueba de escritura " . date("Y-m-d H:i:s");

        if (file_put_contents($testFile, $contenido) !== false) {
            echo "<span style='color:green;'>✅ Se pudo crear un archivo de prueba</span><br>";

            // Intento de borrar
            if (unlink($testFile)) {
                echo "<span style='color:green;'>✅ Se pudo borrar el archivo de prueba</span><br>";
            } else {
                echo "<span style='color:red;'>⚠️ No se pudo borrar el archivo de prueba</span><br>";
            }

        } else {
            echo "<span style='color:red;'>❌ No se pudo crear un archivo de prueba</span><br>";
        }
    }

    // Listado de archivos
    $files = scandir($path);
    echo "<strong>Contenido:</strong><br><pre>";
    print_r($files);
    echo "</pre>";
}

titulo("📌 Rutas detectadas");

$paths = [
    "Ruta absoluta del script" => __FILE__,
    "Directorio actual" => __DIR__,
    "Intento 1" => __DIR__ . "/uploads",
    "Intento 2" => __DIR__ . "/../uploads",
    "Intento 3" => __DIR__ . "/boletaentrada/uploads",
    "Intento 4" => __DIR__ . "/../boletaentrada/uploads",
    "Intento 5 (realpath uploads)" => realpath(__DIR__ . "/uploads"),
    "Intento 6 (realpath ../boletaentrada/uploads)" => realpath(__DIR__ . "/../boletaentrada/uploads"),
];

echo "<pre>";
print_r($paths);
echo "</pre>";

titulo("📁 Verificando carpetas");

checkFolder("Intento 1", __DIR__ . "/uploads");
checkFolder("Intento 2", __DIR__ . "/../uploads");
checkFolder("Intento 3", __DIR__ . "/boletaentrada/uploads");
checkFolder("Intento 4", __DIR__ . "/../boletaentrada/uploads");

titulo("🖼 Probando rutas accesibles desde el navegador");

$testImages = [
    "/funcion/accion/boletaentrada/uploads/",
    "/boletaentrada/uploads/",
    "/uploads/",
    "uploads/",
];

foreach ($testImages as $url) {
    echo "<p>Probando: <code>$url</code></p>";
    echo "<img src='{$url}test.jpg' width='120' onerror=\"this.style.border='2px solid red'\">";
}

echo "<p>⚠️ Si ves bordes rojos, esa ruta NO funciona.</p>";
?>
