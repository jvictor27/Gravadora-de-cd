<div class="content cat_list">

    <section>

        <h1>Cantores:</h1>

        <?php
        $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);
        if ($empty):
            WSErro("Você tentou editar um cantor que não existe no sistema!", WS_INFOR);
        endif;

        $delCat = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
        if ($delCat):
            require ('_models/AdminCantor.class.php');
            $deletar = new AdminCantor;
            $deletar->ExeDelete($delCat);
            
            WSErro($deletar->getError()[0], $deletar->getError()[1]);
        endif;


        $readSes = new Read;
        $readSes->ExeRead("ws_cantor", "ORDER BY cantor_title ASC");
        if (!$readSes->getResultado()):

        else:
            foreach ($readSes->getResultado() as $ses):
                extract($ses);

                $readCDs = new Read;
                $readCDs->ExeRead("ws_cds", "WHERE cd_cantor = :parent", "parent={$cantor_id}");

                $readCats = new Read;
                $readCats->ExeRead("ws_cantor", "ORDER BY cantor_title ASC");

                $countSesCDs = $readCDs->getRowCount();
                // $countSesCats = $readCats->getRowCount();
                ?>
                <section>

                    <header>
                        <h1><strong><?= $cantor_title; ?></strong>  <span>( <?= $countSesCDs; ?> cd(s) )</span></h1>

                        <ul class="info post_actions">
                            <li><a class="act_edit" href="painel.php?exe=cantor/update&catid=<?= $category_id; ?>" title="Editar">Editar</a></li>
                            <li><a class="act_delete" href="painel.php?exe=cantor/index&delete=<?= $category_id; ?>" title="Excluir">Deletar</a></li>
                        </ul>
                    </header>

                </section>
                <?php
            endforeach;
        endif;
        ?>

        <div class="clear"></div>
    </section>

    <div class="clear"></div>
</div> <!-- content home -->