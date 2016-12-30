<table style="width: 100%">
    <tr style="width: 50%;height: 70px">
        <td><img src="<?php echo $data["Marca"]; ?>" height="70px">
        </td>

        <td style="width: 58%;height: 70px;text-align: right">
            <img src="themes/serviciospublicos/images/logoAniversario.png" height="50px"></td>
    </tr>
</table>




<table>
    <tr>
        <td style='text-align:left;border-right:2px solid #0C359C;padding-right:10px;'>
            <span style="text-transform: uppercase;color: #0C359C;font-size: 1.3em;">Aniversario de <?php echo $data["Localidad"]; ?></span> <br><br>
            <span class="labelLocalidad">Localidad: </span><span><?php echo $data["Localidad"]; ?></span><br />
            <span class="labelLocalidad">Aniversario: </span><span><?php echo $data["Aniversario"]; ?></span><br />
            <span class="labelLocalidad">Categor&iacute;a:</span><span> <?php echo $data["Categoria"]; ?></span><br />
            <span class="labelLocalidad">Autoridades:</span><span> <?php echo $data["Autoridades"]; ?></span><br />
            <span class="labelLocalidad">Habitantes:</span><span> <?php echo $data["Habitantes"]; ?></span><br /> 
            <span class="labelLocalidad">Cantidad de Obras:</span><span> <?php echo $data["CantidadObras"]; ?></span><br />
            <span class="labelLocalidad">Monto Total: </span><span><?php echo $data["MontoTotal"]; ?></span><br />
        </td>

        <td align="center" width="250px" style="border-right: 2px solid #0C359C;">
            <img height="200px" src="<?php echo $data["FotoEscudo"]; ?>" /><br>
            <span style="font-size: 12px;color:#0C359C;font-weight: bold">Escudo de la localidad</span>
        </td>
        <td align="center" width="250px" style="border-right: 2px solid #0C359C;">
            <img height="200px" src="<?php echo $data["FotoLocalidad"]; ?>" /><br>
                <span style="font-size: 12px;color:#0C359C;font-weight: bold">Fotograf&iacute;a de la localidad</span>
        </td>
        <td align="center" width="250px" style="border-right: 2px solid #0C359C;">
            <img height="200px" src="<?php echo $data["FotoAutoridad"]; ?>" /><br>
            <span style="font-size: 12px;color:#0C359C;font-weight: bold"><?php echo $data["Autoridades"];  ?></span>
        </td>
    </tr>
<!--	<tr>
            <td align="center">
                    <span style="font-size: 12px;">Escudo de la localidad</span>
            </td>
            <td align="center">
                    <span style="font-size: 12px;">Fotograf&iacute;a de la localidad</span>
            </td>
            <td align="center">
                    <span style="font-size: 12px;"><?php // echo $data["Autoridades"];  ?></span>
            </td>
    </tr>-->
</table>

<br />

<table>
    <thead>
        <tr>
            <?php if (in_array(1, $data["Columns"])) { ?><th align="center" valign="top" width="150px">Organismo</th><?php } ?>
            <?php if (in_array(2, $data["Columns"])) { ?><th align="center" valign="top" width="450px">Obra</th><?php } ?>
            <?php if (in_array(3, $data["Columns"])) { ?><th align="center" valign="top" width="120px">Monto</th><?php } ?>
            <?php if (in_array(4, $data["Columns"])) { ?><th align="center" valign="top" width="100">Nivel de Ejecuci&oacute;n</th><?php } ?>
            <?php if (in_array(5, $data["Columns"])) { ?><th align="center" valign="top" width="120">Fecha de Inauguraci&oacute;n</th><?php } ?>
            <?php if (in_array(6, $data["Columns"])) { ?><th align="center" valign="top" width="100">Estado</th><?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data["Obras"] as $d) {
            ?>
            <tr>
                <?php if (in_array(1, $data["Columns"])) { ?><td align="left" valign="top"><?php echo $d["Organismo"]; ?></td><?php } ?>
                <?php if (in_array(2, $data["Columns"])) { ?><td align="left" valign="top"><?php echo $d["Obra"]; ?></td><?php } ?>
                <?php if (in_array(3, $data["Columns"])) { ?><td align="right" valign="top"><?php echo ($d["Monto"] != '' ? number_format($d["Monto"], 2, ",", ".") : ''); ?></td><?php } ?>
                <?php if (in_array(4, $data["Columns"])) { ?><td align="right" valign="top"><?php echo ($d["PorcentajeAvance"] != '' ? number_format(floatval($d["PorcentajeAvance"]), 2, ",", ".") : ''); ?></td><?php } ?>
                <?php if (in_array(5, $data["Columns"])) { ?><td align="center" valign="top"><?php echo $d["FechaInauguracion"]; ?></td><?php } ?>
                <?php if (in_array(6, $data["Columns"])) { ?><td align="left" valign="top"><?php echo $d["Estado"]; ?></td><?php } ?>

            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
