<?php
include("conexion.php");

/*Guardar*/
if(isset($_POST['guardar'])){
    $fecha = $_POST['fecha'];
    $tipo = $_POST['tipo'];
    $marca = $_POST['marca'];
    $costo = $_POST['costo'];
    $cantidad = $_POST['cantidad'];
    mysqli_query($conexion,"INSERT INTO libretas
    (fecha,tipo,marca,costo,cantidad)
    VALUES
    ('$fecha','$tipo','$marca','$costo','$cantidad')");
    header("Location: libretas.php");
    exit();
}

/*Eliminar*/
if(isset($_GET['eliminar'])){
    $id = $_GET['eliminar'];
    mysqli_query($conexion,"DELETE FROM libretas WHERE id='$id'");

    // Obtener el siguiente ID disponible
    $resultado = mysqli_query($conexion,"SELECT IFNULL(MAX(id),0)+1 AS siguiente FROM libretas");
    $fila = mysqli_fetch_assoc($resultado);
    $siguiente = $fila['siguiente'];

    // Reiniciar AUTO_INCREMENT
    mysqli_query($conexion,"ALTER TABLE libretas AUTO_INCREMENT=$siguiente");
    header("Location: libretas.php");
    exit();
}

/*Variables*/
$id="";
$fecha="";
$tipo="";
$marca="";
$costo="";
$cantidad="";
$editar=false;

/*Cargar Datos*/
if(isset($_GET['editar'])){
    $editar=true;
    $id=$_GET['editar'];
    $consulta=mysqli_query($conexion,"SELECT * FROM libretas WHERE id='$id'");
    if($fila=mysqli_fetch_assoc($consulta)){
        $fecha=$fila['fecha'];
        $tipo=$fila['tipo'];
        $marca=$fila['marca'];
        $costo=$fila['costo'];
        $cantidad=$fila['cantidad'];
    }
}

/*Actualizar*/
if(isset($_POST['actualizar'])){
    $id=$_POST['id'];
    $fecha=$_POST['fecha'];
    $tipo=$_POST['tipo'];
    $marca=$_POST['marca'];
    $costo=$_POST['costo'];
    $cantidad=$_POST['cantidad'];
    mysqli_query($conexion,"UPDATE libretas SET
    fecha='$fecha',
    tipo='$tipo',
    marca='$marca',
    costo='$costo',
    cantidad='$cantidad'
    WHERE id='$id'");
    header("Location: libretas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libretas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<nav>
    <div class="logo">
        <h1>Papelería</h1>
    </div>
    <div class="menu">
        <a href="libretas.php">Libretas</a>
        <a href="colores.php">Colores</a>
        <a href="lapices.php">Lápices</a>
        <a href="mochilas.php">Mochilas</a>
    </div>
</nav>

<form method="POST">

    <h2>Registro de Libretas</h2>

    <!-- El ID debe ir oculto -->
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Fecha</label>
    <input type="date" name="fecha" required value="<?php echo $fecha; ?>">

    <label>Tipo</label>
    <input type="text" name="tipo" required value="<?php echo $tipo; ?>">

    <label>Marca</label>
    <input type="text" name="marca" required value="<?php echo $marca; ?>">

    <label>Costo</label>
    <input type="number" step="0.01" name="costo" required value="<?php echo $costo; ?>">

    <label>Cantidad</label>
    <input type="number" name="cantidad" required value="<?php echo $cantidad; ?>">

    <?php if($editar){ ?>
        <input type="submit" name="actualizar" value="Actualizar Producto">
    <?php }else{ ?>
        <input type="submit" name="guardar" value="Guardar Producto">
    <?php } ?>

</form>

<hr>

<h2>Registros</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Tipo</th>
        <th>Marca</th>
        <th>Costo</th>
        <th>Cantidad</th>
        <th>Acciones</th>
    </tr>
<?php

$consulta=mysqli_query($conexion,"SELECT * FROM libretas ORDER BY id DESC");
while($fila=mysqli_fetch_assoc($consulta))
{
?>

<tr>
    <td><?php echo $fila['id']; ?></td>
    <td><?php echo $fila['fecha']; ?></td>
    <td><?php echo $fila['tipo']; ?></td>
    <td><?php echo $fila['marca']; ?></td>
    <td>$<?php echo $fila['costo']; ?></td>
    <td><?php echo $fila['cantidad']; ?></td>
    <td>
        <a class="editar" href="libretas.php?editar=<?php echo $fila['id']; ?>">
            Editar
        </a>
        |
        <a class="eliminar"
        href="libretas.php?eliminar=<?php echo $fila['id']; ?>"
        onclick="return confirm('¿Deseas eliminar este registro?');">
            Eliminar
        </a>
    </td>
</tr>

<?php
}
?>

</table>
</body>
</html>