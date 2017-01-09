<div class="content form_create">

    <article>

        <header>
            <h1>Cadastrar cd:</h1>
        </header>

        <?php
        $cd = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (isset($cd) && $cd['SendCDForm']):
            //$cd['post_status'] = ($post['SendPostForm'] == 'Cadastrar' ? '0' : '1');
            //imagem
            $cd['cd_cover'] = ($_FILES['cd_cover']['tmp_name'] ? $_FILES['cd_cover'] : null);
            unset($cd['SendCDForm']);

            // ../../->foi removido por conta do frontcontroller
            require('_models/AdminCDs.class.php');
            $cadastra = new AdminCDs;
            $cadastra->ExeCreate($cd);

            if ($cadastra->getResultado()):
                //Enviar a galeria caso exista.
                // if (!empty($_FILES['gallery_covers']['tmp_name'])):
                //     $sendGallery = new AdminCDs;
                //     $sendGallery->gbSend($_FILES['gallery_covers'], $cadastra->getResultado());

                // var_dump($sendGallery); 
                // endif;

                header('Location: painel.php?exe=cds/update&create=true&cdid=' . $cadastra->getResultado());
            else:
                WSErro($cadastra->getError()[0], $cadastra->getError()[1]);
            endif;

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
                    <input type="text" class="formDate center" name="cd_date" value="<?php
                    if (isset($cd['cd_date'])): echo $cd['cd_date'];
                    else: echo date('d/m/Y H:i:s');
                    endif;
                    ?>" />
                </label>

                <label class="label_small">
                    <span class="field">Cantor:</span>
                    <select name="cd_cantor">
                        <option value=""> Selecione o cantor: </option>

                        <?php
                        $readSes = new Read;
                        $readSes->ExeRead("ws_cantor", "ORDER BY cantor_title ASC");
                        if ($readSes->getRowCount() >= 1):
                            foreach ($readSes->getResultado() as $ses):
                                echo "<option  value=\"{$ses['cantor_id']}\"> {$ses['cantor_title']} </option>";
                                // $readCat = new Read;
                                // $readCat->ExeRead("ws_categories", "WHERE category_parent = :parent ORDER BY category_title ASC", "parent={$ses['category_id']}");
                                // if ($readCat->getRowCount() >= 1):
                                //     foreach ($readCat->getResultado() as $cat):
                                //         echo "<option ";

                                //         if ($post['post_category'] == $cat['category_id']):
                                //             echo "selected=\"selected\" ";
                                //         endif;

                                //         echo "value=\"{$cat['category_id']}\"> &raquo;&raquo; {$cat['category_title']} </option>";
                                //     endforeach;
                                // endif;
                            endforeach;
                        endif;
                        ?>

                    </select>
                </label>


            </div><!--/line-->


            <div class="btn_cad_cd">
                <input type="submit" class="btn blue" value="Cadastrar" name="SendCDForm" />
            </div>
            

        </form>

    </article>

    <div class="clear"></div>
</div> <!-- content home -->