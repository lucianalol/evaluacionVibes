<?php
$conexion = mysqli_connect("localhost", "root", "", "vibesdb") or die("Error al conectar a la base de datos");

if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $contrasenia = $_POST['contrasenia'];
    $correo = $_POST['correo']; // Agregamos el campo "correo" al registro
    include("redimensionarImg.php");
    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { // Cambiamos 'foto' a 'imagen' aquí
        move_uploaded_file($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        $imagen = redimensionarImg($_FILES['imagen']['name'], 100, 100); // Cambiamos 'foto' a 'imagen' aquí
        unlink($_FILES['imagen']['name']);
    } else {
        $imagen = "default.jpeg"; // Cambiamos 'foto' a 'imagen' aquí
    }

    $sql = "INSERT INTO usuarios (imagen, nombre, contrasenia, correo) VALUES ('$imagen', '$nombre', '$contrasenia', '$correo')";
    $insertar = mysqli_query($conexion, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="styles.css" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> 
    <title>CRUD de Usuarios</title> 
</head>
<body>
<h1>CRUD DE USUARIOS</h1>
<form action="" method="post" enctype="multipart/form-data">
    <label for="1">Usuario</label>
    <input type="text" name="nombre" id="1"> 
    <label for="2">Contraseña</label>
    <input type="password" name="contrasenia" id="2">
    <label for="3">Imagen</label>
    <input type="file" name="imagen" id="3">
    <label for="4">Correo</label>
    <input type="email" name="correo" id="4"> <!-- Nuevo campo de correo -->
    <input type="submit" name="registrar" value="Registrar">
</form>

<?php
echo '<h1>EDITAR PERFIL</h1>';
if (isset($_GET['id_editar'])) {
    $id_editar = $_GET['id_editar'];
    $sql_c = "SELECT * FROM usuarios WHERE id='$id_editar'";
    $consulta_e = mysqli_query($conexion, $sql_c);
    $registro_e = mysqli_fetch_assoc($consulta_e);
    echo '<form action="" method="post" enctype="multipart/form-data">
    <label for="1">Usuario</label>
    <input type="text" name="nombre" id="1" value="' . $registro_e['nombre'] . '">
    <label for="2">Contraseña</label>
    <input type="password" name="contrasenia" id="2" value="' . $registro_e['contrasenia'] . '">
    <label for="3">Imagen</label>
    <input type="file" name="foto" id="3">
    <label for="4">Correo</label>
    <input type="email" name="correo" id="4" value="' . $registro_e['correo'] . '"> <!-- Nuevo campo de correo -->
    <input type="submit" name="actualizar" value="Actualizar">

    <input type="hidden" name="foto_previa" value="' . $registro_e['foto'] . '">
    <img src="imagenes/' . $registro_e['foto'] . '">
</form>';
}

if (isset($_POST['actualizar'])) {
    $nombre_e = $_POST['nombre'];
    $contrasenia_e = $_POST['contrasenia'];
    $foto_previa = $_POST['foto_previa'];

    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
        move_uploaded_file($_FILES['foto']['tmp_name'], 'imagenes/' . $_FILES['foto']['name']);
        $foto = $_FILES['foto']['name'];
        unlink('imagenes/' . $foto_previa);
    } else {
        $foto = $foto_previa;
    }

    $sql_update = "UPDATE usuarios SET nombre='$nombre_e', contrasenia='$contrasenia_e', foto='$foto' WHERE id='$id_editar'";
    $actualizar = mysqli_query($conexion, $sql_update);
}

if (isset($_GET['id_eliminar'])) {
    $id_eliminar = $_GET['id_eliminar'];
   
    // BORRAR LA FOTO DE LA CARPETA IMAGENES
    $foto_eliminar = "SELECT * FROM usuarios WHERE id='$id_eliminar'";
    $buscar_foto = mysqli_query($conexion, $foto_eliminar);
    $registro_f = mysqli_fetch_assoc($buscar_foto);
 
    unlink('imagenes/'. $registro_f['foto']);
    // BORRAR LA FOTO DE LA CARPETA IMAGENES
    
    $sql_borrar = "DELETE FROM usuarios WHERE id='$id_eliminar'";
    $eliminar = mysqli_query($conexion, $sql_borrar) ? print("<script>alert('Usuario eliminado');window.location='index.php'</script>"):print("<script>alert('Error al borrar');window.location='index.php'</script>");
}

echo '<h2>LISTADO DE USUARIOS</h2>';

$sql = "SELECT * FROM usuarios";
$consulta = mysqli_query($conexion, $sql);
if (mysqli_num_rows($consulta) == 0) {
    echo 'Tabla vacía';
} else {
    while ($registro = mysqli_fetch_assoc($consulta)) {
        echo '
            <div class="usuario">
                <img src="imagenes/'.$registro['foto'].'" alt="foto">
                <div class="datos">
                    <p>nombre: '.$registro['nombre'].'</p>
                    <p>contraseña: '.$registro['contrasenia'].'</p>
                    <p>correo: '.$registro['correo'].'</p> <!-- Mostramos el correo -->
                    <div class="buttons">
                        <a href="index.php?id_editar=' .$registro['id'].'&&nombre=' .$registro['nombre']. '&&contrasenia=' .$registro['contrasenia'].'"><button><i class="fa-solid fa-pen-to-square"></i></button></a>
                        <a href="index.php?id_eliminar='.$registro['id'].'"><button><i class="fa-solid fa-trash"></i></button></a>
                    </div>
                </div> 
            </div>';
    }
}
?>
</body>
</html>
