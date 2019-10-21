<?php if (isset($_SESSION['admin_logged_in'])): ?>
<?php include_once 'classes/general.php'; ?>

    <div class="main_table_container">

        <table id="repository_datatable" class="table table-sm table-striped table-bordered" cellspacing="0">
            <thead>
                <tr>
                    <th class="datatable_name">Name</th>
                    <th class="datatable_art">Art</th>
                    <th class="datatable_typ">Typ</th>
                    <th class="datatable_verbunden">Verbunden mit</th>
                    <th class="datatable_anderungs">Änderungs-datum</th>
                    <th class="datatable_material">Material nummer</th>
                    <th class="datatable_tag">Tag- Beschreibung</th>
                    <th class="datatable_anzahl">Anzahl der Downloads</th>
                    <th class="datatable_link">Link</th>
                    <th class="datatable_upload">Upload</th>
                    <th class="datatable_delete"><span><i class="pe-7s-trash"></i></span></th>
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
                    <td class="main_table_column repository_col_1 datatable_name" data-id="<?=$repository["id"]?>"><span><?=$repository["name"]?></span></td>
                    <td class="main_table_column repository_col_2 datatable_art" data-id="<?=$repository["id"]?>"><span><?=$repository["art"]?></span></td>
                    <td class="main_table_column repository_col_3 datatable_typ" data-id="<?=$repository["id"]?>"><span><?=$repository["typ"]?></span></td>
                    <td class="main_table_column repository_col_4 datatable_verbunden" data-id="<?=$repository["id"]?>"><span><?=$repository["relations"]?></span></td>
                    <td class="main_table_column repository_col_5 datatable_anderungs" data-id="<?=$repository["id"]?>"><span><?=$repository["modification_date"]?></span></td>
                    <td class="main_table_column repository_col_6 datatable_material" data-id="<?=$repository["id"]?>"><span><?=getRepositoryMaterialNumber($repository["id"])?></span></td>
                    <td class="main_table_column repository_col_7 datatable_tag" data-id="<?=$repository["id"]?>"><span><?=$repository["tags"]?></span></td>
                    <td class="main_table_column repository_col_8 datatable_anzahl" data-id="<?=$repository["id"]?>"><span><?=$repository["downloads_counter"]?></span></td>
                    <td class="main_table_column repository_col_9 datatable_link select_lang_button" data-id="<?=$repository["id"]?>">
                        <span>
                            <a href="<?=$repository["pdf_link"]?>" target="_blank" title="<?=$repository["pdf_link"]?>"><i class="pe-7s-next-2"></i></a>
                            <div class="langs_container pdf_container">
                                <div class="dmc_arrow_up arrow_links"></div>
                                <div class="langs_content pdf_links"><a href="<?=$repository["pdf_link"]?>" target="_blank" title="<?=$repository["pdf_link"]?>"><?=$repository["pdf_link"]?></a></div>
                            </div>
                        </span>
                    </td>
                    <td class="main_table_column datatable_upload repository_col_10" data-id="<?=$repository["id"]?>"><span><i class="pe-7s-cloud-upload"></i> Hochladen</span></td>
                    <td class="main_table_column datatable_delete repository_col_11" data-id="<?=$repository["id"]?>"><span><input type="checkbox" class="repository_remove_checkbox" name="repository_remove" value="<?=$repository["id"]?>"></span></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php endif; ?>