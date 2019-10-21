<?php if (isset($_SESSION['admin_logged_in'])): ?>
    <?php include_once 'classes/general.php'; ?>

    <div class="main_table_container">

        <table id="repository_datatable" class="table table-sm table-striped table-bordered" cellspacing="0">
            <thead>
            <tr>
                <th>Name</th>
                <th>Art</th>
                <th>Typ</th>
                <th>Verbund en mit</th>
                <th>Änderungs-datum</th>
                <th>Material nummer</th>
                <th>Tag- Beschreibung</th>
                <th>Anzahl der Downloads</th>
                <th>Link</th>
                <th>Upload</th>
                <th><span><i class="pe-7s-trash"></i></span></th>
            </tr>
            </thead>
            <tbody>

            <?php
            $id_lang = $_SESSION['lang'];
            $repositories = getRepositories($id_lang);
            ?>
            <?php if(count($repositories) > 0): ?>
                <?php foreach($repositories as $repository): ?>
                    <tr>
                        <td class="main_table_column repository_col_1" data-id="<?=$repository["id"]?>"><span><?=$repository["name"]?></span></td>
                        <td class="main_table_column repository_col_2" data-id="<?=$repository["id"]?>"><span><?=$repository["art"]?></span></td>
                        <td class="main_table_column repository_col_3" data-id="<?=$repository["id"]?>"><span><?=$repository["typ"]?></span></td>
                        <td class="main_table_column repository_col_4" data-id="<?=$repository["id"]?>"><span><?=$repository["relations"]?></span></td>
                        <td class="main_table_column repository_col_5" data-id="<?=$repository["id"]?>"><span><?=$repository["modification_date"]?></span></td>
                        <td class="main_table_column repository_col_6" data-id="<?=$repository["id"]?>"><span><?=getRepositoryMaterialNumber($repository["id"])?></span></td>
                        <td class="main_table_column repository_col_7" data-id="<?=$repository["id"]?>"><span><?=$repository["tags"]?></span></td>
                        <td class="main_table_column repository_col_8" data-id="<?=$repository["id"]?>"><span><?=$repository["downloads_counter"]?></span></td>
                        <td class="main_table_column repository_col_9 select_lang_button" data-id="<?=$repository["id"]?>">
                        <span>
                            <a href="<?=$repository["pdf_link"]?>" target="_blank" title="<?=$repository["pdf_link"]?>"><i class="pe-7s-next-2"></i></a>
                            <div class="langs_container pdf_container">
                                <div class="dmc_arrow_up arrow_links"></div>
                                <div class="langs_content pdf_links"><a href="<?=$repository["pdf_link"]?>" target="_blank" title="<?=$repository["pdf_link"]?>"><?=$repository["pdf_link"]?></a></div>
                            </div>
                        </span>
                        </td>
                        <td class="main_table_column repository_col_10" data-id="<?=$repository["id"]?>"><span><i class="pe-7s-cloud-upload"></i> Hochladen</span></td>
                        <td class="main_table_column repository_col_11" data-id="<?=$repository["id"]?>"><span><input type="checkbox" class="repository_remove_checkbox" name="repository_remove" value="<?=$repository["id"]?>"></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>