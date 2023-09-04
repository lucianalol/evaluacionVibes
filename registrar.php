<?php

include 'conexion.php';

if (isset($_POST['registrar'])) {
    $Nbr_u = $_POST['usuario'];
    $Pass_u = $_POST['contrasenia'];
    include("redimensionarImg.php");
    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) { // Cambiamos 'foto' a 'imagen' aquí
        move_uploaded_file($_FILES['imagen']['tmp_name'], $_FILES['imagen']['name']);
        $Img_u = redimensionarImg($_FILES['imagen']['name'], 100, 100); // Cambiamos 'foto' a 'imagen' aquí
        unlink($_FILES['imagen']['name']);
    } else {
        $Img_u = "default.jpeg"; // Cambiamos 'foto' a 'imagen' aquí
    }
    $Pass_u= password_hash($contrasenia, PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (Img_u, Nbr_u, Pass_u) VALUES ('$Img_u', '$Nbr_u', '$Pass_u')";
    $insertar = mysqli_query($conexion, $sql)  ? print("<script>alert('Registro insertado');window.location='index.php'</script>")
    : print("<script>alert('Error');window.location='registrarse.php'</script>");
}
?>



</body>
</html>
