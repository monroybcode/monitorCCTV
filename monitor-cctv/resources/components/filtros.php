<?php
//include '../connection/conexion.php';
//$mysqli->query("SET NAMES 'UTF8'");
?>
<form id="search-filter" class="form-inline" style="padding: 0 5px;">    
    <div col-lg-12 col-md-12 col-sm-12 col-sx-12>
        <b id="mostrando_de_hasta" class="pull-left hidden-xs hidden-sm hidden-md"></b>

        <?php
        if (isset($_SESSION['funciones'])) {
            $array_funciones = $_SESSION['funciones'];


            if (in_array("ver_filtro_folio", $array_funciones)) {
                ?>
                <input type = "number" id = "filtro_folio_t" name = "filtro_folio_t" class = "form-control input-xs" placeholder = "Folio" value = "<?php
                if (isset($_SESSION['filtro_folio'])) {
                    echo $_SESSION['filtro_folio'];
                } else if (isset($_COOKIE['filtro_folio'])) {
                    echo $_COOKIE['filtro_folio'];
                }
                ?>">
                       <?php
                   }
                   ?>


            <?php
            if (in_array("ver_filtro_estatus", $array_funciones)) {
                ?>
                <select class="form-control input-xs" id="filtro_estatus_t" name="filtro_estatus_t">
                    <option value="">Estatus</option>
                    <?php
                    $sql = "select * from catalogo_valor where catalogo=2;";
                    $resultSet = $mysqli->query($sql);

                    while ($fila = $resultSet->fetch_assoc()) {
                        if (isset($_SESSION['filtro_estatus']) && $_SESSION['filtro_estatus'] == $fila['id']) {
                            echo "<option value='" . $fila['id'] . "' selected>" . $fila['descripcion'] . "</option>";
                        } else if (isset($_COOKIE['filtro_estatus']) && $_COOKIE['filtro_estatus'] == $fila['id']) {
                            echo "<option value='" . $fila['id'] . "' selected>" . $fila['descripcion'] . "</option>";
                        } else {
                            echo "<option value='" . $fila['id'] . "'>" . $fila['descripcion'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            }
            ?>

            <?php
            if (in_array("ver_filtro_hospital", $array_funciones)) {
                ?>
                <select class="form-control input-xs" id="filtro_hospital_t" name="filtro_hospital_t">
                    <option value="">Hospital</option>
                    <?php
                    $sql = "select h.id, h.nombre
                            from usuario_hospital uh
                            join hospital h on uh.hospital = h.id
                            where uh.usuario = '" . $_SESSION['id_usuario'] . "'";
                    $resultSet = $mysqli->query($sql);

                    while ($fila = $resultSet->fetch_assoc()) {
                        if (isset($_SESSION['filtro_hospital']) && $_SESSION['filtro_hospital'] == $fila['id']) {
                            echo "<option value='" . $fila['id'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else if (isset($_COOKIE['filtro_hospital']) && $_COOKIE['filtro_hospital'] == $fila['id']) {
                            echo "<option value='" . $fila['id'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else {
                            echo "<option value='" . $fila['id'] . "'>" . $fila['nombre'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            }
            ?>

            <?php
          /*  if (in_array("ver_filtro_usuario", $array_funciones)) {
                ?>
                <select class="form-control input-xs" id="filtro_usuario_t" name="filtro_usuario_t">
                    <option value="">Usuario</option>
                    <?php
                    $sql = "select * from usuarios where ind_activo=1;";
                    $resultSet = $mysqli->query($sql);

                    while ($fila = $resultSet->fetch_assoc()) {
                        if (isset($_SESSION['filtro_usuario']) && $_SESSION['filtro_usuario'] == $fila['id_usuario']) {
                            echo "<option value='" . $fila['id_usuario'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else if (isset($_COOKIE['filtro_usuario']) && $_COOKIE['filtro_usuario'] == $fila['id_usuario']) {
                            echo "<option value='" . $fila['id_usuario'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else {
                            echo "<option value='" . $fila['id_usuario'] . "'>" . $fila['nombre'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            }
           * */
           
            ?>


            <?php
            if (in_array("ver_filtro_categoria", $array_funciones)) {
                ?>
                <select class="form-control input-xs" id="filtro_categoria_t" name="filtro_categoria_t">
                    <option value="">Categoría</option>
                    <?php
                  
                  
                        $sql = "SELECT 
                                usuario_categorias.*, categoria.nombre
                            FROM
                                usuario_categorias
                                    inner join
                                categoria ON id_categoria = categoria.id
                                where id_usuario='" . $_SESSION['id_usuario'] . "'";
                    
                    echo $sql;
                    $resultSet = $mysqli->query($sql);

                    while ($fila = $resultSet->fetch_assoc()) {
                        if ($_SESSION['filtro_categoria'] && $_SESSION['filtro_categoria'] == $fila['id']) {
                            echo "<option value='" . $fila['id_categoria'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else if ($_COOKIE['filtro_categoria'] && $_COOKIE['filtro_categoria'] == $fila['id']) {
                            echo "<option value='" . $fila['id_categoria'] . "' selected>" . $fila['nombre'] . "</option>";
                        } else {
                            echo "<option value='" . $fila['id_categoria'] . "'>" . $fila['nombre'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            }
            ?>

            <?php
            if (in_array("ver_filtro_categoria", $array_funciones)) {
                ?>
                <select class="form-control input-xs" id="filtro_areas_t" name="filtro_areas_t">
                    <option value="">Areas</option>
                    <?php
                    $sql = "SELECT 
                                usuario_grupos.*, areas.nombre_area
                            FROM
                               usuario_grupos
                                    inner join
                                areas ON id_grupo = areas.idareas
                                where id_usuario='" . $_SESSION['id_usuario'] . "'";

                    echo $sql;
                    $resultSet = $mysqli->query($sql);

                    while ($fila = $resultSet->fetch_assoc()) {
                        if ($_SESSION['filtro_area'] && $_SESSION['filtro_area'] == $fila['id']) {
                            echo "<option value='" . $fila['id_grupo'] . "' selected>" . $fila['nombre_area'] . "</option>";
                        } else if ($_COOKIE['filtro_area'] && $_COOKIE['filtro_area'] == $fila['id']) {
                            echo "<option value='" . $fila['id_grupo'] . "' selected>" . $fila['nombre_area'] . "</option>";
                        } else {
                            echo "<option value='" . $fila['id_grupo'] . "'>" . $fila['nombre_area'] . "</option>";
                        }
                    }
                    ?>
                </select>
                <?php
            }
            ?>


            <?php
            if (in_array("ver_btn_filtrar", $array_funciones)) {
                ?>
                <button class="btn btn-link btn-aplica-filtros-tickets btn-sm no-padding" onclick="aplica_filtros('inicio');
                                return false;" type="submit" style="font-size: 20px;" title="Aplicar filtros"><span class="glyphicon glyphicon-filter"></span></button>

                <?php
            }
        }
        ?>

    </div>



</form>
