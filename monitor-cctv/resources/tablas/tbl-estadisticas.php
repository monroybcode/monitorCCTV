<?php
require '../../utils/funciones.php';

$restriccion = "";

if (isset($_POST['restriccion'])) {
    $restriccion =" and ". $_POST['restriccion'];
}

$gestion_pacientes = 0;         /* 1 */
$gestion_finanzas = 0;          /* 48 */
$gestion_almacenes = 0;         /* 82 */
$problemas_operativos = 0;      /* 102 */
$usuarios_permisos_sap = 0;     /* 108 */
$tecnologia_sistemas = 0;       /* 111 */

$gestion_pacientes = contar_tickets("../", " where categoria_1=1  ".$restriccion);

$gestion_finanzas = contar_tickets("../", " where categoria_1=48  " .$restriccion);

$gestion_almacenes = contar_tickets("../", " where categoria_1=82 " .$restriccion);

$problemas_operativos = contar_tickets("../", " where categoria_1=102  " .$restriccion);

$usuarios_permisos_sap = contar_tickets("../", " where categoria_1=108  " .$restriccion);

$tecnologia_sistemas = contar_tickets("../", " where categoria_1=111 " .$restriccion);
?>

<table class="table-s table table-striped text-muted">
    <thead>
        <tr>
            <th style="padding-left: 15px;">Nombre</th>
            <th style="text-align: center;">Total</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            <td style="padding-left: 15px;">Gestión de Pacientes</td>
            <td style="text-align: center;"><?php echo $gestion_pacientes; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">Gestión Finanzas</td>
            <td style="text-align: center;"><?php echo $gestion_finanzas; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">Gestión Almacenes</td>
            <td style="text-align: center;"><?php echo $gestion_almacenes; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">Problemas Operativos</td>
            <td style="text-align: center;"><?php echo $problemas_operativos; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">Usuarios y Permisos SAP</td>
            <td style="text-align: center;"><?php echo $usuarios_permisos_sap; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 15px;">Tecnología / Sistemas</td>
            <td style="text-align: center;"><?php echo $tecnologia_sistemas; ?></td>
        </tr>
    </tbody>
</table>