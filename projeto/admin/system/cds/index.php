<div class="content list_content">

    <section>

        <h1>CD's:</h1>

        <?php
        $empty = filter_input(INPUT_GET, 'empty', FILTER_VALIDATE_BOOLEAN);

        if ($empty):
            WSErro("Oppsss: Você tentou editar um cd que não existe no sistema!", WS_INFOR);
        endif;

        $action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
        if ($action):
            require ('_models/AdminCDs.class.php');
            $cdAction = filter_input(INPUT_GET, 'cd', FILTER_VALIDATE_INT);
            $cdUpdate = new AdminCDs;
            
            switch ($action):
                case 'delete':
                    $cdUpdate->ExeDelete($cdAction);
                        WSErro($cdUpdate->getError()[0], $cdUpdate->getError()[1]);
                    break;

                default:
                    WSErro("Ação não identificada pelo sistema, favor utilize os botões.", WS_ALERT);
            endswitch;
        endif;

        $cdi = 0;
        $getPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
        $Pager = new Pager('painel.php?exe=cds/index&page=');
        $Pager->ExePager($getPage, 4);

        $readCDs = new Read();
        $readCDs->ExeRead("ws_cds", "ORDER BY cd_date DESC LIMIT :limit OFFSET :offset", "limit={$Pager->getLimite()}&offset={$Pager->getOffset()}");
        if ($readCDs->getResultado()):
            foreach ($readCDs->getResultado() as $cd):
                $cdi++;
                extract($cd);
                // $status = (!$cd_status ? 'style="background: #fffed8"' : '');
                ?>
                <article<?php if ($cdi % 2 == 0) echo ' class="right"'; ?> >

                    <div class="img thumb_small">
                        <?= Check::Image('../uploads/' . $cd_cover, $cd_title, 120, 70); ?>
                    </div>

                    <h1><?= $cd_title?></h1>
                    <ul class="info post_actions">
                    <?php
                        $readCats = new Read();
                        $readCats->ExeRead("ws_cantor", "WHERE cantor_id = $cd_cantor");
                        $resultado = $readCats->getResultado();
                        foreach ($readCats->getResultado() as $cat):
                        extract($cat);
                        echo "<li><strong>Cantor:</strong>$cantor_title</li>";
                        endforeach;
                    ?>
                        <li><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($cd_date)); ?>Hs</li>
                        <li><a class="act_edit" href="painel.php?exe=cds/update&cdid=<?= $cd_id; ?>" title="Editar">Editar</a></li>
                        <li><a class="act_delete" href="painel.php?exe=cds/index&cd=<?= $cd_id; ?>&action=delete" title="Excluir">Deletar</a></li>
                    </ul>

                </article>
                <?php
            endforeach;

        else:
            $Pager->ReturnPage();
            WSErro("Desculpe ainda não existem cd's cadastrados", WS_INFOR);
        endif;
        ?>

        <div class="clear"></div>
    </section>

    <?php
    $Pager->ExePaginator("ws_cds");
    echo $Pager->getPaginator();
    ?>

    <!--    <div class="paginator">
            <a href="#">Primeira Página</a>
            <a href="#">1</a>
            <a href="#">2</a>
            <span class="atv">3</span>
            <a href="#">4</a>
            <a href="#">5</a> 
            <a href="#">Última Página</a>
        </div>-->

    <div class="clear"></div>
</div> <!-- content home -->