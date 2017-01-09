<div class="content form_create">

    <article>

        <header>
            <h1>Atualizar CD:</h1>
        </header>

        <?php
        $cd = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $cdid = filter_input(INPUT_GET, 'cdid', FILTER_VALIDATE_INT);

        if (isset($cd) && $cd['SendCDForm']):
            //imagem
            $cd['cd_cover'] = ($_FILES['cd_cover']['tmp_name'] ? $_FILES['cd_cover'] : 'null');
            unset($cd['SendCDForm']);

            require('_models/AdminCDs.class.php');
            $cadastra = new AdminCDs;
            $cadastra->ExeUpdate($cdid, $cd);


            WSErro($cadastra->getError()[0], $cadastra->getError()[1]);

        else:
            $read = new Read();
            $read->ExeRead("ws_cds", "WHERE cd_id = :id", "id={$cdid}");
            if (!$read->getResultado()):
                header('Location painel.php?exe=cds/index&empty=true');
            else:
                $cd = $read->getResultado()[0];
                $cd['cd_date'] = date('d/m/Y H:i:s', strtotime($cd['cd_date']));
            endif;

        endif;

        $checkCreate = filter_input(INPUT_GET, 'create', FILTER_VALIDATE_BOOLEAN);
        if ($checkCreate && empty($cadastra)):
            WSErro("O cd <b>{$cd['cd_title']}</b> foi atualizado com sucesso no sistema!", WS_ACCEPT);
        endif;
        ?>


        <form name="CDForm" action="" method="post" enctype="multipart/form-data">

            <label class="label">
                <span class="field">Enviar Capa:</span>
                <input type="file" name="cd_cover" />
            </label>

            <label class="label">
                <span class="field">Titulo:</span>
                <input type="text" name="cd_title" value="<?php if (isset($cd['cd_title'])) echo $cd['cd_title']; ?>"/>
            </label>

            <div class="label_line">

                <label class="label_small">
                    <span class="field">Data:</span>
                    <input type="text" class="formDate center" name="cd_date" value="<?php if (isset($cd['cd_date'])): echo $cd['cd_date']; else: echo date('d/m/Y H:i:s'); endif;
        ?>" />
                </label>

                <label class="label_small">
                    <span class="field">Cantor:</span>
                    <select name="cd_cantor">
                        <option value=""> Selecione a categoria: </option>

                        <?php
                        $readSes = new Read;
                        $readSes->ExeRead("ws_cantor", "ORDER BY cantor_title ASC");
                        if ($readSes->getRowCount() >= 1):
                            foreach ($readSes->getResultado() as $ses):
                                echo "<option  value=\"{$ses['cantor_id']}\"> {$ses['cantor_title']} </option>";
                            endforeach;
                        endif;
                        ?>

                    </select>
                </label>

            <div class="btn_cad_cd">    
                <input type="submit" class="btn blue" value="Atualizar" name="SendCDForm" />
            </div>
            

        </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->